<?php

//親のクラス：すべてのアイテムの「基本形」・Original
class item{
    public $price;
    public $name;

    public function __construct($name, $price){
        $this->name = $name;
        $this->price = $price;
    }
}

//子クラスその1：普通のドリンク
class drink extends item{

}


//子クラスその2：カップコーヒー（アイス・ホット）
class cupcoffee extends item{
    public function __construct($type){    // $type には 'ice' または 'hot' が入ってくる想定
        $fullname = $type. " cup coffee"; //cupの前,半角スペースいれておく
        $fixedprice = 100;

        parent::__construct($fullname,$fixedprice);
        //親クラスのコンストラクタである public function __construct($name, $price) に対して
        //•	第1引数である $name の席に、自分が用意した $fullName を
        //•	第2引数である $price の席に、自分が用意した $fixedPrice を
        //それぞれ「引き渡して紐づけてね（代入してね）」

    }
}

//子クラスその33：snack
class snack extends item{
    public $name = "potetoChips";
    public $price = 150;
}

class VendingMachine{
    public $name;
    //public $money; ※自販機に投入したお金 一時的な値で自販機が記憶すべきものではないのでここには書かない
    public $depo = 0; //自販機に入っている金額(初期値は０)
    public $cups = 0;
    public $maxcups = 100;

    public function addCup($count){
        $this->cups += $count;
        if($this->cups > $this->maxcups){
            $this->cups = $this->maxcups;
        }

    }


    public function __construct($name){
        $this->name = $name;
    }
    private function pressManufacturerName(){
		return $this->name;
    }
    public function depositCoin($money){
        if($money === 100){
            $this->depo = $this->depo + 100;
        }
        //else{} 100円以外の時は「何もしない（金額を増やさない）」のが仕様なので、この else のブロック自体なくてOK
    }
    public function pressButton($item){
        // ❶ まずはお金が足りているかチェック
        if($this->depo < $item->price){
            return ''; // お金が足りない場合は空文字を返す
        } 
        // ❷ 「カップコーヒー」購入の場合 ※returnされないとそのまま❸の処理に進むので購入した場合は金額も減る
        if($item instanceOf cupcoffee){//カップのストックある場合１つ使う。ない場合は購入不可
            if($this->cups > 0){
                $this->cups -= 1;
            }
            else{
                return '';
            }

        }
        // ❸ 価格処理
        $this->depo = $this->depo - $item->price;
        return $item->name; 
    }

	
}


$cider = new drink('cider', 100);
$cola = new drink('cola', 150);

$vendingMachine = new VendingMachine('サントリー');
// echo $vendingMachine->pressManufacturerName();

$vendingMachine->depositCoin(100);
$vendingMachine->depositCoin(100);
echo $vendingMachine->pressButton($cider). "\n";

// サンプル呼び出しのように、'hot' や 'ice' だけを渡してインスタンスを作る
$hotCupCoffee = new CupCoffee('hot');
$iceCupCoffee = new CupCoffee('ice');

//カップを1つ補給する
$vendingMachine->addCup(1);

// 再度カップコーヒーを購入（カップがあるので買える！100円消費 / 残金0円）
echo $vendingMachine->pressButton($hotCupCoffee) . "\n"; // 出力: hot cup coffee

$potetoChips = new snack("potetoChips", 150);
$vendingMachine->depositCoin(150);
echo $vendingMachine->pressButton($potetoChips). "\n";



?>