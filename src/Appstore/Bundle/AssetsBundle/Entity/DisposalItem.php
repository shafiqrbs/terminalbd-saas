<?php

namespace Appstore\Bundle\AssetsBundle\Entity;

use Appstore\Bundle\InventoryBundle\Entity\Item;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Product\Bundle\ProductBundle\Entity\Category;

/**
 * DisposalItem
 *
 * @ORM\Table("assets_disposal_item")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\AssetsBundle\Repository\DisposalItemRepository")
 */
class DisposalItem
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
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Disposal", inversedBy="disposalItems" )
	 **/
	private  $disposal;

	/**
	 * @ORM\OneToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Product", inversedBy="disposalItem" )
	 **/
	private  $product;

	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\InventoryBundle\Entity\Item", inversedBy="disposalItems" )
	 **/
	private  $item;


	/**
	 * @var string
	 *
	 * @ORM\Column(name="serialNo", type="string", nullable=true)
	 */
	private $serialNo;


	/**
	 * @var int
	 *
	 * @ORM\Column(name="quantity", type="float", nullable=true)
	 */
	private $quantity;


	/**
	 * @var float
	 *
	 * @ORM\Column(name="bookValue", type="float", nullable=true)
	 */
	private $bookValue;


	/**
	 * @var float
	 *
	 * @ORM\Column(name="salesPrice", type="float", nullable=true)
	 */
	private $salesPrice;


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
	 * @return Disposal
	 */
	public function getDisposal() {
		return $this->disposal;
	}

	/**
	 * @param Disposal $disposal
	 */
	public function setDisposal( $disposal ) {
		$this->disposal = $disposal;
	}

	/**
	 * @return Product
	 */
	public function getProduct() {
		return $this->product;
	}

	/**
	 * @param Product $product
	 */
	public function setProduct( $product ) {
		$this->product = $product;
	}

	/**
	 * @return int
	 */
	public function getQuantity() {
		return $this->quantity;
	}

	/**
	 * @param int $quantity
	 */
	public function setQuantity( $quantity ) {
		$this->quantity = $quantity;
	}

	/**
	 * @return float
	 */
	public function getBookValue() {
		return $this->bookValue;
	}

	/**
	 * @param float $bookValue
	 */
	public function setBookValue( $bookValue ) {
		$this->bookValue = $bookValue;
	}

	/**
	 * @return float
	 */
	public function getSalesPrice() {
		return $this->salesPrice;
	}

	/**
	 * @param float $salesPrice
	 */
	public function setSalesPrice( $salesPrice ) {
		$this->salesPrice = $salesPrice;
	}

	/**
	 * @return string
	 */
	public function getSerialNo() {
		return $this->serialNo;
	}

	/**
	 * @param string $serialNo
	 */
	public function setSerialNo( $serialNo ) {
		$this->serialNo = $serialNo;
	}

	/**
	 * @return Item
	 */
	public function getItem() {
		return $this->item;
	}

	/**
	 * @param Item $item
	 */
	public function setItem( $item ) {
		$this->item = $item;
	}


}

