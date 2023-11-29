<?php

namespace Appstore\Bundle\HotelBundle\Entity;

use Appstore\Bundle\AccountingBundle\Entity\AccountBank;
use Appstore\Bundle\AccountingBundle\Entity\AccountMobileBank;
use Appstore\Bundle\AccountingBundle\Entity\AccountSales;
use Appstore\Bundle\DomainUserBundle\Entity\Customer;
use Core\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Setting\Bundle\ToolBundle\Entity\Bank;
use Setting\Bundle\ToolBundle\Entity\PaymentCard;
use Setting\Bundle\ToolBundle\Entity\TransactionMethod;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Invoice
 *
 * @ORM\Table( name ="hotel_invoice_transaction_summary")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\HotelBundle\Repository\HotelInvoiceTransactionSummaryRepository")
 */
class HotelInvoiceTransactionSummary
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
	 * @ORM\OneToOne(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelInvoice", inversedBy="hotelInvoiceTransactionSummary")
	 **/
	private $hotelInvoice;

	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelConfig", inversedBy="hotelInvoiceTransactionSummary")
	 **/
	private $hotelConfig;

	/**
     * @var float
     *
     * @ORM\Column(name="discount", type="float")
     */
    private $discount = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="vat", type="float")
     */
    private $vat;

    /**
     * @var float
     *
     * @ORM\Column(name="serviceCharge", type="float")
     */
    private $serviceCharge;

    /**
     * @var float
     *
     * @ORM\Column(name="subTotal", type="float")
     */
    private $subTotal;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float")
     */
    private $total;


    /**
     * @var float
     *
     * @ORM\Column(name="received", type="float")
     */
    private $received;


	/**
	 * @var float
	 *
	 * @ORM\Column(name="due", type="float")
	 */
	private $due;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="paymentInWord", type="string", length=255, nullable=true)
	 */
	private $paymentInWord;


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
	 * @return HotelInvoice
	 */
	public function getHotelInvoice() {
		return $this->hotelInvoice;
	}

	/**
	 * @param HotelInvoice $hotelInvoice
	 */
	public function setHotelInvoice( $hotelInvoice ) {
		$this->hotelInvoice = $hotelInvoice;
	}

	/**
	 * @return float
	 */
	public function getReceived(){
		return $this->received;
	}

	/**
	 * @param float $received
	 */
	public function setReceived( float $received ) {
		$this->received = $received;
	}

	/**
	 * @return float
	 */
	public function getSubTotal(){
		return $this->subTotal;
	}

	/**
	 * @param float $subTotal
	 */
	public function setSubTotal( float $subTotal ) {
		$this->subTotal = $subTotal;
	}

	/**
	 * @return HotelConfig
	 */
	public function getHotelConfig() {
		return $this->hotelConfig;
	}

	/**
	 * @param HotelConfig $hotelConfig
	 */
	public function setHotelConfig( $hotelConfig ) {
		$this->hotelConfig = $hotelConfig;
	}

	/**
	 * @return float
	 */
	public function getVat(){
		return $this->vat;
	}

	/**
	 * @param float $vat
	 */
	public function setVat( float $vat ) {
		$this->vat = $vat;
	}

	/**
	 * @return float
	 */
	public function getDiscount(){
		return $this->discount;
	}

	/**
	 * @param float $discount
	 */
	public function setDiscount( float $discount ) {
		$this->discount = $discount;
	}

	/**
	 * @return float
	 */
	public function getTotal(){
		return $this->total;
	}

	/**
	 * @param float $total
	 */
	public function setTotal( float $total ) {
		$this->total = $total;
	}

	/**
	 * @return float
	 */
	public function getDue() {
		return $this->due;
	}

	/**
	 * @param float $due
	 */
	public function setDue( float $due ) {
		$this->due = $due;
	}

	/**
	 * @return float
	 */
	public function getServiceCharge(){
		return $this->serviceCharge;
	}

	/**
	 * @param float $serviceCharge
	 */
	public function setServiceCharge( float $serviceCharge ) {
		$this->serviceCharge = $serviceCharge;
	}

	/**
	 * @return string
	 */
	public function getPaymentInWord() {
		return $this->paymentInWord;
	}

	/**
	 * @param string $paymentInWord
	 */
	public function setPaymentInWord( string $paymentInWord ) {
		$this->paymentInWord = $paymentInWord;
	}

}

