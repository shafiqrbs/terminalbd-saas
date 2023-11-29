<?php

namespace Appstore\Bundle\ElectionBundle\Entity;

use Core\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * ElectionMember
 *
 * @ORM\Table("election_member")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\ElectionBundle\Repository\ElectionMemberRepository")
 */
class ElectionMember
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
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionConfig", inversedBy="members")
	 **/
	private $electionConfig;

	/**
	 * @Gedmo\Blameable(on="create")
	 * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User", inversedBy="members" )
	 **/
	private  $createdBy;

    /**
	 * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User", inversedBy="memberApprove" )
	 **/
	private  $approvedBy;

    /**
     *
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionMemberFamily", mappedBy="electionMember")
	 **/
	private $familyMembers;

    /**
	 * @ORM\OneTomany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionCommitteeMember", mappedBy="member")
	 **/
	private $committees;

    /**
	 * @ORM\OneTomany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionEvent", mappedBy="organiser")
	 **/
	private $events;

    /**
	 * @ORM\OneTomany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionVoteCenter", mappedBy="representative")
	 **/
	private $votecenters;

    /**
	 * @ORM\OneTomany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionVoteCenterMember", mappedBy="member")
	 **/
	private $voteCenterMembers;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionLocation", inversedBy="electionMembers")
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/

    protected $location;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionLocation", inversedBy="centerMembers")
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/

    protected $voteCenter;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionParticular", inversedBy="memberPoliticalStatus")
     **/

    protected $politicalStatus;

	/**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionParticular", inversedBy="memberPoliticalDesignation")
     **/

    protected $politicalDesignation;

	/**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionParticular", inversedBy="memberPoliticalParty")
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    protected $oldPoliticalParty;

	/**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionParticular", inversedBy="memberProfession")
     **/
    protected $profession;

	/**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionParticular", inversedBy="memberEducation")
     **/
    protected $education;

	/**
	 * @ORM\ManyToOne(targetEntity="ElectionMember", inversedBy="reference")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="referenceMember", referencedColumnName="id", onDelete="SET NULL", nullable=true)
	 * })
	 */
	private $referenceMember;

    /**
     * @ORM\OneToMany(targetEntity="ElectionMember" , mappedBy="referenceMember")
     * @ORM\OrderBy({"name" = "ASC"})
     **/
    private $reference;


	/**
     * @var string
     *
     * @ORM\Column(name="memberType", type="string", length=20,  nullable=true)
     */
    private $memberType = 'member';


	/**
     * @var integer
     *
     * @ORM\Column(name="code", type="integer",  nullable=true)
     */
    private $code;


	/**
	 * @var string
	 *
	 * @ORM\Column(name="process", type="string", length=20, nullable =true)
	 */
	private $process='Created';


    /**
     * @var string
     *
     * @ORM\Column(name="memberId", type="string",  nullable=true)
     */
    private $memberId;

    /**
     * @var string
     *
     * @ORM\Column(name="postalCode", type="string", length=30, nullable =true)
     */
    private $postalCode;

     /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=150, nullable =true)
     */
    private $name;

     /**
     * @var string
     *
     * @ORM\Column(name="nameBn", type="string", length=150, nullable =true)
     */
    private $nameBn;

    /**
     * @var string
     *
     * @ORM\Column(name="fatherName", type="string", length=100, nullable =true)
     */
    private $fatherName;

    /**
     * @var string
     *
     * @ORM\Column(name="motherName", type="string", length=100, nullable =true)
     */
    private $motherName;


    /**
     * @var string
     *
     * @ORM\Column(name="nid", type="string", length=100, nullable =true)
     */
    private $nid;

    /**
     * @var int
     *
     * @ORM\Column(name="familyMember", type="smallint", length=2, nullable =true)
     */
    private $familyMember;

     /**
     * @var string
     *
     * @ORM\Column(name="religion", type="string", length=100, nullable =true)
     */
    private $religion ="Islam" ;

    /**
     * @var string
     *
     * @ORM\Column(name="nationality", type="string", length=100, nullable =true)
     */
    private $nationality = 'Bangladeshi';

    /**
     * @var string
     *
     * @ORM\Column(name="additionalMobileNo", type="string", length=100, nullable =true)
     */
    private $additionalMobileNo;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=255, nullable =true)
     */
    private $mobile;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable =true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="facebookId", type="string", length=100, nullable =true)
     */
    private $facebookId;

    /**
     * @var string
     *
     * @ORM\Column(name="remark", type="text", nullable =true)
     */
    private $remark;

    /**
     * @var string
     *
     * @ORM\Column(name="biography", type="text", nullable =true)
     */
    private $biography;

    /**
     * @var string
     *
     * @ORM\Column(name="bloodGroup", type="string", length=20, nullable =true)
     */
    private $bloodGroup;

    /**
     * @var string
     *
     * @ORM\Column(name="dob", type="string", nullable =true)
     */
    private $dob;


    /**
     * @var string
     *
     * @ORM\Column(name="maritalStatus", type="string",length=30 , nullable = true)
     */
    private $maritalStatus;

    /**
     * @var integer
     *
     * @ORM\Column(name="age", type="smallint",length=3, nullable = true)
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=10 , nullable = true)
     */
    private $gender;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="address", type="text", nullable =true)
	 */
	private $address;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="village", type="string", length=100, nullable = true)
	 */
	private $village;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="voteCenterName", type="string", length=200, nullable = true)
	 */
	private $voteCenterName;


    /**
	 * @var string
	 *
	 * @ORM\Column(name="ward", type="string", length=150, nullable = true)
	 */
	private $ward;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="memberUnion", type="string", length=150, nullable = true)
	 */
	private $memberUnion;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="thana", type="string", length=150, nullable = true)
	 */
	private $thana;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="district", type="string", length=150, nullable = true)
	 */
	private $district;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="postOffice", type="string", length=150, nullable = true)
	 */
	private $postOffice;


    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status = true;

	/**
     * @var boolean
     *
     * @ORM\Column(name="isMember", type="boolean")
     */
    private $isMember = false;


	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="removeImage", type="boolean")
	 */
	private $removeImage = false;

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
	 * @return ElectionConfig
	 */
	public function getElectionConfig() {
		return $this->electionConfig;
	}

	/**
	 * @param ElectionConfig $electionConfig
	 */
	public function setElectionConfig( $electionConfig ) {
		$this->electionConfig = $electionConfig;
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
     * Set name
     *
     * @param string $name
     *
     * @return ElectionMember
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     *
     * @return ElectionMember
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    public function getNameMobile()
    {

	    $nameMobile = $this->getMobile().' - '.$this->getName();
    	return $nameMobile;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return ElectionMember
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return ElectionMember
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set bloodGroup
     *
     * @param string $bloodGroup
     *
     * @return ElectionMember
     */
    public function setBloodGroup($bloodGroup)
    {
        $this->bloodGroup = $bloodGroup;

        return $this;
    }

    /**
     * Get bloodGroup
     *
     * @return string
     */
    public function getBloodGroup()
    {
        return $this->bloodGroup;
    }



    /**
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param boolean $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }


    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }


    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }


	/**
	 * @return \DateTime
	 */
	public function getUpdated(): \DateTime {
		return $this->updated;
	}

	/**
	 * @param \DateTime $updated
	 */
	public function setUpdated( \DateTime $updated ) {
		$this->updated = $updated;
	}


    /**
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @param string $facebookId
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
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
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }


    /**
     * @return string
     */
    public function getFatherName()
    {
        return $this->fatherName;
    }

    /**
     * @param string $fatherName
     */
    public function setFatherName($fatherName)
    {
        $this->fatherName = $fatherName;
    }

    /**
     * @return string
     */
    public function getMotherName()
    {
        return $this->motherName;
    }

    /**
     * @param string $motherName
     */
    public function setMotherName($motherName)
    {
        $this->motherName = $motherName;
    }

    /**
     * @return string
     */
    public function getReligion()
    {
        return $this->religion;
    }

    /**
     * @param string $religion
     */
    public function setReligion($religion)
    {
        $this->religion = $religion;
    }


    /**
     * @return string
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * @param string $nationality
     */
    public function setNationality($nationality)
    {
        $this->nationality = $nationality;
    }


    /**
     * @return string
     */
    public function getMaritalStatus()
    {
        return $this->maritalStatus;
    }

    /**
     * @param string $maritalStatus
     */
    public function setMaritalStatus($maritalStatus)
    {
        $this->maritalStatus = $maritalStatus;
    }


    /**
     * @return string
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * @param string $dob
     */
    public function setDob($dob)
    {
        $this->dob = $dob;
    }

	/**
	 * @return string
	 */
	public function getPostalCode(){
		return $this->postalCode;
	}

	/**
	 * @param string $postalCode
	 */
	public function setPostalCode( string $postalCode ) {
		$this->postalCode = $postalCode;
	}

	/**
	 * @return string
	 */
	public function getRemark(){
		return $this->remark;
	}

	/**
	 * @param string $remark
	 */
	public function setRemark( string $remark ) {
		$this->remark = $remark;
	}

	/**
	 * Sets file.
	 *
	 * @param Page $file
	 */
	public function setFile(UploadedFile $file = null)
	{
		$this->file = $file;
	}

	/**
	 * Get file.
	 *
	 * @return Page
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
			: $this->getUploadDir().'/' . $this->path;
	}



	protected function getUploadRootDir()
	{
		return __DIR__.'/../../../../../web/'.$this->getUploadDir();
	}

	protected function getUploadDir()
	{
		return 'uploads/domain/'.$this->getElectionConfig()->getGlobalOption()->getId().'/election';
	}

	public function removeUpload()
	{
		if ($file = $this->getAbsolutePath()) {
			unlink($file);
			$this->path = null ;
		}
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
		$filename = date('YmdHmi') . "_" . $this->getFile()->getClientOriginalName();
		$this->getFile()->move(
			$this->getUploadRootDir(),
			$filename
		);

		// set the path property to the filename where you've saved the file
		$this->path = $filename ;

		// clean up the file property as you won't need it anymore
		$this->file = null;
	}

	/**
	 * @return boolean
	 */
	public function getRemoveImage()
	{
		return $this->removeImage;
	}

	/**
	 * @param boolean $removeImage
	 */
	public function setRemoveImage($removeImage)
	{
		$this->removeImage = $removeImage;
	}


	/**
	 * @return string
	 */
	public function getAdditionalMobileNo(){
		return $this->additionalMobileNo;
	}

	/**
	 * @param string $additionalMobileNo
	 */
	public function setAdditionalMobileNo( string $additionalMobileNo ) {
		$this->additionalMobileNo = $additionalMobileNo;
	}

	/**
	 * @return int
	 */
	public function getFamilyMember(){
		return $this->familyMember;
	}

	/**
	 * @param int $familyMember
	 */
	public function setFamilyMember( int $familyMember ) {
		$this->familyMember = $familyMember;
	}

	/**
	 * @return string
	 */
	public function getNid(){
		return $this->nid;
	}

	/**
	 * @param string $nid
	 */
	public function setNid( string $nid ) {
		$this->nid = $nid;
	}



	/**
	 * @return ElectionMemberFamily
	 */
	public function getFamilyMembers() {
		return $this->familyMembers;
	}

	/**
	 * @return ElectionMember
	 */
	public function getLocation() {
		return $this->location;
	}

	/**
	 * @param ElectionMember $location
	 */
	public function setLocation( $location ) {
		$this->location = $location;
	}

	/**
	 * @return ElectionCommitteeMember
	 */
	public function getCommittees() {
		return $this->committees;
	}

	/**
	 * @return ElectionLocation
	 */
	public function getVoteCenter() {
		return $this->voteCenter;
	}

	/**
	 * @param ElectionLocation $voteCenter
	 */
	public function setVoteCenter( $voteCenter ) {
		$this->voteCenter = $voteCenter;
	}


	/**
	 * @return string
	 */
	public function getVillage() {
		return $this->village;
	}

	/**
	 * @param string $village
	 */
	public function setVillage( $village ) {
		$this->village = $village;
	}

	/**
	 * @return string
	 */
	public function getWard() {
		return $this->ward;
	}

	/**
	 * @param string $ward
	 */
	public function setWard( $ward ) {
		$this->ward = $ward;
	}

	/**
	 * @return string
	 */
	public function getThana() {
		return $this->thana;
	}

	/**
	 * @param string $thana
	 */
	public function setThana( $thana ) {
		$this->thana = $thana;
	}

	/**
	 * @return string
	 */
	public function getDistrict() {
		return $this->district;
	}

	/**
	 * @param string $district
	 */
	public function setDistrict( $district ) {
		$this->district = $district;
	}

	/**
	 * @return ElectionMember
	 */
	public function getReferenceMember() {
		return $this->referenceMember;
	}

	/**
	 * @param ElectionMember $referenceMember
	 */
	public function setReferenceMember( $referenceMember ) {
		$this->referenceMember = $referenceMember;
	}

	/**
	 * @return ElectionParticular
	 */
	public function getOldPoliticalParty() {
		return $this->oldPoliticalParty;
	}

	/**
	 * @param ElectionParticular $oldPoliticalParty
	 */
	public function setOldPoliticalParty( $oldPoliticalParty ) {
		$this->oldPoliticalParty = $oldPoliticalParty;
	}

	/**
	 * @return ElectionParticular
	 */
	public function getPoliticalDesignation() {
		return $this->politicalDesignation;
	}

	/**
	 * @param ElectionParticular $politicalDesignation
	 */
	public function setPoliticalDesignation( $politicalDesignation ) {
		$this->politicalDesignation = $politicalDesignation;
	}

	/**
	 * @return ElectionParticular
	 */
	public function getPoliticalStatus() {
		return $this->politicalStatus;
	}

	/**
	 * @param ElectionParticular $politicalStatus
	 */
	public function setPoliticalStatus( $politicalStatus ) {
		$this->politicalStatus = $politicalStatus;
	}

	/**
	 * @return ElectionParticular
	 */
	public function getProfession() {
		return $this->profession;
	}

	/**
	 * @param ElectionParticular $profession
	 */
	public function setProfession( $profession ) {
		$this->profession = $profession;
	}

	/**
	 * @return ElectionParticular
	 */
	public function getEducation() {
		return $this->education;
	}

	/**
	 * @param ElectionParticular $education
	 */
	public function setEducation( $education ) {
		$this->education = $education;
	}

	/**
	 * @return string
	 */
	public function getMemberId(){
		return $this->memberId;
	}

	/**
	 * @param string $memberId
	 */
	public function setMemberId( string $memberId ) {
		$this->memberId = $memberId;
	}

	/**
	 * @return string
	 */
	public function getMemberUnion(): string {
		return $this->memberUnion;
	}

	/**
	 * @param string $memberUnion
	 */
	public function setMemberUnion( string $memberUnion ) {
		$this->memberUnion = $memberUnion;
	}

	/**
	 * @return string
	 */
	public function getBiography(){
		return $this->biography;
	}

	/**
	 * @param string $biography
	 */
	public function setBiography( string $biography ) {
		$this->biography = $biography;
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
	 * @return string
	 */
	public function getProcess(){
		return $this->process;
	}

	/**
	 * @param string $process
	 * In-progress
	 * Approved
	 * Hold
	 */
	public function setProcess( string $process ) {
		$this->process = $process;
	}

	/**
	 * @return string
	 */
	public function getVoteCenterName(){
		return $this->voteCenterName;
	}

	/**
	 * @param string $voteCenterName
	 */
	public function setVoteCenterName( string $voteCenterName ) {
		$this->voteCenterName = $voteCenterName;
	}

	/**
	 * @return ElectionVoteCenter
	 */
	public function getVotecenters() {
		return $this->votecenters;
	}

	/**
	 * @return ElectionEvent
	 */
	public function getEvents() {
		return $this->events;
	}

	/**
	 * @return ElectionVoteCenterMember
	 */
	public function getVoteCenterMembers() {
		return $this->voteCenterMembers;
	}

	/**
	 * @return string
	 */
	public function getPostOffice(){
		return $this->postOffice;
	}

	/**
	 * @param string $postOffice
	 */
	public function setPostOffice( string $postOffice ) {
		$this->postOffice = $postOffice;
	}

	/**
	 * @return string
	 */
	public function getMemberType(){
		return $this->memberType;
	}

	/**
	 * @param string $memberType
	 */
	public function setMemberType( string $memberType ) {
		$this->memberType = $memberType;
	}

	/**
	 * @return string
	 */
	public function getNameBn(){
		return $this->nameBn;
	}

	/**
	 * @param string $nameBn
	 */
	public function setNameBn( string $nameBn ) {
		$this->nameBn = $nameBn;
	}

	/**
	 * @return bool
	 */
	public function isMember(){
		return $this->isMember;
	}

	/**
	 * @param bool $isMember
	 */
	public function setIsMember( bool $isMember ) {
		$this->isMember = $isMember;
	}


}

