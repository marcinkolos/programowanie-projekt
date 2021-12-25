<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'isPrivate',
        'title',
        'message'
    ];

    protected $hidden = [
        
    ];

    public function sender_model()
    {
        return $this->belongsTo(User::class, 'sender');
    }

    public function receiver_model()
    {
        return $this->belongsTo(User::class, 'receiver');
    }
}
