<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\ExamCategory;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category=[
                'name'=>'General Knowledge',
                'status'=>'1'
        ];

        ExamCategory::create($category);
    }
}
