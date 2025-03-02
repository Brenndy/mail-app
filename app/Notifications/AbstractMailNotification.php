<?php

namespace App\Notifications;

use App\Models\Company;
use Illuminate\Notifications\Notification;

abstract class AbstractMailNotification extends Notification
{
    public function getCustomMailer(int $companyId)
    {
        $company = Company::find($companyId);

        $mailSettings = $company->mail_settings;
        // Define a unique mailer for this company
        $uniqueMailerName = 'company_mailer_' . $companyId;

        config(["mail.mailers.$uniqueMailerName" => [
            'transport'  => $mailSettings['transport'],
            'host'       => $mailSettings['host'],
            'port'       => $mailSettings['port'],
            'username'   => $mailSettings['username'],
            'password'   => $mailSettings['password'],
            'encryption' => $mailSettings['encryption'],
        ]]);

        return $uniqueMailerName;
    }
}
