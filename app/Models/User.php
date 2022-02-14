<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $guarded = ['id'];

    protected $hidden = [
        'password', 'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'address' => 'object',
        'ver_code_send_at' => 'datetime'
    ];


    public function lastLogin()
    {
        return $this->hasOne(UserLogin::class)->latest();
    }

    public function login_logs()
    {
        return $this->hasMany(UserLogin::class);
    }

    public function getFullnameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function scopeActive()
    {
        return $this->where('status', 1);
    }

    public function scopeBanned()
    {
        return $this->where('status', 0);
    }

    public function scopeEmailUnverified()
    {
        return $this->where('ev', 0);
    }

    public function scopeSmsUnverified()
    {
        return $this->where('sv', 0);
    }
    public function scopeEmailVerified()
    {
        return $this->where('ev', 1);
    }

    public function scopeSmsVerified()
    {
        return $this->where('sv', 1);
    }

    public function generateToken()
    {
        return $this->createToken('auth_token')->plainTextToken;
    }

}
