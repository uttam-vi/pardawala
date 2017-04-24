<?php

namespace FM\ApiBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class OAuthClientUserProvider implements UserProviderInterface
{
    
    /**
     * @var $em
     */
    private $em = null;
    
    /**
     * @var $oAuthClientRepository
     */
    private $oAuthClientRepository = null;

    /**
     * construct
     * 
     * @param type $entityManager
     */
    public function __construct($entityManager)
    {
        $this->em = $entityManager;
        
        $this->oAuthClientRepository = $this->em->getRepository('FM\ApiBundle\Entity\oAuth\Client');
    }

    public function getUsernameForApiKey($apiKey)
    {
        // Look up the username based on the token in the database, via
        // an API call, or do something entirely different
        $username = 'KeyUserName';
        
        //check in db for key in oauth client table

        return $username;
    }

    public function loadUserByUsername($username)
    {
        return new User(
            $username,
            null,
            // the roles for the user - you may choose to determine
            // these dynamically somehow based on the user
            array('ROLE_API')
        );
    }

    public function refreshUser(UserInterface $user)
    {
        // this is used for storing authentication in the session
        // but in this example, the token is sent in each request,
        // so authentication can be stateless. Throwing this exception
        // is proper to make things stateless
        throw new UnsupportedUserException();
    }

    public function supportsClass($class)
    {
        return 'Symfony\Component\Security\Core\User\User' === $class;
    }
}
