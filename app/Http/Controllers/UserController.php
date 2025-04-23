<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Visit;


class UserController extends Controller
{
    public function updateUserActivity(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $user->last_seen_at = now();
            $user->save();

            $userActivity = new UserActivity();
            $userActivity->user_id = $user->id;
            $userActivity->activity_type = 'active';
            $userActivity->save();

            return response()->json(['message' => 'Активность пользователя обновлена'], 200);
        }

        return response()->json(['message' => 'Не удалось обновить активность пользователя'], 500);
    }

    public function create()
    {
        return view('users.create');
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'surname' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'patronymic' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,manager,trainer,client',
            'birthday' => 'nullable|date',
            'password' => 'required|string|min:6|confirmed',
            'gym_id' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
            'trainer_id' => 'nullable|exists:users,id',
            'information' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'uuid' => 'required|uuid|unique:users,uuid',
        ]);

        // Обработка аватара - идентично методу update
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = time() . '.' . $avatar->getClientOriginalExtension();

            // Создаем папку avatars в public, если ее нет
            if (!file_exists(public_path('avatars'))) {
                mkdir(public_path('avatars'), 0777, true);
            }

            // Перемещаем файл в public/avatars
            $avatar->move(public_path('avatars'), $filename);
            $validatedData['avatar'] = $filename;
        }

        $validatedData['password'] = Hash::make($validatedData['password']);

        User::create($validatedData);

        return redirect()->route('partners')->with('success', 'Пользователь успешно добавлен!');
    }


    public function edit($id)
    {
        $user = User::findOrFail($id);
        $currentUser = auth()->user();

        // Manager restrictions
        if ($currentUser->role === 'manager') {
            // Managers can only view/edit trainers, clients, or themselves
            if ($user->role === 'manager' && $user->id !== $currentUser->id) {
                abort(403, 'Вы можете просматривать только свой профиль менеджера');
            }

            // Managers cannot edit admin users
            if ($user->role === 'admin') {
                abort(403, 'У вас нет прав для редактирования администраторов');
            }
        }

        // Get only active trainers for selection
        $trainers = User::where('role', 'trainer')
            ->where('status', 'active')
            ->get();

        $gyms = \App\Models\Gym::all();

        return view('users.edit', [
            'user' => $user,
            'trainers' => $trainers,
            'gyms' => $gyms,
            'isManager' => ($currentUser->role === 'manager')
        ]);
    }

    public function view($id)
    {
        $user = User::findOrFail($id);
        $currentUser = auth()->user();

        // Manager restrictions
        if ($currentUser->role === 'manager') {
            // Managers can view other managers (read-only), but only edit themselves
            if ($user->role === 'admin') {
                abort(403, 'У вас нет прав для просмотра профилей администраторов');
            }
        }

        // Get related data
        $trainer = $user->trainer_id ? User::find($user->trainer_id) : null;
        $gym = $user->gym_id ? \App\Models\Gym::find($user->gym_id) : null;

        return view('users.view', [
            'user' => $user,
            'trainer' => $trainer,
            'gym' => $gym,
            'canEdit' => $this->canEditUser($currentUser, $user)
        ]);
    }

// Helper method to determine edit permissions
    private function canEditUser($currentUser, $targetUser)
    {
        if ($currentUser->role === 'admin') {
            return true;
        }

        if ($currentUser->role === 'manager') {
            return $targetUser->role !== 'admin' &&
                ($targetUser->role !== 'manager' || $targetUser->id === $currentUser->id);
        }

        return false;
    }

    public function downloadQr($uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();

        return response()->streamDownload(
            function () use ($user) {
                echo QrCode::size(300)->generate($user->uuid);
            },
            "qr-code-{$user->uuid}.svg",
            [
                'Content-Type' => 'image/svg+xml',
            ]
        );
    }

    public function update(Request $request, $id)
    {
        try {
            \Log::info('Updating user:', ['id' => $id, 'data' => $request->all()]);

            $user = User::findOrFail($id);

            $validatedData = $request->validate([
                'surname' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'patronymic' => 'nullable|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'birthday' => 'nullable|date',
                'password' => 'nullable|min:6',
                'role' => 'required|string|in:admin,manager,trainer,client',
                'gym_id' => 'required|integer',
                'status' => 'required|string|in:active,inactive',
                'information' => 'nullable|string',
                'trainer_id' => 'nullable|integer|exists:users,id',
                'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            // Обработка пароля
            if (empty($validatedData['password'])) {
                unset($validatedData['password']);
            } else {
                $validatedData['password'] = Hash::make($validatedData['password']);
            }

            // Обработка аватара
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $filename = time() . '.' . $avatar->getClientOriginalExtension();
                $avatar->move(public_path('avatars'), $filename);
                $validatedData['avatar'] = $filename;
            }

            // Обновляем пользователя
            $user->update($validatedData);

            return redirect()->back()->with('success', 'Данные обновлены!');
        } catch (\Exception $e) {
            \Log::error('Error updating user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ошибка: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $currentUser = Auth::user();

        if (!$user->canForceDelete($currentUser)) {
            return redirect()->back()->with('error', 'У вас нет прав на полное удаление пользователя!');
        }

        // Удаляем связанные данные перед полным удалением
        DB::transaction(function () use ($user) {

            $user->auth_logs()->delete();

            $user->forceDelete();
        });

        return redirect()->route('partners')->with('success', 'Пользователь полностью удален!');
    }

    public function softDelete($id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();

        if (!$user->canSoftDelete($currentUser)) {
            return redirect()->back()->with('error', 'У вас нет прав на удаление пользователей!');
        }

        $user->update(['isDeleted' => 1]);


        return redirect()->back()->with('success', 'Пользователь перемещен в архив!');
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $currentUser = Auth::user();

        if (!$user->canSoftDelete($currentUser)) {
            return redirect()->back()->with('error', 'У вас нет прав на восстановление пользователей!');
        }

        $user->update(['isDeleted' => 0]);
        $user->restore(); // Восстановление

        return redirect()->back()->with('success', 'Пользователь восстановлен!');
    }
    public function toggleBlock($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->status = $user->status === 'active' ? 'inactive' : 'active';
            $user->save();

            $message = $user->status === 'active' ? 'Пользователь был успешно разблокирован.' : 'Пользователь был успешно заблокирован.';
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'There was an error changing the user status.');
        }
    }


    public function showScanForm()
    {
        return view('users.scan-qr');
    }

    public function processScan(Request $request)
    {
        $request->validate([
            'uuid' => 'required|uuid|exists:users,uuid'
        ]);

        $user = User::with(['gym', 'trainer', 'subscriptions' => function($query) {
            $query->wherePivot('is_active', true)
                ->where(function($q) {
                    $q->whereNull('client_subscriptions.end_date')
                        ->orWhere('client_subscriptions.end_date', '>=', now());
                });
        }])->where('uuid', $request->uuid)->firstOrFail();

        return view('users.scan-result', compact('user'));
    }
    public function allowEntry(User $user)
    {
        $manager = auth()->user();

        if (!in_array($manager->role, ['manager', 'admin'])) {
            abort(403, 'У вас нет прав для выполнения этого действия');
        }

        if ($user->isDeleted || $user->status !== 'active') {
            return redirect()->route('scan.qr.form')->with('error', 'Пользователь неактивен или удален');
        }

        if ($user->activeSubscriptions->isEmpty()) {
            return redirect()->route('scan.qr.form')->with('error', 'У пользователя нет активных абонементов');
        }

        if (Visit::where('user_id', $user->id)->whereDate('visited_at', today())->exists()) {
            return redirect()->route('scan.qr.form')->with('warning', 'Пользователь уже посещал зал сегодня');
        }

        Visit::create([
            'user_id' => $user->id,
            'manager_id' => $manager->id,
            'visited_at' => now()
        ]);

        foreach ($user->activeSubscriptions as $subscription) {
            if ($subscription->pivot->remaining_visits > 0) {
                $user->subscriptions()
                    ->updateExistingPivot($subscription->id, [
                        'remaining_visits' => $subscription->pivot->remaining_visits - 1
                    ]);
            }
        }

        return redirect()->route('scan.qr.process')
            ->with('access_allowed', "Доступ разрешен для: {$user->full_name}")
            ->with('visit_time', now()->format('H:i'));
    }

}
