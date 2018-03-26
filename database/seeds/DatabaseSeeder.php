<?php

use Illuminate\Database\Seeder;
use App\Models\Album;
use App\Models\Photo;
use App\User;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Album::truncate();
        Photo::truncate();
        User::truncate();

        $this->call(SeedUserTable::class);
        $this->call(SeedAlbumTable::class);
        $this->call(SeedPhotosTable::class);
    }
}
