<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'image'
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    protected $attributes = [
        'status' => 0,
        'password' => '$2y$10$/jPt9tzQ0/2hkxUhtFtfBeJ.oI/4y.nAMAMFsapIau031RYHzB0PC'
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