<?php

namespace Appstore\Bundle\AssetsBundle\Entity;

use Appstore\Bundle\AccountingBundle\Entity\AccountHead;
use Appstore\Bundle\AccountingBundle\Entity\AccountVendor;
use Appstore\Bundle\AssetsBundle\Entity\DepreciationModel;
use Appstore\Bundle\DomainUserBundle\Entity\Branches;
use Appstore\Bundle\ProcurementBundle\Entity\PurchaseOrderItem;
use Appstore\Bundle\ProcurementBundle\Entity\PurchaseRequisitionItem;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Setting\Bundle\LocationBundle\Entity\Country;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;
use Setting\Bundle\ToolBundle\Entity\ItemWarning;
use Setting\Bundle\ToolBundle\Entity\ProductUnit;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product
 *
 * @ORM\Table("assets_item")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\AssetsBundle\Repository\ItemRepository")
 */
class Item
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
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\AssetsConfig", inversedBy="items" )
	 **/
	private  $config;


    /**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\StockItem", mappedBy="item" )
	 **/
	private  $stockItems;


     /**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Damage", mappedBy="item" )
	 **/
	private  $damages;


     /**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Product", mappedBy="item" )
	 **/
	private  $products;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\ProductLedger", mappedBy="item")
     **/
    protected $ledgers;


    /**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\ItemMetaAttribute", mappedBy="item" )
	 **/
	private  $itemMetaAttributes;

    /**
	 * @ORM\OneToOne(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountHead", mappedBy="assetsItem" )
	 **/
	private  $accountHead;


    /**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Particular")
	 **/
	private  $productGroup;


    /**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Category", inversedBy="items" )
	 **/
	private  $category;

    /**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\ProcurementBundle\Entity\PurchaseRequisitionItem", mappedBy="item" )
	 **/
	private  $purchaseRequisitionItems;


	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\ProcurementBundle\Entity\PurchaseOrderItem", mappedBy="item" )
	 **/
	private  $purchaseOrderItems;


	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Brand", inversedBy="items" )
	 **/
	private  $brand;

    /**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\DepreciationModel", mappedBy="item" )
	 **/
	private  $depreciationModel;

    /**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountVendor", inversedBy="items" )
	 **/
	private  $vendor;

    /**
     * @ORM\ManyToOne(targetEntity="Setting\Bundle\ToolBundle\Entity\ProductUnit", inversedBy="assetsItems" )
     **/
    private  $productUnit;


    /**
     * @ORM\ManyToOne(targetEntity="Setting\Bundle\ToolBundle\Entity\ItemWarning", inversedBy="item")
     * @ORM\OrderBy({"sorting" = "ASC"})
     **/
    private  $itemWarning;


    /**
     * @ORM\ManyToOne(targetEntity="Setting\Bundle\LocationBundle\Entity\Country", inversedBy="assetsItems")
     */
    protected $country;


    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;


     /**
     * @var string
     *
     * @ORM\Column(name="vatName", type="text", nullable=true)
     */
    private $vatName;


      /**
     * @var string
     *
     * @ORM\Column(name="skuName", type="text", nullable=true)
     */
    private $skuName;


     /**
     * @var string
     *
     * @ORM\Column(name="model", type="text", nullable=true)
     */
    private $model;


      /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;


    /**
     * @var string
     *
     * @ORM\Column(name="origin", type="text", nullable=true)
     */
    private $origin;



     /**
     * @var string
     *
     * @ORM\Column(name="caliber", type="text", nullable=true)
     */
    private $caliber;


    /**
     * @var string
     *
     * @ORM\Column(name="assuranceType", type="string", length=100, nullable=true)
     */
    private $assuranceType;

    /**
     * @var string
     *
     * @ORM\Column(name="productType", type="string", length=100, nullable=true)
     */
    private $productType;

     /**
     * @var string
     *
     * @ORM\Column(name="warningLabel", type="string", length=100, nullable=true)
     */
    private $warningLabel;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="sku", type="string", length=50,nullable = true)
     */
    private $sku;

     /**
     * @var string
     *
     * @ORM\Column(name="manufacture", type="string", length=100,nullable = true)
     */
    private $manufacture;

    /**
     * @Gedmo\Slug(fields={"name"}, updatable=false, separator="-")
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;

    /**
     * @var integer
     *
     * @ORM\Column(name="code", type="integer", length=50)
     */
    private $code;


    /**
	 * @var integer
	 *
	 * @ORM\Column(name="quantity", type="integer", nullable=true)
	 */
	private $quantity;

	 /**
	 * @var float
	 *
	 * @ORM\Column(name="price", type="float", nullable=true)
	 */
	private $price;


	 /**
	 * @var float
	 *
	 * @ORM\Column(name="unitPrice", type="float", nullable=true)
	 */
	private $unitPrice;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="purchasePrice", type="float", nullable=true)
	 */
	private $purchasePrice;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="salesPrice", type="float", nullable=true)
	 */
	private $salesPrice;

    /**
	 * @var float
	 *
	 * @ORM\Column(name="vatPercentage", type="float", nullable=true)
	 */
	private $vatPercentage;


    /**
	 * @var float
	 *
	 * @ORM\Column(name="openingQuantity", type="integer", nullable=true)
	 */
	private $openingQuantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="minQuantity", type="integer", nullable=true)
     */
    private $minQuantity = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="maxQuantity", type="integer", nullable=true)
     */
    private $maxQuantity;

     /**
     * @var integer
     *
     * @ORM\Column(name="progressQuantity", type="integer", nullable=true)
     */
    private $progressQuantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="orderCreateItem", type="integer", nullable=true)
     */
    private $orderCreateItem;

    /**
     * @var integer
     *
     * @ORM\Column(name="remainingQuantity", type="integer", nullable=true)
     */
    private $remainingQuantity = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="purchaseQuantity", type="integer", nullable=true)
     */
    private $purchaseQuantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="purchaseReturnQuantity", type="integer", nullable=true)
     */
    private $purchaseReturnQuantity=0;

    /**
     * @var integer
     *
     * @ORM\Column(name="salesQuantity", type="integer", nullable=true)
     */
    private $salesQuantity = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="salesReturnQuantity", type="integer", nullable=true)
     */
    private $salesReturnQuantity = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="damageQuantity", type="integer", nullable=true)
     */
    private $damageQuantity = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="disposalQuantity", type="integer", nullable=true)
     */
    private $disposalQuantity = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="assetsQuantity", type="integer", nullable=true)
     */
    private $assetsQuantity = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="assetsReturnQuantity", type="integer", nullable=true)
     */
    private $assetsReturnQuantity = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="barcode", type="string", nullable=true)
     */
    private $barcode;


    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status = true;

    /**
     * @var string
     *
     * @ORM\Column(name="itemPrefix", type="string", length=10, nullable=true)
     */
    private $itemPrefix;

    /**
     * @var string
     *
     * @ORM\Column(name="serialGeneration", type="string", length=50)
     */
    private $serialGeneration = 'auto';

    /**
     * @var integer
     *
     * @ORM\Column(name="serialFormat", type="smallint", length=5)
     */
    private $serialFormat = 2;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;

    /**
     * @Assert\File(maxSize="8388608")
     */
    protected $file;



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
     * Set name
     *
     * @param string $name
     *
     * @return Item
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Item
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Product
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }



	/**
	 * @return Branches
	 */
	public function getBranch() {
		return $this->branch;
	}

	/**
	 * @param Branches $branch
	 */
	public function setBranch( $branch ) {
		$this->branch = $branch;
	}

	/**
	 * @return Category
	 */
	public function getCategory() {
		return $this->category;
	}

	/**
	 * @param Category $category
	 */
	public function setCategory( $category ) {
		$this->category = $category;
	}



	/**
	 * @return float
	 */
	public function getQuantity() {
		return $this->quantity;
	}

	/**
	 * @param float $quantity
	 */
	public function setQuantity( $quantity ) {
		$this->quantity = $quantity;
	}

	/**
	 * @return string
	 */
	public function getAssuranceType() {
		return $this->assuranceType;
	}

	/**
	 * @param string $assuranceType
	 */
	public function setAssuranceType( $assuranceType ) {
		$this->assuranceType = $assuranceType;
	}


	/**
	 * @return float
	 */
	public function getPurchasePrice() {
		return $this->purchasePrice;
	}

	/**
	 * @param float $purchasePrice
	 */
	public function setPurchasePrice( $purchasePrice ) {
		$this->purchasePrice = $purchasePrice;
	}


	/**
	 * @param float $salvageValue
	 */
	public function setSalvageValue( $salvageValue ) {
		$this->salvageValue = $salvageValue;
	}


	/**
	 * @return AccountVendor
	 */
	public function getVendor() {
		return $this->vendor;
	}

	/**
	 * @param AccountVendor $vendor
	 */
	public function setVendor( $vendor ) {
		$this->vendor = $vendor;
	}

	/**
	 * @return int
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * @param int $code
	 */
	public function setCode( $code ) {
		$this->code = $code;
	}

	/**
	 * @return Distribution
	 */
	public function getDistributions() {
		return $this->distributions;
	}

	public function productItemWithPrice()
	{
		return $product = $this->getSku().' - '.$this->getName().' - BDT. '.$this->getPrice();
	}

	public function productItem()
	{
		return $product = $this->getItem()->getName().' ('.$this->getName().')';
	}

	public function productItemSerial()
	{
		return $product = $this->getItem()->getName().' ('.$this->getName().') - '.$this->getSerialNo() .'=> BDT.'.$this->getBookValue();
	}



    /**
     * @return string
     */
    public function getProductType()
    {
        return $this->productType;
    }

    /**
     * @param string $productType
     */
    public function setProductType($productType)
    {
        $this->productType = $productType;
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
        return __DIR__.'/../../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'uploads/domain/'.$this->getConfig()->getGlobalOption()->getId().'/assets/product-group/';
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
     * @return ItemMetaAttribute
     */
    public function getItemMetaAttributes()
    {
        return $this->itemMetaAttributes;
    }

    /**
     * @return ItemKeyValue
     */
    public function getItemKeyValues()
    {
        return $this->itemKeyValues;
    }

    /**
     * @return
     */
    public function getItemWarning()
    {
        return $this->itemWarning;
    }

    /**
     * @param ItemWarning $itemWarning
     */
    public function setItemWarning($itemWarning)
    {
        $this->itemWarning = $itemWarning;
    }

    /**
     * @return mixed
     */
    public function getItemPrefix()
    {
        return $this->itemPrefix;
    }

    /**
     * @param mixed $itemPrefix
     */
    public function setItemPrefix($itemPrefix)
    {
        $this->itemPrefix = $itemPrefix;
    }

    /**
     * @return string
     */
    public function getSerialGeneration()
    {
        return $this->serialGeneration;
    }

    /**
     * @param string $serialGeneration
     */
    public function setSerialGeneration($serialGeneration)
    {
        $this->serialGeneration = $serialGeneration;
    }

    /**
     * @return mixed
     */
    public function getSerialFormat()
    {
        return $this->serialFormat;
    }

    /**
     * @param mixed $serialFormat
     */
    public function setSerialFormat($serialFormat)
    {
        $this->serialFormat = $serialFormat;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * @return float
     */
    public function getOpeningQuantity()
    {
        return $this->openingQuantity;
    }

    /**
     * @param float $openingQuantity
     */
    public function setOpeningQuantity($openingQuantity)
    {
        $this->openingQuantity = $openingQuantity;
    }

    /**
     * @return int
     */
    public function getMinQuantity()
    {
        return $this->minQuantity;
    }

    /**
     * @param int $minQuantity
     */
    public function setMinQuantity($minQuantity)
    {
        $this->minQuantity = $minQuantity;
    }

    /**
     * @return int
     */
    public function getMaxQuantity()
    {
        return $this->maxQuantity;
    }

    /**
     * @param int $maxQuantity
     */
    public function setMaxQuantity($maxQuantity)
    {
        $this->maxQuantity = $maxQuantity;
    }

    /**
     * @return int
     */
    public function getRemainingQuantity()
    {
        return $this->remainingQuantity;
    }

    /**
     * @param int $remainingQuantity
     */
    public function setRemainingQuantity($remainingQuantity)
    {
        $this->remainingQuantity = $remainingQuantity;
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
    public function getOrderCreateItem()
    {
        return $this->orderCreateItem;
    }

    /**
     * @param int $orderCreateItem
     */
    public function setOrderCreateItem($orderCreateItem)
    {
        $this->orderCreateItem = $orderCreateItem;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param Country $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getManufacture()
    {
        return $this->manufacture;
    }

    /**
     * @param mixed $manufacture
     */
    public function setManufacture($manufacture)
    {
        $this->manufacture = $manufacture;
    }

    /**
     * @return string
     */
    public function getWarningLabel()
    {
        return $this->warningLabel;
    }

    /**
     * @param string $warningLabel
     */
    public function setWarningLabel($warningLabel)
    {
        $this->warningLabel = $warningLabel;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return ProductUnit
     */
    public function getProductUnit()
    {
        return $this->productUnit;
    }

    /**
     * @param ProductUnit $productUnit
     */
    public function setProductUnit($productUnit)
    {
        $this->productUnit = $productUnit;
    }

    /**
     * @return string
     */
    public function getBarcode()
    {
        return $this->barcode;
    }

    /**
     * @param string $barcode
     */
    public function setBarcode($barcode)
    {
        $this->barcode = $barcode;
    }

    /**
     * @return mixed
     */
    public function getSTRPadCode()
    {
        $code = str_pad($this->getCode(),3, '0', STR_PAD_LEFT);
        return $code;
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
     * @return int
     */
    public function getDisposalQuantity()
    {
        return $this->disposalQuantity;
    }

    /**
     * @param int $disposalQuantity
     */
    public function setDisposalQuantity($disposalQuantity)
    {
        $this->disposalQuantity = $disposalQuantity;
    }

    /**
     * @return mixed
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * @param mixed $unitPrice
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;
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
    public function getVatPercentage()
    {
        return $this->vatPercentage;
    }

    /**
     * @param float $vatPercentage
     */
    public function setVatPercentage($vatPercentage)
    {
        $this->vatPercentage = $vatPercentage;
    }



    /**
     * @return DepreciationModel
     */
    public function getDepreciationModel()
    {
        return $this->depreciationModel;
    }

    /**
     * @return PurchaseRequisitionItem
     */
    public function getPurchaseRequisitionItems()
    {
        return $this->purchaseRequisitionItems;
    }

    /**
     * @return PurchaseOrderItem
     */
    public function getPurchaseOrderItems()
    {
        return $this->purchaseOrderItems;
    }

    /**
     * @return Particular
     */
    public function getProductGroup()
    {
        return $this->productGroup;
    }

    /**
     * @param Particular $productGroup
     */
    public function setProductGroup($productGroup)
    {
        $this->productGroup = $productGroup;
    }


    /**
     * @return string
     */
    public function getVatName()
    {
        return $this->vatName;
    }

    /**
     * @param string $vatName
     */
    public function setVatName($vatName)
    {
        $this->vatName = $vatName;
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
     * @return Damage
     */
    public function getDamages()
    {
        return $this->damages;
    }

    /**
     * @return AccountHead
     */
    public function getAccountHead()
    {
        return $this->accountHead;
    }

    /**
     * @return Product
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @return mixed
     */
    public function getLedgers()
    {
        return $this->ledgers;
    }

    /**
     * @param mixed $ledgers
     */
    public function setLedgers($ledgers)
    {
        $this->ledgers = $ledgers;
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param string $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @param string $origin
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;
    }

    /**
     * @return string
     */
    public function getCaliber()
    {
        return $this->caliber;
    }

    /**
     * @param string $caliber
     */
    public function setCaliber($caliber)
    {
        $this->caliber = $caliber;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getSkuName()
    {
        return $this->skuName;
    }

    /**
     * @param string $skuName
     */
    public function setSkuName($skuName)
    {
        $this->skuName = $skuName;
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
     * @return int
     */
    public function getProgressQuantity()
    {
        return $this->progressQuantity;
    }

    /**
     * @param int $progressQuantity
     */
    public function setProgressQuantity($progressQuantity)
    {
        $this->progressQuantity = $progressQuantity;
    }


}

