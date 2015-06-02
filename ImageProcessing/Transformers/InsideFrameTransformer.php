<?php
namespace EDV\FileBundle\ImageProcessing\Transformers;

class InsideFrameTransformer extends TransformerAbstract
{
  public function getTransformed()
  {
    if(!empty($this->height) && !empty($this->width))
    {
      $sourcebox = $this->source->getSize();
      $widthbox  = $sourcebox->widen($this->width);
      $heightbox = $sourcebox->heighten($this->height);
      if($widthbox->getHeight() > $this->height) return $this->source->resize($heightbox);
      else return $this->source->resize($widthbox);
    }
    else throw new \ImagickException("Missing parameters!");
  }
}