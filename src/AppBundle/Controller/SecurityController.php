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
use OAuth2\OAuth2ServerException;
use AppBundle\Services\Traits\ResponseTrait;

class SecurityController extends FOSRestController
{

    use ResponseTrait;

    /**
     * @param Request $request
     *
     * @return Response
     */
    private function tokenAction(Request $request)
    {
        $server = $this->container->get('fos_oauth_server.server');
        try {
            return $server->grantAccessToken($request);
        } catch (OAuth2ServerException $e) {
            return $e->getHttpResponse();
        }
    }

    /**
     * Login user
     *
     * @ApiDoc(
     *   resource=true,
     *   description="Login user",
     *   resourceDescription="Login user",
     *   statusCodes={
     *     200="Returned when successful",
     *     401="Returned when the form has errors"
     *   },
     *   parameters={
     *      {
     *          "name"="username", 
     *          "dataType"="string", 
     *          "required"=true, 
     *          "description"="email / username of user"
     *      },
     *      {
     *          "name"="password", 
     *          "dataType"="string", 
     *          "required"=true, 
     *          "description"="password of user"
     *      },
     *      {
     *          "name"="grant_type", 
     *          "dataType"="string", 
     *          "required"=true,
     *          "description"="'password' as oAuth grant type"
     *      },
     *      {
     *          "name"="client_id", 
     *          "dataType"="string", 
     *          "required"=true, 
     *          "description"="oAuth client id"
     *      },
     *      {
     *          "name"="client_secret", 
     *          "dataType"="string", 
     *          "required"=true, 
     *          "description"="oAuth client secret"
     *      },
     *   }
     * )
     * 
     * @FOSRest\Post(
     *      "/api/login"
     * )
     * 
     * @param Request $request the request object
     *
     * @return FormTypeInterface[]|View
     */
    public function postLoginAction(Request $request)
    {
        $res = $this->tokenAction($request);

        $oAuthTokenJson = $res->getContent();

        $data['token'] = json_decode($oAuthTokenJson, true);

        if (array_key_exists('access_token', $data['token'])) {

            $em = $this->getDoctrine()->getEntityManager();
            $accessToken = $em->getRepository('AppBundle\Entity\oAuth\AccessToken')->findOneBy([ 'token' => $data['token']['access_token']]);

            // add user data
            if ($accessToken->getUser()) {

                $data['user'] = [
                    'id' => $accessToken->getUser()->getId(),
                    'roles' => $accessToken->getUser()->getRoles(),
                    'username' => $accessToken->getUser()->getUsername(),
                    'email' => $accessToken->getUser()->getEmail()
                ];
                
            }

            $message = "Login successfull.";
            return $this->successJsonResponse(200, $message, $data);
        } else if (array_key_exists("error", $data['token'])) {

            $message = $this->container->get('translator')->trans('validation.loginFail', [], 'messages');
            $errors = [];
            return $this->errorJsonResponse(401, $message, $data, $errors);
        }
    }

    
    
    /**
     * Logout user
     *
     * @ApiDoc(
     *   resource=true,
     *   description="Login user",
     *   resourceDescription="Login user",
     *   statusCodes={
     *     201="Returned when successful",
     *     400="Returned when the form has errors"
     *   },
     * )
     * 
     * @FOSRest\Get(
     *      "/secure/api/logout"
     * )
     *
     * @return FormTypeInterface[]|View
     */
    public function getLogoutAction(Request $request)
    {

        $header = getallheaders();

        if (array_key_exists('Authorization', $header)) {
            // Check token in header
            if (strpos($header['Authorization'], 'Bearer') !== false) {

                $accessToken = str_replace('Bearer ', '', $header['Authorization']);
            } else { // if not in header check for quiery string
                $accessToken = $request->getQueryString('access_token');
                $accessToken = str_replace('access_token=', '', $accessToken);
            }
        } else {

            $accessToken = $request->getQueryString('access_token');
            $accessToken = str_replace('access_token=', '', $accessToken);
        }

        $em = $this->getDoctrine()->getEntityManager();
        $accessToken = $em->getRepository('FM\ApiBundle\Entity\oAuth\AccessToken')->findOneBy([ 'token' => $accessToken]);

        if ($accessToken) {

            $em->remove($accessToken);
            $em->flush();

            $message = "Logout successfull.";
            $data = [];

            return $this->successJsonResponse(200, $message, $data);
        } else {

            $message = "Logout fail.";
            $data = [];
            $errors = [];
            return $this->errorJsonResponse(400, $message, $data, $errors);
        }
    }

}
