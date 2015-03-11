<?php

namespace FollowMe\Bundle\ApiBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use FollowMe\Bundle\ApiBundle\Form\Type\DoorType;
use FollowMe\Bundle\ModelBundle\Entity\Door;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\View as FosView;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;

class DoorController extends SuperController
{
    /**
     * @FosView
     *
     * @return View
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Return all doors",
     *  section="Door",
     *  output={
     *      "class"="FollowMe\Bundle\ModelBundle\Entity\Door",
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
    public function getDoorsAction()
    {
        $data = $this->getDoorRepository()->findAll();

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
     * @Get("/door/{id}")
     *
     * @ApiDoc(
     *  resource=false,
     *  description="Return door's information",
     *  section="Door",
     *  output={
     *      "class"="FollowMe\Bundle\ModelBundle\Entity\Door",
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
        /** @var Door $door */
        $door = $this->getDoorRepository()->find($id);

        return $this->createViewWithData(
            $door,
            array('info')
        );
    }

    /**
     * @param Request $request
     * @param Door $door
     * @return View
     */
    protected function process(Request $request, Door $door = null ){

        $success = false;

        // Form
        /** @var ObjectManager $objectManager */
        $objectManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(new DoorType($objectManager, array()), $door ?: new Door());

        // What request is it ?
        $isCreationRequest = $request->isMethod('POST');
        $isEditionRequest = $request->isMethod('PUT');

        if ($isCreationRequest || $isEditionRequest) {
            $form->handleRequest($request);

            if ($form->isValid()) {

                /** @var EntityManager $em */
                $em = $this->getDoctrine()->getManager();

                // Persist
                try{

                    if($isCreationRequest) {
                        $em->persist($door);
                    }

                    $em->flush();
                    $success = true;

                } catch(\PDOException $e) {
                    //
                }

            }
        }

        $statusCode = $success ? 200 : 400;
        $data = array(
            'success' => $success,
        );
        if($success) {
            $data['door'] = $door;
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
     * @Put("/door")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Create a door",
     *  section="Door",
     *  input="FollowMe\Bundle\ApiBundle\Form\Type\DoorType",
     *  output={
     *      "class"="FollowMe\Bundle\ModelBundle\Entity\Door",
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
    public function putDoorAction(Request $request)
    {
        return $this->process($request, new Door());
    }

    /**
     * @FosView
     *
     * @param Request $request
     * @param Door $door
     * @return View
     *
     * @Post("/door/{id}")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Update a door",
     *  section="Door",
     *  input="FollowMe\Bundle\ApiBundle\Form\Type\DoorType",
     *  output={
     *      "class"="FollowMe\Bundle\ModelBundle\Entity\Door",
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
    public function postDoorAction(Request $request, Door $door)
    {
        return $this->process($request, $door);
    }

    /**
     * @FosView
     *
     * @param integer $id
     * @return View
     *
     * @Delete("/door/{id}")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Delete a door",
     *  section="Door",
     *  output={
     *      "class"="FollowMe\Bundle\ModelBundle\Entity\Door",
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
    public function deleteDoorAction($id)
    {
        /** @var Door $door */
        $door = $this->getDoorRepository()->find($id);

        if($door) {

            $success = true;
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();

            try {
                // Unlink sensors
                $door->getSensor1()->getRoom()->removeSensor($door->getSensor1());
                $door->getSensor2()->getRoom()->removeSensor($door->getSensor2());

                // Delete sensors
                $em->remove($door->getSensor1());
                $em->remove($door->getSensor2());

                // Delete door
                $em->remove($door);
                $em->flush();
            }
            catch(\PDOException $e) {
                $success = false;
            }

            if($success) {
                return $this->createViewWithData(
                    array(
                        'success' => true,
                        'message' => 'Door deleted'
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
                    'error' => "Door doesn't exists"
                ),
                null,
                400
            );
        }
    }

    /**
     * @FosView
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Return a non synchronized sensor id",
     *  section="Door",
     *  output={
     *      "class"="FollowMe\Bundle\ModelBundle\Entity\Door",
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
    public function getDoorNon_syncAction()
    {
        $data = $this->getDoorRepository()->findAll();

        return $this->createViewWithData(
            array(

            ),
            array('list')
        );
    }
}
