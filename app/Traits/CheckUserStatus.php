<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

trait CheckUserStatus
{
    public function checkUserStatus()
    {
        $user = Auth::user();

        if ($user && ($user->status !== 'active' || $user->isDeleted == 1)) {
            Auth::logout();
            Session::flash('error', 'Ваш аккаунт неактивен или удален');
            return false;
        }

        return true;
    }
}
