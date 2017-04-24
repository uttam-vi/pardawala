<?php

namespace AppBundle\Services;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use AppBundle\Entity\ProductImages;
use AppBundle\Entity\User;
use AppBundle\Services\Traits\ResponseTrait;

class ApiManager
{
    
    use ResponseTrait;
    /**
     * @var Entity Manager
     */
    protected $entityManager;
    protected $container;

    /**
     * 
     * @param EntityManager $entityManager
     * @param Service Containter $container
     */
    public function __construct(EntityManager $entityManager, $container)
    {

        $this->entityManager = $entityManager;
        $this->container = $container;
    }
    
    /**
     * getProductImages 
     * @param type $category_id
     */
    public function postOrderProduct(Request $request,User $user, Form $form)
    {
        $form->submit(array_merge($request->request->all(), $request->files->all()));
        
        if ($form->isValid()) {
            
            $object = $form->getData();
            
            $details = $object->getDetails();
            $details = json_decode($details,true);
            
            $object->setDetails($details);
            
            $originImage = $object->getOriginImage();
            
            $finalImage = $object->getFinalImage();
            
            $originalFileName = $this->container->get('app.image_uploader')->upload($originImage);
            
            $object->setOriginImage($originalFileName);
            
            $finalFileName = $this->container->get('app.image_uploader')->upload($finalImage);
            
            $object->setFinalImage($finalFileName);
            
            
            $object->setUser($user);
            
            $this->entityManager->persist($object);

            $this->entityManager->flush();
            
            $message = 'Product Order successfully saved';
            
            return $this->successJsonResponse(200, $message, []);
        }else{
            $message = $this->getError($form, $this->container->get('translator'));
            $errors = $this->getErrorMessages($form);
            $data = [];
            return $this->errorJsonResponse(400, $message, $data, $errors);
        }
    }
    
    /**
     * getProductImages 
     * @param type $category_id
     */
    public function getProductImages($category_id)
    {
        $images = $this->entityManager->getRepository('AppBundle\Entity\ProductImages')->findByImageCategory($category_id);
        
        if(!empty($images)) {
            
            $data = [];
            
            foreach($images as $key => $values){
                $data[] = [
                  'id' => $values->getId(),
                  'image' => $this->container->getParameter('product_images_directory_assert').'/'.$values->getImage()
                ];
            }
            $message = 'Images found';

            return $this->successJsonResponse(200, $message, $data);
        }      
        $message = 'Images not found';

        return $this->errorJsonResponse(200, $message, $data = [], $errors = []);
    }
}