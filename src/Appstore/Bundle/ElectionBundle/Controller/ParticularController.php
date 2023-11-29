<?php

namespace Appstore\Bundle\ElectionBundle\Controller;

use Appstore\Bundle\ElectionBundle\Entity\ElectionParticular;
use Appstore\Bundle\ElectionBundle\Form\ParticularType;
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
        $entity = new ElectionParticular();
        $form = $this->createCreateForm($entity);
        $config = $this->getUser()->getGlobalOption()->getElectionConfig();
        $entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionParticular')->findBy(array('electionConfig' => $config),array('particularType'=>'ASC'));
        return $this->render('ElectionBundle:Particular:index.html.twig', array(
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
        $entity = new ElectionParticular();
        $config = $this->getUser()->getGlobalOption()->getElectionConfig();
        $entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionParticular')->findBy(array('electionConfig' => $config),array('particularType'=>'ASC'));
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $config = $this->getUser()->getGlobalOption()->getElectionConfig();
            $entity->setElectionConfig($config);
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been inserted successfully"
            );
            return $this->redirect($this->generateUrl('election_particular', array('id' => $entity->getId())));
        }

        return $this->render('ElectionBundle:Particular:index.html.twig', array(
            'entities' => $entities,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Particular entity.
     *
     * @param ElectionParticular $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ElectionParticular $entity)
    {
	    $config = $this->getUser()->getGlobalOption()->getElectionConfig();
    	$form = $this->createForm(new ParticularType($config), $entity, array(
            'action' => $this->generateUrl('election_particular_create'),
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
        $config = $this->getUser()->getGlobalOption()->getElectionConfig();
        $entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionParticular')->findBy(array('electionConfig' => $config),array('particularType'=>'ASC'));

        $entity = $em->getRepository('ElectionBundle:ElectionParticular')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Particular entity.');
        }

        $editForm = $this->createEditForm($entity);
        return $this->render('ElectionBundle:Particular:index.html.twig', array(
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
    private function createEditForm(ElectionParticular $entity)
    {
	    $config = $this->getUser()->getGlobalOption()->getElectionConfig();
    	$form = $this->createForm(new ParticularType($config), $entity, array(
            'action' => $this->generateUrl('election_particular_update', array('id' => $entity->getId())),
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
        $config = $this->getUser()->getGlobalOption()->getElectionConfig();
        $entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionParticular')->findBy(array('electionConfig' => $config),array('particularType'=>'ASC'));

        $entity = $em->getRepository('ElectionBundle:ElectionParticular')->find($id);

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
            return $this->redirect($this->generateUrl('election_particular'));
        }

        return $this->render('ElectionBundle:Particular:index.html.twig', array(
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
        $entity = $em->getRepository('ElectionBundle:ElectionParticular')->find($id);

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

        return $this->redirect($this->generateUrl('election_particular'));
    }


    /**
     * Status a Page entity.
     *
     */
    public function statusAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ElectionBundle:ElectionParticular')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find District entity.');
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
        return $this->redirect($this->generateUrl('election_particular'));
    }

    public function autoSearchAction(Request $request)
    {
        $item = $_REQUEST['q'];
        if ($item) {
            $inventory = $this->getUser()->getGlobalOption()->getElectionConfig();
            $item = $this->getDoctrine()->getRepository('ElectionBundle:ElectionParticular')->searchAutoComplete($item,$inventory);
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
