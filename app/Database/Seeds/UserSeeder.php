<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('user')->insert([
            'username'   => 'admin',
            'email'      => 'admin@example.com',
            'password'   => password_hash('1234567', PASSWORD_DEFAULT),
            'role'       => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->db->table('user')->insert([
            'username'   => 'user',
            'email'      => 'user@example.com',
            'password'   => password_hash('1234567', PASSWORD_DEFAULT),
            'role'       => 'user',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $faker = \Faker\Factory::create('id_ID');

        for ($i = 0; $i < 8; $i++) {
            $data = [
                'username'   => $faker->userName,
                'email'      => $faker->email,
                'password'   => password_hash('1234567', PASSWORD_DEFAULT),
                'role'       => 'user',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $this->db->table('user')->insert($data);
        }
    }
}