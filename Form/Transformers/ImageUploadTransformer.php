<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 7/19/15
 * Time: 11:51 PM
 */

namespace EDV\FileBundle\Form\Transformers;


use EDV\FileBundle\Entity\EdImage;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploadTransformer implements DataTransformerInterface
{

  public function transform($value)
  {
    return $value;
  }

  public function reverseTransform($value)
  {
    if($value instanceof EdImage && $value->getFile()->getUploadFile() instanceof UploadedFile)
    {
      $value->setProcessed(!$value->getProcessed());
    }
    return $value;
  }
}