<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method static create(array $array)
 * @method static updateOrCreate(array $array, array $array1)
 * @method static find($id)
 * @method static select(string $string)
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = "users";
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'country',
        'state',
        'city',
        'google_id',
        'google_token',
        'stripe_id',
        'pm_type',
        'pm_last_four',
        'trial_ends_at',
        'balance',
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
    ];

    public function post () : HasMany
    {
        return $this->hasMany(Post::class);
    }
    public function comment () : HasMany
    {
        return $this->hasMany(Comment::class);
    }
    protected function setPasswordAttribute ($password): string
    {
        return $this->attributes['password'] = Hash::make($password);
    }
}
