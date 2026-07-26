<?php


// 1. カードクラスの定義
class Card{
    public string $suit; //string(型) $suit(変数名)
    public string $rank;
    public int $strength;

    //３つのステータスをデフォで持てるようにする
    public function __construct($suit, $rank, $strength)//変数名だけでなく、string(型) $suit(変数名)で書く
    {
        $This->suit = $suit; 
        $This->rank = $rank; 
        $This->strength = $strength; 
    }
}

//カードの作成
$suits = ["ハート", "ダイヤ", "クローバー", "スペード"];
$ranks = ["2" => 2, "3" => 3, "4" => 4, "5" => 5, "6" => 6, "7" => 7, "8" => 8, "9" => 9, "10" => 10, "J" => 11, 
"Q" => 12, "K" => 13, "A" => 14 ];

//デッキにカード格納
$deck = [];
foreach($suits as $suit){
    foreach($ranks as $rank => $strength){
        $card = new Card($suit, $rank, $strength);
    }
}
//カードシャッフル
shuffle($deck);

//プレイヤーにカード配る
$player1Hand = array_splice($deck, 0, 26);
$player2Hand = array_splice($deck, 0, 26);


//進行処理
echo "戦争を始めます" . PHP_EOL;
echo "カードが配られました" . PHP_EOL;


function playTurn(&$p1Hand, &$p2Hand, &$tablecard =[]){ //&つけるのはここだけでOK
    echo "戦争！" . PHP_EOL;

    //山札から手札を一枚引く
    $p1Card = array_shift($p1Hand);
    $p2Card = array_shift($p2Hand);

    // 場にカードを置く
    $tablecard[] = p1Card;
    $tablecard[] = p2Card;

    echo "プレイヤー1のカードは{$p1Card->suit}の{$p1Card->rank}です。" . PHP_EOL;
    echo "プレイヤー2のカードは{$p2Card->suit}の{$p2Card->rank}です。" . PHP_EOL;


    //勝負
    if($p1Card->strength > $p2Card->strength){
        echo "プレイヤー1の勝利です" . PHP_EOL;
    }
    elseif($p2Card->strength > $p1Card->strength){
        echo "プレイヤー2の勝利です" . PHP_EOL;
    }
    else{
        echo "引き分けです . PHP_EOL";
        playTurn($p1Hand, $p2Hand, $tablecard);
    }

}

playTurn($p1Hand, $p2Hand, $tablecard);

echo "戦争を終了します。" . PHP_EOL;