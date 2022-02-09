<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = [
            [
                'name' => 'Rakib Com',
                'email' => 'rakibcom@gmail.com',
                'password' => Hash::make('123456com'),
            ]
        ];
        DB::table('companies')->insert($companies);
    }
}
