<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $scientific_materials =[
            'الرياضيات',
            'الفيزياء',
            'الكيمياء',
            'اللغة العربية',
            'علم الأحياء',
            'اللغة الإنكليزية',
            'اللغة الفرنسية',
            ' التربية الوطنية ',
            'التربية الدينية',
        ];
        $literary_materials =[
            'الفلسفة',
            'التاريخ',
            'الجغرافيا',
            'اللغة العربية',
            'اللغة الانكليزية',
            'اللغة الفرنسية',
            'التربية الوطنية',
            'التربية الدينية'
        ];
        
        foreach($scientific_materials as $scientific_material)
        {
            Material::create([
                'name' => $scientific_material,
                'section_id' => 1,
            ]);
        }

        foreach($literary_materials as $literary_material)
        {
            Material::create([
                'name' => $literary_material,
                'section_id' => 2,
            ]);
        }
    }
}
