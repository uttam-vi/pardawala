<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

//        $clientManager = $this->container->get('fos_oauth_server.client_manager.default');
//        $client = $clientManager->createClient();
//        $client->setRedirectUris(array(''));
//        $client->setAllowedGrantTypes(array('token', 'authorization_code', 'password', 'client_credentials', 'refresh_token'));
//        $clientManager->updateClient($client);
//        exit('oauth client generated');

        $usr= $user = $this->getUser();

        if(!empty($usr)){
            // replace this example code with whatever you need
            return $this->render('default/index.html.twig', [
                'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            ]);            
        }else{
            return $this->redirectToRoute('fos_user_security_login');
        }
    }
}
