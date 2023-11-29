<?php

namespace Appstore\Bundle\ElectionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ElectionEvent
 *
 * @ORM\Table( name ="election_event")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\ElectionBundle\Repository\ElectionEventRepository")
 */
class ElectionEvent
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionConfig", inversedBy="events" , cascade={"detach","merge"} )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $electionConfig;

	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionSetup", inversedBy="events")
     * @ORM\JoinColumn(onDelete="CASCADE")
	 **/
	protected $electionSetup;

	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionLocation", inversedBy="events")
	 **/
	protected $location;

	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionMember", inversedBy="events")
	 **/
	protected $organiser;

	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionParticular", inversedBy="event")
	 **/
	protected $eventType;

	/**
	 *
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionEventMember", mappedBy="event")
	 **/
	protected $eventMembers;

	/**
	 *
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionSms", mappedBy="event")
	 **/
	protected $sms;

	/**
	 * @var \DateTime
	 * @ORM\Column(name="startDate", type="datetime", nullable=true)
	 */
	private $startDate;

	/**
	 * @var \DateTime
	 * @ORM\Column(name="endDate", type="datetime", nullable=true)
	 */
	private $endDate;

	/**
	 * @var \DateTime
	 * @ORM\Column(name="eventDate", type="datetime", nullable=true)
	 */
	private $eventDate;

	/**
     * @var string
     *
     * @ORM\Column(name="eventTime", type="string", length=20, nullable=true)
     */
    private $eventTime;

	/**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

	/**
     * @var string
     *
     * @ORM\Column(name="contact", type="string", length=100, nullable=true)
     */
    private $contact;

	/**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=100, nullable=true)
     */
    private $mobile;

    /**
     * @var string
     *
     * @ORM\Column(name="facebookEvent", type="string", length=200, nullable=true)
     */
    private $facebookEvent;


	/**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;


	/**
     * @var string
     *
     * @ORM\Column(name="address", type="text",  nullable=true)
     */
    private $address;


	/**
	 * @Gedmo\Slug(fields={"name"})
	 * @Doctrine\ORM\Mapping\Column(length=100)
	 */
	private $slug;


	/**
     * @var int
     *
     * @ORM\Column(name="sorting", type="smallint",  length=2, nullable=true)
     */
    private $sorting = 0;


    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean" )
     */
    private $status= true;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }




    /**
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param bool $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getSorting()
    {
        return $this->sorting;
    }

    /**
     * @param int $sorting
     */
    public function setSorting($sorting)
    {
        $this->sorting = $sorting;
    }


    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }


	/**
	 * @return string
	 */
	public function getName(){
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name ) {
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @param mixed $content
	 */
	public function setContent( $content ) {
		$this->content = $content;
	}

	/**
	 * @return mixed
	 */
	public function getElectionConfig() {
		return $this->electionConfig;
	}

	/**
	 * @param mixed $electionConfig
	 */
	public function setElectionConfig( $electionConfig ) {
		$this->electionConfig = $electionConfig;
	}


	/**
	 * @return \DateTime
	 */
	public function getCreated(): \DateTime {
		return $this->created;
	}

	/**
	 * @param \DateTime $created
	 */
	public function setCreated( \DateTime $created ) {
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
	 * @return \DateTime
	 */
	public function getStartDate(): \DateTime {
		return $this->startDate;
	}

	/**
	 * @param \DateTime $startDate
	 */
	public function setStartDate( \DateTime $startDate ) {
		$this->startDate = $startDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getEndDate(): \DateTime {
		return $this->endDate;
	}

	/**
	 * @param \DateTime $endDate
	 */
	public function setEndDate( \DateTime $endDate ) {
		$this->endDate = $endDate;
	}

	/**
	 * @return string
	 */
	public function getContact(){
		return $this->contact;
	}

	/**
	 * @param string $contact
	 */
	public function setContact( string $contact ) {
		$this->contact = $contact;
	}

	/**
	 * @return string
	 */
	public function getEmail(){
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail( string $email ) {
		$this->email = $email;
	}



	/**
	 * @return ElectionLocation
	 */
	public function getLocation() {
		return $this->location;
	}

	/**
	 * @param ElectionLocation $location
	 */
	public function setLocation( $location ) {
		$this->location = $location;
	}

	/**
	 * @return ElectionMember
	 */
	public function getOrganiser() {
		return $this->organiser;
	}

	/**
	 * @param ElectionMember $organiser
	 */
	public function setOrganiser( $organiser ) {
		$this->organiser = $organiser;
	}

	/**
	 * @return string
	 */
	public function getMobile(){
		return $this->mobile;
	}

	/**
	 * @param string $mobile
	 */
	public function setMobile( string $mobile ) {
		$this->mobile = $mobile;
	}

	/**
	 * @return ElectionParticular
	 */
	public function getEventType() {
		return $this->eventType;
	}

	/**
	 * @param ElectionParticular $eventType
	 */
	public function setEventType( $eventType ) {
		$this->eventType = $eventType;
	}


	/**
	 * Sets file.
	 *
	 * @param ElectionEvent $file
	 */
	public function setFile(UploadedFile $file = null)
	{
		$this->file = $file;
	}

	/**
	 * Get file.
	 *
	 * @return ElectionEvent
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
	public function getFacebookEvent(){
		return $this->facebookEvent;
	}

	/**
	 * @param string $facebookEvent
	 */
	public function setFacebookEvent( string $facebookEvent ) {
		$this->facebookEvent = $facebookEvent;
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
	 * @return \DateTime
	 */
	public function getEventDate(){
		return $this->eventDate;
	}

	/**
	 * @param \DateTime $eventDate
	 */
	public function setEventDate( \DateTime $eventDate ) {
		$this->eventDate = $eventDate;
	}

	/**
	 * @return string
	 */
	public function getEventTime(){
		return $this->eventTime;
	}

	/**
	 * @param string $eventTime
	 */
	public function setEventTime( string $eventTime ) {
		$this->eventTime = $eventTime;
	}

	/**
	 * @return ElectionEventMember
	 */
	public function getEventMembers() {
		return $this->eventMembers;
	}

	/**
	 * @return ElectionSetup
	 */
	public function getElectionSetup() {
		return $this->electionSetup;
	}

	/**
	 * @param ElectionSetup $electionSetup
	 */
	public function setElectionSetup( $electionSetup ) {
		$this->electionSetup = $electionSetup;
	}


}

