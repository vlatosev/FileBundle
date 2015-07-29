<?php

namespace EDV\FileBundle\Twig;


use EDV\FileBundle\Entity\EdImage;
use EDV\FileBundle\FileServices\ImageRouter;
use EDV\FileBundle\ImageProcessing\ImageProcessor;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class ImageExtension extends \Twig_Extension
{
  /**
   * @var ImageRouter
   */
  private $router;

  public function __construct(ImageRouter $router)
  {
    $this->router = $router;
  }

  /**
   * Returns a list of global functions to add to the existing list.
   *
   * @return array An array of global functions
   */
  public function getFunctions()
  {
    return array(
      'image_src'  => new \Twig_Function_Method($this, 'showImage')
    );
  }

  public function showImage(EdImage $image = null, $type, $absoluteurl = false)
  {
    return $this->router->getImageUrl($image, $type, $absoluteurl);
  }

  /**
   * Returns the name of the extension.
   *
   * @return string The extension name
   */
  public function getName()
  {
    return 'edv_image_extension';
  }
}
