<?php

namespace Appstore\Bundle\HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HotelParticularMeta
 *
 * @ORM\Table( name = "hotel_particular_meta")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\HotelBundle\Repository\HotelParticularMetaRepository")
 */
class HotelParticularMeta
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
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelParticular", inversedBy="particularMetas" )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
	protected $particular;


	/**
	 * @var string
	 *
	 * @ORM\Column(name="metaKey", type="string", nullable= true)
	 */
	private $metaKey;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="metaValue", type="string", nullable= true)
	 */
	private $metaValue;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="sorting", type="smallint", length=5, nullable=true)
	 */
	private $sorting;



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
	 * @param int $showLimit
	 */
	public function setShowLimit($showLimit)
	{
		$this->showLimit = $showLimit;
	}

	/**
	 * @return string
	 */
	public function getMetaKey()
	{
		return $this->metaKey;
	}

	/**
	 * @param string $metaKey
	 */
	public function setMetaKey($metaKey)
	{
		$this->metaKey = $metaKey;
	}

	/**
	 * @return string
	 */
	public function getMetaValue()
	{
		return $this->metaValue;
	}

	/**
	 * @param string $metaValue
	 */
	public function setMetaValue($metaValue)
	{
		$this->metaValue = $metaValue;
	}

	/**
	 * @return HotelParticular
	 */
	public function getParticular() {
		return $this->particular;
	}

	/**
	 * @param HotelParticular $particular
	 */
	public function setParticular( $particular ) {
		$this->particular = $particular;
	}

	/**
	 * @return int
	 */
	public function getSorting(){
		return $this->sorting;
	}

	/**
	 * @param int $sorting
	 */
	public function setSorting( int $sorting ) {
		$this->sorting = $sorting;
	}

}

