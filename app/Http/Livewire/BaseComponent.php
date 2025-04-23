<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LogoutResponse;

class BaseComponent extends Component
{
    public function boot()
    {
        if (Auth::check() && (Auth::user()->status !== 'active' || Auth::user()->isDeleted)) {
            Auth::guard('web')->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            session()->flash('error', 'Ваш аккаунт неактивен или удален');
            return redirect()->route('login');
        }
    }
}
