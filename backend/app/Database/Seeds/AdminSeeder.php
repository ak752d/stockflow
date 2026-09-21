<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $this->db->table('users')->insert([
            'name'       => 'Admin',
            'email'      => 'admin@stockflow.test',
            'password'   => password_hash('Admin123!', PASSWORD_DEFAULT),
            'role'       => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
