<?php

namespace FollowMe\Bundle\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Since;

/**
 * Music
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FollowMe\Bundle\ModelBundle\Entity\LocationRepository")
 *
 * @ExclusionPolicy("NONE")
 */
class Location
{
    /**
     * Location's identifier number
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
     * Date of the location
     *
     * @var \DateTime
     *
     * @Since("0.1")
     * @Groups({"all", "list", "info"})
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var RFSensor
     *
     * @Since("0.1")
     * @Groups({"all", "list", "info"})
     *
     * @ORM\ManyToOne(targetEntity="RFSensor")
     */
    private $sensor;

    /**
     * @var User
     *
     * @Since("0.1")
     * @Groups({"all", "list", "info"})
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="locations")
     */
    private $user;

    /**
     * Author of the music
     *
     * @var string
     *
     * @Since("0.1")
     * @Groups({"all", "list", "info"})
     *
     * @ORM\Column(name="sensor_value", type="integer")
     */
    private $sensorValue;

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
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return Location
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return RFSensor
     */
    public function getSensor()
    {
        return $this->sensor;
    }

    /**
     * @param RFSensor $sensor
     * @return Location
     */
    public function setSensor($sensor)
    {
        $this->sensor = $sensor;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Location
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getSensorValue()
    {
        return $this->sensorValue;
    }

    /**
     * @param string $sensorValue
     * @return Location
     */
    public function setSensorValue($sensorValue)
    {
        $this->sensorValue = $sensorValue;
        return $this;
    }
}
