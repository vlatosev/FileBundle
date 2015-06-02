<?php
namespace EDV\FileBundle\ImageProcessing\Transformers;

use Imagine\Image\ImageInterface;

abstract class TransformerAbstract
{
  /**
   * @var \Imagine\Image\ImageInterface
   */
  protected $source;

  protected $width;

  protected $height;

  public function __construct(ImageInterface $source, $width = null, $height = null)
  {
    $this->source = $source;
    $this->width = $width;
    $this->height = $height;
  }
}