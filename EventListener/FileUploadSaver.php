<?php
namespace ED\FileBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use ED\FileBundle\EdFileEvents;
use ED\FileBundle\Entity\EdFile;
use ED\FileBundle\Entity\EdImage;
use ED\FileBundle\Event\EdFileEvent;
use ED\FileBundle\Event\FileUpdatedEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class FileUploadSaver implements EventSubscriber
{
  private $container;

  public function __construct(ContainerInterface $container)
  {
    $this->container = $container;
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
      $this->container->get('event_dispatcher')->dispatch(EdFileEvents::FILE_REMOVED_EVENT, new EdFileEvent($entity));
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
      $this->container->get('event_dispatcher')->dispatch(EdFileEvents::FILE_UPDATED_EVENT, new EdFileEvent($entity));
    }
  }
}