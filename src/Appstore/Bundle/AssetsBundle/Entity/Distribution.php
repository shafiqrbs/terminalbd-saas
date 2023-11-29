<?php

namespace Appstore\Bundle\AssetsBundle\Entity;

use Appstore\Bundle\DomainUserBundle\Entity\Branches;
use Core\UserBundle\Entity\Profile;
use Core\UserBundle\Entity\User;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * product
 *
 * @ORM\Table("assets_distribution")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\AssetsBundle\Repository\DistributionRepository")
 */
class Distribution
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
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Particular", inversedBy="distributions" )
	 **/
	private  $branch;

	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Product", inversedBy="distributions" )
	 **/
	private  $product;

	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Particular", inversedBy="distributionDepartment" )
	 **/
	private  $department;

	/**
	 * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\Profile", inversedBy="distributionUser" )
	 **/
	private  $employee;

	/**
	 * @Gedmo\Blameable(on="create")
	 * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User", inversedBy="distributionCreate" )
	 **/
	private  $createdBy;


	/**
	 * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User", inversedBy="distributionApprovedBy" )
	 **/
	private  $approvedBy;

	/**
	 * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User", inversedBy="distributionCheckedBy" )
	 **/
	private  $checkedBy;



	/**
	 * @var string
	 *
	 * @ORM\Column(name="address", type="text" , nullable=true)
	 */
	private $address;


	/**
	 * @var string
	 *
	 * @ORM\Column(name="narration", type="text", nullable=true)
	 */
	private $narration;

	/**
	 * @var datetime
	 *
	 * @ORM\Column(name="assignDate", type="date", nullable=true)
	 */
	private $assignDate;

	/**
	 * @var datetime
	 *
	 * @ORM\Column(name="endDate", type="date", nullable=true)
	 */
	private $endDate;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="process", type="string", nullable=true)
	 */
	private $process = 'In-progress';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="requisition", type="string", nullable=true)
	 */
	private $requisition;

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
	 * @return DateTime
	 */
	public function getCreated() {
		return $this->created;
	}

	/**
	 * @param DateTime $created
	 */
	public function setCreated( $created ) {
		$this->created = $created;
	}

	/**
	 * @return DateTime
	 */
	public function getUpdated() {
		return $this->updated;
	}

	/**
	 * @param DateTime $updated
	 */
	public function setUpdated( $updated ) {
		$this->updated = $updated;
	}

	/**
	 * @return DateTime
	 */
	public function getEndDate() {
		return $this->endDate;
	}

	/**
	 * @param DateTime $endDate
	 */
	public function setEndDate( $endDate ) {
		$this->endDate = $endDate;
	}

	/**
	 * @return string
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * @param string $address
	 */
	public function setAddress( $address ) {
		$this->address = $address;
	}

	/**
	 * @return string
	 */
	public function getNarration() {
		return $this->narration;
	}

	/**
	 * @param string $narration
	 */
	public function setNarration( $narration ) {
		$this->narration = $narration;
	}

	/**
	 * @return Profile
	 */
	public function getEmployee() {
		return $this->employee;
	}

	/**
	 * @param Profile $employee
	 */
	public function setEmployee( $employee ) {
		$this->employee = $employee;
	}

	/**
	 * @return Particular
	 */
	public function getDepartment() {
		return $this->department;
	}

	/**
	 * @param Particular $department
	 */
	public function setDepartment( $department ) {
		$this->department = $department;
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
	 * @return Branches
	 */
	public function getBranch() {
		return $this->branch;
	}

	/**
	 * @param Branches $branch
	 */
	public function setBranch( $branch ) {
		$this->branch = $branch;
	}

	/**
	 * @return string
	 */
	public function getRequisition() {
		return $this->requisition;
	}

	/**
	 * @param string $requisition
	 */
	public function setRequisition( $requisition ) {
		$this->requisition = $requisition;
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
	 * @return User
	 */
	public function getApprovedBy() {
		return $this->approvedBy;
	}

	/**
	 * @param User $approvedBy
	 */
	public function setApprovedBy( $approvedBy ) {
		$this->approvedBy = $approvedBy;
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
	 * @return DateTime
	 */
	public function getAssignDate() {
		return $this->assignDate;
	}

	/**
	 * @param DateTime $assignDate
	 */
	public function setAssignDate( $assignDate ) {
		$this->assignDate = $assignDate;
	}
}

