<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; 

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'email_verified_at',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class);
    }

    public function perusahaan()
    {
        return $this->hasOne(Perusahaan::class);
    }

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Scope untuk filter user by role
     */
    public function scopeByRole($query, $role)
    {
        if ($role) {
            return $query->where('role', $role);
        }
        return $query;
    }

    /**
     * Scope untuk search user by name atau email
     */
    public function scopeSearchable($query, $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    /**
     * Query untuk mendapatkan users dengan filter
     */
    public static function getFilteredUsers($role = null, $search = null)
    {
        return static::with([
                'mahasiswa:id,user_id',
                'perusahaan:id,user_id',
                'admin:id,user_id'
            ])
            ->select('id', 'name', 'email', 'role', 'email_verified_at', 'created_at')
            ->byRole($role)
            ->searchable($search);
    }

    /**
     * Query untuk mendapatkan user dengan role profile
     */
    public static function withRoleProfile($id)
    {
        return static::with(['mahasiswa', 'perusahaan', 'admin'])->find($id);
    }
}