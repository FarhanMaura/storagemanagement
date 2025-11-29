<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Roles
        $roles = [
            [
                'name' => 'Main Admin',
                'slug' => 'admin',
            ],
            [
                'name' => 'Petugas Pengajuan',
                'slug' => 'petugas_pengajuan',
            ],
            [
                'name' => 'Manajer Persetujuan',
                'slug' => 'manajer_persetujuan',
            ],
            [
                'name' => 'Petugas Barang Keluar',
                'slug' => 'petugas_barang_keluar',
            ],
            [
                'name' => 'User',
                'slug' => 'user',
            ],
        ];

        foreach ($roles as $role) {
            \App\Models\Role::firstOrCreate(['slug' => $role['slug']], $role);
        }

        // Assign Roles to Users
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            if ($user->email === 'admin@storage.com') {
                $role = \App\Models\Role::where('slug', 'admin')->first();
            } elseif ($user->email === 'admin1@storage.com') {
                $role = \App\Models\Role::where('slug', 'petugas_pengajuan')->first();
            } elseif ($user->email === 'admin2@storage.com') {
                $role = \App\Models\Role::where('slug', 'manajer_persetujuan')->first();
            } elseif ($user->email === 'admin3@storage.com') {
                $role = \App\Models\Role::where('slug', 'petugas_barang_keluar')->first();
            } else {
                $role = \App\Models\Role::where('slug', 'user')->first();
            }

            if ($role) {
                $user->role_id = $role->id;
                $user->save();
            }
        }
    }
}
