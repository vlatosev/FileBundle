<?php
namespace EDV\FileBundle\ImageProcessing\Transformers;

use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FullImageTransformer extends TransformerAbstract
{
  public function getTransformed()
  {
    $emptwidth  = empty($this->width);
    $emptheight = empty($this->height);
    $sourcebox = $this->source->getSize();
    if(!$emptwidth && !$emptheight)
    {
      $targetbox = new Box($this->width, $this->height);
    }
    elseif($emptwidth && !$emptheight)
    {
      $targetbox = $sourcebox->heighten($this->height);
    }
    elseif(!$emptwidth && $emptheight)
    {
      $targetbox = $sourcebox->widen($this->width);
    }
    else throw new \ImagickException("Missing parameters!");
    return $this->source->resize($targetbox);
  }
}