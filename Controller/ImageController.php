<?php

namespace EDV\FileBundle\Controller;

use EDV\FileBundle\Entity\EdImage;
use EDV\FileBundle\Entity\EdImageRepository;
use EDV\FileBundle\ImageProcessing\ImageProcessor;
use Imagine\Imagick\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ImageController extends Controller
{
  /**
   * @Route("/%ed_file.web_image_root%/{namespace}/{image_hash}/{image_thumb}", name="edv_show_image", requirements={"image_thumb" = ".+"})
   */
  public function showImageAction($namespace, $image_hash, $image_thumb)
  {
    $em = $this->getDoctrine()->getManager();
    $repo = $em->getRepository("EDVFileBundle:EdImage");
    $image = $repo->findOneBy([
        'hashString' => $image_hash
    ]);
    if($image instanceof EdImage)
    {
      $image_type = $this->getImageType($image_thumb);
      $cacheFile  = $this->get('edv_file.image_cache_manager')->getCachedFile($image->getFile()->getFileNamespace(), $image->getHashString(), $image_thumb);
      if(is_null($cacheFile))
      {
        $source = $this->get('edv_file.file_manager')->getFile($image->getFile());
        if($image_type != 'original')
        {
          $picture = $this->get('image_processor')->getImageThumb($source, $image_type, $image->getArea());
          $cacheFile = $this->get('edv_file.image_cache_manager')->createFromImagine($picture, $namespace, $image_hash, $image_thumb);
        }
        else
        {
          $cacheFile = $this->get('edv_file.image_cache_manager')->copyToCache($source, $namespace, $image_hash, $image_thumb);
        }
      }
    }
    else throw new ResourceNotFoundException("Image not found!");

    $response = new BinaryFileResponse($cacheFile);
    return $response;
  }

  /**
   * @Route("/%ed_file.web_image_root%/defaults/{image_thumb}", name="edv_show_default_image", requirements={"image_thumb" = ".+"})
   */
  public function showDefaultImageAction($image_thumb)
  {
    $type = $this->getImageType($image_thumb);
    $defaultpath = $this->get('image_processor')->getDefaultImage($type);
    if(!is_null($defaultpath))
    {
      $defaultfile = $this->get('edv_file.file_manager')->getFileFromPath($defaultpath);
      $defaultpic  = $this->get('image_processor')->getImageThumb($defaultfile, $type);
      $cacheFile  = $this->get('edv_file.image_cache_manager')->getCachedFile('defaults', null, $image_thumb);
      if(is_null($cacheFile)) $cacheFile = $this->get('edv_file.image_cache_manager')->createFromImagine($defaultpic, 'defaults', null, $image_thumb);
      return new BinaryFileResponse($cacheFile);
    }
    else
    {
      $dims = $this->get('image_processor')->getImageDimensions($type);
      if(is_null($dims)) return new RedirectResponse('http://placehold.it/150x150');
      else return new RedirectResponse("http://placehold.it/{$dims['width']}x{$dims['height']}");
    }
  }

  /**
   * @Route("/show-preview", name="show_preview_image")
   * @Template()
   */
  public function showPreviewImageAction()
  {
    $id   = $this->get('request')->get('id');
    $type = $this->get('request')->get('type');
    $classes = $this->get('request')->get('img_class');
    $em = $this->getDoctrine()->getManager();
    $image = $em->getRepository("EDFileBundle:EdImage")->find($id);
    if(!is_null($image))
    {
      return [
          'image' => $image,
          'type'  => $type,
          'classes' => $classes
      ];
    }
    throw new NotFoundHttpException('Image not found!');
  }

  private function getImageType($image_thumb)
  {
    $pos = strrpos($image_thumb, '.');
    if($pos !== false) return substr($image_thumb, 0, $pos);
    else return $image_thumb;
  }
}