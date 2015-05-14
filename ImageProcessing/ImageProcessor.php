<?php
namespace ED\FileBundle\ImageProcessing;

use Doctrine\Common\Inflector\Inflector;
use ED\FileBundle\Entity\EdImage;
use ED\FileBundle\ImageProcessing\Transformers\TransformerInterface;
use Imagine\Imagick\Imagine;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Router;

class ImageProcessor
{
  const TRANSFORMER_CLASS_PATH = 'ED\FileBundle\ImageProcessing\Transformers';

  protected $image_types = array(
    'original' => array(
      'transform' => 'original'
    )
  );

  /**
   * @var Router|null
   */
  protected $router = null;

  public function __construct($image_types = array(), Router $router)
  {
    if(is_array($image_types))
    {
      $this->image_types = array_merge($this->image_types, $image_types);
    }

    $this->router = $router;
  }

  /**
   * @param EdImage $image
   * @param string $image_type
   */
  public function getImageThumb(EdImage $image = null, $image_type, $webroot = '')
  {
    $tmp = tmpfile();
    $path = !is_null($image) ?
        ($image->getFile()->getMimeType() == 'image/jpeg' ? $this->correctImageOrientation($image->getFile()->getFilePath(), $tmp) : $image->getFile()->getFilePath()) :
        $webroot . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . $this->getDefaultImage($image_type);
    $imagine = extension_loaded('imagick') ? new Imagine() : new \Imagine\Gd\Imagine();
    $source = $imagine->open($path);
    if(isset($this->image_types[$image_type]))
    {
      $type = $this->image_types[$image_type];
      $transformer_class = self::TRANSFORMER_CLASS_PATH . "\\" . Inflector::classify($type['transform']) . 'Transformer';
      $width  = isset($type['width'])  ? $type['width']  : null;
      $height = isset($type['height']) ? $type['height'] : null;
      /** @var $transformer TransformerInterface */
      $transformer = new $transformer_class($source, $width, $height);
      $retval = $transformer->getTransformed();
    }
    else throw new ResourceNotFoundException("Can't show image!");
    return $retval;

  }

  public function getDefaultImage($type)
  {
    $type_data = isset($this->image_types[$type]) ? $this->image_types[$type] : array();
    if(isset($type_data['default_image']) && !is_null($type_data['default_image']))
    {
      $retval = $type_data['default_image'];
    }
    else
    {
      $width  = isset($type_data['width'])  ? $type_data['width']  : null;
      $height = isset($type_data['height']) ? $type_data['height'] : null;

      if(is_null($width))  $width  = $height;
      if(is_null($height)) $height = $width;

      $retval = "http://placehold.it/{$width}x$height";
    }
     return $retval;
  }

  private function correctImageOrientation($filename, $file) {
    if (function_exists('exif_read_data')) {
      $data = stream_get_meta_data($file);
      $newname = $data['uri'];
      $exif = exif_read_data($filename);
      if($exif && isset($exif['Orientation'])) {
        $orientation = $exif['Orientation'];
        if($orientation != 1){
          $img = imagecreatefromjpeg($filename);
          $deg = 0;
          switch ($orientation) {
            case 3:
              $deg = 180;
              break;
            case 6:
              $deg = 270;
              break;
            case 8:
              $deg = 90;
              break;
          }
          if ($deg) {
            $img = imagerotate($img, $deg, 0);
          }
          // then rewrite the rotated image back to the disk as $filename
          imagejpeg($img, $newname, 95);
        } // if there is some rotation necessary
      } // if have the exif orientation info
    } // if function exists
    return isset($img) && isset($newname) ? $newname : $filename;
  }

  public function getDefaultExtension($type)
  {
    $type_data = isset($this->image_types[$type]) ? $this->image_types[$type] : array();
    if(isset($type_data['default_image']) && !is_null($type_data['default_image']))
    {
      $retval = $type_data['default_image'];
    }
    else return '';
    $pos = strrpos($retval, '.');
    return $pos === false ? '' : substr($retval, $pos + 1);
  }

}