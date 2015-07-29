<?php
namespace EDV\FileBundle\ImageProcessing;

use Doctrine\Common\Inflector\Inflector;
use EDV\FileBundle\Entity\EdImage;
use EDV\FileBundle\ImageProcessing\Transformers\TransformerInterface;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Metadata\ExifMetadataReader;
use Imagine\Image\Metadata\MetadataBag;
use Imagine\Image\Point;
use Imagine\Imagick\Imagine;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Router;

class ImageProcessor
{
  const TRANSFORMER_CLASS_PATH = 'EDV\FileBundle\ImageProcessing\Transformers';

  protected $image_types = array(
    'original' => array(
      'transform' => 'original'
    )
  );

  /**
   * @var Router|null
   */
  protected $router = null;

  /**
   * @var ImagineInterface
   */
  protected $imagine;

  public function __construct($image_types = array(), Router $router)
  {
    if(is_array($image_types))
    {
      $this->image_types = array_merge($this->image_types, $image_types);
    }

    $this->router = $router;

    $this->imagine = extension_loaded('imagick') ? new Imagine() : new \Imagine\Gd\Imagine();
    $this->imagine->setMetadataReader(new ExifMetadataReader());
  }

  /**
   * @param File|null $image
   * @param $image_type
   * @return \Imagine\Image\ImageInterface
   */
  public function getImageThumb(File $image = null, $image_type, $image_area = null)
  {
    if(isset($this->image_types[$image_type]))
    {
      $source = $this->imagine->open($image->getRealPath());

      $this->preProcessSourceImg($source);

      $this->preProcessCropArea($source, $image_area);

      $type = $this->image_types[$image_type];

      $transformer_class = self::TRANSFORMER_CLASS_PATH . "\\" . Inflector::classify($type['transform']) . 'Transformer';
      $width  = isset($type['width'])  ? $type['width']  : null;
      $height = isset($type['height']) ? $type['height'] : null;

      /** @var $transformer TransformerInterface */
      $transformer = new $transformer_class($this->imagine, $source, $width, $height);
      $retval = $transformer->getTransformed();
    }
    else throw new ResourceNotFoundException("Can't show image!");
    return $retval->strip();

  }

  public function getDefaultImage($type)
  {
    $type_data = isset($this->image_types[$type]) ? $this->image_types[$type] : array();
    if(isset($type_data['default_image']) && !is_null($type_data['default_image']))
    {
      $retval = $type_data['default_image'];
    }
    else $retval = null;
    return $retval;
  }

  public function getImageDimensions($type)
  {
    $dim_data = isset($this->image_types[$type]) ? $this->image_types[$type] : array();
    if(isset($dim_data['width']) && isset($dim_data['height']))
    {
      $retval = [
          'height' => $dim_data['height'],
          'width' => $dim_data['width']
      ];
    }
    else $retval = null;
    return $retval;
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

  public function getImagine()
  {
    return $this->imagine;
  }

  /**
   * @param ImageInterface $oldsource
   * @return ImageInterface
   */
  private function preProcessSourceImg(ImageInterface $source)
  {
    /** @var MetadataBag $metadata */
    $metadata = $source->metadata();
    $orientation = $metadata->offsetGet('ifd0.Orientation');
    switch($orientation)
    {
      case 3:
        $source->rotate(180);
        break;
      case 6:
        $source->rotate(90);
        break;
      case 8:
        $source->rotate(-90);
        break;
      default:
        break;
    }
    $metadata->offsetSet('ifd0.Orientation', 1);
    return $source;
  }

  private function preProcessCropArea(ImageInterface $source, $area)
  {
    $data = $this->getCropArea($area);
    if(!is_null($data))
    {
      $point = new Point($data['x1'], $data['y1']);
      $box   = new Box($data['x2'] - $data['x1'], $data['y2'] - $data['y1']);
      $source->crop($point, $box);
    }

  }

  private function getCropArea($image_area)
  {
    $retval = null;
    if(is_array($image_area) && count($image_area) == 4)
    {
      $retval = [
          'x1' => intval($image_area[0]),
          'y1' => intval($image_area[1]),
          'x2' => intval($image_area[2]),
          'y2' => intval($image_area[3])
      ];
    }
    return $retval;
  }

  /**
   * @param ImageInterface $file
   * @return bool
   */
  public function isLandscapeOrient(ImageInterface $file)
  {
    $retval = true;
    /** @var MetadataBag $metadata */
    $metadata = $file->metadata();
    $orientation = $metadata->offsetGet('ifd0.Orientation');
    switch($orientation)
    {
      case 6:
      case 8:
        $retval = false;
        break;
      default:
        break;
    }
    return $retval;
  }
}