<?php

namespace Appstore\Bundle\ElectionBundle\Controller;

use Appstore\Bundle\ElectionBundle\Entity\ElectionCandidate;
use Appstore\Bundle\ElectionBundle\Form\CandidateType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\RunAs;
use Symfony\Component\HttpFoundation\Response;

/**
 * Candidate controller.
 *
 */
class CandidateController extends Controller
{

	/**
	 * Lists all Candidate entities.
	 *
	 */
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$entity = new ElectionCandidate();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionCandidate')->findBy(array('electionConfig' => $config),array('name'=>'DESC'));
		return $this->render('ElectionBundle:Candidate:index.html.twig', array(
			'entities' => $entities,
			'entity' => $entity,
		));
	}


	/**
	 * @Secure(roles="ROLE_ELECTION,ROLE_DOMAIN")
	 */

	public function newAction()
	{
		$em = $this->getDoctrine()->getManager();
		$entity = new ElectionCandidate();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entity->setElectionConfig($config);
		$em->persist($entity);
		$em->flush();
		return $this->redirect($this->generateUrl('election_candidate_edit', array('id' => $entity->getId())));

	}

	/**
	 * Finds and displays a ElectionMember entity.
	 *
	 */
	public function showAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entity = $em->getRepository('ElectionBundle:ElectionCandidate')->findOneBy(array('electionConfig' => $config,'id'=>$id));
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find ElectionMember entity.');
		}
		$html = $this->renderView('ElectionBundle:Candidate:show.html.twig',
			array('entity' => $entity)
		);
		return New Response($html);
		exit;

	}



	/**
	 * Displays a form to edit an existing Candidate entity.
	 *
	 */
	public function editAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entity = $em->getRepository('ElectionBundle:ElectionCandidate')->find($id);
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Candidate entity.');
		}
		$editForm = $this->createEditForm($entity);
		return $this->render('ElectionBundle:Candidate:new.html.twig', array(
			'entity'      => $entity,
			'form'   => $editForm->createView(),
		));
	}

	/**
	 * Creates a form to edit a Candidate entity.
	 *
	 * @param Candidate $entity The entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createEditForm(ElectionCandidate $entity)
	{
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$location = $this->getDoctrine()->getRepository('ElectionBundle:ElectionLocation');

		$form = $this->createForm(new CandidateType($config,$location), $entity, array(
			'action' => $this->generateUrl('election_candidate_update', array('id' => $entity->getId())),
			'method' => 'PUT',
			'attr' => array(
				'class' => 'form-horizontal',
				'novalidate' => 'novalidate',
			)
		));
		return $form;
	}
	/**
	 * Edits an existing Candidate entity.
	 *
	 */
	public function updateAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entity = $em->getRepository('ElectionBundle:ElectionCandidate')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Candidate entity.');
		}

		$editForm = $this->createEditForm($entity);
		$editForm->handleRequest($request);
		$data = $request->request->all();
		if ($editForm->isValid()) {
			$em->flush();
			$this->get('session')->getFlashBag()->add(
				'success',"Data has been changed successfully"
			);
			return $this->redirect($this->generateUrl('election_candidate'));
		}

		return $this->render('ElectionBundle:Candidate:index.html.twig', array(
			'entity'      => $entity,
			'form'   => $editForm->createView(),
		));
	}



	/**
	 * Deletes a Candidate entity.
	 *
	 */
	public function deleteAction($id)
	{

		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ElectionBundle:ElectionCandidate')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Candidate entity.');
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

		return $this->redirect($this->generateUrl('election_candidate'));
	}


	/**
	 * Status a Page entity.
	 *
	 */
	public function statusAction(Request $request, $id)
	{

		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ElectionBundle:ElectionCandidate')->find($id);

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
		return $this->redirect($this->generateUrl('election_candidate'));
	}

	public function autoSearchAction(Request $request)
	{
		$item = $_REQUEST['q'];
		if ($item) {
			$inventory = $this->getUser()->getGlobalOption()->getElectionConfig();
			$item = $this->getDoctrine()->getRepository('ElectionBundle:ElectionCandidate')->searchAutoComplete($item,$inventory);
		}
		return new JsonResponse($item);
	}

	public function searchCandidateNameAction($vendor)
	{
		return new JsonResponse(array(
			'id'=>$vendor,
			'text'=>$vendor
		));
	}

	public function returnResultData(ElectionCandidate $entity, $msg=''){

		$data = $this->getDoctrine()->getRepository('ElectionBundle:ElectionCandidateMember')->getMemberLists($entity);
		return $data;

	}

	private function createCandidateForm(ElectionCandidate $committee , ElectionCandidateMember $entity)
	{
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$memberRep = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember');
		$form = $this->createForm(new CandidateMemberType($config,$committee,$memberRep), $entity, array(
			'action' => $this->generateUrl('election_candidate_member_create',array('id' => $committee->getId())),
			'method' => 'POST',
			'attr' => array(
				'class' => 'horizontal-form',
				'id' => 'memberCandidate',
				'novalidate' => 'novalidate',
			)
		));
		return $form;
	}

	private function createCandidatePoolingForm(ElectionCandidate $committee , ElectionCandidateMember $entity)
	{
		$form = $this->createForm(new CandidatePoolingType(), $entity, array(
			'action' => $this->generateUrl('election_candidate_member_create',array('id' => $committee->getId())),
			'method' => 'POST',
			'attr' => array(
				'class' => 'horizontal-form',
				'id' => 'memberPooling',
				'novalidate' => 'novalidate',
			)
		));
		return $form;
	}

	public function newMemberAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entity = $em->getRepository('ElectionBundle:ElectionCandidate')->findOneBy(array('electionConfig'=>$config,'id'=>$id));
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Candidate entity.');
		}
		$committeeForm = $this->createCandidateForm($entity, New ElectionCandidateMember());
		$poolingForm = $this->createCandidatePoolingForm($entity, New ElectionCandidateMember());
		return $this->render('ElectionBundle:Candidate:member.html.twig', array(
			'entity'      => $entity,
			'form'   => $committeeForm->createView(),
			'pooling'   => $poolingForm->createView(),
		));
	}

	public function createMemberAction(Request $request, ElectionCandidate $committee )
	{
		$em = $this->getDoctrine()->getManager();
		$entity = new ElectionCandidateMember();
		$form = $this->createCandidateForm($committee,$entity);
		$form->handleRequest($request);
		$member = $request->request->all();
		$memberId = $member['committee']['member'];
		$member = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember')->find($memberId);
		$existMember = $this->getDoctrine()->getRepository('ElectionBundle:ElectionCandidateMember')->findOneBy(array('committee' => $committee,'member' => $member));
		if ($form->isValid() and empty($existMember)) {
			$member = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember')->find($memberId);
			$entity->setCandidate($committee);
			$entity->setMember($member);
			$em->persist($entity);
			$em->flush();
			$result = $this->returnResultData($committee);
			return new Response($result);
		}
		return new Response('invalid');
		exit;

	}

	public function memberDeleteAction(ElectionCandidateMember $entity)
	{
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		if($entity->getCandidate()->getElectionConfig()->getId() == $config->getId() ){
			$em = $this->getDoctrine()->getManager();
			$em->remove($entity);
			$em->flush();
			return new Response('success');
		}else{
			return new Response('in-valid');
		}

	}



}
