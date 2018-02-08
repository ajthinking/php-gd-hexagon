<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tile extends Model
{
    public function image() {
        // set up array of points for polygon
        // THIS MUST BE ORIGO TRANSLATE LATER! (COZ ROTATION)
        $startX = 0.5;
        $startY = 0;//0.5*sqrt(3)/2;
        $newX = $startX * cos(pi()/3) - $startY*sin(pi()/3);
        $newY = $startY*cos(pi()/3)+$startX*sin(pi()/3);

        $values = array(
            $startX,  $startY,  // Point 1 (x, y)
            $startX * cos(1*pi()/3) - $startY*sin(1*pi()/3),  $startY*cos(1*pi()/3)+$startX*sin(1*pi()/3), // Point 2 (x, y)
            $startX * cos(2*pi()/3) - $startY*sin(2*pi()/3),  $startY*cos(2*pi()/3)+$startX*sin(2*pi()/3),  // Point 3 (x, y)
            $startX * cos(3*pi()/3) - $startY*sin(3*pi()/3), $startY*cos(3*pi()/3)+$startX*sin(3*pi()/3),  // Point 4 (x, y)
            $startX * cos(4*pi()/3) - $startY*sin(4*pi()/3),  $startY*cos(4*pi()/3)+$startX*sin(4*pi()/3),  // Point 5 (x, y)
            $startX * cos(5*pi()/3) - $startY*sin(5*pi()/3),  $startY*cos(5*pi()/3)+$startX*sin(5*pi()/3)   // Point 6 (x, y)
            );
        
        $values = array_map(function($value) {
            return ($value + 1) * 100;
        }, $values);

        $overlay_values = array(
            $startX,  $startY,  // Point 1 (x, y)
            $startX * cos(1*pi()/3) - $startY*sin(1*pi()/3),  $startY*cos(1*pi()/3)+$startX*sin(1*pi()/3), // Point 2 (x, y)
            $startX * cos(2*pi()/3) - $startY*sin(2*pi()/3),  $startY*cos(2*pi()/3)+$startX*sin(2*pi()/3),  // Point 3 (x, y)
            0, 0
        );
        
        $overlay_values = array_map(function($value) {
            return ($value + 1) * 100;
        }, $overlay_values);        
        

        // create image
        $image = imagecreatetruecolor(250, 250);

        // allocate colors
        $bg   = imagecolorallocate($image, 0, 0, 0);
        imagecolortransparent($image, $bg);
        $grass = imagecolorallocate($image, 0, 255, 0);
        $forest = imagecolorallocate($image, 0, 100, 0);

        // fill the background
        imagefilledrectangle($image, 0, 0, 249, 249, $bg);

        // draw a background_type
        imagefilledpolygon($image, $values, 6, $grass);

        // draw a overlay_type
        imagefilledpolygon($image, $overlay_values, 4, $forest);
        
        
        return $image;
    }

    public function name() {
        return $this->background_type . "_background_with_" . $this->duration . "_" . $this->overlay_type;
    }
}
