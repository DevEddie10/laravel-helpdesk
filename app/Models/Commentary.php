<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commentary extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'assigned_id',
        'status'
    ];

    public function assigned()
    {
        return $this->belongsToMany(Assign::class, 'assigned_commentaries','commentary_id', 'assgment_id')->withTimestamps();
    }

    public function specialist()
    {
        return $this->belongsTo(User::class, 'assigned_id')
            ->select(['id', 'name', 'image']);
    }
}