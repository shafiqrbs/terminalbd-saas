<?php

namespace Appstore\Bundle\AccountingBundle\Repository;
use Appstore\Bundle\AccountingBundle\Entity\AccountPurchase;
use Appstore\Bundle\AccountingBundle\Entity\ExpenditureItem;
use Core\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;


/**
 * ExpenditureItemRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ExpenditureItemRepository extends EntityRepository
{

	protected function handleSearchBetween($qb,$data)
	{

		$grn = isset($data['grn'])? $data['grn'] :'';
		$vendor = isset($data['vendor'])? $data['vendor'] :'';
		$business = isset($data['name'])? $data['name'] :'';
		$brand = isset($data['brandName'])? $data['brandName'] :'';
		$mode = isset($data['mode'])? $data['mode'] :'';
		$vendorId = isset($data['vendorId'])? $data['vendorId'] :'';
		$startDate = isset($data['startDate'])? $data['startDate'] :'';
		$endDate = isset($data['endDate'])? $data['endDate'] :'';

		if (!empty($grn)) {
			$qb->andWhere($qb->expr()->like("e.grn", "'%$grn%'"  ));
		}
		if(!empty($business)){
			$qb->andWhere($qb->expr()->like("ms.name", "'%$business%'"  ));
		}
		if(!empty($brand)){
			$qb->andWhere($qb->expr()->like("ms.brandName", "'%$brand%'"  ));
		}
		if(!empty($mode)){
			$qb->andWhere($qb->expr()->like("ms.mode", "'%$mode%'"  ));
		}
		if(!empty($vendor)){
			$qb->join('e.vendor','v');
			$qb->andWhere($qb->expr()->like("v.companyName", "'%$vendor%'"  ));
		}
		if(!empty($vendorId)){
			$qb->join('e.vendor','v');
			$qb->andWhere("v.id = :vendorId")->setParameter('vendorId', $vendorId);
		}
		if (!empty($startDate) ) {
			$datetime = new \DateTime($data['startDate']);
			$start = $datetime->format('Y-m-d 00:00:00');
			$qb->andWhere("e.receiveDate >= :startDate");
			$qb->setParameter('startDate', $start);
		}

		if (!empty($endDate)) {
			$datetime = new \DateTime($data['endDate']);
			$end = $datetime->format('Y-m-d 23:59:59');
			$qb->andWhere("e.receiveDate <= :endDate");
			$qb->setParameter('endDate', $end);
		}
	}

    public function updatePurchaseTotalPrice(AccountPurchase $entity)
    {
        $em = $this->_em;
        $total = $this->createQueryBuilder('si')
            ->join('si.purchase','e')
            ->select('sum(si.subTotal) as total')
            ->where('e.id = :entity')
            ->setParameter('entity', $entity ->getId())
            ->getQuery()->getOneOrNullResult();

        if($total['total'] > 0){
            $subTotal = $total['total'];
            $entity->setTotalAmount($subTotal);
            $entity->setPurchaseAmount($subTotal);
        }else{
            $entity->setTotalAmount(0);
            $entity->setPurchaseAmount(0);
        }

        $em->persist($entity);
        $em->flush();
        return $entity;

    }


    public function insertPurchaseItems($invoice, $data)
    {

        $em = $this->_em;
        $entity = new ExpenditureItem();
        $entity->setPurchase($invoice);
        $entity->setParticular($data['particular']);
        $entity->setPrice($data['price']);
        $entity->setQuantity($data['quantity']);
        $entity->setSubTotal($data['quantity'] * $data['price']);
        $em->persist($entity);
        $em->flush();


    }


    public function getPurchaseItems(AccountPurchase $sales)
    {
        $entities = $sales->getExpenditureItems();
        $data = '';
        $i = 1;

        /* @var $entity ExpenditureItem */

        foreach ($entities as $entity) {

	        $data .= "<tr id='remove-{$entity->getId()}'>";
            $data .= "<td>{$i}</td>";
            $data .= "<td>{$entity->getParticular()}</td>";
            $data .= "<td>{$entity->getPrice()}</td>";
            $data .= "<td>{$entity->getQuantity()}</td>";
            $data .= "<td>{$entity->getSubTotal()}</td>";
            $data .= "<td><a id='{$entity->getId()}'  data-url='/accounting/expense-purchase/{$sales->getId()}/{$entity->getId()}/particular-delete' href='javascript:' class='btn red mini delete' ><i class='icon-trash'></i></a></td>";
            $data .= '</tr>';
            $i++;

        }
        return $data;
    }

    public function stockPurchaseItemPrice($percentage,$price)
    {
        $discount = (($price * $percentage )/100);
        $purchasePrice = ($price - $discount);
        return $purchasePrice;

    }
}
