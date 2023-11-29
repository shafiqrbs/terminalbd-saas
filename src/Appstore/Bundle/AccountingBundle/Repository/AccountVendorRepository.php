<?php
namespace Appstore\Bundle\AccountingBundle\Repository;
use Appstore\Bundle\AccountingBundle\Entity\AccountVendor;
use Appstore\Bundle\MedicineBundle\Entity\MedicineVendor;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;

/**
 * VendorRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AccountVendorRepository extends EntityRepository
{

    /**
     * @param $qb
     * @param $data
     */

    protected function handleSearchBetween($qb,$data)
    {

        if(!empty($data))
        {
            $mobile =    isset($data['mobile'])? $data['mobile'] :'';
            $name =    isset($data['name'])? $data['name'] :'';
            $companyName =    isset($data['companyName'])? $data['companyName'] :'';
            if (!empty($mobile)) {
                $qb->andWhere($qb->expr()->like("s.mobile", "'%$$mobile%'"  ));
            }
            if (!empty($name)) {
                $qb->andWhere($qb->expr()->like("s.name", "'%$$name%'"  ));
            }
            if (!empty($companyName)) {
                $qb->andWhere($qb->expr()->like("s.companyName", "'%$companyName%'"  ));
            }
        }

    }

    public function findWithSearch(GlobalOption $globalOption, $data)
    {
        $qb = $this->createQueryBuilder('e');
        $qb->where('e.globalOption = :config')->setParameter('config', $globalOption->getId()) ;
        $this->handleSearchBetween($qb,$data);
        $qb->orderBy('e.companyName','ASC');
        $result = $qb->getQuery();
        return  $result;
    }

    public function getLastId(GlobalOption $global)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('count(e.id)');
        $qb->from('AccountingBundle:Vendor','e');
        $qb->andWhere("e.globalOption = :global");
        $qb->setParameter('global', $global->getId());
        $count = $qb->getQuery()->getSingleScalarResult();
        if($count > 0 ){
            return $count+1;
        }else{
            return 1;
        }

    }

    public function newExistingCustomerForSales($globalOption,$mobile,$data)
    {
        $em = $this->_em;
        $companyName = $data['companyName'];
        $name = $data['customerName'];
        $address = isset($data['customerAddress']) ? $data['customerAddress']:'';
        $entity = $this->findOneBy(array('globalOption' => $globalOption ,'mobile' => $mobile));
        if($entity){
            return $entity;
        }else{
            $entity = new AccountVendor();
            $entity->setMobile($mobile);
            $entity->setCompanyName($companyName);
            $entity->setName($name);
            $entity->setAddress($address);
            $entity->setGlobalOption($globalOption);
            $em->persist($entity);
            $em->flush($entity);
            return $entity;
        }
    }

    public function searchAutoComplete($q, GlobalOption $global)
    {
        $qb = $this->createQueryBuilder('e');
        $qb->select('e.companyName as id');
        $qb->addSelect('e.companyName as text');
        $qb->where($qb->expr()->like("e.companyName", "'$q%'" ));
        $qb->orWhere($qb->expr()->like("e.name", "'$q%'" ));
        $qb->andWhere("e.globalOption = :global");
        $qb->setParameter('global', $global->getId());
        $qb->groupBy('e.id');
        $qb->orderBy('e.companyName', 'ASC');
        $qb->setMaxResults( '30' );
        return $qb->getQuery()->getResult();

    }

    public function insertVendor(MedicineVendor $vendor)
    {
    	$em = $this->_em;
    	$entity = new AccountVendor();
	    $entity->setCompanyName($vendor->getCompanyName());
	    $entity->setName($vendor->getName());
	    $entity->setMobile($vendor->getMobile());
	    $entity->setEmail($vendor->getEmail());
	    $entity->setEmail($vendor->getAddress());
	    $entity->setModule('medicine');
	    $entity->setMode($vendor->getMode());
	    $entity->setOldId($vendor->getId());
	    $entity->setGlobalOption($vendor->getMedicineConfig()->getGlobalOption());
	    $em->persist($entity);
	    $em->flush();
    }

    public function getApiVendor(GlobalOption $entity)
    {

        $config = $entity->getId();
        $qb = $this->createQueryBuilder('s');
        $qb->where('s.globalOption = :config')->setParameter('config', $config) ;
        $qb->orderBy('s.companyName','ASC');
        $result = $qb->getQuery()->getResult();

        $data = array();

        /* @var $row MedicineVendor */

        foreach($result as $key => $row) {
            $data[$key]['vendor_id']    = (int) $row->getId();
            $data[$key]['name']           = $row->getCompanyName();
        }

        return $data;
    }

    public function newVendorCreate($globalOption,$mobile,$data)
    {
        $em = $this->_em;
        $name = $data['companyName'];
        $address = isset($data['companyAddress']) ? $data['companyAddress']:'';
        $entity = $this->findOneBy(array('globalOption' => $globalOption ,'companyName' => $name ,'mobile' => $mobile));
        if($entity){
            return $entity;
        }elseif(!empty($mobile) and !empty($name)){
            $entity = new AccountVendor();
            $entity->setCompanyName($name);
            $entity->setMobile($mobile);
            $entity->setName($name);
            $entity->setAddress($address);
            $entity->setGlobalOption($globalOption);
            $em->persist($entity);
            $em->flush($entity);
            return $entity;
        }
        return false;
    }

    public function insertSalesReturnVendor(GlobalOption $globalOption)
    {
        $em = $this->_em;
        $name = "Sales-Return";
        $mobile = $globalOption->getMobile();
        $entity = $this->findOneBy(array('globalOption' => $globalOption ,'companyName' => $name ,'mobile' => $mobile));
        if($entity){
            return $entity;
        }elseif(!empty($mobile) and !empty($name)){
            $entity = new AccountVendor();
            $entity->setCompanyName($name);
            $entity->setMobile($mobile);
            $entity->setName($name);
            $entity->setGlobalOption($globalOption);
            $em->persist($entity);
            $em->flush($entity);
            return $entity;
        }
        return false;
    }

    public function updateVendorCompanyName(AccountVendor $vendor)
    {
        $com = $vendor->getCompanyName();
        $em = $this->_em;
        $qb = $em->createQueryBuilder();
        $qb->update('AccountingBundle:AccountPurchase', 'mg')
            ->set('mg.companyName', ':companyName')
            ->where('mg.accountVendor = :id')
            ->setParameter('companyName',$com)
            ->setParameter('id', $vendor->getId())
            ->getQuery()
            ->execute();

    }



}
