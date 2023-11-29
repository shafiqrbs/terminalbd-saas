<?php

namespace Appstore\Bundle\AssetsBundle\Entity;

use Appstore\Bundle\AccountingBundle\Entity\AccountBank;
use Appstore\Bundle\AccountingBundle\Entity\AccountMobileBank;
use Appstore\Bundle\ProcurementBundle\Entity\PurchaseRequisition;
use Core\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Setting\Bundle\ToolBundle\Entity\Bank;
use Setting\Bundle\ToolBundle\Entity\TransactionMethod;

/**
 * Sales
 *
 * @ORM\Table("assets_sales")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\AssetsBundle\Repository\SalesRepository")
 */
class Sales
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\AssetsConfig", inversedBy="sales" )
     **/
    private $config;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\DomainUserBundle\Entity\Branches", inversedBy="sales" )
     **/
    private $branches;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountSales", mappedBy="assetsSales", cascade={"remove"} )
     * @ORM\OrderBy({"id" = "DESC"})
     **/
    private $accountSales;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\StockItem", mappedBy="sales", cascade={"remove"} )
     * @ORM\OrderBy({"id" = "DESC"})
     **/
    private $stockItems;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\DomainUserBundle\Entity\Customer", inversedBy="sales"  )
     **/
    private $customer;


    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ProcurementBundle\Entity\PurchaseRequisition")
     **/
    private $purchaseRequisition;

    /**
     * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User", inversedBy="salesUser" )
     **/
    private $salesBy;


    /**
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User", inversedBy="sales" )
     **/
    private $createdBy;


    /**
     * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User", inversedBy="salesApprovedBy" )
     **/
    private $approvedBy;

    /**
     * @var string
     *
     * @ORM\Column(name="paymentMethod", type="string", length=50, nullable=true)
     */
    private $paymentMethod = 'Cash';

    /**
     * @var string
     *
     * @ORM\Column(name="salesMode", type="string", length=50, nullable=true)
     */
    private $salesMode = 'pos';

    /**
     * @ORM\ManyToOne(targetEntity="Setting\Bundle\ToolBundle\Entity\TransactionMethod", inversedBy="sales" )
     **/
    private $transactionMethod;

    /**
     * @ORM\ManyToOne(targetEntity="Setting\Bundle\ToolBundle\Entity\Bank", inversedBy="sales" )
     **/
    private $bank;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountBank", inversedBy="sales" )
     **/
    private $accountBank;


    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountMobileBank", inversedBy="sales" )
     **/
    private $accountMobileBank;


    /**
     * @ORM\ManyToOne(targetEntity="Setting\Bundle\ToolBundle\Entity\PaymentCard", inversedBy="sales" )
     **/
    private $paymentCard;

    /**
     * @var string
     *
     * @ORM\Column(name="cardNo", type="string", length=100, nullable=true)
     */
    private $cardNo;

    /**
     * @var string
     *
     * @ORM\Column(name="paymentMobile", type="string", length=50, nullable=true)
     */
    private $paymentMobile;

    /**
     * @var string
     *
     * @ORM\Column(name="paymentInWord", type="string", length=255, nullable=true)
     */
    private $paymentInWord;

    /**
     * @var string
     *
     * @ORM\Column(name="courierInvoice", type="string", length=50, nullable=true)
     */
    private $courierInvoice;

    /**
     * @var string
     *
     * @ORM\Column(name="process", type="string", length=50, nullable=true)
     */
    private $process = 'Created';

    /**
     * @var string
     *
     * @ORM\Column(name="transactionId", type="string", length=100, nullable=true)
     */
    private $transactionId;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice", type="string", length=50, nullable=true)
     */
    private $invoice;

    /**
     * @var integer
     *
     * @ORM\Column(name="code", type="integer",  nullable=true)
     */
    private $code;

    /**
     * @var integer
     *
     * @ORM\Column(name="totalItem", type="smallint", length=2, nullable=true)
     */
    private $totalItem;


    /**
     * @var string
     *
     * @ORM\Column(name="paymentStatus", type="string", length=50, nullable=true)
     */
    private $paymentStatus = "Pending";


    /**
     * @var float
     *
     * @ORM\Column(name="subTotal", type="float", nullable=true)
     */
    private $subTotal = 0;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="purchasePrice", type="float", nullable=true)
	 */
	private $purchasePrice = 0;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="profit", type="float", nullable=true)
	 */
	private $profit = 0;


    /**
     * @var float
     *
     * @ORM\Column(name="discount", type="float", nullable=true)
     */
    private $discount = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="discountType", type="string", length=30, nullable=true)
     */
    private $discountType = "Flat";

	/**
	 * @var float
	 *
	 * @ORM\Column(name="discountCalculation", type="float" , nullable=true)
	 */
	private $discountCalculation = 0;

	/**
     * @var float
     *
     * @ORM\Column(name="valueAddedTax", type="float", nullable=true)
     */
    private $valueAddedTax = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="rebate", type="float", nullable=true)
     */
    private $rebate = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="supplementaryDuty", type="float", nullable = true)
     */
    private $supplementaryDuty = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="totalTaxIncidence", type="float", nullable = true)
     */
    private $totalTaxIncidence = 0;


    /**
     * @var string
     *
     * @ORM\Column(name="purchaseRequisitionNo", type="string", nullable=true)
     */
    private $purchaseRequisitionNo;


    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float", nullable=true)
     */
    private $total = 0;


    /**
     * @var float
     *
     * @ORM\Column(name="netTotal", type="float", nullable=true)
     */
    private $netTotal = 0;



    /**
     * @var float
     *
     * @ORM\Column(name="payment", type="float", nullable=true)
     */
    private $payment = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="deliveryCharge", type="float", nullable=true)
     */
    private $deliveryCharge = 0;


    /**
     * @var float
     *
     * @ORM\Column(name="due", type="float", nullable=true)
     */
    private $due = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", nullable=true)
     */
    private $mobile;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="remark", type="text", nullable=true)
	 */
	private $remark;

    /**
     * @var boolean
     *
     * @ORM\Column(name="revised", type="boolean")
     */
    private $revised = false;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @param string $paymentMethod
     * Cash
     * Cheque
     * Giftcard
     * Bkash
     * Payment Card
     * Other
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @return float
     */
    public function getSubTotal()
    {
        return $this->subTotal;
    }

    /**
     * @param float $subTotal
     */
    public function setSubTotal($subTotal)
    {
        $this->subTotal = $subTotal;
    }

    /**
     * @return float
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param float $discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    /**
     * @return float
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * @param float $vat
     */
    public function setVat($vat)
    {
        $this->vat = $vat;
    }

    /**
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param float $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }


    /**
     * @return float
     */
    public function getDue()
    {
        return $this->due;
    }

    /**
     * @param float $due
     */
    public function setDue($due)
    {
        $this->due = $due;
    }



    /**
     * @return string
     */
    public function getPaymentStatus()
    {
        return $this->paymentStatus;
    }

    /**
     * @param string $paymentStatus
     * Paid
     * Pending
     * Partial
     * Due
     * Other
     */
    public function setPaymentStatus($paymentStatus)
    {
        $this->paymentStatus = $paymentStatus;
    }

    /**
     * @return mixed
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param mixed $customer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return mixed
     */
    public function getSalesBy()
    {
        return $this->salesBy;
    }

    /**
     * @param mixed $salesBy
     */
    public function setSalesBy($salesBy)
    {
        $this->salesBy = $salesBy;
    }


    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return int
     */
    public function getTotalItem()
    {
        return $this->totalItem;
    }

    /**
     * @param int $totalItem
     */
    public function setTotalItem($totalItem)
    {
        $this->totalItem = $totalItem;
    }

    /**
     * @return Bank
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * @param Bank $bank
     */
    public function setBank($bank)
    {
        $this->bank = $bank;
    }

    /**
     * @return mixed
     */
    public function getPaymentCard()
    {
        return $this->paymentCard;
    }

    /**
     * @param mixed $paymentCard
     */
    public function setPaymentCard($paymentCard)
    {
        $this->paymentCard = $paymentCard;
    }

    /**
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param string $mobile
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     * @return mixed
     */
    public function getSalesReturn()
    {
        return $this->salesReturn;
    }

    /**
     * @param mixed $salesReturn
     */
    public function setSalesReturn($salesReturn)
    {
        $this->salesReturn = $salesReturn;
    }

    /**
     * @return mixed
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param mixed $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param integer $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @param string $invoice
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;
    }


    /**
     * @return float
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @param float $payment
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;
    }


    /**
     * @return mixed
     */
    public function getAccountSales()
    {
        return $this->accountSales;
    }

    /**
     * @return mixed
     */
    public function getSalesImport()
    {
        return $this->salesImport;
    }

    /**
     * @param mixed $salesImport
     */
    public function setSalesImport($salesImport)
    {
        $this->salesImport = $salesImport;
    }

    /**
     * @return mixed
     */
    public function getApprovedBy()
    {
        return $this->approvedBy;
    }

    /**
     * @param mixed $approvedBy
     */
    public function setApprovedBy($approvedBy)
    {
        $this->approvedBy = $approvedBy;
    }

    /**
     * @return AccountBank
     */
    public function getAccountBank()
    {
        return $this->accountBank;
    }

    /**
     * @param AccountBank $accountBank
     */
    public function setAccountBank($accountBank)
    {
        $this->accountBank = $accountBank;
    }

    /**
     * @return TransactionMethod
     */
    public function getTransactionMethod()
    {
        return $this->transactionMethod;
    }

    /**
     * @param TransactionMethod $transactionMethod
     */
    public function setTransactionMethod($transactionMethod)
    {
        $this->transactionMethod = $transactionMethod;
    }

    /**
     * @return AccountMobileBank
     */
    public function getAccountMobileBank()
    {
        return $this->accountMobileBank;
    }

    /**
     * @param AccountMobileBank $accountMobileBank
     */
    public function setAccountMobileBank($accountMobileBank)
    {
        $this->accountMobileBank = $accountMobileBank;
    }

    /**
     * @return string
     */
    public function getPaymentMobile()
    {
        return $this->paymentMobile;
    }

    /**
     * @param string $paymentMobile
     */
    public function setPaymentMobile($paymentMobile)
    {
        $this->paymentMobile = $paymentMobile;
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @param string $transactionId
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
    }

    /**
     * @return string
     */
    public function getCardNo()
    {
        return $this->cardNo;
    }

    /**
     * @param string $cardNo
     */
    public function setCardNo($cardNo)
    {
        $this->cardNo = $cardNo;
    }

    /**
     * @return mixed
     */
    public function getBranches()
    {
        return $this->branches;
    }

    /**
     * @param mixed $branches
     */
    public function setBranches($branches)
    {
        $this->branches = $branches;
    }

    /**
     * @return mixed
     */
    public function getSalesReturnAdjustment()
    {
        return $this->salesReturnAdjustment;
    }

    /**
     * @return string
     */
    public function getCourierInvoice()
    {
        return $this->courierInvoice;
    }

    /**
     * @param string $courierInvoice
     */
    public function setCourierInvoice($courierInvoice)
    {
        $this->courierInvoice = $courierInvoice;
    }

    /**
     * @return string
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * @param string $process
     */
    public function setProcess($process)
    {
        $this->process = $process;
    }

    /**
     * @return string
     */
    public function getPaymentInWord()
    {
        return $this->paymentInWord;
    }

    /**
     * @param string $paymentInWord
     */
    public function setPaymentInWord($paymentInWord)
    {
        $this->paymentInWord = $paymentInWord;
    }

    /**
     * @return string
     */
    public function getSalesMode()
    {
        return $this->salesMode;
    }

    /**
     * @param string $salesMode
     */
    public function setSalesMode($salesMode)
    {
        $this->salesMode = $salesMode;
    }

    /**
     * @return string
     */
    public function getDeliveryCharge()
    {
        return $this->deliveryCharge;
    }

    /**
     * @param string $deliveryCharge
     */
    public function setDeliveryCharge($deliveryCharge)
    {
        $this->deliveryCharge = $deliveryCharge;
    }

    /**
     * @return bool
     */
    public function isRevised()
    {
        return $this->revised;
    }

    /**
     * @param bool $revised
     */
    public function setRevised($revised)
    {
        $this->revised = $revised;
    }

    /**
     * @return Reverse
     */
    public function getReverse()
    {
        return $this->reverse;
    }

	/**
	 * @return string
	 */
	public function getDiscountType(){
		return $this->discountType;
	}

	/**
	 * @param string $discountType
	 */
	public function setDiscountType( string $discountType ) {
		$this->discountType = $discountType;
	}

	/**
	 * @return float
	 */
	public function getDiscountCalculation(){
		return $this->discountCalculation;
	}

	/**
	 * @param float $discountCalculation
	 */
	public function setDiscountCalculation($discountCalculation ) {
		$this->discountCalculation = $discountCalculation;
	}

	/**
	 * @return string
	 */
	public function getRemark(){
		return $this->remark;
	}

	/**
	 * @param string $remark
	 */
	public function setRemark($remark ) {
		$this->remark = $remark;
	}

	/**
	 * @return float
	 */
	public function getPurchasePrice(){
		return $this->purchasePrice;
	}

	/**
	 * @param float $purchasePrice
	 */
	public function setPurchasePrice( float $purchasePrice ) {
		$this->purchasePrice = $purchasePrice;
	}

	/**
	 * @return float
	 */
	public function getProfit(){
		return $this->profit;
	}

	/**
	 * @param float $profit
	 */
	public function setProfit( float $profit ) {
		$this->profit = $profit;
	}


    /**
     * @return AssetsConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param AssetsConfig $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return float
     */
    public function getSupplementaryDuty()
    {
        return $this->supplementaryDuty;
    }

    /**
     * @param float $supplementaryDuty
     */
    public function setSupplementaryDuty($supplementaryDuty)
    {
        $this->supplementaryDuty = $supplementaryDuty;
    }


    /**
     * @return PurchaseOrder
     */
    public function getPurchaseOrder()
    {
        return $this->purchaseOrder;
    }

    /**
     * @param PurchaseOrder $purchaseOrder
     */
    public function setPurchaseOrder($purchaseOrder)
    {
        $this->purchaseOrder = $purchaseOrder;
    }

    /**
     * @return PurchaseRequisition
     */
    public function getPurchaseRequisition()
    {
        return $this->purchaseRequisition;
    }

    /**
     * @param PurchaseRequisition $purchaseRequisition
     */
    public function setPurchaseRequisition($purchaseRequisition)
    {
        $this->purchaseRequisition = $purchaseRequisition;
    }

    /**
     * @return StockItem
     */
    public function getStockItems()
    {
        return $this->stockItems;
    }

    /**
     * @return float
     */
    public function getRebate()
    {
        return $this->rebate;
    }

    /**
     * @param float $rebate
     */
    public function setRebate($rebate)
    {
        $this->rebate = $rebate;
    }

    /**
     * @return float
     */
    public function getNetTotal()
    {
        return $this->netTotal;
    }

    /**
     * @param float $netTotal
     */
    public function setNetTotal($netTotal)
    {
        $this->netTotal = $netTotal;
    }

    /**
     * @return float
     */
    public function getValueAddedTax()
    {
        return $this->valueAddedTax;
    }

    /**
     * @param float $valueAddedTax
     */
    public function setValueAddedTax($valueAddedTax)
    {
        $this->valueAddedTax = $valueAddedTax;
    }

    /**
     * @return float
     */
    public function getTotalTaxIncidence()
    {
        return $this->totalTaxIncidence;
    }

    /**
     * @param float $totalTaxIncidence
     */
    public function setTotalTaxIncidence($totalTaxIncidence)
    {
        $this->totalTaxIncidence = $totalTaxIncidence;
    }


    /**
     * @return string
     */
    public function getPurchaseRequisitionNo()
    {
        return $this->purchaseRequisitionNo;
    }

    /**
     * @param string $purchaseRequisitionNo
     */
    public function setPurchaseRequisitionNo($purchaseRequisitionNo)
    {
        $this->purchaseRequisitionNo = $purchaseRequisitionNo;
    }

}

