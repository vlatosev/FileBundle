<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 7/15/15
 * Time: 1:26 PM
 */

namespace EDV\FileBundle\Form\Transformers;


use EDV\FileBundle\Entity\EdFile;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;

class FileUploadTransformer implements DataTransformerInterface
{
  /**
   * @var string
   */
  private $namespace;

  /**
   * @var UserInterface|null
   */
  private $user;

  public function __construct(UserInterface $user = null, $namepace = 'general-files')
  {
    $this->namespace = $namepace;
    $this->user = $user;
  }

  public function transform($value)
  {
    return $value;
  }

  /**
   * @param EdFile $value
   * @return mixed
   */
  public function reverseTransform($value)
  {
    if($value instanceof EdFile && $value->getUploadFile() instanceof File)
    {
      $upload = $value->getUploadFile();
      if($upload instanceof UploadedFile)
      {
        $value
            ->setUploadedBy($this->user)
            ->setCreatedAt(new \DateTime())
            ->setFileNamespace($this->namespace);
      }
    }
    return $value;
  }


}