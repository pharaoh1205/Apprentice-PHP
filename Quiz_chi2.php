<?php
namespace PHP;

use Override;

//子クラスその2：カップコーヒー（アイス・ホット）
class Cupcoffee extends item
{
    public function __construct($type)
    {
        // $type には 'ice' または 'hot' が入ってくる想定
        $fullname = $type . " cup coffee"; //cupの前,半角スペースいれておく
        $fixedprice = 100;

        parent::__construct($fullname, $fixedprice);
        //親クラスのコンストラクタである public function __construct($name, $price) に対して
        //• 第1引数である $name の席に、自分が用意した $fullName を
        //• 第2引数である $price の席に、自分が用意した $fixedPrice を
        //それぞれ「引き渡して紐づけてね（代入してね）」
    }
    public function canBuy($vendingMachine)
    {
        return $vendingMachine->cups > 0;
    
    }
    
    #[Override]
    public function processPurchase($vendingMachine)
    {
       $vendingMachine->cups -=1;//親のprocessPurchaseでreturn使ってないのでreturnつけない
        
    }
}