<?php

namespace ED\FileBundle\Twig;


use ED\FileBundle\Entity\EdImage;
use ED\FileBundle\ImageProcessing\ImageProcessor;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class ImageExtension extends \Twig_Extension
{
  /**
   * @var \Symfony\Component\Routing\RouterInterface
   */
  private $router;

  /**
   * @var ImageProcessor
   */
  private $processor;

  public function __construct(RouterInterface $router, ImageProcessor $processor)
  {
    $this->router    = $router;
    $this->processor = $processor;
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
    if($image instanceof EdImage)
    {
      $retval = $this->router->generate("show_image", array(
        'image_base_dir' => $image->getBaseDir(),
        'image_thumb'    => $type . '.' . $image->getExtension()
      ), $absoluteurl ? UrlGeneratorInterface::ABSOLUTE_URL : UrlGeneratorInterface::ABSOLUTE_PATH);
    }
    elseif(is_null($image))
    {
      $retval = $this->router->generate("show_image", array(
          'image_base_dir' => 'defaults',
          'image_thumb'    => $type . '.' . $this->processor->getDefaultExtension($type)
      ), $absoluteurl ? UrlGeneratorInterface::ABSOLUTE_URL : UrlGeneratorInterface::ABSOLUTE_PATH);
    }
    else throw new ResourceNotFoundException("Wrong parameters!");
    return $retval;
  }

  /**
   * Returns the name of the extension.
   *
   * @return string The extension name
   */
  public function getName()
  {
    return 'image_extension';
  }
}
