<?php

namespace Appstore\Bundle\ElectionBundle\Controller;

use Appstore\Bundle\ElectionBundle\Entity\ElectionCandidate;
use Appstore\Bundle\ElectionBundle\Entity\ElectionSetup;
use Appstore\Bundle\ElectionBundle\Entity\ElectionVoteMatrix;
use Appstore\Bundle\ElectionBundle\Form\SetupType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Setup controller.
 *
 */
class ElectionSetupController extends Controller
{

	/**
	 * Lists all Setup entities.
	 *
	 */
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$entity = new ElectionSetup();
		$form = $this->createCreateForm($entity);
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionSetup')->findBy(array('electionConfig' => $config),array('created'=>'ASC'));
		return $this->render('ElectionBundle:Setup:index.html.twig', array(
			'entities' => $entities,
			'entity' => $entity,
			'show'      => 'hide',
			'form'   => $form->createView(),
		));
	}

	public function newAction(){}
	/**
	 * Creates a new Setup entity.
	 *
	 */
	public function createAction(Request $request)
	{
		$entity = new ElectionSetup();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionSetup')->findBy(array('electionConfig' => $config));
		$form = $this->createCreateForm($entity);
		$form->handleRequest($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$config = $this->getUser()->getGlobalOption()->getElectionConfig();
			$entity->setElectionConfig($config);
			$totalVoter = ($entity->getMaleVoter() + $entity->getFemaleVoter() + $entity->getOtherVoter());
			$entity->setTotalVoter($totalVoter);
			$em->persist($entity);
			$em->flush();
			$this->get('session')->getFlashBag()->add(
				'success',"Data has been inserted successfully"
			);
			return $this->redirect($this->generateUrl('election_setup', array('id' => $entity->getId())));
		}

		return $this->render('ElectionBundle:Setup:index.html.twig', array(
			'entities' => $entities,
			'entity' => $entity,
			'show'      => 'show',
			'form'   => $form->createView(),
		));
	}

	/**
	 * Creates a form to create a Setup entity.
	 *
	 * @param ElectionSetup $entity The entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createCreateForm(ElectionSetup $entity)
	{
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$location = $this->getDoctrine()->getRepository('ElectionBundle:ElectionLocation');
		$form = $this->createForm(new SetupType($config,$location), $entity, array(
			'action' => $this->generateUrl('election_setup_create'),
			'method' => 'POST',
			'attr' => array(
				'class' => 'form-horizontal',
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
		$entity = $em->getRepository('ElectionBundle:ElectionSetup')->findOneBy(array('electionConfig' => $config,'id'=>$id));
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find ElectionMember entity.');
		}
		$html = $this->renderView('ElectionBundle:Setup:show.html.twig',
			array('entity' => $entity)
		);
		return New Response($html);
		exit;

	}


	/**
	 * Displays a form to edit an existing Setup entity.
	 *
	 */
	public function editAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionSetup')->findBy(array('electionConfig' => $config),array('created'=>'DESC'));
		$entity = $em->getRepository('ElectionBundle:ElectionSetup')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Setup entity.');
		}

		$editForm = $this->createEditForm($entity);
		return $this->render('ElectionBundle:Setup:index.html.twig', array(
			'entities'      => $entities,
			'entity'      => $entity,
			'show'      => 'show',
			'form'   => $editForm->createView(),
		));
	}

	/**
	 * Creates a form to edit a Setup entity.
	 *
	 * @param Setup $entity The entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createEditForm(ElectionSetup $entity)
	{
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$location = $this->getDoctrine()->getRepository('ElectionBundle:ElectionLocation');
		$form = $this->createForm(new SetupType($config,$location), $entity, array(
			'action' => $this->generateUrl('election_setup_update', array('id' => $entity->getId())),
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
	 * Edits an existing Setup entity.
	 *
	 */
	public function updateAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionSetup')->findBy(array('electionConfig' => $config),array('created'=>'DESC'));
		$entity = $em->getRepository('ElectionBundle:ElectionSetup')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Setup entity.');
		}

		$editForm = $this->createEditForm($entity);
		$editForm->handleRequest($request);

		if ($editForm->isValid()) {
			$totalVoter = ($entity->getMaleVoter() + $entity->getFemaleVoter() + $entity->getOtherVoter());
			$entity->setTotalVoter($totalVoter);
			$em->flush();
			$this->get('session')->getFlashBag()->add(
				'success',"Data has been changed successfully"
			);
			return $this->redirect($this->generateUrl('election_setup'));
		}

		return $this->render('ElectionBundle:Setup:index.html.twig', array(
			'entities'      => $entities,
			'entity'      => $entity,
			'show'      => 'show',
			'form'   => $editForm->createView(),
		));
	}
	/**
	 * Deletes a Setup entity.
	 *
	 */
	public function deleteAction($id)
	{

		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ElectionBundle:ElectionSetup')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Setup entity.');
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

		return $this->redirect($this->generateUrl('election_setup'));
	}


	/**
	 * Status a Page entity.
	 *
	 */
	public function statusAction(Request $request, $id)
	{

		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ElectionBundle:ElectionSetup')->find($id);

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
		return $this->redirect($this->generateUrl('election_setup'));
	}

	public function autoSearchAction(Request $request)
	{
		$item = $_REQUEST['q'];
		if ($item) {
			$inventory = $this->getUser()->getGlobalOption()->getElectionConfig();
			$item = $this->getDoctrine()->getRepository('ElectionBundle:ElectionSetup')->searchAutoComplete($item,$inventory);
		}
		return new JsonResponse($item);
	}

	public function searchSetupNameAction($vendor)
	{
		return new JsonResponse(array(
			'id'=>$vendor,
			'text'=>$vendor
		));
	}

	public function matrixAction($id)
	{

		set_time_limit(0);
		ignore_user_abort(true);
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ElectionBundle:ElectionSetup')->findOneBy(array('electionConfig'=>$config,'id'=>$id));
		$this->getDoctrine()->getRepository('ElectionBundle:ElectionVoteMatrix')->setupMatrix($entity);

		$matrixs = $this->getDoctrine()->getRepository('ElectionBundle:ElectionVoteMatrix')->getMatrixValue($entity);

		$matrixArr = array();

		/* @var $matrix ElectionVoteMatrix */

		foreach ($matrixs as $matrix){
			$id = $matrix['centerId'].'-'.$matrix['candidateId'];
			$matrixArr[$id] = $matrix;
		}
		return $this->render('ElectionBundle:Setup:matrix.html.twig', array(

			'entity'      => $entity,
			'matrixArr'  => $matrixArr
		));
	}

	public function centerVoteUpdateAction(Request $request)
	{
		$data = $_REQUEST;
		$centerId = (int)$data['centerId'];
		$resultTotalVote = (int)$data['resultTotalVote'];
		$resultInvalidVote = (int)$data['resultInvalidVote'];
		$process = $data['process'];
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ElectionBundle:ElectionVoteCenter')->find($centerId);
		if(!empty($entity) and ($entity->getTotalVoter() >= ($resultTotalVote + $resultInvalidVote) )){
			$entity->setResultTotalVote($resultTotalVote);
			$entity->setResultInvalidVote($resultInvalidVote);
			$entity->setProcess($process);
			$em->persist($entity);
			$em->flush();
			$data = $this->getDoctrine()->getRepository('ElectionBundle:ElectionVoteCenter')->updateTotalVote($entity->getElectionSetup());
			$res = $this->getDoctrine()->getRepository('ElectionBundle:ElectionSetup')->updateTotalVote($entity->getElectionSetup(),$data);
			$result = array(
				'resultTotalVote'           =>  $res->getResultTotalVote(),
				'resultInvalidVote'         =>  $res->getResultInvalidVote(),
				'activeVoteCenter'          =>  $res->getActiveVoteCenter(),
				'holdVoteCenter'            =>  $res->getHoldVoteCenter(),
				'rejectedVoteCenter'        =>  $res->getRejectedVoteCenter(),
				'msg'                       =>  'success',
			);
			return new Response(json_encode($result));
		}else{
			/* @var $res ElectionSetup */
			$res = $entity->getElectionSetup();
			$result = array(
				'resultTotalVote'           =>  $res->getResultTotalVote(),
				'resultInvalidVote'         =>  $res->getResultInvalidVote(),
				'activeVoteCenter'          =>  $res->getActiveVoteCenter(),
				'holdVoteCenter'            =>  $res->getHoldVoteCenter(),
				'rejectedVoteCenter'        =>  $res->getRejectedVoteCenter(),
				'centerTotalVote'           =>  $entity->getResultTotalVote(),
				'centerInvalidVote'         =>  $entity->getResultInvalidVote(),
				'msg'                       =>  "Enter input vote is greater then actual vote",
			);
			return new Response(json_encode($result));
		}
		exit;
	}

	public function voteUpdateAction(Request $request)
	{
		$data = $_REQUEST;
		$matrixId = (int)$data['matrixId'];
		$maleVoter = (int)$data['maleVoter'];
		$femaleVoter = (int)$data['femaleVoter'];
		$otherVoter = (int)$data['otherVoter'];
		$totalVoter = ($maleVoter + $femaleVoter + $otherVoter);
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ElectionBundle:ElectionVoteMatrix')->find($matrixId);
		$totalVoteCast = $this->getDoctrine()->getRepository('ElectionBundle:ElectionVoteMatrix')->getCenterTotalCastVote($entity->getVoteCenter());
		if(!empty($entity) and $entity->getVoteCenter()->getTotalVoter() >= $totalVoteCast ){

			$entity->setMaleVoter($maleVoter);
			$entity->setFemaleVoter($femaleVoter);
			$entity->setOtherVoter($otherVoter);
			$entity->setTotalVoter($totalVoter);
			$em->flush();
			$data = $this->getDoctrine()->getRepository('ElectionBundle:ElectionVoteMatrix')->updateTotalVote($entity->getCandidate());
			$res = $this->getDoctrine()->getRepository('ElectionBundle:ElectionCandidate')->updateTotalVote($entity->getCandidate(),$data);
			$result = array(
				'candidateId'   =>  $res->getId(),
				'maleVote'      =>  $res->getMaleVote(),
				'femaleVote'    =>  $res->getFemaleVote(),
				'otherVote'     =>  $res->getOtherVote(),
				'totalVote'     =>  $res->getTotalVote(),
				'msg'           =>  'success'
			);
			return new Response(json_encode($result));

		}else{

			/* @var $res ElectionCandidate */
			$res = $entity->getCandidate();
			$result = array(
				'candidateId'   =>  $res->getId(),
				'maleVote'      =>  $res->getMaleVote(),
				'femaleVote'    =>  $res->getFemaleVote(),
				'otherVote'     =>  $res->getOtherVote(),
				'totalVote'     =>  $res->getTotalVote(),
				'msg'           =>  "Enter input vote is greater then actual vote",
			);
			return new Response(json_encode($result));
		}
		exit;
	}

	public function centerCandidateTotalVoteUpdateAction(Request $request)
	{
		$data = $_REQUEST;
		$matrixId = (int)$data['matrixId'];
		$totalVoter = (int)$data['centerCandidateVote'];
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ElectionBundle:ElectionVoteMatrix')->find($matrixId);
		$totalVoteCast = $this->getDoctrine()->getRepository('ElectionBundle:ElectionVoteMatrix')->getCenterTotalCastVote($entity->getVoteCenter());
		if(!empty($entity) and $entity->getVoteCenter()->getResultTotalVote() >= ($totalVoteCast + $totalVoter) ){
			$entity->setTotalVoter($totalVoter);
			$em->flush();
			$data = $this->getDoctrine()->getRepository('ElectionBundle:ElectionVoteMatrix')->updateTotalVote($entity->getCandidate());
			$res = $this->getDoctrine()->getRepository('ElectionBundle:ElectionCandidate')->updateTotalVote($entity->getCandidate(),$data);
			$result = array(
				'candidateId'   =>  $res->getId(),
				'maleVote'      =>  $res->getMaleVote(),
				'femaleVote'    =>  $res->getFemaleVote(),
				'otherVote'     =>  $res->getOtherVote(),
				'totalVote'     =>  $res->getTotalVote(),
				'msg'           =>  'success'
			);
			return new Response(json_encode($result));

		}else{

			/* @var $res ElectionCandidate */

			$res = $entity->getCandidate();
			$result = array(
				'candidateId'   =>  $res->getId(),
				'maleVote'      =>  $res->getMaleVote(),
				'femaleVote'    =>  $res->getFemaleVote(),
				'otherVote'     =>  $res->getOtherVote(),
				'totalVote'     =>  $res->getTotalVote(),
				'centerVote'    =>  $entity->getTotalVoter(),
				'msg'           =>  "Enter input vote is greater then actual vote",
			);
			return new Response(json_encode($result));
		}
		exit;
	}

	public function resultGenerateAction($id)
	{
		set_time_limit(0);
		ignore_user_abort(true);
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ElectionBundle:ElectionSetup')->findOneBy(array('electionConfig'=>$config,'id'=>$id));
		$html = $this->renderView('ElectionBundle:Setup:generate.html.twig',
			array('entity' => $entity)
		);
		return New Response($html);
		exit;

	}


}
