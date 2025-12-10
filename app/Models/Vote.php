<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $table = 'votes';
    
    public $timestamps = false;
    
    protected $fillable = [
        'session_id', 'user_id', 'option_id', 'cast_at', 'ip_address'
    ];
    
    protected $casts = [
        'cast_at' => 'datetime',
    ];
    
    public function session()
    {
        return $this->belongsTo(VotingSession::class, 'session_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function option()
    {
        return $this->belongsTo(VotingOption::class, 'option_id');
    }
}