<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        $unreadCount = $request->user()->unreadNotifications()->count();

        // لو عندك layout اسمه layouts.admin خليه، وإلا استخدم view عادي
        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function readAll(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        return back()->with('status', 'تم تعليم كل الإشعارات كمقروءة');
    }

    public function read(Request $request, string $id)
    {
        $n = $request->user()->notifications()->where('id', $id)->firstOrFail();
        if (is_null($n->read_at)) $n->markAsRead();

        $url = data_get($n->data, 'url');
        return $url ? redirect($url) : back();
    }
}
