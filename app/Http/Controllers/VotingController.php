<?php

namespace App\Http\Controllers;

use App\Models\VotingSession;
use App\Models\VotingOption;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VotingController extends Controller
{
    // ============================================
    // PUBLIC METHODS
    // ============================================

    /**
     * Menampilkan daftar voting aktif untuk warga
     */
    public function index()
    {
        $votings = VotingSession::with(['creator', 'options'])
            ->where('is_public', true)
            ->where('selesai', '>', now())
            ->orderBy('mulai', 'desc')
            ->get();
            
        return view('voting.index', compact('votings'));
    }

    /**
     * Menampilkan detail voting
     */
    public function show($id)
    {
        $voting = VotingSession::with(['creator', 'options', 'options.votes'])
            ->findOrFail($id);
            
        $user_has_voted = Auth::check() ? 
            Vote::where('session_id', $id)
                ->where('user_id', Auth::id())
                ->exists() : false;
                
        return view('voting.show', compact('voting', 'user_has_voted'));
    }

    /**
     * Menampilkan hasil voting
     */
    public function results($id)
    {
        $voting = VotingSession::with(['options', 'options.votes'])
            ->findOrFail($id);
            
        $total_votes = $voting->votes()->count();
        
        return view('voting.results', compact('voting', 'total_votes'));
    }

    // ============================================
    // VOTING ACTIONS (PROTECTED)
    // ============================================

    /**
     * Form untuk memberikan suara
     */
    public function voteForm($id)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu untuk voting.');
        }

        $voting = VotingSession::with('options')
            ->where('is_public', true)
            ->where('selesai', '>', now())
            ->findOrFail($id);
        
        if (!$voting->allow_multiple_choice) {
            return redirect("/voting/{$id}");
        }
        
        // PERBAIKAN: Ganti $voting->hasVoted() dengan query langsung
        $hasVoted = Vote::where('session_id', $id)
            ->where('user_id', Auth::id())
            ->exists();
            
        if ($hasVoted) {
            return redirect("/voting/{$id}")->with('error', 'Anda sudah memberikan suara pada voting ini.');
        }

        return view('voting.vote', compact('voting'));
    }

    /**
     * Proses submit voting (POST)
     */
    public function submitVote(Request $request, $session_id)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu untuk voting.');
        }

        $request->validate([
            'option_id' => 'required|exists:voting_options,id'
        ]);

        // Cek apakah user sudah vote di session ini
        $existing_vote = Vote::where('session_id', $session_id)
            ->where('user_id', Auth::id())
            ->exists();
            
        if ($existing_vote) {
            return back()->with('error', 'Anda sudah memberikan suara pada voting ini.');
        }

        // Cek apakah voting masih aktif
        $voting = VotingSession::findOrFail($session_id);
        if (!$voting->isActive()) {
            return back()->with('error', 'Voting sudah berakhir.');
        }

        // Simpan vote
        Vote::create([
            'session_id' => $session_id,
            'user_id' => Auth::id(),
            'option_id' => $request->option_id,
            'cast_at' => now(),
            'ip_address' => $request->ip()
        ]);

        return redirect("/voting/{$session_id}")->with('success', 'Suara Anda telah tercatat!');
    }

    // ============================================
    // ADMIN METHODS
    // ============================================

    /**
     * Kelola voting (halaman admin)
     */
    public function manageSessions()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/voting')->with('error', 'Akses ditolak. Hanya untuk admin.');
        }

        $votings = VotingSession::with(['creator', 'options'])
            ->withCount('votes')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('voting.manage', compact('votings'));
    }

    /**
     * Form buat voting baru
     */
    public function createSession()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/voting')->with('error', 'Akses ditolak. Hanya untuk admin.');
        }
        
        return view('voting.create-session');
    }

    /**
     * Simpan voting baru
     */
    public function storeSession(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/voting')->with('error', 'Akses ditolak. Hanya untuk admin.');
        }

        $request->validate([
            'judul' => 'required|max:200',
            'deskripsi' => 'nullable',
            'mulai' => 'required|date',
            'selesai' => 'required|date|after:mulai',
            'options' => 'required|array|min:2'
        ]);

        DB::beginTransaction();
        try {
            // Buat voting session
            $session = VotingSession::create([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'created_by' => Auth::id(),
                'mulai' => $request->mulai,
                'selesai' => $request->selesai,
                'is_public' => true,
                'allow_multiple_choice' => $request->has('allow_multiple'),
                'is_anonymous' => false
            ]);

            // Buat opsi voting
            foreach ($request->options as $index => $option) {
                if (!empty(trim($option))) {
                    VotingOption::create([
                        'session_id' => $session->id,
                        'pilihan_label' => trim($option),
                        'deskripsi_opsi' => $request->descriptions[$index] ?? null,
                        'urutan' => $index
                    ]);
                }
            }

            DB::commit();
            return redirect('/voting')->with('success', 'Sesi voting berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat voting: ' . $e->getMessage());
        }
    }

    /**
     * Form edit voting
     */
    public function editSession($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/voting')->with('error', 'Akses ditolak. Hanya untuk admin.');
        }

        $voting = VotingSession::with('options')->findOrFail($id);
        return view('voting.edit', compact('voting'));
    }

    /**
     * Update voting
     */
    public function updateSession(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/voting')->with('error', 'Akses ditolak. Hanya untuk admin.');
        }

        $request->validate([
            'judul' => 'required|max:200',
            'deskripsi' => 'nullable',
            'mulai' => 'required|date',
            'selesai' => 'required|date|after:mulai',
        ]);

        $voting = VotingSession::findOrFail($id);
        $voting->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'mulai' => $request->mulai,
            'selesai' => $request->selesai,
        ]);

        return redirect('/voting/manage')->with('success', 'Sesi voting berhasil diperbarui!');
    }

    /**
     * Hapus voting
     */
    public function destroySession($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/voting')->with('error', 'Akses ditolak. Hanya untuk admin.');
        }

        $voting = VotingSession::findOrFail($id);
        
        // Hapus semua votes terkait
        Vote::where('session_id', $id)->delete();
        
        // Hapus semua options
        VotingOption::where('session_id', $id)->delete();
        
        // Hapus voting session
        $voting->delete();

        return redirect('/voting/manage')->with('success', 'Sesi voting berhasil dihapus!');
    }

    /**
     * Tutup voting (admin)
     */
    public function closeSession($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/voting')->with('error', 'Akses ditolak. Hanya untuk admin.');
        }

        $voting = VotingSession::findOrFail($id);
        $voting->update([
            'selesai' => now()
        ]);

        return redirect('/voting/manage')->with('success', 'Voting berhasil ditutup!');
    }

    /**
     * Alias untuk vote (POST method)
     */
    public function vote(Request $request, $session_id)
    {
        return $this->submitVote($request, $session_id);
    }

    // ============================================
    // HELPER METHODS
    // ============================================

    /**
     * Export hasil voting ke PDF
     */
    public function exportPDF($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/voting')->with('error', 'Akses ditolak. Hanya untuk admin.');
        }

        $voting = VotingSession::with(['options', 'options.votes'])
            ->findOrFail($id);
            
        // TODO: Implement PDF export
        return back()->with('info', 'Fitur export PDF sedang dalam pengembangan.');
    }

    /**
     * Export hasil voting ke Excel
     */
    public function exportExcel($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/voting')->with('error', 'Akses ditolak. Hanya untuk admin.');
        }

        $voting = VotingSession::with(['options', 'options.votes'])
            ->findOrFail($id);
            
        // TODO: Implement Excel export
        return back()->with('info', 'Fitur export Excel sedang dalam pengembangan.');
    }

    /**
     * Cek apakah user sudah voting
     */
    private function hasUserVoted($session_id, $user_id = null)
    {
        if (!$user_id) {
            $user_id = Auth::id();
        }
        
        return Vote::where('session_id', $session_id)
            ->where('user_id', $user_id)
            ->exists();
    }

}