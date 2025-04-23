<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    protected $dates = ['deleted_at']; // Поле для мягкого удаления


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'username',
        'email',
        'birthday',
        'name',
        'surname',
        'patronymic',
        'information',
        'password',
        'role',
        'gym_id',
        'trainer_id',
        'isDeleted',
        'status',
        'avatar',
        'last_seen_at',
        'isDeleted'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthday' => 'date',
        'last_seen_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        'avatar_url',
        'full_name'
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return trim("{$this->surname} {$this->name} {$this->patronymic}");
    }

    /**
     * Get the avatar URL.
     *
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        return $this->avatar
            ? asset('avatars/' . $this->avatar)
            : asset('avatars/avatar.jpg');
    }

    /**
     * Check if user is online.
     *
     * @return bool
     */
    public function isOnline()
    {
        return $this->last_seen_at && Carbon::parse($this->last_seen_at)->diffInMinutes(now()) < 5;
    }

    /**
     * Scope a query to search users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function search($query)
    {
        return empty($query)
            ? static::query()->where('status', 'active')
            : static::where('status', 'active')
                ->where(function($q) use ($query) {
                    $q->where('name', 'LIKE', '%'. $query . '%')
                        ->orWhere('email', 'LIKE', '%' . $query . '%')
                        ->orWhere('phone', 'LIKE', '%' . $query . '%')
                        ->orWhere('surname', 'LIKE', '%' . $query . '%');
                });
    }





    // Relationships


    public function auth_logs()
    {
        return $this->hasMany(AuthLog::class, 'user_id');
    }

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function trainings()
    {
        return $this->belongsToMany(Training::class, 'training_user', 'user_id', 'training_id');
    }

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }


    public function subscriptions()
    {
        return $this->belongsToMany(Subscription::class, 'client_subscriptions')
            ->withPivot([
                'start_date',
                'end_date',
                'remaining_visits',
                'trainer_id',
                'is_active'
            ])
            ->withTimestamps();
    }

    public function activeSubscriptions()
    {
        return $this->belongsToMany(Subscription::class, 'client_subscriptions')
            ->withPivot([
                'start_date',
                'end_date',
                'remaining_visits',
                'trainer_id',
                'is_active'
            ])
            ->wherePivot('is_active', true)
            ->where(function($query) {
                $query->whereNull('client_subscriptions.end_date')
                    ->orWhere('client_subscriptions.end_date', '>=', now());
            });
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::deleting(function($user) {
            DB::transaction(function () use ($user) {

                $user->auth_logs()->delete();
            });
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    // Проверка прав на полное удаление
    public function canForceDelete($user)
    {
        return $user->role === 'admin';
    }

    // Проверка прав на мягкое удаление/восстановление
    public function canSoftDelete($user)
    {
        return in_array($user->role, ['admin', 'manager']);
    }

}
