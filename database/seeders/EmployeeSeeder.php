<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        Employee::updateOrCreate(
            [
                'email' => 'akbarmajenesmk5@gmail.com', // kunci unik
            ],
            [
                'employee_name' => 'Muhammad Akbar',
                'nip'           => '423987624578',
                'status'        => 'PNS',
                'is_active'     => true,
            ]
        );
    }
}
