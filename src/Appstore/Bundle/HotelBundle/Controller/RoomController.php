<?php

namespace Appstore\Bundle\HotelBundle\Controller;
use Appstore\Bundle\HospitalBundle\Entity\Particular;
use Appstore\Bundle\HotelBundle\Entity\HotelConfig;
use Appstore\Bundle\HotelBundle\Entity\HotelParticular;
use Appstore\Bundle\HotelBundle\Entity\HotelParticularMeta;
use Appstore\Bundle\HotelBundle\Entity\HotelRoomGallery;
use Appstore\Bundle\HotelBundle\Form\RoomType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Response;

/**
 * StockController controller.
 *
 */
class RoomController extends Controller
{

    public function paginate($entities)
    {

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $entities,
            $this->get('request')->query->get('page', 1)/*page number*/,
            25  /*limit per page*/
        );
        $pagination->setTemplate('SettingToolBundle:Widget:pagination.html.twig');
        return $pagination;
    }

    /**
     * Lists all Particular entities.
     * @Secure(roles="ROLE_HOTEL_STOCK,ROLE_DOMAIN");
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();
        $data = $_REQUEST;
        $config = $this->getUser()->getGlobalOption()->getHotelConfig();
        $entities = $this->getDoctrine()->getRepository('HotelBundle:HotelParticular')->findWithSearch($config,'room',$data);
        $pagination = $this->paginate($entities);
	    $options = $em->getRepository('HotelBundle:HotelOption')->findBy(array('hotelConfig' => $config,'status'=>1),array('hotelParticularType' =>'asc' ));
	    $type = $this->getDoctrine()->getRepository('HotelBundle:HotelParticularType')->findBy(array('status'=>1,'parent'=>'room'));
        return $this->render('HotelBundle:Room:index.html.twig', array(
            'entities' => $pagination,
            'options' => $options,
            'types' => $type,
            'config' => $config,
            'searchForm' => $data,
        ));

    }

    /**
     * Displays a form to create a new Vendor entity.
     * @Secure(roles="ROLE_HOTEL_STOCK,ROLE_DOMAIN");
     */

    public function newAction()
    {
        $entity = new HotelParticular();
	    $config = $this->getUser()->getGlobalOption()->getHotelConfig();
        $form   = $this->createCreateForm($entity);
        return $this->render('HotelBundle:Room:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }


    /**
     * Creates a new HotelParticular entity.
     *
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /* @var $config HotelConfig */

        $config = $this->getUser()->getGlobalOption()->getHotelConfig();
        $entity = new HotelParticular();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
	    $name = $entity->getName();
	    $checkName = $this->getDoctrine()->getRepository( 'HotelBundle:HotelParticular' )->findOneBy( array(
		    'hotelConfig' => $config,
		    'name'           => $name
	    ) );
        if ($form->isValid() and empty($checkName)) {
            $em = $this->getDoctrine()->getManager();
            $entity->setHotelConfig($config);
            $entity->upload();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been added successfully"
            );
            return $this->redirect($this->generateUrl('hotel_room_new'));
        }
	    $stockFormat = $config->getStockFormat();
        $this->get('session')->getFlashBag()->add(
            'error',"Required field does not input"
        );
        return $this->render('HotelBundle:Room:new.html.twig', array(
            'entity'        => $entity,
            'stockFormat'   => $stockFormat,
            'form'          => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Particular entity.
     *
     * @param Particular $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(HotelParticular $entity)
    {

        $option = $this->getUser()->getGlobalOption();
        $form = $this->createForm(new RoomType($option), $entity, array(
            'action' => $this->generateUrl('hotel_room_create', array('id' => $entity->getId())),
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
     * @Secure(roles="ROLE_HOTEL_STOCK,ROLE_DOMAIN");
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('HotelBundle:HotelParticular')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Particular entity.');
        }
	    $config = $this->getUser()->getGlobalOption()->getHotelConfig();
	    $editForm = $this->createEditForm($entity);
	    return $this->render("HotelBundle:Room:edit.html.twig", array(
            'entity'            => $entity,
            'form'              => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Particular entity.
     *
     * @param Particular $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(HotelParticular $entity)
    {
        $option = $this->getUser()->getGlobalOption();
        $form = $this->createForm(new RoomType($option), $entity, array(
            'action' => $this->generateUrl('hotel_room_update', array('id' => $entity->getId())),
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
        $entity = $em->getRepository('HotelBundle:HotelParticular')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Particular entity.');
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
	    $data = $request->request->all();

	    if ($editForm->isValid()) {
            if($entity->upload() && !empty($entity->getFile())){
                $entity->removeUpload();
            }
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been updated successfully"
            );
	        $this->getDoctrine()->getRepository('HotelBundle:HotelParticular')->insertItemKeyValue($entity,$data);
	        $this->getDoctrine()->getRepository('HotelBundle:HotelRoomGallery')->insertProductGallery($entity,$data);
	        return $this->redirect($this->generateUrl('hotel_room'));
        }
        return $this->render('HotelBundle:Room:new.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }


	public function uploadItemImageAction(HotelParticular $item)
	{
		$entity = new HotelRoomGallery();
		$option = $this->getUser()->getGlobalOption();
		$entity ->upload($option->getId(),$item->getId());
	}

	public function keyValueDeleteAction(HotelParticularMeta $itemKeyValue)
	{
		if($itemKeyValue){
			$em = $this->getDoctrine()->getManager();
			$em->remove($itemKeyValue);
			$em->flush();
			return new Response('success');
		}else{
			return new Response('failed');
		}
	}

	public function keyValueSortedAction(Request $request,Particular $particular)
	{
		$data = $request ->request->get('menuItem');
		$this->getDoctrine()->getRepository('HotelBundle:HotelParticular')->setDivOrdering($data);
		exit;

	}





	/**
     * Deletes a Particular entity.
     * @Secure(roles="ROLE_HOTEL_STOCK,ROLE_DOMAIN");
     */

    public function deleteAction(HotelParticular $entity)
    {
        $em = $this->getDoctrine()->getManager();

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
        return $this->redirect($this->generateUrl('hotel_room'));
    }

   
    /**
     * Status a Page entity.
     *
     */

    public function statusAction(HotelParticular $entity)
    {

        $em = $this->getDoctrine()->getManager();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find District entity.');
        }
        $status = $entity->getStatus();
        if($status == 1){
            $entity->setStatus(0);
        } else{
            $entity->setStatus(1);
        }
        $em->flush();
        $this->get('session')->getFlashBag()->add(
            'success',"Status has been changed successfully"
        );
        return $this->redirect($this->generateUrl('hotel_room'));
    }

    public function inlineUpdateAction(Request $request)
    {
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('HotelBundle:HotelParticular')->find($data['pk']);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find particular entity.');
        }
        $setField = 'set'.$data['name'];
        $entity->$setField(abs($data['value']));
        $em->flush();
        exit;

    }

    public function production(HotelParticular $particular)
    {

    }

    public function transfer(HotelParticular $particular)
    {

    }

	public function autoSearchAction(Request $request)
	{
		$item = $_REQUEST['q'];
		if ($item) {
			$inventory = $this->getUser()->getGlobalOption()->getHotelConfig();
			$item = $this->getDoctrine()->getRepository('HotelBundle:HotelParticular')->searchAutoComplete($inventory,$item);
		}
		return new JsonResponse($item);
	}

	public function searchNameAction($stock)
	{
		return new JsonResponse(array(
			'id'=> $stock,
			'text'=> $stock
		));
	}

}
