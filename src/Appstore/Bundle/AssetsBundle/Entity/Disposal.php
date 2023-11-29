<?php

namespace Appstore\Bundle\AssetsBundle\Entity;

use Appstore\Bundle\DomainUserBundle\Entity\Branches;
use Core\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Depreciation
 *
 * @ORM\Table("assets_disposal")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\AssetsBundle\Repository\DisposalRepository")
 */
class Disposal
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
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\AssetsConfig")
	 **/
	private  $config;



	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Particular", inversedBy="disposals" )
	 **/
	private  $branch;


	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\DisposalItem", mappedBy="disposal" )
	 **/
	private  $disposalItems;

	/**
	 * @Gedmo\Blameable(on="create")
	 * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User")
	 **/
	private  $createdBy;


	/**
	 * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User")
	 **/
	private  $approvedBy;


	/**
	 * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User")
	 **/
	private  $checkedBy;


	/**
	 * @var float
	 *
	 * @ORM\Column(name="subTotal", type="float", nullable=true)
	 */
	private $subTotal;


	/**
	 * @var float
	 *
	 * @ORM\Column(name="grandTotal", type="float", nullable=true)
	 */
	private $grandTotal;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="totalItem", type="float", nullable=true)
	 */
	private $totalItem;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="totalQuantity", type="float", nullable=true)
	 */
	private $totalQuantity;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="refNo", type="string", nullable=true)
	 */
	private $refNo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="process", type="string", nullable=true)
	 */
	private $process = "Created";

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="code", type="integer",  nullable=true)
	 */
	private $code;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="invoice", type="string", length=30, nullable=true)
	 */
	private $invoice;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="narration", type="text", nullable=true)
	 */
	private $narration;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $path;

	/**
	 * @Assert\File(maxSize="8388608")
	 */
	protected $file;



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
	public function getProcess() {
		return $this->process;
	}

	/**
	 * @param string $process
	 */
	public function setProcess( $process ) {
		$this->process = $process;
	}

	/**
	 * @return float
	 */
	public function getTotalQuantity() {
		return $this->totalQuantity;
	}

	/**
	 * @param float $totalQuantity
	 */
	public function setTotalQuantity( $totalQuantity ) {
		$this->totalQuantity = $totalQuantity;
	}

	/**
	 * @return float
	 */
	public function getTotalItem() {
		return $this->totalItem;
	}

	/**
	 * @param float $totalItem
	 */
	public function setTotalItem( $totalItem ) {
		$this->totalItem = $totalItem;
	}

	/**
	 * @return float
	 */
	public function getGrandTotal() {
		return $this->grandTotal;
	}

	/**
	 * @param float $grandTotal
	 */
	public function setGrandTotal( $grandTotal ) {
		$this->grandTotal = $grandTotal;
	}

	/**
	 * @return float
	 */
	public function getSubTotal() {
		return $this->subTotal;
	}

	/**
	 * @param float $subTotal
	 */
	public function setSubTotal( $subTotal ) {
		$this->subTotal = $subTotal;
	}

	/**
	 * @return User
	 */
	public function getCheckedBy() {
		return $this->checkedBy;
	}

	/**
	 * @param User $checkedBy
	 */
	public function setCheckedBy( $checkedBy ) {
		$this->checkedBy = $checkedBy;
	}

	/**
	 * @return User
	 */
	public function getApprovedBy() {
		return $this->approvedBy;
	}

	/**
	 * @param mixed $approvedBy
	 */
	public function setApprovedBy( $approvedBy ) {
		$this->approvedBy = $approvedBy;
	}

	/**
	 * @return User
	 */
	public function getCreatedBy() {
		return $this->createdBy;
	}

	/**
	 * @param User $createdBy
	 */
	public function setCreatedBy( $createdBy ) {
		$this->createdBy = $createdBy;
	}

	/**
	 * @return Particular
	 */
	public function getBranch() {
		return $this->branch;
	}

	/**
	 * @param Particular $branch
	 */
	public function setBranch( $branch ) {
		$this->branch = $branch;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreated() {
		return $this->created;
	}

	/**
	 * @param \DateTime $created
	 */
	public function setCreated( $created ) {
		$this->created = $created;
	}

	/**
	 * @return \DateTime
	 */
	public function getUpdated() {
		return $this->updated;
	}

	/**
	 * @param \DateTime $updated
	 */
	public function setUpdated( $updated ) {
		$this->updated = $updated;
	}



	/**
	 * @return int
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * @param int $code
	 */
	public function setCode( $code ) {
		$this->code = $code;
	}

	/**
	 * @return string
	 */
	public function getInvoice() {
		return $this->invoice;
	}

	/**
	 * @param string $invoice
	 */
	public function setInvoice( $invoice ) {
		$this->invoice = $invoice;
	}

	/**
	 * @return string
	 */
	public function getRefNo() {
		return $this->refNo;
	}

	/**
	 * @param string $refNo
	 */
	public function setRefNo( $refNo ) {
		$this->refNo = $refNo;
	}

	/**
	 * Sets file.
	 *
	 * @param Purchase $file
	 */

	public function setFile(UploadedFile $file = null)
	{
		$this->file = $file;
	}

	/**
	 * Get file.
	 *
	 * @return Purchase
	 */
	public function getFile()
	{
		return $this->file;
	}

	public function getAbsolutePath()
	{
		return null === $this->path
			? null
			: $this->getUploadRootDir().'/'.$this->path;
	}

	public function getWebPath()
	{
		return null === $this->path
			? null
			: $this->getUploadDir().'/'.$this->path;
	}

	protected function getUploadRootDir()
	{
		return __DIR__.'/../../../../../web/'.$this->getUploadDir();
	}

	protected function getUploadDir()
	{
		return 'uploads/domain/disposal/';
	}

	public function upload()
	{
		// the file property can be empty if the field is not required
		if (null === $this->getFile()) {
			return;
		}

		// use the original file name here but you should
		// sanitize it at least to avoid any security issues

		// move takes the target directory and then the
		// target filename to move to
		$this->getFile()->move(
			$this->getUploadRootDir(),
			$this->getFile()->getClientOriginalName()
		);

		// set the path property to the filename where you've saved the file
		$this->path = $this->getFile()->getClientOriginalName();

		// clean up the file property as you won't need it anymore
		$this->file = null;
	}

	/**
	 * @return string
	 */
	public function getNarration() {
		return $this->narration;
	}

	/**
	 * @param mixed $narration
	 */
	public function setNarration( $narration ) {
		$this->narration = $narration;
	}


	/**
	 * @return DisposalItem
	 */
	public function getDisposalItems() {
		return $this->disposalItems;
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


}

