<?php

namespace Appstore\Bundle\ElectionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ElectionVoteCount
 *
 * @ORM\Table( name ="election_vote_matrix")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\ElectionBundle\Repository\ElectionVoteMatrixRepository")
 */
class ElectionVoteMatrix
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
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionCandidate", inversedBy="voteMatrix")
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
	protected $candidate;

	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionSetup", inversedBy="voteMatrix")
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
	protected $electionSetup;


	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionVoteCenter", inversedBy="voteMatrix")
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
	protected $voteCenter;

	/**
     * @var int
     *
     * @ORM\Column(name="totalVoter", type="integer",  length = 10, nullable=true)
     */
    private $totalVoter = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="maleVoter", type="integer",  length = 10, nullable=true)
     */
    private $maleVoter = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="femaleVoter", type="integer",  length = 10, nullable=true)
     */
    private $femaleVoter = 0;

	/**
     * @var int
     *
     * @ORM\Column(name="otherVoter", type="integer",  length = 10, nullable=true)
     */
    private $otherVoter = 0;


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
	 * @return int
	 */
	public function getTotalVoter() {
		return $this->totalVoter;
	}

	/**
	 * @param int $totalVoter
	 */
	public function setTotalVoter( $totalVoter ) {
		$this->totalVoter = $totalVoter;
	}

	/**
	 * @return int
	 */
	public function getMaleVoter(){
		return $this->maleVoter;
	}

	/**
	 * @param int $maleVoter
	 */
	public function setMaleVoter( int $maleVoter ) {
		$this->maleVoter = $maleVoter;
	}

	/**
	 * @return int
	 */
	public function getFemaleVoter(){
		return $this->femaleVoter;
	}

	/**
	 * @param int $femaleVoter
	 */
	public function setFemaleVoter( int $femaleVoter ) {
		$this->femaleVoter = $femaleVoter;
	}

	/**
	 * @return int
	 */
	public function getOtherVoter(){
		return $this->otherVoter;
	}

	/**
	 * @param int $otherVoter
	 */
	public function setOtherVoter( int $otherVoter ) {
		$this->otherVoter = $otherVoter;
	}

	/**
	 * @return ElectionVoteCenter
	 */
	public function getVoteCenter() {
		return $this->voteCenter;
	}

	/**
	 * @param ElectionVoteCenter $voteCenter
	 */
	public function setVoteCenter( $voteCenter ) {
		$this->voteCenter = $voteCenter;
	}

	/**
	 * @return ElectionCandidate
	 */
	public function getCandidate() {
		return $this->candidate;
	}

	/**
	 * @param ElectionCandidate $candidate
	 */
	public function setCandidate( $candidate ) {
		$this->candidate = $candidate;
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

