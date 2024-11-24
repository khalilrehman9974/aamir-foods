<?php

namespace Database\Seeders;

use App\Models\Notifications;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Notifications::create([
            'id' => '1',
            'type' => 'notification',
            'notifiable_type' => 'Admin',
            'notifiable_id' => '1',
            'data' =>'Hello Aamir Foods Notification System',
        ]);
    }
}
