<?php
namespace EDV\FileBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use EDV\FileBundle\Event\EdFileEvent;
use EDV\FileBundle\Event\FileUpdatedEvent;

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
    $this->em->getRepository("EDVFileBundle:EdImage")->cleanOnFileUpdate($file, $this->photodir);
  }
}