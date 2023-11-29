<?php

namespace Appstore\Bundle\ElectionBundle\Entity;

use Appstore\Bundle\ElectionBundle\Form\ParticularType;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ElectionParticular
 *
 * @ORM\Table( name ="election_particular")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\ElectionBundle\Repository\ElectionParticularRepository")
 */
class ElectionParticular
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionConfig", inversedBy="electionParticulars" , cascade={"detach","merge"} )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $electionConfig;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionConfig", mappedBy="parliament" , cascade={"detach","merge"} )
     **/
    private  $parliaments;


    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionParticularType", inversedBy="particulars" , cascade={"detach","merge"} )
     **/
    private  $particularType;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionMember", mappedBy="politicalStatus")
     **/
    private $memberPoliticalStatus;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionMember", mappedBy="politicalDesignation")
     **/
    private $memberPoliticalDesignation;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionMember", mappedBy="profession")
     **/
    private $memberProfession;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionMember", mappedBy="education")
     **/
    private $memberEducation;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionMember", mappedBy="oldPoliticalParty")
     **/
    private $memberPoliticalParty;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionLocation", mappedBy="locationType")
     **/
    private $locations;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionCommitteeMember", mappedBy="designation")
     **/
    private $committeeMember;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionCandidate", mappedBy="politicalParty")
     **/
    private $electionCandidateParty;


    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionCandidate", mappedBy="marka")
     **/
    private $electionCandidateMarka;

	/**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionSetup", mappedBy="electionType")
     **/
    private $electionSetup;

	/**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionCampaignAnalysis", mappedBy="priority")
     **/
    private $campaignAnalysisPriority;

	/**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionCampaignAnalysis", mappedBy="analysisType")
     **/
    private $campaignAnalysisType;

	/**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionEvent", mappedBy="eventType")
     **/
    private $event;


	/**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionCommittee", mappedBy="locationType")
     **/
    private $locationTypes;


	/**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionCommittee", mappedBy="committeeType")
     **/
    private $committeeTypes;


	/**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionCommittee", mappedBy="politicalWing")
     **/
    private $politicalWings;


	/**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

	/**
     * @var string
     *
     * @ORM\Column(name="defineSlug", type="string", length=100, nullable=true)
     */
    private $defineSlug;

	/**
	 * @Gedmo\Slug(fields={"name"})
	 * @Doctrine\ORM\Mapping\Column(length=100)
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
	 * @ORM\Column(name="sku", type="string", nullable=true)
	 */
	private $sku;

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
	 * @return ElectionMember
	 */
	public function getElectionMember() {
		return $this->electionMember;
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
	 * @return ElectionCommittee
	 */
	public function getElectionCommittees() {
		return $this->electionCommittees;
	}

	/**
	 * @return ElectionCandidate
	 */
	public function getElectionCandidateParty() {
		return $this->electionCandidateParty;
	}

	/**
	 * @return ElectionCandidate
	 */
	public function getElectionCandidateMarka() {
		return $this->electionCandidateMarka;
	}


	/**
	 * @return ParticularType
	 */
	public function getParticularType() {
		return $this->particularType;
	}

	/**
	 * @param ParticularType $particularType
	 */
	public function setParticularType( $particularType ) {
		$this->particularType = $particularType;
	}

	/**
	 * @return string
	 */
	public function getSku(){
		return $this->sku;
	}

	/**
	 * @param string $sku
	 */
	public function setSku( string $sku ) {
		$this->sku = $sku;
	}

	/**
	 * @return ElectionLocation
	 */
	public function getLocations() {
		return $this->locations;
	}

	/**
	 * @return ElectionConfig
	 */
	public function getParliaments() {
		return $this->parliaments;
	}

	/**
	 * @return ElectionMember
	 */
	public function getMemberPoliticalStatus() {
		return $this->memberPoliticalStatus;
	}

	/**
	 * @return ElectionMember
	 */
	public function getMemberPoliticalDesignation() {
		return $this->memberPoliticalDesignation;
	}

	/**
	 * @return ElectionMember
	 */
	public function getMemberPoliticalParty() {
		return $this->memberPoliticalParty;
	}

	/**
	 * @return ElectionMember
	 */
	public function getMemberEducation() {
		return $this->memberEducation;
	}

	/**
	 * @param ElectionMember $memberEducation
	 */
	public function setMemberEducation( $memberEducation ) {
		$this->memberEducation = $memberEducation;
	}

	/**
	 * @return ElectionMember
	 */
	public function getMemberProfession() {
		return $this->memberProfession;
	}

	/**
	 * @param ElectionMember $memberProfession
	 */
	public function setMemberProfession( $memberProfession ) {
		$this->memberProfession = $memberProfession;
	}

	/**
	 * @return mixed
	 */
	public function getCommitteeMember() {
		return $this->committeeMember;
	}

	/**
	 * @return mixed
	 */
	public function getDefineSlug() {
		return $this->defineSlug;
	}

	/**
	 * @param mixed $defineSlug
	 */
	public function setDefineSlug( $defineSlug ) {
		$this->defineSlug = $defineSlug;
	}


}

