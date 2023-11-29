<?php

namespace Appstore\Bundle\EducationBundle\Controller;

use Appstore\Bundle\EducationBundle\Entity\EducationParticular;
use Appstore\Bundle\EducationBundle\Form\ParticularType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Particular controller.
 *
 */
class ParticularController extends Controller
{

    /**
     * Lists all Particular entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new EducationParticular();
        $form = $this->createCreateForm($entity);
        $config = $this->getUser()->getGlobalOption()->getEducationConfig();
        $entities = $this->getDoctrine()->getRepository( 'EducationBundle:EducationParticular' )->findBy(array( 'educationConfig' => $config),array( 'particularType' =>'ASC'));
        return $this->render('EducationBundle:Particular:index.html.twig', array(
            'entities' => $entities,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }
    /**
     * Creates a new Particular entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new EducationParticular();
        $config = $this->getUser()->getGlobalOption()->getEducationConfig();
        $entities = $this->getDoctrine()->getRepository( 'EducationBundle:EducationParticular' )->findBy(array( 'educationConfig' => $config),array( 'particularType' =>'ASC'));
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $config = $this->getUser()->getGlobalOption()->getEducationConfig();
            $entity->setEducationConfig($config);
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been inserted successfully"
            );
            return $this->redirect($this->generateUrl('education_particular', array('id' => $entity->getId())));
        }

        return $this->render('EducationBundle:Particular:index.html.twig', array(
            'entities' => $entities,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Particular entity.
     *
     * @param EducationParticular $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(EducationParticular $entity)
    {
	    $config = $this->getUser()->getGlobalOption()->getEducationConfig();
    	$form = $this->createForm(new ParticularType($config), $entity, array(
            'action' => $this->generateUrl('education_particular_create'),
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
        $config = $this->getUser()->getGlobalOption()->getEducationConfig();
        $entities = $this->getDoctrine()->getRepository( 'EducationBundle:EducationParticular' )->findBy(array( 'educationConfig' => $config),array( 'particularType' =>'ASC'));

        $entity = $em->getRepository( 'EducationBundle:EducationParticular' )->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Particular entity.');
        }

        $editForm = $this->createEditForm($entity);
        return $this->render('EducationBundle:Particular:index.html.twig', array(
            'entities'      => $entities,
            'entity'      => $entity,
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
    private function createEditForm(EducationParticular $entity)
    {
	    $config = $this->getUser()->getGlobalOption()->getEducationConfig();
    	$form = $this->createForm(new ParticularType($config), $entity, array(
            'action' => $this->generateUrl('education_particular_update', array('id' => $entity->getId())),
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
        $config = $this->getUser()->getGlobalOption()->getEducationConfig();
        $entities = $this->getDoctrine()->getRepository( 'EducationBundle:EducationParticular' )->findBy(array( 'educationConfig' => $config),array( 'particularType' =>'ASC'));

        $entity = $em->getRepository( 'EducationBundle:EducationParticular' )->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Particular entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been changed successfully"
            );
            return $this->redirect($this->generateUrl('education_particular'));
        }

        return $this->render('EducationBundle:Particular:index.html.twig', array(
            'entities'      => $entities,
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a Particular entity.
     *
     */
    public function deleteAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository( 'EducationBundle:EducationParticular' )->find($id);

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

        return $this->redirect($this->generateUrl('education_particular'));
    }


    /**
     * Status a Page entity.
     *
     */
    public function statusAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository( 'EducationBundle:EducationParticular' )->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Education Particular entity.');
        }
        $status = $entity->isStatus();
        if($status == 1){
            $entity->setStatus(false);
        } else{
            $entity->setStatus(true);
        }
        $em->flush();
        $this->get('session')->getFlashBag()->add(
            'success',"Status has been changed successfully"
        );
        return $this->redirect($this->generateUrl('education_particular'));
    }

    public function autoSearchAction(Request $request)
    {
        $item = $_REQUEST['q'];
        if ($item) {
            $inventory = $this->getUser()->getGlobalOption()->getEducationConfig();
            $item = $this->getDoctrine()->getRepository( 'EducationBundle:EducationParticular' )->searchAutoComplete($item,$inventory);
        }
        return new JsonResponse($item);
    }

    public function searchParticularNameAction($vendor)
    {
        return new JsonResponse(array(
            'id'=>$vendor,
            'text'=>$vendor
        ));
    }

}
