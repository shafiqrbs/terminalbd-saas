<?php

namespace Appstore\Bundle\DmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;

/**
 * Service
 *
 * @ORM\Table( name ="dms_dental_service")
 * @ORM\Entity(repositoryClass="")
 */
class DentalService
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
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsService", mappedBy="dentalService")
     **/
    private $dmsService;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=50, nullable=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=10, nullable=true)
     */
    private $code;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsParticular", mappedBy="service")
     **/
    private $dmsParticulars;



    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsInvoice", mappedBy="service")
     **/
    private $dmsInvoices;


    /**
     * @var int
     *
     * @ORM\Column(name="sorting", type="smallint",  length=2, nullable=true)
     */
    private $sorting = 0;

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
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
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
     * @return mixed
     */
    public function getDmsService()
    {
        return $this->dmsService;
    }

    /**
     * @param mixed $dmsService
     */
    public function setDmsService($dmsService)
    {
        $this->dmsService = $dmsService;
    }


}

