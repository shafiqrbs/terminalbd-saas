<?php

namespace Appstore\Bundle\AssetsBundle\Entity;

use Appstore\Bundle\AccountingBundle\Entity\AccountHead;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * Depreciation
 *
 * @ORM\Table("assets_depreciation_batch")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\AssetsBundle\Repository\DepreciationBatchRepository")
 */
class DepreciationBatch
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
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\AssetsConfig", inversedBy="depreciationBatches" )
	 **/
	private  $config;

	/**
	 * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\DepreciationModel", inversedBy="depreciationBatch" )
	 **/
	private  $depreciation;

   /**
	 * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\ProductLedger", mappedBy="batch" )
	 **/
	private  $ledgers;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="depreciationEffectedDate", type="date",  nullable=true)
     */
    private $depreciationDate;


    /**
     * @var string
     *
     * @ORM\Column(name="items", type="text", nullable=true)
     */
    private $items;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float", nullable=true)
     */
    private $amount=0;




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
     * @return AssetsConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param AssetsConfig $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return DepreciationModel
     */
    public function getDepreciation()
    {
        return $this->depreciation;
    }

    /**
     * @param DepreciationModel $depreciation
     */
    public function setDepreciation($depreciation)
    {
        $this->depreciation = $depreciation;
    }

    /**
     * @return \DateTime
     */
    public function getDepreciationDate()
    {
        return $this->depreciationDate;
    }

    /**
     * @param \DateTime $depreciationDate
     */
    public function setDepreciationDate($depreciationDate)
    {
        $this->depreciationDate = $depreciationDate;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return ProductLedger
     */
    public function getLedgers()
    {
        return $this->ledgers;
    }

    /**
     * @return string
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param string $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }


}

