<?php
namespace EDV\FileBundle\ImageProcessing\Transformers;

use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;

abstract class TransformerAbstract
{
  /**
   * @var ImagineInterface
   */
  protected $imagine;

  /**
   * @var \Imagine\Image\ImageInterface
   */
  protected $source;

  protected $width;

  protected $height;

  public function __construct(ImagineInterface $imagine, ImageInterface $source, $width = null, $height = null)
  {
    $this->source = $source;
    $this->width = $width;
    $this->height = $height;
    $this->imagine = $imagine;
  }
}