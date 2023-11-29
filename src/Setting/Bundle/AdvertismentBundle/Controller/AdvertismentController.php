<?php

namespace Setting\Bundle\AdvertismentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Setting\Bundle\AdvertismentBundle\Entity\Advertisment;
use Setting\Bundle\AdvertismentBundle\Form\AdvertismentType;

/**
 * Advertisment controller.
 *
 */
class AdvertismentController extends Controller
{


    public function paginate($entities)
    {

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $entities,
            $this->get('request')->query->get('page', 1)/*page number*/,
            20  /*limit per page*/
        );
        return $pagination;
    }



    /**
     * Lists all Advertisment entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SettingAdvertismentBundle:Advertisment')->findAll();
        $pagination = $this->paginate($entities);
        return $this->render('SettingAdvertismentBundle:Advertisment:index.html.twig', array(
            'entities' => $entities,
            'pagination' => $pagination
        ));
    }
    /**
     * Creates a new Advertisment entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Advertisment();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->upload();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',"Data has been inserted successfully"
            );
            return $this->redirect($this->generateUrl('advertisment_show', array('id' => $entity->getId())));
        }

        return $this->render('SettingAdvertismentBundle:Advertisment:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Advertisment entity.
     *
     * @param Advertisment $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Advertisment $entity)
    {
        $form = $this->createForm(new AdvertismentType(), $entity, array(
            'action' => $this->generateUrl('advertisment_create'),
            'method' => 'POST',
            'attr' => array(
                'class' => 'horizontal-form',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }

    /**
     * Displays a form to create a new Advertisment entity.
     *
     */
    public function newAction()
    {
        $entity = new Advertisment();
        $form   = $this->createCreateForm($entity);

        return $this->render('SettingAdvertismentBundle:Advertisment:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),

        ));
    }

    /**
     * Finds and displays a Advertisment entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SettingAdvertismentBundle:Advertisment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Advertisment entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SettingAdvertismentBundle:Advertisment:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Advertisment entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SettingAdvertismentBundle:Advertisment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Advertisment entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SettingAdvertismentBundle:Advertisment:new.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Advertisment entity.
    *
    * @param Advertisment $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Advertisment $entity)
    {
        $form = $this->createForm(new AdvertismentType(), $entity, array(
            'action' => $this->generateUrl('advertisment_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array(
                'class' => 'horizontal-form',
                'novalidate' => 'novalidate',
            )
        ));

        return $form;
    }
    /**
     * Edits an existing Advertisment entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SettingAdvertismentBundle:Advertisment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Advertisment entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->upload();
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been changed successfully"
            );
            return $this->redirect($this->generateUrl('advertisment_edit', array('id' => $id)));
        }

        return $this->render('SettingAdvertismentBundle:Advertisment:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Advertisment entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SettingAdvertismentBundle:Advertisment')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Advertisment entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('advertisment'));
    }

    /**
     * Creates a form to delete a Advertisment entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('advertisment_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
     * Status a Advertisment entity.
     *
     */
    public function statusAction(Request $request, Advertisment $entity)
    {

        $em = $this->getDoctrine()->getManager();
        $status = $entity->getStatus();
        if($status == 1){
            $entity->setStatus(0);
        } else{
            $entity->setStatus(1);
        }
        $em->flush();
        $this->get('session')->getFlashBag()->add(
            'error',"Status has been changed successfully"
        );
        return $this->redirect($this->generateUrl('advertisment'));
    }
}
