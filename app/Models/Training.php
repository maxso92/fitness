<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'trainer_id',
        'training_at',
        'info',
        'status',
        'cancel_reason',
    ];

    public function clients()
    {
        return $this->belongsToMany(\App\Models\User::class, 'training_user', 'training_id', 'user_id');
    }

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    // Проверка прав на удаление
    public function canDelete($user)
    {
        // Администратор может удалять любые тренировки
        if ($user->role === 'admin') {
            return true;
        }

        // Менеджер может удалять тренировки в своем зале
        if ($user->role === 'manager') {
            return $this->trainer && $this->trainer->gym_id === $user->gym_id;
        }

        // Тренер может удалять только свои тренировки
        if ($user->role === 'trainer' && $this->trainer_id === $user->id) {
            return true;
        }

        return false;
    }
}
