<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * Run step by step:
     * 1. php artisan db:seed --class=SettingsSeeder
     * 2. php artisan db:seed --class=RolesAndPermissionsSeeder
     * 3. php artisan db:seed --class=InitialDataSeeder
     * 4. php artisan db:seed --class=MembershipSeeder
     * 5. php artisan db:seed --class=CmsContentSeeder
     * 
     * Or run all at once:
     * php artisan db:seed
     */
    public function run(): void
    {
        $this->call([
            SettingsSeeder::class,
            RolesAndPermissionsSeeder::class,
            InitialDataSeeder::class,
            MembershipSeeder::class,
            CmsContentSeeder::class,
        ]);
    }
}