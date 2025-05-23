<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Notification;
use Illuminate\Http\Request;


class NotificationController extends Controller
{
    // For all
    public function index()
    {
      
      $data = Notification::get();
      return response()->json(['data'=>$data]);
    }

    public function forAdmin()
    {
        $admin_id = Admin::first();
      $data = Notification::where('admin_id',$admin_id)->get();
      return response()->json(['data'=>$data]);
    }

    public function notifications()
    {
        $user = auth()->user();
        $notifications = Notification::where('user_id',$user->id)->orderBy('id','DESC')->get();
            return response([ 'data' => $notifications], 200);

    }








}
