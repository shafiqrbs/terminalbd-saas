<?php
namespace Appstore\Bundle\HotelBundle\Controller;

use Appstore\Bundle\HotelBundle\Entity\HotelParticular;
use Appstore\Bundle\HotelBundle\Entity\HotelProductionElement;
use Appstore\Bundle\HotelBundle\Entity\Particular;
use Appstore\Bundle\HotelBundle\Form\ParticularType;
use Appstore\Bundle\HotelBundle\Form\ProductionType;
use Appstore\Bundle\HotelBundle\Form\ProductType;
use Appstore\Bundle\HotelBundle\Form\StockType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


/**
 * ProductionController controller.
 *
 */
class ProductionController extends Controller
{


    /**
     * Creates a form to edit a Particular entity.
     *
     * @param Particular $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createProductionCostingForm(HotelParticular $entity)
    {

        $form = $this->createForm(new ProductionType(), $entity, array(
            'action' => $this->generateUrl('business_production_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array(
                'class' => 'form-horizontal',
                'novalidate' => 'novalidate',
                'id' => 'production',
            )
        ));
        return $form;
    }

    public function productionRoomAction(HotelParticular  $entity)
    {


    	$em = $this->getDoctrine()->getManager();
        $config =$this->getUser()->getGlobalOption()->getHotelConfig()->getId();
        if($entity->getProductType() != 'production' and $entity->getHotelConfig()->getId() != $config){
            return $this->redirect($this->generateUrl('business_stock'));
        }
        $config = $this->getUser()->getGlobalOption()->getHotelConfig();
        $editForm = $this->createProductionCostingForm($entity);
        $particulars = $em->getRepository('HotelBundle:HotelParticular')->getFindWithParticular($config,$type = array('consumable','stock'));
        return $this->render('HotelBundle:Production:production.html.twig', array(
            'entity'      => $entity,
            'particulars' => $particulars,
            'form'   => $editForm->createView(),
        ));
    }

	public function productionFoodAction(HotelParticular  $entity)
	{


		$em = $this->getDoctrine()->getManager();
		$config =$this->getUser()->getGlobalOption()->getHotelConfig()->getId();
		if($entity->getProductType() != 'production' and $entity->getHotelConfig()->getId() != $config){
			return $this->redirect($this->generateUrl('business_stock'));
		}
		$config = $this->getUser()->getGlobalOption()->getHotelConfig();
		$editForm = $this->createProductionCostingForm($entity);
		$particulars = $em->getRepository('HotelBundle:HotelParticular')->getFindWithParticular($config,$type = array('consumable','stock'));
		return $this->render('HotelBundle:Production:production.html.twig', array(
			'entity'      => $entity,
			'particulars' => $particulars,
			'form'   => $editForm->createView(),
		));
	}

    public function productionPackageAction(HotelParticular  $entity)
	{


		$em = $this->getDoctrine()->getManager();
		$config =$this->getUser()->getGlobalOption()->getHotelConfig()->getId();
		if($entity->getProductType() != 'production' and $entity->getHotelConfig()->getId() != $config){
			return $this->redirect($this->generateUrl('business_stock'));
		}
		$config = $this->getUser()->getGlobalOption()->getHotelConfig();
		$editForm = $this->createProductionCostingForm($entity);
		$particulars = $em->getRepository('HotelBundle:HotelParticular')->getFindWithParticular($config,$type = array('consumable','stock'));
		return $this->render('HotelBundle:Production:production.html.twig', array(
			'entity'      => $entity,
			'particulars' => $particulars,
			'form'   => $editForm->createView(),
		));
	}

    public function preProductionAction(){

    }

    public function productionUpdateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('HotelBundle:HotelParticular')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Particular entity.');
        }
        $editForm = $this->createProductionCostingForm($entity);
        $editForm->handleRequest($request);
        $data = $request->request->all();
        if ($editForm->isValid()) {
            $entity->setSalesPrice($data['productionSalesPrice']);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been updated successfully"
            );
            //$this->getDoctrine()->getRepository('HotelBundle:HotelParticular')->updateSalesPrice($entity);
            return $this->redirect($this->generateUrl('business_stock'));
        }

        return $this->render('HotelBundle:Production:production.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }


    public function particularSearchAction(HotelParticular $particular)
    {
        return new Response(json_encode(array('particularId'=> $particular->getId() ,'price'=> $particular->getSalesPrice() , 'purchasePrice'=> $particular->getPurchasePrice(), 'quantity'=> 1 , 'minimumPrice'=> '')));
    }

    public function addParticularAction(Request $request, HotelParticular $particular)
    {
        $em = $this->getDoctrine()->getManager();
        $particularId = $request->request->get('particularId');
        $quantity = $request->request->get('quantity');
        $price = $request->request->get('price');
        $data = array('particularId' => $particularId , 'quantity' => $quantity,'price' => $price );
        $this->getDoctrine()->getRepository('HotelBundle:HotelProductionElement')->insertProductionElement($particular, $data);
	    $particularReturn = $this->getDoctrine()->getRepository('HotelBundle:HotelParticular')->updateSalesPrice($particular);
        $production = $this->getDoctrine()->getRepository('HotelBundle:HotelProductionElement')->particularProductionElements($particular);
        return new Response(json_encode(array('subTotal' => $particularReturn->getSalesPrice(),'purchasePrice' => $particular->getPurchasePrice(),'particulars' => $production)));
        exit;
    }

    public function deleteAction(HotelParticular $particular, HotelProductionElement $element){

        $em = $this->getDoctrine()->getManager();
        if (!$element) {
            throw $this->createNotFoundException('Unable to find SalesItem entity.');
        }
        $em->remove($element);
        $em->flush();
        $this->getDoctrine()->getRepository('HotelBundle:HotelParticular')->updateSalesPrice($particular);
        return new Response(json_encode(array('subTotal' => $particular->getSalesPrice(),'purchasePrice' => $particular->getPurchasePrice(),'particulars' => '')));
        exit;


    }

    public function sortingAction()
    {
        $entity = new Particular();
        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getHotelConfig();
        $pagination = $em->getRepository('HotelBundle:Particular')->productSortingList($config,array('product','stockable'));
        $editForm = $this->createCreateForm($entity);
        return $this->render('HotelBundle:Product:sorting.html.twig', array(
            'pagination' => $pagination,
            'entity' => $entity,
            'form'   => $editForm->createView(),
        ));

    }

    public function sortedAction(Request $request)
    {
        $data = $request ->request->get('item');
        $this->getDoctrine()->getRepository('HotelBundle:Particular')->setProductSorting($data);
        exit;
    }

}
