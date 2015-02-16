<?php

namespace FollowMe\Bundle\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Since;
use JMS\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * DeviceSynchronisationRequest
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FollowMe\Bundle\ModelBundle\Entity\DeviceSynchronisationRequestRepository")
 *
 * @ExclusionPolicy("NONE")
 */
class DeviceSynchronisationRequest
{
    /**
     * @var integer
     *
     * @Since("0.1")
     * @Groups({"all", "list", "info"})
     *
     * @ORM\Column(name="device_id", type="integer")
     * @ORM\Id
     *
     * @NotBlank()
     */
    private $deviceId;

    /**
     * @var integer
     *
     * @Since("0.1")
     * @Groups({"all", "list", "info"})
     *
     * @ORM\Column(name="type_id", type="integer")
     * @ORM\Id
     *
     * @NotBlank()
     */
    private $typeId;

    /**
     * @var \DateTime
     *
     * @Since("0.1")
     * @Groups({"all", "list", "info"})
     *
     * @ORM\Column(name="date", type="datetime")
     * @ORM\Id
     *
     * @NotBlank()
     */
    private $date;

    /**
     * Get device id
     *
     * @Since("0.1")
     * @Groups({"all", "list", "info"})
     *
     * @return integer 
     */
    public function getDeviceId()
    {
        return $this->deviceId;
    }

    /**
     * Get type id
     *
     * @return integer
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

}
