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

        DB::table('active_emails')->insert([
            'id' => 1,
            'email' => 'kvelikov43@gmail.com',
            'user_id' => 1,
        ]);
    }
}
