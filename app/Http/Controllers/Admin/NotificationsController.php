<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests;
use App\Models\User;
use App\Notifications\UserFollowed;

class NotificationsController extends Controller
{
    //..
    public function index() {
        $users = User::where('id', '!=', auth()->user()->id)->get();
        return view('notifications.index', compact('users'));
    }

    public function follow(User $user)
    {
        $follower = auth()->user();
        if ( ! $follower->isFollowing($user->id)) {
            $follower->follow($user->id);

            // add this to send a notification
            $user->notify(new UserFollowed($follower));

            return back()->withSuccess("You are now friends with {$user->name}");
        }

        return back()->withSuccess("You are already following {$user->name}");
    }


    public function unfollow(User $user) {
        $follower = auth()->user();
        if($follower->isFollowing($user->id)) {
            $follower->unfollow($user->id);
            return back()->withSuccess("You are no longer friends with {$user->name}");
        }
        return back()->withError("You are not following {$user->name}");
    }

    public function notifications() {

    	dd(auth()->user()->unreadNotifications()->limit(5)->get()->toArray());
        return auth()->user()->unreadNotifications()->limit(5)->get()->toArray();
    }
}
