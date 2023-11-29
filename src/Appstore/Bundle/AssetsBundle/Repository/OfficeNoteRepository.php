<?php

namespace Appstore\Bundle\AssetsBundle\Repository;
use Appstore\Bundle\AssetsBundle\Entity\Purchase;
use Appstore\Bundle\AssetsBundle\Entity\PurchaseItem;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\ToolBundle\Event\Glo;

/**
 * ItemTypeGroupingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OfficeNoteRepository extends EntityRepository
{

    /**
     * @param $qb
     * @param $data
     */

    protected function handleWithSearch($qb,$data)
    {
        if(!empty($data))
        {
            $item = isset($data['item'])? $data['item'] :'';
            $color = isset($data['color'])? $data['color'] :'';
            $size = isset($data['size'])? $data['size'] :'';
            $vendor = isset($data['vendor'])? $data['vendor'] :'';
            $brand = isset($data['brand'])? $data['brand'] :'';
            $category = isset($data['category'])? $data['category'] :'';
            $unit = isset($data['unit'])? $data['unit'] :'';
            $barcode = isset($data['barcode'])? $data['barcode'] :'';

            if (!empty($barcode)) {

                $qb->join('e.purchaseItem', 'p');
                $qb->andWhere("p.barcode = :barcode");
                $qb->setParameter('barcode', $barcode);
            }

            if (!empty($item)) {
                $qb->andWhere("m.name = :name");
                $qb->setParameter('name', $item);
            }
            if (!empty($color)) {
                $qb->join('item.color', 'c');
                $qb->andWhere("c.name = :color");
                $qb->setParameter('color', $color);
            }
            if (!empty($size)) {
                $qb->join('item.size', 's');
                $qb->andWhere("s.name = :size");
                $qb->setParameter('size', $size);
            }
            if (!empty($vendor)) {
                $qb->join('item.vendor', 'v');
                $qb->andWhere("v.companyName = :vendor");
                $qb->setParameter('vendor', $vendor);
            }

            if (!empty($brand)) {
                $qb->join('item.brand', 'b');
                $qb->andWhere("b.name = :brand");
                $qb->setParameter('brand', $brand);
            }

            if (!empty($category)) {
                $qb->join('m.category','cat');
                $qb->andWhere("cat.name = :category");
                $qb->setParameter('category', $category);
            }

            if (!empty($unit)) {
                $qb->join('m.productUnit','u');
                $qb->andWhere("b.name = :unit");
                $qb->setParameter('unit', $unit);
            }

        }

    }


    public function findWithSearch($config,$data)
    {

        $qb = $this->createQueryBuilder('e');
        $qb->where("e.config = :config")->setParameter('config', $config);
        $qb->orderBy('e.created','ASC');
        $qb->getQuery();
        return  $qb;

    }


}