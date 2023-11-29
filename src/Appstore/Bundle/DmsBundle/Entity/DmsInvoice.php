<?php

namespace Appstore\Bundle\DmsBundle\Entity;

use Appstore\Bundle\AccountingBundle\Entity\AccountBank;
use Appstore\Bundle\AccountingBundle\Entity\AccountMobileBank;
use Appstore\Bundle\AccountingBundle\Entity\AccountSales;
use Appstore\Bundle\DomainUserBundle\Entity\Customer;
use Appstore\Bundle\MedicineBundle\Entity\MedicineDoctorPrescribe;
use Core\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Setting\Bundle\ToolBundle\Entity\Bank;
use Setting\Bundle\ToolBundle\Entity\PaymentCard;
use Setting\Bundle\ToolBundle\Entity\TransactionMethod;

/**
 * Invoice
 *
 * @ORM\Table( name ="dms_invoice")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\DmsBundle\Repository\DmsInvoiceRepository")
 */
class DmsInvoice
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsConfig", inversedBy="dmsInvoices")
     **/
    private $dmsConfig;


     /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsDoctorInvoice", mappedBy="dmsInvoice" , cascade={"remove"})
     * @ORM\OrderBy({"updated" = "DESC"})
     **/
    private $dmsDoctorInvoices;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsService", inversedBy="dmsInvoices")
     **/
    private $service;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsInvoiceParticular", mappedBy="dmsInvoice" , cascade={"remove"} )
     **/
    private  $invoiceParticulars;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsInvoiceMedicine", mappedBy="dmsInvoice" , cascade={"remove"} )
     * @ORM\OrderBy({"updated" = "DESC"})
     **/
    private  $invoiceMedicines;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\MedicineBundle\Entity\MedicineDoctorPrescribe", mappedBy="dmsInvoice" , cascade={"remove"} )
     * @ORM\OrderBy({"updated" = "DESC"})
     **/
    private  $medicineDoctorPrescribes;


   /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsInvoiceAccessories", mappedBy="dmsInvoice" , cascade={"remove"} )
     * @ORM\OrderBy({"id" = "ASC"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $dmsInvoiceAccessories;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsTreatmentPlan", mappedBy="dmsInvoice" , cascade={"remove"} )
     * @ORM\OrderBy({"created" = "DESC"})
     **/
    private  $dmsTreatmentPlans;

    /**
     * @ORM\OneToOne(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsReverse", mappedBy="dmsInvoice" , cascade={"remove"} )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $dmsReverse;


   /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountSales", mappedBy="dmsInvoices" )
     * @ORM\OrderBy({"id" = "DESC"})
     **/
    private  $accountSales;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\DomainUserBundle\Entity\Customer", inversedBy="dmsInvoices" ,cascade={"persist"} )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $customer;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsParticular", inversedBy="dmsInvoice", cascade={"persist"}  )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $referredDoctor;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsParticular", inversedBy="assignDoctorInvoices", cascade={"persist"}  )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $assignDoctor;

    /**
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User", inversedBy="dmsInvoiceCreatedBy" )
     **/
    private  $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User", inversedBy="dmsInvoiceApprovedBy" )
     **/
    private  $approvedBy;

    /**
     * @var string
     *
     * @ORM\Column(name="paymentMethod", type="string", length=30, nullable=true)
     */
    private $paymentMethod='Cash';


    /**
     * @ORM\ManyToOne(targetEntity="Setting\Bundle\ToolBundle\Entity\TransactionMethod", inversedBy="dmsInvoice" )
     **/
    private  $transactionMethod;

    /**
     * @ORM\ManyToOne(targetEntity="Setting\Bundle\ToolBundle\Entity\Bank", inversedBy="dmsInvoices" )
     **/
    private  $bank;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountBank", inversedBy="dmsInvoices" )
     **/
    private  $accountBank;


    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountMobileBank", inversedBy="dmsInvoices" )
     **/
    private  $accountMobileBank;


    /**
     * @ORM\ManyToOne(targetEntity="Setting\Bundle\ToolBundle\Entity\PaymentCard", inversedBy="dmsInvoices" )
     **/
    private  $paymentCard;

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
     * @ORM\Column(name="process", type="string", length=50, nullable=true)
     */
    private $process ='Created';

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
     * @var string
     *
     * @ORM\Column(name="paymentStatus", type="string", length=50, nullable=true)
     */
    private $paymentStatus = "Pending";

    /**
     * @var string
     *
     * @ORM\Column(name="cabinNo", type="string", length=50, nullable=true)
     */
    private $cabinNo;


    /**
     * @var float
     *
     * @ORM\Column(name="subTotal", type="float", nullable=true)
     */
    private $subTotal;


    /**
     * @var float
     *
     * @ORM\Column(name="estimateTotal", type="float", nullable=true)
     */
    private $estimateTotal;


    /**
     * @var float
     *
     * @ORM\Column(name="discount", type="float", nullable=true)
     */
    private $discount;

    /**
     * @var integer
     *
     * @ORM\Column(name="percentage", type="smallint" , length=3 , nullable=true)
     */
    private $percentage;


    /**
     * @var float
     *
     * @ORM\Column(name="vat", type="float", nullable=true)
     */
    private $vat;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float", nullable=true)
     */
    private $total;

    /**
     * @var float
     *
     * @ORM\Column(name="payment", type="float", nullable=true)
     */
    private $payment;

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
    private $due;

    /**
     * @var boolean
     *
     * @ORM\Column(name="revised", type="boolean" )
     */
    private $revised = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="sendSms", type="boolean" )
     */
    private $sendSms = false;


    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="text", nullable=true)
     */
    private $mobile;

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
     * @var \DateTime
     * @ORM\Column(name="releaseDate", type="datetime", nullable=true)
     */
    private $releaseDate;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="deliveryDate", type="datetime", nullable=true)
     */
    private $deliveryDate;

    /**
     * @var string
     *
     * @ORM\Column(name="deliveryTime", type="string", length=20, nullable=true)
     */
    private $deliveryTime;

    /**
     * @var string
     *
     * @ORM\Column(name="deliveryDateTime", type="string",  length=50, nullable=true)
     */
    private $deliveryDateTime;

    /**
     * @var string
     *
     * @ORM\Column(name="printFor", type="string",  length=100, nullable=true)
     */
    private $printFor ='pathological';



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
     * @return InvoiceParticular
     */
    public function getInvoiceParticulars()
    {
        return $this->invoiceParticulars;
    }

    /**
     * @param InvoiceParticular $invoiceParticulars
     */
    public function setInvoiceParticulars($invoiceParticulars)
    {
        $this->invoiceParticulars = $invoiceParticulars;
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
     * @return string
     */
    public function getSubTotal()
    {
        return $this->subTotal;
    }

    /**
     * @param string $subTotal
     */
    public function setSubTotal($subTotal)
    {
        $this->subTotal = $subTotal;
    }

    /**
     * @return string
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param string $discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    /**
     * @return string
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * @param string $vat
     */
    public function setVat($vat)
    {
        $this->vat = $vat;
    }

    /**
     * @return string
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param string $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
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
     * @param PaymentCard $paymentCard
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
     * @return string
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @param string $payment
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;
    }


    /**
     * @return AccountSales
     */
    public function getAccountSales()
    {
        return $this->accountSales;
    }

    /**
     * @param AccountSales $accountSales
     */
    public function setAccountSales($accountSales)
    {
        $this->accountSales = $accountSales;
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
     * @return string
     */
    public function getDue()
    {
        return $this->due;
    }

    /**
     * @param string $due
     */
    public function setDue($due)
    {
        $this->due = $due;
    }

    /**
     * @return Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param Service $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }


    /**
     * @return int
     */
    public function getPercentage()
    {
        return $this->percentage;
    }

    /**
     * @param int $percentage
     */
    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;
    }



    /**
     * @return Particular
     */
    public function getAssignDoctor()
    {
        return $this->assignDoctor;
    }

    /**
     * @param Particular $assignDoctor
     */
    public function setAssignDoctor($assignDoctor)
    {
        $this->assignDoctor = $assignDoctor;
    }


    /**
     * @return boolean
     */
    public function getRevised()
    {
        return $this->revised;
    }

    /**
     * @param boolean $revised
     */
    public function setRevised($revised)
    {
        $this->revised = $revised;
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

    public function getDeliveryCount()
    {
        $count = 0;
        foreach ($this->getInvoiceParticulars() as $data ){

           /* @var $data InvoiceParticular */
           if($data->getParticularDeliveredBy()){
               $count++;
           }
        }
        return $count;
    }

    public function getReportCount()
    {
        $count = 0;
        foreach ($this->getInvoiceParticulars() as $data ){

            /* @var $data InvoiceParticular */
            if($data->getParticular()->getService()->getSlug() == 'diagnostic'){
                $count++;
            }
        }
        return $count;
    }

    /**
     * @return DmsReverse
     */
    public function getDmsReverse()
    {
        return $this->dmsReverse;
    }


    /**
     * @return DmsConfig
     */
    public function getDmsConfig()
    {
        return $this->dmsConfig;
    }

    /**
     * @param DmsConfig $dmsConfig
     */
    public function setDmsConfig($dmsConfig)
    {
        $this->dmsConfig = $dmsConfig;
    }

    /**
     * @return DmsDoctorInvoice
     */
    public function getDmsDoctorInvoices()
    {
        return $this->dmsDoctorInvoices;
    }

    /**
     * @return DmsTreatmentPlan
     */
    public function getDmsTreatmentPlans()
    {
        return $this->dmsTreatmentPlans;
    }


    /**
     * @return DmsInvoiceMedicine
     */
    public function getInvoiceMedicines()
    {
        return $this->invoiceMedicines;
    }


    /**
     * @return bool
     */
    public function isSendSms()
    {
        return $this->sendSms;
    }

    /**
     * @param bool $sendSms
     */
    public function setSendSms($sendSms)
    {
        $this->sendSms = $sendSms;
    }

    /**
     * @return DmsInvoiceAccessories
     */
    public function getDmsInvoiceAccessories()
    {
        return $this->dmsInvoiceAccessories;
    }


    /**
     * @return float
     */
    public function getEstimateTotal()
    {
        return $this->estimateTotal;
    }

    /**
     * @param float $estimateTotal
     */
    public function setEstimateTotal($estimateTotal)
    {
        $this->estimateTotal = $estimateTotal;
    }

    /**
     * @return MedicineDoctorPrescribe
     */
    public function getMedicineDoctorPrescribes()
    {
        return $this->medicineDoctorPrescribes;
    }

    /**
     * @param MedicineDoctorPrescribe $medicineDoctorPrescribes
     */
    public function setMedicineDoctorPrescribes($medicineDoctorPrescribes)
    {
        $this->medicineDoctorPrescribes = $medicineDoctorPrescribes;
    }

}

