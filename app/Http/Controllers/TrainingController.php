<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Training;

class TrainingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'trainer_id' => 'required|exists:users,id',
            'clients' => 'required|array',
            'clients.*' => 'exists:users,id',
            'training_at' => 'required|date',
            'info' => 'nullable|string',
        ]);

        // Создаем тренировку
        $training = Training::create([
            'trainer_id' => $request->trainer_id,
            'training_at' => $request->training_at,
            'info' => $request->info,
        ]);

        // Привязываем клиентов к тренировке через pivot таблицу
        $training->clients()->attach($request->clients);

        return redirect()->back()->with('success', 'Тренировка добавлена успешно.');
    }
}
