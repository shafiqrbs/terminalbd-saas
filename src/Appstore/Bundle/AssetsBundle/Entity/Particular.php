<?php

namespace Appstore\Bundle\AssetsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Particular
 *
 * @ORM\Table("assets_particular")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\AssetsBundle\Repository\ParticularRepository")
 */
class Particular
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
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\ParticularType", inversedBy="particulars" )
	 **/
	private  $type;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\ProductLedger", mappedBy="branch" )
	 **/
	private  $ledgers;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Product", mappedBy="branch" )
	 **/
	private  $branchProducts;

	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\AssetsConfig", inversedBy="particulars" )
	 **/
	private  $config;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Distribution", mappedBy="department" )
	 **/
	private  $distributionDepartment;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Product", mappedBy="depreciationStatus" )
	 **/
	private  $products;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\StockItem", mappedBy="branch" )
	 **/
	private  $stockItems;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Disposal", mappedBy="branch" )
	 **/
	private  $disposals;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Distribution", mappedBy="branch" )
	 **/
	private  $distributions;

	/**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


    /**
     * @Gedmo\Slug(fields={"name"}, updatable=false, separator="-")
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;


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
     * @return Particular
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
     * @return Particular
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
     * @return Particular
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
	 * @return ParticularType
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param ParticularType $type
	 */
	public function setType( $type ) {
		$this->type = $type;
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
    public function getBranchProducts()
    {
        return $this->branchProducts;
    }

    /**
     * @return Distribution
     */
    public function getDistributionDepartment()
    {
        return $this->distributionDepartment;
    }

    /**
     * @return Product
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @return Distribution
     */
    public function getDistributions()
    {
        return $this->distributions;
    }


}

