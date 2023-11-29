<?php


namespace Appstore\Bundle\HotelBundle\Controller;


use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\RunAs;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Invoice controller.
 *
 */
class ReportController extends Controller
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

	public function salesOverviewAction()
	{
		$em = $this->getDoctrine()->getManager();
		$data = $_REQUEST;
		$user = $this->getUser();
		$cashOverview = $this->getDoctrine()->getRepository('AccountingBundle:AccountSales')->salesOverview($user,$data);

		$salesPrice = $em->getRepository('HotelBundle:HotelInvoice')->reportSalesOverview($user,$data);
		$purchasePrice = $em->getRepository('HotelBundle:HotelInvoice')->reportSalesItemPurchaseSalesOverview($user,$data);
		return $this->render('HotelBundle:Report:sales/salesOverview.html.twig', array(
			'option'                    => $user->getGlobalOption() ,
			'cashOverview'              => $cashOverview ,
			'salesPrice'                => $salesPrice ,
			'purchasePrice'             => $purchasePrice ,
			'branches'                  => $this->getUser()->getGlobalOption()->getBranches(),
			'searchForm'                => $data ,
		));

	}

	public function salesDetailsAction()
	{
		$em = $this->getDoctrine()->getManager();
		$data = $_REQUEST;
		$user = $this->getUser();
		$entities = $em->getRepository('HotelBundle:HotelInvoice')->salesReport($user,$data);
		$pagination = $this->paginate($entities);
		$salesPurchasePrice = $em->getRepository('HotelBundle:HotelInvoice')->salesPurchasePriceReport($user,$data,$pagination);
		$purchaseSalesPrice = $em->getRepository('HotelBundle:HotelInvoice')->reportSalesItemPurchaseSalesOverview($user,$data);
		$transactionMethods = $em->getRepository('SettingToolBundle:TransactionMethod')->findBy(array('status' => 1), array('name' => 'ASC'));
		$cashOverview = $em->getRepository('HotelBundle:HotelInvoice')->reportSalesOverview($user,$data);
		return $this->render('HotelBundle:Report:sales/sales.html.twig', array(
			'option'                => $user->getGlobalOption() ,
			'entities'              => $pagination ,
			'purchasePrice'         => $salesPurchasePrice ,
			'cashOverview'          => $cashOverview,
			'purchaseSalesPrice'    => $purchaseSalesPrice,
			'transactionMethods'    => $transactionMethods ,
			'branches'              => $this->getUser()->getGlobalOption()->getBranches(),
			'searchForm'            => $data,
		));
	}

	public function salesStockItemAction()
	{
		$em = $this->getDoctrine()->getManager();
		$data = $_REQUEST;
		$user = $this->getUser();
		$purchaseSalesPrice = $em->getRepository('HotelBundle:HotelInvoice')->reportSalesItemPurchaseSalesOverview($user,$data);
		$cashOverview = $em->getRepository('HotelBundle:HotelInvoice')->reportSalesOverview($user,$data);
		$entities = $em->getRepository('HotelBundle:HotelInvoiceParticular')->reportSalesStockItem($user,$data);
		$pagination = $this->paginate($entities);
		$type = $this->getDoctrine()->getRepository('HotelBundle:HotelParticularType')->findBy(array('status'=>1));
		$category = $this->getDoctrine()->getRepository('HotelBundle:Category')->findBy(array('status'=>1));

		return $this->render('HotelBundle:Report:sales/salesStock.html.twig', array(
			'option'  => $user->getGlobalOption() ,
			'entities' => $pagination,
			'cashOverview' => $cashOverview,
			'purchaseSalesItem' => $purchaseSalesPrice,
			'types' => $type,
			'categories' => $category,
			'branches' => $this->getUser()->getGlobalOption()->getBranches(),
			'searchForm' => $data,
		));
	}

	public function customerSalesItemAction()
	{
		$em = $this->getDoctrine()->getManager();
		$data = $_REQUEST;
		$user = $this->getUser();
		$entities = $em->getRepository('HotelBundle:HotelInvoiceParticular')->reportCustomerSalesItem($user,$data);
		$pagination = $this->paginate($entities);
		$type = $this->getDoctrine()->getRepository('HotelBundle:HotelParticularType')->findBy(array('status'=>1));
		$category = $this->getDoctrine()->getRepository('HotelBundle:Category')->findBy(array('status'=>1));

		return $this->render('HotelBundle:Report:sales/customerSalesItem.html.twig', array(
			'option'  => $user->getGlobalOption() ,
			'entities' => $pagination,
			'types' => $type,
			'categories' => $category,
			'branches' => $this->getUser()->getGlobalOption()->getBranches(),
			'searchForm' => $data,
		));
	}


	public function salesUserAction()
	{
		$em = $this->getDoctrine()->getManager();
		$data = $_REQUEST;
		$user = $this->getUser();
		$salesPurchasePrice = $em->getRepository('HotelBundle:MedicineSales')->salesUserPurchasePriceReport($user,$data);
		$entities = $em->getRepository('HotelBundle:MedicineSales')->salesUserReport($user,$data);
		return $this->render('HotelBundle:Report:sales/salesUser.html.twig', array(
			'option'  => $user->getGlobalOption() ,
			'entities'      => $entities ,
			'salesPurchasePrice'      => $salesPurchasePrice ,
			'branches' => $this->getUser()->getGlobalOption()->getBranches(),
			'searchForm'    => $data ,
		));
	}

	public function monthlyUserSalesAction(){

		$em = $this->getDoctrine()->getManager();
		$data = $_REQUEST;
		$user = $this->getUser();
		$config = $user->getGlobalOption()->getHotelConfig();
		$employees = $em->getRepository('DomainUserBundle:DomainUser')->getSalesUser($user->getGlobalOption());
		$entities = $em->getRepository('HotelBundle:MedicineSales')->monthlySales($user,$data);
		$salesAmount = array();
		foreach($entities as $row) {
			$salesAmount[$row['salesBy'].$row['month']] = $row['total'];
		}
		return $this->render('HotelBundle:Report:sales/salesMonthlyUser.html.twig', array(
			'inventory'      => $config ,
			'salesAmount'      => $salesAmount ,
			'employees'      => $employees ,
			'branches' => $this->getUser()->getGlobalOption()->getBranches(),
			'searchForm'    => $data ,
		));

	}


	public function purchaseOverviewAction(){

		$em = $this->getDoctrine()->getManager();
		$data = $_REQUEST;
		$user = $this->getUser();

		$purchaseCashOverview   = $em->getRepository('HotelBundle:HotelPurchase')->reportPurchaseOverview($user,$data);
		$transactionCash        = $em->getRepository('HotelBundle:HotelPurchase')->reportPurchaseTransactionOverview($user,$data);
		$purchaseMode           = $em->getRepository('HotelBundle:HotelPurchase')->reportPurchaseModeOverview($user,$data);

		return $this->render('HotelBundle:Report:purchase/overview.html.twig', array(
			'option'                            => $user->getGlobalOption() ,
			'purchaseCashOverview'              => $purchaseCashOverview ,
			'transactionCash'                   => $transactionCash ,
			'purchaseMode'                      => $purchaseMode ,
			'stockMode'                         => '' ,
			'searchForm'                        => $data,

		));
	}

	public function purchaseVendorAction()
	{
		$em = $this->getDoctrine()->getManager();
		$data = $_REQUEST;
		$user = $this->getUser();
		$entities = $em->getRepository('HotelBundle:HotelPurchase')->purchaseVendorReport($user,$data);
		return $this->render('HotelBundle:Report:purchase/purchaseVendor.html.twig', array(
			'option'                => $user->getGlobalOption() ,
			'entities'              => $entities ,
			'searchForm'            => $data,
		));
	}


	public function purchaseVendorDetailsAction()
	{
		$data = $_REQUEST;
		$globalOption = $this->getUser()->getGlobalOption();
		$entities = $this->getDoctrine()->getRepository('AccountingBundle:AccountPurchase')->findWithSearch($globalOption,$data);
		$pagination = $this->paginate($entities);
		$overview = $this->getDoctrine()->getRepository('AccountingBundle:AccountPurchase')->accountPurchaseOverview($globalOption,$data);
		$accountHead = $this->getDoctrine()->getRepository('AccountingBundle:AccountHead')->getChildrenAccountHead($parent =array(5));
		$transactionMethods = $this->getDoctrine()->getRepository('SettingToolBundle:TransactionMethod')->findBy(array('status'=>1),array('name'=>'asc'));
		return $this->render('HotelBundle:Report:purchase/purchase.html.twig', array(
			'globalOption' => $globalOption,
			'entities' => $pagination,
			'accountHead' => $accountHead,
			'transactionMethods' => $transactionMethods,
			'searchForm' => $data,
			'overview' => $overview,
		));
	}



	public function productPurchaseStockSalesAction()
	{

		$em = $this->getDoctrine()->getManager();
		$data = $_REQUEST;
		$user = $this->getUser();
		$config = $user->getGlobalOption()->getMedicineConfig();
		$entities = $em->getRepository('HotelBundle:HotelPurchase')->productPurchaseStockSalesReport($user,$data);
		$pagination = $this->paginate($entities);
		$racks = $this->getDoctrine()->getRepository('HotelBundle:MedicineParticular')->findBy(array('medicineConfig'=> $config,'particularType'=>'1'));
		$modeFor = $this->getDoctrine()->getRepository('HotelBundle:MedicineParticularType')->findBy(array('modeFor'=>'brand'));
		return $this->render('HotelBundle:Report:purchase/purchaseVendorStockSales.html.twig', array(
			'option'                => $user->getGlobalOption() ,
			'pagination'            => $pagination ,
			'racks'                 => $racks,
			'modeFor'               => $modeFor,
			'searchForm'            => $data,
		));
	}


	public function productPurchaseStockSalesPriceAction()
	{

		$em = $this->getDoctrine()->getManager();
		$data = $_REQUEST;
		$user = $this->getUser();
		$config = $user->getGlobalOption()->getMedicineConfig();
		$entities = $em->getRepository('HotelBundle:HotelPurchase')->productPurchaseStockSalesPriceReport($user,$data);
		$pagination = $this->paginate($entities);
		$racks = $this->getDoctrine()->getRepository('HotelBundle:MedicineParticular')->findBy(array('medicineConfig'=> $config,'particularType'=>'1'));
		$modeFor = $this->getDoctrine()->getRepository('HotelBundle:MedicineParticularType')->findBy(array('modeFor'=>'brand'));
		return $this->render('HotelBundle:Report:purchase/purchaseVendorStockSales.html.twig', array(
			'option'                => $user->getGlobalOption() ,
			'pagination'              => $pagination ,
			'racks' => $racks,
			'modeFor' => $modeFor,
			'searchForm'            => $data,
		));
	}


	public function purchaseBrandStockSalesAction()
	{

		$em = $this->getDoctrine()->getManager();
		$data = $_REQUEST;
		$user = $this->getUser();
		$entities = $em->getRepository('HotelBundle:HotelPurchase')->purchaseVendorStockReport($user,$data);
		return $this->render('HotelBundle:Report:purchase/purchaseVendorStockSales.html.twig', array(
			'option'                => $user->getGlobalOption() ,
			'entities'              => $entities ,
			'searchForm'            => $data,
		));
	}


	public function purchaseBrandAction()
	{
		$em = $this->getDoctrine()->getManager();
		$data = $_REQUEST;
		$user = $this->getUser();
		$entities = $em->getRepository('HotelBundle:HotelPurchase')->purchaseVendorReport($user,$data);
		return $this->render('HotelBundle:Report:purchase/purchaseBrand.html.twig', array(
			'option'                => $user->getGlobalOption() ,
			'entities'              => $entities ,
			'searchForm'            => $data,
		));
	}

	public function purchaseDetailsAction()
	{

		$em = $this->getDoctrine()->getManager();
		$data = $_REQUEST;
		$user = $this->getUser();
		$entities = $em->getRepository('HotelBundle:HotelPurchase')->purchaseReport($user,$data);
		$pagination = $this->paginate($entities);
		$cashOverview = $em->getRepository('HotelBundle:HotelPurchase')->reportPurchaseOverview($user,$data);
		$transactionMethods = $em->getRepository('SettingToolBundle:TransactionMethod')->findBy(array('status' => 1), array('name' => 'ASC'));
		return $this->render('HotelBundle:Report:purchase/purchase.html.twig', array(
			'option'                => $user->getGlobalOption() ,
			'entities'              => $pagination ,
			'cashOverview'          => $cashOverview,
			'transactionMethods'    => $transactionMethods ,
			'searchForm'            => $data,
		));
	}

}
