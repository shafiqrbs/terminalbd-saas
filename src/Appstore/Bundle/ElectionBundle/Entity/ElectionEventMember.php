<?php

namespace Appstore\Bundle\ElectionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ElectionEventGuest
 *
 * @ORM\Table( name ="election_event_guest")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\ElectionBundle\Repository\ElectionEventRepository")
 */
class ElectionEventMember
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
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionEvent", inversedBy="eventMembers" , cascade={"detach","merge"} )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
	private  $event;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", length=100, nullable=true)
	 */
	private $name;


	/**
	 * @var string
	 *
	 * @ORM\Column(name="designation", type="string", length=100, nullable=true)
	 */
	private $designation;


	/**
	 * @var string
	 *
	 * @ORM\Column(name="mobile", type="string", length=100, nullable=true)
	 */
	private $mobile;


	/**
	 * @var string
	 *
	 * @ORM\Column(name="description", type="text", nullable=true)
	 */
	private $description;


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
	 * @return string
	 */
	public function getDescription(){
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription( string $description ) {
		$this->description = $description;
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
	 * @return ElectionEvent
	 */
	public function getEvent() {
		return $this->event;
	}

	/**
	 * @param ElectionEvent $event
	 */
	public function setEvent( $event ) {
		$this->event = $event;
	}

	/**
	 * @return string
	 */
	public function getDesignation(){
		return $this->designation;
	}

	/**
	 * @param string $designation
	 */
	public function setDesignation( string $designation ) {
		$this->designation = $designation;
	}


}

