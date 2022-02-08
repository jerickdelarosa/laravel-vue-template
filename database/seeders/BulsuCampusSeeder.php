<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BulsuCampusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_bulsu_campuses')->insert([
            [
                'id' => 1,
                'campus_name' => 'Main Campus',
            ],
            [
                'id' => 2,
                'campus_name' => 'Bustos Campus',
            ],
            [
                'id' => 3,
                'campus_name' => 'Sarmiento Campus',
            ],
            [
                'id' => 4,
                'campus_name' => 'Meneses Campus',
            ],
            [
                'id' => 5,
                'campus_name' => 'Hagonoy Campus',
            ],
            [
                'id' => 6,
                'campus_name' => 'San Rafael Campus',
            ],
        ]);
    }
}
