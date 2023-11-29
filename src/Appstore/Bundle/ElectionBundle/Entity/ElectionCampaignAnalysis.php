<?php

namespace Appstore\Bundle\ElectionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ElectionParticular
 *
 * @ORM\Table( name ="election_campaign_analysis")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\ElectionBundle\Repository\ElectionCampaignAnalysisRepository")
 */
class ElectionCampaignAnalysis
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionConfig", inversedBy="campaignAnalysis" , cascade={"detach","merge"} )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $electionConfig;


    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionParticular", inversedBy="campaignAnalysisType" , cascade={"detach","merge"} )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $analysisType;

	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionSetup", inversedBy="campaigns")
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
	protected $electionSetup;

	/**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionParticular", inversedBy="campaignAnalysisPriority" , cascade={"detach","merge"} )
     **/
    private  $priority;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ElectionBundle\Entity\ElectionLocation", inversedBy="campaignAnalysis" , cascade={"detach","merge"} )
     **/
    private  $location;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

	/**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

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
	 * @return mixed
	 */
	public function getAnalysisType() {
		return $this->analysisType;
	}

	/**
	 * @param mixed $analysisType
	 */
	public function setAnalysisType( $analysisType ) {
		$this->analysisType = $analysisType;
	}

	/**
	 * @return mixed
	 */
	public function getPriority() {
		return $this->priority;
	}

	/**
	 * @param mixed $priority
	 */
	public function setPriority( $priority ) {
		$this->priority = $priority;
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


}

