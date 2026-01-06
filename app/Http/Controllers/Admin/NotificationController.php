<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function create()
    {

        return view('admin.notifications.create');

    }

   public function send(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'type' => 'required|in:0,1,2',
            'user_id' => 'nullable|exists:users,id',
            'driver_id' => 'nullable|exists:drivers,id',
        ]);

        $title = $request->title;
        $body = $request->body;
        $type = $request->type;

        $sent = false;

        if ($type == 0) {
            // Send to all users and drivers
            $sent = FCMController::sendMessageToAll($title, $body);
        } elseif ($type == 1 && $request->user_id) {
            $user = \App\Models\User::find($request->user_id);
            $sent = FCMController::sendMessage($title, $body, $user->fcm_token, $user->id, 'order', 'user');
        } elseif ($type == 2 && $request->driver_id) {
            $driver = \App\Models\Driver::find($request->driver_id);
            $sent = FCMController::sendMessage($title, $body, $driver->fcm_token, $driver->id, 'order', 'driver');
        } else {
            // Send to all users or all drivers
            $recipients = $type == 1 ? \App\Models\User::all() : \App\Models\Driver::all();
            $sent = true;
            foreach ($recipients as $recipient) {
                if (!$recipient->fcm_token) continue;
                $modelType = $type == 1 ? 'user' : 'driver';
                $result = FCMController::sendMessage($title, $body, $recipient->fcm_token, $recipient->id, 'order', $modelType);
                if (!$result) $sent = false;
            }
        }

        // Save to DB
       Notification::create([
            'title' => $title,
            'body' => $body,
            'type' => $type,
            'user_id' => $request->user_id,    // this will be null if not set, which is fine
            'driver_id' => $request->driver_id,
        ]);

        return redirect()->back()->with($sent ? 'message' : 'error', $sent ? 'Notification sent successfully' : 'Some notifications failed');
    }

}
