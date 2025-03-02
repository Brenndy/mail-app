<?php

namespace App\Factories;

use App\Models\Company;

class MailConfigFactory
{
    public static function getMailConfig(Company $company)
    {
        $settings = $company->mail_settings;
        $transport = $settings['transport'] ?? 'smtp';

        switch ($transport) {
            case 'smtp':
                return self::smtpConfig($settings);
            case 'office365':
            case 'gmail':
                return self::oauthConfig($settings);
            default:
                throw new \Exception("Unsupported mail transport: $transport");
        }
    }

    private static function smtpConfig($settings)
    {
        return [
            'transport'  => 'smtp',
            'host'       => $settings['host'],
            'port'       => $settings['port'],
            'username'   => $settings['username'],
            'password'   => $settings['password'],
            'encryption' => $settings['encryption'],
            'from'       => $settings['from'],
        ];
    }

    private static function oauthConfig($settings)
    {
        // Get the fresh access token using the refresh token
        $accessToken = OAuth2TokenService::getAccessToken($settings);

        if (!$accessToken) {
            throw new \Exception("Failed to get OAuth2 token");
        }

        return [
            'transport'  => 'smtp',
            'host'       => ($settings['transport'] === 'gmail') ? 'smtp.gmail.com' : 'smtp.office365.com',
            'port'       => 587,
            'username'   => $settings['username'],
            'password'   => $accessToken, // Use OAuth2 token as password
            'encryption' => 'tls',
            'auth_mode'  => 'xoauth2', // Required for OAuth2 authentication
            'from'       => $settings['from'],
        ];
    }
}
