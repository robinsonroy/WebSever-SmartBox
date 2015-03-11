<?php

namespace FollowMe\Bundle\ApiBundle\Controller;

use FollowMe\Bundle\ApiBundle\Form\Type\DoorType;
use FOS\RestBundle\Controller\Annotations\View as FosView;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

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
