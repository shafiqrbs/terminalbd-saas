<?php

namespace Appstore\Bundle\AssetsBundle\Entity;

use Appstore\Bundle\AccountingBundle\Entity\AccountHead;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * Depreciation
 *
 * @ORM\Table("assets_depreciation_model")
 * @ORM\Entity()
 */
class DepreciationModel
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
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\AssetsConfig", inversedBy="depreciationModels" )
	 **/
	private  $config;

	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Depreciation", inversedBy="depreciationModels" )
	 **/
	private  $depreciation;

	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Category", inversedBy="depreciationModel" )
	 **/
	private  $category;

	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Item", inversedBy="depreciationModel" )
	 **/
	private  $item;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Product", mappedBy="depreciation" )
	 **/
	private  $products;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\DepreciationBatch", mappedBy="depreciation" )
	 **/
	private  $depreciationBatch;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\ProductLedger", mappedBy="depreciation" )
	 **/
	private  $ledgers;

	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountHead", inversedBy="depreciationModelDebit" )
	 **/
	private  $accountHeadDebit;

	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountHead", inversedBy="depreciationModelCredit" )
	 **/
	private  $accountHeadCredit;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", length=255)
	 */
	private $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="assetsType", type="string", length=255)
	 */
	private $assetsType;


	/**
	 * @var float
	 *
	 * @ORM\Column(name="rate", type="float", nullable=true)
	 */
	private $rate;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="depreciationYear", type="float", nullable=true)
	 */
	private $depreciationYear;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="status", type="boolean")
	 */
	private $status = true;

    /**
	 * @var boolean
	 *
	 * @ORM\Column(name="isDefault", type="boolean")
	 */
	private $isDefault = false;


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
	 * @return Item
	 */
	public function getItem() {
		return $this->item;
	}

	/**
	 * @param Item $item
	 */
	public function setItem( $item ) {
		$this->item = $item;
	}


	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( $name ) {
		$this->name = $name;
	}

	/**
	 * @return float
	 */
	public function getRate() {
		return $this->rate;
	}

	/**
	 * @param float $rate
	 */
	public function setRate( $rate ) {
		$this->rate = $rate;
	}

	/**
	 * @return AccountHead
	 */
	public function getAccountHeadDebit() {
		return $this->accountHeadDebit;
	}

	/**
	 * @param AccountHead $accountHeadDebit
	 */
	public function setAccountHeadDebit( $accountHeadDebit ) {
		$this->accountHeadDebit = $accountHeadDebit;
	}

	/**
	 * @return AccountHead
	 */
	public function getAccountHeadCredit() {
		return $this->accountHeadCredit;
	}

	/**
	 * @param AccountHead $accountHeadCredit
	 */
	public function setAccountHeadCredit( $accountHeadCredit ) {
		$this->accountHeadCredit = $accountHeadCredit;
	}

	/**
	 * @return bool
	 */
	public function isStatus() {
		return $this->status;
	}

	/**
	 * @param bool $status
	 */
	public function setStatus( $status ) {
		$this->status = $status;
	}

	/**
	 * @return string
	 */
	public function getAssetsType() {
		return $this->assetsType;
	}

	/**
	 * @param string $assetsType
	 */
	public function setAssetsType( $assetsType ) {
		$this->assetsType = $assetsType;
	}

	/**
	 * @return float
	 */
	public function getDepreciationYear() {
		return $this->depreciationYear;
	}

	/**
	 * @param float $depreciationYear
	 */
	public function setDepreciationYear( $depreciationYear ) {
		$this->depreciationYear = $depreciationYear;
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
     * @return ProductLedger
     */
    public function getLedgers()
    {
        return $this->ledgers;
    }

    /**
     * @return Product
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @return Depreciation
     */
    public function getDepreciation()
    {
        return $this->depreciation;
    }

    /**
     * @param Depreciation $depreciation
     */
    public function setDepreciation($depreciation)
    {
        $this->depreciation = $depreciation;
    }

    /**
     * @return bool
     */
    public function isDefault()
    {
        return $this->isDefault;
    }

    /**
     * @param bool $isDefault
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;
    }


}

