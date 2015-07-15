<?php
namespace EDV\FileBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use EDV\FileBundle\EDVFileEvents;
use EDV\FileBundle\Entity\EdFile;
use EDV\FileBundle\Event\EdFileEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class FileUploadSaver implements EventSubscriber
{
  /**
   * @var EventDispatcherInterface
   */
  private $dispatcher;

  private $upload_dir = null;

  public function __construct(EventDispatcherInterface $dispatcher, $root_dir)
  {
    $this->dispatcher = $dispatcher;
    $this->upload_dir = $root_dir . "/../uploads";
  }

  public function getSubscribedEvents()
  {
    return array(
      'preRemove',
      'postPersist',
      'postRemove'
    );
  }

  public function preRemove(LifecycleEventArgs $args)
  {
    $entity = $args->getEntity();
    if($entity instanceof EdFile)
    {
      $entity->setForRemoving($entity->getId());
    }
  }

  public function postRemove(LifecycleEventArgs $args)
  {
    $entity = $args->getEntity();
    if($entity instanceof EdFile)
    {
      $entity->processRemove();
      $this->dispatcher->dispatch(EDVFileEvents::FILE_REMOVED_EVENT, new EdFileEvent($entity));
    }
  }

  public function postPersist(LifecycleEventArgs $args)
  {
    $entity = $args->getEntity();
    if($entity instanceof EdFile)
    {
      if(is_null($entity->getUploadFile())) return;
      $namespace = strlen($entity->getFileNamespace()) > 0 ? DIRECTORY_SEPARATOR . $entity->getFileNamespace() : '';
      $dir = EdFile::getUploadDir() . $namespace;
      umask(0000);
      $entity->getUploadFile()->move($dir, $entity->getId());
      $this->dispatcher->dispatch(EDVFileEvents::FILE_UPDATED_EVENT, new EdFileEvent($entity));
    }
  }
}