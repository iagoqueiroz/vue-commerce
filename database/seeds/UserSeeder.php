<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'name'     => 'Administrator',
            'email'    => 'admin@system.com',
            'password' => bcrypt('admin'),
            'admin'    => 1,
        ]);

        factory(User::class)->create([
            'name'     => 'User',
            'email'    => 'user@system.com',
            'password' => bcrypt('user'),
            'admin'    => 0,
        ]);
    }
}
