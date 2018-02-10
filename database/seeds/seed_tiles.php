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
        $t->duration = 1;
        $t->save();

        $t = new Tile();
        $t->background_type = "grass";
        $t->overlay_type = "forest";
        $t->duration = 2;
        $t->save();
        
        $t = new Tile();
        $t->background_type = "grass";
        $t->overlay_type = "forest";
        $t->duration = 3;
        $t->save();
        
        $t = new Tile();
        $t->background_type = "grass";
        $t->overlay_type = "forest";
        $t->duration = 4;
        $t->save();
        
        $t = new Tile();
        $t->background_type = "grass";
        $t->overlay_type = "forest";
        $t->duration = 5;
        $t->save();
        
        

        $t = new Tile();
        $t->background_type = "grass";
        $t->overlay_type = "water";
        $t->duration = 1;
        $t->save();

        $t = new Tile();
        $t->background_type = "grass";
        $t->overlay_type = "water";
        $t->duration = 2;
        $t->save();
        
        $t = new Tile();
        $t->background_type = "grass";
        $t->overlay_type = "water";
        $t->duration = 3;
        $t->save();
        
        $t = new Tile();
        $t->background_type = "grass";
        $t->overlay_type = "water";
        $t->duration = 4;
        $t->save();
        
        $t = new Tile();
        $t->background_type = "grass";
        $t->overlay_type = "water";
        $t->duration = 5;
        $t->save();
        

        
        $t = new Tile();
        $t->background_type = "forest";
        $t->overlay_type = "water";
        $t->duration = 1;
        $t->save();

        $t = new Tile();
        $t->background_type = "forest";
        $t->overlay_type = "water";
        $t->duration = 2;
        $t->save();
        
        $t = new Tile();
        $t->background_type = "forest";
        $t->overlay_type = "water";
        $t->duration = 3;
        $t->save();
        
        $t = new Tile();
        $t->background_type = "forest";
        $t->overlay_type = "water";
        $t->duration = 4;
        $t->save();
        
        $t = new Tile();
        $t->background_type = "forest";
        $t->overlay_type = "water";
        $t->duration = 5;
        $t->save();        
    }
}
