<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VotingOption extends Model
{
    use HasFactory;

    protected $table = 'voting_options';
    
    protected $fillable = [
        'session_id', 'pilihan_label', 'urutan'
    ];
    
    // Relasi ke session voting
    public function session()
    {
        return $this->belongsTo(VotingSession::class, 'session_id');
    }
    
    // Relasi ke votes
    public function votes()
    {
        return $this->hasMany(Vote::class, 'option_id');
    }
    
    // Hitung total suara untuk opsi ini
    public function getTotalVotesAttribute()
    {
        return $this->votes()->count();
    }
}