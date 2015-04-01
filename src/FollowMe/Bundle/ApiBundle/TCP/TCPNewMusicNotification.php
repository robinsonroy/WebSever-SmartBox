<?php
/**
 * Created by PhpStorm.
 * User: pieraggi
 * Date: 01/04/15
 * Time: 12:49
 */

namespace FollowMe\Bundle\ApiBundle\TCP;


use FollowMe\Bundle\ModelBundle\Entity\Music;
use FollowMe\Bundle\ModelBundle\Entity\User;

class TCPNewMusicNotification extends TCPNotification {

    /**
     * @var Music
     */
    private $music;

    /**
     * @param Music $music
     * @param User $user
     */
    public function __construct(Music $music, User $user) {
        parent::__construct($user);
        $this->setMusic($music);
    }

    /**
     * @return Music
     */
    public function getMusic()
    {
        return $this->music;
    }

    /**
     * @param Music $music
     */
    public function setMusic(Music $music)
    {
        $this->music = $music;
        if($music) {
            $this->setMessage("play:".$music->getId());
        }
    }



}