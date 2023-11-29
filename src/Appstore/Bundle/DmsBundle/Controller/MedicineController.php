<?php

namespace Appstore\Bundle\DmsBundle\Controller;

use Appstore\Bundle\DmsBundle\Entity\DmsParticular;
use Appstore\Bundle\DmsBundle\Form\MedicineType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * MedicineController controller.
 *
 */
class MedicineController extends Controller
{

    public function paginate($entities)
    {

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $entities,
            $this->get('request')->query->get('page', 1)/*page number*/,
            25  /*limit per page*/
        );
        $pagination->setTemplate('SettingToolBundle:Widget:pagination.html.twig');
        return $pagination;
    }

    /**
     * Lists all DmsParticular entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getDmsConfig();
        $entities = $this->getDoctrine()->getRepository('DmsBundle:DmsParticular')->getMedicineParticular($config,array('accessories'));
        $pagination = $this->paginate($entities);
        $entity = new DmsParticular();
        $form = $this->createCreateForm($entity);
        return $this->render('DmsBundle:Medicine:index.html.twig', array(
            'pagination' => $pagination,
            'entity' => $entity,
            'formShow'            => 'hide',
            'form'   => $form->createView(),
        ));

    }

    /**
     * Creates a new DmsParticular entity.
     *
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getDmsConfig();
        $entities = $this->getDoctrine()->getRepository('DmsBundle:DmsParticular')->getMedicineParticular($config,array('accessories'));
        $pagination = $this->paginate($entities);
        $entity = new DmsParticular();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setDmsConfig($config);
            $service = $this->getDoctrine()->getRepository('DmsBundle:DmsService')->findOneBy(array('slug'=>'accessories'));
            $entity->setService($service);
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been added successfully"
            );
            return $this->redirect($this->generateUrl('dms_medicine'));
        }
        return $this->render('DmsBundle:Medicine:index.html.twig', array(
            'entity' => $entity,
            'pagination' => $pagination,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a DmsParticular entity.
     *
     * @param DmsParticular $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(DmsParticular $entity)
    {

        $form = $this->createForm(new MedicineType(), $entity, array(
            'action' => $this->generateUrl('dms_medicine_create', array('id' => $entity->getId())),
            'method' => 'POST',
            'attr' => array(
                'class' => 'horizontal-form',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }


    /**
     * Displays a form to edit an existing DmsParticular entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DmsBundle:DmsParticular')->find($id);
        $config = $this->getUser()->getGlobalOption()->getDmsConfig();
        $entities = $this->getDoctrine()->getRepository('DmsBundle:DmsParticular')->getMedicineParticular($config,array('accessories'));
        $pagination = $this->paginate($entities);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DmsParticular entity.');
        }
        $editForm = $this->createEditForm($entity);
        return $this->render('DmsBundle:Medicine:index.html.twig', array(
            'pagination'        => $pagination,
            'entity'            => $entity,
            'formShow'            => 'show',
            'form'              => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a DmsParticular entity.
     *
     * @param DmsParticular $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(DmsParticular $entity)
    {
        $form = $this->createForm(new MedicineType(), $entity, array(
            'action' => $this->generateUrl('dms_medicine_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array(
                'class' => 'horizontal-form',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }
    /**
     * Edits an existing DmsParticular entity.
     *
     */
    public function updateAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getDmsConfig();
        $entities = $this->getDoctrine()->getRepository('DmsBundle:DmsParticular')->getMedicineParticular($config,array('accessories'));
        $pagination = $this->paginate($entities);
        $entity = $em->getRepository('DmsBundle:DmsParticular')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DmsParticular entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',"Data has been updated successfully"
            );
            return $this->redirect($this->generateUrl('dms_medicine'));
        }
        return $this->render('DmsBundle:Medicine:index.html.twig', array(
            'pagination'      => $pagination,
            'entity'      => $entity,
            'formShow'            => 'show',
            'form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a DmsParticular entity.
     *
     */
    public function deleteAction(DmsParticular $entity)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DmsParticular entity.');
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
        return $this->redirect($this->generateUrl('dms_medicine'));
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
        return $this->redirect($this->generateUrl('dms_medicine'));
    }

    public function inlineUpdateAction(Request $request)
    {
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DmsBundle:DmsParticular')->find($data['pk']);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find particular entity.');
        }
        $setField = 'set'.$data['name'];
        $entity->$setField(abs($data['value']));
        $em->flush();
        exit;

    }
}
