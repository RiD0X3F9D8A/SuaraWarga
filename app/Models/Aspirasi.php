<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aspirasi extends Model
{
    protected $table = 'aspirasi';
    
    protected $fillable = [
        'judul',
        'isi',
        'user_id',
        'admin_id',
        'admin_response',
        'status',
        'alasan_penolakan',
        'is_response_edited',
        'responded_at'
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'responded_at' => 'datetime'
    ];
    
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    
    public function admin()
    {
        return $this->belongsTo(\App\Models\User::class, 'admin_id');
    }
    
    public function isSubmitted()
    {
        return $this->status === 'submitted';
    }
    
    public function isApproved()
    {
        return $this->status === 'approved';
    }
    
    public function isRejected()
    {
        return $this->status === 'rejected';
    }
    
    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }
    
    public function isCompleted()
    {
        return $this->status === 'completed';
    }
    
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'submitted' => '<span class="badge bg-warning">Menunggu</span>',
            'approved' => '<span class="badge bg-success">Disetujui</span>',
            'rejected' => '<span class="badge bg-danger">Ditolak</span>',
            'in_progress' => '<span class="badge bg-info">Diproses</span>',
            'completed' => '<span class="badge bg-primary">Selesai</span>'
        ];
        
        return $badges[$this->status] ?? '<span class="badge bg-secondary">-</span>';
    }
}