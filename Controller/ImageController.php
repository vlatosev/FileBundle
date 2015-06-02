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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ImageController extends Controller
{
  /**
   * @Route("/%ed_file.web_image_root%/{image_base_dir}/{image_thumb}", name="show_image", requirements={"image_base_dir" = ".+"})
   */
  public function showImageAction($image_base_dir, $image_thumb)
  {
    $em = $this->getDoctrine()->getManager();
    $repo = $em->getRepository("EDFileBundle:EdImage");
    $image = $repo->findOneBy(array('baseDir' => $image_base_dir));
    $imgprocessor = $this->get('image_processor');
    if(!is_null($image))
    {
      $image_type = basename($image_thumb, '.' . $image->getExtension());
    }
    elseif($image_base_dir == 'defaults')
    {
      $image_type = $this->customBasename($image_thumb);
    }
    else throw new ResourceNotFoundException("Image not found!");
    /** @var $file Image */
    $ds = DIRECTORY_SEPARATOR;
    $webroot = dirname($this->container->getParameter("kernel.root_dir"));
    $file = $imgprocessor->getImageThumb($image, $image_type, $webroot);
    umask(0000);
    $image_dir = $webroot . $ds . "web$ds" . $this->container->getParameter('ed_file.web_image_root') . $ds . $image_base_dir;
    if(!is_dir($image_dir)) $havedir = mkdir($image_dir, 0777, true);
    else $havedir = true;
    if($havedir)
    {
      $webpath = $image_dir . $ds . $image_thumb;
      $file->save($webpath);
      $response = new BinaryFileResponse($webpath);
    }
    else throw new FileException("Can't save image file!");
    return $response;
  }

  /**
   * @Route("/%ed_file.web_image_root%/defaults/{type}", name="show_default_image", requirements={"type" = ".+"})
   */
  public function showDefaultImageAction($type)
  {
    $processor = $this->get('image_processor');
    $file = $processor->getDefaultImage($type);
    return false;
  }

  private function customBasename($retval)
  {
    $pos = strrpos($retval, '.');
    return $pos === false ? '' : substr($retval, 0, $pos);
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
}