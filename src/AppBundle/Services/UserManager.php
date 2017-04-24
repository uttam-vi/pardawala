<?php

namespace AppBundle\Services;

use AppBundle\Entity\User;
use AppBundle\Services\Traits\ResponseTrait;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class UserManager
{

    use ResponseTrait;

    /**
     * @var entity manager
     */
    protected $entityManager;

    /**
     * @var type 
     */
    protected $passwordEncoder;

    /**
     *
     * @var type 
     */
    protected $container;

    /**
     * @param Entity Manager $entityManager
     */
    public function __construct($entityManager, $passwordEncoder, $container)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->container = $container;
    }

    /**
     * 
     * @param Request $request
     * @param Form $form
     * @return type
     */
    public function addUser(Request $request, Form $form)
    {
        $detailedError = $request->get('detailedError');
       
        $password = $request->request->get('password');
        $confirmPassword = $request->request->get('confirmPassword');
        
        $request->request->remove('confirmPassword');

        $form->submit($request->request->all());
        if ($form->isValid()) {
            
            if($password != $confirmPassword){
                $message = 'Password and confirm password must be same.';
                $data = [];
                $errors = [];
                return $this->errorJsonResponse(400, $message, $data, $errors);
            }
            
            $object = $form->getData();

            try {

                $encoder = $this->passwordEncoder;
                $plainPassword = $request->request->get('password');
                $encoded = $encoder->encodePassword($object, $plainPassword);
                
                $object->setPassword($encoded);
                
                $object->setEnabled('1');
                
                $object->setUsername($object->getUsername());
                
                $object->setEmail($object->getEmail());

                $object->setRoles(['ROLE_CLIENT']);
                                
                $this->entityManager->persist($object);
                $this->entityManager->flush();
            } catch (Exception $e) {

                $message = $this->container->get('translator')->trans('validation.addException', [], 'messages');
                $data = [];
                $errors = [];
                return $this->errorJsonResponse(400, $message, $data, $errors);
            }
            
            $data = [];
            
            $data['user'] = [
                'id' => $object->getId(),
                'roles' => $object->getRoles(),
                'username' => $object->getUsername(),
                'email' => $object->getEmail(),
                'firstName' => $object->getFirstName(),
                'lastName' => $object->getLastName()
            ];
            
            $message = 'New user successfully created';
            
            return $this->successJsonResponse(201, $message, $data);
        } else {

            $message = $this->getError($form, $this->container->get('translator'));

            $errors = $this->getErrorMessages($form);
            
            $data = [];
            return $this->errorJsonResponse(400, $message, $data, $errors);
        }

        return \FOS\RestBundle\View\View::create($form, 400);
    }

}
