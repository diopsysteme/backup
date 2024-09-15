<?php

namespace App\Models;
use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
 
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
#observedBy
#[ObservedBy([UserObserver::class])]
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'login',
        'role'
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
    
    public function client():HasOne
    {
        return $this->hasOne(Client::class);
    }
    public function role(){
        return $this->belongsTo(Role::class);
    }
    public function roleValue(){
        return $this->role()->first()->label;
    }
    public function hasRole($role)
    {
        // dd($this->roleValue());

        return $this->roleValue() === $role;
    }
    public function article(){
        return $this->hasMany(Article::class);
    }
    public function routeNotificationForMail($notification)
{
    return $this->login;
}

}
