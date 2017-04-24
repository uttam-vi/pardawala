<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;  
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use AppBundle\Entity\User;
use AppBundle\Form\SignUpType;

class SignupController extends FOSRestController
{
    /**
     * Signup for given user type.
     *
     * @ApiDoc(
     *   resource=true,
     *   description="Signup for given user type",
     *   resourceDescription="Signup for given user type",
     *   input="FMApiBundle\Form\SignUpType",
     *   statusCodes={
     *     201="Returned when successful",
     *     400="Returned when the form has errors"
     *   },
     *   parameters={
     *      {
     *          "name"="username", 
     *          "dataType"="string", 
     *          "required"=true, 
     *          "description"="username"
     *      },
     *      {
     *          "name"="firstName", 
     *          "dataType"="string", 
     *          "required"=true, 
     *          "description"="firstName"
     *      },
     *      {
     *          "name"="lastName", 
     *          "dataType"="string", 
     *          "required"=true, 
     *          "description"="lastName"
     *      },
     *      {
     *          "name"="email", 
     *          "dataType"="string", 
     *          "required"=true, 
     *          "description"="email of user"
     *      },
     *      {
     *          "name"="password", 
     *          "dataType"="string", 
     *          "required"=true, 
     *          "description"="password of user NOTE : Your password must be at least 6 characters long containing at least one number and at least one uppercase letter, REGX : /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,255}/" 
     *      },
     *      {
     *          "name"="confirmPassword", 
     *          "dataType"="string", 
     *          "required"=true, 
     *          "description"="Password and confirm Password mast be same."
     *      }
     *   }
     * )
     * 
     * @FOSRest\Post(
     *      "/api/signup"
     * )
     * 
     * @param Request $request the request object
     *
     * @return FormTypeInterface[]|View
     */
    public function postSignupAction(Request $request)
    {   
        $user = new User();
        
        $form = $this->createForm('AppBundle\Form\SignUpType', $user); // new User()
        
        return $this->container->get('api.user_manager')->addUser($request, $form);
    }
    
}