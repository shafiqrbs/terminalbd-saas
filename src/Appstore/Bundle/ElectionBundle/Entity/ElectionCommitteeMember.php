<?php

namespace Appstore\Bundle\ElectionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ElectionCommitteeMember
 *
 * @ORM\Table( name ="election_committee_member")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\ElectionBundle\Repository\ElectionCommitteeMemberRepository")
 */
class ElectionCommitteeMember
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionCommittee", inversedBy="members" , cascade={"detach","merge"} )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $committee;

	/**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionMember", inversedBy="committees" , cascade={"detach","merge"} )
     **/
    private  $member;

	/**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionParticular", inversedBy="committeeMember" , cascade={"detach","merge"} )
     **/
    private  $designation;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="remark", type="string", length=100, nullable=true)
	 */
	private $remark;


	/**
     * @var boolean
     *
     * @ORM\Column(name="isMaster", type="boolean" )
     */
    private $isMaster = true;

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
	 * @return ElectionParticular
	 */
	public function getDesignation() {
		return $this->designation;
	}

	/**
	 * @param ElectionParticular $designation
	 */
	public function setDesignation( $designation ) {
		$this->designation = $designation;
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
	 * @return ElectionCommittee
	 */
	public function getCommittee() {
		return $this->committee;
	}

	/**
	 * @param ElectionCommittee $committee
	 */
	public function setCommittee( $committee ) {
		$this->committee = $committee;
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

