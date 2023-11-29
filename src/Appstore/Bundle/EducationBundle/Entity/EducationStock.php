<?php

namespace Appstore\Bundle\EducationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Setting\Bundle\ToolBundle\Entity\ProductUnit;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * EducationStock
 *
 * @ORM\Table( name = "education_stock")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\EducationBundle\Repository\EducationStockRepository")
 */
class EducationStock
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\EducationBundle\Entity\EducationConfig", inversedBy="stocks" , cascade={"detach","merge"} )
     **/
    private  $educationConfig;

    /**
     * @ORM\ManyToOne(targetEntity="Setting\Bundle\ToolBundle\Entity\ProductUnit", inversedBy="educationStocks" )
     **/
    private  $unit;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\EducationBundle\Entity\EducationFeesItem", mappedBy="stock" )
     **/
    private  $feesItems;


    /**
     * @var string
     *
     * @ORM\Column(name="productType", type="string", length=20, nullable=true)
     */
    private $productType;


    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="productionType", type="string", length=30,nullable = true)
     */
    private $productionType;


    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="smallint", length=3, nullable=true)
     */
    private $quantity = 1;

    /**
     * @var integer
     *
     * @ORM\Column(name="openingQuantity", type="integer", nullable=true)
     */
    private $openingQuantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="minQuantity", type="integer", nullable=true)
     */
    private $minQuantity;

	/**
     * @var integer
     *
     * @ORM\Column(name="transferQuantity", type="integer", nullable=true)
     */
    private $transferQuantity;


    /**
     * @var integer
     *
     * @ORM\Column(name="purchaseQuantity", type="integer", nullable=true)
     */
    private $purchaseQuantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="salesQuantity", type="integer", nullable=true)
     */
    private $salesQuantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="remainingQuantity", type="integer", nullable=true)
     */
    private $remainingQuantity = 0;


    /**
     * @var integer
     *
     * @ORM\Column(name="purchaseReturnQuantity", type="integer", nullable=true)
     */
    private $purchaseReturnQuantity = 0;

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
     * @var float
     *
     * @ORM\Column(name="purchasePrice", type="float", nullable=true)
     */
    private $purchasePrice;


	/**
	 * @var float
	 *
	 * @ORM\Column(name="productionSalesPrice", type="float", nullable=true)
	 */
	private $productionSalesPrice;



    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;


    /**
     * @var float
     *
     * @ORM\Column(name="salesPrice", type="float", nullable=true)
     */
    private $salesPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", nullable=true)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="discountPrice", type="decimal", nullable=true)
     */
    private $discountPrice;

    /**
     * @var \string
     *
     * @ORM\Column(name="minimumPrice", type="decimal", nullable=true)
     */
    private $minimumPrice;


    /**
     * @var string
     *
     * @ORM\Column(name="sorting", type="string", length=5, nullable=true)
     */
    private $sorting;

    /**
     * @var integer
     *
     * @ORM\Column(name="code", type="integer",  nullable=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="particularCode", type="string", length=10, nullable=true)
     */
    private $particularCode;


    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean" )
     */
    private $status= true;

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
     * Set name
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * Set content
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set price
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }


    /**
     * @return string
     */
    public function getMinimumPrice()
    {
        return $this->minimumPrice;
    }

    /**
     * @param string $minimumPrice
     */
    public function setMinimumPrice($minimumPrice)
    {
        $this->minimumPrice = $minimumPrice;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param bool $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
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
    public function getParticularCode()
    {
        return $this->particularCode;
    }

    /**
     * @param string $particularCode
     */
    public function setParticularCode($particularCode)
    {
        $this->particularCode = $particularCode;
    }


    /**
     * @return ProductUnit
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param ProductUnit $unit
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
    }


    /**
     * Sets file.
     *
     * @param BusinessStock $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return BusinessStock
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

    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'uploads/domain/'.$this->getBusinessConfig()->getGlobalOption()->getId().'/business/';
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
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->getFile()->getClientOriginalName()
        );

        // set the path property to the filename where you've saved the file
        $this->path = $this->getFile()->getClientOriginalName();

        // clean up the file property as you won't need it anymore
        $this->file = null;
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
     * @return string
     */
    public function getPurchaseAverage()
    {
        return $this->purchaseAverage;
    }

    /**
     * @param string $purchaseAverage
     */
    public function setPurchaseAverage($purchaseAverage)
    {
        $this->purchaseAverage = $purchaseAverage;
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
     * @return string
     */
    public function getPurchasePrice()
    {
        return $this->purchasePrice;
    }

    /**
     * @param string $purchasePrice
     */
    public function setPurchasePrice($purchasePrice)
    {
        $this->purchasePrice = $purchasePrice;
    }

    /**
     * @return string
     */
    public function getDiscountPrice()
    {
        return $this->discountPrice;
    }

    /**
     * @param string $discountPrice
     */
    public function setDiscountPrice($discountPrice)
    {
        $this->discountPrice = $discountPrice;
    }

    /**
     * @return float
     */
    public function getOverHead()
    {
        return $this->overHead;
    }

    /**
     * @param float $overHead
     */
    public function setOverHead($overHead)
    {
        $this->overHead = $overHead;
    }


    /**
     * @return float
     */
    public function getUtility()
    {
        return $this->utility;
    }

    /**
     * @param float $utility
     */
    public function setUtility($utility)
    {
        $this->utility = $utility;
    }

    /**
     * @return float
     */
    public function getPackaging()
    {
        return $this->packaging;
    }

    /**
     * @param float $packaging
     */
    public function setPackaging($packaging)
    {
        $this->packaging = $packaging;
    }

    /**
     * @return float
     */
    public function getMarketing()
    {
        return $this->marketing;
    }

    /**
     * @param float $marketing
     */
    public function setMarketing($marketing)
    {
        $this->marketing = $marketing;
    }

    public function getCodeName()
    {
        $codeName = $this->getSorting().' - '.$this->getName();
        return $codeName;
    }

    /**
     * @return string
     */
    public function getSorting()
    {
        return $this->sorting;
    }

    /**
     * @param string $sorting
     */
    public function setSorting($sorting)
    {
        $this->sorting = $sorting;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return BusinessConfig
     */
    public function getBusinessConfig()
    {
        return $this->businessConfig;
    }

    /**
     * @param BusinessConfig $businessConfig
     */
    public function setBusinessConfig($businessConfig)
    {
        $this->businessConfig = $businessConfig;
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
     * @return BusinessPurchaseItem
     */
    public function getBusinessPurchaseItems()
    {
        return $this->businessPurchaseItems;
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
     * @return BusinessPurchaseReturnItem
     */
    public function getBusinessPurchaseReturnItems()
    {
        return $this->businessPurchaseReturnItems;
    }

    /**
     * @return BusinessDamage
     */
    public function getBusinessDamages()
    {
        return $this->businessDamages;
    }

    /**
     * @return BusinessInvoiceParticular
     */
    public function getBusinessInvoiceParticulars()
    {
        return $this->businessInvoiceParticulars;
    }

    /**
     * @return BusinessProductionExpense
     */
    public function getBusinessProductionExpense()
    {
        return $this->businessProductionExpense;
    }

    /**
     * @return BusinessProductionExpense
     */
    public function getBusinessProductionExpenseItem()
    {
        return $this->businessProductionExpenseItem;
    }

    /**
     * @return BusinessStockType
     */
    public function getBusinessStockType()
    {
        return $this->BusinessStockType;
    }

    /**
     * @param BusinessStockType $BusinessStockType
     */
    public function setBusinessStockType($BusinessStockType)
    {
        $this->BusinessStockType = $BusinessStockType;
    }

    /**
     * @return string
     */
    public function getProductionType()
    {
        return $this->productionType;
    }

    /**
     * @param string $productionType
     */
    public function setProductionType(string $productionType)
    {
        $this->productionType = $productionType;
    }

	/**
	 * @return WearHouse
	 */
	public function getWearHouse() {
		return $this->wearHouse;
	}

	/**
	 * @param WearHouse $wearHouse
	 */
	public function setWearHouse( $wearHouse ) {
		$this->wearHouse = $wearHouse;
	}

	/**
	 * @return float
	 */
	public function getSalesPrice() {
		return $this->salesPrice;
	}

	/**
	 * @param float $salesPrice
	 */
	public function setSalesPrice( float $salesPrice ) {
		$this->salesPrice = $salesPrice;
	}

	/**
	 * @return BusinessProductionElement
	 */
	public function getProductionElements() {
		return $this->productionElements;
	}

	/**
	 * @return mixed
	 */
	public function getProduction() {
		return $this->production;
	}

	/**
	 * @return float
	 */
	public function getProductionSalesPrice(){
		return $this->productionSalesPrice;
	}

	/**
	 * @param float $productionSalesPrice
	 */
	public function setProductionSalesPrice( float $productionSalesPrice ) {
		$this->productionSalesPrice = $productionSalesPrice;
	}

	/**
	 * @return int
	 */
	public function getTransferQuantity(){
		return $this->transferQuantity;
	}

	/**
	 * @param int $transferQuantity
	 */
	public function setTransferQuantity( int $transferQuantity ) {
		$this->transferQuantity = $transferQuantity;
	}

	/**
	 * @return BusinessProduction
	 */
	public function getBusinessProductions() {
		return $this->businessProductions;
	}

    /**
     * @return EducationConfig
     */
    public function getEducationConfig()
    {
        return $this->educationConfig;
    }

    /**
     * @param EducationConfig $educationConfig
     */
    public function setEducationConfig($educationConfig)
    {
        $this->educationConfig = $educationConfig;
    }

}

