<?php

namespace AppBundle\Controller;

use AppBundle\Entity\OrderProduct;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ApiController extends FOSRestController 
{
    /**
     * Get Product category list
     *
     * @ApiDoc(
     *   resource=true,
     *   description="Get Product category list",
     *   resourceDescription="Get Product category list",
     *      headers={
     *          {
     *              "name"="Authorization",
     *              "required"=true,
     *              "description"="Bearer <oAuth token>"
     *          }
     *      },
     *      statusCodes={
     *          200="Returned when successful",
     *          400="Returned when the form has errors"
     *      }
     * )
     * 
     * @FOSRest\Get(
     *      "/getProductCategory"
     * )
     * 
     * @param Request $request the request object
     *
     * @return FormTypeInterface[]|View
     */
    public function getProductCategoryAction(Request $request) 
    {
        return $this->container->get('api.api_manager')->getProductCategory($request);
    }
    
    /**
     * Get Product category wise images.
     *
     * @ApiDoc(
     *   resource=true,
     *   description="Get Product category wise images",
     *   resourceDescription="Get Product category wise images",
     *      headers={
     *          {
     *              "name"="Authorization",
     *              "required"=true,
     *              "description"="Bearer <oAuth token>"
     *          }
     *      },
     *      statusCodes={
     *          200="Returned when successful",
     *          400="Returned when the form has errors"
     *      }
     * )
     * 
     * @FOSRest\Get(
     *      "/getProductImages/{category_id}"
     * )
     * 
     * @param Request $request the request object
     *
     * @return FormTypeInterface[]|View
     */
    public function getProductImagesAction(Request $request, $category_id) 
    {
        return $this->container->get('api.api_manager')->getProductImages($category_id);
    }
    
    /**
     * Order Product : ROLE_CLIENT
     * 
     * @ApiDoc(
     *      resource=true,
     *      description="Order Product, Allowed Roles : ROLE_CLIENT",
     *      resourceDescription="Order Product",
     *      headers={
     *          {
     *              "name"="Authorization",
     *              "required"=true,
     *              "description"="Bearer <oAuth token>"
     *          }
     *      },
     *      parameters={
     *          {
     *              "name"="orderProductDetail",
     *              "dataType"="string",
     *              "required"=true,
     *              "description"="order details in json format."
     *          },
     *          {
     *              "name"="discription",
     *              "dataType"="string",
     *              "required"=true,
     *              "description"="Discription"
     *          },
     *          {
     *              "name"="originImage",
     *              "dataType"="file",
     *              "required"=true,
     *              "description"="Select origin image File"
     *          },
     *          {
     *              "name"="finalImage",
     *              "dataType"="file",
     *              "required"=true,
     *              "description"="Select final image File"
     *          }
     *      },
     *      statusCodes={
     *          200="Returned when successful",
     *          400="Returned when the form has errors"
     *      }
     * )
     * 
     * @FOSRest\Post("/OrderProduct")
     * 
     * @Security("has_role('ROLE_CLIENT')")
     * 
     * @param Request $request
     */
    public function postOrderProductAction(Request $request)
    {
        $orderProduct = new OrderProduct();
        $form = $this->createForm('\AppBundle\Form\OrderProductType',$orderProduct);
        
        return $this->container->get('api.api_manager')->postOrderProduct($request, $this->getUser(), $form);
    }

}
