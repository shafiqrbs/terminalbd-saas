<?php

namespace Appstore\Bundle\ElectionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ElectionSetup
 *
 * @ORM\Table( name ="election_setup")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\ElectionBundle\Repository\ElectionSetupRepository")
 */
class ElectionSetup
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
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionConfig", inversedBy="electionSetups" , cascade={"detach","merge"} )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
	private  $electionConfig;

	/**
	 * @ORM\OneToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionConfig", mappedBy="setup")
	 **/
	private  $dashboard;

	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionLocation", inversedBy="electionSetup")
     * @ORM\JoinColumn(onDelete="CASCADE")
	 **/
	protected $location;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionVoteMatrix", mappedBy="electionSetup")
	 **/
	protected $voteMatrix;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionCommittee", mappedBy="electionSetup")
	 **/
	protected $electionCommittees;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionEvent", mappedBy="electionSetup")
	 **/
	protected $events;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionCampaignAnalysis", mappedBy="electionSetup")
	 **/
	protected $campaigns;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionCandidate", mappedBy="electionSetup")
	 **/
	protected $candidates;

	/**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionVoteCenter", mappedBy="electionSetup")
	 * @ORM\OrderBy({"voteCenterName" = "ASC"})
	 **/
	protected $votecenters;

	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionParticular", inversedBy="electionSetup")
	 **/
	protected $electionType;


	/**
	 * @var \DateTime
	 * @ORM\Column(name="electionDate", type="datetime", nullable=true)
	 */
	private $electionDate;


	/**
	 * @var string
	 *
	 * @ORM\Column(name="district", type="string", length=200, nullable = true)
	 */
	private $district;


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
	 * @var int
	 *
	 * @ORM\Column(name="voteCenter", type="integer",  length = 4, nullable=true)
	 */
	private $voteCenter = 0;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="resultTotalVote", type="integer",  length = 10, nullable=true)
	 */
	private $resultTotalVote = 0;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="resultMaleVote", type="integer",  length = 10, nullable=true)
	 */
	private $resultMaleVote = 0;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="resultFemaleVote", type="integer",  length = 10, nullable=true)
	 */
	private $resultFemaleVote = 0;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="resultOtherVote", type="integer",  length = 10, nullable=true)
	 */
	private $resultOtherVote = 0;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="resultInvalidVote", type="integer",  length = 10, nullable=true)
	 */
	private $resultInvalidVote = 0;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="resultVoteCenter", type="integer",  length = 4, nullable=true)
	 */
	private $resultVoteCenter = 0;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="activeVoteCenter", type="integer",  length = 4, nullable=true)
	 */
	private $activeVoteCenter = 0;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="holdVoteCenter", type="integer",  length = 4, nullable=true)
	 */
	private $holdVoteCenter = 0;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="rejectedVoteCenter", type="integer",  length = 4, nullable=true)
	 */
	private $rejectedVoteCenter = 0;


	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="currentElection", type="boolean" )
	 */
	private $currentElection = true;

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
	 * @return string
	 */
	public function getDistrict(): string {
		return $this->district;
	}

	/**
	 * @param string $district
	 */
	public function setDistrict( string $district ) {
		$this->district = $district;
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
	 * @return ElectionParticular
	 */
	public function getElectionType() {
		return $this->electionType;
	}

	/**
	 * @param ElectionParticular $electionType
	 */
	public function setElectionType( $electionType ) {
		$this->electionType = $electionType;
	}

	/**
	 * @return \DateTime
	 */
	public function getElectionDate(){
		return $this->electionDate;
	}

	/**
	 * @param \DateTime $electionDate
	 */
	public function setElectionDate( \DateTime $electionDate ) {
		$this->electionDate = $electionDate;
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
	 * @return \DateTime
	 */
	public function getUpdated(){
		return $this->updated;
	}

	/**
	 * @param \DateTime $updated
	 */
	public function setUpdated( \DateTime $updated ) {
		$this->updated = $updated;
	}

	/**
	 * @return ElectionVoteMatrix
	 */
	public function getVoteMatrix() {
		return $this->voteMatrix;
	}

	/**
	 * @return ElectionVoteCenter
	 */
	public function getVotecenters() {
		return $this->votecenters;
	}

	/**
	 * @return ElectionCandidate
	 */
	public function getCandidates() {
		return $this->candidates;
	}

	public function getElectionName(){

		$year = $this->getElectionDate()->format('y');
		$type = $this->getElectionType()->getName();
		$data = $type.' - '.$year.' => '.$this->getLocation()->getName();
		return $data;

	}

	public function getSetupName(){

		$year = $this->getElectionDate()->format('y');
		$type = $this->getElectionType()->getName();
		$data = $type.' - '.$year.', '.$this->getLocation()->getName();
		return $data;

	}

	/**
	 * @return int
	 */
	public function getVoteCenter(){
		return $this->voteCenter;
	}

	/**
	 * @param int $voteCenter
	 */
	public function setVoteCenter( int $voteCenter ) {
		$this->voteCenter = $voteCenter;
	}

	/**
	 * @return bool
	 */
	public function getCurrentElection(){
		return $this->currentElection;
	}

	/**
	 * @param bool $currentElection
	 */
	public function setCurrentElection( bool $currentElection ) {
		$this->currentElection = $currentElection;
	}


	/**
	 * @return int
	 */
	public function getResultOtherVote(){
		return $this->resultOtherVote;
	}

	/**
	 * @param int $resultOtherVote
	 */
	public function setResultOtherVote( int $resultOtherVote ) {
		$this->resultOtherVote = $resultOtherVote;
	}

	/**
	 * @return int
	 */
	public function getResultFemaleVote(){
		return $this->resultFemaleVote;
	}

	/**
	 * @param int $resultFemaleVote
	 */
	public function setResultFemaleVote( int $resultFemaleVote ) {
		$this->resultFemaleVote = $resultFemaleVote;
	}

	/**
	 * @return int
	 */
	public function getResultMaleVote(){
		return $this->resultMaleVote;
	}

	/**
	 * @param int $resultMaleVote
	 */
	public function setResultMaleVote( int $resultMaleVote ) {
		$this->resultMaleVote = $resultMaleVote;
	}

	/**
	 * @return int
	 */
	public function getResultTotalVote(){
		return $this->resultTotalVote;
	}

	/**
	 * @param int $resultTotalVote
	 */
	public function setResultTotalVote( int $resultTotalVote ) {
		$this->resultTotalVote = $resultTotalVote;
	}


	/**
	 * @return int
	 */
	public function getResultInvalidVote(){
		return $this->resultInvalidVote;
	}

	/**
	 * @param int $resultInvalidVote
	 */
	public function setResultInvalidVote( int $resultInvalidVote ) {
		$this->resultInvalidVote = $resultInvalidVote;
	}

	/**
	 * @return int
	 */
	public function getActiveVoteCenter(){
		return $this->activeVoteCenter;
	}

	/**
	 * @return int
	 */
	public function getResultVoteCenter(){
		return $this->resultVoteCenter;
	}

	/**
	 * @param int $resultVoteCenter
	 */
	public function setResultVoteCenter( int $resultVoteCenter ) {
		$this->resultVoteCenter = $resultVoteCenter;
	}



	/**
	 * @param int $activeVoteCenter
	 */
	public function setActiveVoteCenter( int $activeVoteCenter ) {
		$this->activeVoteCenter = $activeVoteCenter;
	}

	/**
	 * @return int
	 */
	public function getHoldVoteCenter(){
		return $this->holdVoteCenter;
	}

	/**
	 * @param int $holdVoteCenter
	 */
	public function setHoldVoteCenter( int $holdVoteCenter ) {
		$this->holdVoteCenter = $holdVoteCenter;
	}

	/**
	 * @return int
	 */
	public function getRejectedVoteCenter(){
		return $this->rejectedVoteCenter;
	}

	/**
	 * @param int $rejectedVoteCenter
	 */
	public function setRejectedVoteCenter( int $rejectedVoteCenter ) {
		$this->rejectedVoteCenter = $rejectedVoteCenter;
	}

	/**
	 * @return ElectionCommittee
	 */
	public function getElectionCommittees() {
		return $this->electionCommittees;
	}

	/**
	 * @return ElectionEvent
	 */
	public function getEvents() {
		return $this->events;
	}

	/**
	 * @return ElectionCampaignAnalysis
	 */
	public function getCampaigns() {
		return $this->campaigns;
	}


}

