<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Firebase\JWT\JWT;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function donations()
    {
        return $this->hasMany(Donation::class, 'user_id');
    }

    public function generateJwtToken()
{
    $payload = [
        'iss' => config('app.url'), // Issuer
        'sub' => $this->id,         // Subject (user ID)
        'iat' => now()->timestamp,  // Issued at
        'exp' => now()->addHours(2)->timestamp // Expiry
    ];

    return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
}
}
