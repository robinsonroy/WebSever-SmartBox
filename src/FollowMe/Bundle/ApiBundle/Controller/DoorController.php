<?php

namespace FollowMe\Bundle\ApiBundle\Controller;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use FollowMe\Bundle\ModelBundle\Entity\Door;
use FollowMe\Bundle\ModelBundle\Entity\RFSensor;
use FollowMe\Bundle\ModelBundle\Entity\Room;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\View as FosView;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
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

        // If door exists
        if($door)
        {
            return $this->createViewWithData(
                $door,
                array('info')
            );
        }

        // Door doesn't exists
        return $this->createViewWithData(
            array(
                'success' => false,
                'message' => "Door doesn't exists"
            ),
            null,
            SuperController::ERROR
        );
    }

    /**
     * @param array $data
     * @return bool
     */
    private function isValidSensorData(array $data) {
        return (isset($data['id']) && isset($data['room']) && isset($data['room']['id']));
    }

    /**
     * @param array $data
     * @return RFSensor|null
     */
    private function createSensorWithData(array $data) {

        // Room exists ?
        /** @var Room $room */
        $room = $this->getRoomRepository()->find($data['room']['id']);
        if(!$room)
            return null;

        $sensor = new RFSensor();
        $sensor->setRoom($room);
        $sensor->setId($data['id']);

        return $sensor;
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
        //TODO: Don't create new sensor, link to non sync sensors

        $success = false;
        $error_message = null;
        $door = null;

        // What request is it ?
        if ($request->isMethod('PUT')) {

            // Decode data
            $raw = json_decode($request->getContent(), true);

            // Validate data
            if( isset($raw['sensor1']) && isset($raw['sensor2']) && $this->isValidSensorData($raw['sensor1']) && $this->isValidSensorData($raw['sensor2']) )
            {
                // Set data
                $door = new Door();

                /** @var EntityManager $em */
                $em = $this->getDoctrine()->getManager();

                // Create new sensors
                $sensor1 = $this->createSensorWithData($raw['sensor1']);
                $sensor2 = $this->createSensorWithData($raw['sensor2']);

                // Valid data ?
                if($sensor1 && $sensor2)
                {
                    // If valid data
                    if($door)
                    {
                        // Update data
                        $door->setSensor1($sensor1);
                        $door->setSensor2($sensor2);

                        try{
                            // Persist
                            $em->persist($sensor1);
                            $em->persist($sensor2);
                            $em->persist($door);

                            // Update
                            $em->flush();
                            $success = true;

                        } catch(\PDOException $e) {
                            $error_message = 'An error occurred';
                        } catch(DBALException $e) {
                            $error_message = 'One or both of the sensors might already be associated to another door';
                        }
                    }
                }
                // Sensors don't exits
                else {
                    $error_message = "Specified room doesn't exist";
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
            $data = $door;
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
                    SuperController::SERVER_ERROR
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
                SuperController::ERROR
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
