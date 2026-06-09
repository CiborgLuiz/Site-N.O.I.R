<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ArchivePasswordSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('archive_passwords')->updateOrInsert(
            ['id' => 1],
            [
                'password' => Hash::make(env('ARCHIVE_ACCESS_PASSWORD', 'NOIR-MY-ADMIN-ACESSPASSWORD')),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
