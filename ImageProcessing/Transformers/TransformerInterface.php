<?php
namespace EDV\FileBundle\ImageProcessing\Transformers;

use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;

interface TransformerInterface
{
  public function __construct(ImagineInterface $imagine, ImageInterface $image, $width = null, $height = null);

  /**
   * @return ImageInterface
   */
  public function getTransformed();
}