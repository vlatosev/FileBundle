<?php
namespace EDV\FileBundle\ImageProcessing\Transformers;

use Imagine\Image\Box;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Point;

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
      $palette = new RGB();
      $canvasImage = $this->imagine->create($wantedsize, $palette->color("FFFFFF"));

      $xstart = floor(($wantedsize->getWidth()  - $targetbox->getWidth())/2);
      $ystart = floor(($wantedsize->getHeight() - $targetbox->getHeight())/2);
      $startpoint  = new Point($xstart, $ystart);
      return $canvasImage->paste($resized, $startpoint);

    }
    else throw new \ImagickException("Missing parameters!");
  }

}