<?php

namespace Appstore\Bundle\HotelBundle\Entity;

use Appstore\Bundle\AccountingBundle\Entity\AccountBank;
use Appstore\Bundle\AccountingBundle\Entity\AccountMobileBank;
use Appstore\Bundle\AccountingBundle\Entity\AccountSales;
use Appstore\Bundle\DomainUserBundle\Entity\Customer;
use Core\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Setting\Bundle\ToolBundle\Entity\Bank;
use Setting\Bundle\ToolBundle\Entity\PaymentCard;
use Setting\Bundle\ToolBundle\Entity\TransactionMethod;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Invoice
 *
 * @ORM\Table( name ="hotel_invoice")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\HotelBundle\Repository\HotelInvoiceRepository")
 */
class HotelInvoice
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelConfig", inversedBy="hotelInvoices")
     **/
    private $hotelConfig;

    /**
     * @ORM\OneToOne(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelReverse", mappedBy="hotelInvoice")
     **/
    private $hotelReverse;

     /**
     * @ORM\OneToOne(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelInvoiceTransactionSummary", mappedBy="hotelInvoice", cascade={"remove"})
     **/
    private $hotelInvoiceTransactionSummary;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelInvoiceTransaction", mappedBy="hotelInvoice")
     **/
    private $hotelInvoiceTransactions;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelInvoiceParticular", mappedBy="hotelInvoice" , cascade={"remove"} )
     * @ORM\OrderBy({"id" = "ASC"})
     **/
    private  $hotelInvoiceParticulars;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\DomainUserBundle\Entity\Customer", inversedBy="hotelInvoices" ,cascade={"persist"} )
     **/
    private  $customer;

    /**
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User", inversedBy="hotelInvoiceCreatedBy" )
     **/
    private  $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User", inversedBy="salesUser" )
     **/
    private $salesBy;

    /**
     * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User", inversedBy="hotelInvoiceApprovedBy" )
     **/
    private  $approvedBy;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountSales", mappedBy="hotelInvoice" )
     **/
    private  $accountSales;

    /**
     * @ORM\ManyToOne(targetEntity="Setting\Bundle\ToolBundle\Entity\TransactionMethod", inversedBy="hotelInvoice" )
     **/
    private  $transactionMethod;

    /**
     * @ORM\ManyToOne(targetEntity="Setting\Bundle\ToolBundle\Entity\Bank", inversedBy="hotelInvoice" )
     **/
    private  $bank;

    /**
     * @ORM\ManyToOne(targetEntity="Setting\Bundle\ToolBundle\Entity\PaymentCard", inversedBy="hotelInvoice" )
     **/
    private  $paymentCard;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountBank", inversedBy="hotelInvoice" )
     **/
    private  $accountBank;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountMobileBank", inversedBy="hotelInvoice" )
     **/
    private  $accountMobileBank;

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
     * @ORM\Column(name="transactionId", type="string", length=100, nullable=true)
     */
    private $transactionId;

    /**
     * @var string
     *
     * @ORM\Column(name="process", type="string", length=50, nullable=true)
     */
    private $process ='created';

    /**
     * @var string
     *
     * @ORM\Column(name="roomName", type="string", length=256, nullable=true)
     */
    private $roomName;

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
     * @var string
     *
     * @ORM\Column(name="paymentStatus", type="string", length=50, nullable=true)
     */
    private $paymentStatus = "Pending";

    /**
     * @var string
     *
     * @ORM\Column(name="invoiceFor", type="string", length=20, nullable=true)
     */
    private $invoiceFor = "hotel";

    /**
     * @var string
     *
     * @ORM\Column(name="discountType", type="string", length=20, nullable=true)
     */
    private $discountType ='';

    /**
     * @var float
     *
     * @ORM\Column(name="discountCalculation", type="float" , nullable=true)
     */
    private $discountCalculation = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="subTotal", type="float", nullable=true)
     */
    private $subTotal;

    /**
     * @var float
     *
     * @ORM\Column(name="discount", type="float", nullable=true)
     */
    private $discount = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="vat", type="float", nullable=true)
     */
    private $vat;

     /**
     * @var float
     *
     * @ORM\Column(name="serviceCharge", type="float", nullable=true)
     */
    private $serviceCharge;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float", nullable=true)
     */
    private $total;


    /**
     * @var float
     *
     * @ORM\Column( name = "received", type = "float" , nullable = true)
     */
    private $received = 0;

	/**
     * @var float
     *
     * @ORM\Column( name = "paymentReceived", type = "float" , nullable = true)
     */
    private $paymentReceived = 0;


    /**
     * @var float
     *
     * @ORM\Column(name="commission", type="float", nullable=true)
     */
    private $commission = 0;


    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;


    /**
     * @var float
     *
     * @ORM\Column(name="due", type="float", nullable=true)
     */
    private $due = 0;


    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="text", nullable=true)
     */
    private $mobile;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isReversed", type="boolean", nullable=true)
     */
    private $isReversed;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param User $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
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
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }



    /**
     * @return HotelConfig
     */
    public function getHotelConfig()
    {
        return $this->hotelConfig;
    }

    /**
     * @param HotelConfig $hotelConfig
     */
    public function setHotelConfig($hotelConfig)
    {
        $this->hotelConfig = $hotelConfig;
    }

    /**
     * @return User
     */
    public function getApprovedBy()
    {
        return $this->approvedBy;
    }

    /**
     * @param User $approvedBy
     */
    public function setApprovedBy($approvedBy)
    {
        $this->approvedBy = $approvedBy;
    }

    /**
     * @return HotelInvoiceParticular
     */
    public function getHotelInvoiceParticulars()
    {
        return $this->hotelInvoiceParticulars;
    }

    /**
     * @return string
     */
    public function getDiscountType()
    {
        return $this->discountType;
    }

    /**
     * @param string $discountType
     */
    public function setDiscountType($discountType)
    {
        $this->discountType = $discountType;
    }

    /**
     * @return float
     */
    public function getDiscountCalculation()
    {
        return $this->discountCalculation;
    }

    /**
     * @param float $discountCalculation
     */
    public function setDiscountCalculation($discountCalculation)
    {
        $this->discountCalculation = $discountCalculation;
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
     * @return AccountSales
     */
    public function getAccountSales()
    {
        return $this->accountSales;
    }

    /**
     * @return PaymentCard
     */
    public function getPaymentCard()
    {
        return $this->paymentCard;
    }

    /**
     * @param PaymentCard $paymentCard
     */
    public function setPaymentCard($paymentCard)
    {
        $this->paymentCard = $paymentCard;
    }

    /**
     * @return User
     */
    public function getSalesBy()
    {
        return $this->salesBy;
    }

    /**
     * @param User $salesBy
     */
    public function setSalesBy($salesBy)
    {
        $this->salesBy = $salesBy;
    }


	/**
	 * @return HotelReverse
	 */
	public function getHotelReverse() {
		return $this->hotelReverse;
	}

	/**
	 * @return bool
	 */
	public function isReversed(){
		return $this->isReversed;
	}

	/**
	 * @param bool $isReversed
	 */
	public function setIsReversed( bool $isReversed ) {
		$this->isReversed = $isReversed;
	}

	/**
	 * @return HotelInvoiceTransaction
	 */
	public function getHotelInvoiceTransactions() {
		return $this->hotelInvoiceTransactions;
	}

	/**
	 * @return float
	 */
	public function getReceived(){
		return $this->received;
	}

	/**
	 * @param float $received
	 */
	public function setReceived($received ) {
		$this->received = $received;
	}

	/**
	 * @return float
	 */
	public function getSubTotal(){
		return $this->subTotal;
	}

	/**
	 * @param float $subTotal
	 */
	public function setSubTotal($subTotal) {
		$this->subTotal = $subTotal;
	}

	/**
	 * @return float
	 */
	public function getDiscount(){
		return $this->discount;
	}

	/**
	 * @param float $discount
	 */
	public function setDiscount($discount ) {
		$this->discount = $discount;
	}

	/**
	 * @return float
	 */
	public function getVat(){
		return $this->vat;
	}

	/**
	 * @param float $vat
	 */
	public function setVat($vat ) {
		$this->vat = $vat;
	}

	/**
	 * @return float
	 */
	public function getTotal(){
		return $this->total;
	}

	/**
	 * @param float $total
	 */
	public function setTotal($total ) {
		$this->total = $total;
	}

	/**
	 * @return float
	 */
	public function getCommission(){
		return $this->commission;
	}

	/**
	 * @param float $commission
	 */
	public function setCommission($commission ) {
		$this->commission = $commission;
	}

	/**
	 * @return float
	 */
	public function getDue(){
		return $this->due;
	}

	/**
	 * @param float $due
	 */
	public function setDue($due ) {
		$this->due = $due;
	}

	/**
	 * @return float
	 */
	public function getPaymentReceived(){
		return $this->paymentReceived;
	}

	/**
	 * @param float $paymentReceived
	 */
	public function setPaymentReceived($paymentReceived ) {
		$this->paymentReceived = $paymentReceived;
	}

	/**
	 * @return string
	 */
	public function getInvoiceFor(){
		return $this->invoiceFor;
	}

	/**
	 * @param string $invoiceFor
	 */
	public function setInvoiceFor( string $invoiceFor ) {
		$this->invoiceFor = $invoiceFor;
	}

	/**
	 * @return string
	 */
	public function getRoomName(){
		return $this->roomName;
	}

	/**
	 * @param string $roomName
	 */
	public function setRoomName( string $roomName ) {
		$this->roomName = $roomName;
	}

	/**
	 * @return HotelInvoiceTransactionSummary
	 */
	public function getHotelInvoiceTransactionSummary() {
		return $this->hotelInvoiceTransactionSummary;
	}

	/**
	 * @return float
	 */
	public function getServiceCharge(){
		return $this->serviceCharge;
	}

	/**
	 * @param float $serviceCharge
	 */
	public function setServiceCharge( float $serviceCharge ) {
		$this->serviceCharge = $serviceCharge;
	}

}

