<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 7/30/15
 * Time: 2:18 PM
 */

namespace EDV\FileBundle\FileServices;


use Doctrine\ORM\EntityManagerInterface;
use EDV\FileBundle\Entity\EdImage;
use EDV\FileBundle\Entity\EdImageRegister;

class ImagePublicRegistrator
{
  protected $em;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  /**
   * @param EdImage $image
   * @param $type
   */
  public function register(EdImage $image, $type)
  {
    if(!$this->isRegistered($image, $type))
    {
      $record = new EdImageRegister();
      $record->setImage($image);
      $record->setType($type);
      $this->em->persist($record);
      $this->em->flush();
    }
  }

  private function isRegistered(EdImage $image, $type)
  {
    return $this->em
        ->createQuery("SELECT COUNT(ir) FROM EDVFileBundle:EdImageRegister AS ir JOIN ir.image AS im WHERE im.id = :imgid AND ir.type = :type")
        ->setParameters([
            'imgid' => $image->getId(),
            'type' => $type
        ])
        ->getSingleScalarResult() > 0;
  }

  /**
   * @param string $image_hash
   * @param string $image_type
   * @return EdImage|null
   * @throws \Doctrine\ORM\NonUniqueResultException
   */
  public function getRegisteredImage($image_hash, $image_type)
  {
    $retval = $this->em
        ->createQuery("SELECT ir,im FROM EDVFileBundle:EdImageRegister AS ir JOIN ir.image AS im WHERE im.hashString = :imghash AND ir.type = :type")
        ->setParameters([
            'imghash' => $image_hash,
            'type' => $image_type
        ])
        ->getOneOrNullResult();
    return $retval instanceof EdImageRegister ? $retval->getImage() : null;
  }

}
