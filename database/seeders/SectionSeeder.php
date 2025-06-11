<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Section::create([
            'name' => 'الفرع العلمي',
            // 'name' => 'scientific'
        ]);
        Section::create([
            'name' => 'الفرع الأدبي',
            // 'name' => 'literary'
        ]);
    }
}
