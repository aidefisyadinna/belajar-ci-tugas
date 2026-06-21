<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama' => 'Chardwake Jam Tangan Wanita',
                'harga'  => 217600,
                'jumlah' => 100,
                'foto' => '1781538426_01e519cad29b10111d05.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama' => 'Birth Stone Bracelet May Emerald YG',
                'harga'  => 168000,
                'jumlah' => 50,
                'foto' => '1781538471_2d89f0cea236ddf8aab8.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama' => 'Birth Stone Pendant February Amethyst RG',
                'harga'  => 160000,
                'jumlah' => 75,
                'foto' => '1781538408_09f7592c2711706d1fbc.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama' => 'Spiral Earrings RG',
                'harga'  => 128000,
                'jumlah' => 80,
                'foto' => '1781538340_302228573abef37c02ab.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama' => 'Cincin Wanita R027 Korea Elegan Butterfly Style',
                'harga'  => 120000,
                'jumlah' => 60,
                'foto' => '1781538238_e031bcc5cc8e0fbe3d7f.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama' => 'Clara Ladies Belt',
                'harga'  => 259300,
                'jumlah' => 70,
                'foto' => '1781538563_d0ca0228733d4aa8f690.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama' => 'Chardwake Gelang Wanita',
                'harga'  => 128000,
                'jumlah' => 47,
                'foto' => '1781538795_e52165a990d00efff763.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama' => 'Gadish Sunglasses',
                'harga'  => 512000,
                'jumlah' => 30,
                'foto' => '1781538872_e53f335a109cc2779238.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama' => 'VIVAN Smart Watch TFT 1.83"',
                'harga'  => 420000,
                'jumlah' => 15,
                'foto' => '1781539026_edbe1cc24fbb2b6340ea.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($data as $item) {
            $this->db->table('product')->insert($item);
        }
    }
}
