<?php

namespace Appstore\Bundle\ElectionBundle\Controller;

use Appstore\Bundle\ElectionBundle\Entity\ElectionMember;
use Appstore\Bundle\ElectionBundle\Entity\ElectionMemberFamily;
use Appstore\Bundle\ElectionBundle\Form\MemberType;
use Appstore\Bundle\ElectionBundle\Form\VoterType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\RunAs;
use Symfony\Component\HttpFoundation\Response;


/**
 * ElectionMember controller.
 *
 */
class VoterController extends Controller
{


    public function paginate($entities)
    {

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $entities,
            $this->get('request')->query->get('page', 1)/*page number*/,
            25  /*limit per page*/
        );
        return $pagination;
    }


    /**
     * @Secure(roles="ROLE_ELECTION,ROLE_DOMAIN")
     */

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $data = $_REQUEST;
	    $config = $this->getUser()->getGlobalOption()->getElectionConfig();

        $entities = $em->getRepository('ElectionBundle:ElectionMember')->findWithSearch($config,$data);
        $pagination = $this->paginate($entities);
        $importCount = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember')->getImportCount($config,'Import');
        return $this->render('ElectionBundle:Voter:index.html.twig', array(
            'entities' => $pagination,
            'importCount' => $importCount,
            'searchForm' => $data,
        ));
    }

    /**
     * Creates a new ElectionMember entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new ElectionMember();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $config = $this->getUser()->getGlobalOption()->getElectionConfig();
            $entity->setElectionConfig($config);
	        $mobile = $this->get('settong.toolManageRepo')->specialExpClean($entity->getMobile());
	        $entity->setMobile($mobile);
	        $entity->setVillage($entity->getLocation()->getName());
	        $entity->setVoteCenterName($entity->getVoteCenter()->getName());
	        $entity->setWard($entity->getLocation()->wardName());
	        $entity->setMemberUnion($entity->getLocation()->unionName());
	        $entity->setThana($entity->getLocation()->thanaName());
	        $entity->setDistrict($entity->getElectionConfig()->getDistrict()->getName());
	        $entity->upload();
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('election_voter'));
        }
        return $this->render('ElectionBundle:Voter:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a ElectionMember entity.
     *
     * @param ElectionMember $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ElectionMember $entity)
    {
	    $config = $this->getUser()->getGlobalOption()->getElectionConfig();
        $location = $this->getDoctrine()->getRepository('ElectionBundle:ElectionLocation');
        $form = $this->createForm(new VoterType($config,$location), $entity, array(
            'action' => $this->generateUrl('election_voter_create'),
            'method' => 'POST',
            'attr' => array(
                'class' => 'form-horizontal',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }

    /**
     * Displays a form to create a new ElectionMember entity.
     *
     */

    /**
     * @Secure(roles="ROLE_ELECTION,ROLE_DOMAIN")
     */

    public function newAction()
    {
        $entity = new ElectionMember();
        $form   = $this->createCreateForm($entity);

        return $this->render('ElectionBundle:Voter:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }



    /**
     * Displays a form to edit an existing ElectionMember entity.
     *
     */

    /**
     * @Secure(roles="ROLE_ELECTION,ROLE_DOMAIN")
     */

    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ElectionBundle:ElectionMember')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ElectionMember entity.');
        }

        $editForm = $this->createEditForm($entity);
        return $this->render('ElectionBundle:Voter:new.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }

	/**
    * Creates a form to edit a ElectionMember entity.
    *
    * @param ElectionMember $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ElectionMember $entity)
    {
	    $config = $this->getUser()->getGlobalOption()->getElectionConfig();
	    $location = $this->getDoctrine()->getRepository('ElectionBundle:ElectionLocation');
	    $form = $this->createForm(new VoterType($config,$location), $entity, array(
		    'action' => $this->generateUrl('election_voter_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array(
                'class' => 'form-horizontal',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }
    /**
     * Edits an existing ElectionMember entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ElectionBundle:ElectionMember')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ElectionMember entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
	        $mobile = $this->get('settong.toolManageRepo')->specialExpClean($entity->getMobile());
	        $entity->setMobile($mobile);
	        $entity->setVillage($entity->getLocation()->getName());
	        $entity->setVoteCenterName($entity->getVoteCenter()->getName());
	        $entity->setWard($entity->getLocation()->wardName());
	        $entity->setMemberUnion($entity->getLocation()->unionName());
	        $entity->setThana($entity->getLocation()->thanaName());
	        $entity->setDistrict($entity->getElectionConfig()->getDistrict()->getName());
	        if($entity->getRemoveImage() == 1){
		        $entity->removeUpload();
	        }
	        $entity->upload();
	        $em->flush();
            return $this->redirect($this->generateUrl('election_voter'));
        }

        return $this->render('ElectionBundle:Member:new.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }


    /**
     * @Secure(roles="ROLE_ELECTION,ROLE_DOMAIN")
     */

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $globalOption = $this->getUser()->getGlobalOption();
        $entity = $em->getRepository('ElectionBundle:ElectionMember')->findOneBy(array('globalOption'=>$globalOption,'id' => $id));
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ElectionMember entity.');
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
        }
        return $this->redirect($this->generateUrl('election_voter'));
    }

	/**
	 * Status a Page entity.
	 *
	 */
	public function statusAction(Request $request, $id)
	{

		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ElectionBundle:ElectionMember')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find District entity.');
		}

		$status = $entity->isMember();
		if($status == 1){
			$entity->setIsMember(false);
			$entity->setMemberType('voter');
		} else{
			$entity->setIsMember(true);
			$entity->setMemberType('member');
		}
		$em->flush();
		$this->get('session')->getFlashBag()->add(
			'success',"Data has been changed successfully"
		);
		return $this->redirect($request->headers->get('referer'));
	}


	public function autoSearchAction(Request $request)
    {

        $item = $_REQUEST['q'];
        if ($item) {
            $go = $this->getUser()->getGlobalOption()->getElectionConfig();
            $item = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember')->searchAutoComplete($go,$item);
        }
        return new JsonResponse($item);
    }

    public function searchNameAction($customer)
    {
        return new JsonResponse(array(
            'id'=> $customer,
            'text' => $customer
        ));
    }

    public function autoMobileSearchAction(Request $request)
    {
        $item = $_REQUEST['q'];
        if ($item) {
            $go = $this->getUser()->getGlobalOption();
            $item = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember')->searchAutoCompleteName($go,$item);
        }
        return new JsonResponse($item);
    }

    public function searchElectionMemberMobileAction($customer)
    {
        return new JsonResponse(array(
            'id'=> $customer,
            'text' => $customer
        ));
    }

    public function autoCodeSearchAction(Request $request)
    {

        $q = $_REQUEST['term'];
        $option = $this->getUser()->getGlobalOption();
        $entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember')->searchAutoCompleteCode($option,$q);
        $items = array();
        foreach ($entities as $entity):
            $items[]=array('id' => $entity['customer'],'value' => $entity['text']);
        endforeach;
        return new JsonResponse($items);

    }

    public function searchCodeAction($customer)
    {
        return new JsonResponse(array(
            'id'=> $customer,
            'text' => $customer
        ));
    }


    public function autoLocationSearchAction(Request $request)
    {
        $item = $_REQUEST['q'];
        if ($item) {
            $item = $this->getDoctrine()->getRepository('SettingLocationBundle:Location')->searchAutoComplete($item);
        }
        return new JsonResponse($item);

    }

    public function searchLocationNameAction($location)
    {
        return new JsonResponse(array(
            'id'=> $location,
            'text' => $location
        ));
    }

    public function searchAutoCompleteNameAction()
    {
        $q = $_REQUEST['q'];
        $option = $this->getUser()->getGlobalOption();
        $entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember')->searchAutoCompleteName($option,$q);
        $items = array();
        foreach ($entities as $entity):
            $items[]=array('id' => $entity['id'],'value' => $entity['id']);
        endforeach;
        return new JsonResponse($entities);

    }

    public function searchAutoCompleteMobileAction()
    {
        $q = $_REQUEST['term'];
        $option = $this->getUser()->getGlobalOption();
        $entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember')->searchAutoComplete($option,$q);
        $items = array();
        foreach ($entities as $entity):
            $items[]=array('id' => $entity['customer'],'value' => $entity['id']);
        endforeach;
        return new JsonResponse($items);

    }

	public function voterImportUpdatedAction()
	{

		ini_set( 'max_execution_time', 0 );
		ignore_user_abort( true );

		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember')->findBy(array('electionConfig'=>$config,'memberType'=>'voter','process'=>'Import'));
		foreach ($entities as $entity):
			if(!empty($entity->getVillage())){
				$location = $this->getDoctrine()->getRepository('ElectionBundle:ElectionLocation')->getVillageMemberName($config ,$entity->getVillage());
				$voteCenter = $this->getDoctrine()->getRepository('ElectionBundle:ElectionLocation')->getMemberVoteCenter($config ,$entity->getVoteCenterName());
				if(!empty($location)){
					$entity->setLocation($location);
					$entity->setVillage($entity->getLocation()->getName());
					$entity->setWard($entity->getLocation()->wardName());
					$entity->setMemberUnion($entity->getLocation()->unionName());
					$entity->setThana($entity->getLocation()->thanaName());
					$entity->setDistrict($entity->getElectionConfig()->getDistrict()->getName());
					if(!empty($voteCenter)){
						$entity->setVoteCenter($voteCenter);
						$entity->setVoteCenterName($entity->getVoteCenter()->getName());
					}
					$entity->setProcess('Approved');
					$em->flush();
				}
			}

		endforeach;
		return $this->redirect($this->generateUrl('election_voter'));

	}



}
