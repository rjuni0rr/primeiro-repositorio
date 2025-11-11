<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'email' => 'sysadmin@localhost.com',
                'password' => bcrypt('Aa123456'),
                'id_company' => 0,
                'role' => 'sys-admin',
                'active' => true

            ],

            [
                'active' => true,
                'email' => 'admin1@localhost.com',
                'id_company' => 1,
                'password' => bcrypt('Aa123456'),
                'role' => 'client-admin'

            ],

            [
                'active' => true,
                'email' => 'admin2@localhost.com',
                'id_company' => 2,
                'password' => bcrypt('Aa123456'),
                'role' => 'client-admin'

            ],
        ];
        DB::table('users')->insert($users);

        echo count($users) . ' usuários foram criados com sucesso.';
    }
}
