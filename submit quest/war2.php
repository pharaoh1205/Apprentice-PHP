<?php


// 1. カードクラスの定義
class Card{
    public string $suit; //string(型) $suit(変数名)
    public string $rank;
    public int $strength;

    //３つのステータスをデフォで持てるようにする
    public function __construct($suit, $rank, $strength)//変数名だけでなく、string(型) $suit(変数名)で書く
    {
        $this->suit = $suit; 
        $this->rank = $rank; 
        $this->strength = $strength; 
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
        $deck[] = new Card($suit, $rank, $strength);
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


function playTurn(&$p1Hand, &$p2Hand, &$tableCards =[]){ //&つけるのはここだけでOK
    echo "戦争！" . PHP_EOL;

    while($p1Hand > 0 || $p2Hand > 0) {
        //山札から手札を一枚引く
        $p1Card = array_shift($p1Hand);
        $p2Card = array_shift($p2Hand);

        // 場にカードを置く
        $tableCard[] = $p1Card;
        $tableCard[] = $p2Card;

        echo "プレイヤー1のカードは{$p1Card->suit}の{$p1Card->rank}です。" . PHP_EOL;
        echo "プレイヤー2のカードは{$p2Card->suit}の{$p2Card->rank}です。" . PHP_EOL;


        //勝負
        if($p1Card->strength > $p2Card->strength){
            echo "プレイヤー1の勝利ですプレイヤー1はカードを2枚もらいました。" . PHP_EOL;

            // 1. 場札をプレイヤー1の手札（末尾）に追加する
            $p1Hand = array_merge($p1Hand, $tableCards);
            // 2. 場札を空にする
            $tableCards = [];
        }
        elseif($p2Card->strength > $p1Card->strength){
            echo "プレイヤー2の勝利ですプレイヤー2はカードを2枚もらいました。" . PHP_EOL;

            // 1. 場札をプレイヤー2の手札（末尾）に追加する
            $p2Hand = array_merge($p2Hand, $tableCards);
            // 2. 場札を空にする
            $tableCards = [];
        }
        else{
            echo "引き分けです" . PHP_EOL;
            playTurn($p1Hand, $p2Hand, $tableCard);
        }
    }

    
    if(count($p2Hand) === 0){
        echo "プレイヤー2の手札がなくなりました。 . PHP_EOL";
        echo "プレイヤー1の手札の枚数は52枚です。プレイヤー2の手札の枚数は0枚です。。" . PHP_EOL;
        echo "プレイヤー1が1位、プレイヤー2が2位です。 . PHP_EOL";
    }
    else{
        echo "プレイヤー1の手札がなくなりました。 . PHP_EOL";
        echo "プレイヤー2の手札の枚数は52枚です。プレイヤー1の手札の枚数は0枚です。。" . PHP_EOL;
        echo "プレイヤー2が1位、プレイヤー1が2位です。" . PHP_EOL;
    }
    
    

}

playTurn($p1Hand, $p2Hand, $tableCards);

echo "戦争を終了します。" . PHP_EOL;