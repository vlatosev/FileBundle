<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 7/14/15
 * Time: 2:32 PM
 */

namespace EDV\FileBundle\EventListener;


use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use EDV\FileBundle\Entity\EdFile;

class FileUploadSubscriber implements EventSubscriber
{
  private $upload_root;

  /**
   * @param string $uploadroot
   */
  public function __construct($uploadroot)
  {
    $this->upload_root = $uploadroot;
  }

  /**
   * @return array
   */
  public function getSubscribedEvents()
  {
    return [
      Events::postPersist
    ];
  }

  public function postPersist(LifecycleEventArgs $args)
  {
    $entity = $args->getEntity();
    if($entity instanceof EdFile && !is_null($entity->getUploadFile()))
    {
      $entity->getUploadFile()->move($this->upload_root . DIRECTORY_SEPARATOR . $entity->getFileNamespace(), strval($entity->getId()));
    }
  }

}