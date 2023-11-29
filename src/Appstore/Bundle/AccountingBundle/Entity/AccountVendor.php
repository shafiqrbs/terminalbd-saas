<?php

namespace Appstore\Bundle\AccountingBundle\Entity;

use Appstore\Bundle\BusinessBundle\Entity\BusinessInvoiceParticular;
use Appstore\Bundle\BusinessBundle\Entity\BusinessPurchase;
use Appstore\Bundle\TallyBundle\Entity\Purchase;
use Appstore\Bundle\TallyBundle\Entity\StockItem;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;

/**
 * Vendor
 *
 * @ORM\Table(name="account_vendor")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\AccountingBundle\Repository\AccountVendorRepository")
 */
class AccountVendor
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
     * @ORM\ManyToOne(targetEntity="Setting\Bundle\ToolBundle\Entity\GlobalOption", inversedBy="vendors")
     **/
    protected $globalOption;

    /**
     * @ORM\OneToOne(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountHead", mappedBy="accountVendor" )
     **/
    private  $accountHead;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Item", mappedBy="vendor" )
     **/
    private  $items;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\EcommerceBundle\Entity\ItemImport", mappedBy="vendor" , cascade={"persist", "remove"})
     */
    protected $itemImports;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Product", mappedBy="vendor" )
     **/
    private  $products;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\StockItem", mappedBy="vendor" )
     **/
    private  $stockItems;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountPurchase", mappedBy="accountVendor")
     */
    protected $accountPurchases;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountPurchaseCommission", mappedBy="accountVendor")
     */
    protected $accountPurchaseCommissions;

     /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\MedicineBundle\Entity\MedicinePurchase", mappedBy="accountVendor")
     */
    protected $medicinePurchases;

     /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelPurchase", mappedBy="vendor")
     */
    protected $hotelPurchases;

     /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\RestaurantBundle\Entity\Purchase", mappedBy="vendor")
     */
    protected $restaurantPurchases;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelPurchaseReturn", mappedBy="vendor")
     */
    protected $hotelPurchasesReturns;

    /**
     * @ORM\ManyToOne(targetEntity="Setting\Bundle\LocationBundle\Entity\Country", inversedBy="vendors")
     */
    protected $country;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\BusinessBundle\Entity\BusinessPurchase", mappedBy="vendor")
     */
    protected $businessPurchases;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\BusinessBundle\Entity\BusinessVendorStock", mappedBy="vendor")
     **/
    private $businessVendorStocks;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\BusinessBundle\Entity\BusinessInvoiceParticular", mappedBy="vendor")
     **/
    private $businessInvoiceParticulars;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\BusinessBundle\Entity\BusinessPurchaseReturn", mappedBy="vendor")
     */
    protected $businessPurchasesReturns;

	/**
	 * @ORM\OneToOne(targetEntity="Appstore\Bundle\DomainUserBundle\Entity\Customer", inversedBy="accountVendor")
	 * @ORM\JoinColumn(name="customer_id", referencedColumnName="id", nullable=true, onDelete="cascade")
	 */
	protected $customer;

	/**
     * @var string
     *
     * @ORM\Column(name="module", type="string", length = 50, nullable=true)
     */
    private $module;


    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;


    /**
     * @var string
     *
     * @ORM\Column(name="vendorCode", type="string", length=20)
     */
    private $vendorCode;

    /**
     * @var integer
     *
     * @ORM\Column(name="code", type="integer")
     */
    private $code;


    /**
     * @var string
     *
     * @ORM\Column(name="companyName", type="string", length=255)
     */
    private $companyName;

    /**
     * @Gedmo\Slug(fields={"companyName"})
     * @Doctrine\ORM\Mapping\Column(length=255)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="text", nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=255, nullable=true)
     */
    private $mobile;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255 , nullable=true)
     */
    private $email;

    /**
     * @var integer
     *
     * @ORM\Column(name="oldId", type="integer", length=10 , nullable=true)
     */
    private $oldId;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="mode", type="string", length= 50, nullable=true)
	 */
	private $mode;


	/**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean" )
     */
    private $status=true;


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
     * @return Vendor
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
     * Set address
     *
     * @param string $address
     *
     * @return Vendor
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     *
     * @return Vendor
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }



    /**
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @param string $companyName
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
    }

    /**
     * @return boolean
     */
    public function isStatus()
    {
        return $this->status;
    }

    /**
     * @param boolean $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return integer
     */
    public function getVendorCode()
    {
        return $this->vendorCode;
    }

    /**
     * @param mixed $vendorCode
     */
    public function setVendorCode($vendorCode)
    {
        $this->vendorCode = $vendorCode;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }



    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
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
     * @return BusinessPurchase
     */
    public function getBusinessPurchases()
    {
        return $this->businessPurchases;
    }

    /**
     * @return AccountPurchase
     */
    public function getAccountPurchases()
    {
        return $this->accountPurchases;
    }

	/**
	 * @return int
	 */
	public function getOldId(){
		return $this->oldId;
	}

	/**
	 * @param int $oldId
	 */
	public function setOldId( int $oldId ) {
		$this->oldId = $oldId;
	}

	/**
	 * @return string
	 */
	public function getMode(){
		return $this->mode;
	}

	/**
	 * @param string $mode
	 */
	public function setMode($mode ) {
		$this->mode = $mode;
	}

	/**
	 * @return string
	 */
	public function getModule() {
		return $this->module;
	}

	/**
	 * @param string $module
	 */
	public function setModule( string $module ) {
		$this->module = $module;
	}

    /**
     * @return BusinessInvoiceParticular
     */
    public function getBusinessInvoiceParticulars()
    {
        return $this->businessInvoiceParticulars;
    }

    /**
     * @return StockItem
     */
    public function getStockItems()
    {
        return $this->stockItems;
    }

    /**
     * @return Purchase
     */
    public function getTallyPurchase()
    {
        return $this->tallyPurchase;
    }


}

