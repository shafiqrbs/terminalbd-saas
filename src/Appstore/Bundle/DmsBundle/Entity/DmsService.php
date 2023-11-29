<?php

namespace Appstore\Bundle\DmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Service
 *
 * @ORM\Table( name ="dms_service")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\DmsBundle\Repository\DmsServiceRepository")
 */
class DmsService
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsConfig", inversedBy="dmsServices")
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private $dmsConfig;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\DmsBundle\Entity\DentalService", inversedBy="dmsService")
     **/
    private $dentalService;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsInvoiceParticular", mappedBy="dmsService")
     **/
    private $invoiceParticular;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsParticular", mappedBy="service", cascade={"detach","merge"})
     **/
    private $dmsParticulars;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsInvoice", mappedBy="service")
     **/
    private $dmsInvoices;


    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=200, nullable=true)
     */
    private $name;


    /**
     * @var string
     *
     * @ORM\Column(name="serviceFormat", type="string", length=50, nullable=true)
     */
    private $serviceFormat;


     /**
     * @var int
     *
     * @ORM\Column(name="servicePosition", type="smallint", length =1, nullable=true)
     */
    private $servicePosition;

    /**
     * @var integer
     *
     * @ORM\Column(name="serviceHeight", type="integer", length=3, nullable=true)
     */
    private $serviceHeight;

    /**
     * @var int
     *
     * @ORM\Column(name="serviceSorting", type="smallint",  length=2, nullable=true)
     */
    private $serviceSorting = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="serviceHeaderShow", type="boolean", nullable=true)
     */
    private $serviceHeaderShow= false;


    /**
     * @var boolean
     *
     * @ORM\Column(name="serviceShow", type="boolean", nullable=true)
     */
    private $serviceShow = false;


    /**
     * @Gedmo\Slug(fields={"name"})
     * @Doctrine\ORM\Mapping\Column(length=255)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=10, nullable=true)
     */
    private $code;

    /**
     * @var int
     *
     * @ORM\Column(name="sorting", type="smallint",  length=2, nullable=true)
     */
    private $sorting = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="hasQuantity", type="boolean" )
     */
    private $hasQuantity = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean" )
     */
    private $status= true;


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
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
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
    public function getSorting()
    {
        return $this->sorting;
    }

    /**
     * @param int $sorting
     */
    public function setSorting($sorting)
    {
        $this->sorting = $sorting;
    }

    /**
     * @return bool
     */
    public function getHasQuantity()
    {
        return $this->hasQuantity;
    }

    /**
     * @param bool $hasQuantity
     */
    public function setHasQuantity($hasQuantity)
    {
        $this->hasQuantity = $hasQuantity;
    }


    /**
     * @return DmsInvoice
     */
    public function getDmsInvoices()
    {
        return $this->dmsInvoices;
    }

    /**
     * @return DmsParticular
     */
    public function getDmsParticulars()
    {
        return $this->dmsParticulars;
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
     * @return DentalService
     */
    public function getDentalService()
    {
        return $this->dentalService;
    }

    /**
     * @return mixed
     */
    public function getInvoiceParticular()
    {
        return $this->invoiceParticular;
    }

    /**
     * @return string
     */
    public function getServiceFormat()
    {
        return $this->serviceFormat;
    }

    /**
     * @param string $serviceFormat
     * Teeth Format
     * Checkbox with Text Field
     * Text Field
     * Checkbox
     * Text-area
     */

    public function setServiceFormat($serviceFormat)
    {
        $this->serviceFormat = $serviceFormat;
    }


    /**
     * @return int
     */
    public function getServiceHeight()
    {
        return $this->serviceHeight;
    }

    /**
     * @param int $serviceHeight
     */
    public function setServiceHeight($serviceHeight)
    {
        $this->serviceHeight = $serviceHeight;
    }

    /**
     * @return int
     */
    public function getServiceSorting()
    {
        return $this->serviceSorting;
    }

    /**
     * @param int $serviceSorting
     */
    public function setServiceSorting($serviceSorting)
    {
        $this->serviceSorting = $serviceSorting;
    }

    /**
     * @return bool
     */
    public function isServiceHeaderShow()
    {
        return $this->serviceHeaderShow;
    }

    /**
     * @param bool $serviceHeaderShow
     */
    public function setServiceHeaderShow($serviceHeaderShow)
    {
        $this->serviceHeaderShow = $serviceHeaderShow;
    }

    /**
     * @return bool
     */
    public function isServiceShow()
    {
        return $this->serviceShow;
    }

    /**
     * @param bool $serviceShow
     */
    public function setServiceShow($serviceShow)
    {
        $this->serviceShow = $serviceShow;
    }

    /**
     * @return int
     */
    public function getServicePosition()
    {
        return $this->servicePosition;
    }

    /**
     * @param int $servicePosition
     */
    public function setServicePosition($servicePosition)
    {
        $this->servicePosition = $servicePosition;
    }

    /**
     * @param DentalService $dentalService
     */
    public function setDentalService($dentalService)
    {
        $this->dentalService = $dentalService;
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


}

