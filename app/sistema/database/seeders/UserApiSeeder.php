<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Hash;

class UserApiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create example user for authentication in API. This insert is just for development.
        DB::table('userapi')->insert([
            'name' => 'Usuario API',
            'email' => 'autentica@api.com',
            'password' => Hash::make('123456'),
        ]);
    }
}
