<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ArchivePasswordSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('archive_passwords')->insert([
            'password' => Hash::make('NOIR-MY-ADMIN-ACESSPASSWORD'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
