<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        DB::table('users')->insert([
            [
                'name' => 'BSU Webmaster',
                'email' => 'admin@bulsu.edu.ph',
                'designation' => 'Administrator'
            ]
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
