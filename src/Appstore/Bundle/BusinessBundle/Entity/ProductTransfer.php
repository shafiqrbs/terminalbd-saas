<?php

namespace Appstore\Bundle\BusinessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BusinessWearHouse
 *
 * @ORM\Table( name ="business_product_transfer")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\BusinessBundle\Repository\ProductTransferRepository")
 */
class ProductTransfer
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\BusinessBundle\Entity\BusinessConfig", inversedBy="wearHouses" , cascade={"detach","merge"} )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $businessConfig;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\BusinessBundle\Entity\BusinessParticular", inversedBy="wearHouse")
     **/
    private $fromProduct;

	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\BusinessBundle\Entity\BusinessParticular", inversedBy="wearHouse")
	 **/
	private $toProduct;

	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\BusinessBundle\Entity\WearHouse", inversedBy="wearHouse")
	 **/
	private $wearHouse;


    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer", length=10, nullable=true)
     */
    private $quantity;


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
	 * @return BusinessParticular
	 */
	public function getFromProduct() {
		return $this->fromProduct;
	}

	/**
	 * @param BusinessParticular $fromProduct
	 */
	public function setFromProduct( $fromProduct ) {
		$this->fromProduct = $fromProduct;
	}

	/**
	 * @return BusinessParticular
	 */
	public function getToProduct() {
		return $this->toProduct;
	}

	/**
	 * @param BusinessParticular $toProduct
	 */
	public function setToProduct( $toProduct ) {
		$this->toProduct = $toProduct;
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
	 * @return int
	 */
	public function getQuantity(): int {
		return $this->quantity;
	}

	/**
	 * @param int $quantity
	 */
	public function setQuantity( int $quantity ) {
		$this->quantity = $quantity;
	}

	/**
	 * @return bool
	 */
	public function isStatus(): bool {
		return $this->status;
	}

	/**
	 * @param bool $status
	 */
	public function setStatus( bool $status ) {
		$this->status = $status;
	}


}

