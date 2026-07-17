<?php

class item{
    public $price;
    public $drinkname;

    public function __construct($drinkname){
        $this->drinkname = $drinkname;

        if($this->drinkname === "cola"){
        $this->price = 150;
        }
        elseif($this->drinkname === "cider"){
        $this->price = 100;
        }
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
            return $item->drinkname; 
        }
        return ''; // お金が足りない場合は空文字を返す
    }

	
}


$cider = new item("cider");
$cola = new item("cola");

$vendingMachine = new VendingMachine('サントリー');
// echo $vendingMachine->pressManufacturerName();

$vendingMachine->depositCoin(100);
echo $vendingMachine->pressButton($cider);

$vendingMachine->depositCoin(100);
echo $vendingMachine->pressButton($cola);

?>