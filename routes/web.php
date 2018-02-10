<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    $tile = \App\Tile::first();    
    imagepng($tile->image(), "/home/anders/Code/hex/public/img/" . $t->name() . ".png");
});


Route::get('/masktest', function () {
    \App\Tile::maskTest();
});

Route::get('/saveHexagonMask', function () {
    (new \App\Hexagon)->saveHexagonMask();
});

Route::get('/saveSegmentMask', function () {
    $tile = \App\Tile::first();
    (new \App\Hexagon)->saveSegmentMask($tile);
});

Route::get('overlay', function () {
    $t = \App\Tile::first();    
    $background = imagecreatefromjpeg( '/home/anders/Code/hex/public/img/terrain/grass.jpg' );
    $hexagonMask = imagecreatefrompng( '/home/anders/Code/hex/public/img/hexagonMask.png' );
    $background = \App\Tile::imagealphamask( $background, $hexagonMask, $background );

    // Load source and mask
    $source = imagecreatefromjpeg( '/home/anders/Code/hex/public/img/terrain/forest.jpg' );
    $segmentMask = imagecreatefrompng( '/home/anders/Code/hex/public/img/segmentMask.png' );
    // Apply mask to source
    $overlay = \App\Tile::imagealphamask( $source, $segmentMask, $source );

    // Overlay segment
    // Cut to hexagon
    imagepng($background, "/home/anders/Code/hex/public/img/layer1.png");
    imagepng($overlay, "/home/anders/Code/hex/public/img/layer2.png");

    $result = $background;

    imagecopy($result, $background,0,0,0,0,$t->image_x_resolution, $t->image_y_resolution);
    imagecopy($result, $overlay,0,0,0,0,$t->image_x_resolution, $t->image_y_resolution);
    imagepng($result, "/home/anders/Code/hex/public/img/myFirst.png");

});

Route::get('/saveAll', function () {
    $tiles = \App\Tile::all();
    $tiles->each(function($tile) {
        $background = imagecreatefromjpeg( '/home/anders/Code/hex/public/img/terrain/' . $tile->background_type . '.jpg' );
        $hexagonMask = imagecreatefrompng( '/home/anders/Code/hex/public/img/hexagonMask.png' );
        $background = \App\Tile::imagealphamask( $background, $hexagonMask, $background );

        $source = imagecreatefromjpeg( '/home/anders/Code/hex/public/img/terrain/' . $tile->overlay_type .'.jpg' );
        (new \App\Hexagon)->saveSegmentMask($tile);
        $segmentMask = imagecreatefrompng( '/home/anders/Code/hex/public/img/segmentMask.png' );
        $overlay = \App\Tile::imagealphamask( $source, $segmentMask, $source );

        imagepng($background, "/home/anders/Code/hex/public/img/layer1.png");
        imagepng($overlay, "/home/anders/Code/hex/public/img/layer2.png");
    
        $result = $background;
    
        imagecopy($result, $background,0,0,0,0,$tile->image_x_resolution, $tile->image_y_resolution);
        imagecopy($result, $overlay,0,0,0,0,$tile->image_x_resolution, $tile->image_y_resolution);
        imagepng($result, "/home/anders/Code/hex/public/img/output/".  rand(1,1000) ."png");        
    });

    


});

