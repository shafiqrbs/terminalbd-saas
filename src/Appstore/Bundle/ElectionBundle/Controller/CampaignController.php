<?php

namespace Appstore\Bundle\ElectionBundle\Controller;

use Appstore\Bundle\ElectionBundle\Entity\ElectionCampaignAnalysis;
use Appstore\Bundle\ElectionBundle\Entity\ElectionEvent;
use Appstore\Bundle\ElectionBundle\Form\CampaignType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Campaign controller.
 *
 */
class CampaignController extends Controller
{

    /**
     * Lists all Campaign entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new ElectionCampaignAnalysis();
        $form = $this->createCreateForm($entity);
        $config = $this->getUser()->getGlobalOption()->getElectionConfig();
        $entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionCampaignAnalysis')->findBy(array('electionConfig' => $config),array('created'=>'ASC'));
        return $this->render('ElectionBundle:Campaign:index.html.twig', array(
            'entities' => $entities,
            'entity' => $entity,
            'show'      => 'hide',
            'form'   => $form->createView(),
        ));
    }
    /**
     * Creates a new Campaign entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new ElectionCampaignAnalysis();
        $config = $this->getUser()->getGlobalOption()->getElectionConfig();
        $entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionCampaignAnalysis')->findBy(array('electionConfig' => $config));
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
            return $this->redirect($this->generateUrl('election_campaign', array('id' => $entity->getId())));
        }

        return $this->render('ElectionBundle:Campaign:index.html.twig', array(
            'entities' => $entities,
            'entity' => $entity,
            'show'      => 'show',
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Campaign entity.
     *
     * @param ElectionCampaignAnalysis $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ElectionCampaignAnalysis $entity)
    {
	    $config = $this->getUser()->getGlobalOption()->getElectionConfig();
	    $location = $this->getDoctrine()->getRepository('ElectionBundle:ElectionLocation');
    	$form = $this->createForm(new CampaignType($config,$location), $entity, array(
            'action' => $this->generateUrl('election_campaign_create'),
            'method' => 'POST',
            'attr' => array(
                'class' => 'horizontal-form',
                'id' => 'campaign',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }

	/**
	 * Finds and displays a ElectionMember entity.
	 *
	 */
	public function showAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entity = $em->getRepository('ElectionBundle:ElectionCampaignAnalysis')->findOneBy(array('electionConfig' => $config,'id'=>$id));
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find ElectionMember entity.');
		}
		$html = $this->renderView('ElectionBundle:Campaign:show.html.twig',
			array('entity' => $entity)
		);
		return New Response($html);
		exit;

	}


	/**
     * Displays a form to edit an existing Campaign entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getElectionConfig();
        $entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionCampaignAnalysis')->findBy(array('electionConfig' => $config),array('created'=>'DESC'));
        $entity = $em->getRepository('ElectionBundle:ElectionCampaignAnalysis')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Campaign entity.');
        }

        $editForm = $this->createEditForm($entity);
        return $this->render('ElectionBundle:Campaign:index.html.twig', array(
            'entities'      => $entities,
            'entity'      => $entity,
            'show'      => 'show',
            'form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Campaign entity.
    *
    * @param Campaign $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ElectionCampaignAnalysis $entity)
    {
	    $config = $this->getUser()->getGlobalOption()->getElectionConfig();
	    $location = $this->getDoctrine()->getRepository('ElectionBundle:ElectionLocation');
	    $form = $this->createForm(new CampaignType($config,$location), $entity, array(
            'action' => $this->generateUrl('election_campaign_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array(
                'class' => 'horizontal-form',
                'id' => 'campaign',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }
    /**
     * Edits an existing Campaign entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getElectionConfig();
	    $entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionCampaignAnalysis')->findBy(array('electionConfig' => $config),array('created'=>'DESC'));
        $entity = $em->getRepository('ElectionBundle:ElectionCampaignAnalysis')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Campaign entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been changed successfully"
            );
            return $this->redirect($this->generateUrl('election_campaign'));
        }

        return $this->render('ElectionBundle:Campaign:index.html.twig', array(
            'entities'      => $entities,
            'entity'      => $entity,
            'show'      => 'show',
            'form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a Campaign entity.
     *
     */
    public function deleteAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ElectionBundle:ElectionCampaignAnalysis')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Campaign entity.');
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

        return $this->redirect($this->generateUrl('election_campaign'));
    }


    /**
     * Status a Page entity.
     *
     */
    public function statusAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ElectionBundle:ElectionCampaignAnalysis')->find($id);

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
        return $this->redirect($this->generateUrl('election_campaign'));
    }

    public function autoSearchAction(Request $request)
    {
        $item = $_REQUEST['q'];
        if ($item) {
            $config = $this->getUser()->getGlobalOption()->getElectionConfig();
            $item = $this->getDoctrine()->getRepository('ElectionBundle:ElectionCampaignAnalysis')->searchAutoComplete($config,$item);
        }
        return new JsonResponse($item);
    }

    public function searchCampaignNameAction($vendor)
    {
        return new JsonResponse(array(
            'id' => $vendor,
            'text' => $vendor
        ));
    }



}
