<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Hexagon;


class Tile extends Model
{
    protected $hexagon;

    public function __construct($attributes = array()) {
        parent::__construct($attributes);
        $this->hexagon = new Hexagon();
    }

    public function image() {
        // create image
        $image = imagecreatetruecolor($this->image_x_resolution, $this->image_y_resolution);

        // allocate colors
        $bg   = imagecolorallocate($image, 0, 0, 0);
        imagecolortransparent($image, $bg);
        $grass = imagecolorallocate($image, 0, 255, 0);
        $forest = imagecolorallocate($image, 0, 100, 0);

        // transparency, background and overlay
        imagefilledrectangle($image, 0, 0, $this->image_x_resolution - 1, $this->image_y_resolution -1, $bg);
        imagefilledpolygon($image, $this->hexagon->hexagon(), sizeof($this->hexagon->hexagon())/2, $grass);
        imagefilledpolygon($image, $this->hexagon->segment(), sizeof($this->hexagon->segment())/2, $forest);
        
        return $image;
    }

    public function name() {
        return $this->background_type . "_background_with_" . $this->duration . "_" . $this->overlay_type;
    }

    public static function maskTest() {
        // Load source and mask
        $source = imagecreatefrompng( '/home/anders/Code/hex/public/img/maskTest1.png' );
        $mask = imagecreatefrompng( '/home/anders/Code/hex/public/img/maskTest2.png' );
        // Apply mask to source
        Tile::imagealphamask( $source, $mask, $source );
    }

    public static function imagealphamask( &$picture, $mask, $source ) {
        // Get sizes and set up new picture
        $xSize = imagesx( $picture );
        $ySize = imagesy( $picture );
        $newPicture = imagecreatetruecolor( $xSize, $ySize );
        imagesavealpha( $newPicture, true );
        imagefill( $newPicture, 0, 0, imagecolorallocatealpha( $newPicture, 0, 0, 0, 127 ) );

        // Resize mask if necessary
        if( $xSize != imagesx( $mask ) || $ySize != imagesy( $mask ) ) {
            $tempPic = imagecreatetruecolor( $xSize, $ySize );
            imagecopyresampled( $tempPic, $mask, 0, 0, 0, 0, $xSize, $ySize, imagesx( $mask ), imagesy( $mask ) );
            imagedestroy( $mask );
            $mask = $tempPic;
        }

        // Perform pixel-based alpha map application
        for( $x = 0; $x < $xSize; $x++ ) {
            for( $y = 0; $y < $ySize; $y++ ) {
                $alpha = imagecolorsforindex( $mask, imagecolorat( $mask, $x, $y ) );

                    if(($alpha['red'] == 0) && ($alpha['green'] == 0) && ($alpha['blue'] == 0) && ($alpha['alpha'] == 0))
                    {
                        // It's a black part of the mask
                        imagesetpixel( $newPicture, $x, $y, imagecolorallocatealpha( $newPicture, 0, 0, 0, 127 ) ); // Stick a black, but totally transparent, pixel in.
                    }
                    else
                    {

                        // Check the alpha state of the corresponding pixel of the image we're dealing with.    
                        $alphaSource = imagecolorsforindex( $source, imagecolorat( $source, $x, $y ) );

                        if(($alphaSource['alpha'] == 127))
                        {
                            imagesetpixel( $newPicture, $x, $y, imagecolorallocatealpha( $newPicture, 0, 0, 0, 127 ) ); // Stick a black, but totally transparent, pixel in.
                        } 
                        else
                        {
                            $color = imagecolorsforindex( $source, imagecolorat( $source, $x, $y ) );
                            imagesetpixel( $newPicture, $x, $y, imagecolorallocatealpha( $newPicture, $color[ 'red' ], $color[ 'green' ], $color[ 'blue' ], $color['alpha'] ) ); // Stick the pixel from the source image in
                        }


                    }
            }
        }

        // Copy back to original picture
        imagedestroy( $picture );
        $picture = $newPicture;
        return $picture;
    }
    
    public function getBaseImage() {
        return imagecreatetruecolor($this->image_x_resolution, $this->image_y_resolution);
    }

    public static function saveAll() {
        $tiles = \App\Tile::where("id", "<", 2)->get();
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

            imagesetthickness($result, 20);
            imageline($result, 1500, 0.5*1500*sqrt(3)/2, 700, 0.5*1500*sqrt(3)/2, imagecolorallocate($result, 92,64,51));


            imagepng($result, "/home/anders/Code/hex/public/img/output/".  rand(1,1000) .".png");        
        });        
    }

        
}
