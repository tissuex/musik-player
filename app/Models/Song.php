<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    /**
     * Properti $fillable untuk mengizinkan Mass Assignment.
     * TAMBAHKAN INI:
     */
    protected $fillable = [
        'title',
        'artist',
        'album',
        'file_path',
        'cover_art_path',
    ];

    public function likers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'liked_songs', 'song_id', 'user_id');
    }
}