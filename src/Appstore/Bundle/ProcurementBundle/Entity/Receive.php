<?php

namespace Appstore\Bundle\ProcurementBundle\Entity;


use Appstore\Bundle\AccountingBundle\Entity\AccountBank;
use Appstore\Bundle\AccountingBundle\Entity\AccountPurchase;
use Appstore\Bundle\AccountingBundle\Entity\AccountVendor;
use Appstore\Bundle\ProcurementBundle\Entity\PurchaseOrder;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Setting\Bundle\ToolBundle\Entity\TransactionMethod;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Purchase
 *
 * @ORM\Table(name="pro_receive")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\ProcurementBundle\Repository\ReceiveRepository")
 */
    class Receive
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
         * @ORM\OneToMany(targetEntity="Appstore\Bundle\ProcurementBundle\Entity\ReceiveItem", mappedBy="receive"  )
         **/
        private  $receiveItems;


         /**
         * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ProcurementBundle\Entity\PurchaseOrder")
         **/
        private  $purchaseOrder;


        /**
         * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountVendor", inversedBy="assetsPurchase" , cascade={"detach","merge"} )
         **/
        private  $vendor;


         /**
         * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountPurchase", mappedBy="assetsPurchase" , cascade={"detach","merge"} )
         **/
        private  $accountPurchase;


        /**
         * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountBank", inversedBy="accountPurchases" )
         **/
        private  $accountBank;

        /**
         * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountMobileBank", inversedBy="accountPurchases" )
         **/
        private  $accountMobileBank;

        /**
         * @ORM\ManyToOne(targetEntity="Setting\Bundle\ToolBundle\Entity\TransactionMethod", inversedBy="accountPurchases" )
         **/
        private  $transactionMethod;


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
         * @var float
         *
         * @ORM\Column(name="vat", type="float", nullable=true)
         */
        private $vat = 0.00;


         /**
         * @var float
         *
         * @ORM\Column(name="vatPercent", type="float", nullable=true)
         */
        private $vatPercent = 0.00;


        /**
         * @var float
         *
         * @ORM\Column(name="payment", type="float", nullable=true)
         */
        private $payment = 0;

        /**
         * @var string
         *
         * @ORM\Column(name="grn", type="string", length = 50, nullable=true)
         */
        private $grn;

        /**
         * @var string
         *
         * @ORM\Column(name="challanNo", type="string", length = 50, nullable=true)
         */
        private $challanNo;


        /**
         * @var string
         *
         * @ORM\Column(name="gatepass", type="string", length = 50, nullable=true)
         */
        private $gatepass;


        /**
         * @var string
         *
         * @ORM\Column(name="lcNo", type="string", length = 50, nullable=true)
         */
        private $lcNo;


         /**
         * @var string
         *
         * @ORM\Column(name="process", type="string", length = 50, nullable=true)
         */
        private $process = "Created";


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
         * @ORM\Column(type="string", length=255, nullable=true)
         */
        protected $path;

        /**
         * @Assert\File(maxSize="8388608")
         */
        protected $file;



        /**
         * @var \DateTime
         * @Gedmo\Timestampable(on="create")
         * @ORM\Column(name="receiveDate", type="datetime")
         */
        private $receiveDate;

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
         * @var string
         *
         * @ORM\Column(name="processType", type="string", length=50, nullable = true)
         */
        private $processType;


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
         * @return \DateTime
         */
        public function getReceiveDate()
        {
            return $this->receiveDate;
        }

        /**
         * @param \DateTime $receiveDate
         */
        public function setReceiveDate($receiveDate)
        {
            $this->receiveDate = $receiveDate;
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
         * Local
         * Foreign
         * Service
         * @return string
         */
        public function getProcessType()
        {
            return $this->processType;
        }

        /**
         * @param string $processType
         */
        public function setProcessType($processType)
        {
            $this->processType = $processType;
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
         * @return mixed
         */
        public function getAccountMobileBank()
        {
            return $this->accountMobileBank;
        }

        /**
         * @param mixed $accountMobileBank
         */
        public function setAccountMobileBank($accountMobileBank)
        {
            $this->accountMobileBank = $accountMobileBank;
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
         * @return string
         */
        public function getGrn()
        {
            return $this->grn;
        }

        /**
         * @param string $grn
         */
        public function setGrn($grn)
        {
            $this->grn = $grn;
        }

        /**
         * @return string
         */
        public function getChallanNo()
        {
            return $this->challanNo;
        }

        /**
         * @param string $challanNo
         */
        public function setChallanNo($challanNo)
        {
            $this->challanNo = $challanNo;
        }

        /**
         * @return string
         */
        public function getLcNo()
        {
            return $this->lcNo;
        }

        /**
         * @param string $lcNo
         */
        public function setLcNo($lcNo)
        {
            $this->lcNo = $lcNo;
        }


        /**
         * @return AccountPurchase
         */
        public function getAccountPurchase()
        {
            return $this->accountPurchase;
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
         * @return AccountVendor
         */
        public function getVendor()
        {
            return $this->vendor;
        }

        /**
         * @param AccountVendor $vendor
         */
        public function setVendor($vendor)
        {
            $this->vendor = $vendor;
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
         * Sets file.
         *
         * @param Item $file
         */
        public function setFile(UploadedFile $file = null)
        {
            $this->file = $file;
        }

        /**
         * Get file.
         *
         * @return Item
         */
        public function getFile()
        {
            return $this->file;
        }

        public function getAbsolutePath()
        {
            return null === $this->path
                ? null
                : $this->getUploadRootDir().'/'.$this->path;
        }

        public function getWebPath()
        {
            return null === $this->path
                ? null
                : $this->getUploadDir().'/'.$this->path;
        }

        /**
         * @ORM\PostRemove()
         */
        public function removeUpload()
        {
            if ($file = $this->getAbsolutePath()) {
                unlink($file);
            }
        }


        protected function getUploadRootDir()
        {
            return __DIR__ . '/../../../../../web/' .$this->getUploadDir();
        }

        protected function getUploadDir()
        {
            return 'uploads/domain/'.$this->getConfig()->getGlobalOption()->getId().'/assets/';
        }

        public function upload()
        {
            // the file property can be empty if the field is not required
            if (null === $this->getFile()) {
                return;
            }

            // use the original file name here but you should
            // sanitize it at least to avoid any security issues

            // move takes the target directory and then the
            // target filename to move to
            $filename = date('YmdHmi') . "_" . $this->getFile()->getClientOriginalName();
            $this->getFile()->move(
                $this->getUploadRootDir(),
                $filename
            );

            // set the path property to the filename where you've saved the file
            $this->path = $filename;

            // clean up the file property as you won't need it anymore
            $this->file = null;
        }

        /**
         * @return mixed
         */
        public function getReceiveItems()
        {
            return $this->receiveItems;
        }

        /**
         * @param mixed $receiveItems
         */
        public function setReceiveItems($receiveItems)
        {
            $this->receiveItems = $receiveItems;
        }

        /**
         * @return float
         */
        public function getVatPercent()
        {
            return $this->vatPercent;
        }

        /**
         * @param float $vatPercent
         */
        public function setVatPercent($vatPercent)
        {
            $this->vatPercent = $vatPercent;
        }

        /**
         * @return string
         */
        public function getGatepass()
        {
            return $this->gatepass;
        }

        /**
         * @param string $gatepass
         */
        public function setGatepass($gatepass)
        {
            $this->gatepass = $gatepass;
        }

        /**
         * @return mixed
         */
        public function getPath()
        {
            return $this->path;
        }

        /**
         * @param mixed $path
         */
        public function setPath($path)
        {
            $this->path = $path;
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





    }

