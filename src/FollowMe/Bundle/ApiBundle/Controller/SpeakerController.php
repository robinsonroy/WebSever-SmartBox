<?php

namespace FollowMe\Bundle\ApiBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use FollowMe\Bundle\ApiBundle\Form\Type\SpeakerType;
use FollowMe\Bundle\ModelBundle\Entity\Room;
use FollowMe\Bundle\ModelBundle\Entity\Speaker;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\View as FosView;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;

class SpeakerController extends SuperController
{
    /**
     * @FosView
     *
     * @return View
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Return all speakers",
     *  section="Speaker",
     *  output={
     *      "class"="FollowMe\Bundle\ModelBundle\Entity\Speaker",
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
    public function getSpeakersAction()
    {
        $data = $this->getSpeakerRepository()->findAll();

        return $this->createViewWithData(
            $data,
            array('list')
        );
    }

    /**
     * @FosView
     *
     * @param int $id
     * @return View
     *
     * @Get("/speaker/{id}")
     *
     * @ApiDoc(
     *  resource=false,
     *  description="Return speaker's information",
     *  section="Speaker",
     *  output={
     *      "class"="FollowMe\Bundle\ModelBundle\Entity\Speaker",
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
    public function getSpeakerAction($id)
    {
        /** @var Speaker $speaker */
        $speaker = $this->getSpeakerRepository()->find($id);

        // If speaker exists
        if($speaker)
        {
            return $this->createViewWithData(
                $speaker,
                array('info')
            );
        }

        // Speaker doesn't exists
        return $this->createViewWithData(
            array(
                'success' => false,
                'message' => "Speaker doesn't exists"
            ),
            null,
            SuperController::ERROR
        );
    }

    /**
     * @param Request $request
     * @param Speaker $speaker
     * @return View
     */
    protected function process(Request $request, Speaker $speaker = null){

        $success = false;
        $error_message = null;

        // What request is it ?
        $isEditionRequest = $request->isMethod('POST');
        $isCreationRequest = $request->isMethod('PUT');

        if ($isCreationRequest || $isEditionRequest) {

            // Decode data
            $raw = json_decode($request->getContent(), true);

            // Validate data
            if( isset($raw['room']) && isset($raw['name']) )
            {
                /** @var EntityManager $em */
                $em = $this->getDoctrine()->getManager();

                // Room exists ?
                /** @var Room $room */
                $room = $this->getRoomRepository()->find($raw['room']);
                if($room) {

                    // Set data
                    if($isCreationRequest) {
                        $speaker = new Speaker();
                    }
                    else if(!$speaker) {
                        $error_message = "Speaker doesn't exists";
                    }

                    // If valid data
                    if($speaker)
                    {
                        // Update data
                        $speaker->setRoom($room);
                        $speaker->setName($raw['name']);

                        try{
                            // Persist
                            if($isCreationRequest) {
                                $em->persist($speaker);
                            }

                            // Update
                            $em->flush();
                            $success = true;

                        } catch(\PDOException $e) {
                            //
                        }
                    }

                }
                // Room doesn't exits
                else {
                    $error_message = "Specified room doesn't exists";
                }
            }
            // Invalid input
            else {
                $error_message = "Input data isn't valid";
            }

        }
        // Invalid method
        else {
            $error_message = "HTTP Methods isn't valid";
        }

        $statusCode = $success ? SuperController::OK : SuperController::ERROR;

        if($success) {
            $data['speaker'] = $speaker;
        }
        else {
            $data = array(
                'success' => $success,
                'message' => $error_message
            );
        }

        return $this->createViewWithData(
            $data,
            array('info'),
            $statusCode
        );
    }

    /**
     * @FosView
     *
     * @param Request $request
     * @param Speaker $speaker
     * @return View
     *
     * @Post("/speaker/{id}")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Update speaker",
     *  section="Speaker",
     *  output={
     *      "class"="FollowMe\Bundle\ModelBundle\Entity\Speaker",
     *      "groups"={"info"}
     *  },
     *  statusCodes={
     *      200="Returned when successful",
     *      400="Returned when the input data isn't valid"
     *  },
     *  tags={
     *      "dev"
     *  }
     *)
     *
     */
    public function postSpeakerAction(Request $request, Speaker $speaker)
    {
        return $this->process($request, $speaker);
    }

    /**
     * @FosView
     *
     * @param Request $request
     * @return View
     *
     * @Put("/speaker")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Create speaker",
     *  section="Speaker",
     *  output={
     *      "class"="FollowMe\Bundle\ModelBundle\Entity\Speaker",
     *      "groups"={"info"}
     *  },
     *  statusCodes={
     *      200="Returned when successful",
     *      400="Returned when the input data isn't valid"
     *  },
     *  tags={
     *      "dev"
     *  }
     *)
     *
     */
    public function putSpeakerAction(Request $request)
    {
        return $this->process($request);
    }

    /**
     * @FosView
     *
     * @param integer $id
     * @return View
     *
     * @Delete("/speaker/{id}")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Delete a speaker",
     *  section="Speaker",
     *  output={
     *      "class"="FollowMe\Bundle\ModelBundle\Entity\Speaker",
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
    public function deleteSpeakerAction($id)
    {
        /** @var Speaker $speaker */
        $speaker = $this->getSpeakerRepository()->find($id);

        if($speaker) {

            $success = true;
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();

            try {

                // Unlink room
                if($speaker->getRoom())
                {
                    $speaker->getRoom()->removeSpeaker($speaker);
                    $speaker->setRoom(null);
                }

                $em->remove($speaker);
                $em->flush();
            }
            catch(\PDOException $e) {
                $success = false;
            }

            if($success) {
                return $this->createViewWithData(
                    array(
                        'success' => true,
                        'message' => 'Speaker deleted'
                    )
                );
            }
            else {
                return $this->createViewWithData(
                    array(
                        'success' => false,
                        'message' => 'An error occurred'
                    ),
                    null,
                    SuperController::SERVER_ERROR
                );
            }
        }
        else {
            return $this->createViewWithData(
                array(
                    'success' => false,
                    'error' => "Speaker doesn't exists"
                ),
                null,
                SuperController::ERROR
            );
        }
    }

    /**
     * @FosView
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Return non synchronized speakers",
     *  section="Speaker",
     *  output={
     *      "class"="FollowMe\Bundle\ModelBundle\Entity\Speaker",
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
    public function getSpeakersNon_syncAction()
    {
        $data = $this->getSpeakerRepository()->findAll();

        return $this->createViewWithData(
            $data,
            array('list')
        );
    }
}
