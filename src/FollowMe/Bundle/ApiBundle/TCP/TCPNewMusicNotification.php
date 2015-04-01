<?php
/**
 * Created by PhpStorm.
 * User: pieraggi
 * Date: 01/04/15
 * Time: 12:49
 */

namespace FollowMe\Bundle\ApiBundle\TCP;


class TCPNewMusicNotification extends TCPNotification {

    private static $MESSAGE = "play";

    public function getMessage()
    {
        return self::$MESSAGE;
    }
}