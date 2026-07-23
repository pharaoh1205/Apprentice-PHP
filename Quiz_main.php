<?php

namespace PHP;

$cider = new Drink('cider', 100);
$cola = new Drink('cola', 150);

$vendingMachine = new VendingMachine('サントリー');
// echo $vendingMachine->pressManufacturerName();

$vendingMachine->depositCoin(100);
$vendingMachine->depositCoin(100);
echo $vendingMachine->pressButton($cider) . "\n";

// サンプル呼び出しのように、'hot' や 'ice' だけを渡してインスタンスを作る
$hotCupCoffee = new CupCoffee('hot');
$iceCupCoffee = new CupCoffee('ice');

//カップを1つ補給する
$vendingMachine->addCup(1);

// 再度カップコーヒーを購入（カップがあるので買える！100円消費 / 残金0円）
echo $vendingMachine->pressButton($hotCupCoffee) . "\n"; // 出力: hot cup coffee

$potetoChips = new Snack("potetoChips", 150);
$vendingMachine->depositCoin(150);
echo $vendingMachine->pressButton($potetoChips) . "\n";
