<?php

namespace Appstore\Bundle\DmsBundle\Controller;

use Appstore\Bundle\DmsBundle\Entity\DmsParticular;
use Appstore\Bundle\DmsBundle\Entity\Particular;
use Appstore\Bundle\DmsBundle\Form\DoctorType;
use Appstore\Bundle\DmsBundle\Form\CabinType;
use Appstore\Bundle\DmsBundle\Form\OtherServiceType;
use Appstore\Bundle\DmsBundle\Form\ParticularType;
use Appstore\Bundle\DmsBundle\Form\PathologyType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * ParticularController controller.
 *
 */
class ParticularController extends Controller
{

    public function paginate($entities)
    {
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $entities,
            $this->get('request')->query->get('page', 1)/*page number*/,
            50  /*limit per page*/
        );
        return $pagination;
    }


    /**
     * Lists all Particular entities.
     *
     */
    public function indexAction()
    {
        $entity = new DmsParticular();
        $data = $_REQUEST;
        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getDmsConfig();
        $pagination = $em->getRepository('DmsBundle:DmsParticular')->getServiceLists($config);
        //$pagination = $this->paginate($pagination);
        $editForm = $this->createCreateForm($entity);
        return $this->render('DmsBundle:Particular:index.html.twig', array(
            'pagination' => $pagination,
            'searchForm' => $data,
            'form'   => $editForm->createView(),
        ));

    }

    /**
     * Creates a new Particular entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new DmsParticular();
        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getDmsConfig();
        $pagination = $em->getRepository('DmsBundle:DmsParticular')->getServiceLists($config);
        //$pagination = $this->paginate($pagination);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setDmsConfig($config);
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been added successfully"
            );
            return $this->redirect($this->generateUrl('dms_particular'));
        }

        return $this->render('DmsBundle:Particular:index.html.twig', array(
            'entity' => $entity,
            'pagination' => $pagination,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Particular entity.
     *
     * @param DmsParticular $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(DmsParticular $entity)
    {
        $config = $this->getUser()->getGlobalOption()->getDmsConfig();
        $form = $this->createForm(new ParticularType($config), $entity, array(
            'action' => $this->generateUrl('dms_particular_create', array('id' => $entity->getId())),
            'method' => 'POST',
            'attr' => array(
                'class' => 'horizontal-form',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }



    /**
     * Displays a form to edit an existing Particular entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getDmsConfig();
        $pagination = $em->getRepository('DmsBundle:DmsParticular')->getServiceLists($config);
        //$pagination = $this->paginate($pagination);
        $entity = $em->getRepository('DmsBundle:DmsParticular')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Particular entity.');
        }
        $editForm = $this->createEditForm($entity);

        return $this->render('DmsBundle:Particular:index.html.twig', array(
            'entity'      => $entity,
            'pagination'      => $pagination,
            'form'   => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Particular entity.
     *
     * @param Particular $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(DmsParticular $entity)
    {

        $config = $this->getUser()->getGlobalOption()->getDmsConfig();
        $form = $this->createForm(new ParticularType($config), $entity, array(
            'action' => $this->generateUrl('dms_particular_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array(
                'class' => 'horizontal-form',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }
    /**
     * Edits an existing Particular entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getDmsConfig();
        $pagination = $em->getRepository('DmsBundle:DmsParticular')->getServiceLists($config);
        //$pagination = $this->paginate($pagination);
        $entity = $em->getRepository('DmsBundle:DmsParticular')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Particular entity.');
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been updated successfully"
            );
            return $this->redirect($this->generateUrl('dms_particular'));
        }

        return $this->render('DmsBundle:Particular:index.html.twig', array(
            'entity'      => $entity,
            'pagination'      => $pagination,
            'form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a Particular entity.
     *
     */
    public function deleteAction(DmsParticular $entity)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Particular entity.');
        }
        try {

            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'error',"Data has been deleted successfully"
            );

        } catch (ForeignKeyConstraintViolationException $e) {
            $this->get('session')->getFlashBag()->add(
                'notice',"Data has been relation another Table"
            );
        }catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add(
                'notice', 'Please contact system administrator further notification.'
            );
        }
        return $this->redirect($this->generateUrl('dms_particular'));
    }

   
    /**
     * Status a Page entity.
     *
     */
    public function statusAction(DmsParticular $entity)
    {

        $em = $this->getDoctrine()->getManager();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find District entity.');
        }

        $status = $entity->getStatus();
        if($status == 1){
            $entity->setStatus(0);
        } else{
            $entity->setStatus(1);
        }
        $em->flush();
        $this->get('session')->getFlashBag()->add(
            'success',"Status has been changed successfully"
        );
        return $this->redirect($this->generateUrl('dms_particular'));
    }
}
