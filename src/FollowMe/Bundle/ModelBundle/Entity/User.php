<?php

namespace FollowMe\Bundle\ModelBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Groups;
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
     * User's id
     *
     * @var integer
     *
     * @Since("0.1")
     * @Groups({"all", "list", "info"})
     *
     * @ORM\Column(name="id", type="integer", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * User's name
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
     * Bracelet id
     *
     * @var integer
     *
     * @Since("0.1")
     * @Groups({"all", "list", "info"})
     *
     * @ORM\Column(name="braceletId", type="integer", unique=true)
     *
     * @NotBlank()
     */
    private $braceletId;

    /**
     * @var Room
     *
     * @Since("0.1")
     * @Groups({"all", "info"})
     *
     * @ORM\ManyToOne(targetEntity="Room", inversedBy="currentUsers")
     *
     */
    private $currentRoom;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @Since("0.1")
     * @Groups({"all"})
     *
     * @ORM\OneToMany(targetEntity="Location", mappedBy="user", cascade={"all"})
     */
    private $locations;

    /**
     * @var Music
     *
     * @Since("0.1")
     * @Groups({"all"})
     *
     * @ORM\ManyToOne(targetEntity="Music")
     */
    private $currentlyPlayedMusic;

    /**
     * @var boolean
     *
     * @Since("0.1")
     * @Groups({"all"})
     *
     * @ORM\Column(name="is_playing_music", type="boolean")
     */
    private $isPlayingMusic;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setLocations(new ArrayCollection());
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

    /**
     * @return Room
     */
    public function getCurrentRoom()
    {
        return $this->currentRoom;
    }

    /**
     * @param Room $currentRoom
     * @return User
     */
    public function setCurrentRoom($currentRoom)
    {
        $this->currentRoom = $currentRoom;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $locations
     * @return User
     */
    public function setLocations($locations)
    {
        $this->locations = $locations;
        return $this;
    }

    /**
     * @param Location $location
     * @return bool TRUE if added, FALSE otherwise
     */
    public function addLocation(Location $location)
    {
        if($location && !$this->getLocations()->contains($location)) {
            $this->getLocations()->add($location);
            return true;
        }

        return false;
    }

    /**
     * @param Location $location
     * @return bool TRUE if removed, FALSE otherwise
     */
    public function removeLocation(Location $location)
    {
        if($location && $this->getLocations()->contains($location)) {
            $this->getLocations()->remove($location);
            return true;
        }
        return false;
    }

    /**
     * @return Music
     */
    public function getCurrentlyPlayedMusic()
    {
        return $this->currentlyPlayedMusic;
    }

    /**
     * @param Music $currentlyPlayedMusic
     * @return User
     */
    public function setCurrentlyPlayedMusic($currentlyPlayedMusic)
    {
        $this->currentlyPlayedMusic = $currentlyPlayedMusic;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isIsPlayingMusic()
    {
        return $this->isPlayingMusic;
    }

    /**
     * @param boolean $isPlayingMusic
     */
    public function setIsPlayingMusic($isPlayingMusic)
    {
        $this->isPlayingMusic = $isPlayingMusic;
    }
}
