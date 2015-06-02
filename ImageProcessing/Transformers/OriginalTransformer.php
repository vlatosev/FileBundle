<?php
namespace EDV\FileBundle\ImageProcessing\Transformers;

use Imagine\Image\ImageInterface;

/**
 * Returns original picture as it is uploaded
 *
 * Class OriginalTransformer
 * @package ED\FileBundle\ImageProcessing\Transformers
 */
class OriginalTransformer extends  TransformerAbstract
{

  public function getTransformed()
  {
    return $this->source;
  }
}