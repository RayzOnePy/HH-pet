<?php

namespace Database\Seeders;

use App\Models\CompanyRole;
use Illuminate\Database\Seeder;

class CompanyRoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['owner', 'hr'];

        foreach ($roles as $role) {
            CompanyRole::create(['name' => $role]);
        }
    }
}
