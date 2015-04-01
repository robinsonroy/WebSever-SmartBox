<?php
/**
 * Created by PhpStorm.
 * User: pieraggi
 * Date: 01/04/15
 * Time: 12:36
 */

namespace FollowMe\Bundle\ApiBundle\TCP;


use FollowMe\Bundle\ModelBundle\Entity\User;

class TCPControlMusicNotification extends TCPNotification {

    /**
     * @var int
     */
    const PAUSE_MESSAGE = 0;
    /**
     * @var int
     */
    const PLAY_MESSAGE = 1;

    /**
     * @var int
     */
    private $messageType;

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
            case self::PAUSE_MESSAGE:
                $this->setMessage("control:pause");
                break;

            case self::PLAY_MESSAGE:
                $this->setMessage("control:play");
                break;
        }
    }

    /**
     * @param User $user
     * @param int $messageType
     */
    public function __construct(User $user, $messageType)
    {
        parent::__construct($user);
        $this->setMessageType($messageType);
    }

}