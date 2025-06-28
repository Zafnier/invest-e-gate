<?php

namespace Database\Seeders\Demo;

use App\Models\Admin\BasicSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BasicSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'site_name'       => "AdFund",
            'web_version'       => "3.1.1",
            'site_title'      => "Develop Your Dreams",
            'base_color'      => "#4A8FCA",
            'secondary_color' => "#ea5455",
            'otp_exp_seconds' => "3600",
            'timezone'        => "Asia/Dhaka",
            'site_logo_dark'  => "448011b8-70a2-4933-b37d-1fe05f8fd6f1.webp",
            'site_logo'       => "ac4c24fe-2ae0-473d-8302-1d0e20d6141d.webp",
            'site_fav_dark'   => "c7d6be78-e60d-4206-83e6-994d1c0fb4af.webp",
            'site_fav'        => "4534f573-69af-4410-892c-699053096f3e.webp",
            'user_registration'  => 1,
            'email_verification' => 1,
            'agree_policy'       => 1,
            'mail_config'       => [
                "method" => "smtp",
                "host" => "appdevs.net",
                "port" => "465",
                "encryption" => "ssl",
                "password" => "QP2fsLk?80Ac",
                "username" => "system@appdevs.net",
                "from" => "system@appdevs.net",
                "app_name" => "AdFund",
            ],
            'broadcast_config'  => [
                "method" => "pusher",
                "app_id" => "",
                "primary_key" => "",
                "secret_key" => "",
                "cluster" => ""
            ],
            'push_notification_config'  => [
                "method" => "pusher",
                "instance_id" => "",
                "primary_key" => ""
            ],
        ];

        BasicSettings::firstOrCreate($data);
    }
}
