<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 7/16/15
 * Time: 2:00 PM
 */

namespace EDV\FileBundle\ImageProcessing;


use Doctrine\ORM\EntityManagerInterface;
use EDV\FileBundle\Entity\EdFile;
use EDV\FileBundle\Entity\EdImage;
use EDV\FileBundle\FileServices\FileManager;

class ImageManager
{
  /**
   * @var EntityManagerInterface
   */
  private $em;

  private $image_class;

  /**
   * @var ImageProcessor
   */
  private $imgproc;

  /**
   * @var FileManager
   */
  private $fileman;

  public function __construct(EntityManagerInterface $em, ImageProcessor $img_processor, $imgClass, FileManager $fileManager)
  {
    $this->em = $em;
    $this->image_class = $imgClass;
    $this->imgproc = $img_processor;
    $this->fileman = $fileManager;
  }

  public function updateImage(EdFile $file)
  {
    $image = $this->em->getRepository($this->image_class)->findOneBy([
        'file' => $file
    ]);
    if($image instanceof EdImage)
    {
      $source = $this->fileman->getFile($file);
      if(!is_null($source))
      {
        $imagine = $this->imgproc->getImagine();
        $file = $imagine->open($source->getPathname());
        $image->setWidth($file->getSize()->getWidth());
        $image->setHeight($file->getSize()->getHeight());
        $image->setProcessed(true);
      }
      $image->setBaseDir($image->getFile()->getFileNamespace() . '/' . $this->getUniqueCachedName());
      $image->setExtension($image->getFile()->getExtension());
      $this->em->persist($image);
      $this->em->flush();
    }
  }

  protected function getUniqueCachedName($extension = null)
  {
    $extension = empty($extension) ? '' : ".$extension";
    do
    {
      $fileName = uniqid('', true);
      $fileName = implode('-', array_reverse(explode('.', $fileName))) . $extension;
    }
    while($this->uniqueNameExists($fileName));
    return $fileName;
  }

  /**
   * @param $fileName
   * @return bool
   */
  protected function uniqueNameExists($fileName)
  {
    $dql = "SELECT COUNT(image) FROM EDVFileBundle:EdImage AS image WHERE image.baseDir = :name";
    $query = $this->em->createQuery($dql)->setParameters(array('name' => $fileName));
    $result = $query->getSingleScalarResult();
    return ($result > 0);
  }

}