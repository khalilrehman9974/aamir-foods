<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    const randomPassword = '123456';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
     * Run the database seeds.
     *
     * @return void
     */
    {
        User::create([
            'name' => 'Khalil ur Rehman',
            'email' => 'khalil@test.com',
            'is_admin' => '1',
            'password' => bcrypt(Self::randomPassword),
            'created_by'=> 'sadmin',
            'updated_by'=> 'sadmin',
        ]);
    }
    }
}
