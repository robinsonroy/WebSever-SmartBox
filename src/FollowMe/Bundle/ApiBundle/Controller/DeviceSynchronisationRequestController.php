<?php

namespace FollowMe\Bundle\ApiBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use FollowMe\Bundle\ApiBundle\Form\Type\DoorType;
use FollowMe\Bundle\ModelBundle\Entity\Door;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\View as FosView;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;

class DeviceSynchronisationRequestController extends SuperController
{
    /**
     * @FosView
     *
     * @param integer $id
     * @return View
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Request a synchronisation",
     *  section="Synchronisation",
     *  statusCodes={
     *      200="Returned when successful",
     *      400="Returned when the input data isn't valid"
     *  },
     *  tags={
     *      "dev"
     *  }
     *)
     */
    public function postSyncRequest($id)
    {
        return $this->createViewWithData(
            array(
                'success' => true
            ),
            array('info')
        );
    }
}
