<?php
namespace ED\FileBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use ED\FileBundle\Entity\EdImage;

class ImageInitializer
{
  public function prePersist(LifecycleEventArgs $args)
  {
    $entity = $args->getEntity();
    if($entity instanceof EdImage)
    {
      $repo = $args->getEntityManager()->getRepository("EDFileBundle:EdImage");
      $repo->setImageData($entity);
    }
  }
}