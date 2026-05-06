<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        DB::table('users')->insert([
            ['name' => 'Administrators', 'email' => 'admin@aptiekasmap.lv', 'password' => Hash::make('admin123'), 'role' => 'admin',        'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Jānis Bērziņš',  'email' => 'janis@gmail.com',      'password' => Hash::make('user123'),  'role' => 'user',         'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Anna Kalniņa',    'email' => 'anna@gmail.com',       'password' => Hash::make('user123'),  'role' => 'user',         'created_at' => now(), 'updated_at' => now()],
            ['name' => 'BENU Pārstāvis',  'email' => 'benu@benu.lv',         'password' => Hash::make('pharm123'), 'role' => 'pharmacy_rep', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Medicines
        DB::table('medicines')->insert([
            ['name' => 'Paracetamol BENU',   'active_substance' => 'Paracetamols',    'form' => 'Tabletes',  'dose' => '500mg',    'manufacturer' => 'BENU Pharma',   'description' => 'Pretsāpju un pretdrudža līdzeklis',    'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ibuprofen Grindeks', 'active_substance' => 'Ibuprofēns',      'form' => 'Tabletes',  'dose' => '400mg',    'manufacturer' => 'Grindeks AS',    'description' => 'Pretiekaisuma un pretsāpju līdzeklis', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Amoxicillin Sandoz', 'active_substance' => 'Amoksicilīns',   'form' => 'Kapsulas',  'dose' => '500mg',    'manufacturer' => 'Sandoz',         'description' => 'Plaša spektra antibiotiķis',           'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Loratadīns Teva',    'active_substance' => 'Loratadīns',     'form' => 'Tabletes',  'dose' => '10mg',     'manufacturer' => 'Teva',           'description' => 'Pretalerģijas līdzeklis',              'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Omeprazols EG',      'active_substance' => 'Omeprazols',     'form' => 'Kapsulas',  'dose' => '20mg',     'manufacturer' => 'EG Labo',        'description' => 'Kuņģa skābes mazinātājs',              'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ārstnieciskais sīrups','active_substance'=>'Dekstrometorfāns','form' => 'Šķidrums', 'dose' => '15mg/5ml', 'manufacturer' => 'Nycomed',        'description' => 'Klepus nomākšanai',                   'created_at' => now(), 'updated_at' => now()],
        ]);

        // Pharmacies
        DB::table('pharmacies')->insert([
            ['name' => 'BENU Aptieka Brīvības',  'address' => 'Brīvības iela 85, Rīga',     'latitude' => 56.9560123, 'longitude' => 24.1234567, 'phone' => '+37167001111', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mēness Aptieka Centrs',  'address' => 'Elizabetes iela 10, Rīga',   'latitude' => 56.9510000, 'longitude' => 24.1150000, 'phone' => '+37167002222', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Euroaptieka Imanta',     'address' => 'Imanta 7. līnija 1, Rīga',   'latitude' => 56.9310000, 'longitude' => 24.0200000, 'phone' => '+37167003333', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dr. Tālivaldis Aptieka', 'address' => 'Dzirnavu iela 55, Rīga',     'latitude' => 56.9490000, 'longitude' => 24.1100000, 'phone' => '+37167004444', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Apotheka Pārdaugava',    'address' => 'Kurzemes prospekts 3, Rīga', 'latitude' => 56.9400000, 'longitude' => 24.0800000, 'phone' => '+37167005555', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Availability
        DB::table('availability')->insert([
            ['pharmacy_id'=>1,'medicine_id'=>1,'quantity'=>50, 'price'=>1.45,'status'=>'available',  'created_at'=>now(),'updated_at'=>now()],
            ['pharmacy_id'=>1,'medicine_id'=>2,'quantity'=>30, 'price'=>2.10,'status'=>'available',  'created_at'=>now(),'updated_at'=>now()],
            ['pharmacy_id'=>1,'medicine_id'=>3,'quantity'=>0,  'price'=>5.80,'status'=>'unavailable','created_at'=>now(),'updated_at'=>now()],
            ['pharmacy_id'=>2,'medicine_id'=>1,'quantity'=>20, 'price'=>1.35,'status'=>'available',  'created_at'=>now(),'updated_at'=>now()],
            ['pharmacy_id'=>2,'medicine_id'=>2,'quantity'=>5,  'price'=>1.99,'status'=>'available',  'created_at'=>now(),'updated_at'=>now()],
            ['pharmacy_id'=>2,'medicine_id'=>4,'quantity'=>40, 'price'=>3.20,'status'=>'available',  'created_at'=>now(),'updated_at'=>now()],
            ['pharmacy_id'=>3,'medicine_id'=>1,'quantity'=>100,'price'=>1.29,'status'=>'available',  'created_at'=>now(),'updated_at'=>now()],
            ['pharmacy_id'=>3,'medicine_id'=>5,'quantity'=>15, 'price'=>4.50,'status'=>'available',  'created_at'=>now(),'updated_at'=>now()],
            ['pharmacy_id'=>3,'medicine_id'=>6,'quantity'=>8,  'price'=>6.75,'status'=>'available',  'created_at'=>now(),'updated_at'=>now()],
            ['pharmacy_id'=>4,'medicine_id'=>3,'quantity'=>12, 'price'=>5.90,'status'=>'available',  'created_at'=>now(),'updated_at'=>now()],
            ['pharmacy_id'=>4,'medicine_id'=>4,'quantity'=>25, 'price'=>3.10,'status'=>'available',  'created_at'=>now(),'updated_at'=>now()],
            ['pharmacy_id'=>5,'medicine_id'=>1,'quantity'=>60, 'price'=>1.40,'status'=>'available',  'created_at'=>now(),'updated_at'=>now()],
            ['pharmacy_id'=>5,'medicine_id'=>5,'quantity'=>20, 'price'=>4.30,'status'=>'available',  'created_at'=>now(),'updated_at'=>now()],
        ]);
    }
}
