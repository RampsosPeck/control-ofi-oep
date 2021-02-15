<?php

use App\Cargo;
use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cargo = new Cargo;
        $cargo->nombre = 'Programmer';
        $cargo->Fecha = '2021-02-12';
        $cargo->save();

        $suadmin = new User;
        $suadmin->name = 'Jorge Peralta';
        $suadmin->cedula = '10519606';
        $suadmin->telefono = '75729198';
        $suadmin->type = 'admin';
        $suadmin->cargo_id = 1;
        $suadmin->email = 'jorge@gmail.com';
        $suadmin->password = bcrypt('secret');
        $suadmin->bio = 'Mentrax web systems developer and security partner';
        $suadmin->save();
    }
}
