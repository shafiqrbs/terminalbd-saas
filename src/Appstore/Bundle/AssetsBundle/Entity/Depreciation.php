<?php

namespace Appstore\Bundle\AssetsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Product\Bundle\ProductBundle\Entity\Category;

/**
 * Depreciation
 *
 * @ORM\Table("assets_depreciation")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\AssetsBundle\Repository\DepreciationRepository")
 */
class Depreciation
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
     * @ORM\OneToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\AssetsConfig", inversedBy="depreciation" )
     **/
    private  $config;


     /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\DepreciationModel", mappedBy="depreciation" )
     **/
    private  $depreciationModels;


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
	private $depreciationYear = 5;


	/**
	 * @var string
	 *
	 * @ORM\Column(name="depreciationPulse", type="string", nullable=true)
	 */
	private $depreciationPulse = 'year';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="model", type="string", nullable=true)
	 */
	private $model ="straight-line";

	/**
	 * @var string
	 *
	 * @ORM\Column(name="yearEnd", type="string", nullable=true)
	 */
	private $yearEnd ='calendar';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="policy", type="string", nullable=true)
	 */
	private $policy = 'straight-line';

    /**
	 * @var string
	 *
	 * @ORM\Column(name="effected", type="string", nullable=true)
	 */
	 private $effected;



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
	 * @return string
	 */
	public function getYearEnd() {
		return $this->yearEnd;
	}

	/**
	 * @param string $yearEnd
	 * Calendar Year
	 * Fiscal Year
	 *
	 */
	public function setYearEnd( $yearEnd ) {
		$this->yearEnd = $yearEnd;
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
	 * @return string
	 */
	public function getPolicy() {
		return $this->policy;
	}

	/**
	 * @param string $policy
	 * Straight line
	 * Reducing Balance
	 */
	public function setPolicy( $policy ) {
		$this->policy = $policy;
	}


	/**
	 * @return string
	 */
	public function getModel() {
		return $this->model;
	}

	/**
	 * @param string $model
	 * Cost model
	 * Revaluation model
	 */
	public function setModel( $model ) {
		$this->model = $model;
	}

	/**
	 * @return string
	 */
	public function getDepreciationPulse() {
		return $this->depreciationPulse;
	}

	/**
	 * @param string $depreciationPulse
	 */
	public function setDepreciationPulse( $depreciationPulse ) {
		$this->depreciationPulse = $depreciationPulse;
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
     * @return string
     */
    public function getEffected()
    {
        return $this->effected;
    }

    /**
     * @param string $effected
     */
    public function setEffected($effected)
    {
        $this->effected = $effected;
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
     * @return DepreciationModel
     */
    public function getDepreciationModels()
    {
        return $this->depreciationModels;
    }


}

