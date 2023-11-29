<?php

namespace Appstore\Bundle\EducationBundle\Controller;

use Appstore\Bundle\EducationBundle\Entity\Student;
use Appstore\Bundle\EducationBundle\Form\StudentType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\RunAs;
use Symfony\Component\HttpFoundation\Response;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;


/**
 * Student controller.
 *
 */
class StudentController extends Controller
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
     * @Secure(roles="ROLE_EDUCATION,ROLE_DOMAIN")
     */

    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $_REQUEST;
	    $config = $this->getUser()->getGlobalOption()->getEducationConfig();
	    $entities = $em->getRepository('EducationBundle:Student')->findWithSearch($config,$data);
        $pagination = $this->paginate($entities);
        return $this->render('EducationBundle:Student:index.html.twig', array(
            'entities' => $pagination,
            'searchForm' => $data,
        ));
    }

    /**
     * Creates a new Student entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Student();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $config = $this->getUser()->getGlobalOption()->getEducationConfig();
            $entity->setElectionConfig($config);
	        $mobile = $this->get('settong.toolManageRepo')->specialExpClean($entity->getMobile());
	        $entity->setMobile($mobile);
	        $entity->upload();
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('education_student'));
        }
        return $this->render('EducationBundle:Student:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Student entity.
     *
     * @param  $entity Student The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Student $entity)
    {
	    $config = $this->getUser()->getGlobalOption()->getEducationConfig();
        $location = $this->getDoctrine()->getRepository('SettingLocationBundle:Location');
        $form = $this->createForm(new StudentType($config,$location), $entity, array(
            'action' => $this->generateUrl('education_student_create'),
            'method' => 'POST',
            'attr' => array(
                'class' => 'form-horizontal',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }

    /**
     * Displays a form to create a new Student entity.
     *
     */

    /**
     * @Secure(roles="ROLE_EDUCATION,ROLE_DOMAIN")
     */

    public function newAction()
    {
        $entity = new Student();
        $form   = $this->createCreateForm($entity);

        return $this->render('EducationBundle:Student:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Student entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
	    $config = $this->getUser()->getGlobalOption()->getEducationConfig();
        $entity = $em->getRepository('ElectionBundle:Student')->findOneBy(array('electionConfig' => $config,'id'=>$id));
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Student entity.');
        }
	    $html = $this->renderView('EducationBundle:Student:profile.html.twig',
		    array('entity' => $entity)
	    );
	    return New Response($html);
		exit;

    }


    /**
     * Displays a form to edit an existing Student entity.
     *
     */

    /**
     * @Secure(roles="ROLE_EDUCATION,ROLE_DOMAIN")
     */

    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ElectionBundle:Student')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Student entity.');
        }

        $editForm = $this->createEditForm($entity);
        return $this->render('EducationBundle:Student:new.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }

	/**
    * Creates a form to edit a Student entity.
    *
    * @param Student $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Student $entity)
    {
	    $config = $this->getUser()->getGlobalOption()->getEducationConfig();
	    $location = $this->getDoctrine()->getRepository('ElectionBundle:ElectionLocation');
	    $form = $this->createForm(new MemberType($config,$location), $entity, array(
		    'action' => $this->generateUrl('education_student_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array(
                'class' => 'form-horizontal',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }
    /**
     * Edits an existing Student entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ElectionBundle:Student')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Student entity.');
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
	        $entity->setDistrict($entity->getEducationConfig()->getDistrict()->getName());
	        if($entity->getRemoveImage() == 1){
		        $entity->removeUpload();
	        }
	        $entity->upload();
	        $em->flush();
            return $this->redirect($this->generateUrl('education_student'));
        }

        return $this->render('EducationBundle:Student:new.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }


    /**
     * @Secure(roles="ROLE_EDUCATION,ROLE_DOMAIN")
     */

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $globalOption = $this->getUser()->getGlobalOption();
        $entity = $em->getRepository('ElectionBundle:Student')->findOneBy(array('globalOption'=>$globalOption,'id' => $id));
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Student entity.');
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
        return $this->redirect($this->generateUrl('education_student'));
    }

    public function autoSearchAction(Request $request)
    {

        $item = $_REQUEST['q'];
        if ($item) {
            $go = $this->getUser()->getGlobalOption()->getEducationConfig();
            $item = $this->getDoctrine()->getRepository('ElectionBundle:Student')->searchAutoComplete($go,$item);
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
            $item = $this->getDoctrine()->getRepository('ElectionBundle:Student')->searchAutoCompleteName($go,$item);
        }
        return new JsonResponse($item);
    }

    public function searchStudentMobileAction($customer)
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
        $entities = $this->getDoctrine()->getRepository('ElectionBundle:Student')->searchAutoCompleteCode($option,$q);
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
        $entities = $this->getDoctrine()->getRepository('ElectionBundle:Student')->searchAutoCompleteName($option,$q);
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
        $entities = $this->getDoctrine()->getRepository('ElectionBundle:Student')->searchAutoComplete($option,$q);
        $items = array();
        foreach ($entities as $entity):
            $items[]=array('id' => $entity['customer'],'value' => $entity['id']);
        endforeach;
        return new JsonResponse($items);

    }

	public function familyAction($id)
	{
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ElectionBundle:Student')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Student entity.');
		}
		$editForm = $this->createEditForm($entity);
		return $this->render('EducationBundle:Student:family.html.twig', array(
			'entity'      => $entity,
			'form'   => $editForm->createView(),
		));
	}

	public function addFamilyMemberAction(Request $request , Student $member)
	{


		$em = $this->getDoctrine()->getManager();
		$data = $request->request->all();
		if(!empty($data['name'])){
			$entity = new StudentFamily();
			$entity->setStudent($member);
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

	public function returnResultData(Student $entity,$msg=''){

		$familyMembers = $this->getDoctrine()->getRepository('ElectionBundle:StudentFamily')->getFamilyMember($entity);
		return $familyMembers;

	}

	public function familyMemberDeleteAction(StudentFamily $entity)
	{
		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getEducationConfig();
		if($config == $entity->getStudent()->getEducationConfig()){
			if (!$entity) {
				throw $this->createNotFoundException('Unable to find Student entity.');
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

		$config =   $global->getEducationConfig();
		$entities = $em->getRepository('ElectionBundle:Student')->findWithSearch($config,$data,'member');
		$entities = $entities->getQuery()->getResult();


		$array[] = 'Name,Mobile,Father Name,NID,Village,Vote center,Ward,Union/Purashabva,Thana/Upozila,District';

		/* @var $entity Student */

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
		$config = $this->getUser()->getGlobalOption()->getEducationConfig();
		$entities = $this->getDoctrine()->getRepository('ElectionBundle:Student')->findBy(array('electionConfig'=>$config,'process'=>'Import'));
		foreach ($entities as $entity):
			$location = $this->getDoctrine()->getRepository('ElectionBundle:ElectionLocation')->getVillageMemberName($config ,$entity->getVillage());
			$voteCenter = $this->getDoctrine()->getRepository('ElectionBundle:ElectionLocation')->getMemberVoteCenter($config ,$entity->getVoteCenterName());
			if(!empty($location)){
				$entity->setLocation($location);
				$entity->setVillage($entity->getLocation()->getName());
				$entity->setWard($entity->getLocation()->wardName());
				$entity->setMemberUnion($entity->getLocation()->unionName());
				$entity->setThana($entity->getLocation()->thanaName());
				$entity->setDistrict($entity->getEducationConfig()->getDistrict()->getName());
				if(!empty($voteCenter)){
					$entity->setVoteCenter($voteCenter);
					$entity->setVoteCenterName($entity->getVoteCenter()->getName());
				}
				$entity->setProcess('Approved');
				$em->flush();
			}
		endforeach;
		return $this->redirect($this->generateUrl('education_student'));

	}

	public function generateCardAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$data = explode(',',$request->cookies->get('memberIds'));
		if(is_null($data)) {
			return $this->redirect($this->generateUrl('education_student'));
		}
		$config = $this->getUser()->getGlobalOption()->getEducationConfig();
		$entities = $this->getDoctrine()->getRepository('ElectionBundle:Student')->getBarcodeForPrint($config,$data);

		return $this->render('EducationBundle:Student:idcard.html.twig', array(
			'entities'      => $entities,
			'config'      => $config
		));
	}

/*
	public function itemBarcoder(ElectionConfig $config , Student $entity )
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
