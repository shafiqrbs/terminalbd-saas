<?php
namespace Appstore\Bundle\ElectionBundle\Importer;
use Appstore\Bundle\ElectionBundle\Entity\ElectionMember;
use Appstore\Bundle\ElectionBundle\Entity\ElectionParticular;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;


class Member
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
	        $mobile  = '0'.trim($item['MobileNo']);
            $entity = $this->getDoctrain()->getRepository('ElectionBundle:ElectionMember')->findOneBy(array('electionConfig' => $this->getElectionConfig(),'memberType'=>'member','name' => $name,'mobile' => $mobile));
	        if(empty($entity) and !empty($name) and !empty($mobile) ) {
		        $member = new ElectionMember();
		        $member->setElectionConfig($this->getElectionConfig());
		        $member->setName($name);
		        $member->setMobile($mobile);
		        $member->setFatherName(trim($item['FatherName']));
		        $member->setNameBn(trim($item['NameBN']));
		        $member->setMotherName(trim($item['MotherName']));
		        $member->setNid(trim($item['NID']));
		        $member->setAge(trim($item['Age']));
		        $member->setGender(trim($item['Gender']));
		        $member->setNationality(trim($item['Nationality']));
				if(!empty(trim($item['Education']))){
					$particular = $this->getParticular(trim($item['Education']),'education');
					$member->setEducation($particular);
				}
		        if(!empty(trim($item['Profession']))){
				    $particular = $this->getParticular(trim($item['Profession']),'profession');
					$member->setProfession($particular);
				}
		        $member->setEmail(trim($item['Email']));
		        $member->setFacebookId(trim($item['FacebookID']));
		        $member->setAddress(trim($item['Address']));
		        $member->setVillage(trim($item['Village']));
		        $member->setWard(trim($item['Ward']));
		        $member->setVoteCenterName(trim($item['VoteCenter']));
		        $member->setMemberUnion(trim($item['Union']));
		        $member->setThana(trim($item['Thana']));
		        $member->setDistrict(trim($item['District']));
		        $member->setPostOffice(trim($item['PostOffice']));
		        $member->setPostalCode(trim($item['PostalCode']));
		        $member->setBloodGroup(trim($item['BloodGroup']));
		        $member->setReligion(trim($item['Religion']));
		        $member->setFamilyMember(trim($item['FamilyMember']));
		        if(!empty(trim($item['PoliticalStatus']))){
			        $particular = $this->getParticular(trim($item['PoliticalStatus']),'political');
			        $member->setPoliticalStatus($particular);
		        }
		        $member->setProcess('Import');
		        $member->setMemberType('member');
		        $this->persist( $member );
		        $this->flush();
	        }
        }
    }

	private function getParticular($particular,$type)
	{
		$entity = $this->getCachedData($particular, $particular);
		$particularRepository = $this->getElectionParticularRepository();
		$particularTypeRepository = $this->getElectionParticularTypeRepository();
		if($entity == NULL) {

			$entity = $particularRepository->findOneBy(array(
				'electionConfig'   => $this->getElectionConfig(),
				'name'              => $particular
			));
			$particularType = $particularTypeRepository->findOneBy(array('slug'=> $type));
			if($entity == null) {
				$entity = new ElectionParticular();
				$entity->setName($particular);
				$entity->setParticularType($particularType);
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

	/**
     * @return \Appstore\Bundle\ElectionBundle\Repository\ElectionParticularTypeRepository;
     */
    private function getElectionParticularTypeRepository()
    {
        return $this->getDoctrain()->getRepository('ElectionBundle:ElectionParticularType');
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