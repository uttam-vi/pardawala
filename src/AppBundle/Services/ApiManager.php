<?php

namespace AppBundle\Services;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use AppBundle\Entity\ProductImages;
use AppBundle\Entity\User;
use AppBundle\Entity\OrderProductDetail;
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
        $orderProductDetail = $request->request->get('orderProductDetail');
        
        $request->request->remove('orderProductDetail');
        
        $form->submit(array_merge($request->request->all(), $request->files->all()));
        
        if ($form->isValid()) {
            
            $object = $form->getData();
            
            $details = json_decode($orderProductDetail,true);
            $detailErrors = [];
            foreach($details as $key=>$value)
            {
                $imageCategory = !empty($value['imageCategory'])?$value['imageCategory']:'';
                $productImage = !empty($value['productImage'])?$value['productImage']:'';
                
                if($imageCategory && $productImage){
                    
                    $imageCategory = $this->entityManager->getRepository('AppBundle\Entity\ImageCategory')
                            ->find($imageCategory);
                    
                    $productImage = $this->entityManager->getRepository('AppBundle\Entity\ProductImages')
                                    ->findOneBy([
                                        'id'=>$productImage,
                                        'imageCategory'=>$imageCategory
                                    ]);
                    
                    $OrderProductDetail = new OrderProductDetail();
                    
                    $OrderProductDetail->setImageCategory($imageCategory);
                    $OrderProductDetail->setProductImages($productImage);
                    
                    $object->addOrderProductDetail($OrderProductDetail);
                    
                }else{
                    $detailErrors[$key] = 'imageCategory or productImage not found.';
                }
            }
            
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
    public function getProductImages(Request $request, $category_id)
    {
        $images = $this->entityManager->getRepository('AppBundle\Entity\ProductImages')->findByImageCategory($category_id);
        
        if(!empty($images)) {
            
            $data = [];
            $baseurl = $this->container->getParameter("base_url");
            foreach($images as $key => $values){
                $data[] = [
                  'id' => $values->getId(),
                  'image' => ($values->getImage())?$baseurl.'/'.$this->container->getParameter('product_images_directory_assert').'/'.$values->getImage():''
                ];
            }
            $message = 'Images found';

            return $this->successJsonResponse(200, $message, $data);
        }      
        $message = 'Images not found';

        return $this->errorJsonResponse(200, $message, $data = [], $errors = []);
    }
    
    /**
     * getProductCategory 
     * 
     */
    public function getProductCategory(Request $request)
    {
        $images = $this->entityManager->getRepository('AppBundle\Entity\ImageCategory')->findAll();
        
        if(!empty($images)) {
            
            $data = [];
            $baseurl = $this->container->getParameter("base_url");
            
            foreach($images as $key => $values){
                $data[] = [
                  'id' => $values->getId(),
                  'name' => $values->getName(),
                  'image' => ($values->getImage())?$baseurl.'/'.$this->container->getParameter('product_images_directory_assert').'/'.$values->getImage():''
                ];
            }
            $message = 'Category list';

            return $this->successJsonResponse(200, $message, $data);
        }      
        $message = 'Category not found';

        return $this->errorJsonResponse(200, $message, $data = [], $errors = []);
    }
}