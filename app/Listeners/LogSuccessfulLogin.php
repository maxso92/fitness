<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Jenssegers\Agent\Agent;
use App\Models\AuthLog;
use Carbon\Carbon;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $agent = new Agent();

        AuthLog::create([
            'user_id' => $event->user->id,
            'ip_address' => request()->ip(),
            'browser' => $agent->browser(),
            'device' => $agent->device(),
            'login_time' => Carbon::now(),
        ]);
    }
}
