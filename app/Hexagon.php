<?php

namespace App;

class Hexagon {

    protected $image_x_resolution;
    protected $image_y_resolution;

    const DEFAULT_X_RESOLUTION = 1500;

    public function __construct($image_x_resolution = Hexagon::DEFAULT_X_RESOLUTION) {
        $this->image_x_resolution = $image_x_resolution;
        $this->image_y_resolution = floor($image_x_resolution * sqrt(3)/2);
    }

    public function hexagon() {
        $startX = 0.5;
        $startY = 0;
        $newX = $startX * cos(pi()/3) - $startY*sin(pi()/3);
        $newY = $startY*cos(pi()/3)+$startX*sin(pi()/3);

        $coordinates = collect([
            $startX,  $startY,
            $startX * cos(1*pi()/3) - $startY*sin(1*pi()/3),  $startY*cos(1*pi()/3)+$startX*sin(1*pi()/3), // Point 2 (x, y)
            $startX * cos(2*pi()/3) - $startY*sin(2*pi()/3),  $startY*cos(2*pi()/3)+$startX*sin(2*pi()/3),  // Point 3 (x, y)
            $startX * cos(3*pi()/3) - $startY*sin(3*pi()/3), $startY*cos(3*pi()/3)+$startX*sin(3*pi()/3),  // Point 4 (x, y)
            $startX * cos(4*pi()/3) - $startY*sin(4*pi()/3),  $startY*cos(4*pi()/3)+$startX*sin(4*pi()/3),  // Point 5 (x, y)
            $startX * cos(5*pi()/3) - $startY*sin(5*pi()/3),  $startY*cos(5*pi()/3)+$startX*sin(5*pi()/3)   // Point 6 (x, y)
        ]);
        
        $coordinates = $coordinates->map(function($value, $key) {
            if($key % 2 == 0) {
                return ($value + 0.5)*$this->image_x_resolution;
            }
            return ($value + 0.5*sqrt(3)/2)*$this->image_x_resolution;
        });
        
        return $coordinates->toArray();
    }

    public function segment($duration) {
        $startX = 0.5;
        $startY = 0;
        $overlay_values = collect([
            $startX,  $startY,
            //$startX * cos(1*pi()/3) - $startY*sin(1*pi()/3),  $startY*cos(1*pi()/3)+$startX*sin(1*pi()/3),
        ]);
        
        for($i = 1; $i <= $duration; $i++) {
             $overlay_values->push($startX * cos($i*pi()/3) - $startY*sin($i*pi()/3));
             $overlay_values->push($startY*cos($i*pi()/3)+$startX*sin($i*pi()/3));
        }
        $overlay_values->push(0);
        $overlay_values->push(0);

        $overlay_values = $overlay_values->map(function($value, $key) {
            if($key % 2 == 0) {
                return ($value + 0.5)*$this->image_x_resolution;
            }
            return ($value + 0.5*sqrt(3)/2)*$this->image_x_resolution;
        });
        
        return $overlay_values->toArray();;
    }

    public function saveHexagonMask() {
        // create image
        $image = imagecreatetruecolor($this->image_x_resolution, $this->image_y_resolution);

        // allocate colors
        $bgColor   = imagecolorallocate($image, 0, 0, 0);
        //imagecolortransparent($image, $bgColor);
        $maskColor = imagecolorallocate($image, 255, 255, 255);

        // transparency, background and overlay
        imagefilledrectangle($image, 0, 0, $this->image_x_resolution - 1, $this->image_y_resolution -1, $bgColor);
        imagefilledpolygon($image, $this->hexagon(), sizeof($this->hexagon())/2, $maskColor);
        imagepng($image, "/home/anders/Code/hex/public/img/hexagonMask.png");
        return "OK";       
    }

    public function saveSegmentMask($t) {
        // create image
        $image = imagecreatetruecolor($this->image_x_resolution, $this->image_y_resolution);

        // allocate colors
        $bgColor   = imagecolorallocate($image, 0, 0, 0);
        //imagecolortransparent($image, $bgColor);
        $maskColor = imagecolorallocate($image, 255, 255, 255);

        // transparency, background and overlay
        imagefilledrectangle($image, 0, 0, $this->image_x_resolution - 1, $this->image_y_resolution -1, $bgColor);
        imagefilledpolygon($image, $this->segment($t->duration), sizeof($this->segment($t->duration))/2, $maskColor);
        imagepng($image, "/home/anders/Code/hex/public/img/segmentMask.png");
        return "OK";        
    }
}