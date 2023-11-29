<?php

namespace Appstore\Bundle\ElectionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ElectionVoteCenterMember
 *
 * @ORM\Table( name ="election_vote_center_member")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\ElectionBundle\Repository\ElectionVoteCenterMemberRepository")
 */
class ElectionVoteCenterMember
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionMember", inversedBy="voteCenterMembers" , cascade={"detach","merge"} )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $member;

	/**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionVoteCenter", inversedBy="centerMembers" , cascade={"detach","merge"} )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $voteCenter;


	/**
	 * @var string
	 *
	 * @ORM\Column(name="agentMobile", type="string",  length =100, nullable=true)
	 */
	private $agentMobile;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="personType", type="string",  length =50, nullable=true)
	 */
	private $personType;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="poolingOfficer", type="string",  length =150, nullable=true)
	 */
	private $poolingOfficer;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="poolingMobile", type="string",  length =150, nullable=true)
	 */
	private $poolingMobile;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="boothNo", type="string",  length =10, nullable=true)
	 */
	private $boothNo = 0;


    /**
     * @var boolean
     *
     * @ORM\Column(name="isMaster", type="boolean" )
     */
    private $isMaster = false;

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
	 * @return bool
	 */
	public function isMaster(){
		return $this->isMaster;
	}

	/**
	 * @param bool $isMaster
	 */
	public function setIsMaster( bool $isMaster ) {
		$this->isMaster = $isMaster;
	}

	/**
	 * @return ElectionMember
	 */
	public function getElectionMember() {
		return $this->electionMember;
	}

	/**
	 * @param ElectionMember $electionMember
	 */
	public function setElectionMember( $electionMember ) {
		$this->electionMember = $electionMember;
	}

	/**
	 * @return string
	 */
	public function getPersonType(){
		return $this->personType;
	}

	/**
	 * @param string $personType
	 * Agent
	 * Pooling
	 */
	public function setPersonType( string $personType ) {
		$this->personType = $personType;
	}

	/**
	 * @return string
	 */
	public function getAgentMobile(){
		return $this->agentMobile;
	}

	/**
	 * @param string $agentMobile
	 */
	public function setAgentMobile( string $agentMobile ) {
		$this->agentMobile = $agentMobile;
	}

	/**
	 * @return string
	 */
	public function getPoolingOfficer(){
		return $this->poolingOfficer;
	}

	/**
	 * @param string $poolingOfficer
	 */
	public function setPoolingOfficer( string $poolingOfficer ) {
		$this->poolingOfficer = $poolingOfficer;
	}

	/**
	 * @return string
	 */
	public function getPoolingMobile() {
		return $this->poolingMobile;
	}

	/**
	 * @param string $poolingMobile
	 */
	public function setPoolingMobile( string $poolingMobile ) {
		$this->poolingMobile = $poolingMobile;
	}

	/**
	 * @return string
	 */
	public function getBoothNo(){
		return $this->boothNo;
	}

	/**
	 * @param string $boothNo
	 */
	public function setBoothNo( string $boothNo ) {
		$this->boothNo = $boothNo;
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
	 * @return ElectionMember
	 */
	public function getMember() {
		return $this->member;
	}

	/**
	 * @param ElectionMember $member
	 */
	public function setMember( $member ) {
		$this->member = $member;
	}


}

