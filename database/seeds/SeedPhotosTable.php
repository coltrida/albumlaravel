<?php

use App\Models\Album;
use App\Models\Photo;
use Illuminate\Database\Seeder;

class SeedPhotosTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
/*        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Photo::truncate();*/

        // per ogni album voglio 200 foto

        $albums = Album::get();
        foreach ($albums as $album){
            factory(App\Models\Photo::class, 200)->create(
                ['album_id' => $album->id]
            );
        }



    }
}
