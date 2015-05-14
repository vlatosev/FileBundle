<?php
namespace ED\FileBundle\ImageProcessing\Transformers;

use Imagine\Image\Box;
use Imagine\Image\Color;
use Imagine\Image\Point;
use Imagine\Imagick\Imagine;

class AddBlankAreaTransformer extends TransformerAbstract
{
  public function getTransformed()
  {
    if(!empty($this->height) && !empty($this->width))
    {
      $sourcebox = $this->source->getSize();
      $widthbox  = $sourcebox->widen($this->width);
      $heightbox = $sourcebox->heighten($this->height);
      if($widthbox->getHeight() > $this->height)
      {
        $resized = $this->source->resize($heightbox);
        $targetbox = $heightbox;
      }
      else
      {
        $resized = $this->source->resize($widthbox);
        $targetbox = $widthbox;
      }

      $wantedsize = new Box($this->width, $this->height);

      $canvasImage = extension_loaded('imagick') ? new Imagine() : new \Imagine\Gd\Imagine();
      $backcolor = new Color('fff');
      $canvasImage = $canvasImage->create($wantedsize,$backcolor);
      $xstart = floor(($wantedsize->getWidth()  - $targetbox->getWidth())/2);
      $ystart = floor(($wantedsize->getHeight() - $targetbox->getHeight())/2);
      $startpoint  = new Point($xstart, $ystart);
      return $canvasImage->paste($resized, $startpoint);

    }
    else throw new \ImagickException("Missing parameters!");
  }

}