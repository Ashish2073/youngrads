<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Yajra\Datatables\Datatables;

class AdminNotification extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:admin')->except('');
  }

  public function index()
  {
    if (request()->ajax()) {
      $notifications = Admin::find(1)->notifications;
      return Datatables::of($notifications)
        ->AddColumn('notifications', function ($row) {
          return view('dashboard.admin_notifications.notification', ['row' => $row])->render();
        })
        ->rawColumns(['notifications'])
        ->make(true);
    } else {
      $pageConfigs = [
        'pageHeader' => false
      ];
      return view('dashboard.admin_notifications.index', [
        'pageConfigs' => $pageConfigs
      ]);
    }
  }


  function markRead(Request $request)
  {
    $notifications = Admin::find(1)->unreadNotifications->where('id', $request->id)->markAsRead();
    return Admin::find(1)->unreadNotifications->count();
  }
}
