<?php

namespace Appstore\Bundle\InventoryBundle\Service;


/**
 * RequestManager
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */

class PosItemManager {

    private $name;
    private $quantity;
    private $price;
    private $dollarSign;

    public function __construct($name = '', $quantity = '', $price = '', $dollarSign = false) {
        $this -> name = $name;
        $this -> quantity = $quantity;
        $this -> price = $price;
        $this -> dollarSign = $dollarSign;
    }

    public function __toString() {
        $rightCols = 10;
        $leftCols = 48;
        if($this -> dollarSign) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }
        $left = str_pad($this -> name, $leftCols) ;
        $center = $this -> quantity ;

        $sign = ($this -> dollarSign ? 'Tk.' : '');
        $right = str_pad($sign . $this -> price, $rightCols, ' ', STR_PAD_LEFT);
        return "$left$center$right\n";
    }
}
