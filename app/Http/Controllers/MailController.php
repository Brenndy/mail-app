<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\CompanyMailNotification;

class MailController
{
    public function mail()
    {
        $user = User::first();

        $user->notify(new CompanyMailNotification($user->company_id));

        echo 'Notification sended to first user<br>';

        $user = User::find(2);
        $user->notify(new CompanyMailNotification($user->company_id));

        echo 'Notification sended to second user';
    }
}
