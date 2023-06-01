<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('attendances')->insert([
            [
                'workerID' => 02200,
                'training_group' => 'NST',
                'training_day' => '2023-05-30'
            ],
            [
                'workerID' => 03400,
                'training_group' => 'NST',
                'training_day' => '2023-05-30'
            ]
            ]);
    }
}
