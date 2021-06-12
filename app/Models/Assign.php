<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Assign extends Model
{
    use HasFactory;

    protected $table = 'assignments';

    protected $fillable = [
        'user_id',
        'category_id',
        'media_id',
        'state_id',
        'assigned_id',
        'status_id',
        'modulo_id',
        'solution_id',
        'description',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)
            ->select(['id', 'name']);
    }

    public function specialist()
    {
        return $this->belongsTo(User::class, 'assigned_id')
            ->select(['id', 'name']);
    }

    public function category()
    {
        return $this->belongsTo(Category::class)
            ->select(['id', 'name']);
    }

    public function medio()
    {
        return $this->belongsTo(Media::class, 'media_id')
            ->select(['id', 'name']);
    }

    public function state()
    {
        return $this->belongsTo(State::class)
            ->select(['id', 'name']);
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'modulo_id')
            ->select(['id', 'name']);
    }

    public function solution()
    {
        return $this->belongsTo(Solution::class)
            ->select(['id', 'name']);
    }

    public function commentaries()
    {
        return $this->belongsToMany(Commentary::class, 'assigned_commentaries', 'assgment_id')
            ->as('tickets')
            ->withTimestamps();
    }
}
