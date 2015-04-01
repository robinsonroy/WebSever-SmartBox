<?php
/**
 * Created by PhpStorm.
 * User: pieraggi
 * Date: 01/04/15
 * Time: 12:18
 */

namespace FollowMe\Bundle\ApiBundle\TCP;


use FollowMe\Bundle\ModelBundle\Entity\User;
use UnexpectedValueException;

class TCPNotification {

    /**
     * @var string
     */
    private static $TARGET_IP = "127.0.0.1";

    /**
     * @var int
     */
    private static $PORT = 4545;

    /**
     * @var string
     */
    private $message;

    /**
     * @var User
     */
    private $user;

    /**
     * @return String
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param String $message
     */
    protected function setMessage($message)
    {
        $this->message = $message;
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
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @param User $user
     * @param string $message
     */
    public function __construct(User $user, $message = null)
    {
        $this->setUser($user);
        $this->setMessage($message);
    }

    /**
     * @throws UnexpectedValueException
     */
    public function send()
    {
        if($this->getMessage() && $this->getUser()) {
            $client = stream_socket_client('tcp://'.self::$TARGET_IP.':'.self::$PORT, $errno, $errorMessage);

            if ($client === false) {
                throw new UnexpectedValueException("Failed to connect: $errorMessage ($errno)");
            }

            fwrite($client, $this->getMessage().":".$this->getUser()->getId());
            //echo stream_get_contents($client);
            fclose($client);
        }
    }

}