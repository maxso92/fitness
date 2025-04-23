<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'type',
        'duration_days',
        'visits_count',
        'has_trainer_service',
        'trainer_id',
        'is_active'
    ];

    protected $casts = [
        'pivot.start_date' => 'date',
        'pivot.end_date' => 'date',
        'has_trainer_service' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }


    public function clients()
    {
        return $this->belongsToMany(User::class, 'client_subscriptions')
            ->withPivot([
                'start_date',
                'end_date',
                'remaining_visits',
                'trainer_id',
                'is_active'
            ])
            ->withTimestamps();
    }

    public function getTypeNameAttribute()
    {
        return $this->type === 'time' ? 'По времени' : 'По посещениям';
    }

    public function getDurationAttribute()
    {
        if ($this->type === 'time') {
            return $this->duration_days . ' дней';
        }
        return $this->visits_count . ' посещений';
    }
}
