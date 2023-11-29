<?php

namespace Appstore\Bundle\HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * HotelConfig
 *
 * @ORM\Table( name ="hotel_config")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\HotelBundle\Repository\HotelConfigRepository")
 */
class HotelConfig
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
     * @ORM\OneToOne(targetEntity="Setting\Bundle\ToolBundle\Entity\GlobalOption", inversedBy="hotelConfig" , cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private $globalOption;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelInvoice", mappedBy="hotelConfig")
     **/
    private $hotelInvoices;

	/**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelOption", mappedBy="hotelConfig")
     **/
    private $hotelOptions;

	/**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelInvoiceTransactionSummary", mappedBy="hotelConfig")
     **/
    private $hotelInvoiceTransactionSummary;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelReverse", mappedBy="hotelConfig")
     **/
    private $hotelReverses;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\HotelBundle\Entity\Category", mappedBy="hotelConfig")
     **/
    private $categories;

     /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelPurchase", mappedBy="hotelConfig")
     **/
    private $hotelPurchases;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelParticular", mappedBy="hotelConfig")
     **/
    private $hotelParticulars;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelDamage", mappedBy="hotelConfig")
     **/
    private $hotelDamages;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelPurchaseReturn", mappedBy="hotelConfig")
     **/
    private $hotelPurchasesReturns;

    /**
     * @var array
     *
     * @ORM\Column(name="stockFormat", type="array", length=50,nullable = true)
     */
    private $stockFormat;

    /**
     * @var string
     *
     * @ORM\Column(name="printer", type="string", length=50,nullable = true)
     */
    private $printer;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="text", nullable = true)
     */
    private $address;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="notification", type="boolean",  nullable=true)
	 */
	private $notification = false;

	/**
     * @var string
     *
     * @ORM\Column(name="invoiceBookingsms", type="text", nullable = true)
     */
    private $invoiceBookingsms;

    /**
     * @var string
     *
     * @ORM\Column(name="invoiceCheckinsms", type="text", nullable = true)
     */
    private $invoiceCheckinsms;

	/**
     * @var string
     *
     * @ORM\Column(name="invoiceCheckoutsms", type="text", nullable = true)
     */
    private $invoiceCheckoutsms;


    /**
     * @var float
     *
     * @ORM\Column(name="vatForHotel", type="float",  nullable=true)
     */
    private $vatForHotel;

	/**
     * @var float
     *
     * @ORM\Column(name="vatForRestaurant", type="float",  nullable=true)
     */
    private $vatForRestaurant;

	/**
     * @var float
     *
     * @ORM\Column(name="serviceCharge", type="float",  nullable=true)
     */
    private $serviceCharge;


    /**
     * @var float
     *
     * @ORM\Column(name="fontSizeLabel", type="float",  nullable=true)
     */
    private $fontSizeLabel;

    /**
     * @var float
     *
     * @ORM\Column(name="fontSizeValue", type="float",  nullable=true)
     */
    private $fontSizeValue;

    /**
     * @var string
     *
     * @ORM\Column(name="vatRegNo", type="string",  nullable=true)
     */
    private $vatRegNo;


    /**
     * @var boolean
     *
     * @ORM\Column(name="vatEnable", type="boolean",  nullable=true)
     */
    private $vatEnable = false;

    /**
     * @var float
     *
     * @ORM\Column(name="printMarginTop", type="float",  nullable=true)
     */
    private $printTopMargin = 0;


    /**
     * @var float
     *
     * @ORM\Column(name="printMarginBottom", type="float",  nullable=true)
     */
    private $printMarginBottom = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="headerLeftWidth", type="string",  nullable=true)
     */
    private $headerLeftWidth = 0;


    /**
     * @var string
     *
     * @ORM\Column(name="headerRightWidth", type="string",  nullable=true)
     */
    private $headerRightWidth = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="printMarginReportTop", type="float",  nullable=true)
     */
    private $printMarginReportTop = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="printMarginReportLeft", type="float",  nullable=true)
     */
    private $printMarginReportLeft = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isPrintHeader", type="boolean",  nullable=true)
     */
    private $isPrintHeader = true;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isInvoiceTitle", type="boolean",  nullable=true)
     */
    private $isInvoiceTitle = true;


    /**
     * @var boolean
     *
     * @ORM\Column(name="isPrintFooter", type="boolean",  nullable=true)
     */
    private $isPrintFooter = true;

    /**
     * @var string
     *
     * @ORM\Column(name="invoicePrefix", type="string", length=10,nullable = true)
     */
    private $invoicePrefix;

    /**
     * @var array
     *
     * @ORM\Column(name="invoiceProcess", type="array", nullable = true)
     */
    private $invoiceProcess;

    /**
     * @var string
     *
     * @ORM\Column(name="customerPrefix", type="string", length=10,nullable = true)
     */
    private $customerPrefix;

    /**
     * @var string
     *
     * @ORM\Column(name="productionType", type="string", length=30,nullable = true)
     */
    private $productionType;

    /**
     * @var string
     *
     * @ORM\Column(name="invoiceType", type="string", length=30,nullable = true)
     */
    private $invoiceType;

    /**
     * @var string
     *
     * @ORM\Column(name="invoiceForRestaurant", type="boolean",nullable = true)
     */
    private $invoiceForRestaurant = false;


    /**
     * @var string
     *
     * @ORM\Column(name="bodyFontSize", type="string", length=10,nullable = true)
     */
    private $bodyFontSize;

    /**
     * @var string
     *
     * @ORM\Column(name="sidebarFontSize", type="string", length=10,nullable = true)
     */
    private $sidebarFontSize;

    /**
     * @var string
     *
     * @ORM\Column(name="invoiceFontSize", type="string", length=10,nullable = true)
     */
    private $invoiceFontSize;


    /**
     * @var float
     *
     * @ORM\Column(name="printLeftMargin", type="float", nullable = true)
     */
    private $printLeftMargin = 0;


    /**
     * @var integer
     *
     * @ORM\Column(name="invoiceHeight", type="integer", nullable = true)
     */
    private $invoiceHeight = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="leftTopMargin", type="integer", nullable = true)
     */
    private $leftTopMargin = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="bodyTopMargin", type="integer", nullable = true)
     */
    private $bodyTopMargin = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="sidebarWidth", type="string", nullable = true)
     */
    private $sidebarWidth = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="bodyWidth", type="string", nullable = true)
     */
    private $bodyWidth = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="invoicePrintLogo", type="boolean",  nullable=true)
     */
    private $invoicePrintLogo = true;

    /**
     * @var boolean
     *
     * @ORM\Column(name="barcodePrint", type="boolean",  nullable=true)
     */
    private $barcodePrint = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="customInvoice", type="boolean",  nullable=true)
     */
    private $customInvoice = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="customInvoicePrint", type="boolean")
     */
    private $customInvoicePrint = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="showStock", type="boolean",  nullable=true)
     */
     private $showStock = false;

/**
     * @var boolean
     *
     * @ORM\Column(name="isPowered", type="boolean",  nullable=true)
     */
     private $isPowered = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="removeImage", type="boolean")
     */
    private $removeImage = false;

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
     * @return float
     */
    public function getPrintLeftMargin()
    {
        return $this->printLeftMargin;
    }

    /**
     * @param float $printLeftMargin
     */
    public function setPrintLeftMargin($printLeftMargin)
    {
        $this->printLeftMargin = $printLeftMargin;
    }


    /**
     * @return bool
     */
    public function isIsPrintFooter()
    {
        return $this->isPrintFooter;
    }

    /**
     * @param bool $isPrintFooter
     */
    public function setIsPrintFooter($isPrintFooter)
    {
        $this->isPrintFooter = $isPrintFooter;
    }

    /**
     * @return bool
     */
    public function isIsPrintHeader()
    {
        return $this->isPrintHeader;
    }

    /**
     * @param bool $isPrintHeader
     */
    public function setIsPrintHeader($isPrintHeader)
    {
        $this->isPrintHeader = $isPrintHeader;
    }

    /**
     * @return float
     */
    public function getPrintMarginBottom()
    {
        return $this->printMarginBottom;
    }

    /**
     * @param float $printMarginBottom
     */
    public function setPrintMarginBottom($printMarginBottom)
    {
        $this->printMarginBottom = $printMarginBottom;
    }

    /**
     * @return string
     */
    public function getPrinter()
    {
        return $this->printer;
    }

    /**
     * @param string $printer
     */
    public function setPrinter($printer)
    {
        $this->printer = $printer;
    }


    /**
     * @return string
     */
    public function getVatRegNo()
    {
        return $this->vatRegNo;
    }

    /**
     * @param string $vatRegNo
     */
    public function setVatRegNo($vatRegNo)
    {
        $this->vatRegNo = $vatRegNo;
    }

    /**
     * @return bool
     */
    public function isBarcodePrint()
    {
        return $this->barcodePrint;
    }

    /**
     * @param bool $barcodePrint
     */
    public function setBarcodePrint($barcodePrint)
    {
        $this->barcodePrint = $barcodePrint;
    }


    /**
     * @return bool
     */
    public function getVatEnable()
    {
        return $this->vatEnable;
    }

    /**
     * @param bool $vatEnable
     */
    public function setVatEnable($vatEnable)
    {
        $this->vatEnable = $vatEnable;
    }

    /**
     * @return float
     */
    public function getPrintTopMargin()
    {
        return $this->printTopMargin;
    }

    /**
     * @param float $printTopMargin
     */
    public function setPrintTopMargin($printTopMargin)
    {
        $this->printTopMargin = $printTopMargin;
    }



    /**
     * @return boolean
     */
    public function isInvoicePrintLogo()
    {
        return $this->invoicePrintLogo;
    }

    /**
     * @param boolean $invoicePrintLogo
     */
    public function setInvoicePrintLogo($invoicePrintLogo)
    {
        $this->invoicePrintLogo = $invoicePrintLogo;
    }

    /**
     * @return bool
     */
    public function getPrintInstruction()
    {
        return $this->printInstruction;
    }

    /**
     * @param bool $printInstruction
     */
    public function setPrintInstruction($printInstruction)
    {
        $this->printInstruction = $printInstruction;
    }

    /**
     * @return string
     */
    public function getInvoicePrefix()
    {
        return $this->invoicePrefix;
    }

    /**
     * @param string $invoicePrefix
     */
    public function setInvoicePrefix($invoicePrefix)
    {
        $this->invoicePrefix = $invoicePrefix;
    }

    /**
     * @return string
     */
    public function getCustomerPrefix()
    {
        return $this->customerPrefix;
    }

    /**
     * @param string $customerPrefix
     */
    public function setCustomerPrefix($customerPrefix)
    {
        $this->customerPrefix = $customerPrefix;
    }

    /**
     * @return float
     */
    public function getPrintMarginReportTop()
    {
        return $this->printMarginReportTop;
    }

    /**
     * @param float $printMarginReportTop
     */
    public function setPrintMarginReportTop($printMarginReportTop)
    {
        $this->printMarginReportTop = $printMarginReportTop;
    }

    /**
     * @return bool
     */
    public function getIsInvoiceTitle()
    {
        return $this->isInvoiceTitle;
    }

    /**
     * @param bool $isInvoiceTitle
     */
    public function setIsInvoiceTitle($isInvoiceTitle)
    {
        $this->isInvoiceTitle = $isInvoiceTitle;
    }


    /**
     * @return array
     */
    public function getInvoiceProcess()
    {
        return $this->invoiceProcess;
    }

    /**
     * @param array $invoiceProcess
     */
    public function setInvoiceProcess($invoiceProcess)
    {
        $this->invoiceProcess = $invoiceProcess;
    }



    /**
     * @return int
     */
    public function getInvoiceHeight()
    {
        return $this->invoiceHeight;
    }

    /**
     * @param int $invoiceHeight
     */
    public function setInvoiceHeight($invoiceHeight)
    {
        $this->invoiceHeight = $invoiceHeight;
    }


    /**
     * @return float
     */
    public function getPrintMarginReportLeft()
    {
        return $this->printMarginReportLeft;
    }

    /**
     * @param float $printMarginReportLeft
     */
    public function setPrintMarginReportLeft($printMarginReportLeft)
    {
        $this->printMarginReportLeft = $printMarginReportLeft;
    }

    /**
     * @return float
     */
    public function getFontSizeLabel()
    {
        return $this->fontSizeLabel;
    }

    /**
     * @param float $fontSizeLabel
     */
    public function setFontSizeLabel($fontSizeLabel)
    {
        $this->fontSizeLabel = $fontSizeLabel;
    }

    /**
     * @return float
     */
    public function getFontSizeValue()
    {
        return $this->fontSizeValue;
    }

    /**
     * @param float $fontSizeValue
     */
    public function setFontSizeValue($fontSizeValue)
    {
        $this->fontSizeValue = $fontSizeValue;
    }


    /**
     * @return HotelPurchase
     */
    public function getHotelPurchases()
    {
        return $this->hotelPurchases;
    }

    /**
     * @return HotelParticular
     */
    public function getHotelParticulars()
    {
        return $this->hotelParticulars;
    }


    /**
     * @return string
     */
    public function getBodyFontSize()
    {
        return $this->bodyFontSize;
    }

    /**
     * @param string $bodyFontSize
     */
    public function setBodyFontSize($bodyFontSize)
    {
        $this->bodyFontSize = $bodyFontSize;
    }

    /**
     * @return string
     */
    public function getSidebarFontSize()
    {
        return $this->sidebarFontSize;
    }

    /**
     * @param string $sidebarFontSize
     */
    public function setSidebarFontSize($sidebarFontSize)
    {
        $this->sidebarFontSize = $sidebarFontSize;
    }

    /**
     * @return string
     */
    public function getInvoiceFontSize()
    {
        return $this->invoiceFontSize;
    }

    /**
     * @param string $invoiceFontSize
     */
    public function setInvoiceFontSize($invoiceFontSize)
    {
        $this->invoiceFontSize = $invoiceFontSize;
    }

    /**
     * @return int
     */
    public function getBodyTopMargin()
    {
        return $this->bodyTopMargin;
    }

    /**
     * @param int $bodyTopMargin
     */
    public function setBodyTopMargin($bodyTopMargin)
    {
        $this->bodyTopMargin = $bodyTopMargin;
    }

    /**
     * @return int
     */
    public function getLeftTopMargin()
    {
        return $this->leftTopMargin;
    }

    /**
     * @param int $leftTopMargin
     */
    public function setLeftTopMargin($leftTopMargin)
    {
        $this->leftTopMargin = $leftTopMargin;
    }


    /**
     * @return string
     */
    public function getHeaderLeftWidth()
    {
        return $this->headerLeftWidth;
    }

    /**
     * @param string $headerLeftWidth
     */
    public function setHeaderLeftWidth($headerLeftWidth)
    {
        $this->headerLeftWidth = $headerLeftWidth;
    }

    /**
     * @return string
     */
    public function getHeaderRightWidth()
    {
        return $this->headerRightWidth;
    }

    /**
     * @param string $headerRightWidth
     */
    public function setHeaderRightWidth($headerRightWidth)
    {
        $this->headerRightWidth = $headerRightWidth;
    }

    /**
     * @return string
     */
    public function getSidebarWidth()
    {
        return $this->sidebarWidth;
    }

    /**
     * @param string $sidebarWidth
     */
    public function setSidebarWidth($sidebarWidth)
    {
        $this->sidebarWidth = $sidebarWidth;
    }

    /**
     * @return string
     */
    public function getBodyWidth()
    {
        return $this->bodyWidth;
    }

    /**
     * @param string $bodyWidth
     */
    public function setBodyWidth($bodyWidth)
    {
        $this->bodyWidth = $bodyWidth;
    }

    /**
     * @return bool
     */
    public function isCustomInvoice()
    {
        return $this->customInvoice;
    }

    /**
     * @param bool $customInvoice
     */
    public function setCustomInvoice($customInvoice)
    {
        $this->customInvoice = $customInvoice;
    }

    /**
     * @return bool
     */
    public function isShowStock()
    {
        return $this->showStock;
    }

    /**
     * @param bool $showStock
     */
    public function setShowStock($showStock)
    {
        $this->showStock = $showStock;
    }

    /**
     * @return HotelDamage
     */
    public function getHotelDamages()
    {
        return $this->hotelDamages;
    }

    /**
     * @return HotelPurchaseReturn
     */
    public function getHotelPurchasesReturns()
    {
        return $this->hotelPurchasesReturns;
    }

    /**
     * @return bool
     */
    public function isCustomInvoicePrint(): bool
    {
        return $this->customInvoicePrint;
    }

    /**
     * @param bool $customInvoicePrint
     */
    public function setCustomInvoicePrint(bool $customInvoicePrint)
    {
        $this->customInvoicePrint = $customInvoicePrint;
    }

    /**
     * Sets file.
     *
     * @param Page $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return Page
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
            : $this->getUploadDir().'/' . $this->path;
    }



    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'uploads/domain/'.$this->getGlobalOption()->getId().'/hotel';
    }

    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
            $this->path = null ;
        }
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
        $this->path = $filename ;

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }

    /**
     * @return boolean
     */
    public function getRemoveImage()
    {
        return $this->removeImage;
    }

    /**
     * @param boolean $removeImage
     */
    public function setRemoveImage($removeImage)
    {
        $this->removeImage = $removeImage;
    }

   

    /**
     * Set address
     *
     * @param mixed $address
     * @return HotelConfig
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
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
     * @return string
     */
    public function getInvoiceType()
    {
        return $this->invoiceType;
    }

    /**
     * @param string $invoiceType
     */
    public function setInvoiceType(string $invoiceType)
    {
        $this->invoiceType = $invoiceType;
    }

	/**
	 * @return HotelInvoice
	 */
	public function getHotelInvoices() {
		return $this->hotelInvoices;
	}

	/**
	 * @return Category
	 */
	public function getCategories() {
		return $this->categories;
	}

	/**
	 * @return array
	 */
	public function getStockFormat(){
		return $this->stockFormat;
	}

	/**
	 * @param array $stockFormat
	 */
	public function setStockFormat( array $stockFormat ) {
		$this->stockFormat = $stockFormat;
	}

	/**
	 * @return bool
	 */
	public function isPowered(){
		return $this->isPowered;
	}

	/**
	 * @param bool $isPowered
	 */
	public function setIsPowered( bool $isPowered ) {
		$this->isPowered = $isPowered;
	}

	/**
	 * @return string
	 */
	public function getInvoiceBookingsms(){
		return $this->invoiceBookingsms;
	}

	/**
	 * @param string $invoiceBookingsms
	 */
	public function setInvoiceBookingsms( string $invoiceBookingsms ) {
		$this->invoiceBookingsms = $invoiceBookingsms;
	}

	/**
	 * @return string
	 */
	public function getInvoiceCheckinsms(){
		return $this->invoiceCheckinsms;
	}

	/**
	 * @param string $invoiceCheckinsms
	 */
	public function setInvoiceCheckinsms( string $invoiceCheckinsms ) {
		$this->invoiceCheckinsms = $invoiceCheckinsms;
	}

	/**
	 * @return string
	 */
	public function getInvoiceCheckoutsms(){
		return $this->invoiceCheckoutsms;
	}

	/**
	 * @param string $invoiceCheckoutsms
	 */
	public function setInvoiceCheckoutsms( string $invoiceCheckoutsms ) {
		$this->invoiceCheckoutsms = $invoiceCheckoutsms;
	}

	/**
	 * @return bool
	 */
	public function isNotification(){
		return $this->notification;
	}

	/**
	 * @param bool $notification
	 */
	public function setNotification( bool $notification ) {
		$this->notification = $notification;
	}

	/**
	 * @return HotelInvoiceTransactionSummary
	 */
	public function getHotelInvoiceTransactionSummary() {
		return $this->hotelInvoiceTransactionSummary;
	}

	/**
	 * @return float
	 */
	public function getVatForHotel(){
		return $this->vatForHotel;
	}

	/**
	 * @param float $vatForHotel
	 */
	public function setVatForHotel( float $vatForHotel ) {
		$this->vatForHotel = $vatForHotel;
	}

	/**
	 * @return float
	 */
	public function getVatForRestaurant(){
		return $this->vatForRestaurant;
	}

	/**
	 * @param float $vatForRestaurant
	 */
	public function setVatForRestaurant( float $vatForRestaurant ) {
		$this->vatForRestaurant = $vatForRestaurant;
	}

	/**
	 * @return float
	 */
	public function getServiceCharge(){
		return $this->serviceCharge;
	}

	/**
	 * @param float $serviceCharge
	 */
	public function setServiceCharge( float $serviceCharge ) {
		$this->serviceCharge = $serviceCharge;
	}

    /**
     * @return string
     */
    public function getInvoiceForRestaurant()
    {
        return $this->invoiceForRestaurant;
    }

    /**
     * @param string $invoiceForRestaurant
     */
    public function setInvoiceForRestaurant($invoiceForRestaurant)
    {
        $this->invoiceForRestaurant = $invoiceForRestaurant;
    }


}

