<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 7/16/15
 * Time: 3:46 PM
 */

namespace EDV\FileBundle\FileServices;


use EDV\FileBundle\Entity\EdFile;
use Symfony\Component\HttpFoundation\File\File;

class FileManager
{
  private $upload_root;

  public function __construct($rootf)
  {
    $this->upload_root = $rootf;
  }

  public function moveUploadedFile(EdFile $file)
  {
    $file->getUploadFile()->move($this->getFileDir($file), strval($file->getId()));
  }

  /**
   * @param EdFile $file
   * @return File
   */
  public function getFile(EdFile $file)
  {
    return new File($this->getFileDir($file) . DIRECTORY_SEPARATOR . strval($file->getId()));
  }

  /**
   * @param EdFile $file
   * @return string
   */
  protected function getFileDir(EdFile $file)
  {
    return $this->upload_root . DIRECTORY_SEPARATOR . $file->getFileNamespace();
  }
}