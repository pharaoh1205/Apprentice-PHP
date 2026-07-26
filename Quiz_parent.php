<?php
namespace PHP;
//親のクラス：すべてのアイテムの「基本形」・Original
class Item
{
    public $price;
    public $name;

    public function __construct($name, $price)
    {
        $this->name = $name;
        $this->price = $price;
    }

    public function canBuy($vendingMachine){
        return true;
    }
    public function processPurchase($vendingMachine){

    }
}

