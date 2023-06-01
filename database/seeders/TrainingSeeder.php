<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('trainings')->insert([
            [
                'training_group' => 'NST',
            ],
            [
                'training_group' => 'デイケア',
            ],
            [
                'training_group' => '医局会',
            ],
            [
                'training_group' => '医療安全委員会',
            ],
            [
                'training_group' => '院内感染委員会',
            ],
            [
                'training_group' => '院内研究会',
            ],
            [
                'training_group' => '院内研究発表会',
            ],
            [
                'training_group' => '運営会議',
            ],
            [
                'training_group' => '幹部会',
            ],
            [
                'training_group' => '教育委員会',
            ],
            [
                'training_group' => '個人情報委員会',
            ],
            [
                'training_group' => '交通安全研修会',
            ],
            [
                'training_group' => '広報委員会',
            ],
            [
                'training_group' => '師長会',
            ],
            [
                'training_group' => '職員研修',
            ],
            [
                'training_group' => '水曜会',
            ],
            [
                'training_group' => '認知症の話',
            ],
            [
                'training_group' => '連絡協議会',
            ],
            [
                'training_group' => '褥瘡委員会',
            ]
            ]);
    }
}
