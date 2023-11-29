<?php

namespace Appstore\Bundle\HotelBundle\Entity;

use Appstore\Bundle\HotelBundle\Entity\HotelParticular;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Setting\Bundle\ToolBundle\Entity\ProductUnit;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * HotelPurchaseItem
 *
 * @ORM\Table(name ="hotel_purchase_item")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\HotelBundle\Repository\HotelPurchaseItemRepository")
 */
class HotelPurchaseItem
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelParticular", inversedBy="hotelPurchaseItems" )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $hotelParticular;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelPurchaseReturnItem", mappedBy="hotelPurchaseItem" )
     **/
    private  $hotelPurchaseReturnItems;


    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelPurchase", inversedBy="hotelPurchaseItems" )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $hotelPurchase;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelDamage", mappedBy="hotelPurchaseItem" )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $hotelDamages;

    /**
     * @var float
     *
     * @ORM\Column(name="quantity", type="float")
     */
    private $quantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="salesQuantity", type="integer",nullable=true)
     */
    private $salesQuantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="salesReturnQuantity", type="integer",nullable=true)
     */
    private $salesReturnQuantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="salesReplaceQuantity", type="integer",nullable=true)
     */
    private $salesReplaceQuantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="purchaseReturnQuantity", type="integer",nullable=true)
     */
    private $purchaseReturnQuantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="damageQuantity", type="integer",nullable=true)
     */
    private $damageQuantity;


    /**
     * @var integer
     *
     * @ORM\Column(name="remainingQuantity", type="integer",nullable=true)
     */
    private $remainingQuantity;


    /**
     * @var float
     *
     * @ORM\Column(name="purchasePrice", type="float")
     */
    private $purchasePrice;


    /**
     * @var float
     *
     * @ORM\Column(name="actualPurchasePrice", type="float")
     */
    private $actualPurchasePrice;


    /**
     * @var integer
     *
     * @ORM\Column(name="code", type="integer", nullable = true)
     */
    private $code;


    /**
     * @var float
     *
     * @ORM\Column(name="salesPrice", type="float", nullable = true)
     */
    private $salesPrice;

    /**
     * @var float
     *
     * @ORM\Column(name="purchaseSubTotal", type="float", nullable = true)
     */
    private $purchaseSubTotal;


    /**
     * @var string
     *
     * @ORM\Column(name="barcode", type="string",  nullable = true)
     */
    private $barcode;


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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return HotelPurchaseItem
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set purchasePrice
     *
     * @param float $purchasePrice
     *
     * @return HotelPurchase
     */
    public function setPurchasePrice($purchasePrice)
    {
        $this->purchasePrice = $purchasePrice;

        return $this;
    }

    /**
     * Get purchasePrice
     *
     * @return float
     */
    public function getPurchasePrice()
    {
        return $this->purchasePrice;
    }




    /**
     * Set salesPrice
     *
     * @param float $salesPrice
     *
     * @return HotelPurchaseItem
     */
    public function setSalesPrice($salesPrice)
    {
        $this->salesPrice = $salesPrice;

        return $this;
    }

    /**
     * Get salesPrice
     *
     * @return float
     */
    public function getSalesPrice()
    {
        return $this->salesPrice;
    }

    /**
     * @return string
     */
    public function getBarcode()
    {
        return $this->barcode;
    }

    /**
     * @param string $barcode
     */
    public function setBarcode($barcode)
    {
        $this->barcode = $barcode;
    }

    /**
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param integer $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }


    /**
     * @return float
     */
    public function getPurchaseSubTotal()
    {
        return $this->purchaseSubTotal;
    }

    /**
     * @param float $purchaseSubTotal
     */
    public function setPurchaseSubTotal($purchaseSubTotal)
    {
        $this->purchaseSubTotal = $purchaseSubTotal;
    }

    /**
     * @return HotelPurchase
     */
    public function getHotelPurchase()
    {
        return $this->hotelPurchase;
    }

    /**
     * @param HotelPurchase $hotelPurchase
     */
    public function setHotelPurchase($hotelPurchase)
    {
        $this->hotelPurchase = $hotelPurchase;
    }

    /**
     * @return HotelParticular
     */
    public function getHotelParticular()
    {
        return $this->hotelParticular;
    }

    /**
     * @param HotelParticular $hotelParticular
     */
    public function setHotelParticular($hotelParticular)
    {
        $this->hotelParticular = $hotelParticular;
    }

    /**
     * @return float
     */
    public function getActualPurchasePrice()
    {
        return $this->actualPurchasePrice;
    }

    /**
     * @param float $actualPurchasePrice
     */
    public function setActualPurchasePrice($actualPurchasePrice)
    {
        $this->actualPurchasePrice = $actualPurchasePrice;
    }

    /**
     * @return int
     */
    public function getSalesQuantity()
    {
        return $this->salesQuantity;
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
    public function getSalesReturnQuantity()
    {
        return $this->salesReturnQuantity;
    }

    /**
     * @param int $salesReturnQuantity
     */
    public function setSalesReturnQuantity($salesReturnQuantity)
    {
        $this->salesReturnQuantity = $salesReturnQuantity;
    }

    /**
     * @return int
     */
    public function getSalesReplaceQuantity()
    {
        return $this->salesReplaceQuantity;
    }

    /**
     * @param int $salesReplaceQuantity
     */
    public function setSalesReplaceQuantity($salesReplaceQuantity)
    {
        $this->salesReplaceQuantity = $salesReplaceQuantity;
    }

    /**
     * @return int
     */
    public function getPurchaseReturnQuantity()
    {
        return $this->purchaseReturnQuantity;
    }

    /**
     * @param int $purchaseReturnQuantity
     */
    public function setPurchaseReturnQuantity($purchaseReturnQuantity)
    {
        $this->purchaseReturnQuantity = $purchaseReturnQuantity;
    }

    /**
     * @return int
     */
    public function getDamageQuantity()
    {
        return $this->damageQuantity;
    }

    /**
     * @param int $damageQuantity
     */
    public function setDamageQuantity($damageQuantity)
    {
        $this->damageQuantity = $damageQuantity;
    }

    /**
     * @return int
     */
    public function getRemainingQuantity()
    {
        return $this->remainingQuantity;
    }

    /**
     * @param int $remainingQuantity
     */
    public function setRemainingQuantity($remainingQuantity)
    {
        $this->remainingQuantity = $remainingQuantity;
    }

    /**
     * @return HotelPurchaseReturnItem
     */
    public function getHotelPurchaseReturnItems()
    {
        return $this->hotelPurchaseReturnItems;
    }

    /**
     * @return HotelDamage
     */
    public function getHotelDamages()
    {
        return $this->hotelDamages;
    }


}

