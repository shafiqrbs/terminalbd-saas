<?php

namespace Appstore\Bundle\AssetsBundle\Entity;


use Appstore\Bundle\AccountingBundle\Entity\AccountBank;
use Appstore\Bundle\AccountingBundle\Entity\AccountPurchase;
use Appstore\Bundle\AccountingBundle\Entity\AccountVendor;
use Appstore\Bundle\ProcurementBundle\Entity\PurchaseOrder;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Setting\Bundle\ToolBundle\Entity\TransactionMethod;

/**
 * Purchase
 *
 * @ORM\Table(name="assets_stock_issue")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\AssetsBundle\Repository\PurchaseRepository")
 */
    class StockIssue
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
         * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\AssetsConfig")
         * @ORM\JoinColumn(onDelete="CASCADE")
         **/
        private $config;

        /**
         * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\StockIssueItem", mappedBy="stockIssue"  )
         **/
        private  $stockIssueItems;

        /**
         * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Particular")
         **/
        private  $department;


        /**
         * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User")
         **/
        private  $receiveUser;


        /**
         * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\OfficeNote")
         **/
        private  $officeNotes;


        /**
         * @Gedmo\Blameable(on="create")
         * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User")
         **/
        private  $createdBy;


        /**
         * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User")
         **/
        private  $approvedBy;


        /**
         * @var float
         *
         * @ORM\Column(name="subTotal", type="float", nullable=true)
         */
        private $subTotal = 0;

        /**
         * @var float
         *
         * @ORM\Column(name="netTotal", type="float", nullable=true)
         */
        private $netTotal = 0;


         /**
         * @var string
         *
         * @ORM\Column(name="process", type="string", length = 50, nullable=true)
         */
        private $process = "process";


         /**
         * @var string
         *
         * @ORM\Column(name="invoice", type="string", length = 50, nullable=true)
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
         * @ORM\Column(name="remark", type="text", nullable = true)
         */
        private $remark;


        /**
         * @var \DateTime
         * @Gedmo\Timestampable(on="create")
         * @ORM\Column(name="receiveDate", type="datetime")
         */
        private $deliveryDate;

        /**
         * @var \DateTime
         * @Gedmo\Timestampable(on="create")
         * @ORM\Column(name="created", type="datetime")
         */
        private $created;

        /**
         * @var \DateTime
         * @ORM\Column(name="updated", type="datetime", nullable = true)
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
         * @param float $payment
         */
        public function setPayment($payment)
        {
            $this->payment = $payment;
        }



        /**
         * @return int
         */
        public function getCode()
        {
            return $this->code;
        }

        /**
         * @param int $code
         */
        public function setCode($code)
        {
            $this->code = $code;
        }



        /**
         * @return string
         */
        public function getRemark()
        {
            return $this->remark;
        }

        /**
         * @param string $remark
         */
        public function setRemark($remark)
        {
            $this->remark = $remark;
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
         * @param string $lcNo
         */
        public function setLcNo($lcNo)
        {
            $this->lcNo = $lcNo;
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
         * @return StockItem
         */
        public function getStockItems()
        {
            return $this->stockItems;
        }

        /**
         * @return mixed
         */
        public function getOfficeNotes()
        {
            return $this->officeNotes;
        }

        /**
         * @param mixed $officeNotes
         */
        public function setOfficeNotes($officeNotes)
        {
            $this->officeNotes = $officeNotes;
        }

        /**
         * @return mixed
         */
        public function getDepartment()
        {
            return $this->department;
        }

        /**
         * @param mixed $department
         */
        public function setDepartment($department)
        {
            $this->department = $department;
        }

        /**
         * @return mixed
         */
        public function getReceiveUser()
        {
            return $this->receiveUser;
        }

        /**
         * @param mixed $receiveUser
         */
        public function setReceiveUser($receiveUser)
        {
            $this->receiveUser = $receiveUser;
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
         * @return \DateTime
         */
        public function getDeliveryDate()
        {
            return $this->deliveryDate;
        }

        /**
         * @param \DateTime $deliveryDate
         */
        public function setDeliveryDate($deliveryDate)
        {
            $this->deliveryDate = $deliveryDate;
        }

        /**
         * @return mixed
         */
        public function getStockIssueItems()
        {
            return $this->stockIssueItems;
        }





    }

