<?php

namespace FollowMe\Bundle\ApiBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use FollowMe\Bundle\ApiBundle\Form\Type\RoomType;
use FollowMe\Bundle\ModelBundle\Entity\Room;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\View as FosView;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializerBuilder;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

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

        return $this->createViewWithData(
            $room,
            array('info')
        );
    }

    /**
     * @param Request $request
     * @param Room $room
     * @return View
     */
    protected function process(Request $request, Room $room = null ){

        $success = false;

        // Form
        /** @var ObjectManager $objectManager */
        $objectManager = $this->getDoctrine()->getManager();

        if($room == null)
            $room = new Room();

        $form = $this->createForm(new RoomType($objectManager, array()), $room);

        // What request is it ?
        $isCreationRequest = $request->isMethod('POST');
        $isEditionRequest = $request->isMethod('PUT');

        if ($isCreationRequest || $isEditionRequest) {
            $form->submit($request->get($form->getName()), true);

            if ($form->isValid()) {

                /** @var EntityManager $em */
                $em = $this->getDoctrine()->getManager();

                // Persist
                try{

                    if($isCreationRequest) {
                        $em->persist($room);
                    }

                    $em->flush();
                    $success = true;

                } catch(\PDOException $e) {
                    //
                }

            }
            else {
                var_dump($form->isSubmitted());
                var_dump($form->isSynchronized());
                var_dump($form->isEmpty());
                var_dump($form->isRequired());
                var_dump($request->getContent());
                var_dump($form->getErrors()->count());
                var_dump($room);
            }
        }

        $statusCode = $success ? 200 : 400;
        $data = array(
            'success' => $success,
        );
        if($success) {
            $data['room'] = $room;
        }
        else {
            $data['error'] = "Input data isn't valid";
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
     *  input="FollowMe\Bundle\ApiBundle\Form\Type\RoomType",
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
     * @param Room $room
     * @return View
     *
     * @Post("/room/{id}")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Update a room",
     *  section="Room",
     *  input="FollowMe\Bundle\ApiBundle\Form\Type\RoomType",
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
    public function postRoomAction(Request $request, Room $room)
    {
        return $this->process($request, $room);
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
                    500
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
                400
            );
        }

    }
}
