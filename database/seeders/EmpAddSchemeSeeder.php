<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpAddSchemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $emp_add_schemes = [
            [
                'from_amount' => 1000,
                'to_amount' => 2000,
                'qty' => 10
            ]
        ];
        DB::table('emp_add_schemes')->insert($emp_add_schemes);
    }
}
