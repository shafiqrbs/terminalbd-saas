<?php

namespace Appstore\Bundle\AssetsBundle\Repository;
use Appstore\Bundle\AssetsBundle\Entity\AssetsConfig;
use Appstore\Bundle\AssetsBundle\Entity\Depreciation;
use Appstore\Bundle\AssetsBundle\Entity\DepreciationModel;
use Appstore\Bundle\AssetsBundle\Entity\Product;
use Core\UserBundle\Entity\User;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;
use Symfony\Component\DependencyInjection\Container;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * ItemTypeGroupingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends EntityRepository
{


    public function getFeatureCategoryProduct($inventory,$data,$limit){

        $qb = $this->createQueryBuilder('product');
        $qb->leftJoin("product.masterItem",'masterItem');
        $qb->leftJoin('product.goodsItems','goodsitems');
        $qb->where("product.isWeb = 1");
        $qb->andWhere("product.status = 1");
        $qb->andWhere("product.inventoryConfig = :inventory");
        $qb->setParameter('inventory', $inventory);

        if (!empty($data['brand'])) {
            $qb->andWhere("product.brand IN(:brand)");
            $qb->setParameter('brand',$data['brand']);
        }
        if (!empty($data['promotion'])) {
            $qb->andWhere("product.promotion IN(:promotion)");
            $qb->setParameter('promotion',$data['promotion']);
        }

        if (!empty($data['tag'])) {
            $qb->andWhere("product.tag IN(:tag)");
            $qb->setParameter('tag',$data['tag']);
        }

        if (!empty($data['discount'])) {
            $qb->andWhere("product.discount IN(:discount)");
            $qb->setParameter('discount',$data['discount']);
        }

        if (!empty($data['category'])) {

            $qb
                ->join('masterItem.category', 'category')
                ->andWhere(
                    $qb->expr()->orX(
                        $qb->expr()->like('category.path', "'". intval($data['category']) . "/%'"),
                        $qb->expr()->like('category.path', "'%/" . intval($data['category']) . "/%'")
                    )
                );
        }
        $qb->orderBy('product.updated', 'DESC');
        if($limit > 0 ) {
            $qb->setMaxResults($limit);
        }
        $res = $qb->getQuery();
        return  $res;
    }


    public function checkDuplicateSKU(GlobalOption $option,$data)
    {
        $masterItem = $data['item']['name'];
        $vendor     = isset($data['item']['vendor']) ? $data['item']['vendor'] :'NULL';
        $itemBrand  = isset($data['item']['brand']) ? $data['item']['brand']:'NULL';

        $qb = $this->createQueryBuilder('e');
        $qb->select('COUNT(e.id) countid');
        $qb->where("e.globalOption = :globalOption");
        $qb->setParameter('globalOption', $option);
        $count = $qb->getQuery()->getOneOrNullResult();
        $result = $count['countid'];
        return $result;

    }


    public function findWithSearch($config,$data = array())
    {

        $item = isset($data['item'])? $data['item'] :'';
        $branch = isset($data['branch'])? $data['branch'] :'';
        $category = isset($data['category'])? $data['category'] :'';
        $parent = isset($data['parent'])? $data['parent'] :'';
	    $depreciation = isset($data['depreciation'])? $data['depreciation'] :'';
	    $effectedDate = isset($data['effectedDate'])? $data['effectedDate'] :'';

        $qb = $this->createQueryBuilder('item');
        $qb->where("item.status IS NOT NULL");
        $qb->andWhere("item.config = :config")->setParameter('config', $config);

        if (!empty($effectedDate)) {
            $end = date('Y-m-01 23:59:59',strtotime($effectedDate));
            $qb->andWhere("item.depreciationEffectedDate <= :date")->setParameter('date',$end);
        }
        if($item){
            $qb->join('item.item', 'i');
            $qb->andWhere($qb->expr()->like("i.name", "'$item%'"));
        }
        if (!empty($category)) {
            $qb->join('item.category', 'c');
            $qb->andWhere($qb->expr()->like("c.name", "'%$category%'"  ));
        }

        if (!empty($parent)) {
            $qb->join('item.parentCategory', 'pc');
            $qb->andWhere($qb->expr()->like("pc.name", "'%$parent%'"  ));
        }

        if (!empty($depreciation)) {
	        $qb->join('item.depreciation', 'd');
            $qb->andWhere("d.id = :depreciation");
            $qb->setParameter('depreciation', $depreciation);
        }

        if (!empty($vendor)) {
            $qb->join('item.vendor', 'v');
            $qb->andWhere("v.companyName = :vendor");
            $qb->setParameter('vendor', $vendor);
        }

        if (!empty($branch)) {

            $qb->join('item.branch', 'b');
            $qb->andWhere("b.name = :branch");
            $qb->setParameter('branch', $branch);

        }
        $qb->orderBy('item.updated','DESC');
        return  $qb;

    }

    public function updateDepreciationModelProduct(DepreciationModel $entity,$data )
    {
        $item = isset($data['item'])? $data['item'] :'';
        $category = isset($data['category'])? $data['category'] :'';

        $depreciation = $this->_em->getRepository('AssetsBundle:DepreciationModel')->findOneBy(array('config'=>$entity->getConfig(),'isDefault'=>1));

        $qb = $this->_em->createQueryBuilder();
        $q = $qb->update('AssetsBundle:Product','e');
        $q->set('e.depreciation', $entity->getId());
        $q->where("e.config = :config")->setParameter('config', $entity->getConfig()->getId());
        $q->andWhere("e.depreciation = :depreciation")->setParameter('depreciation',$depreciation->getId());
        if($item){ $q->andWhere('e.item = ?1')->setParameter(1, $item);}
        if($category) { $q->andWhere('e.category = ?2')->setParameter(2, $category);}
        $p = $q->getQuery()->execute();
    }

    public function depreciationGenerate($data)
	{

		$item = isset($data['item'])? $data['item'] :'';
		$category = isset($data['category'])? $data['category'] :'';

		$qb = $this->createQueryBuilder('item');
		$qb->where("item.status IS NOT NULL");
		if (!empty($item)) {
			$qb->join('item.item', 'm');
			$qb->andWhere("m.id = :name");
			$qb->setParameter('name', $item);
		}
		if (!empty($category)) {
			$qb->join('item.category', 'c');
			$qb->andWhere("c.id = :category");
			$qb->setParameter('category', $category);
		}
		$qb->orderBy('item.updated','DESC');
		$qb->getQuery();
		return  $qb;

	}

    public function getInventoryExcel($inventory,$data){

        $item = isset($data['item'])? $data['item'] :'';
        $gpSku = isset($data['gpSku'])? $data['gpSku'] :'';
        $category = isset($data['category'])? $data['category'] :'';
        $brand = isset($data['brand'])? $data['brand'] :'';

        $qb = $this->createQueryBuilder('item');
        $qb->join('item.masterItem', 'm');
        $qb->where("item.inventoryConfig = :inventory");
        $qb->setParameter('inventory', $inventory);

        if (!empty($item)) {

            $qb->andWhere("m.name = :name");
            $qb->setParameter('name', $item);
        }
        if (!empty($gpSku)) {
            $qb->andWhere($qb->expr()->like("item.gpSku", "'%$gpSku%'"  ));
        }
        if (!empty($category)) {
            $qb->join('m.category', 'c');
            $qb->andWhere("c.name = :category");
            $qb->setParameter('category', $category);
        }
        if (!empty($brand)) {
            $qb->join('m.brand', 'b');
            $qb->andWhere("b.name = :brand");
            $qb->setParameter('brand', $brand);
        }
        $qb->orderBy('item.gpSku','ASC');
        $result = $qb->getQuery()->getResult();
        return  $result;
    }

    public function getLastId($inventory)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('count(item.id)');
        $qb->from('InventoryBundle:Item','item');
        $qb->where("item.inventoryConfig = :inventory");
        $qb->setParameter('inventory', $inventory);
        $count = $qb->getQuery()->getSingleScalarResult();
        if($count > 0 ){
         return $count+1;
        }else{
         return 1;
        }

    }

    public function searchAutoComplete($item, AssetsConfig $inventory)
    {

        $search = strtolower($item);
        $query = $this->createQueryBuilder('i');
        $query->join('i.inventoryConfig', 'ic');
        $query->leftJoin('i.stockItems', 'stockItem');
        $query->select('i.id as id');
        $query->addSelect('i.name as name');
        $query->addSelect('i.skuSlug as text');
        $query->addSelect('i.sku as sku');
        $query->addSelect('SUM(stockItem.quantity) as remainingQuantity');
        $query->where($query->expr()->like("i.skuSlug", "'%$search%'"  ));
        $query->andWhere("i.purchaseQuantity > 0 ");
        $query->andWhere("ic.id = :inventory");
        $query->setParameter('inventory', $inventory->getId());
        $query->groupBy('i.id');
        $query->orderBy('i.name', 'ASC');
        $query->setMaxResults( '30' );
        return $query->getQuery()->getResult();

    }

     public function searchAutoCompleteAllItem($item, AssetsConfig $inventory)
    {

        $search = strtolower($item);
        $query = $this->createQueryBuilder('i');
        $query->join('i.inventoryConfig', 'ic');
        $query->select('i.id as id');
        $query->addSelect('i.name as name');
        $query->addSelect('i.skuSlug as text');
        $query->addSelect('i.sku as sku');
        $query->where($query->expr()->like("i.skuSlug", "'%$search%'"  ));
        $query->andWhere("ic.id = :inventory");
        $query->setParameter('inventory', $inventory->getId());
        $query->groupBy('i.id');
        $query->orderBy('i.name', 'ASC');
        $query->setMaxResults( '30' );
        return $query->getQuery()->getResult();

    }

    public function insertReceiveItem(Receive $sales)
    {
        $em = $this->_em;

        /** @var  $item ReceiveItem */

         foreach($sales->getReceiveItems() as $item ){

            if($item->getItem()->getCategory()->getCategoryType() == 'Assets'){
                $this->insertPurchaseItemToAssetsProduct($item);
            }
        }
    }


    public function insertPurchaseItemToAssetsProduct(ReceiveItem $receiveItem)
    {
        $em = $this->_em;
        $item = $receiveItem->getPurchaseItem();
        $status = $em->getRepository('AssetsBundle:Particular')->findOneBy(array('slug'=>'ready-to-deploy'));
        $depreciation = $em->getRepository('AssetsBundle:DepreciationModel')->findOneBy(array('config' => $item->getConfig(),'isDefault' => 1));

        if($receiveItem->getExternalSerial()){

            $comma_separated = explode(",", $receiveItem->getExternalSerial());
            foreach ($comma_separated as $serialNo):

                $product = new Product();
                $product->setConfig($item->getConfig());
                $product->setPurchaseItem($item);
                $product->setReceiveItem($receiveItem);
                $product->setSerialNo($serialNo);
                $product->setName($item->getName());
                if($item->getAssetsPurchase()){
                    $product->setVendor($item->getAssetsPurchase()->getVendor());
                }
                if($item->getVendor()){
                    $product->setVendor($receiveItem->getReceive()->getVendor());
                }
                $product->setItem($item->getItem());
                $product->setCategory($item->getItem()->getCategory());
                $product->setParentCategory($product->getCategory()->getParent());
                $product->setPurchasePrice($item->getPurchasePrice());
                $product->setBookValue($item->getPurchasePrice());
                $product->setAssuranceType($item->getAssuranceType());
                $product->setExpiredDate($item->getExpiredDate());
                $product->setDepreciationStatus($status);
                $product->setDepreciation($depreciation);
                $product->setDepreciationEffectedDate($this->effectedDateSetup($depreciation->getDepreciation()));
                $em->persist($product);
                $em->flush($product);
                $this->_em->getRepository('AssetsBundle:ProductLedger')->insertProductLedger($product);

            endforeach;
        }
    }

    private function effectedDateSetup(Depreciation $depreciation)
    {
        $em = $this->_em;
        $effected = $depreciation->getEffected();
        $cur = date('d-m-Y');
        $effectiveDate = date('Y-m-t', strtotime("+{$effected}", strtotime($cur)));
        return $date = new  \DateTime($effectiveDate);

    }


}