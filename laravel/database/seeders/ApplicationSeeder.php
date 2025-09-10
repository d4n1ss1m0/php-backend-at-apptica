<?php

namespace Database\Seeders;

use App\Models\Application;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $insertData = [
            [
                'id' => 1,
                'app_id' => 1421444,
                'app_name' => 'Among Us'
            ]
        ];

        //используем upsert для удобного изменения данных из seeder
        Application::upsert($insertData, ['id'], ['app_id', 'app_name']);
    }
}
