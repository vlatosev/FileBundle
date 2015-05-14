<?php
namespace ED\FileBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use ED\FileBundle\Event\EdFileEvent;
use ED\FileBundle\Event\FileUpdatedEvent;

class FileUpdatedListener
{
  /**
   * @var \Doctrine\ORM\EntityManagerInterface
   */
  protected $em;

  protected $photodir;

  public function __construct(EntityManagerInterface $em = null, $photodir)
  {
    $this->em = $em;
    $this->photodir = $photodir;
  }

  public function onFileUpdated(EdFileEvent $event)
  {
    $file = $event->getFile();
    $this->em->getRepository("EDFileBundle:EdImage")->cleanOnFileUpdate($file, $this->photodir);
  }
}