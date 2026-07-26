<?php
namespace PHP;

class VendingMachine
{
    public $name;
    //public $money; ※自販機に投入したお金 一時的な値で自販機が記憶すべきものではないのでここには書かない
    public $depo = 0; //自販機に入っている金額(初期値は０)
    public $cups = 0;
    public $maxcups = 100;

    public function addCup($count)
    {
        $this->cups += $count;
        if ($this->cups > $this->maxcups) {
            $this->cups = $this->maxcups;
        }
    }


    public function __construct($name)
    {
        $this->name = $name;
    }
    private function pressManufacturerName()
    {
        return $this->name;
    }
    public function depositCoin($money)
    {
        if ($money === 100) {
            $this->depo = $this->depo + 100;
        }
        //else{} 100円以外の時は「何もしない（金額を増やさない）」のが仕様なので、この else のブロック自体なくてOK
    }
    public function pressButton($item)
    {
        // ❶ まずはお金が足りているかチェック
        if ($this->depo < $item->price) {
            return ''; // お金が足りない場合は空文字を返す
        }
        
        if(!$item->canBuy($this)){
            return '';
        }

        $item->processPurchase($this);
        

        // ❸ 価格処理
        $this->depo = $this->depo - $item->price;
        return $item->name;
    }
}