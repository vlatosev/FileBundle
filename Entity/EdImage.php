<?php

namespace EDV\FileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EDV\FileBundle\Entity\EdFile;
use Imagine\Imagick\Image;
use Imagine\Imagick\Imagine;

/**
 * EdImage
 *
 * @ORM\Table(name="ed_image")
 * @ORM\Entity(repositoryClass="EDV\FileBundle\Entity\EdImageRepository")
 */
class EdImage
{
  /**
   * @var integer
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @var integer
   *
   * @ORM\Column(name="width", type="integer")
   */
  private $width = 0;

  /**
   * @var integer
   *
   * @ORM\Column(name="height", type="integer")
   */
  private $height = 0;

  /**
   * @var boolean
   *
   * @ORM\Column(name="processed", type="boolean")
   */
  private $processed = false;

  /**
   * @var string
   *
   * @ORM\Column(name="base_dir", type="string", length=255, nullable=false, unique=true)
   */
  private $baseDir = '';

  /**
   * @var EdFile
   *
   * @ORM\OneToOne(targetEntity="EDV\FileBundle\Entity\EdFile", cascade={"persist", "remove"}, orphanRemoval=true)
   * @ORM\JoinColumn(name="file_id", nullable=true, onDelete="cascade")
   */
  private $file;

  /**
   * @var string
   *
   * @ORM\Column(name="extension", type="string", length=255, nullable=false)
   */
  private $extension = '';

  /**
   * Get id
   *
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Set width
   *
   * @param integer $width
   * @return EdImage
   */
  public function setWidth($width)
  {
    $this->width = $width;

    return $this;
  }

  /**
   * Get width
   *
   * @return integer
   */
  public function getWidth()
  {
    return $this->width;
  }

  /**
   * Set height
   *
   * @param integer $height
   * @return EdImage
   */
  public function setHeight($height)
  {
    $this->height = $height;

    return $this;
  }

  /**
   * Get height
   *
   * @return integer
   */
  public function getHeight()
  {
    return $this->height;
  }

  /**
   * Set processed
   *
   * @param boolean $processed
   * @return EdImage
   */
  public function setProcessed($processed)
  {
    $this->processed = $processed;

    return $this;
  }

  /**
   * Get processed
   *
   * @return boolean
   */
  public function getProcessed()
  {
    return $this->processed;
  }

  /**
   * Set file
   *
   * @param \EDV\FileBundle\Entity\EdFile $file
   * @return EdImage
   */
  public function setFile(EdFile $file = null)
  {
    $this->file = $file;

    return $this;
  }

  /**
   * Get file
   *
   * @return EdFile
   */
  public function getFile()
  {
    return $this->file;
  }

    /**
     * Set baseDir
     *
     * @param string $baseDir
     * @return EdImage
     */
    public function setBaseDir($baseDir)
    {
        $this->baseDir = $baseDir;

        return $this;
    }

    /**
     * Get baseDir
     *
     * @return string 
     */
    public function getBaseDir()
    {
        return $this->baseDir;
    }

    /**
     * Set extension
     *
     * @param string $extension
     * @return EdImage
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string 
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * get image source url
     *
     * @param string $type
     * @return string
     */
    public function getSrcData($type = 'original')
    {
      $retval = array(
        'image_base_dir' => $this->baseDir,
        'image_thumb'    => $type . '.' . $this->extension
      );
      return $retval;
    }
}
