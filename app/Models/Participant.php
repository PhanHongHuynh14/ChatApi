<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'zoom_id',
    ];
    public function users()
    {
        return $this->belongsTo(User::class);
    }
    public function zooms()
    {
        return $this->belongsTo(Zoom::class);
    }
}
