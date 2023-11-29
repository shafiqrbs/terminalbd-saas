<?php

namespace Appstore\Bundle\DmsBundle\Entity;

use Core\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Setting\Bundle\LocationBundle\Entity\Location;
use Setting\Bundle\ToolBundle\Entity\ProductUnit;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DmsParticular
 *
 * @ORM\Table( name = "dms_particular")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\DmsBundle\Repository\DmsParticularRepository")
 */
class DmsParticular
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsConfig", inversedBy="dmsParticulars")
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private $dmsConfig;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsInvoice", mappedBy="referredDoctor")
     **/
    private $dmsInvoice;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsDoctorInvoice", mappedBy="assignDoctor")
     **/
    private $dmsDoctorInvoices;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsInvoiceAccessories", mappedBy="dmsParticular")
     **/
    private $dmsInvoiceAccessories;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsInvoice", mappedBy="assignDoctor")
     **/
    private $assignDoctorInvoices;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsService", inversedBy="dmsParticulars")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @ORM\OrderBy({"sorting" = "ASC"})
     **/
    private $service;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsInvoiceParticular", mappedBy="dmsParticular" )
     * @ORM\OrderBy({"id" = "DESC"})
     **/
    private $invoiceParticular;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsPurchaseItem", mappedBy="dmsParticular" )
     * @ORM\OrderBy({"id" = "ASC"})
     **/
    private $dmsPurchaseItems;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsTreatmentPlan", mappedBy="dmsParticular" )
     * @ORM\OrderBy({"id" = "DESC"})
     **/
    private $dmsTreatmentPlans;


    /**
     * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User", inversedBy="dmsParticularOperator" )
     **/
    private $assignOperator;

    /**
     * @ORM\OneToOne(targetEntity="Core\UserBundle\Entity\User", inversedBy="dmsParticularDoctor" )
     **/
    private $assignDoctor;

    /**
     * @ORM\ManyToOne(targetEntity="Setting\Bundle\LocationBundle\Entity\Location", inversedBy="dmsParticulars")
     **/
    protected $location;

    /**
     * @ORM\ManyToOne(targetEntity="Setting\Bundle\ToolBundle\Entity\ProductUnit", inversedBy="dmsParticulars" )
     **/
    private  $unit;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;


    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="smallint", length=3, nullable=true)
     */
    private $quantity = 1;


    /**
     * @var string
     *
     * @ORM\Column(name="room", type="string", length=100, nullable=true)
     */
    private $room;


    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="instruction", type="text", nullable=true)
     */
    private $instruction;


    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", nullable=true)
     */
    private $price;

    /**
     * @var float
     *
     * @ORM\Column(name="minimumPrice", type="float", nullable=true)
     */
    private $minimumPrice;

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
     * @var string
     *
     * @ORM\Column(name="purchaseAverage", type="decimal", nullable=true)
     */
    private $purchaseAverage;

    /**
     * @var string
     *
     * @ORM\Column(name="purchasePrice", type="decimal", nullable=true)
     */
    private $purchasePrice;

    /**
     * @var string
     *
     * @ORM\Column(name="phoneNo", type="string", length=128, nullable=true)
     */
    private $phoneNo;

    /**
     * @var string
     *
     * @ORM\Column(name="startHour", type="string", length=10, nullable=true)
     */
    private $startHour;

    /**
     * @var string
     *
     * @ORM\Column(name="endHour", type="string", length=10, nullable=true)
     */
    private $endHour;

    /**
     * @var array
     *
     * @ORM\Column(name="weeklyOffDay", type="array", nullable=true)
     */
    private $weeklyOffDay;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="doctorPrescription", type="text", nullable=true)
     */
    private $doctorPrescription;


    /**
     * @var string
     *
     * @ORM\Column(name="designation", type="string", length=256, nullable=true)
     */
    private $designation;


    /**
     * @var integer
     *
     * @ORM\Column(name="code", type="integer",  nullable=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="dmsParticularCode", type="string", length=10, nullable=true)
     */
    private $dmsParticularCode;


    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=15, nullable=true)
     */
    private $mobile;

    /**
     * @var string
     *
     * @ORM\Column(name="particularCode", type="string", length=15, nullable=true)
     */
    private $particularCode;

    /**
     * @var boolean
     *
     * @ORM\Column(name="testDuration", type="boolean" )
     */
    private $testDuration = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="reportFormat", type="boolean" )
     */
    private $reportFormat = false;


    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean" )
     */
    private $status = true;

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
     *
     * @param string $name
     *
     * @return DmsParticular
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

    public function getReferred()
    {

        return $this->dmsParticularCode . ' - ' . $this->name . ' (' . $this->mobile . ')/' . $this->getService()->getName();
    }

    public function getDoctor()
    {
        return $this->dmsParticularCode . ' - ' . $this->name;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return DmsParticular
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
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
     *
     * @param float $price
     *
     * @return DmsParticular
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
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
     * @return mixed
     */
    public function getAssignOperator()
    {
        return $this->assignOperator;
    }

    /**
     * @param User $assignOperator
     */
    public function setAssignOperator($assignOperator)
    {
        $this->assignOperator = $assignOperator;
    }

    /**
     * @return string
     */
    public function getDmsParticularCode()
    {
        return $this->dmsParticularCode;
    }

    /**
     * @param string $dmsParticularCode
     */
    public function setDmsParticularCode($dmsParticularCode)
    {
        $this->dmsParticularCode = $dmsParticularCode;
    }

    /**
     * @return Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param Service $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }



    /**
     * @return string
     */
    public function getStartHour()
    {
        return $this->startHour;
    }

    /**
     * @param string $startHour
     */
    public function setStartHour($startHour)
    {
        $this->startHour = $startHour;
    }

    /**
     * @return string
     */
    public function getEndHour()
    {
        return $this->endHour;
    }

    /**
     * @param string $endHour
     */
    public function setEndHour($endHour)
    {
        $this->endHour = $endHour;
    }

    /**
     * @return array
     */
    public function getWeeklyOffDay()
    {
        return $this->weeklyOffDay;
    }

    /**
     * @param array $weeklyOffDay
     */
    public function setWeeklyOffDay($weeklyOffDay)
    {
        $this->weeklyOffDay = $weeklyOffDay;
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
     * @return string
     */
    public function getPhoneNo()
    {
        return $this->phoneNo;
    }

    /**
     * @param string $phoneNo
     */
    public function setPhoneNo($phoneNo)
    {
        $this->phoneNo = $phoneNo;
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
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param Location $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param string $mobile
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Sets file.
     *
     * @param DmsParticular $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return DmsParticular
     */
    public function getFile()
    {
        return $this->file;
    }

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir() . '/' . $this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir() . '/' . $this->path;
    }

    protected function getUploadRootDir()
    {
        return __DIR__ . '/../../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'uploads/domain/' . $this->getHospitalConfig()->getGlobalOption()->getId() . '/hms/';
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
     * @return string
     */
    public function getInstruction()
    {
        return $this->instruction;
    }

    /**
     * @param string $instruction
     */
    public function setInstruction($instruction)
    {
        $this->instruction = $instruction;
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
    public function getSalesQuantity()
    {
        return $this->salesQuantity;
    }


    /**
     * @return User
     */
    public function getAssignDoctor()
    {
        return $this->assignDoctor;
    }

    /**
     * @param User $assignDoctor
     */
    public function setAssignDoctor($assignDoctor)
    {
        $this->assignDoctor = $assignDoctor;
    }


    /**
     * @return string
     */
    public function getDesignation()
    {
        return $this->designation;
    }

    /**
     * @param string $designation
     */
    public function setDesignation($designation)
    {
        $this->designation = $designation;
    }


    /**
     * @return bool
     */
    public function getTestDuration()
    {
        return $this->testDuration;
    }

    /**
     * @param bool $testDuration
     */
    public function setTestDuration($testDuration)
    {
        $this->testDuration = $testDuration;
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
     * @return bool
     */
    public function getReportFormat()
    {
        return $this->reportFormat;
    }

    /**
     * @param bool $reportFormat
     */
    public function setReportFormat($reportFormat)
    {
        $this->reportFormat = $reportFormat;
    }


    /**
     * @return DmsInvoice
     */
    public function getDmsInvoice()
    {
        return $this->dmsInvoice;
    }

    /**
     * @return DmsConfig
     */
    public function getDmsConfig()
    {
        return $this->dmsConfig;
    }

    /**
     * @param DmsConfig $dmsConfig
     */
    public function setDmsConfig($dmsConfig)
    {
        $this->dmsConfig = $dmsConfig;
    }

    /**
     * @return DmsInvoiceParticular
     */
    public function getInvoiceParticular()
    {
        return $this->invoiceParticular;
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
     * @return DmsInvoice
     */
    public function getAssignDoctorInvoices()
    {
        return $this->assignDoctorInvoices;
    }

    /**
     * @return DmsPurchaseItem
     */
    public function getDmsPurchaseItems()
    {
        return $this->dmsPurchaseItems;
    }

    /**
     * @return DmsTreatmentPlan
     */
    public function getDmsTreatmentPlans()
    {
        return $this->dmsTreatmentPlans;
    }

    /**
     * @return float
     */
    public function getMinimumPrice()
    {
        return $this->minimumPrice;
    }

    /**
     * @param float $minimumPrice
     */
    public function setMinimumPrice($minimumPrice)
    {
        $this->minimumPrice = $minimumPrice;
    }

    /**
     * @return string
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * @param string $room
     */
    public function setRoom($room)
    {
        $this->room = $room;
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
     * @return DmsInvoiceAccessories
     */
    public function getDmsInvoiceAccessories()
    {
        return $this->dmsInvoiceAccessories;
    }

    /**
     * @return string
     */
    public function getDoctorPrescription()
    {
        return $this->doctorPrescription;
    }

    /**
     * @param string $doctorPrescription
     */
    public function setDoctorPrescription($doctorPrescription)
    {
        $this->doctorPrescription = $doctorPrescription;
    }


}