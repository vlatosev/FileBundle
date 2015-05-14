<?php
namespace ED\FileBundle\ImageProcessing\Transformers;

use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;

class CropInsideTransformer extends TransformerAbstract
{
  public function getTransformed()
  {
    if(!empty($this->height) && !empty($this->width))
    {
      $sourcebox = $this->source->getSize();
      $targetbox = new Box($this->width, $this->height);
      $framebox  = $sourcebox->heighten($this->height);
      if($framebox->getWidth() < $this->width)
      {
        $framebox = $sourcebox->widen($this->width);
        $cropstart = new Point(0, floor(abs($this->height - $framebox->getHeight())/2));
      }
      else
      {
        $cropstart = new Point(floor(abs($this->width - $framebox->getWidth())/2), 0);
      }
    }
    else throw new \ImagickException("Missing parameters!");
    return $this->source->resize($framebox)->crop($cropstart, $targetbox);
  }
}