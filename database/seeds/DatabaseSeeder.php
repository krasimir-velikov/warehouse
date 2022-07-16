<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => "Krasimir Velikov",
            'email' => 'kvelikov43@gmail.com',
            'password' => bcrypt("password"),
            'level' => 1
        ]);

//        DB::table('categories')->insert([
//            'name' => 'Clothing',
//        ]);
//        DB::table('categories')->insert([
//            'name' => 'Food',
//        ]);
//        DB::table('subcategories')->insert([
//           'name' => 'Shirts',
//           'category_id' => 1
//        ]);
//        DB::table('subcategories')->insert([
//            'name' => 'Shoes',
//            'category_id' => 1
//        ]);
//        DB::table('subcategories')->insert([
//           'name' => 'Vegetables',
//           'category_id' => 2
//        ]);
//        DB::table('subcategories')->insert([
//            'name' => 'Fruits',
//            'category_id' => 2
//        ]);
//        DB::table('suppliers')->insert([
//            'name' => 'Schneider'
//
//        ]);
    }
}
