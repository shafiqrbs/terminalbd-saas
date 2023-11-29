<?php

namespace Appstore\Bundle\ProcurementBundle\Entity;

use Appstore\Bundle\EcommerceBundle\Entity\OrderItem;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * PurchaseItem
 *
 * @ORM\Table(name="pro_purchase_requisition_item")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\ProcurementBundle\Repository\PurchaseRequisitionItemRepository")
 */
class PurchaseRequisitionItem
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Item",inversedBy="purchaseRequisitionItems")
     **/
    private  $item;


     /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ProcurementBundle\Entity\PurchaseRequisition", inversedBy="requisitionItems" )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $requisition;

      /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\ProcurementBundle\Entity\PurchaseOrderItem", mappedBy="requisitionItem" )
     **/
    private  $orderItems;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable = true)
     */
    private $name;


    /**
     * @var float
     *
     * @ORM\Column(name="quantity", type="float")
     */
    private $quantity;

    /**
     * @var float
     *
     * @ORM\Column(name="issueQuantity", type="float", nullable=true)
     */
    private $issueQuantity;

    /**
     * @var float
     *
     * @ORM\Column(name="purchasePrice", type="float", nullable=true)
     */
    private $purchasePrice;


    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", nullable=true)
     */
    private $price;


    /**
     * @var integer
     *
     * @ORM\Column(name="code", type="integer", nullable = true)
     */
    private $code;

    /**
     * @var float
     *
     * @ORM\Column(name="purchaseSubTotal", type="float", nullable = true)
     */
    private $purchaseSubTotal;

    /**
     * @var float
     *
     * @ORM\Column(name="salesPrice", type="float", nullable = true)
     */
    private $salesPrice;
    /**
     * @var float
     *
     * @ORM\Column(name="salesSubTotal", type="float", nullable = true)
     */
    private $salesSubTotal;

    /**
     * @var float
     *
     * @ORM\Column(name="webPrice", type="float", nullable = true)
     */
    private $webPrice;


    /**
     * @var float
     *
     * @ORM\Column(name="process", type="string", nullable = true)
     */
    private $process= 'created';

    /**
     * @var float
     *
     * @ORM\Column(name="mode", type="string", nullable = true)
     */
    private $mode = '';

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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
    public function getRequisition()
    {
        return $this->requisition;
    }

    /**
     * @param mixed $requisition
     */
    public function setRequisition($requisition)
    {
        $this->requisition = $requisition;
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
    public function getIssueQuantity()
    {
        return $this->issueQuantity;
    }

    /**
     * @param float $issueQuantity
     */
    public function setIssueQuantity($issueQuantity)
    {
        $this->issueQuantity = $issueQuantity;
    }

    /**
     * @return float
     */
    public function getPurchasePrice()
    {
        return $this->purchasePrice;
    }

    /**
     * @param float $purchasePrice
     */
    public function setPurchasePrice($purchasePrice)
    {
        $this->purchasePrice = $purchasePrice;
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
     * @return float
     */
    public function getSalesPrice()
    {
        return $this->salesPrice;
    }

    /**
     * @param float $salesPrice
     */
    public function setSalesPrice($salesPrice)
    {
        $this->salesPrice = $salesPrice;
    }

    /**
     * @return float
     */
    public function getSalesSubTotal()
    {
        return $this->salesSubTotal;
    }

    /**
     * @param float $salesSubTotal
     */
    public function setSalesSubTotal($salesSubTotal)
    {
        $this->salesSubTotal = $salesSubTotal;
    }

    /**
     * @return float
     */
    public function getWebPrice()
    {
        return $this->webPrice;
    }

    /**
     * @param float $webPrice
     */
    public function setWebPrice($webPrice)
    {
        $this->webPrice = $webPrice;
    }

    /**
     * @return float
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * @param float $process
     */
    public function setProcess($process)
    {
        $this->process = $process;
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
     * @return mixed
     */
    public function getOrderItems()
    {
        return $this->orderItems;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return float
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param float $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }




}

