<?php

use App\Models\Album;
use Illuminate\Database\Seeder;

class SeedAlbumTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
/*        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Album::truncate();*/
        factory(App\Models\Album::class, 10)->create();

    }
}
