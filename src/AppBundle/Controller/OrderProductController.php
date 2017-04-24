<?php

namespace AppBundle\Controller;

use AppBundle\Entity\OrderProduct;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Orderproduct controller.
 *
 * @Route("admin/orderproduct")
 */
class OrderProductController extends Controller
{
    /**
     * Lists all orderProduct entities.
     *
     * @Route("/", name="orderproduct_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $orderProducts = $em->getRepository('AppBundle:OrderProduct')->findAll();

        return $this->render('orderproduct/index.html.twig', array(
            'orderProducts' => $orderProducts,
        ));
    }

    /**
     * Finds and displays a orderProduct entity.
     *
     * @Route("/{id}", name="orderproduct_show")
     * @Method("GET")
     */
    public function showAction(OrderProduct $orderProduct)
    {
        
        return $this->render('orderproduct/show.html.twig', array(
            'orderProduct' => $orderProduct,
        ));
    }
}
