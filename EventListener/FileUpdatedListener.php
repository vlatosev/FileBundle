<?php
namespace EDV\FileBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use EDV\FileBundle\Event\EdFileEvent;
use EDV\FileBundle\Event\FileUpdatedEvent;
use EDV\FileBundle\ImageProcessing\ImageManager;

class FileUpdatedListener
{
  protected $imageManager;

  public function __construct(ImageManager $im)
  {
    $this->imageManager = $im;
  }

  public function onFileUpdated(EdFileEvent $event)
  {
    $this->imageManager->updateImage($event->getFile());
  }
}