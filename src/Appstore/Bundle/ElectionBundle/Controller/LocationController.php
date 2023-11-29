<?php

namespace Appstore\Bundle\ElectionBundle\Controller;

use Appstore\Bundle\ElectionBundle\Entity\ElectionLocation;
use Appstore\Bundle\ElectionBundle\Form\LocationType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Location controller.
 *
 */
class LocationController extends Controller
{

    /**
     * Lists all Location entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new ElectionLocation();
        $form = $this->createCreateForm($entity);
        $config = $this->getUser()->getGlobalOption()->getElectionConfig();
        $entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionLocation')->findBy(array('electionConfig' => $config),array('locationType'=>'ASC'));
        $locationTypes = $this->getDoctrine()->getRepository( 'ElectionBundle:ElectionParticular' )->getListOfParticular($config,'location');
        return $this->render('ElectionBundle:Location:index.html.twig', array(
            'entities' => $entities,
            'entity' => $entity,
            'locationTypes' => $locationTypes,
            'form'   => $form->createView(),
        ));
    }
    /**
     * Creates a new Location entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new ElectionLocation();
        $config = $this->getUser()->getGlobalOption()->getElectionConfig();
        $entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionLocation')->findBy(array('electionConfig' => $config),array('locationType'=>'ASC'));
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
            return $this->redirect($this->generateUrl('election_location', array('id' => $entity->getId())));
        }

        return $this->render('ElectionBundle:Location:index.html.twig', array(
            'entities' => $entities,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Location entity.
     *
     * @param ElectionLocation $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ElectionLocation $entity)
    {
	    $config = $this->getUser()->getGlobalOption()->getElectionConfig();
	    $location = $this->getDoctrine()->getRepository('ElectionBundle:ElectionLocation');
	    $form = $this->createForm(new LocationType($config,$location), $entity, array(
            'action' => $this->generateUrl('election_location_create'),
            'method' => 'POST',
            'attr' => array(
                'class' => 'horizontal-form',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }

    /**
     * Displays a form to edit an existing Location entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getElectionConfig();
	    $entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionLocation')->findBy(array('electionConfig' => $config),array('locationType'=>'ASC'));
	    $entity = $em->getRepository('ElectionBundle:ElectionLocation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Location entity.');
        }

        $editForm = $this->createEditForm($entity);
        return $this->render('ElectionBundle:Location:index.html.twig', array(
            'entities'      => $entities,
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Location entity.
    *
    * @param Location $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ElectionLocation $entity)
    {
	    $config = $this->getUser()->getGlobalOption()->getElectionConfig();
	    $location = $this->getDoctrine()->getRepository('ElectionBundle:ElectionLocation');
    	$form = $this->createForm(new LocationType($config,$location), $entity, array(
            'action' => $this->generateUrl('election_location_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array(
                'class' => 'horizontal-form',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }
    /**
     * Edits an existing Location entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getElectionConfig();
        $entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionLocation')->findBy(array('electionConfig' => $config),array('locationType'=>'ASC'));

        $entity = $em->getRepository('ElectionBundle:ElectionLocation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Location entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been changed successfully"
            );
            return $this->redirect($this->generateUrl('election_location'));
        }

        return $this->render('ElectionBundle:Location:index.html.twig', array(
            'entities'      => $entities,
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a Location entity.
     *
     */
    public function deleteAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ElectionBundle:ElectionLocation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Location entity.');
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

        return $this->redirect($this->generateUrl('election_location'));
    }


    /**
     * Status a Page entity.
     *
     */
    public function statusAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ElectionBundle:ElectionLocation')->find($id);

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
        return $this->redirect($this->generateUrl('election_location'));
    }

    public function typeSearchAction(Request $request)
    {
        $item =$request->request->get('locationType');
        if ($item) {
            $config = $this->getUser()->getGlobalOption()->getElectionConfig();
            $item = $this->getDoctrine()->getRepository('ElectionBundle:ElectionLocation')->listTypeBaseLocation($config,$item);
        }
        return new JsonResponse($item);
    }

    public function autoSearchAction(Request $request,$type)
    {
        $item = $_REQUEST['q'];
        if ($item) {
            $config = $this->getUser()->getGlobalOption()->getElectionConfig();
            $item = $this->getDoctrine()->getRepository('ElectionBundle:ElectionLocation')->searchAutoComplete($config,$item,$type);
        }
        return new JsonResponse($item);
    }

     public function allLocationSearchAction(Request $request)
    {
        $item = $_REQUEST['q'];
        if ($item) {
            $config = $this->getUser()->getGlobalOption()->getElectionConfig();
            $item = $this->getDoctrine()->getRepository('ElectionBundle:ElectionLocation')->searchAutoComplete($config,$item);
        }
        return new JsonResponse($item);
    }

    public function searchLocationNameAction($name)
    {
        return new JsonResponse(array(
            'id'=>$name,
            'text'=>$name
        ));
    }


}
