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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;

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
      Events::postUpdate,
    ];
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
        $this->fman->moveUploadedFile($entity);
        $this->dispatcher->dispatch(EDVFileEvents::FILE_UPDATED_EVENT, new EdFileEvent($entity));
      }
      catch(\Exception $e) {
        $args->getEntityManager()->remove($entity);
        $args->getEntityManager()->flush();
        throw new FileException($e->getMessage());
      }
    }
  }

}