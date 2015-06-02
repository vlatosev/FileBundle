<?php

namespace EDV\FileBundle\Controller;

use EDV\FileBundle\Entity\EdFile;
use EDV\FileBundle\Form\FileUploadType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class FileController extends Controller
{
    /**
     * @Route("/upload-file")
     * @Template()
     */
    public function uploadAction(Request $request)
    {
      $this->createFormBuilder();
      if (!$request->isXmlHttpRequest()) $this->redirect($this->generateUrl('_homepage'));
      $uploadedFile = $request->get('uploadfile');
      $class        = $request->get('file_class');
      $namespace    = $request->get('file_namespace');
      $formdata     = [
        'file_namespace' => $namespace,
        'upload_file'    => $uploadedFile
      ];
      $form         = $this->createForm('upload_widget', [], ['file_sub_class' => $class]);
      $form->handleRequest($request);
      if($form->isValid())
      {
        $object = $form->getData();
        $this->getDoctrine()->getManager()->persist($object);
        $this->getDoctrine()->getManager()->flush();
        $response = [
            'error' => false
        ];
        if(!is_null($class)) $response['class'] = $class;
        $response['file_id'] = $object->getId();
        return new JsonResponse($response);
      }
      else
      {
        $error = "Error while uploading";
      }

    }

}
