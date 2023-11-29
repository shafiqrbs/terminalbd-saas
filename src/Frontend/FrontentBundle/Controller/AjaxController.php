<?php

namespace Frontend\FrontentBundle\Controller;


use Appstore\Bundle\EcommerceBundle\Entity\Item;
use Appstore\Bundle\EcommerceBundle\Entity\ItemSub;
use Frontend\FrontentBundle\Service\Cart;
use Frontend\FrontentBundle\Service\MobileDetect;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;



class AjaxController extends Controller
{

    private function returnCartSummaryAjaxData($cart)
    {
        $amount = number_format($cart->total(), 2, '.', '');
        $data = array(
            'cartTotal' =>  (string)$amount,
            'totalItems' => count($cart->contents()),
            'totalQuantity' => (string)$cart->total_items(),
            'cartResult' => count($cart->contents())." | ৳ ".(string)$amount,
            'process' => "success"
        );
        $array = json_encode($data);
        return $array;
    }


    public function inlineSubProductAction($subdomain ,Item $product)
    {

        $subId = $_REQUEST['subItem'];
        $em = $this->getDoctrine()->getManager();
        $globalOption = $em->getRepository('SettingToolBundle:GlobalOption')->findOneBy(array('subDomain'=>$subdomain));
        /* @var ItemSub $subItem */
        $subItem = $em->getRepository('EcommerceBundle:ItemSub')->findOneBy(array('item'=> $product,'id'=> $subId));
        if(!empty($globalOption)){

            $themeName = $globalOption->getSiteSetting()->getTheme()->getFolderName();
            /* Device Detection code desktop or mobile */

            $detect = new MobileDetect();
            if($detect->isMobile() || $detect->isTablet() ) {
                $theme = 'Template/Mobile/'.$themeName;
            }else{
                $theme = 'Template/Desktop/'.$themeName;
            }
            $html =  $this->renderView('FrontendBundle:'.$theme.':inlineSubProduct.html.twig',
                array(
                    'globalOption'    => $globalOption,
                    'product'    => $product,
                    'subItem'    => $subItem
                )
            );

            $unit       = empty($product->getProductUnit()) ? '' : $product->getProductUnit()->getName();
            $size       = empty($subItem->getSize()) ? '' : $subItem->getSize()->getName();
            $sizeUnit   = empty($subItem->getProductUnit()) ? '' : $subItem->getProductUnit()->getName();

            if($subItem->getDiscountPrice()){
                $price = "<strike>{$subItem->getSalesPrice()}</strike> <strong class='list-price' >{$subItem->getDiscountPrice()}</strong>/{$unit}";
            }else{
                $price = "<strong class='list-price'>{$subItem->getSalesPrice()}</strong>/{$unit}";
            }

            $array = (json_encode(array('subItem' => $html ,'salesPrice' => $price,'size' => $size,'sizeUnit' => $sizeUnit )));
            return new Response($array);

        }
    }

    public function productInlineCartAction(Request $request , $subdomain , Item $product)
    {

        $data = $_REQUEST;

        $cart = new Cart($request->getSession());
        $em = $this->getDoctrine()->getManager();
        $globalOption = $em->getRepository('SettingToolBundle:GlobalOption')->findOneBy(array('subDomain' => $subdomain));

        $productImg = !empty($data['productImg']) ? $data['productImg'] : '';
        $quantity = !empty($data['quantity']) ? $data['quantity'] : 0;
        $sub = !empty($data['subItem']) ? $data['subItem'] : 0;
        $color = !empty($data['color']) ? $data['color'] : 0;

        if ($color > 0) {
            $colorName = $this->getDoctrine()->getRepository('SettingToolBundle:ProductColor')->find($color)->getName();
        } else {
            $colorName = '';
        }

        if ($sub > 0) {
            $subitem = $this->getDoctrine()->getRepository('EcommerceBundle:ItemSub')->find($sub);
        } else {
            $subitem = '';
        }

        /** @var  $globalOption GlobalOption */

        if (!empty($subitem)) {

            $salesPrice = $subitem->getDiscountPrice() == null ?  $subitem->getSalesPrice() : $subitem->getDiscountPrice();
            $unit = empty($subitem->getProductUnit()) ? '' : '-'.$subitem->getProductUnit()->getName();
            $size = $subitem->getSize()->getName();
            $sizeUnit = $size.$unit;
            $insert = array(
                'id' => $subitem->getId(),
                'name' => $product->getWebName(),
                'brand' => !empty($product->getBrand()) ? $product->getBrand()->getName() : '',
                'category' => !empty($product->getCategory()) ? $product->getCategory()->getName() : '',
                'productUnit' => $sizeUnit,
                'color' => $colorName,
                'colorId' => $color,
                'price' => $salesPrice,
                'quantity' => $quantity,
                'productImg' => $productImg);
            $cart->insert($insert);

        }else{

            $salesPrice = $product->getDiscountPrice() == null ?  $product->getSalesPrice() : $product->getDiscountPrice();
            $insert = array(
                'id' => $product->getId(),
                'name' => $product->getWebName(),
                'brand' => !empty($product->getBrand()) ? $product->getBrand()->getName() : '',
                'category' => !empty($product->getCategory()) ? $product->getCategory()->getName() : '',
                'size' => !empty($product->getSize()) ? $product->getSize()->getName() : '',
                'sizeUnit' => !empty($product->getProductUnit()) ? $product->getProductUnit()->getName() : '',
                'productUnit' => !empty($product->getProductUnit()) ? $product->getProductUnit()->getName() : '',
                'price' => $salesPrice,
                'quantity' => $quantity,
                'productImg' => $productImg
            );
            $cart->insert($insert);
        }
        $array = $this->returnCartSummaryAjaxData($cart);
        return new Response($array);

    }

    public function productAddSingleCartAction(Request $request , $subdomain , Item $product)
    {

        $cart = new Cart($request->getSession());
        $quantity = 1;
        $salesPrice = $product->getDiscountPrice() == null ?  $product->getSalesPrice() : $product->getDiscountPrice();
        $size = !empty($product->getSize()) ? "-".$product->getSize()->getName() : '';
        $sizeUnit = !empty($product->getSizeUnit()) ? $product->getSizeUnit()->getName() : '';
        $productUnit = (!empty($product->getProductUnit())) ? $product->getProductUnit()->getName() : '';
        $productName = $product->getName()." ".$size.$sizeUnit;
        $insert = array(
            'id' => $product->getId(),
            'name' => $productName,
            'brand' => empty($product->getBrand()) ? '' : $product->getBrand()->getName(),
            'category' => empty($product->getCategory()) ? '' : $product->getCategory()->getName(),
            'price' => $salesPrice,
            'quantity' => $quantity,
            'productUnit' => $productUnit,
            'productImg' => ''
        );
        $cart->insert($insert);
        $array = $this->returnCartSummaryAjaxData($cart);
        return new Response($array);

    }

    public function productAddMedicineCartAction(Request $request , $subdomain , $product)
    {

        $cart = new Cart($request->getSession());
        $em = $this->getDoctrine()->getManager();
        $ecommerceItem = $this->getDoctrine()->getRepository('EcommerceBundle:Item')->find($product);
        $stockItem = $this->getDoctrine()->getRepository('MedicineBundle:MedicineStock')->find($product);
        $productId = '';
        $productName = '';
        $productUnit = '';
        $brand = '';
        $category = '';
        $salesPrice = 0;
        if($ecommerceItem){
            $productId = $ecommerceItem->getId();
            $productName = $ecommerceItem->getWebName();
            $productUnit = (!empty($ecommerceItem->getProductUnit())) ? $ecommerceItem->getProductUnit()->getName() : '';
            $brand = !empty($ecommerceItem->getBrand()) ? $ecommerceItem->getBrand()->getName() : '';
            $category = !empty($ecommerceItem->getCategory()) ? $ecommerceItem->getCategory()->getName() : '';
            $salesPrice = $ecommerceItem->getDiscountPrice() == null ?  $ecommerceItem->getSalesPrice() : $ecommerceItem->getDiscountPrice();
        }elseif($stockItem){
            $productId = $stockItem->getId();
            $productName = $stockItem->getName();
            $productUnit = (!empty($stockItem->getUnit())) ? $stockItem->getUnit()->getName() : '';
            $brand = !empty($stockItem->getBrandName()) ? $stockItem->getBrandName() : '';
            $salesPrice = $stockItem->getSalesPrice();
        }
        $data = $_REQUEST;
        $quantity =  isset($data['quantity']) ? $data['quantity'] :'';

        /** @var GlobalOption $globalOption */

        $data = array(
            'id' => $productId,
            'name' => $productName,
            'brand' => $brand,
            'category' => $category,
            'price' => $salesPrice,
            'quantity' => $quantity,
            'productUnit' => $productUnit,
            'productImg' => ''
        );

        $cart->insert($data);
        $array = $this->returnCartSummaryAjaxData($cart);
        return new Response($array);

    }

    public function productModalAction(Request $request ,$subdomain,Item $item)
    {
        $cart = new Cart($request->getSession());
        $em = $this->getDoctrine()->getManager();
        $masterItem = $em->getRepository('EcommerceBundle:ItemSub')->findOneBy(array('item'=>$item->getId(),'masterItem'=>1));
        $globalOption = $em->getRepository('SettingToolBundle:GlobalOption')->findOneBy(array('subDomain'=>$subdomain));
        if(empty($masterItem)){
            $subItem ='';
        }else{
            $subItem = isset($_REQUEST['subItem']) ? $_REQUEST['subItem'] : $masterItem->getId() ;
            $subItem = $em->getRepository('EcommerceBundle:ItemSub')->findOneBy(array('item' => $item,'id' => $subItem));
        }
        if(!empty($globalOption)){

            $themeName = $globalOption->getSiteSetting()->getTheme()->getFolderName();
            /* Device Detection code desktop or mobile */
            $next = $this->getDoctrine()->getRepository('EcommerceBundle:Item')->frontendProductNext($item);
            $previous = $this->getDoctrine()->getRepository('EcommerceBundle:Item')->frontendProductPrev($item);

            $detect = new MobileDetect();
            if($detect->isMobile() || $detect->isTablet() ) {
                $theme = 'Template/Mobile/'.$themeName;
            }else{
                $theme = 'Template/Desktop/'.$themeName;
            }
            return $this->render('FrontendBundle:'.$theme.':productModal.html.twig',
                array(
                    'globalOption'      => $globalOption,
                    'product'           => $item,
                    'next'              => $next,
                    'previous'          => $previous,
                    'cart'              => $cart,
                    'subItem'           => $subItem
                )
            );
        }

    }
}
