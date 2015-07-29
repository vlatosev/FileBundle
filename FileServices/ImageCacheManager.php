<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 7/21/15
 * Time: 12:39 PM
 */

namespace EDV\FileBundle\FileServices;

use Imagine\Image\ImageInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;

class ImageCacheManager
{
  /**
   * site's web directory
   *
   * @var string
   */
  private $webroot  = '';

  /**
   * cache directory in web
   *
   * @var string
   */
  private $cachedir = '';

  public function __construct($webroot, $cachedir)
  {
    $this->webroot  = realpath($webroot . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'web');
    $this->cachedir = $cachedir;
  }

  /**
   * @param File $file
   * @param $namespace
   * @param $image_hash
   * @param $image_thumb
   * @return File
   */
  public function copyToCache(File $file, $namespace, $image_hash, $image_thumb)
  {
    $dest = $this->createDestinationPath($namespace, $image_hash, $image_thumb);
    umask(0000);
    if(!@copy($file->getPathname(), $dest))
    {
      $err = error_get_last();
      throw new FileException($err['message']);
    }
    return new File($dest);
  }

  /**
   * @param ImageInterface $image
   * @param $namespace
   * @param $image_hash
   * @param $image_thumb
   * @return File
   */
  public function createFromImagine(ImageInterface $image, $namespace, $image_hash, $image_thumb)
  {
    umask(0000);
    $dest = $this->createDestinationPath($namespace, $image_hash, $image_thumb);
    $image->save($dest);
    return new File($dest);
  }

  /**
   * @param $namespace
   * @param $image_hash
   * @param $image_thumb
   * @return string
   */
  private function createDestinationPath($namespace, $image_hash, $image_thumb)
  {
    return $this->createDestinationDir($namespace, $image_hash) . DIRECTORY_SEPARATOR . $image_thumb;
  }

  /**
   * @param $namespace
   * @param $image_hash
   * @return string
   */
  private function createDestinationDir($namespace, $image_hash)
  {
    $retval = $this->getDestinationDir($namespace, $image_hash);
    if(!is_dir($retval)) mkdir($retval, 0777, true);
    return $retval;
  }

  /**
   * @param $namespace
   * @param $image_hash
   * @param $image_thumb
   * @return string
   */
  private function getDestinationPath($namespace, $image_hash, $image_thumb)
  {
    return $this->getDestinationDir($namespace, $image_hash) . DIRECTORY_SEPARATOR . $image_thumb;
  }

  /**
   * @param $namespace
   * @param $image_hash
   * @return string
   */
  private function getDestinationDir($namespace, $image_hash)
  {
    return $this->webroot . DIRECTORY_SEPARATOR . $this->cachedir . DIRECTORY_SEPARATOR . $namespace . (is_null($image_hash) ? '' : DIRECTORY_SEPARATOR . $image_hash );
  }

  /**
   * @param $namespace
   * @param $image_hash
   * @param $image_thumb
   * @return null|File
   */
  public function getCachedFile($namespace, $image_hash, $image_thumb)
  {
    $path = $this->getDestinationPath($namespace, $image_hash, $image_thumb);
    if(is_file($path)) return new File($path);
    return null;
  }

  /**
   * @param $hash
   * @param $namespace
   */
  public function invalidateCache($hash, $namespace)
  {
    $cachedir = $this->getDestinationDir($namespace, $hash);
    if(!empty($hash) && file_exists($cachedir) && is_dir($cachedir))
    {
      foreach (glob($cachedir . DIRECTORY_SEPARATOR . '*') as $filename)
      {
        if(is_file($filename))
          if(!@unlink($filename)) throw new FileException("Can't delete cache file!");
      }
      if(!@rmdir($cachedir)) throw new FileException("Can't delete cache dir!");
    }
  }
}