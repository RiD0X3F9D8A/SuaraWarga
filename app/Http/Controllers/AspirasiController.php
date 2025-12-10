<?php

namespace App\Http\Controllers;

use App\Models\Aspirasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AspirasiController extends Controller
{
    // ========== UNTUK WARGA ==========
    
    public function create()
    {
        return view('aspirasi.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:200|min:5',
            'isi' => 'required|string|min:10'
        ]);
        
        Aspirasi::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'user_id' => Auth::id(),
            'status' => 'submitted'
        ]);
        
        return redirect()->route('aspirasi.my')
            ->with('success', 'Aspirasi berhasil dikirim!');
    }
    
    public function myAspirasi(Request $request)
    {
        $query = Aspirasi::where('user_id', Auth::id());

        // Filter status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $aspirasis = $query->orderBy('created_at', 'desc')->get();
            
        return view('aspirasi.my', compact('aspirasis'));
    }

    public function publicIndex()
    {
        $aspirasis = Aspirasi::with('user')
            ->whereIn('status', ['approved', 'completed'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('aspirasi.public', compact('aspirasis'));
    }
    
    public function show($id)
    {
        $aspirasi = Aspirasi::with(['user'])->findOrFail($id);
        
        if (Auth::user()->role !== 'admin' && $aspirasi->user_id !== Auth::id()) {
            return redirect()->route('aspirasi.my')
                ->with('error', 'Akses ditolak.');
        }
        
        return view('aspirasi.show', compact('aspirasi'));
    }
    
    // ========== UNTUK ADMIN ==========
    
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/dashboard')->with('error', 'Akses ditolak. Hanya untuk admin.');
        }
        
        // QUERY SEDERHANA - tanpa kolom yang bermasalah
        $aspirasis = Aspirasi::with(['user'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $stats = [
            'total' => Aspirasi::count(),
            'submitted' => Aspirasi::where('status', 'submitted')->count(),
            'approved' => Aspirasi::where('status', 'approved')->count(),
            'rejected' => Aspirasi::where('status', 'rejected')->count(),
            'completed' => Aspirasi::where('status', 'completed')->count()
        ];
        
        return view('aspirasi.index', compact('aspirasis', 'stats'));
    }
    
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        
        $aspirasi = Aspirasi::findOrFail($id);
        $aspirasi->delete();
        
        return redirect()->route('aspirasi.index')
            ->with('success', 'Aspirasi berhasil dihapus.');
    }
    
    public function approve($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        
        $aspirasi = Aspirasi::findOrFail($id);
        $aspirasi->update([
            'status' => 'approved',
            'admin_id' => Auth::id()
        ]);
        
        return redirect()->back()->with('success', 'Aspirasi berhasil disetujui!');
    }
    
    public function reject(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        
        $request->validate([
            'alasan_penolakan' => 'required|string|min:5'
        ]);
        
        $aspirasi = Aspirasi::findOrFail($id);
        $aspirasi->update([
            'status' => 'rejected',
            'admin_id' => Auth::id(),
            'alasan_penolakan' => $request->alasan_penolakan
        ]);
        
        return redirect()->back()->with('success', 'Aspirasi berhasil ditolak.');
    }
    
    public function respond(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        
        $request->validate([
            'admin_response' => 'required|string|min:5'
        ]);
        
        $aspirasi = Aspirasi::findOrFail($id);
        
        $is_update = !empty($aspirasi->admin_response);
        
        $updateData = [
            'admin_response' => $request->admin_response,
            'admin_id' => Auth::id(),
            'status' => 'completed',
            'responded_at' => now()
        ];
        
        if ($is_update) {
            $updateData['is_response_edited'] = true;
        }
        
        $aspirasi->update($updateData);
        
        $message = $is_update ? 'Tanggapan berhasil diperbarui!' : 'Tanggapan berhasil dikirim!';
        
        return redirect()->back()->with('success', $message);
    }
    
    public function markInProgress($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        
        $aspirasi = Aspirasi::findOrFail($id);
        $aspirasi->update(['status' => 'in_progress']);
        
        return redirect()->back()->with('success', 'Status diubah menjadi In Progress');
    }
    
    public function markCompleted($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        
        $aspirasi = Aspirasi::findOrFail($id);
        $aspirasi->update(['status' => 'completed']);
        
        return redirect()->back()->with('success', 'Status diubah menjadi Completed');
    }
}