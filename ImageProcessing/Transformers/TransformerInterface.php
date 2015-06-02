<?php
namespace EDV\FileBundle\ImageProcessing\Transformers;

use Imagine\Image\ImageInterface;

interface TransformerInterface
{
  public function __construct(ImageInterface $image, $width = null, $height = null);

  public function getTransformed();
}