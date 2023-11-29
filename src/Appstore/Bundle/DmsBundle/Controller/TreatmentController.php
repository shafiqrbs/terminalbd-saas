<?php

namespace Appstore\Bundle\DmsBundle\Controller;

use Appstore\Bundle\DmsBundle\Entity\DmsParticular;
use Appstore\Bundle\DmsBundle\Form\TreatmentType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * TreatmentController controller.
 *
 */
class TreatmentController extends Controller
{


    /**
     * Lists all DmsParticular entities.
     *
     */
    public function indexAction()
    {

        $entity = new DmsParticular();
        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getDmsConfig();
        $pagination = $em->getRepository('DmsBundle:DmsParticular')->getFindDentalServiceParticular($config,array('treatment'));
        $form = $this->createCreateForm($entity);
        return $this->render('DmsBundle:Treatment:index.html.twig', array(
            'pagination' => $pagination,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));

    }

    /**
     * Creates a new DmsParticular entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new DmsParticular();
        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getDmsConfig();
        $pagination = $em->getRepository('DmsBundle:DmsParticular')->getFindDentalServiceParticular($config,array('treatment'));

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setDmsConfig($config);
            $service = $this->getDoctrine()->getRepository('DmsBundle:DmsService')->findOneBy(array('serviceFormat'=>'treatment'));
            $entity->setService($service);
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been added successfully"
            );
            return $this->redirect($this->generateUrl('dms_treatment', array('id' => $entity->getId())));
        }

        return $this->render('DmsBundle:Treatment:index.html.twig', array(
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
        $globalOption = $this->getUser()->getGlobalOption();
        $form = $this->createForm(new TreatmentType($globalOption), $entity, array(
            'action' => $this->generateUrl('dms_treatment_create', array('id' => $entity->getId())),
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
        $config = $this->getUser()->getGlobalOption()->getDmsConfig();
        $pagination = $em->getRepository('DmsBundle:DmsParticular')->getFindDentalServiceParticular($config,array('treatment'));      $entity = $em->getRepository('DmsBundle:DmsParticular')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DmsParticular entity.');
        }
        $editForm = $this->createEditForm($entity);
        return $this->render('DmsBundle:Treatment:index.html.twig', array(
            'entity'      => $entity,
            'pagination'      => $pagination,
            'form'   => $editForm->createView(),
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
        $globalOption = $this->getUser()->getGlobalOption();
        $form = $this->createForm(new TreatmentType($globalOption), $entity, array(
            'action' => $this->generateUrl('dms_treatment_update', array('id' => $entity->getId())),
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
        $pagination = $em->getRepository('DmsBundle:DmsParticular')->getFindDentalServiceParticular($config,array('treatment'));
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
            return $this->redirect($this->generateUrl('dms_treatment'));
        }

        return $this->render('DmsBundle:Treatment:index.html.twig', array(
            'entity'      => $entity,
            'pagination'      => $pagination,
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
        return $this->redirect($this->generateUrl('dms_treatment'));
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
        return $this->redirect($this->generateUrl('dms_treatment'));
    }
}
