<?php

namespace FollowMe\Bundle\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Since;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Door
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FollowMe\Bundle\ModelBundle\Entity\DoorRepository")
 *
 * @ExclusionPolicy("NONE")
 * @AccessType("public_methods")
 */
class Door
{
    /**
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
     * Zigbee E/R sensor 1
     *
     * @var RFSensor
     *
     * @Since("0.1")
     * @Groups({"all", "list", "info"})
     *
     * @NotBlank()
     *
     * @ORM\OneToOne(targetEntity="RFSensor")
     */
    private $sensor1;

    /**
     * Zigbee E/R sensor 2
     *
     * @var RFSensor
     *
     * @Since("0.1")
     * @Groups({"all", "list", "info"})
     *
     * @NotBlank()
     *
     * @ORM\OneToOne(targetEntity="RFSensor")
     */
    private $sensor2;

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
     * @return RFSensor
     */
    public function getSensor1()
    {
        return $this->sensor1;
    }

    /**
     * @param RFSensor $sensor1
     * @return Door
     */
    public function setSensor1($sensor1)
    {
        $this->sensor1 = $sensor1;
        return $this;
    }

    /**
     * @return RFSensor
     */
    public function getSensor2()
    {
        return $this->sensor2;
    }

    /**
     * @param RFSensor $sensor2
     * @return Door
     */
    public function setSensor2($sensor2)
    {
        $this->sensor2 = $sensor2;
        return $this;
    }

}
