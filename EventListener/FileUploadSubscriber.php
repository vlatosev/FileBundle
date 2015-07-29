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
use EDV\FileBundle\EDVFileEvents;
use EDV\FileBundle\Entity\EdFile;
use EDV\FileBundle\Event\EdFileEvent;
use EDV\FileBundle\FileServices\FileManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadSubscriber implements EventSubscriber
{
  /**
   * @var FileManager
   */
  private $fman;

  /**
   * @var EventDispatcherInterface
   */
  private $dispatcher;

  /**
   * @param string $uploadroot
   */
  public function __construct(FileManager $fileManager, EventDispatcherInterface $disp)
  {
    $this->fman = $fileManager;
    $this->dispatcher = $disp;
  }

  /**
   * @return array
   */
  public function getSubscribedEvents()
  {
    return [
      Events::postPersist,
      Events::postRemove,
      Events::preRemove,
      Events::postUpdate,
      Events::prePersist,
      Events::preUpdate
    ];
  }

  public function preRemove(LifecycleEventArgs $args)
  {
    $entity = $args->getEntity();
    if($entity instanceof EdFile)
    {
      $entity->deleteFile = $this->fman->getFile($entity)->getPathname();
    }
  }

  public function postRemove(LifecycleEventArgs $args)
  {
    $entity = $args->getEntity();
    if($entity instanceof EdFile)
    {
      if(isset($entity->deleteFile)) unlink($entity->deleteFile);
    }
  }

  public function preUpdate(LifecycleEventArgs $args)
  {
    $this->preFunction($args);
  }

  public function prePersist(LifecycleEventArgs $args)
  {
    $this->preFunction($args);
  }

  public function postUpdate(LifecycleEventArgs $args)
  {
    $this->postFunction($args);
  }

  public function postPersist(LifecycleEventArgs $args)
  {
    $this->postFunction($args);
  }

  private function postFunction(LifecycleEventArgs $args)
  {
    $entity = $args->getEntity();
    if($entity instanceof EdFile && !is_null($entity->getUploadFile()))
    {
      try {
        $this->dispatcher->dispatch(EDVFileEvents::FILE_UPLOADED_EVENT, new EdFileEvent($entity));
        $this->fman->moveUploadedFile($entity);
      }
      catch(\Exception $e) {
        $args->getEntityManager()->remove($entity);
        $args->getEntityManager()->flush();
        throw new FileException($e->getMessage());
      }
    }
  }

  private function preFunction(LifecycleEventArgs $args)
  {
    $entity = $args->getEntity();
    if($entity instanceof EdFile && !is_null($entity->getUploadFile()))
    {
      $upload = $entity->getUploadFile();
      if($upload instanceof UploadedFile)
      {
        $entity
            ->setMimeType($upload->getClientMimeType())
            ->setExtension($upload->getClientOriginalExtension())
            ->setName(basename($upload->getClientOriginalName(), '.' . $upload->getClientOriginalExtension()))
            ->setSize($upload->getClientSize());
      }
      elseif($upload instanceof File)
      {
        $ext = $upload->guessExtension();
        $entity
            ->setMimeType($upload->getMimeType())
            ->setExtension($ext)
            ->setName(basename($upload->getFilename(), ".$ext"))
            ->setSize($upload->getSize());
      }

    }
  }

}