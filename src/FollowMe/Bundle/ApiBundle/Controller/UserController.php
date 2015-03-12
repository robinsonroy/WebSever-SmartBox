<?php

namespace FollowMe\Bundle\ApiBundle\Controller;

use Doctrine\ORM\EntityManager;
use FollowMe\Bundle\ModelBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\View as FosView;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;

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
     * @param integer $id
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

        // If user exists
        if($user)
        {
            return $this->createViewWithData(
                $user,
                array('info')
            );
        }

        // User doesn't exist
        return $this->createViewWithData(
            array(
                'success' => false,
                'message' => "User doesn't exist"
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
        $user = null;

        // What request is it ?
        $isEditionRequest = $request->isMethod('POST');
        $isCreationRequest = $request->isMethod('PUT');

        if ($isCreationRequest || $isEditionRequest) {

            // Decode data
            $raw = json_decode($request->getContent(), true);

            // Validate data
            if( isset($raw['name']) && (  ($isCreationRequest && isset($raw['bracelet_id'])) || ($isEditionRequest && isset($raw['id'])) ) )
            {
                /** @var EntityManager $em */
                $em = $this->getDoctrine()->getManager();

                // Set data
                if($isCreationRequest) {
                    $user = new User();
                }
                else {
                    $user = $this->getUserRepository()->find($raw['id']);
                    if(!$user)
                        $error_message = "User doesn't exist";
                }

                // If valid data
                if($user)
                {
                    // Update data
                    $user->setName($raw['name']);
                    if($isCreationRequest)
                        $user->setBraceletId($raw['bracelet_id']);

                    try{
                        // Persist
                        if($isCreationRequest) {
                            $em->persist($user);
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
            $data = $user;
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
        return $this->process($request);
    }

    /**
     * @FosView
     *
     * @param Request $request
     * @return View
     *
     * @Post("/user")
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
    public function postUserAction(Request $request)
    {
        return $this->process($request);
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
        /** @var User $door */
        $user = $this->getUserRepository()->find($id);

        if($user) {

            $success = true;
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();

            try {
                // Delete user
                $em->remove($user);
                $em->flush();
            }
            catch(\PDOException $e) {
                $success = false;
            }

            if($success) {
                return $this->createViewWithData(
                    array(
                        'success' => true,
                        'message' => 'User deleted'
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
                    'error' => "User doesn't exist"
                ),
                null,
                SuperController::ERROR
            );
        }
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
