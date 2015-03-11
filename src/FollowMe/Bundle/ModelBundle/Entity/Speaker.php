<?php

namespace FollowMe\Bundle\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Since;
use JMS\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Speaker
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FollowMe\Bundle\ModelBundle\Entity\SpeakerRepository")
 *
 * @ExclusionPolicy("NONE")
 * @AccessType("public_methods")
 */
class Speaker
{
    /**
     * Speaker's identifier number
     *
     * @var integer
     *
     * @Since("0.1")
     * @Groups({"all", "list", "info"})
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Speaker's name
     *
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
     * Room in which the speaker is located
     *
     * @var Room
     *
     * @Since("0.1")
     * @Groups({"all", "list", "info"})
     *
     * @ORM\ManyToOne(targetEntity="Room", inversedBy="speakers")
     */
    private $room;

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
     * @return Room
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * @param Room $room
     * @return Speaker
     */
    public function setRoom($room)
    {
        $this->room = $room;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Speaker
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

}
