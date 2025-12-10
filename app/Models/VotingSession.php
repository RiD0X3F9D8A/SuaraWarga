<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VotingSession extends Model
{
    use HasFactory;

    protected $table = 'voting_sessions';
    
    protected $fillable = [
        'judul', 'deskripsi', 'created_by', 'mulai', 'selesai',
        'is_public', 'allow_multiple_choice', 'is_anonymous'
    ];
    
    protected $casts = [
        'mulai' => 'datetime',
        'selesai' => 'datetime',
    ];
    
    // Relasi ke user yang membuat
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    // Alias untuk compatibility
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    // Relasi ke opsi voting
    public function options()
    {
        return $this->hasMany(VotingOption::class, 'session_id');
    }
    
    // Relasi ke votes
    public function votes()
    {
        return $this->hasMany(Vote::class, 'session_id');
    }
    
    // ⭐⭐ TAMBAHKAN METHOD INI ⭐⭐
    // Cek apakah voting masih aktif
   public function isActive()
{
    $now = now();
    return $this->mulai <= $now && $this->selesai >= $now;
}

// Jika menggunakan withCount('votes')
public function getVotesCountAttribute()
{
    return $this->votes()->count();
}
 
    // Cek apakah user sudah vote
    public function hasVoted($userId = null)
    {
        if (!$userId && auth()->check()) {
            $userId = auth()->id();
        }
        
        if (!$userId) {
            return false;
        }
        
        return $this->votes()->where('user_id', $userId)->exists();
    }
    
    // Scope untuk voting aktif
    public function scopeAktif($query)
    {
        return $query->where('selesai', '>', now());
    }
}