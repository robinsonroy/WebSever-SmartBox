<?php

namespace FollowMe\Bundle\ApiBundle\Controller;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use FollowMe\Bundle\ApiBundle\TCP\TCPControlMusicNotification;
use FollowMe\Bundle\ApiBundle\TCP\TCPNewMusicNotification;
use FollowMe\Bundle\ApiBundle\TCP\TCPVolumeMusicNotification;
use FollowMe\Bundle\ModelBundle\Entity\Music;
use FollowMe\Bundle\ModelBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\View as FosView;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;

class MusicController extends SuperController
{
    /**
     * Get all musics
     *
     * @FosView
     *
     * @return View
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Return all musics",
     *  section="Music",
     *  output={
     *      "class"="FollowMe\Bundle\ModelBundle\Entity\Music",
     *      "groups"={"list"}
     *  },
     *  statusCodes={
     *      200="Returned when successful"
     *  },
     *  tags={
     *      "dev"
     *  }
     *)
     *
     */
    public function getMusicsAction()
    {
        $data = $this->getMusicRepository()->findAll();

        return $this->createViewWithData(
            $data,
            array('list')
        );
    }

    /**
     * Get music information
     *
     * @FosView
     *
     * @param integer $id
     * @return View
     *
     * @Get("/music/{id}")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Return music's information",
     *  section="Music",
     *  output={
     *      "class"="FollowMe\Bundle\ModelBundle\Entity\Music",
     *      "groups"={"info"}
     *  },
     *  statusCodes={
     *      200="Returned when successful"
     *  },
     *  tags={
     *      "dev"
     *  }
     *)
     *
     */
    public function getMusicAction($id)
    {
        /** @var Music $music */
        $music = $this->getMusicRepository()->find($id);

        // If music exists
        if($music)
        {
            return $this->createViewWithData(
                $music,
                array('info')
            );
        }

        // Music doesn't exist
        return $this->createViewWithData(
            array(
                'success' => false,
                'message' => "Music doesn't exist"
            ),
            null,
            SuperController::ERROR
        );
    }

    /**
     * Play new song
     *
     * @FosView
     *
     * @param Request $request
     * @return View
     *
     * @Post("/music/play_song")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Play music",
     *  section="Music",
     *  statusCodes={
     *      200="Returned when successful"
     *  },
     *  tags={
     *      "dev"
     *  }
     *)
     *
     */
    public function postPlaySongAction(Request $request)
    {
        $user = null;
        $music = null;
        $response_message = null;
        $success = false;

        // Decode data
        $raw = json_decode($request->getContent(), true);

        // Validate data
        if (
            isset($raw['music']) &&  isset($raw['music']['id']) &&
            isset($raw['user']) && isset($raw['user']['id'])
        ) {
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();

            /** @var User $user */
            $user = $this->getUserRepository()->find($raw['user']['id']);
            if (!$user)
                $response_message = "User doesn't exist";

            /** @var Music $music */
            $music = $this->getMusicRepository()->find($raw['music']['id']);
            if (!$music)
                $response_message = "Music doesn't exist";

            // Valid
            if($user && $music)
            {
                // TCP Message
                $notif = new TCPNewMusicNotification($music, $user);
                $notif->send();

                // Save
                try {
                    $em->flush($user);

                    // Update user
                    $user->setCurrentlyPlayedMusic($music);
                    $user->setIsPlayingMusic(true);

                    // OK
                    $response_message = 'Now playing music';
                    $success = true;
                }
                catch(\PDOException $e) {
                    $response_message = 'Code 1 : '.$e->getMessage();
                }
                catch(DBALException $e) {
                    $response_message = 'Code 2 : '.$e->getMessage();
                }
            }

        } // Invalid input
        else {
            $response_message = "Input data isn't valid";
        }

        // Generate response
        $statusCode = $success ? SuperController::OK : SuperController::ERROR;

        $data = array(
            'success' => $success,
            'message' => $response_message
        );

        return $this->createViewWithData(
            $data,
            array('info'),
            $statusCode
        );

    }

    /**
     * Update volume
     *
     * @FosView
     *
     * @param integer $userId
     * @param integer|string $volume
     *
     * @return View
     *
     * @Get("/music/volume/{userId}/{volume}")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Change volume",
     *  section="Music",
     *  statusCodes={
     *      200="Returned when successful"
     *  },
     *  tags={
     *      "dev"
     *  }
     *)
     *
     */
    public function getMusicVolume($userId, $volume)
    {
        if(!is_numeric($userId) && (is_numeric($volume) || ($volume == '+' || $volume == '-') ) ) {
            throw $this->createNotFoundException();
        }

        $success = false;
        $response_message = null;

        /** @var User $user */
        $user = $this->getUserRepository()->find($userId);
        if (!$user)
            $response_message = "User doesn't exist";
        else if(is_numeric($volume) && ($volume < 0 || $volume > 100)) {
            $response_message = "Invalid volume parameter";
        }
        else {

            $notif = null;

            if($volume == '+') {
                $notif = new TCPControlMusicNotification($user, TCPVolumeMusicNotification::UP);
            }
            else if($volume == '-') {
                $notif = new TCPControlMusicNotification($user, TCPVolumeMusicNotification::DOWN);
            }
            else {
                $notif = new TCPControlMusicNotification($user, TCPVolumeMusicNotification::ABSOLUTE, $volume);
            }

            $notif->send();
            $response_message = 'Volume updated';
            $success = true;
        }

        // Generate response
        $statusCode = $success ? SuperController::OK : SuperController::ERROR;

        $data = array(
            'success' => $success,
            'message' => $response_message
        );

        return $this->createViewWithData(
            $data,
            array('info'),
            $statusCode
        );
    }

    /**
     * @FosView
     *
     * @param integer $userId
     *
     * @return View
     *
     * @Get("/music/play_pause/{userId}")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Play/Pause music",
     *  section="Music",
     *  statusCodes={
     *      200="Returned when successful"
     *  },
     *  tags={
     *      "dev"
     *  }
     *)
     *
     */
    public function getPlayPauseAction($userId)
    {
        if(!is_numeric($userId)) {
            throw $this->createNotFoundException();
        }

        $success = false;
        $response_message = null;

        /** @var User $user */
        $user = $this->getUserRepository()->find($userId);
        if (!$user)
            $response_message = "User doesn't exist";
        else {
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();

            // Currently played music available ?
            if($user->getCurrentlyPlayedMusic()) {
                // Is playing ?
                if($user->isIsPlayingMusic()) {
                    // Pause music
                    $notif = new TCPControlMusicNotification($user, TCPControlMusicNotification::PAUSE_MESSAGE);
                    $notif->send();

                    $user->setIsPlayingMusic(false);
                    $response_message = 'Now playing music';
                }
                else {
                    // Play music
                    $notif = new TCPControlMusicNotification($user, TCPControlMusicNotification::PLAY_MESSAGE);
                    $notif->send();

                    $user->setIsPlayingMusic(true);
                    $response_message = 'Music has been paused';
                }

                try {
                    $em->flush($user);
                    $success = true;
                }
                catch(\PDOException $e) {
                    $response_message = 'An error occurred - Code 1';
                }
                catch(DBALException $e) {
                    $response_message = 'An error occurred - Code 2';
                }
            }
            // Not playing music
            else {
                $response_message = "Specified user isn't playing music";
            }

        }

        // Generate response
        $statusCode = $success ? SuperController::OK : SuperController::ERROR;

        $data = array(
            'success' => $success,
            'message' => $response_message
        );

        return $this->createViewWithData(
            $data,
            array('info'),
            $statusCode
        );

    }

}
