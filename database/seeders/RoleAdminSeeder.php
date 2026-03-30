<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $masterRole = Role::create([
            'name' => 'master',
        ]);

        $adminRole = Role::create([
            'name' => 'admin',
        ]);

        $agentRole = Role::create([
            'name' => 'agent',
        ]);

        $investorRole = Role::create([
            'name' => 'investor',
        ]);

        $user = User::create([
            'name' => 'master',
            'email' => 'master@master.com',
            'phone' => '081280946366',
            'photo' => 'mantap.png',
            'password' => bcrypt('123456789'),
        ]);

        $user->assignRole($masterRole);
    }
}
