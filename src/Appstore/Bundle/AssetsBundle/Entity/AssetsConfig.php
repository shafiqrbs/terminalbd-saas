<?php

namespace Appstore\Bundle\AssetsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TallyConfig
 *
 * @ORM\Table( name ="assets_config")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\AssetsBundle\Repository\AssetsConfigRepository")
 */
class AssetsConfig
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
     * @ORM\OneToOne(targetEntity="Setting\Bundle\ToolBundle\Entity\GlobalOption", inversedBy="assetsConfig" , cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private $globalOption;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\StockItem", mappedBy="config" , cascade={"persist", "remove"})
     **/
    private $stockItems;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\DepreciationModel", mappedBy="config" , cascade={"persist", "remove"})
     **/
    private $depreciationModels;

     /**
     * @ORM\OneToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Depreciation", mappedBy="config" , cascade={"persist", "remove"})
     **/
    private $depreciation;

     /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\DepreciationBatch", mappedBy="config" , cascade={"persist", "remove"})
     **/
    private $depreciationBatches;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Particular", mappedBy="config" , cascade={"persist", "remove"})
     **/
    private $particulars;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Item", mappedBy="config" , cascade={"persist", "remove"})
     **/
    private $items;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Product", mappedBy="config")
     **/
    protected $products;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Brand", mappedBy="config" , cascade={"persist", "remove"})
     **/
    private $brands;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Sales", mappedBy="config" , cascade={"persist", "remove"})
     **/
    private $sales;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Category", mappedBy="config" , cascade={"persist", "remove"})
     **/
    private $categories;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Damage", mappedBy="config" , cascade={"persist", "remove"})
     **/
    private $damages;


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
     * @var integer
     *
     * @ORM\Column(name="profitPercent", type="smallint",  nullable=true)
     */
    private $profitPercent = 20;

    /**
     * @var smallint
     *
     * @ORM\Column(name="vatPercentage", type="smallint",  nullable=true)
     */
    private $vatPercentage;

    /**
     * @var smallint
     *
     * @ORM\Column(name="fontSizeLabel", type="smallint",  nullable=true)
     */
    private $fontSizeLabel;

    /**
     * @var smallint
     *
     * @ORM\Column(name="fontSizeValue", type="smallint",  nullable=true)
     */
    private $fontSizeValue;

    /**
     * @var string
     *
     * @ORM\Column(name="vatRegNo", type="string",  nullable=true)
     */
    private $vatRegNo;


    /**
     * @var string
     *
     * @ORM\Column(name="businessModel", type="string",  nullable=true)
     */
    private $businessModel='general';


    /**
     * @var float
     *
     * @ORM\Column(name="unitCommission", type="float",  nullable=true)
     */
    private $unitCommission = 0;


    /**
     * @var boolean
     *
     * @ORM\Column(name="vatEnable", type="boolean",  nullable=true)
     */
    private $vatEnable = false;

    /**
     * @var smallint
     *
     * @ORM\Column(name="invoiceWidth", type="smallint",  nullable=true)
     */
    private $invoiceWidth = 0;


    /**
     * @var smallint
     *
     * @ORM\Column(name="printMarginTop", type="smallint",  nullable=true)
     */
    private $printTopMargin = 0;


    /**
     * @var smallint
     *
     * @ORM\Column(name="printMarginBottom", type="smallint",  nullable=true)
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
     * @var smallint
     *
     * @ORM\Column(name="printMarginReportTop", type="smallint",  nullable=true)
     */
    private $printMarginReportTop = 0;

    /**
     * @var smallint
     *
     * @ORM\Column(name="printMarginReportLeft", type="smallint",  nullable=true)
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
     * @ORM\Column(name="borderWidth", type="smallint", length=2, nullable = true)
     */
    private $borderWidth = 0;


    /**
     * @var string
     *
     * @ORM\Column(name="borderColor", type="string", length=25,nullable = true)
     */
    private $borderColor;


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
     * @var smallint
     *
     * @ORM\Column(name="printLeftMargin", type="smallint", nullable = true)
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
     * @ORM\Column(name="regularInvoice", type="boolean",  nullable=true)
     */
    private $regularInvoice = true;

    /**
     * @var boolean
     *
     * @ORM\Column(name="posInvoice", type="boolean",  nullable=true)
     */
    private $posInvoice = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="workOrderInvoice", type="boolean",  nullable=true)
     */
    private $workOrderInvoice = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="assetsIssue", type="boolean",  nullable=true)
     */
    private $assetsIssue = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="inventoryIssue", type="boolean",  nullable=true)
     */
    private $inventoryIssue = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="requisitionIssue", type="boolean",  nullable=true)
     */
    private $requisitionIssue = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="serviceInvoice", type="boolean",  nullable=true)
     */
    private $serviceInvoice = true;

    /**
     * @var boolean
     *
     * @ORM\Column(name="localPurchase", type="boolean",  nullable=true)
     */
    private $localPurchase = true;

    /**
     * @var boolean
     *
     * @ORM\Column(name="foreignPurchase", type="boolean",  nullable=true)
     */
    private $foreignPurchase = false;

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
     * @var boolean
     *
     * @ORM\Column(name="isBranch", type="boolean")
     */
    private $isBranch = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isAttribute", type="boolean")
     */
    private $isAttribute = false;

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
     * @return smallint
     */
    public function getPrintLeftMargin()
    {
        return $this->printLeftMargin;
    }

    /**
     * @param smallint $printLeftMargin
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
     * @return smallint
     */
    public function getPrintMarginBottom()
    {
        return $this->printMarginBottom;
    }

    /**
     * @param smallint $printMarginBottom
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
     * @return smallint
     */
    public function getVatPercentage()
    {
        return $this->vatPercentage;
    }

    /**
     * @param smallint $vatPercentage
     */
    public function setVatPercentage($vatPercentage)
    {
        $this->vatPercentage = $vatPercentage;
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
     * @return smallint
     */
    public function getPrintTopMargin()
    {
        return $this->printTopMargin;
    }

    /**
     * @param smallint $printTopMargin
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
     * @return smallint
     */
    public function getPrintMarginReportTop()
    {
        return $this->printMarginReportTop;
    }

    /**
     * @param smallint $printMarginReportTop
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
     * @return smallint
     */
    public function getPrintMarginReportLeft()
    {
        return $this->printMarginReportLeft;
    }

    /**
     * @param smallint $printMarginReportLeft
     */
    public function setPrintMarginReportLeft($printMarginReportLeft)
    {
        $this->printMarginReportLeft = $printMarginReportLeft;
    }

    /**
     * @return smallint
     */
    public function getFontSizeLabel()
    {
        return $this->fontSizeLabel;
    }

    /**
     * @param smallint $fontSizeLabel
     */
    public function setFontSizeLabel($fontSizeLabel)
    {
        $this->fontSizeLabel = $fontSizeLabel;
    }

    /**
     * @return smallint
     */
    public function getFontSizeValue()
    {
        return $this->fontSizeValue;
    }

    /**
     * @param smallint $fontSizeValue
     */
    public function setFontSizeValue($fontSizeValue)
    {
        $this->fontSizeValue = $fontSizeValue;
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
     * @param TallyConfig $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return TallyConfig
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
        return 'uploads/domain/'.$this->getGlobalOption()->getId().'/tally';
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
     * @return TallyConfig
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
    public function getBusinessModel(){
        return $this->businessModel;
    }

    /**
     * @param string $businessModel
     */
    public function setBusinessModel( string $businessModel ) {
        $this->businessModel = $businessModel;
    }


    /**
     * @return float
     */
    public function getUnitCommission()
    {
        return $this->unitCommission;
    }

    /**
     * @param float $unitCommission
     */
    public function setUnitCommission($unitCommission)
    {
        $this->unitCommission = $unitCommission;
    }

    /**
     * @return smallint
     */
    public function getInvoiceWidth()
    {
        return $this->invoiceWidth;
    }

    /**
     * @param smallint $invoiceWidth
     */
    public function setInvoiceWidth($invoiceWidth)
    {
        $this->invoiceWidth = $invoiceWidth;
    }

    /**
     * @return string
     */
    public function getBorderWidth()
    {
        return $this->borderWidth;
    }

    /**
     * @param string $borderWidth
     */
    public function setBorderWidth($borderWidth)
    {
        $this->borderWidth = $borderWidth;
    }

    /**
     * @return string
     */
    public function getBorderColor()
    {
        return $this->borderColor;
    }

    /**
     * @param string $borderColor
     */
    public function setBorderColor($borderColor)
    {
        $this->borderColor = $borderColor;
    }

    /**
     * @return StockItem
     */
    public function getStockItems()
    {
        return $this->stockItems;
    }

    /**
     * @return Damage
     */
    public function getDamages()
    {
        return $this->damages;
    }

    /**
     * @return Sales
     */
    public function getSales()
    {
        return $this->sales;
    }

    /**
     * @return Purchase
     */
    public function getPurchase()
    {
        return $this->purchase;
    }



    /**
     * @return Category
     */
    public function getParentCategories()
    {
        $arrs = array();
        foreach ($this->getCategories() as $cat){

            /* @var $cat Category */

            if($cat->getLevel() == 1){
                $arrs[] = $cat;
            }
            return $arrs;
        }
        return false;

    }

    /**
     * @return Category
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @return Item
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return Brand
     */
    public function getBrands()
    {
        return $this->brands;
    }

    /**
     * @return boolean
     */
    public function getisBranch()
    {
        return $this->isBranch;
    }

    /**
     * @param boolean $isBranch
     */
    public function setIsBranch($isBranch)
    {
        $this->isBranch = $isBranch;
    }

    /**
     * @return boolean
     */
    public function isAttribute()
    {
        return $this->isAttribute;
    }

    /**
     * @param boolean $isAttribute
     */
    public function setIsAttribute($isAttribute)
    {
        $this->isAttribute = $isAttribute;
    }

    /**
     * @return int
     */
    public function getProfitPercent()
    {
        return $this->profitPercent;
    }

    /**
     * @param int $profitPercent
     */
    public function setProfitPercent($profitPercent)
    {
        $this->profitPercent = $profitPercent;
    }

    /**
     * @return bool
     */
    public function isRegularInvoice()
    {
        return $this->regularInvoice;
    }

    /**
     * @param bool $regularInvoice
     */
    public function setRegularInvoice($regularInvoice)
    {
        $this->regularInvoice = $regularInvoice;
    }

    /**
     * @return bool
     */
    public function isPosInvoice()
    {
        return $this->posInvoice;
    }

    /**
     * @param bool $posInvoice
     */
    public function setPosInvoice($posInvoice)
    {
        $this->posInvoice = $posInvoice;
    }

    /**
     * @return bool
     */
    public function isWorkOrderInvoice()
    {
        return $this->workOrderInvoice;
    }

    /**
     * @param bool $workOrderInvoice
     */
    public function setWorkOrderInvoice($workOrderInvoice)
    {
        $this->workOrderInvoice = $workOrderInvoice;
    }

    /**
     * @return bool
     */
    public function isAssetsIssue()
    {
        return $this->assetsIssue;
    }

    /**
     * @param bool $assetsIssue
     */
    public function setAssetsIssue($assetsIssue)
    {
        $this->assetsIssue = $assetsIssue;
    }

    /**
     * @return bool
     */
    public function isInventoryIssue()
    {
        return $this->inventoryIssue;
    }

    /**
     * @param bool $inventoryIssue
     */
    public function setInventoryIssue($inventoryIssue)
    {
        $this->inventoryIssue = $inventoryIssue;
    }

    /**
     * @return bool
     */
    public function isRequisitionIssue()
    {
        return $this->requisitionIssue;
    }

    /**
     * @param bool $requisitionIssue
     */
    public function setRequisitionIssue($requisitionIssue)
    {
        $this->requisitionIssue = $requisitionIssue;
    }

    /**
     * @return bool
     */
    public function isLocalPurchase()
    {
        return $this->localPurchase;
    }

    /**
     * @param bool $localPurchase
     */
    public function setLocalPurchase($localPurchase)
    {
        $this->localPurchase = $localPurchase;
    }

    /**
     * @return bool
     */
    public function getForeignPurchase()
    {
        return $this->foreignPurchase;
    }

    /**
     * @param bool $foreignPurchase
     */
    public function setForeignPurchase($foreignPurchase)
    {
        $this->foreignPurchase = $foreignPurchase;
    }

    /**
     * @return bool
     */
    public function isServiceInvoice()
    {
        return $this->serviceInvoice;
    }

    /**
     * @param bool $serviceInvoice
     */
    public function setServiceInvoice($serviceInvoice)
    {
        $this->serviceInvoice = $serviceInvoice;
    }



    /**
     * @return Particular
     */
    public function getParticulars()
    {
        return $this->particulars;
    }

    /**
     * @return DepreciationModel
     */
    public function getDepreciationModels()
    {
        return $this->depreciationModels;
    }

    /**
     * @return DepreciationBatch
     */
    public function getDepreciationBatches()
    {
        return $this->depreciationBatches;
    }

    /**
     * @return Depreciation
     */
    public function getDepreciation()
    {
        return $this->depreciation;
    }


}

