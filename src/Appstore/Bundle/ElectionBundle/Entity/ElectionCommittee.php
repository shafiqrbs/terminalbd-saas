<?php

namespace Appstore\Bundle\ElectionBundle\Entity;

use Core\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Setting\Bundle\LocationBundle\Entity\Location;

/**
 * ElectionCommittee
 *
 * @ORM\Table( name ="election_committee")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\ElectionBundle\Repository\ElectionCommitteeRepository")
 */
class ElectionCommittee
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionConfig", inversedBy="committees" , cascade={"detach","merge"} )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $electionConfig;


	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\electionLocation", inversedBy="committees")
     * @ORM\JoinColumn(onDelete="CASCADE")
	 **/
	protected $location;

	/**
	 * @ORM\ManyToOne(targetEntity="Setting\Bundle\LocationBundle\Entity\Location", inversedBy="committees")
	 **/
	protected $geoLocation;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionCommitteeMember", mappedBy="committee")
     **/
	protected $members;


	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionSms", mappedBy="committee")
	 **/
	protected $sms;


	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionSetup", inversedBy="electionCommittees")
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
	protected $electionSetup;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionParticular", inversedBy="locationTypes")
     **/
    protected $locationType;


     /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionParticular", inversedBy="committeeTypes")
     **/
    protected $committeeType;


    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionParticular", inversedBy="politicalWings" , cascade={"detach","merge"} )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $politicalWing;

    /**
     * @var string
     *
     * @ORM\Column(name="mode", type="string", length = 50, nullable = true)
     */
    private $mode = 'election';



    /**
	 * @Gedmo\Blameable(on="create")
	 * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User", inversedBy="committeeCreatedBy" )
	 **/
	private  $createdBy;

	/**
	 * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User", inversedBy="committeeApprovedBy" )
	 **/
	private  $approvedBy;


	/**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="remark", type="string", length=100, nullable=true)
	 */
	private $remark;


     /**
     * @var integer
     *
     * @ORM\Column(name="timeDuration", type="smallint", nullable=true)
     */
    private $timeDuration;

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
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=50, nullable=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=10, nullable=true)
     */
    private $code;

     /**
     * @var string
     *
     * @ORM\Column(name="process", type="string", length=20, nullable=true)
     */
    private $process='Created';

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
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
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
	 * @return \DateTime
	 */
	public function getStartDate(){
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
	public function getEndDate(){
		return $this->endDate;
	}

	/**
	 * @param \DateTime $endDate
	 */
	public function setEndDate( \DateTime $endDate ) {
		$this->endDate = $endDate;
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
	 * @return ElectionCommitteeMember
	 */
	public function getMembers() {
		return $this->members;
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
	public function setProcess( string $process ) {
		$this->process = $process;
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

    /**
     * @return Location
     */
    public function getGeoLocation()
    {
        return $this->geoLocation;
    }

    /**
     * @param Location $geoLocation
     */
    public function setGeoLocation($geoLocation)
    {
        $this->geoLocation = $geoLocation;
    }



    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param string $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * @return ElectionParticular
     */
    public function getPoliticalWing()
    {
        return $this->politicalWing;
    }

    /**
     * @param ElectionParticular $politicalWing
     */
    public function setPoliticalWing($politicalWing)
    {
        $this->politicalWing = $politicalWing;
    }

    /**
     * @return ElectionParticular
     */
    public function getCommitteeType()
    {
        return $this->committeeType;
    }

    /**
     * @param ElectionParticular $committeeType
     */
    public function setCommitteeType($committeeType)
    {
        $this->committeeType = $committeeType;
    }

    /**
     * @return ElectionParticular
     */
    public function getLocationType()
    {
        return $this->locationType;
    }

    /**
     * @param ElectionParticular $locationType
     */
    public function setLocationType($locationType)
    {
        $this->locationType = $locationType;
    }

    /**
     * @return int
     */
    public function getTimeDuration()
    {
        return $this->timeDuration;
    }

    /**
     * @param int $timeDuration
     */
    public function setTimeDuration($timeDuration)
    {
        $this->timeDuration = $timeDuration;
    }


}

