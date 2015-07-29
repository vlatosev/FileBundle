<?php

namespace EDV\FileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EDV\FileBundle\Entity\EdFile;

/**
 * EdImage
 *
 * @ORM\Table(name="ed_image")
 * @ORM\Entity()
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
   * @ORM\Column(name="hash_string", type="string", length=255, nullable=false, unique=true)
   */
  private $hashString = '';

  /**
   * @var EdFile
   *
   * @ORM\OneToOne(targetEntity="EDV\FileBundle\Entity\EdFile", cascade={"persist", "remove"}, orphanRemoval=true)
   * @ORM\JoinColumn(name="file_id", nullable=true, onDelete="cascade")
   */
  private $file;

  /**
   * @var array
   *
   * @ORM\Column(name="area", type="simple_array")
   */
  private $area;

  public function __construct()
  {
    $this->area = [];
  }

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
     * Get extension
     *
     * @return string 
     */
    public function getExtension()
    {
        return $this->getFile()->getExtension();
    }

    /**
     * Set file
     *
     * @param \EDV\FileBundle\Entity\EdFile $file
     * @return EdImage
     */
    public function setFile(\EDV\FileBundle\Entity\EdFile $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return \EDV\FileBundle\Entity\EdFile 
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set hashString
     *
     * @param string $hashString
     * @return EdImage
     */
    public function setHashString($hashString)
    {
        $this->hashString = $hashString;

        return $this;
    }

    /**
     * Get hashString
     *
     * @return string 
     */
    public function getHashString()
    {
        return $this->hashString;
    }

    /**
     * Set area
     *
     * @param array $area
     * @return EdImage
     */
    public function setArea($area)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return array 
     */
    public function getArea()
    {
        return $this->area;
    }
}
