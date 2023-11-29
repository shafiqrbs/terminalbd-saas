<?php

namespace Appstore\Bundle\AssetsBundle\Entity;

use Appstore\Bundle\AccountingBundle\Entity\AccountVendor;
use Appstore\Bundle\DomainUserBundle\Entity\Branches;
use Core\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * StockItem
 *
 * @ORM\Table("assets_stock")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\AssetsBundle\Repository\StockItemRepository")
 */
class StockItem
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\AssetsConfig", inversedBy="stockItems" )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    protected  $config;


    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Item", inversedBy="stockItems")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $item;


    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Sales", inversedBy="stockItems")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $sales;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Particular", inversedBy="stockItems")
     */
    protected $branch;


    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Damage", inversedBy="stockItems" )
     **/
    private  $damage;


    /**
     * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User", inversedBy="stockItems")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $createdBy;


    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountVendor", inversedBy="stockItems")
     */
    protected $vendor;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Brand", inversedBy="stockItems")
     */
    protected $brand;


    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Category", inversedBy="stockItems")
     **/
    private  $category;


    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", nullable = true)
     */
    private $price = 0;


     /**
     * @var float
     *
     * @ORM\Column(name="purchasePrice", type="float", nullable = true)
     */
    private $purchasePrice = 0;


    /**
     * @var float
     *
     * @ORM\Column(name="salesPrice", type="float", nullable = true)
     */
    private $salesPrice = 0;


     /**
     * @var float
     *
     * @ORM\Column(name="actualPrice", type="float", nullable = true)
     */
    private $actualPrice = 0;


    /**
     * @var float
     *
     * @ORM\Column(name="customsDuty", type="float", nullable=true)
     */
    private $customsDuty = 0.00;


    /**
     * @var float
     *
     * @ORM\Column(name="customsDutyPercent", type="float", nullable=true)
     */
    private $customsDutyPercent = 0.00;


    /**
     * @var float
     *
     * @ORM\Column(name="supplementaryDuty", type="float", nullable=true)
     */
    private $supplementaryDuty = 0.00;

    /**
     * @var float
     *
     * @ORM\Column(name="supplementaryDutyPercent", type="float", nullable=true)
     */
    private $supplementaryDutyPercent = 0.00;

    /**
     * @var float
     *
     * @ORM\Column(name="valueAddedTax", type="float", nullable=true)
     */
    private $valueAddedTax = 0.00;


    /**
     * @var float
     *
     * @ORM\Column(name="valueAddedTaxPercent", type="float", nullable=true)
     */
    private $valueAddedTaxPercent = 0.00;


    /**
     * @var float
     *
     * @ORM\Column(name="advanceIncomeTax", type="float", nullable=true)
     */
    private $advanceIncomeTax = 0.00;


    /**
     * @var float
     *
     * @ORM\Column(name="advanceIncomeTaxPercent", type="float", nullable=true)
     */
    private $advanceIncomeTaxPercent = 0.00;


    /**
     * @var float
     *
     * @ORM\Column(name="recurringDeposit", type="float", nullable=true)
     */
    private $recurringDeposit = 0.00;

    /**
     * @var float
     *
     * @ORM\Column(name="recurringDepositPercent", type="float", nullable=true)
     */
    private $recurringDepositPercent = 0.00;


    /**
     * @var float
     *
     * @ORM\Column(name="advanceTradeVat", type="float", nullable=true)
     */
    private $advanceTradeVat = 0.00;


    /**
     * @var float
     *
     * @ORM\Column(name="advanceTradeVatPercent", type="float", nullable=true)
     */
    private $advanceTradeVatPercent = 0.00;

    /**
     * @var float
     *
     * @ORM\Column(name="rebatePercent", type="float", nullable=true)
     */
    private $rebatePercent = 0.00;


     /**
     * @var float
     *
     * @ORM\Column(name="rebate", type="float", nullable=true)
     */
    private $rebate = 0.00;


    /**
     * @var float
     *
     * @ORM\Column(name="totalTaxIncidence", type="float", nullable=true)
     */
    private $totalTaxIncidence = 0.00;


    /**
     * @var float
     *
     * @ORM\Column(name="subTotal", type="float", nullable = true)
     */
    private $subTotal = 0;


     /**
     * @var float
     *
     * @ORM\Column(name="total", type="float", nullable = true)
     */
    private $total = 0;


    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer", nullable = true)
     */
    private $quantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="openingQuantity", type="integer", nullable = true)
     */
    private $openingQuantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="purchaseQuantity", type="integer", nullable = true)
     */
    private $purchaseQuantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="purchaseReturnQuantity", type="integer", nullable = true)
     */
    private $purchaseReturnQuantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="salesQuantity", type="integer", nullable = true)
     */
    private $salesQuantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="salesReturnQuantity", type="integer", nullable = true)
     */
    private $salesReturnQuantity;


    /**
     * @var integer
     *
     * @ORM\Column(name="assetsQuantity", type="integer", nullable = true)
     */
    private $assetsQuantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="assetsReturnQuantity", type="integer", nullable = true)
     */
    private $assetsReturnQuantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="damageQuantity", type="integer", nullable = true)
     */
    private $damageQuantity;



    /**
     * @var string
     *
     * @ORM\Column(name="process", type="string", nullable = true)
     */
    private $process;

     /**
     * @var string
     *
     * @ORM\Column(name="mode", type="string", length = 40, nullable = true)
     */
    private $mode;

    /**
     * @var string
     *
     * @ORM\Column(name="serialNo", type="text", length=255, nullable = true)
     */
    private $serialNo;

    /**
     * @var string
     *
     * @ORM\Column(name="assuranceType", type="string", length=50, nullable = true)
     */
    private $assuranceType;

    /**
     * @var string
     *
     * @ORM\Column(name="assuranceFromVendor", type="string", length=100, nullable = true)
     */
    private $assuranceFromVendor;

    /**
     * @var string
     *
     * @ORM\Column(name="assuranceToCustomer", type="string", length=100, nullable = true)
     */
    private $assuranceToCustomer;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="expiredDate", type="datetime", nullable=true)
     */
    private $expiredDate;



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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return StockItem
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
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
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param mixed $item
     */
    public function setItem($item)
    {
        $this->item = $item;
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
     * @param int $process
     * purchase         = 1
     * purchase return  = 2
     * sales            = 3
     * sales return     = 4
     * reject           = 5
     * damage          = 6
     */


    public function setProcess($process)
    {
        $this->process = $process;
    }

    /**
     * @return mixed
     */
    public function getDamage()
    {
        return $this->damage;
    }

    /**
     * @param mixed $damage
     */
    public function setDamage($damage)
    {
        $this->damage = $damage;
    }


    /**
     * @return mixed
     */
    public function getSales()
    {
        return $this->sales;
    }

    /**
     * @param mixed $sales
     */
    public function setSales($sales)
    {
        $this->sales = $sales;
    }

    /**
     * @return float
     */
    public function getPurchasePrice()
    {
        return $this->purchasePrice;
    }

    /**
     * @param float $purchasePrice
     */
    public function setPurchasePrice($purchasePrice)
    {
        $this->purchasePrice = $purchasePrice;
    }

    /**
     * @return float
     */
    public function getSalesPrice()
    {
        return $this->salesPrice;
    }

    /**
     * @param float $salesPrice
     */
    public function setSalesPrice($salesPrice)
    {
        $this->salesPrice = $salesPrice;
    }

    /**
     * @return float
     */
    public function getActualPrice()
    {
        return $this->actualPrice;
    }

    /**
     * @param float $actualPrice
     */
    public function setActualPrice($actualPrice)
    {
        $this->actualPrice = $actualPrice;
    }

    /**
     * @return float
     */
    public function getCustomsDuty()
    {
        return $this->customsDuty;
    }

    /**
     * @param float $customsDuty
     */
    public function setCustomsDuty($customsDuty)
    {
        $this->customsDuty = $customsDuty;
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
    public function getAdvanceIncomeTax()
    {
        return $this->advanceIncomeTax;
    }

    /**
     * @param float $advanceIncomeTax
     */
    public function setAdvanceIncomeTax($advanceIncomeTax)
    {
        $this->advanceIncomeTax = $advanceIncomeTax;
    }

    /**
     * @return float
     */
    public function getRecurringDeposit()
    {
        return $this->recurringDeposit;
    }

    /**
     * @param float $recurringDeposit
     */
    public function setRecurringDeposit($recurringDeposit)
    {
        $this->recurringDeposit = $recurringDeposit;
    }

    /**
     * @return float
     */
    public function getAdvanceTradeVat()
    {
        return $this->advanceTradeVat;
    }

    /**
     * @param float $advanceTradeVat
     */
    public function setAdvanceTradeVat($advanceTradeVat)
    {
        $this->advanceTradeVat = $advanceTradeVat;
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
     * @return float
     */
    public function getAdvanceTradeVatPercent()
    {
        return $this->advanceTradeVatPercent;
    }

    /**
     * @param float $advanceTradeVatPercent
     */
    public function setAdvanceTradeVatPercent($advanceTradeVatPercent)
    {
        $this->advanceTradeVatPercent = $advanceTradeVatPercent;
    }

    /**
     * @return float
     */
    public function getRecurringDepositPercent()
    {
        return $this->recurringDepositPercent;
    }

    /**
     * @param float $recurringDepositPercent
     */
    public function setRecurringDepositPercent($recurringDepositPercent)
    {
        $this->recurringDepositPercent = $recurringDepositPercent;
    }

    /**
     * @return float
     */
    public function getAdvanceIncomeTaxPercent()
    {
        return $this->advanceIncomeTaxPercent;
    }

    /**
     * @param float $advanceIncomeTaxPercent
     */
    public function setAdvanceIncomeTaxPercent($advanceIncomeTaxPercent)
    {
        $this->advanceIncomeTaxPercent = $advanceIncomeTaxPercent;
    }

    /**
     * @return float
     */
    public function getValueAddedTaxPercent()
    {
        return $this->valueAddedTaxPercent;
    }

    /**
     * @param float $valueAddedTaxPercent
     */
    public function setValueAddedTaxPercent($valueAddedTaxPercent)
    {
        $this->valueAddedTaxPercent = $valueAddedTaxPercent;
    }

    /**
     * @return float
     */
    public function getSupplementaryDutyPercent()
    {
        return $this->supplementaryDutyPercent;
    }

    /**
     * @param float $supplementaryDutyPercent
     */
    public function setSupplementaryDutyPercent($supplementaryDutyPercent)
    {
        $this->supplementaryDutyPercent = $supplementaryDutyPercent;
    }

    /**
     * @return float
     */
    public function getCustomsDutyPercent()
    {
        return $this->customsDutyPercent;
    }

    /**
     * @param float $customsDutyPercent
     */
    public function setCustomsDutyPercent($customsDutyPercent)
    {
        $this->customsDutyPercent = $customsDutyPercent;
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
    public function getSerialNo()
    {
        return $this->serialNo;
    }

    /**
     * @param string $serialNo
     */
    public function setSerialNo($serialNo)
    {
        $this->serialNo = $serialNo;
    }

    /**
     * @return string
     */
    public function getAssuranceType()
    {
        return $this->assuranceType;
    }

    /**
     * @param string $assuranceType
     */
    public function setAssuranceType($assuranceType)
    {
        $this->assuranceType = $assuranceType;
    }

    /**
     * @return string
     */
    public function getAssuranceFromVendor()
    {
        return $this->assuranceFromVendor;
    }

    /**
     * @param string $assuranceFromVendor
     */
    public function setAssuranceFromVendor($assuranceFromVendor)
    {
        $this->assuranceFromVendor = $assuranceFromVendor;
    }

    /**
     * @return string
     */
    public function getAssuranceToCustomer()
    {
        return $this->assuranceToCustomer;
    }

    /**
     * @param string $assuranceToCustomer
     */
    public function setAssuranceToCustomer($assuranceToCustomer)
    {
        $this->assuranceToCustomer = $assuranceToCustomer;
    }

    /**
     * @return mixed
     */
    public function getExpiredDate()
    {
        return $this->expiredDate;
    }

    /**
     * @param mixed $expiredDate
     */
    public function setExpiredDate($expiredDate)
    {
        $this->expiredDate = $expiredDate;
    }

    /**
     * @return Branches
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * @param Branches $branch
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;
    }

    /**
     * @return int
     */
    public function getOpeningQuantity()
    {
        return $this->openingQuantity;
    }

    /**
     * @param int $openingQuantity
     */
    public function setOpeningQuantity($openingQuantity)
    {
        $this->openingQuantity = $openingQuantity;
    }

    /**
     * @return int
     */
    public function getPurchaseQuantity()
    {
        return $this->purchaseQuantity;
    }

    /**
     * @param int $purchaseQuantity
     */
    public function setPurchaseQuantity($purchaseQuantity)
    {
        $this->purchaseQuantity = $purchaseQuantity;
    }

    /**
     * @return int
     */
    public function getPurchaseReturnQuantity()
    {
        return $this->purchaseReturnQuantity;
    }

    /**
     * @param int $purchaseReturnQuantity
     */
    public function setPurchaseReturnQuantity($purchaseReturnQuantity)
    {
        $this->purchaseReturnQuantity = $purchaseReturnQuantity;
    }

    /**
     * @return int
     */
    public function getSalesQuantity()
    {
        return $this->salesQuantity;
    }

    /**
     * @param int $salesQuantity
     */
    public function setSalesQuantity($salesQuantity)
    {
        $this->salesQuantity = $salesQuantity;
    }

    /**
     * @return int
     */
    public function getSalesReturnQuantity()
    {
        return $this->salesReturnQuantity;
    }

    /**
     * @param int $salesReturnQuantity
     */
    public function setSalesReturnQuantity($salesReturnQuantity)
    {
        $this->salesReturnQuantity = $salesReturnQuantity;
    }

    /**
     * @return int
     */
    public function getAssetsQuantity()
    {
        return $this->assetsQuantity;
    }

    /**
     * @param int $assetsQuantity
     */
    public function setAssetsQuantity($assetsQuantity)
    {
        $this->assetsQuantity = $assetsQuantity;
    }

    /**
     * @return int
     */
    public function getAssetsReturnQuantity()
    {
        return $this->assetsReturnQuantity;
    }

    /**
     * @param int $assetsReturnQuantity
     */
    public function setAssetsReturnQuantity($assetsReturnQuantity)
    {
        $this->assetsReturnQuantity = $assetsReturnQuantity;
    }

    /**
     * @return int
     */
    public function getDamageQuantity()
    {
        return $this->damageQuantity;
    }

    /**
     * @param int $damageQuantity
     */
    public function setDamageQuantity($damageQuantity)
    {
        $this->damageQuantity = $damageQuantity;
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
     * @return Brand
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param Brand $brand
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
    }

    /**
     * @return TallyConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param TallyConfig $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
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
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return TaxTariff
     */
    public function getHsCode()
    {
        return $this->hsCode;
    }

    /**
     * @param TaxTariff $hsCode
     */
    public function setHsCode($hsCode)
    {
        $this->hsCode = $hsCode;
    }

    /**
     * @return mixed
     */
    public function getPurchaseStockReturn()
    {
        return $this->purchaseStockReturn;
    }

    /**
     * @param mixed $purchaseStockReturn
     */
    public function setPurchaseStockReturn($purchaseStockReturn)
    {
        $this->purchaseStockReturn = $purchaseStockReturn;
    }

    /**
     * @return mixed
     */
    public function getSalesStockReturn()
    {
        return $this->salesStockReturn;
    }

    /**
     * @param mixed $salesStockReturn
     */
    public function setSalesStockReturn($salesStockReturn)
    {
        $this->salesStockReturn = $salesStockReturn;
    }

    /**
     * @return mixed
     */
    public function getAssetsStockReturn()
    {
        return $this->assetsStockReturn;
    }

    /**
     * @param mixed $assetsStockReturn
     */
    public function setAssetsStockReturn($assetsStockReturn)
    {
        $this->assetsStockReturn = $assetsStockReturn;
    }

    /**
     * @return Purchase
     */
    public function getPurchase()
    {
        return $this->purchase;
    }

    /**
     * @param Purchase $purchase
     */
    public function setPurchase($purchase)
    {
        $this->purchase = $purchase;
    }

    /**
     * @return float
     */
    public function getRebatePercent()
    {
        return $this->rebatePercent;
    }

    /**
     * @param float $rebatePercent
     */
    public function setRebatePercent($rebatePercent)
    {
        $this->rebatePercent = $rebatePercent;
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
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param string $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * @return PurchaseReturn
     */
    public function getPurchaseReturn()
    {
        return $this->purchaseReturn;
    }

    /**
     * @param PurchaseReturn $purchaseReturn
     */
    public function setPurchaseReturn($purchaseReturn)
    {
        $this->purchaseReturn = $purchaseReturn;
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



}

