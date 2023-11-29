<?php

namespace Appstore\Bundle\AssetsBundle\Repository;
use Appstore\Bundle\AccountingBundle\Entity\AccountBank;
use Appstore\Bundle\AccountingBundle\Entity\AccountJournal;
use Appstore\Bundle\AccountingBundle\Entity\AccountPurchase;
use Appstore\Bundle\InventoryBundle\Entity\Sales;
use Appstore\Bundle\InventoryBundle\Entity\SalesReturn;
use Doctrine\ORM\EntityRepository;
use Proxies\__CG__\Appstore\Bundle\DomainUserBundle\Entity\PaymentSalary;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;

/**
 * AccountingConfigRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AssetsConfigRepository extends EntityRepository
{

    public function reset(GlobalOption $option){

        $em = $this->_em;
        $option = $option->getAssetsConfig()->getId();

        $transaction = $em->createQuery('DELETE AssetsBundle:Disposal e WHERE e.config = '.$option);
        $transaction->execute();

        $transaction = $em->createQuery('DELETE AssetsBundle:Product e WHERE e.config = '.$option);
        $transaction->execute();

        $transaction = $em->createQuery('DELETE AssetsBundle:Product e WHERE e.config = '.$option);
        $transaction->execute();

        $transaction = $em->createQuery('DELETE AssetsBundle:StockItem e WHERE e.config = '.$option);
        $transaction->execute();

        $transaction = $em->createQuery('DELETE AssetsBundle:Sales e WHERE e.config = '.$option);
        $transaction->execute();

    }
}