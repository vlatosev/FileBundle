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
   * @ORM\Column(name="extension", type="string", length=255, nullable=true)
   */
  private $extension = null;
}
