<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 7/16/15
 * Time: 2:00 PM
 */

namespace EDV\FileBundle\ImageProcessing;


use Doctrine\ORM\EntityManagerInterface;
use EDV\FileBundle\Entity\EdImage;
use Symfony\Component\HttpFoundation\File\File;

class ImageManager
{
  private $image_class;

  /**
   * @var ImageProcessor
   */
  private $imgproc;

  public function __construct(ImageProcessor $img_processor, $imgClass)
  {
    $this->image_class = $imgClass;
    $this->imgproc = $img_processor;
  }

  public function updateImage(EdImage $image, EntityManagerInterface $em)
  {
    $source = $image->getFile()->getUploadFile();
    if($source instanceof File)
    {
      $imagine = $this->imgproc->getImagine();
      $file = $imagine->open($source->getPathname());
      $island = $this->imgproc->isLandscapeOrient($file);
      if($island)
      {
        $image->setWidth($file->getSize()->getWidth());
        $image->setHeight($file->getSize()->getHeight());
      }
      else
      {
        $image->setHeight($file->getSize()->getWidth());
        $image->setWidth($file->getSize()->getHeight());
      }
      $image->setProcessed(true);
      $image->setHashString($this->getUniqueCachedName($em));
      $image->setArea([]);
    }
  }

  protected function getUniqueCachedName(EntityManagerInterface $em)
  {
    do
    {
      $fileName = uniqid('', true);
      $fileName = implode('-', array_reverse(explode('.', $fileName)));
    }
    while($this->uniqueNameExists($fileName, $em));
    return $fileName;
  }

  /**
   * @param $fileName
   * @return bool
   */
  protected function uniqueNameExists($fileName, EntityManagerInterface $em)
  {
    $dql = "SELECT COUNT(image) FROM EDVFileBundle:EdImage AS image WHERE image.hashString = :name";
    $query = $em->createQuery($dql)->setParameters(array('name' => $fileName));
    $result = $query->getSingleScalarResult();
    return ($result > 0);
  }

}