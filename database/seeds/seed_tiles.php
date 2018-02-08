<?php


use Illuminate\Database\Seeder;
use App\Tile;

class seed_tiles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $t = new Tile();
        $t->background_type = "grass";
        $t->overlay_type = "forest";
        $t->duration = 3;
        $t->save();
    }
}
