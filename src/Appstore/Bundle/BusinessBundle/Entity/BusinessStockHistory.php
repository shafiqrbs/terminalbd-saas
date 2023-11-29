<?php

namespace Appstore\Bundle\BusinessBundle\Entity;

use Core\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Setting\Bundle\ToolBundle\Entity\ProductUnit;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * BusinessStockHistory
 *
 * @ORM\Table( name = "business_stock_history")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\BusinessBundle\Repository\BusinessStockHistoryRepository")
 */
class BusinessStockHistory
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\BusinessBundle\Entity\BusinessConfig" , cascade={"detach","merge"} )
     **/
    private  $businessConfig;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\BusinessBundle\Entity\BusinessParticular", inversedBy="stockHistory" )
     * @ORM\OrderBy({"sorting" = "ASC"})
     **/
    private $item;

     /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\BusinessBundle\Entity\BusinessPurchaseItem")
     * @ORM\OrderBy({"sorting" = "ASC"})
     **/
    private $purchaseItem;


     /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\BusinessBundle\Entity\BusinessPurchaseReturnItem")
     * @ORM\OrderBy({"sorting" = "ASC"})
     **/
    private $purchaseReturnItem;


    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\BusinessBundle\Entity\BusinessInvoiceParticular")
     * @ORM\OrderBy({"sorting" = "ASC"})
     **/
    private $salesItem;


     /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\BusinessBundle\Entity\BusinessInvoiceReturn")
     * @ORM\OrderBy({"sorting" = "ASC"})
     **/
    private $salesReturnItem;


    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\BusinessBundle\Entity\BusinessDamage")
     * @ORM\OrderBy({"sorting" = "ASC"})
     **/
    private $damageItem;


    /**
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User")
     **/
    private  $createdBy;


    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $process;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $opening = 0;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $quantity = 0;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $openingQuantity = 0;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $closingQuantity = 0;


    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $purchaseQuantity = 0;


     /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $purchaseReturnQuantity = 0;


     /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $salesQuantity = 0;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $salesReturnQuantity = 0;


    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $damageQuantity = 0;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $bonusQuantity = 0;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $bonusSalesQuantity = 0;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $spoilQuantity = 0;



    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;


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
    public function getBusinessConfig()
    {
        return $this->businessConfig;
    }

    /**
     * @param mixed $businessConfig
     */
    public function setBusinessConfig($businessConfig)
    {
        $this->businessConfig = $businessConfig;
    }

    /**
     * @return mixed
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param mixed $item
     */
    public function setItem($item)
    {
        $this->item = $item;
    }

    /**
     * @return mixed
     */
    public function getPurchaseItem()
    {
        return $this->purchaseItem;
    }

    /**
     * @param mixed $purchaseItem
     */
    public function setPurchaseItem($purchaseItem)
    {
        $this->purchaseItem = $purchaseItem;
    }

    /**
     * @return mixed
     */
    public function getPurchaseReturnItem()
    {
        return $this->purchaseReturnItem;
    }

    /**
     * @param mixed $purchaseReturnItem
     */
    public function setPurchaseReturnItem($purchaseReturnItem)
    {
        $this->purchaseReturnItem = $purchaseReturnItem;
    }

    /**
     * @return mixed
     */
    public function getSalesItem()
    {
        return $this->salesItem;
    }

    /**
     * @param mixed $salesItem
     */
    public function setSalesItem($salesItem)
    {
        $this->salesItem = $salesItem;
    }

    /**
     * @return mixed
     */
    public function getSalesReturnItem()
    {
        return $this->salesReturnItem;
    }

    /**
     * @param mixed $salesReturnItem
     */
    public function setSalesReturnItem($salesReturnItem)
    {
        $this->salesReturnItem = $salesReturnItem;
    }

    /**
     * @return mixed
     */
    public function getDamageItem()
    {
        return $this->damageItem;
    }

    /**
     * @param mixed $damageItem
     */
    public function setDamageItem($damageItem)
    {
        $this->damageItem = $damageItem;
    }

    /**
     * @return float
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param float $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return float
     */
    public function getPurchaseQuantity()
    {
        return $this->purchaseQuantity;
    }

    /**
     * @param float $purchaseQuantity
     */
    public function setPurchaseQuantity($purchaseQuantity)
    {
        $this->purchaseQuantity = $purchaseQuantity;
    }

    /**
     * @return float
     */
    public function getPurchaseReturnQuantity()
    {
        return $this->purchaseReturnQuantity;
    }

    /**
     * @param float $purchaseReturnQuantity
     */
    public function setPurchaseReturnQuantity($purchaseReturnQuantity)
    {
        $this->purchaseReturnQuantity = $purchaseReturnQuantity;
    }

    /**
     * @return float
     */
    public function getSalesQuantity()
    {
        return $this->salesQuantity;
    }

    /**
     * @param float $salesQuantity
     */
    public function setSalesQuantity($salesQuantity)
    {
        $this->salesQuantity = $salesQuantity;
    }

    /**
     * @return float
     */
    public function getSalesReturnQuantity()
    {
        return $this->salesReturnQuantity;
    }

    /**
     * @param float $salesReturnQuantity
     */
    public function setSalesReturnQuantity($salesReturnQuantity)
    {
        $this->salesReturnQuantity = $salesReturnQuantity;
    }

    /**
     * @return float
     */
    public function getDamageQuantity()
    {
        return $this->damageQuantity;
    }

    /**
     * @param float $damageQuantity
     */
    public function setDamageQuantity($damageQuantity)
    {
        $this->damageQuantity = $damageQuantity;
    }

    /**
     * @return float
     */
    public function getBonusQuantity()
    {
        return $this->bonusQuantity;
    }

    /**
     * @param float $bonusQuantity
     */
    public function setBonusQuantity($bonusQuantity)
    {
        $this->bonusQuantity = $bonusQuantity;
    }

    /**
     * @return float
     */
    public function getSpoilQuantity()
    {
        return $this->spoilQuantity;
    }

    /**
     * @param float $spoilQuantity
     */
    public function setSpoilQuantity($spoilQuantity)
    {
        $this->spoilQuantity = $spoilQuantity;
    }

    /**
     * @return string
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * @param string $process
     */
    public function setProcess($process)
    {
        $this->process = $process;
    }

    /**
     * @return float
     */
    public function getOpeningQuantity()
    {
        return $this->openingQuantity;
    }

    /**
     * @param float $openingQuantity
     */
    public function setOpeningQuantity($openingQuantity)
    {
        $this->openingQuantity = $openingQuantity;
    }

    /**
     * @return float
     */
    public function getClosingQuantity()
    {
        return $this->closingQuantity;
    }

    /**
     * @param float $closingQuantity
     */
    public function setClosingQuantity($closingQuantity)
    {
        $this->closingQuantity = $closingQuantity;
    }

    /**
     * @return float
     */
    public function getOpening()
    {
        return $this->opening;
    }

    /**
     * @param float $opening
     */
    public function setOpening($opening)
    {
        $this->opening = $opening;
    }

    /**
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param User $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return float
     */
    public function getBonusSalesQuantity()
    {
        return $this->bonusSalesQuantity;
    }

    /**
     * @param float $bonusSalesQuantity
     */
    public function setBonusSalesQuantity($bonusSalesQuantity)
    {
        $this->bonusSalesQuantity = $bonusSalesQuantity;
    }


}

