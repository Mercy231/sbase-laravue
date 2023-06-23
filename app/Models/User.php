<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Brick\Math\BigInteger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @method static create(array $array)
 * @method static updateOrCreate(array $array, array $array1)
 * @method static find($id)
 * @method static select(string $string)
 * @method static where(string $string, $id)
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

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
        'country_id',
        'state_id',
        'city_id',
        'google_id',
        'google_token',
        'stripe_id',
        'pm_type',
        'pm_last_four',
        'trial_ends_at',
        'balance',
        'avatar',
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
    protected $with = [
        "country",
        "state",
        "city",
        "notifications",
        "newNotifications",
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
    public function country ()
    {
        return $this->hasOne(Country::class, "id","country_id");
    }
    public function state () : HasOne
    {
        return $this->hasOne(State::class,"id", "state_id");
    }
    public function city () : HasOne
    {
        return $this->hasOne(City::class,"id", "city_id");
    }
    public function notifications() : HasMany
    {
        return $this->hasMany(Notification::class)
            ->whereNot("status", "deleted")
            ->orderBy("updated_at");
    }
    public function newNotifications() : HasMany
    {
        return $this->hasMany(Notification::class)->where("status", "unread");
    }
    protected function setPasswordAttribute ($password): string
    {
        return $this->attributes['password'] = Hash::make($password);
    }
    protected function getAvatarAttribute ($avatar) : string
    {
        return asset('/public/storage/images/avatars/' . $avatar);
    }
}
