<?php

// database/seeders/ListUniversitasSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListUniversitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('list_universitas')->insert([
            [
                'kode' => 'UNIV001',
                'nama_universitas' => 'Universitas Airlangga',
                'alamat_universitas' => 'Jl. Airlangga No.4, Surabaya, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV002',
                'nama_universitas' => 'Institut Teknologi Sepuluh Nopember',
                'alamat_universitas' => 'Jl. Raya ITS, Sukolilo, Surabaya, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV003',
                'nama_universitas' => 'Universitas Brawijaya',
                'alamat_universitas' => 'Jl. Veteran, Malang, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV004',
                'nama_universitas' => 'Universitas Negeri Surabaya',
                'alamat_universitas' => 'Jl. Lidah Wetan, Surabaya, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV005',
                'nama_universitas' => 'Universitas Jember',
                'alamat_universitas' => 'Jl. Kalimantan No.37, Jember, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV006',
                'nama_universitas' => 'Universitas Muhammadiyah Malang',
                'alamat_universitas' => 'Jl. Raya Tlogomas No.246, Malang, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV007',
                'nama_universitas' => 'Universitas Islam Negeri Sunan Ampel Surabaya',
                'alamat_universitas' => 'Jl. Ahmad Yani No.117, Surabaya, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV008',
                'nama_universitas' => 'Universitas Islam Malang',
                'alamat_universitas' => 'Jl. Mayjen Haryono No.193, Malang, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV009',
                'nama_universitas' => 'Universitas Negeri Malang',
                'alamat_universitas' => 'Jl. Semarang No.5, Malang, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV010',
                'nama_universitas' => 'Universitas Trunojoyo Madura',
                'alamat_universitas' => 'Jl. Raya Telang, Bangkalan, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV011',
                'nama_universitas' => 'Universitas PGRI Adi Buana Surabaya',
                'alamat_universitas' => 'Jl. Dukuh Menanggal XII, Surabaya, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV012',
                'nama_universitas' => 'Universitas Kristen Petra',
                'alamat_universitas' => 'Jl. Siwalankerto No.121-131, Surabaya, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV013',
                'nama_universitas' => 'Universitas Katolik Widya Mandala Surabaya',
                'alamat_universitas' => 'Jl. Dinoyo No.42-44, Surabaya, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV014',
                'nama_universitas' => 'Universitas Muhammadiyah Gresik',
                'alamat_universitas' => 'Jl. Sumatra No.101, Gresik, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV015',
                'nama_universitas' => 'Universitas Islam Jember',
                'alamat_universitas' => 'Jl. Kyai Mojo No.101, Jember, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV016',
                'nama_universitas' => 'Universitas Muhammadiyah Ponorogo',
                'alamat_universitas' => 'Jl. Budi Utomo No.10, Ponorogo, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV017',
                'nama_universitas' => 'Universitas Merdeka Malang',
                'alamat_universitas' => 'Jl. Terusan Dieng No.62-64, Malang, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV018',
                'nama_universitas' => 'Universitas Wijaya Kusuma Surabaya',
                'alamat_universitas' => 'Jl. Dukuh Kupang XXV No.54, Surabaya, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV019',
                'nama_universitas' => 'Universitas 17 Agustus 1945 Surabaya',
                'alamat_universitas' => 'Jl. Semolowaru No.45, Surabaya, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV020',
                'nama_universitas' => 'Universitas Muhammadiyah Sidoarjo',
                'alamat_universitas' => 'Jl. Mojopahit No.666 B, Sidoarjo, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV021',
                'nama_universitas' => 'Universitas PGRI Madiun',
                'alamat_universitas' => 'Jl. Setiabudi No.85, Madiun, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV022',
                'nama_universitas' => 'Universitas Muhammadiyah Jember',
                'alamat_universitas' => 'Jl. Karimata No.49, Jember, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV023',
                'nama_universitas' => 'Universitas Islam Lamongan',
                'alamat_universitas' => 'Jl. Veteran No.53, Lamongan, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV024',
                'nama_universitas' => 'Universitas Pesantren Tinggi Darul Ulum',
                'alamat_universitas' => 'Jl. Raya Peterongan, Jombang, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV025',
                'nama_universitas' => 'Universitas Pembangunan Nasional Veteran Jawa Timur',
                'alamat_universitas' => 'Jl. Raya Rungkut Madya, Surabaya, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV026',
                'nama_universitas' => 'Universitas dr. Soetomo',
                'alamat_universitas' => 'Jl. Semolowaru No.84, Surabaya, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV027',
                'nama_universitas' => 'Universitas Negeri Malang Kampus II Blitar',
                'alamat_universitas' => 'Jl. Kalimantan No.37, Blitar, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV028',
                'nama_universitas' => 'Universitas Islam Darul Ulum Lamongan',
                'alamat_universitas' => 'Jl. Airlangga No.3, Lamongan, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV029',
                'nama_universitas' => 'Universitas Kadiri',
                'alamat_universitas' => 'Jl. Selomangleng No.1, Kediri, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'UNIV030',
                'nama_universitas' => 'Universitas Nahdlatul Ulama Blitar',
                'alamat_universitas' => 'Jl. Masjid No.22, Blitar, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

