<?php
namespace ED\FileBundle\Form\Transformers;

use Doctrine\ORM\EntityManagerInterface;
use ED\FileBundle\Entity\EdFile;
use ED\FileBundle\Entity\EdImage;
use ED\UserBundle\Entity\User;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileReplaceTransformer implements DataTransformerInterface
{
  /**
   * @var \ED\UserBundle\Entity\User
   */
  protected $user;

  /**
   * @var \Doctrine\ORM\EntityManagerInterface
   */
  protected $em;

  protected $subClass;

  public function __construct(User $user, EntityManagerInterface $em, $subClass = null)
  {
    $this->em       = $em;
    $this->user     = $user;
    $this->subClass = $subClass;
  }

  public function transform($value)
  {
    return $this->isFileClass($value) ? ['file_id' => $value->getId()] : null;
  }

  public function reverseTransform($value)
  {
    if(!is_null($value['upload_file']) && $value['upload_file'] instanceof UploadedFile)
    {
      $retval = new EdFile();
      $retval->setUploadFile($value['upload_file']);
      $retval->setFileNamespace($value['file_namespace']);
      $retval->setUploadedBy($this->user);
      if(!is_null($this->subClass))
      {
        $fullclass = 'ED\FileBundle\Entity\\' . $this->subClass;
        $object = new $fullclass;
        $object->setFile($retval);
        return $object;
      }
    }
    elseif(!is_null($value['file_id']))
    {
      $retval = $this->em->getRepository(is_null($this->subClass) ? 'EDFileBundle:EdFile' : 'ED\FileBundle\Entity\\' . $this->subClass)->find($value['file_id']);
    }
    else
    {
      $retval = null;
    }
    return $retval;
  }

  private function isFileClass($value)
  {
    return is_object($value) && (($value instanceof EdFile && is_null($this->subClass)) || is_a($value, 'ED\FileBundle\Entity\\' . $this->subClass));
  }
}
