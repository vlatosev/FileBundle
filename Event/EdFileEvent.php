<?php
namespace EDV\FileBundle\Event;

use EDV\FileBundle\Entity\EdFile;
use Symfony\Component\EventDispatcher\Event;

class EdFileEvent extends Event
{
  /**
   * @var EdFile
   */
  protected $file;

  public function  __construct(EdFile $file)
  {
    $this->file = $file;
  }

  public function getFile()
  {
    return $this->file;
  }
}