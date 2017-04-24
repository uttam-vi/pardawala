<?php

namespace AppBundle\Security;


use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

use Doctrine\Common\Persistence\ObjectRepository;

use Doctrine\ORM\NoResultException;

class UserProvider implements UserProviderInterface
{
    
    /**
     * @var $em
     */
    private $em = null;
    
    /**
     * @var $userRepository
     */
    private $userRepository = null;

    /**
     * construct
     * 
     * @param type $entityManager
     */
    public function __construct($entityManager)
    {
        $this->em = $entityManager;
        
        $this->userRepository = $this->em->getRepository('AppBundle:User');
    }

    /**
     * loadUserByUsername
     * 
     * @param string $username
     * 
     * @return User
     * @throws UsernameNotFoundException
     */
    public function loadUserByUsername($username)
    {

        // find user by username or email
        $q = $this->userRepository
            ->createQueryBuilder('u')
            ->where('(u.username = :username OR u.email = :email) AND u.enabled = 1')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery();
        
        try {
            $user = $q->getSingleResult();
        } catch (NoResultException $e) {
            $message = sprintf(
                'Unable to find by "%s".',
                $username
            );
            throw new UsernameNotFoundException($message, 0, $e);
        }
        
        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(
                sprintf(
                    'Instances of "%s" are not supported.',
                    $class
                )
            );
        }
        return $this->userRepository->find($user->getId());
    }

    public function supportsClass($class)
    {
        return $this->userRepository->getClassName() === $class
        || is_subclass_of($class, $this->userRepository->getClassName());
    }

}