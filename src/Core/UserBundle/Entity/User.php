<?php

namespace Core\UserBundle\Entity;

use Appstore\Bundle\AccountingBundle\Entity\AccountCash;
use Appstore\Bundle\AccountingBundle\Entity\AccountHead;
use Appstore\Bundle\AccountingBundle\Entity\AccountSalesAdjustment;
use Appstore\Bundle\BusinessBundle\Entity\BusinessAndroidProcess;
use Appstore\Bundle\DmsBundle\Entity\DmsParticular;
use Appstore\Bundle\DoctorPrescriptionBundle\Entity\DpsParticular;
use Appstore\Bundle\DomainUserBundle\Entity\Branches;
use Appstore\Bundle\DomainUserBundle\Entity\DomainUser;
use Appstore\Bundle\EcommerceBundle\Entity\Order;
use Appstore\Bundle\EcommerceBundle\Entity\OrderPayment;
use Appstore\Bundle\EcommerceBundle\Entity\PreOrder;
use Appstore\Bundle\EcommerceBundle\Entity\PreOrderPayment;
use Appstore\Bundle\HospitalBundle\Entity\HmsInvoiceReturn;
use Appstore\Bundle\HospitalBundle\Entity\Particular;
use Appstore\Bundle\HotelBundle\Entity\HotelTemporaryInvoice;
use Appstore\Bundle\HumanResourceBundle\Entity\DailyAttendance;
use Appstore\Bundle\HumanResourceBundle\Entity\EmployeePayroll;
use Appstore\Bundle\InventoryBundle\Entity\BranchInvoice;
use Appstore\Bundle\InventoryBundle\Entity\Damage;
use Appstore\Bundle\InventoryBundle\Entity\Delivery;
use Appstore\Bundle\InventoryBundle\Entity\DeliveryReturn;
use Appstore\Bundle\InventoryBundle\Entity\ExcelImporter;
use Appstore\Bundle\InventoryBundle\Entity\ServiceSales;
use Appstore\Bundle\InventoryBundle\Entity\StockItem;
use Appstore\Bundle\MedicineBundle\Entity\MedicinePurchase;
use Appstore\Bundle\MedicineBundle\Entity\MedicineReverse;
use Appstore\Bundle\MedicineBundle\Entity\MedicineSalesTemporary;
use Appstore\Bundle\RestaurantBundle\Entity\RestaurantTemporary;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="fos_user")
 * @UniqueEntity(fields="username",message="User name already existing,Please try again.")
 * @UniqueEntity(fields="email",message="Email address already existing,Please try again.")
 * @ORM\Entity(repositoryClass="Core\UserBundle\Entity\Repository\UserRepository")
 */
class User extends BaseUser
{


	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $username;

	protected $role;

	protected $enabled = true;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="isDelete", type="boolean", nullable=true)
	 */
	private $isDelete = 0;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="domainOwner", type="smallint", nullable=true)
	 */
	private $domainOwner = 0;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="userGroup", type="string", length = 30, nullable=true)
	 */
	private $userGroup = "user";

	/**
	 * @var string
	 *
	 * @ORM\Column(name="appPassword", type="string", length = 30, nullable=true)
	 */
	private $appPassword = "@123456";

	/**
	 * @var array
	 *
	 * @ORM\Column(name="appRoles", type="array", nullable=true)
	 */
	private $appRoles;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="agent", type="boolean", nullable=true)
	 */
	private $agent = false;


	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $avatar;

	/**
	 * @ORM\ManyToMany(targetEntity="Group", inversedBy="users")
	 * @ORM\JoinTable(name="user_user_group",
	 *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
	 * )
	 */
	protected $groups;


	/**
	 * @ORM\ManyToOne(targetEntity="Setting\Bundle\ToolBundle\Entity\GlobalOption", inversedBy="users" )
	 *  * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="globalOption_id", referencedColumnName="id")
	 * })
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 **/

	protected $globalOption;

	/**
	 * @ORM\OneToMany(targetEntity="Setting\Bundle\ToolBundle\Entity\GlobalOption", mappedBy="agent" )
	 *  * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="globalOptionAgent_id", referencedColumnName="id")
	 * })
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 **/
	protected $globalOptionAgents;

    /**
     * @ORM\OneToOne(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountHead", mappedBy="employee" )
     **/
    private  $accountHead;



	/**
     * @ORM\OneToOne(targetEntity="Appstore\Bundle\HumanResourceBundle\Entity\EmployeePayroll", mappedBy="employee" , cascade={"persist", "remove"})
     **/
    private  $employeePayroll;


	/**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\HumanResourceBundle\Entity\EmployeePayroll", mappedBy="approvedBy" )
     **/
    private  $payrollApproved;


	/**
	 * This part for system customer payment
	 */

	/**
	 * @ORM\OneToMany(targetEntity="Setting\Bundle\ContentBundle\Entity\Page", mappedBy="user" , cascade={"persist", "remove"} )
	 */
	protected $pages;


	/**
	 * @ORM\OneToMany(targetEntity="Setting\Bundle\ContentBundle\Entity\HomeSlider", mappedBy="user" , cascade={"persist", "remove"})
	 */
	protected $homeSliders;


	/**
	 * @ORM\OneToMany(targetEntity="Product\Bundle\ProductBundle\Entity\Product", mappedBy="user" , cascade={"persist", "remove"})
	 */
	protected $products;


	/**
	 * @ORM\OneToOne(targetEntity="Setting\Bundle\ToolBundle\Entity\SiteSetting", mappedBy="user" , cascade={"persist", "remove"})
	 **/

	private $siteSetting;
	/**
	 * @ORM\OneToOne(targetEntity="Setting\Bundle\ContentBundle\Entity\HomePage", mappedBy="user" , cascade={"persist", "remove"})
	 **/

	private $homePage;

	/**
	 * @ORM\OneToOne(targetEntity="Setting\Bundle\ContentBundle\Entity\ContactPage", mappedBy="user" , cascade={"persist", "remove"})
	 **/

	private $contactPage;

	/**
	 * @ORM\OneToMany(targetEntity="Setting\Bundle\ContentBundle\Entity\SyndicateContent", mappedBy="user" , cascade={"persist", "remove"})
	 */
	protected $syndicateContents;

	/**
	 * @ORM\OneToOne(targetEntity="Profile", mappedBy="user", cascade={"persist", "remove"})
	 *
	 */
	protected $profile;

	/**
	 * @ORM\OneToOne(targetEntity="Product\Bundle\ProductBundle\Entity\CategoryGrouping", mappedBy="user", cascade={"persist", "remove"})
	 *
	 */
	protected $categoryGrouping;


	/**
	 * @ORM\OneToMany(targetEntity="Setting\Bundle\ContentBundle\Entity\ContactMessage", mappedBy="user" , cascade={"persist", "remove"})
	 */
	protected $contactMessages;


	/**
	 * @ORM\OneToMany(targetEntity="Setting\Bundle\ContentBundle\Entity\EmailBox", mappedBy="user" , cascade={"persist", "remove"})
	 **/

	protected $emailBox;

	/**
	 * @ORM\OneToOne(targetEntity="Appstore\Bundle\DomainUserBundle\Entity\DomainUser", mappedBy="user" , cascade={"persist", "remove"})
	 **/
	protected $domainUser;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\DomainUserBundle\Entity\Notepad", mappedBy="user" , cascade={"persist", "remove"})
	 **/
	protected $notepad;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\DomainUserBundle\Entity\CustomerInbox", mappedBy="replyUser" , cascade={"persist", "remove"})
	 **/

	protected $customerInbox;


	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\DomainUserBundle\Entity\UserInbox", mappedBy="user" , cascade={"persist", "remove"})
	 **/
	protected $userInbox;


	/**
	 * @ORM\OneToOne(targetEntity="Appstore\Bundle\DomainUserBundle\Entity\Branches", mappedBy="branchManager"  , cascade={"persist", "remove"})
	 */
	protected $branches;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Distribution", mappedBy="createdBy" , cascade={"persist", "remove"})
	 **/
	protected $distributionCreate;


	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Distribution", mappedBy="checkedBy" , cascade={"persist", "remove"})
	 **/
	protected $distributionCheckedBy;


	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Distribution", mappedBy="approvedBy" , cascade={"persist", "remove"})
	 **/
	protected $distributionApprovedBy;



	/* ----------------------------------inventory------------------*/

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\InventoryBundle\Entity\ExcelImporter", mappedBy="createdBy" , cascade={"persist", "remove"})
	 */
	protected $excelImporters;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\InventoryBundle\Entity\InventoryAndroidProcess", mappedBy="createdBy" , cascade={"persist", "remove"})
	 */
	protected $inventoryAndroidProcess;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\InventoryBundle\Entity\Reverse", mappedBy="createdBy" , cascade={"persist", "remove"})
	 */
	protected $reverse;


	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\InventoryBundle\Entity\StockItem", mappedBy="createdBy"  , cascade={"persist", "remove"})
	 */
	protected $stockItems;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\InventoryBundle\Entity\Purchase", mappedBy="createdBy" , cascade={"persist", "remove"})
	 */
	protected $purchase;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\InventoryBundle\Entity\Purchase", mappedBy="approvedBy" , cascade={"persist", "remove"} )
	 */
	protected $purchasesApprovedBy;


	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\InventoryBundle\Entity\PurchaseReturn", mappedBy="createdBy" , cascade={"persist", "remove"} )
	 */
	protected $purchaseReturn;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\InventoryBundle\Entity\PurchaseReturn", mappedBy="approvedBy" , cascade={"persist", "remove"} )
	 */
	protected $purchasesReturnApprovedBy;


	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\InventoryBundle\Entity\Sales", mappedBy="salesBy" , cascade={"persist", "remove"} )
	 */
	protected $salesUser;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\InventoryBundle\Entity\Sales", mappedBy="createdBy" , cascade={"persist", "remove"} )
	 */
	protected $sales;


	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\InventoryBundle\Entity\Sales", mappedBy="approvedBy" , cascade={"persist", "remove"} )
	 */
	protected $salesApprovedBy;


	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\InventoryBundle\Entity\SalesReturn", mappedBy="createdBy" , cascade={"persist", "remove"})
	 */
	protected $salesReturn;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\InventoryBundle\Entity\SalesImport", mappedBy="createdBy" , cascade={"persist", "remove"} )
	 */
	protected $salesImport;


	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\InventoryBundle\Entity\Damage", mappedBy="createdBy" , cascade={"persist", "remove"} )
	 */
	protected $damage;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\InventoryBundle\Entity\Delivery", mappedBy="createdBy" , cascade={"persist", "remove"} )
	 */
	protected $delivery;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\InventoryBundle\Entity\Delivery", mappedBy="approvedBy" , cascade={"persist", "remove"} )
	 */
	protected $deliveryApprovedBy;


	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\InventoryBundle\Entity\Damage", mappedBy="approvedBy" , cascade={"persist", "remove"} )
	 */
	protected $damageApprovedBy;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\InventoryBundle\Entity\BranchInvoice", mappedBy="createdBy" , cascade={"persist", "remove"} )
	 */
	protected $branchInvoice;


	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\InventoryBundle\Entity\BranchInvoice", mappedBy="approvedBy" , cascade={"persist", "remove"} )
	 */
	protected $branchInvoiceApprovedBy;


	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\InventoryBundle\Entity\DeliveryReturn", mappedBy="createdBy" , cascade={"persist", "remove"} )
	 */
	protected $deliveryReturn;


	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\InventoryBundle\Entity\DeliveryReturn", mappedBy="approvedBy" , cascade={"persist", "remove"} )
	 */
	protected $deliveryReturnApprovedBy;


	/* ----------------------------------Accounting------------------*/


	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountCash", mappedBy="toUser" , cascade={"persist", "remove"} )
	 */
	protected $accountCashes;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountJournal", mappedBy="createdBy" , cascade={"persist", "remove"} )
	 */
	protected $accountJournal;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountJournal", mappedBy="toUser" , cascade={"persist", "remove"} )
	 */
	protected $accountJournalToUser;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountJournal", mappedBy="approvedBy" , cascade={"persist", "remove"} )
	 */
	protected $accountJournalApprove;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountBalanceTransfer", mappedBy="createdBy" , cascade={"persist", "remove"} )
	 */
	protected $balanceTransfer;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountBalanceTransfer", mappedBy="approvedBy" , cascade={"persist", "remove"} )
	 */
	protected $balanceTransferApprove;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountPurchase", mappedBy="createdBy" , cascade={"persist", "remove"} )
	 */
	protected $accountPurchases;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountPurchase", mappedBy="approvedBy" , cascade={"persist", "remove"} )
	 */
	protected $purchaseApprove;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountPurchase", mappedBy="toUser" , cascade={"persist", "remove"} )
	 */
	protected $purchasesToUser;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountSales", mappedBy="createdBy" , cascade={"persist", "remove"} )
	 */
	protected $accountSales;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountSales", mappedBy="approvedBy" , cascade={"persist", "remove"} )
	 */
	protected $salesApprove;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountSalesAdjustment", mappedBy="createdBy" , cascade={"persist", "remove"} )
	 */
	protected $salesAdjustment;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountSalesAdjustment", mappedBy="approvedBy" , cascade={"persist", "remove"} )
	 */
	protected $salesAdjustmentApprove;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountPurchaseCommission", mappedBy="createdBy" , cascade={"persist", "remove"} )
	 */
	protected $purchaseCommission;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountPurchaseCommission", mappedBy="approvedBy" , cascade={"persist", "remove"} )
	 */
	protected $purchaseCommissionApprove;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\PettyCash", mappedBy="createdBy" , cascade={"persist", "remove"} )
	 */
	protected $pettyCash;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\PettyCash", mappedBy="toUser" , cascade={"persist", "remove"} )
	 */
	protected $pettyCashToUser;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\PettyCash", mappedBy="approvedBy" , cascade={"persist", "remove"} )
	 */
	protected $pettyCashApprove;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\Expenditure", mappedBy="createdBy" , cascade={"persist", "remove"} )
	 */
	protected $expenditure;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\Expenditure", mappedBy="toUser" , cascade={"persist", "remove"} )
	 */
	protected $expenditureToUser;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\Expenditure", mappedBy="approvedBy" , cascade={"persist", "remove"} )
	 */
	protected $expenditureApprove;


	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\PaymentSalary", mappedBy="createdBy" , cascade={"persist", "remove"} )
	 * @ORM\OrderBy({"updated" = "DESC"})
	 */
	protected $paymentSalary;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\PaymentSalary", mappedBy="user" , cascade={"persist", "remove"} )
	 * @ORM\OrderBy({"updated" = "DESC"})
	 */
	protected $paymentSalaries;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\PaymentSalary", mappedBy="approvedBy" , cascade={"persist", "remove"} )
	 */
	protected $paymentSalaryApprove;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\SalarySetting", mappedBy="user" , cascade={"persist", "remove"} )
	 */
	protected $employeeSalaries;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\SalarySetting", mappedBy="createdBy" , cascade={"persist", "remove"} )
	 */
	protected $salarySetting;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\SalarySetting", mappedBy="approvedBy" , cascade={"persist", "remove"} )
	 */
	protected $salarySettingApproved;


	/*------------------------------------------------ Domain ---------------------------------*/


	/**
	 * @ORM\OneToMany(targetEntity="Setting\Bundle\ToolBundle\Entity\InvoiceSmsEmail", mappedBy="createdBy" , cascade={"persist", "remove"} )
	 */
	protected $invoiceSmsEmail;

	/**
	 * @ORM\OneToMany(targetEntity="Setting\Bundle\ToolBundle\Entity\InvoiceSmsEmail", mappedBy="receivedBy" , cascade={"persist", "remove"} )
	 */
	protected $invoiceSmsEmailReceivedBy;


	/**
	 * @ORM\OneToMany(targetEntity="Setting\Bundle\ToolBundle\Entity\InvoiceModule", mappedBy="createdBy" , cascade={"persist", "remove"} )
	 */
	protected $invoiceModule;

	/**
	 * @ORM\OneToMany(targetEntity="Setting\Bundle\ToolBundle\Entity\InvoiceModule", mappedBy="paymentBy" , cascade={"persist", "remove"} )
	 */
	protected $invoiceModulePaymentBy;

	/**
	 * @ORM\OneToMany(targetEntity="Setting\Bundle\ToolBundle\Entity\InvoiceModule", mappedBy="receivedBy" , cascade={"persist", "remove"} )
	 */
	protected $invoiceModuleReceivedBy;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\HumanResourceBundle\Entity\EmployeeLeave", mappedBy="employee" , cascade={"persist", "remove"} )
	 */
	protected $employeeLeave;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\HumanResourceBundle\Entity\Attendance", mappedBy="employee" , cascade={"persist", "remove"} )
	 */
	protected $attendance;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\HumanResourceBundle\Entity\EmployeeLeave", mappedBy="approvedBy" , cascade={"persist", "remove"} )
	 */
	protected $employeeLeaveApprove;


	/**
	 *  =========================================== Service Sales System============================================
	 */

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\InventoryBundle\Entity\ServiceSales", mappedBy="createdBy"  )
	 **/
	private  $serviceSales;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\InventoryBundle\Entity\ServiceSales", mappedBy="assignTo"  )
	 **/
	private  $serviceSalesBy;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\InventoryBundle\Entity\ServiceSales", mappedBy="approvedBy"  )
	 **/
	private  $serviceApprovedBy;

	/* ==================================== HMS =========================================**/

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\HospitalBundle\Entity\HmsInvoiceTemporaryParticular", mappedBy="user" , cascade={"persist", "remove"})
	 */
	protected $hmsInvoiceTemporaryParticulars;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\HospitalBundle\Entity\Invoice", mappedBy="deliveredBy" , cascade={"persist", "remove"})
	 */
	protected $hmsInvoiceDeliveredBy;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\HospitalBundle\Entity\Invoice", mappedBy="createdBy" , cascade={"persist", "remove"})
	 */
	protected $hmsInvoiceCreatedBy;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\HospitalBundle\Entity\Invoice", mappedBy="approvedBy" , cascade={"persist", "remove"})
	 */
	protected $hmsInvoiceApprovedBy;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\HospitalBundle\Entity\Invoice", mappedBy="dischargeBy" , cascade={"persist", "remove"})
	 */
	protected $hmsDischargeBy;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\HumanResourceBundle\Entity\DailyAttendance", mappedBy="user" , cascade={"persist", "remove"})
	 */
	protected $userAttendance;

	/*========================= DMS USER =======================================================*/

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsInvoice", mappedBy="createdBy" , cascade={"persist", "remove"})
	 */
	protected $dmsInvoiceCreatedBy;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsInvoice", mappedBy="approvedBy" , cascade={"persist", "remove"})
	 */
	protected $dmsInvoiceApprovedBy;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsParticular", mappedBy="assignOperator" , cascade={"persist", "remove"})
	 */
	protected $dmsParticularOperator;

	/**
	 * @ORM\OneToOne(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsParticular", mappedBy="assignDoctor" , cascade={"persist", "remove"})
	 */
	protected $dmsParticularDoctor;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsPurchase", mappedBy="createdBy" , cascade={"persist", "remove"})
	 */
	protected $dmsPurchase;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsPurchase", mappedBy="approvedBy" , cascade={"persist", "remove"})
	 */
	protected $dmsPurchasesApprovedBy;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsDoctorInvoice", mappedBy="createdBy" , cascade={"persist", "remove"})
	 */
	protected $dmsDoctorInvoiceCreatedBy;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsDoctorInvoice", mappedBy="approvedBy" , cascade={"persist", "remove"})
	 */
	protected $dmsDoctorInvoiceApprovedBy;

	/*=========================== DPS Bundle =========================================*/

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\DoctorPrescriptionBundle\Entity\DpsInvoice", mappedBy="assignDoctor" , cascade={"persist", "remove"})
	 */
	protected $assignDoctorInvoices;



	/*=========================== Restaurant Bundle =========================================*/

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\RestaurantBundle\Entity\RestaurantTemporary", mappedBy="user" , cascade={"persist", "remove"})
	 */
	protected $restaurantTemps;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\RestaurantBundle\Entity\RestaurantAndroidProcess", mappedBy="createdBy" , cascade={"persist", "remove"})
	 */
	protected $restaurantAndroidProcess;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\RestaurantBundle\Entity\Invoice", mappedBy="createdBy" , cascade={"persist", "remove"})
	 */
	protected $invoiceCreatedBy;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\RestaurantBundle\Entity\Invoice", mappedBy="salesBy" , cascade={"persist", "remove"})
	 */
	protected $invoiceSalesBy;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\RestaurantBundle\Entity\Invoice", mappedBy="approvedBy" , cascade={"persist", "remove"})
	 */
	protected $invoiceApprovedBy;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\RestaurantBundle\Entity\Purchase", mappedBy="createdBy" , cascade={"persist", "remove"})
	 */
	protected $restaurantPurchasesCreatedBy;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\RestaurantBundle\Entity\Purchase", mappedBy="approvedBy" , cascade={"persist", "remove"})
	 */
	protected $restaurantPurchasesApprovedBy;


	/*=========================== DPS BUNDLE ====================================*/


	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\DoctorPrescriptionBundle\Entity\DpsInvoice", mappedBy="createdBy" , cascade={"persist", "remove"})
	 */
	protected $dpsInvoiceCreatedBy;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\DoctorPrescriptionBundle\Entity\DpsParticular", mappedBy="assignOperator" , cascade={"persist", "remove"})
	 */
	protected $dpsParticularOperator;

	/**
	 * @ORM\OneToOne(targetEntity="Appstore\Bundle\DoctorPrescriptionBundle\Entity\DpsParticular", mappedBy="assignDoctor" , cascade={"persist", "remove"})
	 */
	protected $dpsParticularDoctor;

	/*=========================== MEDICINE BUNDLE ====================================*/


	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\MedicineBundle\Entity\MedicinePurchase", mappedBy="createdBy" , cascade={"persist", "remove"})
	 */
	protected $medicinePurchase;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\MedicineBundle\Entity\MedicinePurchase", mappedBy="approvedBy" , cascade={"persist", "remove"})
	 */
	protected $medicinePurchasesApprovedBy;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\MedicineBundle\Entity\MedicinePurchase", mappedBy="purchaseBy" , cascade={"persist", "remove"})
	 */
	protected $medicinePurchasesBy;


	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\MedicineBundle\Entity\MedicineSales", mappedBy="createdBy" , cascade={"persist", "remove"})
	 */
	protected $medicineSalesCreatedBy;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\MedicineBundle\Entity\MedicineSalesTemporary", mappedBy="user" , cascade={"persist", "remove"})
	 */
	protected $medicineSalesTemporary;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\MedicineBundle\Entity\MedicineSales", mappedBy="approvedBy" , cascade={"persist", "remove"})
	 */
	protected $medicineSalesApprovedBy;


	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\MedicineBundle\Entity\MedicineSales", mappedBy="salesBy" , cascade={"persist", "remove"})
	 */
	protected $medicineSalesBy;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\MedicineBundle\Entity\MedicineReverse", mappedBy="createdBy" , cascade={"persist", "remove"})
	 */
	protected $medicineReverse;


	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\MedicineBundle\Entity\MedicinePurchaseReturn", mappedBy="createdBy" , cascade={"persist", "remove"})
	 */
	protected $medicinePurchaseReturn;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\MedicineBundle\Entity\MedicinePurchaseReturn", mappedBy="approvedBy" , cascade={"persist", "remove"})
	 */
	protected $medicinePurchasesReturnApprovedBy;

    /**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\MedicineBundle\Entity\MedicineStockHouse", mappedBy="createdBy" , cascade={"persist", "remove"})
	 */
	protected $stockHouses;


	/*=========================== BUSINESS BUNDLE ====================================*/


	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\BusinessBundle\Entity\BusinessInvoice", mappedBy="createdBy" , cascade={"persist", "remove"})
	 */
	protected $businessInvoiceCreatedBy;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\BusinessBundle\Entity\BusinessAndroidProcess", mappedBy="createdBy" , cascade={"persist", "remove"})
	 */
	protected $businessAndroidProcess;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\BusinessBundle\Entity\BusinessInvoice", mappedBy="approvedBy" , cascade={"persist", "remove"})
	 */
	protected $businessInvoiceApprovedBy;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\BusinessBundle\Entity\BusinessPurchase", mappedBy="createdBy" , cascade={"persist", "remove"})
	 */
	protected $businessPurchase;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\BusinessBundle\Entity\BusinessPurchase", mappedBy="approvedBy" , cascade={"persist", "remove"})
	 */
	protected $businessPurchasesApprovedBy;

	/*=========================== HOTEL BUNDLE ====================================*/


	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelInvoice", mappedBy="createdBy" , cascade={"persist", "remove"})
	 */
	protected $hotelInvoiceCreatedBy;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelInvoice", mappedBy="approvedBy" , cascade={"persist", "remove"})
	 */
	protected $hotelInvoiceApprovedBy;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelPurchase", mappedBy="createdBy" , cascade={"persist", "remove"})
	 */
	protected $hotelPurchase;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelPurchase", mappedBy="approvedBy" , cascade={"persist", "remove"})
	 */
	protected $hotelPurchasesApprovedBy;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelDamage", mappedBy="createdBy" , cascade={"persist", "remove"})
	 */
	protected $hotelDamage;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelTemporaryInvoice", mappedBy="createdBy" , cascade={"persist", "remove"})
	 */
	protected $hotelTemporary;

	/**
	 *  =========================================== Election System============================================
	 */

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionMember", mappedBy="createdBy"  )
	 **/
	private  $members;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionMember", mappedBy="approvedBy"  )
	 **/
	private  $memberApprove;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionCommittee", mappedBy="createdBy"  )
	 **/
	private  $committeeCreatedBy;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionCommittee", mappedBy="approvedBy"  )
	 **/
	private  $committeeApprovedBy;

    /**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\DomainUserBundle\Entity\Customer", mappedBy="approvedBy"  )
	 **/
	private  $approvedCustomers;


    /**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\DomainUserBundle\Entity\Customer", mappedBy="checkedBy"  )
	 **/
	private  $checkedCustomers;




	public function isGranted($role)
	{
		$domain = $this->getRole();
		if('ROLE_SUPER_ADMIN' === $domain or 'ROLE_DOMAIN' === $domain) {
			return true;
		}elseif(in_array($role, $this->getRoles())){
			return true;
		}
		return false;
	}

    public function hasRoles($role)
    {
        $array = array_intersect($role, $this->getRoles());
        if(!empty($array)){
            return true;
        }
        return false;
    }

	/**
	 * Set username;
	 *
	 * @param string $username
	 * @return User
	 */
	public function setUsername($username)
	{
		$this->username = $username;

		return $this;
	}

	/**
	 * Get username
	 *
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}

	public function getUserFullName(){
        if($this->profile){
            return $this->profile->getName();
        }
        return false;
	}

	public function userDoctor(){

		if(!empty($this->profile->getDesignation())){
			$designation = $this->profile->getDesignation()->getName();
		}else{
			$designation ='';
		}

		return $this->profile->getName().' ('.$designation.')';
	}

    public function userMarketingExecutive(){

        if(!empty($this->profile->getDesignation())){
            $designation = $this->profile->getDesignation()->getName();
        }else{
            $designation ='';
        }
        return $this->profile->getName().' ('.$designation.')';
    }

	public function toArray($collection)
	{
		$this->setRoles($collection->toArray());
	}

	public function setRole($role)
	{
		$this->getRoles();
		$this->addRole($role);

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getRole()
	{
		$role = $this->getRoles();
		return $role[0];

	}


	/**
	 * @param Profile $profile
	 */
	public function setProfile($profile)
	{
		$profile->setUser($this);
		$this->profile = $profile;
	}

	/**
	 * @return Profile
	 */
	public function getProfile()
	{
		return $this->profile;
	}

	/**
	 * get avatar image file name
	 *
	 * @return string
	 */
	public function getAvatar()
	{
		return $this->avatar;
	}

	/**
	 * set avatar image file name
	 */
	public function setAvatar($avatar)
	{
		$this->avatar = $avatar;
	}

	public function isSuperAdmin()
	{
		$groups = $this->getGroups();
		foreach ($groups as $group) {
			if ($group->hasRole('ROLE_SUPER_ADMIN')) {
				return true;
			}
		}
		return false;
	}

	public function isRoleAdmin()
	{
		$groups = $this->getGroups();
		foreach ($groups as $group) {
			if ($group->hasRole('ROLE_ADMIN')) {
				return true;
			}
		}
		return false;
	}


	/**
	 * @param mixed $education
	 */
	public function setEducation($education)
	{
		$education->setUser($this);
		$this->education = $education;
	}

	/**
	 * @return mixed
	 */
	public function getEducation()
	{
		return $this->education;
	}

	/**
	 * @return mixed
	 */
	public function getPages()
	{
		return $this->pages;
	}


	/**
	 * @param mixed $siteSetting
	 */
	public function setSiteSetting($siteSetting)
	{
		$siteSetting->setUser($this);
		$this->siteSetting = $siteSetting;
	}

	/**
	 * @return mixed
	 */
	public function getSiteSetting()
	{
		return $this->siteSetting;
	}


	/**
	 * @return GlobalOption
	 */
	public function getGlobalOption()
	{
		return $this->globalOption;
	}

	/**
	 * @param GlobalOption $globalOption
	 */
	public function setGlobalOption($globalOption)
	{
		$this->globalOption = $globalOption;
	}



	/**
	 * @return mixed
	 */
	public function getHomePage()
	{
		return $this->homePage;
	}

	/**
	 * @return mixed
	 */
	public function getContactPage()
	{
		return $this->contactPage;
	}

	/**
	 * @return mixed
	 */
	public function getSyndicateContents()
	{
		return $this->syndicateContents;
	}


	/**
	 * @return mixed
	 */
	public function getProducts()
	{
		return $this->products;
	}

	/**
	 * @return mixed
	 */
	public function getVendor()
	{
		return $this->vendor;
	}

	/**
	 * @param mixed $vendor
	 */
	public function setVendor($vendor)
	{
		$this->vendor = $vendor;
	}

	/**
	 * @return mixed
	 */
	public function getCategoryGrouping()
	{
		return $this->categoryGrouping;
	}

	/**
	 * @return mixed
	 */
	public function getHomeSliders()
	{
		return $this->homeSliders;
	}


	/**
	 * @return mixed
	 */
	public function getStudyAbroad()
	{
		return $this->studyAbroad;
	}

	/**
	 * @return mixed
	 */
	public function getTutor()
	{
		return $this->tutor;
	}


	/**
	 * @return mixed
	 */
	public function getSalesUser()
	{
		return $this->salesUser;
	}

	/**
	 * @return mixed
	 */
	public function getSales()
	{
		return $this->sales;
	}

	/**
	 * @return mixed
	 */
	public function getPurchaseReturn()
	{
		return $this->purchaseReturn;
	}

	/**
	 * @return mixed
	 */
	public function getPurchasesReturnApprovedBy()
	{
		return $this->purchasesReturnApprovedBy;
	}


	/**
	 * @return boolean
	 */
	public function getIsDelete()
	{
		return $this->isDelete;
	}

	/**
	 * @param boolean $isDelete
	 */
	public function setIsDelete($isDelete)
	{
		$this->isDelete = $isDelete;
	}

	/**
	 * @return mixed
	 */
	public function getSalesReturn()
	{
		return $this->salesReturn;
	}

	/**
	 * @return mixed
	 */
	public function getPattyCash()
	{
		return $this->pattyCash;
	}

	/**
	 * @return mixed
	 */
	public function getPettyCashApprove()
	{
		return $this->pettyCashApprove;
	}

	/**
	 * @return mixed
	 */
	public function getExpenditure()
	{
		return $this->expenditure;
	}

	/**
	 * @return mixed
	 */
	public function getExpenditureToUser()
	{
		return $this->expenditureToUser;
	}

	/**
	 * @return mixed
	 */
	public function getExpenditureApprove()
	{
		return $this->expenditureApprove;
	}

	/**
	 * @return mixed
	 */
	public function getPaymentSalaries()
	{
		return $this->paymentSalaries;
	}

	/**
	 * @return mixed
	 */
	public function getSalesApprovedBy()
	{
		return $this->salesApprovedBy;
	}

	/**
	 * @return mixed
	 */
	public function getInvoiceSmsEmail()
	{
		return $this->invoiceSmsEmail;
	}

	/**
	 * @return mixed
	 */
	public function getInvoiceSmsEmailReceivedBy()
	{
		return $this->invoiceSmsEmailReceivedBy;
	}

	/**
	 * @return mixed
	 */
	public function getSalesImport()
	{
		return $this->salesImport;
	}

	/**
	 * @return StockItem
	 */
	public function getStockItems()
	{
		return $this->stockItems;
	}

	/**
	 * @return Order
	 */
	public function getOrders()
	{
		return $this->orders;
	}

	/**
	 * @return PreOrder
	 */
	public function getPreOrders()
	{
		return $this->preOrders;
	}

	public function getCheckRoleEcommercePreorder($role = NULL)
	{

		$roles = array(
			'ROLE_DOMAIN_INVENTORY_ECOMMERCE',
			'ROLE_DOMAIN_INVENTORY_ECOMMERCE_MANAGER',
			'ROLE_DOMAIN_INVENTORY_MANAGER',
			'ROLE_DOMAIN_INVENTORY_APPROVE',
			'ROLE_DOMAIN_MANAGER',
			'ROLE_DOMAIN'
		);

		if(in_array($role,$roles)){
			return true;
		}else{
			return false;
		}

	}


	public function getCheckRoleGlobal($existRole = NULL)
	{
		$result = array_intersect($existRole, $this->getRoles());
		if(empty($result)){
			return false;
		}else{
			return true;
		}

	}


    public function getCheckExistRole($existRole = NULL)
    {
        $result = in_array($existRole, $this->getRoles());
        if(empty($result)){
            return false;
        }else{
            return true;
        }

    }
	/**
	 * @return PreOrder
	 */
	public function getPreOrderProcess()
	{
		return $this->preOrderProcess;
	}

	/**
	 * @return PreOrder
	 */
	public function getPreOrderApproved()
	{
		return $this->preOrderApproved;
	}

	/**
	 * @return Damage
	 */
	public function getDamageApprovedBy()
	{
		return $this->damageApprovedBy;
	}

	/**
	 * @return Damage
	 */
	public function getDamage()
	{
		return $this->damage;
	}

	/**
	 * @return Order
	 */
	public function getOrderProcess()
	{
		return $this->orderProcess;
	}

	/**
	 * @return Order
	 */
	public function getOrderApproved()
	{
		return $this->orderApproved;
	}


	/**
	 * @return ServiceSales
	 */
	public function getServiceSales()
	{
		return $this->serviceSales;
	}

	/**
	 * @return ServiceSales
	 */
	public function getServiceSalesBy()
	{
		return $this->serviceSalesBy;
	}

	/**
	 * @return ServiceSales
	 */
	public function getServiceApprovedBy()
	{
		return $this->serviceApprovedBy;
	}

	/**
	 * @return Branches
	 */
	public function getBranches()
	{
		return $this->branches;
	}

	/**
	 * @return BranchInvoice
	 */
	public function getBranchInvoice()
	{
		return $this->branchInvoice;
	}

	/**
	 * @return BranchInvoice
	 */
	public function getBranchInvoiceApprovedBy()
	{
		return $this->branchInvoiceApprovedBy;
	}

	/**
	 * @return ExcelImporter
	 */
	public function getExcelImporters()
	{
		return $this->excelImporters;
	}

	/**
	 * @return Delivery
	 */
	public function getDelivery()
	{
		return $this->delivery;
	}

	/**
	 * @return Delivery
	 */
	public function getDeliveryApprovedBy()
	{
		return $this->deliveryApprovedBy;
	}

	/**
	 * @return DeliveryReturn
	 */
	public function getDeliveryReturn()
	{
		return $this->deliveryReturn;
	}

	/**
	 * @return DeliveryReturn
	 */
	public function getDeliveryReturnApprovedBy()
	{
		return $this->deliveryReturnApprovedBy;
	}

	/**
	 * @return GlobalOption
	 */
	public function getGlobalOptionAgents()
	{
		return $this->globalOptionAgents;
	}

	/**
	 * @return mixed
	 */
	public function getAgent()
	{
		return $this->agent;
	}

	/**
	 * @param mixed $agent
	 */
	public function setAgent($agent)
	{
		$this->agent = $agent;
	}

	/**
	 * @return Particular
	 */
	public function getParticularOperator()
	{
		return $this->particularOperator;
	}

	/**
	 * @return InvoiceParticular
	 */
	public function getHmsInvoiceParticularCollected()
	{
		return $this->hmsInvoiceParticularCollected;
	}

	/**
	 * @return DailyAttendance
	 */
	public function getUserAttendance()
	{
		return $this->userAttendance;
	}


	/**
	 * @return DailyAttendance
	 */
	public function getUserAttendanceMonth($year,$month)
	{
		$attendances = $this->getUserAttendance();

		/* @var DailyAttendance $attendance */

		$presentDays = array();
		foreach ($attendances as $attendance){
			if($attendance->getYear() == $year and $attendance->getMonth() == $month ){
				$presentDays[] = $attendance->getPresentDay();
			}
		}
		return $presentDays;
	}

	/**
	 * @return HrAttendanceMonth
	 */
	public function getMonthlyPresentDay($year,$month)
	{
		$attendances = $this->getUserAttendance();

		/* @var HrAttendanceMonth $attendance */

		$presentDays = array();
		foreach ($attendances as $attendance){
			if($attendance->getYear() == $year and $attendance->getMonth() == $month ){
				$presentDays[] = $attendance->getPresentDay();
			}
		}
		return count($presentDays);
	}

	/**
	 * @return OrderPayment
	 */
	public function getOrderPayments()
	{
		return $this->orderPayments;
	}

	/**
	 * @return PreOrderPayment
	 */
	public function getPreOrderPayments()
	{
		return $this->preOrderPayments;
	}

	/**
	 * @return DmsParticular
	 */
	public function getDmsParticularDoctor()
	{
		return $this->dmsParticularDoctor;
	}

	/**
	 * @return HmsInvoiceTemporaryParticular
	 */
	public function getHmsInvoiceTemporaryParticulars()
	{
		return $this->hmsInvoiceTemporaryParticulars;
	}

	/**
	 * @return MedicineReverse
	 */
	public function getMedicineReverse()
	{
		return $this->medicineReverse;
	}

	/**
	 * @return DpsParticular
	 */
	public function getDpsParticularOperator()
	{
		return $this->dpsParticularOperator;
	}

	/**
	 * @return MedicinePurchase
	 */
	public function getMedicinePurchasesBy()
	{
		return $this->medicinePurchasesBy;
	}

	/**
	 * @return MedicineSalesTemporary
	 */
	public function getMedicineSalesTemporary()
	{
		return $this->medicineSalesTemporary;
	}

	/**
	 * @return int
	 */
	public function getDomainOwner()
	{
		return $this->domainOwner;
	}

	/**
	 * @param int $domainOwner
	 */
	public function setDomainOwner($domainOwner)
	{
		$this->domainOwner = $domainOwner;
	}

	/**
	 * @return DomainUser
	 */
	public function getDomainUser()
	{
		return $this->domainUser;
	}

	/**
	 * @return CustomerInvoice
	 */
	public function getCustomerInvoice() {
		return $this->customerInvoice;
	}

	/**
	 * @return bool
	 */
	public function isEnabled(){
		return $this->enabled;
	}

	/**
	 * @return HotelTemporaryInvoice
	 */
	public function getHotelTemporary() {
		return $this->hotelTemporary;
	}

	/**
	 * @return AccountCash
	 */
	public function getAccountCashes() {
		return $this->accountCashes;
	}

    /**
     * @return HmsInvoiceReturn
     */
    public function getHmsInvoiceReturnCreatedBy()
    {
        return $this->hmsInvoiceReturnCreatedBy;
    }

    /**
     * @return HmsInvoiceReturn
     */
    public function getHmsInvoiceReturnApprovedBy()
    {
        return $this->hmsInvoiceReturnApprovedBy;
    }

    /**
     * @return RestaurantTemporary
     */
    public function getRestaurantTemps()
    {
        return $this->restaurantTemps;
    }

    /**
     * @return AccountSalesAdjustment
     */
    public function getSalesAdjustment()
    {
        return $this->salesAdjustment;
    }

    /**
     * @return AccountSalesAdjustment
     */
    public function getSalesAdjustmentApprove()
    {
        return $this->salesAdjustmentApprove;
    }

    /**
     * @return string
     */
    public function getUserGroup()
    {
        return $this->userGroup;
    }

    /**
     * @param string $userGroup
     */
    public function setUserGroup($userGroup)
    {
        $this->userGroup = $userGroup;
    }

    /**
     * @return AccountHead
     */
    public function getAccountHead()
    {
        return $this->accountHead;
    }


    /**
     * @return EmployeePayroll
     */
    public function getPayrollApproved()
    {
        return $this->payrollApproved;
    }

    /**
     * @param  EmployeePayroll $employeePayroll
     */
    public function setEmployeePayroll($employeePayroll)
    {
        $employeePayroll->setEmployee($this);
        $this->employeePayroll = $employeePayroll;
    }

     /**
     * @return EmployeePayroll
     */
    public function getEmployeePayroll()
    {
        return $this->employeePayroll;
    }

    /**
     * @return array
     */
    public function getAppRoles()
    {
        return $this->appRoles;
    }

    /**
     * @param array $appRoles
     */
    public function setAppRoles($appRoles)
    {
        $this->appRoles = $appRoles;
    }

    /**
     * @return string
     */
    public function getAppPassword()
    {
        return $this->appPassword;
    }

    /**
     * @param string $appPassword
     */
    public function setAppPassword($appPassword)
    {
        $this->appPassword = $appPassword;
    }

    /**
     * @return BusinessAndroidProcess
     */
    public function getBusinessAndroidProcess()
    {
        return $this->businessAndroidProcess;
    }

    /**
     * @return mixed
     */
    public function getParticularDoctor()
    {
        return $this->particularDoctor;
    }





}