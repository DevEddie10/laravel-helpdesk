<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'assigned_roles')
            ->select(['name', 'display_name', 'description'])
            ->as('permissions');
    }

    public function assigned()
    {
        return $this->hasMany(Assign::class);
    }
}
