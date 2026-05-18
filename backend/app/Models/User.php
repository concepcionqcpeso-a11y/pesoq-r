<?php

namespace App\Models;

// 1. THIS LINE MUST BE HERE
use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {
    // 2. THIS TRAIT MUST BE INSIDE THE CLASS
    use HasApiTokens, HasFactory, Notifiable; 

    protected $fillable = [
        'full_name', 'email', 'phone', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}