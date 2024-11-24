<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notifications;
use Illuminate\Support\Facades\Auth;



/*
     * Class BankService
     * @package App\Services
     * */


// use Illuminate\Support\Facades\Input;

class NotificationService
{


    public function NotificationData()
    {
        $userId = Auth::user()->id;

        // Check if the session has the correct user ID
        if (!$userId) {
            return redirect('user/login')->with('fail', 'You must be logged in to access the dashboard');
        }

        $LoggedUserInfo = User::find($userId);

        // Fetch the count of messages for the user
        $messageCount = Notifications::where('notifiable_id', $userId)->count();

        // Fetch the notification, ensuring they are ordered by the newest first
        $messages = Notifications::where('notifiable_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();


        return $LoggedUserInfo . $messageCount . $messages;
    }
}
