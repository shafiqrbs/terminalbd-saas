<?php
namespace Appstore\Bundle\ElectionBundle\Importer;
use Appstore\Bundle\ElectionBundle\Entity\ElectionMember;
use Appstore\Bundle\ElectionBundle\Entity\ElectionParticular;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;


class Voter
{
    use ContainerAwareTrait;

	protected $electionConfig;
    private $data = array();
    private $cache = array();

	public function isValid($data) {

		return true;

	}

    public function import($data)
    {
        $this->data = $data;

        foreach($this->data as $key => $item) {

          	$name = trim($item['Name']);
	        $fatherName = trim($item['FatherName']);
	        $nid = trim($item['NID']);
            $entity = $this->getDoctrain()->getRepository('ElectionBundle:ElectionMember')->findOneBy(array('electionConfig' => $this->getElectionConfig(),'memberType'=>'voter','name' => $name,'fatherName' => $fatherName,'nid'=>$nid));
	        if(empty($entity) and !empty($name) and !empty($fatherName) ) {
		        $member = new ElectionMember();
		        $member->setElectionConfig($this->getElectionConfig());
		        $member->setName($name);
		        $member->setMobile(trim($item['MobileNo']));
		        $member->setFatherName(trim($item['FatherName']));
		        $member->setMotherName(trim($item['MotherName']));
		        $member->setNid(trim($item['NID']));
		        $member->setAddress(trim($item['Address']));
		        $member->setVillage(trim($item['Village']));
		        $member->setPostOffice(trim($item['PostOffice']));
		        $member->setPostalCode(trim($item['PostalCode']));
		        $member->setBloodGroup(trim($item['BloodGroup']));
		        $member->setProcess('Import');
		        $member->setMemberType('voter');
		        $this->persist( $member );
		        $this->flush();
	        }
        }
    }

	private function getParticular($particular)
	{
		$entity = $this->getCachedData($particular, $particular);
		$particularRepository = $this->getElectionParticularRepository();
		if($entity == NULL) {

			$entity = $particularRepository->findOneBy(array(
				'electionConfig'   => $this->getElectionConfig(),
				'name'              => $particular
			));

			if($entity == null) {
				$entity = new ElectionParticular();
				$entity->setName($particular);
				$entity->setElectionConfig($this->getElectionConfig());
				$entity = $this->save($entity);
			}

			$this->setCachedData($particular , $particular, $entity);
		}

		return $entity;
	}

	public function setElectionConfig($config)
	{
		$this->electionConfig = $config;
	}


	private function getElectionConfig()
	{
		$electionConfig = $this->getCachedData('ElectionConfig', $this->electionConfig);

		if($electionConfig == NULL) {
			$electionConfig = $this->getDoctrain()->getRepository('ElectionBundle:ElectionConfig')->find($this->electionConfig);
			$this->setCachedData('ElectionConfig', $this->electionConfig, $electionConfig);
		}

		return $electionConfig;
	}


    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    private function getEntityManager()
    {
        return $this->getDoctrain()->getManager();
    }

    /**
     * @return \Appstore\Bundle\ElectionBundle\Repository\ElectionParticularRepository;
     */
    private function getElectionParticularRepository()
    {
        return $this->getDoctrain()->getRepository( 'ElectionBundle:ElectionParticular' );
    }



    private function save($entity){
        $this->persist($entity);
        $this->getEntityManager()->flush();
        return $entity;
    }

    private function persist($entity){
        $this->getEntityManager()->persist($entity);
    }

    private function getCachedData($type, $key)
    {
        if(isset($this->cache[$type][$key])){
            return $this->cache[$type][$key];
        }

        return NULL;
    }

    private function setCachedData($type, $key, $value)
    {
        $this->cache[$type][$key] = $value;
    }


    private function flush()
    {
        $this->getEntityManager()->flush();
    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    private function getDoctrain()
    {
        return $this->container->get('doctrine');
    }

    function sentence_case($string) {
        $sentences = preg_split('/([.?!]+)/', $string, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
        $new_string = '';
        foreach ($sentences as $key => $sentence) {
            $new_string .= ($key & 1) == 0?
                ucfirst(strtolower(trim($sentence))) :
                $sentence.' ';
        }
        return trim($new_string);
    }


}