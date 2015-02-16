<?php

namespace FollowMe\Bundle\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Since;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FollowMe\Bundle\ModelBundle\Entity\UserRepository")
 *
 * @ExclusionPolicy("NONE")
 */
class User
{
    /**
     * @var integer
     *
     * @Since("0.1")
     * @Groups({"all", "list", "info"})
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @Since("0.1")
     * @Groups({"all", "list", "info"})
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @NotBlank()
     */
    private $name;

    /**
     * @var integer
     *
     * @Since("0.1")
     * @Groups({"all", "list", "info"})
     *
     * @ORM\Column(name="braceletId", type="integer")
     *
     * @NotBlank()
     */
    private $braceletId;


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
     * @return User
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
     * Set braceletId
     *
     * @param integer $braceletId
     * @return User
     */
    public function setBraceletId($braceletId)
    {
        $this->braceletId = $braceletId;

        return $this;
    }

    /**
     * Get braceletId
     *
     * @return integer 
     */
    public function getBraceletId()
    {
        return $this->braceletId;
    }
}
