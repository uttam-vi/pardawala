<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ProductImages;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Productimage controller.
 *
 * @Route("admin/productimages")
 */
class ProductImagesController extends Controller
{
    /**
     * Lists all productImage entities.
     *
     * @Route("/", name="productimages_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $productImages = $em->getRepository('AppBundle:ProductImages')->findAll();

        return $this->render('productimages/index.html.twig', array(
            'productImages' => $productImages,
        ));
    }

    /**
     * Creates a new productImage entity.
     *
     * @Route("/new", name="productimages_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $productImage = new ProductImages();
        $form = $this->createForm('AppBundle\Form\ProductImagesType', $productImage);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $file = $productImage->getImage();
            
            $fileName = $this->get('app.image_uploader')->upload($file);
            
            $productImage->setImage($fileName);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($productImage);
            $em->flush($productImage);

            return $this->redirectToRoute('productimages_show', array('id' => $productImage->getId()));
        }

        return $this->render('productimages/new.html.twig', array(
            'productImage' => $productImage,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a productImage entity.
     *
     * @Route("/{id}", name="productimages_show")
     * @Method("GET")
     */
    public function showAction(ProductImages $productImage)
    {
        $deleteForm = $this->createDeleteForm($productImage);

        return $this->render('productimages/show.html.twig', array(
            'productImage' => $productImage,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing productImage entity.
     *
     * @Route("/{id}/edit", name="productimages_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ProductImages $productImage)
    {
        $deleteForm = $this->createDeleteForm($productImage);
        $editForm = $this->createForm('AppBundle\Form\ProductImagesType', $productImage);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            
//            $file = $productImage->getImage();
//            
//            $fileName = $this->get('app.image_uploader')->upload($file);
//            
//            $productImage->setImage($fileName);
            
            return $this->redirectToRoute('productimages_edit', array('id' => $productImage->getId()));
        }

        return $this->render('productimages/edit.html.twig', array(
            'productImage' => $productImage,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a productImage entity.
     *
     * @Route("/{id}", name="productimages_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ProductImages $productImage)
    {
        $form = $this->createDeleteForm($productImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($productImage);
            $em->flush($productImage);
        }

        return $this->redirectToRoute('productimages_index');
    }

    /**
     * Creates a form to delete a productImage entity.
     *
     * @param ProductImages $productImage The productImage entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ProductImages $productImage)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('productimages_delete', array('id' => $productImage->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
