<?php

namespace Appstore\Bundle\ElectionBundle\Controller;


use Appstore\Bundle\ElectionBundle\Entity\ElectionMemberImport;
use Appstore\Bundle\ElectionBundle\Form\ImportType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\RunAs;
use Setting\Bundle\ToolBundle\Form\ColorType;

/**
 * ItemColor controller.
 *
 */
class ImportController extends Controller
{

	/**
	 * Lists all ElectionMemberImport entities.
	 *
	 */
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entities = $em->getRepository('ElectionBundle:ElectionMemberImport')->findBy(array('electionConfig' => $config),array('updated'=>'DESC'));
		return $this->render('ElectionBundle:ElectionMemberImport:index.html.twig', array(
			'entities' => $entities,
		));
	}
	/**
	 * Creates a new ElectionMemberImport entity.
	 *
	 */
	public function createAction(Request $request)
	{
		$entity = new ElectionMemberImport();
		$form = $this->createCreateForm($entity);
		$form->handleRequest($request);
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		if ($form->isValid()) {

			$em = $this->getDoctrine()->getManager();
			$entity->setElectionConfig($config);
			$entity->upload();
			$em->persist($entity);
			$em->flush();
			$this->get('session')->getFlashBag()->add(
				'success',"Data has been added successfully"
			);
			return $this->redirect($this->generateUrl('election_member_import'));
		}

		return $this->render('ElectionBundle:ElectionMemberImport:new.html.twig', array(
			'entity' => $entity,
			'form'   => $form->createView(),
		));
	}

	/**
	 * Creates a form to create a ElectionMemberImport entity.
	 *
	 * @param ElectionMemberImport $entity The entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createCreateForm(ElectionMemberImport $entity)
	{
		$form = $this->createForm(new ImportType(), $entity, array(
			'action' => $this->generateUrl('election_member_import_create'),
			'method' => 'POST',
		));
		return $form;
	}

	/**
	 * Displays a form to create a new ElectionMemberImport entity.
	 *
	 */
	public function newAction()
	{
		$entity = new ElectionMemberImport();
		$form   = $this->createCreateForm($entity);
		return $this->render('ElectionBundle:ElectionMemberImport:new.html.twig', array(
			'entity' => $entity,
			'form'   => $form->createView(),
		));
	}

	public function excelDataImportAction(ElectionMemberImport $excelElectionMemberImporter) {

		//set_time_limit(0);
		ini_set( 'max_execution_time', 0 );
		ignore_user_abort( true );

		$em     = $this->getDoctrine()->getManager();
		$config = $excelElectionMemberImporter->getElectionConfig();
		if ( $excelElectionMemberImporter->getVoterType() == 'member' ) {
			$importer = $this->get( 'appstore_election_member_import' );
			$importer->setElectionConfig( $config->getId() );
			$reader = $this->get( 'appstore_election.importer.member_import' );
			$file   = realpath( $excelElectionMemberImporter->getAbsolutePath() );
			$data   = $reader->getData( $file );
		}else{
			$importer = $this->get( 'appstore_election_voter_import' );
			$importer->setElectionConfig( $config->getId() );
			$reader = $this->get( 'appstore_election.importer.voter_import' );
			$file   = realpath( $excelElectionMemberImporter->getAbsolutePath() );
			$data   = $reader->getData( $file );
		}

		if($importer->isValid($data)) {
			$importer->import($data);
			$excelElectionMemberImporter->setProgress('migrated');
			$this->get('session')->getFlashBag()->add(
				'success',"Data has been migration successfully"
			);
			$em->flush();
		}else{
			$this->get('session')->getFlashBag()->add(
				'error',"Excel File memo no null or exist in your system"
			);
			$em->flush();
		}
		return $this->redirect($this->generateUrl('election_member_import'));
	}


	public function deleteAction(ElectionMemberImport $excelElectionMemberImporter)
	{
		$em = $this->getDoctrine()->getManager();
		if ($excelElectionMemberImporter) {
			$excelElectionMemberImporter->removeUpload();
			$em->remove($excelElectionMemberImporter);
			$em->flush();
		}
		return $this->redirect($this->generateUrl('election_member_import'));
	}



}
