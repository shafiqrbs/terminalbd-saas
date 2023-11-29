<?php

namespace Appstore\Bundle\InventoryBundle\Entity;

use Appstore\Bundle\AccountingBundle\Entity\AccountBank;
use Appstore\Bundle\AccountingBundle\Entity\AccountBkash;
use Appstore\Bundle\AccountingBundle\Entity\AccountMobileBank;
use Appstore\Bundle\DomainUserBundle\Entity\Customer;
use Core\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Setting\Bundle\ToolBundle\Entity\Bank;
use Setting\Bundle\ToolBundle\Entity\TransactionMethod;

/**
 * ServiceSales
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Appstore\Bundle\InventoryBundle\Repository\ServiceSalesRepository")
 */
class ServiceSales
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
         * @ORM\ManyToOne(targetEntity="Appstore\Bundle\InventoryBundle\Entity\InventoryConfig", inversedBy="serviceSales" )
         * @ORM\JoinColumn(onDelete="CASCADE")
         **/
        private  $inventoryConfig;

        /**
         * @ORM\OneToMany(targetEntity="Appstore\Bundle\InventoryBundle\Entity\ServiceSalesItem", mappedBy="serviceSales"  , cascade={"remove"} )
         * @ORM\OrderBy({"id" = "DESC"})
         **/
        private  $serviceSalesItems;

        /**
         * @ORM\ManyToOne(targetEntity="Appstore\Bundle\DomainUserBundle\Entity\Customer", inversedBy="serviceSales"  )
         **/
        private  $customer;

        /**
         * @Gedmo\Blameable(on="create")
         * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User", inversedBy="serviceSales" )
         **/
        private  $createdBy;

        /**
         * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User", inversedBy="serviceSalesBy" )
         **/
        private  $assignTo;

        /**
         * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User", inversedBy="serviceApprovedBy" )
         **/
        private  $approvedBy;


        /**
         * @ORM\ManyToOne(targetEntity="Setting\Bundle\ToolBundle\Entity\TransactionMethod", inversedBy="serviceSales" )
         **/
        private  $transactionMethod;


        /**
         * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountBank", inversedBy="serviceSales" )
         **/
        private  $accountBank;


        /**
         * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountMobileBank", inversedBy="serviceSales" )
         **/
        private  $accountMobileBank;


        /**
         * @ORM\ManyToOne(targetEntity="Setting\Bundle\ToolBundle\Entity\paymentCard", inversedBy="serviceSales" )
         **/
        private  $paymentCard;

        /**
         * @var string
         *
         * @ORM\Column(name="chequeCardNo", type="string", length=100, nullable=true)
         */
        private $chequeCardNo;


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
         * @var string
         *
         * @ORM\Column(name="subTotal", type="decimal", nullable=true)
         */
        private $subTotal;

        /**
         * @var string
         *
         * @ORM\Column(name="discount", type="decimal", nullable=true)
         */
        private $discount;

        /**
         * @var string
         *
         * @ORM\Column(name="vat", type="decimal", nullable=true)
         */
        private $vat;

        /**
         * @var string
         *
         * @ORM\Column(name="total", type="decimal", nullable=true)
         */
        private $total;


        /**
         * @var string
         *
         * @ORM\Column(name="payment", type="decimal", nullable=true)
         */
        private $payment;


        /**
         * @var string
         *
         * @ORM\Column(name="comment", type="text", nullable=true)
         */
        private $comment;

        /**
         * @var string
         *
         * @ORM\Column(name="due", type="decimal", nullable=true)
         */
        private $due;


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
         * @return InventoryConfig
         */
        public function getInventoryConfig()
        {
         return $this->inventoryConfig;
        }

        /**
         * @param InventoryConfig $inventoryConfig
         */
        public function setInventoryConfig($inventoryConfig)
        {
         $this->inventoryConfig = $inventoryConfig;
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
        public function getChequeCardNo()
        {
         return $this->chequeCardNo;
        }

        /**
         * @param string $chequeCardNo
         */
        public function setChequeCardNo($chequeCardNo)
        {
         $this->chequeCardNo = $chequeCardNo;
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
         * @return ServiceSalesItem
         */
        public function getServiceSalesItems()
        {
                return $this->serviceSalesItems;
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
         * @return User
         */
        public function getAssignTo()
        {
                return $this->assignTo;
        }

        /**
         * @param User $assignTo
         */
        public function setAssignTo($assignTo)
        {
                $this->assignTo = $assignTo;
        }

}

