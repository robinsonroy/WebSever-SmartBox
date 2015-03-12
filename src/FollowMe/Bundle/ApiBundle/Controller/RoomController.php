<?php

namespace FollowMe\Bundle\ApiBundle\Controller;

use Doctrine\ORM\EntityManager;
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

class RoomController extends SuperController
{
    /**
     * @FosView
     *
     * @return View
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Return all rooms",
     *  section="Room",
     *  output={
     *      "class"="FollowMe\Bundle\ModelBundle\Entity\Room",
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
    public function getRoomsAction()
    {
        $data = $this->getRoomRepository()->findAll();

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
     * @Get("/room/{id}")
     *
     * @ApiDoc(
     *  resource=false,
     *  description="Return room's information",
     *  section="Room",
     *  output={
     *      "class"="FollowMe\Bundle\ModelBundle\Entity\Room",
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
    public function getRoomAction($id)
    {
        /** @var Room $room $room */
        $room = $this->getRoomRepository()->find($id);

        // If the room exists
        if($room)
        {
            return $this->createViewWithData(
                $room,
                array('info')
            );
        }

        // Room doesn't exist
        return $this->createViewWithData(
            array(
                'success' => false,
                'message' => "Room doesn't exist"
            ),
            null,
            SuperController::ERROR
        );
    }

    /**
     * @param Request $request
     * @return View
     */
    protected function process(Request $request){

        $success = false;
        $error_message = null;
        $room = null;

        // What request is it ?
        $isEditionRequest = $request->isMethod('POST');
        $isCreationRequest = $request->isMethod('PUT');

        if ($isCreationRequest || $isEditionRequest) {

            // Decode data
            $raw = json_decode($request->getContent(), true);

            // Validate data
            if( isset($raw['name']) && ( $isCreationRequest ||  ($isEditionRequest && isset($raw['id'])) ) )
            {
                /** @var EntityManager $em */
                $em = $this->getDoctrine()->getManager();

                // Set data
                if($isCreationRequest) {
                    $room = new Room();
                }
                else {
                    $room = $this->getRoomRepository()->find($raw['id']);
                    if(!$room)
                        $error_message = "Room doesn't exist";
                }

                // If valid data
                if($room)
                {
                    // Update data
                    $room->setName($raw['name']);

                    try{
                        // Persist
                        if($isCreationRequest) {
                            $em->persist($room);
                        }

                        // Update
                        $em->flush();
                        $success = true;

                    } catch(\PDOException $e) {
                        //
                    }
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
            $data = $room;
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
     * @return View
     *
     * @Put("/room")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Create a room",
     *  section="Room",
     *  output={
     *      "class"="FollowMe\Bundle\ModelBundle\Entity\Room",
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
    public function putRoomAction(Request $request)
    {
        return $this->process($request);
    }

    /**
     * @FosView
     *
     * @param Request $request
     * @return View
     *
     * @Post("/room")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Update a room",
     *  section="Room",
     *  output={
     *      "class"="FollowMe\Bundle\ModelBundle\Entity\Room",
     *      "groups"={"list"}
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
    public function postRoomAction(Request $request)
    {
        return $this->process($request);
    }

    /**
     * @FosView
     *
     * @param integer $id
     * @return View
     *
     * @Delete("/room/{id}")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Delete a room",
     *  section="Room",
     *  output={
     *      "class"="FollowMe\Bundle\ModelBundle\Entity\Room",
     *      "groups"={"list"}
     *  },
     *  statusCodes={
     *      200="Returned when successful",
     *      400="Returned if room doesn't exists"
     *  },
     *  tags={
     *      "dev"
     *  }
     *)
     *
     */
    public function deleteRoomAction($id)
    {
        /** @var Room $room */
        $room = $this->getRoomRepository()->find($id);

        if($room) {

            $success = true;
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();

            try {

                // Prevent deletion if there are related sensors
                if($room->getSensors() && $room->getSensors()->count() > 0) {
                    return $this->createViewWithData(
                        array(
                            'success' => false,
                            'message' => 'There are related doors, delete the doors first'
                        ),
                        null,
                        SuperController::ERROR
                    );
                }

                // Unlink all speakers
                /** @var Speaker $speaker */
                foreach($room->getSpeakers() as $speaker) {
                    $speaker->setRoom(null);
                }

                $em->remove($room);
                $em->flush();
            }
            catch(\PDOException $e) {
                $success = false;
            }

            if($success) {
                return $this->createViewWithData(
                    array(
                        'success' => true,
                        'message' => 'Room deleted'
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
                    'error' => "Room doesn't exists"
                ),
                null,
                SuperController::ERROR
            );
        }

    }
}
