<?php

namespace EDV\FileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

/**
 * EdFile
 *
 * @ORM\Table(name="ed_file")
 * @ORM\Entity
 */
class EdFile
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
   * @var string
   *
   * @ORM\Column(name="name", type="string", length=255)
   */
  private $name;

  /**
   * @var string
   *
   * @ORM\Column(name="extension", type="string", length=63)
   */
  private $extension = '';

  /**
   * @var string
   *
   * @ORM\Column(name="file_namespace", type="string", length=255)
   */
  private $fileNamespace = 'general-files';

  /**
   * @var integer
   *
   * @ORM\ManyToOne(targetEntity="UploaderInterface")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true, onDelete="cascade")
   */
  private $uploadedBy;

  /**
   * @var string
   *
   * @ORM\Column(name="mime_type", type="string", length=255)
   */
  private $mimeType = '';

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="created_at", type="datetime")
   */
  private $createdAt;

  /**
   * @var integer
   *
   * @ORM\Column(name="size", type="integer", nullable=false)
   */
  private $size = 0;

  /**
   * @var File|null
   */
  protected $uploadedFile = null;

  public function __construct()
  {
    $this->createdAt = new \DateTime();
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
   * Set name
   *
   * @param string $name
   * @return EdFile
   */
  public function setName($name)
  {
    $this->name = $name;

    return $this;
  }

  /**
   * Get name
   *
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * Set extension
   *
   * @param string $extension
   * @return EdFile
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
   * Set fileNamespace
   *
   * @param string $fileNamespace
   * @return EdFile
   */
  public function setFileNamespace($fileNamespace)
  {
    $this->fileNamespace = $fileNamespace;

    return $this;
  }

  /**
   * Get fileNamespace
   *
   * @return string
   */
  public function getFileNamespace()
  {
    return $this->fileNamespace;
  }

  /**
   * Set createdAt
   *
   * @param \DateTime $createdAt
   * @return EdFile
   */
  public function setCreatedAt($createdAt)
  {
    $this->createdAt = $createdAt;

    return $this;
  }

  /**
   * Get createdAt
   *
   * @return \DateTime
   */
  public function getCreatedAt()
  {
    return $this->createdAt;
  }

  /**
   * Set size
   *
   * @param integer $size
   * @return EdFile
   */
  public function setSize($size)
  {
    $this->size = $size;

    return $this;
  }

  /**
   * Get size
   *
   * @return integer
   */
  public function getSize()
  {
    return $this->size;
  }

  /**
   * Set uploadedBy
   *
   * @param \ED\UserBundle\Entity\User $uploadedBy
   * @return EdFile
   */
  public function setUploadedBy(\ED\UserBundle\Entity\User $uploadedBy = null)
  {
    $this->uploadedBy = $uploadedBy;

    return $this;
  }

  /**
   * Get uploadedBy
   *
   * @return \ED\UserBundle\Entity\User
   */
  public function getUploadedBy()
  {
    return $this->uploadedBy;
  }

  /**
   * @return null|File
   */
  public function getUploadFile()
  {
    return $this->uploadedFile;
  }

  /**
   * @param File|null $file
   * @return $this
   */
  public function setUploadFile(File $file = null)
  {
    $this->uploadedFile = $file;

    return $this;
  }

  /**
   * Set mimeType
   *
   * @param string $mimeType
   * @return EdFile
   */
  public function setMimeType($mimeType)
  {
    $this->mimeType = $mimeType;

    return $this;
  }

  /**
   * Get mimeType
   *
   * @return string
   */
  public function getMimeType()
  {
    return $this->mimeType;
  }
}
