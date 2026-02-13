<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendFCMNotification;
use App\Models\Banner;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:notification-add', ['only' => ['create', 'send']]);
    }

    public function create()
    {

        return view('admin.notifications.create');

    }

   public function send(Request $request)
    {
        $request->validate([
            'title'     => 'required|string',
            'body'      => 'required|string',
            'type'      => 'required|in:0,1,2',
            'user_id'   => 'nullable|exists:users,id',
            'driver_id' => 'nullable|exists:drivers,id',
        ]);

        $title = $request->title;
        $body  = $request->body;
        $type  = $request->type;

        if ($type == 0) {
            // All users and drivers
            \App\Models\User::whereNotNull('fcm_token')->select('id', 'fcm_token')
                ->chunk(100, function ($users) use ($title, $body) {
                    foreach ($users as $user) {
                        SendFCMNotification::dispatch($title, $body, $user->fcm_token, $user->id, 'user');
                    }
                });

            \App\Models\Driver::whereNotNull('fcm_token')->select('id', 'fcm_token')
                ->chunk(100, function ($drivers) use ($title, $body) {
                    foreach ($drivers as $driver) {
                        SendFCMNotification::dispatch($title, $body, $driver->fcm_token, $driver->id, 'driver');
                    }
                });

        } elseif ($type == 1 && $request->user_id) {
            // Single user
            $user = \App\Models\User::find($request->user_id);
            if ($user?->fcm_token) {
                SendFCMNotification::dispatch($title, $body, $user->fcm_token, $user->id, 'user');
            }

        } elseif ($type == 2 && $request->driver_id) {
            // Single driver
            $driver = \App\Models\Driver::find($request->driver_id);
            if ($driver?->fcm_token) {
                SendFCMNotification::dispatch($title, $body, $driver->fcm_token, $driver->id, 'driver');
            }

        } elseif ($type == 1) {
            // All users
            \App\Models\User::whereNotNull('fcm_token')->select('id', 'fcm_token')
                ->chunk(100, function ($users) use ($title, $body) {
                    foreach ($users as $user) {
                        SendFCMNotification::dispatch($title, $body, $user->fcm_token, $user->id, 'user');
                    }
                });

        } elseif ($type == 2) {
            // All drivers
            \App\Models\Driver::whereNotNull('fcm_token')->select('id', 'fcm_token')
                ->chunk(100, function ($drivers) use ($title, $body) {
                    foreach ($drivers as $driver) {
                        SendFCMNotification::dispatch($title, $body, $driver->fcm_token, $driver->id, 'driver');
                    }
                });
        }

        // Save to DB immediately (don't wait for jobs)
        \App\Models\Notification::create([
            'title'     => $title,
            'body'      => $body,
            'type'      => $type,
            'user_id'   => $request->user_id,
            'driver_id' => $request->driver_id,
        ]);

        return redirect()->back()->with('message', 'Notifications are being sent in the background.');
    }

}
