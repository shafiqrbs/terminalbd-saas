<?php

namespace Appstore\Bundle\ElectionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ElectionCandidateSetup
 *
 * @ORM\Table( name ="election_candidate")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\ElectionBundle\Repository\ElectionCandidateRepository")
 */
class ElectionCandidate
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionConfig", inversedBy="candidates" , cascade={"detach","merge"} )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $electionConfig;


	/**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionLocation", inversedBy="candidates" , cascade={"detach","merge"} )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $location;

	/**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionSetup", inversedBy="candidates" , cascade={"detach","merge"} )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $electionSetup;

	/**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionVoteMatrix", mappedBy="candidate" , cascade={"detach","merge"} )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $voteMatrix;


	/**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionParticular", inversedBy="electionCandidateParty" , cascade={"detach","merge"} )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $politicalParty;


	/**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionParticular", inversedBy="electionCandidateMarka" , cascade={"detach","merge"} )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $marka;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", length=100, nullable=true)
	 */
	private $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="mobile", type="string", length=100, nullable=true)
	 */
	private $mobile;


    /**
     * @var int
     *
     * @ORM\Column(name="totalVote", type="integer",  length = 10, nullable=true)
     */
    private $totalVote = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="maleVote", type="integer",  length = 10, nullable=true)
     */
    private $maleVote = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="femaleVote", type="integer",  length = 10, nullable=true)
     */
    private $femaleVote = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="otherVote", type="integer",  length = 10, nullable=true)
     */
    private $otherVote = 0;

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
	 * @return mixed
	 */
	public function getPoliticalParty() {
		return $this->politicalParty;
	}

	/**
	 * @param mixed $politicalParty
	 */
	public function setPoliticalParty( $politicalParty ) {
		$this->politicalParty = $politicalParty;
	}

	/**
	 * @return mixed
	 */
	public function getMarka() {
		return $this->marka;
	}

	/**
	 * @param mixed $marka
	 */
	public function setMarka( $marka ) {
		$this->marka = $marka;
	}

	/**
	 * @return int
	 */
	public function getTotalVote(): int {
		return $this->totalVote;
	}

	/**
	 * @param int $totalVote
	 */
	public function setTotalVote( int $totalVote ) {
		$this->totalVote = $totalVote;
	}

	/**
	 * @return int
	 */
	public function getMaleVote(): int {
		return $this->maleVote;
	}

	/**
	 * @param int $maleVote
	 */
	public function setMaleVote( int $maleVote ) {
		$this->maleVote = $maleVote;
	}

	/**
	 * @return int
	 */
	public function getFemaleVote(): int {
		return $this->femaleVote;
	}

	/**
	 * @param int $femaleVote
	 */
	public function setFemaleVote( int $femaleVote ) {
		$this->femaleVote = $femaleVote;
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
	 * @return ElectionVoteMatrix
	 */
	public function getVoteMatrix() {
		return $this->voteMatrix;
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
	 * @return int
	 */
	public function getOtherVote(){
		return $this->otherVote;
	}

	/**
	 * @param int $otherVote
	 */
	public function setOtherVote( int $otherVote ) {
		$this->otherVote = $otherVote;
	}


}

