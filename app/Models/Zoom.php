<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zoom extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'user_id'
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
