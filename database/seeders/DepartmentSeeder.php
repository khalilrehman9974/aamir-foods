<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = [
            [
                'name' => 'Admin Department.',
                'created_by' => '1',
                'updated_by' => '1',
            ],
            [
                'name' => 'Accounts Department.',
                'created_by' => '1',
                'updated_by' => '1',
            ],
            [
                'name' => 'Finished Goods Store.',
                'created_by' => '1',
                'updated_by' => '1',
            ],
            [
                'name' => 'Main Store.',
                'created_by' => '1',
                'updated_by' => '1',
            ],
            [
                'name' => 'Non Inventory Store.',
                'created_by' => '1',
                'updated_by' => '1',
            ],
            [
                'name' => 'Bubble Department.',
                'created_by' => '1',
                'updated_by' => '1',
            ],
            [
                'name' => 'Hazmeen Department.',
                'created_by' => '1',
                'updated_by' => '1',
            ],
            [
                'name' => 'Sales Department.',
                'created_by' => '1',
                'updated_by' => '1',
            ]
        ];

        foreach ($departments as $department) {
            \App\Models\Department::create($department);
        }
    }
}
