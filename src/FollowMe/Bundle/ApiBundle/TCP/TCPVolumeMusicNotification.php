<?php
/**
 * Created by PhpStorm.
 * User: pieraggi
 * Date: 01/04/15
 * Time: 12:36
 */

namespace FollowMe\Bundle\ApiBundle\TCP;


use FollowMe\Bundle\ModelBundle\Entity\User;

class TCPVolumeMusicNotification extends TCPNotification {

    /**
     * @var int
     */
    const UP = 0;
    /**
     * @var int
     */
    const DOWN = 1;
    /**
     * @var int
     */
    const ABSOLUTE = 2;

    /**
     * @var int
     */
    private $messageType;

    /**
     * @var int
     */
    private $volume;

    /**
     * @return int
     */
    public function getMessageType()
    {
        return $this->messageType;
    }

    /**
     * @param int $messageType
     */
    public function setMessageType($messageType)
    {
        $this->messageType = $messageType;
        switch($this->messageType)
        {
            case self::UP:
                $this->setMessage("volume:relative:+");
                break;

            case self::DOWN:
                $this->setMessage("volume:relative:-");
                break;
        }
    }

    /**
     * @return int
     */
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * @param int $volume
     */
    public function setVolume($volume)
    {
        $this->volume = $volume;
        $this->setMessage("volume:absolute:".$volume);
    }

    /**
     * @param User $user
     * @param int $messageType
     * @param int|null $volume
     */
    public function __construct(User $user, $messageType, $volume = null)
    {
        parent::__construct($user);
        $this->setMessageType($messageType);
        if($volume !== null)
            $this->setVolume($volume);
    }

}