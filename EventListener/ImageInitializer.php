<?php
namespace EDV\FileBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use EDV\FileBundle\Entity\EdImage;

class ImageInitializer
{
  public function prePersist(LifecycleEventArgs $args)
  {
    $entity = $args->getEntity();
    if($entity instanceof EdImage)
    {
      $repo = $args->getEntityManager()->getRepository("EDVFileBundle:EdImage");
      $repo->setImageData($entity);
    }
  }
}