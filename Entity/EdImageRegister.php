<?php

namespace EDV\FileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EdImageRegister
 *
 * @ORM\Table(name="ed_image_register")
 * @ORM\Entity
 */
class EdImageRegister
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
     * @var EdImage
     *
     * @ORM\ManyToOne(targetEntity="EdImage")
     * @ORM\JoinColumn(name="image_id", onDelete="cascade", nullable=false)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     */
    private $type;

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
     * Set type
     *
     * @param string $type
     * @return EdImageRegister
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set image
     *
     * @param \EDV\FileBundle\Entity\EdImage $image
     * @return EdImageRegister
     */
    public function setImage(\EDV\FileBundle\Entity\EdImage $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \EDV\FileBundle\Entity\EdImage 
     */
    public function getImage()
    {
        return $this->image;
    }
}
