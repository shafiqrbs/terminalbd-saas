<?php

namespace Appstore\Bundle\RestaurantBundle\Repository;
use Appstore\Bundle\RestaurantBundle\Entity\RestaurantTemporary;
use Core\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;

/**
 * RestaurantTemporary
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */

class RestaurantTemporaryRepository extends EntityRepository
{

    public function getSubTotalAmount(User $user)
    {
        $config = $user->getGlobalOption()->getRestaurantConfig()->getId();
        $qb = $this->createQueryBuilder('e');
        $qb->select('SUM(e.subTotal) AS subTotal');
        $qb->addSelect('SUM(e.purchasePrice * e.quantity) AS purchasePrice');
        $qb->where('e.restaurantConfig = :config');
        $qb->setParameter('config', $config);
        $qb->andWhere("e.user =".$user->getId());
        $res = $qb->getQuery()->getOneOrNullResult();
        return $res;
    }

    public function generateVat(User $user,$total)
    {
        $config = $user->getGlobalOption()->getRestaurantConfig();
        $vat = 0;
        if($config->getVatEnable() == 1 and $config->getVatMode() == "excluding"){
            $vat = (($total * $config->getVatPercentage())/100);
        }
        return $vat;

    }

    public function insertInvoiceItems(User $user, $data)
    {
        $particular = $this->_em->getRepository('RestaurantBundle:Particular')->find($data['particularId']);
        $em = $this->_em;

        $entity = new RestaurantTemporary();
        $invoiceParticular = $this->findOneBy(array('user' => $user ,'particular' => $particular));
        if(!empty($invoiceParticular) and $data['process'] == 'update') {
            $entity = $invoiceParticular;
            $entity->setQuantity((float)$data['quantity']);
            $entity->setSubTotal($data['price'] * $entity->getQuantity());
        }elseif(!empty($invoiceParticular) and $data['process'] == "create") {
            $entity = $invoiceParticular;
            $entity->setQuantity($invoiceParticular->getQuantity() + 1);
            $entity->setSubTotal($data['price'] * $entity->getQuantity());
        }else{
            $entity->setQuantity(1);
            $entity->setSalesPrice($data['price']);
            $entity->setSubTotal($data['price']);
        }
        if($particular->getRestaurantConfig()->isProduction() == 1 and $particular->getService()->getSlug() == 'product'){
            $entity->setPurchasePrice($particular->getProductionElementAmount());
        }else{
            $entity->setPurchasePrice($particular->getPurchasePrice());
        }
        $entity->setUser($user);
        $entity->setRestaurantConfig($particular->getRestaurantConfig());
        $entity->setParticular($particular);
        $em->persist($entity);
        $em->flush();

    }

    public function getSalesSearchItems(User $user)
    {
        $entities = $user->getRestaurantTemps();
        $data = '';
        $i = 1;
        foreach ($entities as $entity) {
            $data .= '<tr id="remove-'. $entity->getId() . '">';
            $data .= '<td class=""><span class="badge badge-warning toggle badge-custom" id='. $entity->getId() .'" ><span>[+]</span></span></td>';
            $data .= '<td class="" >' . $i . '</td>';
            $data .= '<td class="" >' . $entity->getParticular()->getName() . '</td>';
            $data .= '<td class="" >' . $entity->getQuantity() . '</td>';
            $data .= '<td class="" >' . $entity->getSalesPrice() . '</td>';
            $data .= '<td class="" >' . $entity->getSubTotal() . '</td>';
            $data .= '<td class="" >
            <a id="'.$entity->getId().'" data-id="'.$entity->getId().'"  data-url="/restaurant/invoice-temporary/' . $entity->getId() . '/particular-delete" href="javascript:" class="btn red mini particularDelete" ><i class="icon-trash"></i></a>
            </td>';
            $data .= '</tr>';
            $i++;
        }
        return $data;
    }

    public function getSalesGridItems(User $user)
    {
        $entities = $user->getRestaurantTemps();
        $data = '';
        $i = 1;
        foreach ($entities as $entity) {
            $data .= "<tr id='remove-{$entity->getId()}'>";
            $data .= "<td>{$i}. {$entity->getParticular()->getName()}</td>";
            $data .= "<td>{$entity->getSalesPrice()}</td>";
            $data .= "<td><div class='input-append' style='margin-bottom: 0!important;'>
                                                    <span class='input-group-btn'>
  <a href='javascript:' data-action='/restaurant/temporary/{$entity->getParticular()->getId()}/product-update' class='btn yellow btn-number mini' data-type='minus' data-id='{$entity->getId()}'  data-text='{$entity->getId()}' data-title='{{ item.salesPrice }}'  data-field='quantity'>
                                                            <span class='fa fa-minus'></span>
                                                   </a>
                                                                     <input type='text' class='form-control inline-m-wrap updateProduct btn-qnt-particular' data-action='/restaurant/temporary/{$entity->getParticular()->getId()}/product-update' id='quantity-{$entity->getId()}' data-id='{$entity->getId()}' data-title='{$entity->getSalesPrice()}' name='quantity-{$entity->getId()}' value='{$entity->getQuantity()}' data-action='' min='1' max='1000'>
                                                      <a href='javascript:' data-action='/restaurant/temporary/{$entity->getParticular()->getId()}/product-update' class='btn green btn-number mini'  data-type='plus' data-id='{$entity->getId()}' data-title='{$entity->getSalesPrice()}'  data-text='{$entity->getId()}' data-field='quantity'>
                                                          <span class='fa fa-plus'></span>
                                                  </a>
                                                        </span>

                                            </div></td>";
            $data .= "<td>{$entity->getSubTotal()}</td>";
            $data .= '<td class="" >
            <a id="'.$entity->getId().'" data-id="'.$entity->getId().'" data-url="/restaurant/temporary/' . $entity->getId() . '/particular-delete" href="javascript:" class="btn red mini particularDelete" ><i class="icon-trash"></i></a>
            </td>';
            $data .= '</tr>';
            $i++;
        }
        return $data;
    }

    public function getSalesListItems(User $user)
    {
        $entities = $user->getRestaurantTemps();
        $data = '';
        $i = 1;
        foreach ($entities as $entity) {
            $data .= '<tr id="remove-'. $entity->getId() . '">';
            $data .= '<td class=""><span class="badge badge-warning toggle badge-custom" id='. $entity->getId() .'" ><span>[+]</span></span></td>';
            $data .= '<td class="" >' . $i . '</td>';
            $data .= '<td class="" >' . $entity->getParticular()->getName() . '</td>';
            $data .= '<td class="" >' . $entity->getQuantity() . '</td>';
            $data .= '<td class="" >' . $entity->getSalesPrice() . '</td>';
            $data .= '<td class="" >' . $entity->getSubTotal() . '</td>';
            $data .= '<td class="" >
            <a id="'.$entity->getId().'" data-id="'.$entity->getId().'" title="Are you sure went to delete ?" data-url="/restaurant/invoice-temporary/' . $entity->getId() . '/particular-delete" href="javascript:" class="btn red mini particularDelete" ><i class="icon-trash"></i></a>
            </td>';
            $data .= '</tr>';
            $i++;
        }
        return $data;
    }

    public function removeInitialParticular(User $user)
    {
        $em = $this->_em;
        $config = $user->getGlobalOption()->getRestaurantConfig()->getId();
        $entity = $em->createQuery('DELETE RestaurantBundle:RestaurantTemporary e WHERE e.restaurantConfig = '.$config.' and e.user = '.$user->getId());
        $entity->execute();
    }

}