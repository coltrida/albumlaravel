<?php


use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class SeedUserTable extends Seeder
{
    public function run()
    {
/*      $sql = 'INSERT INTO users (name, email, password, created_at)
        value (:name, :email, :password, :created_at)';

        for($i=0; $i<31; $i++){
        DB::statement($sql, [
            'name' => 'davide'.$i,
            'email' => $i.'ciao@gmail.com',
            'password' => bcrypt('davide'),
            'created_at' => Carbon::now()   //date('y-m-d H:i:s')
        ]);
        }*/

/*        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        User::truncate();*/

        $users = factory(App\User::class, 30)->create();

    }
}
