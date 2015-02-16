<?php

namespace FollowMe\Bundle\ApiBundle\Controller;

use FollowMe\Bundle\ModelBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\View as FosView;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\BrowserKit\Request;

class UserController extends SuperController
{
    /**
     * @FosView
     *
     * @return View
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Return all users",
     *  section="User",
     *  output={
     *      "class"="FollowMe\Bundle\ModelBundle\Entity\User",
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
    public function getUsersAction()
    {
        $data = $this->getUserRepository()->findAll();

        return $this->createViewWithData(
            $data,
            array('list')
        );
    }

    /**
     * @FosView
     *
     * @return View
     *
     * @Get("/user/{id}")
     *
     * @ApiDoc(
     *  resource=false,
     *  description="Return user's information",
     *  section="User",
     *  output={
     *      "class"="FollowMe\Bundle\ModelBundle\Entity\User",
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
    public function getUserAction($id)
    {
        /** @var User $user */
        $user = $this->getUserRepository()->find($id);

        return $this->createViewWithData(
            $user,
            array('info')
        );
    }

    /**
     * @FosView
     *
     * @param Request $request
     * @return View
     *
     * @Put("/user")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Create a user",
     *  section="User",
     *  output={
     *      "class"="FollowMe\Bundle\ModelBundle\Entity\User",
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
    public function putUserAction(Request $request)
    {
        $data = $this->getUserRepository()->findAll();

        return $this->createViewWithData(
            array(

            ),
            array('list')
        );
    }

    /**
     * @FosView
     *
     * @param Request $request
     * @param User $user
     * @return View
     *
     * @Post("/user/{id}")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Update a user",
     *  section="User",
     *  output={
     *      "class"="FollowMe\Bundle\ModelBundle\Entity\User",
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
    public function postUserAction(Request $request, User $user)
    {
        $data = $this->getUserRepository()->findAll();

        return $this->createViewWithData(
            array(

            ),
            array('info')
        );
    }

    /**
     * @FosView
     *
     * @param integer $id
     * @return View
     *
     * @Delete("/user/{id}")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Delete a user",
     *  section="User",
     *  output={
     *      "class"="FollowMe\Bundle\ModelBundle\Entity\User",
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
    public function deleteUserAction($id)
    {
        /** @var User $user */
        $user = $this->getUserRepository()->find($id);

        return $this->createViewWithData(
            array(

            ),
            array('list')
        );
    }

    /**
     * @FosView
     *
     * @return View
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Return a non synchronized bracelet id",
     *  section="User",
     *  output={
     *      "class"="FollowMe\Bundle\ModelBundle\Entity\User",
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
    public function getUserNon_syncAction()
    {
        $data = $this->getUserRepository()->findAll();

        return $this->createViewWithData(
            array(

            ),
            array('list')
        );
    }
}
