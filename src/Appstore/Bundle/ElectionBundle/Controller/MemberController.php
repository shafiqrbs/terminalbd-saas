<?php

namespace Appstore\Bundle\ElectionBundle\Controller;

use Appstore\Bundle\ElectionBundle\Entity\ElectionConfig;
use Appstore\Bundle\ElectionBundle\Entity\ElectionMember;
use Appstore\Bundle\ElectionBundle\Entity\ElectionMemberFamily;
use Appstore\Bundle\ElectionBundle\Form\MemberType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\RunAs;
use Symfony\Component\HttpFoundation\Response;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;


/**
 * ElectionMember controller.
 *
 */
class MemberController extends Controller
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

    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $_REQUEST;
	    $config = $this->getUser()->getGlobalOption()->getElectionConfig();
	    $type = 'member';
        $entities = $em->getRepository('ElectionBundle:ElectionMember')->findWithSearch($config,$data,$type);
        $pagination = $this->paginate($entities);
        $importCount = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember')->getImportCount($config,'Import');
        return $this->render('ElectionBundle:Member:index.html.twig', array(
            'entities' => $pagination,
            'importCount' => $importCount,
            'selected' => explode(',', $request->cookies->get('memberIds', '')),
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
            return $this->redirect($this->generateUrl('election_member'));
        }
        return $this->render('ElectionBundle:Member:new.html.twig', array(
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
        $form = $this->createForm(new MemberType($config,$location), $entity, array(
            'action' => $this->generateUrl('election_member_create'),
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

        return $this->render('ElectionBundle:Member:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ElectionMember entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
	    $config = $this->getUser()->getGlobalOption()->getElectionConfig();
        $entity = $em->getRepository('ElectionBundle:ElectionMember')->findOneBy(array('electionConfig' => $config,'id'=>$id));
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ElectionMember entity.');
        }
	    $html = $this->renderView('ElectionBundle:Member:profile.html.twig',
		    array('entity' => $entity)
	    );
	    return New Response($html);
		exit;

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
        return $this->render('ElectionBundle:Member:new.html.twig', array(
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
	    $form = $this->createForm(new MemberType($config,$location), $entity, array(
		    'action' => $this->generateUrl('election_member_update', array('id' => $entity->getId())),
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
            return $this->redirect($this->generateUrl('election_member'));
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
        return $this->redirect($this->generateUrl('election_member'));
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

	public function familyAction($id)
	{
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ElectionBundle:ElectionMember')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find ElectionMember entity.');
		}
		$editForm = $this->createEditForm($entity);
		return $this->render('ElectionBundle:Member:family.html.twig', array(
			'entity'      => $entity,
			'form'   => $editForm->createView(),
		));
	}

	public function addFamilyMemberAction(Request $request , ElectionMember $member)
	{


		$em = $this->getDoctrine()->getManager();
		$data = $request->request->all();
		if(!empty($data['name'])){
			$entity = new ElectionMemberFamily();
			$entity->setElectionMember($member);
			$entity->setName($data['name']);
			$entity->setMobile($data['mobile']);
			$entity->setNid($data['nid']);
			$entity->setAge($data['age']);
			$entity->setRelation($data['relation']);
			$em->persist($entity);
			$em->flush();
		}
		$result = $this->returnResultData($member);
		return new Response($result);
		exit;
	}

	public function returnResultData(ElectionMember $entity,$msg=''){

		$familyMembers = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMemberFamily')->getFamilyMember($entity);
		return $familyMembers;

	}

	public function familyMemberDeleteAction(ElectionMemberFamily $entity)
	{
		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		if($config == $entity->getElectionMember()->getElectionConfig()){
			if (!$entity) {
				throw $this->createNotFoundException('Unable to find ElectionMember entity.');
			}
			$em->remove($entity);
			$em->flush();
			$this->get('session')->getFlashBag()->add(
				'error',"Data has been deleted successfully"
			);
			return new Response('success');
		}else{
			return new Response('invalid');
		}

		exit;

	}

	public function memberExportToCsvAction()
	{

		set_time_limit(0);
		ignore_user_abort(true);
		$em = $this->getDoctrine()->getManager();
		$global = $this->getUser()->getGlobalOption();
		$data = $_REQUEST;
		$array = array();

		$config =   $global->getElectionConfig();
		$entities = $em->getRepository('ElectionBundle:ElectionMember')->findWithSearch($config,$data,'member');
		$entities = $entities->getQuery()->getResult();


		$array[] = 'Name,Mobile,Father Name,NID,Village,Vote center,Ward,Union/Purashabva,Thana/Upozila,District';

		/* @var $entity ElectionMember */

		foreach ($entities as $key => $entity){

			$rows = array(
				$entity->getName(),
				$entity->getMobile(),
				$entity->getFatherName(),
				$entity->getNid(),
				$entity->getVillage(),
				$entity->getVoteCenterName(),
				$entity->getWard(),
				$entity->getMemberUnion(),
				$entity->getThana(),
				$entity->getDistrict(),

			);
			$array[] = implode(',', $rows);
		}
		$compareStart = new \DateTime();
		$start =  $compareStart->format('d-m-Y');
		$fileName = $start.'-member-list.csv';
		$content = implode("\n", $array);
		$response = new Response($content);
		$response->headers->set('Content-Type', 'text/csv');
		$response->headers->set('Content-Type', 'application/octet-stream');
		$response->headers->set('Content-Disposition', 'attachment; filename='.$fileName);
		return $response;
		exit;

	}

	public function memberImportUpdatedAction()
	{

		ini_set( 'max_execution_time', 0 );
		ignore_user_abort( true );

		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember')->findBy(array('electionConfig'=>$config,'process'=>'Import'));
		foreach ($entities as $entity):
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
		endforeach;
		return $this->redirect($this->generateUrl('election_member'));

	}

	public function generateCardAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$data = explode(',',$request->cookies->get('memberIds'));
		if(is_null($data)) {
			return $this->redirect($this->generateUrl('election_member'));
		}
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember')->getBarcodeForPrint($config,$data);

		return $this->render('ElectionBundle:Member:idcard.html.twig', array(
			'entities'      => $entities,
			'config'      => $config
		));
	}

/*
	public function itemBarcoder(ElectionConfig $config , ElectionMember $entity )
	{


		$barcodeWidth = "{$config->getBarcodeWidth()}px";
		$barcodeHeight = "{$config->getBarcodeHeight()}px";
		$barcodeMargin = $config->getBarcodeMargin();
		if($barcodeMargin == 0 ){
			$margin = 0;
		}else{
			$margin = "{$barcodeMargin}px";
		}
		$barcodePadding = $config->getBarcodePadding();
		if($barcodePadding == 0 ){
			$padding = 0;
		}else{
			$padding = "{$barcodePadding}px";
		}
		$barcodeBorder = $config->getBarcodeBorder();
		if($barcodeBorder > 0 ){
			$border = "{$barcodeBorder}px";
		}else{
			$border = 0;
		}

		$text = $config->getCardText();
		$scale = $config->getBarcodeScale();
		$fontsize = $config->getBarcodeFontSize();
		$thickness = $config->getBarcodeThickness();

		$barcode = new BarcodeGenerator();
		$barcode->setText($entity->getMemberId());
		$barcode->setType(BarcodeGenerator::Code128);
		$barcode->setScale($scale);
		$barcode->setThickness($thickness);
		$barcode->setFontSize(8);
		$code = $barcode->generate();
		$data = '';
	//	$data .='<div class="barcode-block" style="width:'.$barcodeWidth.'; height:'.$barcodeHeight.'; border:'.$border.'; padding:'.$padding.'; margin-top:'.$margin.'; ">';
		$data .='<div class="centered">';
	//	$data .='<div class="clearfix"></div>';
		$data .='<img src="data:image/png;base64,'.$code.'" />';
		$data .='</div>';
	//	$data .='</div>';
		return $data;

	}
*/


}
