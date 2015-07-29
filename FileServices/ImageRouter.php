<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 7/29/15
 * Time: 3:25 PM
 */

namespace EDV\FileBundle\FileServices;


use EDV\FileBundle\Entity\EdImage;
use EDV\FileBundle\ImageProcessing\ImageProcessor;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class ImageRouter
{
  /**
   * @var RouterInterface
   */
  protected $router;

  /**
   * @var ImageProcessor
   */
  protected $processor;

  public function __construct(RouterInterface $router, ImageProcessor $processor)
  {
    $this->router = $router;
    $this->processor = $processor;
  }

  /**
   * @param EdImage|null $image
   * @param string $type
   * @param bool|false $absoluteurl
   * @return string
   */
  public function getImageUrl(EdImage $image = null, $type, $absoluteurl = false)
  {
    if(!is_null($image))
    {
      $retval = $this->router->generate("edv_show_image", array(
          'namespace'   => $image->getFile()->getFileNamespace(),
          'image_hash'  => $image->getHashString(),
          'image_thumb' => $type . '.' . $image->getExtension()
      ), $absoluteurl ? UrlGeneratorInterface::ABSOLUTE_URL : UrlGeneratorInterface::ABSOLUTE_PATH);
    }
    else
    {
      $retval = $this->router->generate("edv_show_default_image", array(
          'image_thumb' => $type . '.' . $this->processor->getDefaultExtension($type)
      ), $absoluteurl ? UrlGeneratorInterface::ABSOLUTE_URL : UrlGeneratorInterface::ABSOLUTE_PATH);
    }
    return $retval;
  }
}