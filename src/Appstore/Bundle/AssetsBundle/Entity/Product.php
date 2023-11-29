<?php

namespace Appstore\Bundle\AssetsBundle\Entity;

use Appstore\Bundle\DomainUserBundle\Entity\Branches;
use Appstore\Bundle\AssetsBundle\Entity\Category;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product
 *
 * @ORM\Table("assets_product")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\AssetsBundle\Repository\ProductRepository")
 */
class Product
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
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\AssetsConfig", inversedBy="products" )
	 **/
	private  $config;

	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\DepreciationModel", inversedBy="products" )
	 **/
	private  $depreciation;

	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Particular", inversedBy="products" )
	 **/
	private  $depreciationStatus;


	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Distribution", mappedBy="product" )
	 **/
	private  $distributions;


	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Item", inversedBy="products" )
	 **/
	private  $item;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\ProductLedger", mappedBy="product")
     **/
    protected $ledgers;


	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Particular", inversedBy="branchProducts" )
	 **/
	private  $branch;


	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Category", inversedBy="products" )
	 **/
	private  $category;

	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Category", inversedBy="childProducts" )
	 **/
	private  $parentCategory;


	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Brand", inversedBy="products" )
	 **/
	private  $brand;

    /**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountVendor", inversedBy="products" )
	 **/
	private  $vendor;


	/**
	 * @var string
	 *
	 * @ORM\Column(name="serialNo", type="string", length=100, nullable=true)
	 */
	private $serialNo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="branchSerialNo", type="string", length=100, nullable=true)
	 */
	private $branchSerialNo;


	/**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;


    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    private $slug;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="quantity", type="integer", nullable=true)
	 */
	private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="productType", type="string", length=30, nullable=true)
     */
    private $productType;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="assuranceType", type="string", nullable=true)
	 */
	private $assuranceType;

	/**
	 * @var datetime
	 *
	 * @ORM\Column(name="expiredDate", type="date", nullable=true)
	 */
	private $expiredDate;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="purchasePrice", type="float", nullable=true)
	 */
	private $purchasePrice;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="servicePrice", type="float", nullable=true)
	 */
	private $servicePrice;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="depreciationValue", type="float", nullable=true)
	 */
	private $depreciationValue;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="bookValue", type="float", nullable=true)
	 */
	private $bookValue;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="salvageValue", type="float", nullable=true)
	 */
	private $salvageValue;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="straightLineValue", type="float", nullable=true)
	 */
	private $straightLineValue;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="straightLinePercentage", type="float", nullable=true)
	 */
	private $straightLinePercentage;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="reducingBalancePercentage", type="float", nullable=true)
	 */
	private $reducingBalancePercentage;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="depreciationRate", type="float", nullable=true)
	 */
	private $depreciationRate;


	/**
	 * @var integer
	 *
	 * @ORM\Column(name="code", type="integer",  nullable=true)
	 */
	private $code;

	/**
	 * @var DateTime
	 *
	 * @ORM\Column(name="depreciationEffectedDate", type="date",  nullable=true)
	 */
	private $depreciationEffectedDate;


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
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status = true;

	/**
     * @var boolean
     *
     * @ORM\Column(name="customDepreciation", type="boolean")
     */
    private $customDepreciation = false;

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
     * @return Product
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
     * @return Product
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
	 * @return DepreciationModel
	 */
	public function getDepreciation() {
		return $this->depreciation;
	}

	/**
	 * @param DepreciationModel $depreciation
	 */
	public function setDepreciation( $depreciation ) {
		$this->depreciation = $depreciation;
	}

	/**
	 * @return Particular
	 */
	public function getDepreciationStatus() {
		return $this->depreciationStatus;
	}

	/**
	 * @param Particular $depreciationStatus
	 */
	public function setDepreciationStatus( $depreciationStatus ) {
		$this->depreciationStatus = $depreciationStatus;
	}



	/**
	 * @return PurchaseItem
	 */
	public function getPurchaseItem() {
		return $this->purchaseItem;
	}

	/**
	 * @param PurchaseItem $purchaseItem
	 */
	public function setPurchaseItem( $purchaseItem ) {
		$this->purchaseItem = $purchaseItem;
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
	 * @return Category
	 */
	public function getParentCategory() {
		return $this->parentCategory;
	}

	/**
	 * @param Category $parentCategory
	 */
	public function setParentCategory( $parentCategory ) {
		$this->parentCategory = $parentCategory;
	}


	/**
	 * @return string
	 */
	public function getSerialNo() {
		return $this->serialNo;
	}

	/**
	 * @param string $serialNo
	 */
	public function setSerialNo( $serialNo ) {
		$this->serialNo = $serialNo;
	}

	/**
	 * @return string
	 */
	public function getBranchSerialNo() {
		return $this->branchSerialNo;
	}

	/**
	 * @param string $branchSerialNo
	 */
	public function setBranchSerialNo( $branchSerialNo ) {
		$this->branchSerialNo = $branchSerialNo;
	}

	/**
	 * @return string
	 */
	public function getTags() {
		return $this->tags;
	}

	/**
	 * @param string $tags
	 */
	public function setTags( $tags ) {
		$this->tags = $tags;
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
	 * @return \DateTime
	 */
	public function getExpiredDate() {
		return $this->expiredDate;
	}

	/**
	 * @param \DateTime $expiredDate
	 */
	public function setExpiredDate( $expiredDate ) {
		$this->expiredDate = $expiredDate;
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
	 * @return float
	 */
	public function getServicePrice() {
		return $this->servicePrice;
	}

	/**
	 * @param float $servicePrice
	 */
	public function setServicePrice( $servicePrice ) {
		$this->servicePrice = $servicePrice;
	}

	/**
	 * @return float
	 */
	public function getBookValue() {
		return $this->bookValue;
	}

	/**
	 * @param float $bookValue
	 */
	public function setBookValue( $bookValue ) {
		$this->bookValue = $bookValue;
	}

	/**
	 * @return float
	 */
	public function getSalvageValue() {
		return $this->salvageValue;
	}

	/**
	 * @param float $salvageValue
	 */
	public function setSalvageValue( $salvageValue ) {
		$this->salvageValue = $salvageValue;
	}

	/**
	 * @return DateTime
	 */
	public function getCreated() {
		return $this->created;
	}

	/**
	 * @param DateTime $created
	 */
	public function setCreated( $created ) {
		$this->created = $created;
	}

	/**
	 * @return DateTime
	 */
	public function getUpdated() {
		return $this->updated;
	}

	/**
	 * @param DateTime $updated
	 */
	public function setUpdated( $updated ) {
		$this->updated = $updated;
	}

	/**
	 * @return Vendor
	 */
	public function getVendor() {
		return $this->vendor;
	}

	/**
	 * @param Vendor $vendor
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
	 * @return float
	 */
	public function getDepreciationValue() {
		return $this->depreciationValue;
	}

	/**
	 * @param float $depreciationValue
	 */
	public function setDepreciationValue( $depreciationValue ) {
		$this->depreciationValue = $depreciationValue;
	}

	/**
	 * @return Distribution
	 */
	public function getDistributions() {
		return $this->distributions;
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
	 * @return bool
	 */
	public function isCustomDepreciation() {
		return $this->customDepreciation;
	}

	/**
	 * @param bool $customDepreciation
	 */
	public function setCustomDepreciation( $customDepreciation ) {
		$this->customDepreciation = $customDepreciation;
	}

	/**
	 * @return float
	 */
	public function getStraightLineValue() {
		return $this->straightLineValue;
	}

	/**
	 * @param float $straightLineValue
	 */
	public function setStraightLineValue( $straightLineValue ) {
		$this->straightLineValue = $straightLineValue;
	}

	/**
	 * @return float
	 */
	public function getStraightLinePercentage() {
		return $this->straightLinePercentage;
	}

	/**
	 * @param float $straightLinePercentage
	 */
	public function setStraightLinePercentage( $straightLinePercentage ) {
		$this->straightLinePercentage = $straightLinePercentage;
	}

	/**
	 * @return float
	 */
	public function getReducingBalancePercentage() {
		return $this->reducingBalancePercentage;
	}

	/**
	 * @param float $reducingBalancePercentage
	 */
	public function setReducingBalancePercentage( $reducingBalancePercentage ) {
		$this->reducingBalancePercentage = $reducingBalancePercentage;
	}

	/**
	 * @return float
	 */
	public function getDepreciationRate() {
		return $this->depreciationRate;
	}

	/**
	 * @param float $depreciationRate
	 */
	public function setDepreciationRate( $depreciationRate ) {
		$this->depreciationRate = $depreciationRate;
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
     * @return AssetsItemBrand
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param AssetsItemBrand $brand
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
        return 'uploads/domain/'.$this->getGlobalOption()->getId().'/assets/product/';
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
     * @return Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param Item $item
     */
    public function setItem($item)
    {
        $this->item = $item;
    }

    /**
     * @return DateTime
     */
    public function getDepreciationEffectedDate()
    {
        return $this->depreciationEffectedDate;
    }

    /**
     * @param DateTime $depreciationEffectedDate
     */
    public function setDepreciationEffectedDate($depreciationEffectedDate)
    {
        $this->depreciationEffectedDate = $depreciationEffectedDate;
    }

    /**
     * @return mixed
     */
    public function getReceiveItem()
    {
        return $this->receiveItem;
    }

    /**
     * @param mixed $receiveItem
     */
    public function setReceiveItem($receiveItem)
    {
        $this->receiveItem = $receiveItem;
    }

}

