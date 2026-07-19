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
    #[Override]
    public function __construct($type){ // $type には 'ice' または 'hot' が入ってくる想定
        $fullname = $type. " cup coffee"; //cupの前,半角スペースいれておく
        $fixedprice = 100;

        parent::__construct($fullname,$fixedprice);
        //親クラスのコンストラクタである public function __construct($name, $price) に対して
        //•	第1引数である $name の席に、自分が用意した $fullName を
        //•	第2引数である $price の席に、自分が用意した $fixedPrice を
        //それぞれ「引き渡して紐づけてね（代入してね）」

    }
}

class VendingMachine{
    public $name;
    //public $money; ※自販機に投入したお金 一時的な値で自販機が記憶すべきものではないのでここには書かない
    public $depo = 0; //自販機に入っている金額(初期値は０)


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
        if($this->depo >= $item->price){
            $this->depo = $this->depo - $item->price;
            return $item->name; 
        }
        return ''; // お金が足りない場合は空文字を返す
    }

	
}


$cider = new Drink('cider', 100);
$cola = new Drink('cola', 150);

$vendingMachine = new VendingMachine('サントリー');
// echo $vendingMachine->pressManufacturerName();

$vendingMachine->depositCoin(100);
echo $vendingMachine->pressButton($cider);

// サンプル呼び出しのように、'hot' や 'ice' だけを渡してインスタンスを作る
$hotCupCoffee = new CupCoffee('hot');
$iceCupCoffee = new CupCoffee('ice');

?>