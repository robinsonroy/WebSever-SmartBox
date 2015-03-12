<?php

namespace FollowMe\Bundle\ApiBundle\Controller;

use FollowMe\Bundle\ModelBundle\Entity\Music;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View as FosView;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class MusicController extends SuperController
{
    /**
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
    public function getDoorAction($id)
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
     * @FosView
     *
     * @param integer $id
     * @return View
     *
     * @Get("/music/{id}/play")
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
    public function getPlayMusicAction($id)
    {

    }

    /**
     * @FosView
     *
     * @param integer $id
     * @return View
     *
     * @Get("/music/{id}/pause")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Pause music",
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
    public function getPauseMusicAction($id)
    {

    }

    /**
     * @FosView
     *
     * @param integer $id
     * @return View
     *
     * @Get("/music/{id}/stop")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Stop music",
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
    public function getStopMusicAction($id)
    {

    }

}
