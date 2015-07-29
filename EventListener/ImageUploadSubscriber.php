<?php
namespace EDV\FileBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use EDV\FileBundle\Entity\EdImage;
use EDV\FileBundle\FileServices\ImageCacheManager;
use EDV\FileBundle\ImageProcessing\ImageManager;

class ImageUploadSubscriber implements EventSubscriber
{
  /**
   * @var ImageManager
   */
  protected $imageManager;

  /**
   * @var ImageCacheManager
   */
  protected $cacheManager;

  public function __construct(ImageManager $im, ImageCacheManager $cacheManager)
  {
    $this->imageManager = $im;
    $this->cacheManager = $cacheManager;
  }

  public function getSubscribedEvents()
  {
    return [
      Events::prePersist,
      Events::preUpdate,
      Events::postRemove
    ];
  }

  public function postRemove(LifecycleEventArgs $args)
  {
    $image = $args->getEntity();
    if($image instanceof EdImage)
    {
      $this->cacheManager->invalidateCache($image->getHashString(), $image->getFile()->getFileNamespace());
    }
  }

  public function prePersist(LifecycleEventArgs $args)
  {
    $this->preFunction($args);
  }

  public function preUpdate(LifecycleEventArgs $args)
  {
    $this->preFunction($args);
  }

  private function preFunction(LifecycleEventArgs $args)
  {
    $image = $args->getEntity();
    if($image instanceof EdImage)
    {
      $this->cacheManager->invalidateCache($image->getHashString(), $image->getFile()->getFileNamespace());
      $this->imageManager->updateImage($image, $args->getEntityManager());
    }
  }
}