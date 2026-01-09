<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $user = User::create([
            'name' => 'admin1',
            'email' => 'admin1@admin.com',
            'phone' => '081280946366',
            'photo' => 'mantap.png',
            'password' => bcrypt('123123123123'),
        ]);

        $user->assignRole('admin');
    }
}
