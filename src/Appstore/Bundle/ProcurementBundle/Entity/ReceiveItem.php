<?php

namespace Appstore\Bundle\ProcurementBundle\Entity;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Setting\Bundle\ToolBundle\Entity\ItemWarning;


/**
 * VoucherItem
 *
 * @ORM\Table(name ="pro_receive_item")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\ProcurementBundle\Repository\ReceiveItemRepository")
 */
class ReceiveItem
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ProcurementBundle\Entity\ProcurementConfig", inversedBy="receiveItems" )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private $config;


    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ProcurementBundle\Entity\Receive", inversedBy="receiveItems" )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $receive;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Item", inversedBy="receiveItems" )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $item;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ProcurementBundle\Entity\PurchaseOrderItem", inversedBy="receiveItems" )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $orderItem;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\StockItem", mappedBy = "receiveItem", cascade={"persist"} )
     **/
    private  $stockItems;

     /**
     * @ORM\ManyToOne(targetEntity="Setting\Bundle\ToolBundle\Entity\ItemWarning")
     **/
    private  $assuranceFromVendor;

     /**
     * @ORM\ManyToOne(targetEntity="Setting\Bundle\ToolBundle\Entity\ItemWarning")
     **/
    private  $assuranceToCustomer;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\ItemMetaAttribute", mappedBy="purchaseItem" , cascade={"remove"}  )
     **/
    private  $itemMetaAttributes;



    /**
     * @var string
     *
     * @ORM\Column(name="assuranceType", type="string", length=50, nullable = true)
     */
    private $assuranceType;


    /**
     * @var datetime
     *
     * @ORM\Column(name="effectedDate", type="datetime", nullable=true)
     */
    private $effectedDate;

    /**
     * @var datetime
     *
     * @ORM\Column(name="expiredDate", type="datetime", nullable=true)
     */
    private $expiredDate;

    /**
     * @var array
     *
     * @ORM\Column(name="internalSerial", type="simple_array",  nullable = true)
     */
    private $internalSerial;

    /**
     * @var string
     *
     * @ORM\Column(name="externalSerial", type="text",  nullable = true)
     */
    private $externalSerial;


    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="remark", type="text", nullable=true)
     */
    private $remark;

    /**
     * @var string
     *
     * @ORM\Column(name="process", type="string", length=50, nullable=true)
     */
    private $process = "In-progress";


    /**
     * @var float
     *
     * @ORM\Column(name="quantity", type="float",nullable=true)
     */
    private $quantity;


     /**
     * @var float
     *
     * @ORM\Column(name="poQuantity", type="float",nullable=true)
     */
    private $poQuantity;


    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", nullable = true)
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
     * @ORM\Column(name="subTotal", type="float", nullable = true)
     */
    private $subTotal;


    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float", nullable = true)
     */
    private $total;


    /**
     * @var string
     *
     * @ORM\Column(name="barcode", type="string",  nullable = true)
     */
    private $barcode;


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
    public function getSubTotal()
    {
        return $this->subTotal;
    }

    /**
     * @param float $subTotal
     */
    public function setSubTotal($subTotal)
    {
        $this->subTotal = $subTotal;
    }

    /**
     * @return ItemWarning
     */
    public function getItemWarning() {
        return $this->itemWarning;
    }

    /**
     * @param ItemWarning $itemWarning
     */
    public function setItemWarning( $itemWarning ) {
        $this->itemWarning = $itemWarning;
    }

    /**
     * @return string
     */
    public function getAssuranceType() {
        return $this->assuranceType;
    }

    /**
     * @param string $assuranceType
     */
    public function setAssuranceType( $assuranceType ) {
        $this->assuranceType = $assuranceType;
    }

    /**
     * @return \DateTime
     */
    public function getExpiredDate() {
        return $this->expiredDate;
    }

    /**
     * @param \DateTime $expiredDate
     */
    public function setExpiredDate( $expiredDate ) {
        $this->expiredDate = $expiredDate;
    }

    /**
     * @return array
     */
    public function getInternalSerial() {
        return $this->internalSerial;
    }

    /**
     * @param array $internalSerial
     */
    public function setInternalSerial( $internalSerial ) {
        $this->internalSerial = $internalSerial;
    }

    /**
     * @return string
     */
    public function getExternalSerial() {
        return $this->externalSerial;
    }

    /**
     * @param string $externalSerial
     */
    public function setExternalSerial( $externalSerial ) {
        $this->externalSerial = $externalSerial;
    }

    /**
     * @return string
     */
    public function getEffectedDate() {
        return $this->effectedDate;
    }

    /**
     * @param string $effectedDate
     */
    public function setEffectedDate( $effectedDate ) {
        $this->effectedDate = $effectedDate;
    }

    /**
     * @return ItemMetaAttribute
     */
    public function getItemMetaAttributes() {
        return $this->itemMetaAttributes;
    }

    /**
     * @param ItemMetaAttribute $itemMetaAttributes
     */
    public function setItemMetaAttributes( $itemMetaAttributes ) {
        $this->itemMetaAttributes = $itemMetaAttributes;
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
     * @return DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param DateTime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }


    /**
     * @return string
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * @param string $remark
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;
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
     * @return Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param Item $item
     */
    public function setItem($item)
    {
        $this->item = $item;
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
     * @return StockItem
     */
    public function getStockItems()
    {
        return $this->stockItems;
    }

    /**
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param float $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * @return ItemWarning
     */
    public function getAssuranceFromVendor()
    {
        return $this->assuranceFromVendor;
    }

    /**
     * @param ItemWarning $assuranceFromVendor
     */
    public function setAssuranceFromVendor($assuranceFromVendor)
    {
        $this->assuranceFromVendor = $assuranceFromVendor;
    }

    /**
     * @return ItemWarning
     */
    public function getAssuranceToCustomer()
    {
        return $this->assuranceToCustomer;
    }

    /**
     * @param ItemWarning $assuranceToCustomer
     */
    public function setAssuranceToCustomer($assuranceToCustomer)
    {
        $this->assuranceToCustomer = $assuranceToCustomer;
    }


    /**
     * @return float
     */
    public function getPoQuantity()
    {
        return $this->poQuantity;
    }

    /**
     * @param float $poQuantity
     */
    public function setPoQuantity($poQuantity)
    {
        $this->poQuantity = $poQuantity;
    }

    /**
     * @return mixed
     */
    public function getReceive()
    {
        return $this->receive;
    }

    /**
     * @param mixed $receive
     */
    public function setReceive($receive)
    {
        $this->receive = $receive;
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
    public function getOrderItem()
    {
        return $this->orderItem;
    }

    /**
     * @param mixed $orderItem
     */
    public function setOrderItem($orderItem)
    {
        $this->orderItem = $orderItem;
    }


}

