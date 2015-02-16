<?php

namespace FollowMe\Bundle\ModelBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Room
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FollowMe\Bundle\ModelBundle\Entity\RoomRepository")
 *
 * @ExclusionPolicy("NONE")
 */
class Room
{
    /**
     * @var integer
     *
     * @Groups({"all", "list", "info"})
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Groups({"all", "list", "info"})
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @NotBlank()
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @Exclude
     *
     * @ORM\OneToMany(targetEntity="Speaker", mappedBy="room", cascade={"all"})
     */
    private $speakers;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @Exclude
     *
     * @ORM\OneToMany(targetEntity="RFSensor", mappedBy="room", cascade={"all"})
     */
    private $sensors;

    public function __construct()
    {
        $this->id = 0;
        $this->sensors = new ArrayCollection();
        $this->speakers = new ArrayCollection();
    }

    /**
     * Return sensors count (used for serialization)
     *
     * @VirtualProperty
     * @Groups({"all", "info"})
     *
     * @return integer
     */
    public function getSensorsCount()
    {
        return $this->getSensors()->count();
    }

    /**
     * Return speakers count (used for serialization)
     *
     * @VirtualProperty
     * @Groups({"all", "info"})
     *
     * @return integer
     */
    public function getSpeakersCount()
    {
        return $this->getSpeakers()->count();
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
     * @return Room
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
     * Get speakers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSpeakers()
    {
        return $this->speakers;
    }

    /**
     * Set speakers
     *
     * @param \Doctrine\Common\Collections\Collection $speakers
     * @return Room
     */
    public function setSpeakers($speakers)
    {
        $this->speakers = $speakers;
        return $this;
    }

    /**
     * Add speaker
     *
     * @param Speaker $speaker
     * @return boolean TRUE if the speakers list didn't contained the specified element, FALSE otherwise
     */
    public function addSpeaker(Speaker $speaker)
    {
        if($speaker && !$this->getSpeakers()->contains($speaker)) {
            return $this->getSpeakers()->add($speaker);
        }
        return false;
    }

    /**
     * Remove speaker
     *
     * @param Speaker $speaker
     * @return boolean TRUE if the speakers list contained the specified element, FALSE otherwise.
     */
    public function removeSpeaker(Speaker $speaker)
    {
        if($speaker) {
            return $this->getSpeakers()->removeElement($speaker);
        }

        return false;
    }

    /**
     * Get sensors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSensors()
    {
        return $this->sensors;
    }

    /**
     * Set sensors
     *
     * @param \Doctrine\Common\Collections\Collection $sensors
     * @return Room
     */
    public function setSensors($sensors)
    {
        $this->sensors = $sensors;
        return $this;
    }

    /**
     * Add sensor
     *
     * @param RFSensor $sensor
     * @return boolean TRUE if the sensors list didn't contained the specified element, FALSE otherwise
     */
    public function addSensor(RFSensor $sensor)
    {
        if($sensor && !$this->getSensors()->contains($sensor)) {
            return $this->getSensors()->add($sensor);
        }
        return false;
    }

    /**
     * Remove sensor
     *
     * @param RFSensor $sensor
     * @return boolean TRUE if the sensors list contained the specified element, FALSE otherwise.
     */
    public function removeSensor(RFSensor $sensor)
    {
        if($sensor) {
            return $this->getSensors()->removeElement($sensor);
        }

        return false;
    }
}
