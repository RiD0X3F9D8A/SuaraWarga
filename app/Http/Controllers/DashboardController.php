<?php

namespace App\Http\Controllers;

use App\Models\Aspirasi;
use App\Models\User;
use App\Models\VotingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Dashboard untuk Warga
    public function warga()
    {
        $user = Auth::user();
        $aspirasi_count = Aspirasi::where('user_id', $user->id)->count();
        $voting_active = VotingSession::where('selesai', '>', now())->count();
        $aspirasi_terbaru = Aspirasi::where('user_id', $user->id)->latest()->take(3)->get();
        
        return view('dashboard.warga', compact('user', 'aspirasi_count', 'voting_active', 'aspirasi_terbaru'));
    }

    // Dashboard untuk Admin
    public function admin()
    {
        $total_warga = User::where('role', 'warga')->count();
        $aspirasi_baru = Aspirasi::where('status', 'terkirim')->count();
        $voting_aktif = VotingSession::where('selesai', '>', now())->count();
        $aspirasi_terbaru = Aspirasi::with('user')->latest()->take(5)->get();
        
        return view('dashboard.admin', compact('total_warga', 'aspirasi_baru', 'voting_aktif', 'aspirasi_terbaru'));
    }

    // Redirect berdasarkan role
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return redirect('/dashboard/admin');
            } else {
                return redirect('/dashboard/warga');
            }
        }
        return redirect('/login');
    }
}