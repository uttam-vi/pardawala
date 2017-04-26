<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ImageCategory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Imagecategory controller.
 *
 * @Route("admin/imagecategory")
 */
class ImageCategoryController extends Controller
{
    /**
     * Lists all imageCategory entities.
     *
     * @Route("/", name="imagecategory_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $imageCategories = $em->getRepository('AppBundle:ImageCategory')->findAll();

        return $this->render('imagecategory/index.html.twig', array(
            'imageCategories' => $imageCategories,
        ));
    }

    /**
     * Creates a new imageCategory entity.
     *
     * @Route("/new", name="imagecategory_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $imageCategory = new Imagecategory();
        $form = $this->createForm('AppBundle\Form\ImageCategoryType', $imageCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $imageCategory->getImage();
            
            $fileName = $this->get('app.image_uploader')->upload($file);
            
            $imageCategory->setImage($fileName);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($imageCategory);
            $em->flush($imageCategory);

            return $this->redirectToRoute('imagecategory_show', array('id' => $imageCategory->getId()));
        }

        return $this->render('imagecategory/new.html.twig', array(
            'imageCategory' => $imageCategory,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a imageCategory entity.
     *
     * @Route("/{id}", name="imagecategory_show")
     * @Method("GET")
     */
    public function showAction(ImageCategory $imageCategory)
    {
        $deleteForm = $this->createDeleteForm($imageCategory);

        return $this->render('imagecategory/show.html.twig', array(
            'imageCategory' => $imageCategory,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing imageCategory entity.
     *
     * @Route("/{id}/edit", name="imagecategory_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ImageCategory $imageCategory)
    {
        $deleteForm = $this->createDeleteForm($imageCategory);
        $editForm = $this->createForm('AppBundle\Form\ImageCategoryType', $imageCategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            
            $file = $imageCategory->getImage();
            
            $fileName = $this->get('app.image_uploader')->upload($file);
            
            $imageCategory->setImage($fileName);
            
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('imagecategory_edit', array('id' => $imageCategory->getId()));
        }

        return $this->render('imagecategory/edit.html.twig', array(
            'imageCategory' => $imageCategory,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a imageCategory entity.
     *
     * @Route("/{id}", name="imagecategory_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ImageCategory $imageCategory)
    {
        $form = $this->createDeleteForm($imageCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($imageCategory);
            
            try{
                $em->flush($imageCategory);
                
            } catch (\Exception $ex) {
                $error = $ex->getMessage();
                
                $this->addFlash(
                    'error',
                    "Error While deleting product image."
                );
            } 
        }

        return $this->redirectToRoute('imagecategory_index');
    }

    /**
     * Creates a form to delete a imageCategory entity.
     *
     * @param ImageCategory $imageCategory The imageCategory entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ImageCategory $imageCategory)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('imagecategory_delete', array('id' => $imageCategory->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
