<?php

namespace FollowMe\Bundle\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Since;

/**
 * Room
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FollowMe\Bundle\ModelBundle\Entity\RFSensorRepository")
 *
 * @ExclusionPolicy("NONE")
 * @AccessType("public_methods")
 */
class RFSensor
{
    /**
     * Sensor's identifier number
     *
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
     * Room in which the sensor is located
     *
     * @var Room
     *
     * @Since("0.1")
     * @Groups({"all", "list", "info"})
     *
     * @ORM\ManyToOne(targetEntity="Room", inversedBy="sensors")
     */
    private $room;

    /**
     * @param int $id
     * @return RFSensor
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * @return Room
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * @param Room $room
     * @return RFSensor
     */
    public function setRoom($room)
    {
        $this->room = $room;
        return $this;
    }

}
