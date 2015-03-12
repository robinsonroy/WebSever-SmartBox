<?php

namespace FollowMe\Bundle\ApiBundle\Controller;

use FollowMe\Bundle\ModelBundle\Entity\DoorRepository;
use FollowMe\Bundle\ModelBundle\Entity\MusicRepository;
use FollowMe\Bundle\ModelBundle\Entity\RFSensorRepository;
use FollowMe\Bundle\ModelBundle\Entity\RoomRepository;
use FollowMe\Bundle\ModelBundle\Entity\SpeakerRepository;
use FollowMe\Bundle\ModelBundle\Entity\UserRepository;
use FOS\RestBundle\View\View as FosView;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SuperController extends Controller
{
    const OK    = 200;
    const CREATED = 201;
    const ERROR    = 400;
    const UNAUTHORIZED   = 401;
    const SERVER_ERROR   = 500;

    /**
     * @return UserRepository
     */
    public function getUserRepository(){
        return $this->getDoctrine()->getRepository('FollowMeModelBundle:User');
    }

    /**
     * @return SpeakerRepository
     */
    public function getSpeakerRepository(){
        return $this->getDoctrine()->getRepository('FollowMeModelBundle:Speaker');
    }

    /**
     * @return RoomRepository
     */
    public function getRoomRepository(){
        return $this->getDoctrine()->getRepository('FollowMeModelBundle:Room');
    }

    /**
     * @return DoorRepository
     */
    public function getDoorRepository(){
        return $this->getDoctrine()->getRepository('FollowMeModelBundle:Door');
    }

    /**
     * @return RFSensorRepository
     */
    public function getRFSensorRepository() {
        return $this->getDoctrine()->getRepository('FollowMeModelBundle:RFSensor');
    }

    /**
     * @return MusicRepository
     */
    public function getMusicRepository() {
        return $this->getDoctrine()->getRepository('FollowMeModelBundle:Music');
    }

    /**
     * @return \JMS\Serializer\Serializer
     */
    public function getSerializer() {
        return $this->get('jms_serializer');
    }

    /**
     * @param $data
     * @return array|null
     */
    protected function addDebug( $data ){
        if( $this->getRequest()->get('debug', false) ){
            return array( 'debug' => $data);
        }
        else
            return null;
    }

    /**
     * @return mixed
     */
    protected function getApiVersion()
    {
        return $this->container->getParameter('api_version');
    }

    /**
     * @param mixed $data
     * @param array|null $groups
     * @param int $statusCode
     * @return FosView
     */
    protected function createViewWithData($data, $groups = null, $statusCode = SuperController::OK){

        $context = new SerializationContext();
        $context->setVersion($this->getApiVersion());
        $context->setSerializeNull(true);
        if($groups)
            $context->setGroups($groups);

        /* @var $view FOSView */
        $view = FosView::create();
        $view->setFormat('json');
        $view->setStatusCode($statusCode);
        $view->setSerializationContext($context);

        $view->setData($data);
        return $view;
    }
}
