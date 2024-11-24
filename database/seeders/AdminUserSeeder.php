<?php
namespace Database\Seeders;

use App\Models\Business;
use App\Models\FinancialYear;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

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
        // User::create([
        //     'name' => 'Khalil ur Rehman',
        //     'email' => 'khalil@test.com',
        //     'is_admin' => '1',
        //     'password' => bcrypt(Self::randomPassword),
        //     'created_by'=> 'sadmin',
        //     'updated_by'=> 'sadmin',
        // ]);
        // Business::create([
        //     'name' => 'Aamir Foods',
        //     'created_by' =>'1',
        //     'updated_by' =>'1',
        // ]);
        FinancialYear::create([
            'name' => '2024',
            'start_date' => '01-01-24',
            'end_date' => '30-12-24',
            'created_by' =>'1',
            'updated_by' =>'1',
        ]);
    }
    }
}
