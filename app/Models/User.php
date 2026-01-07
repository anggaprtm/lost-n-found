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
        'nim',
        'email',
        'password',
        'phone',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function validatedReports()
    {
        return $this->hasMany(Report::class, 'validator_id');
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    public function validatedClaims()
    {
        return $this->hasMany(Claim::class, 'validator_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPetugas()
    {
        return $this->role === 'petugas';
    }

    public function isMahasiswa()
    {
        return $this->role === 'mahasiswa';
    }

   public function getRoleAttribute($value)
   {
        return strtolower(trim($value));
    }


}
