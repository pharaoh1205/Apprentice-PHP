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


function playGame(&$p1Hand, &$p2Hand) {
    $tableCards = []; // 場札の箱 / $tableCards = []; という場札リセットあるので参照渡し不要

    // 【1】どちらかの手札がなくなるまで、ずーっと勝負を繰り返す（whileループ）
    while (count($p1Hand) > 0 && count($p2Hand) > 0) {
        echo "戦争！" . PHP_EOL;

        //手札からカードを１枚引く
        $p1Card = array_shift($p1Hand);
        $p2Card = array_shift($p2Hand);

		//引いたカードを場に出す
        $tableCards[] = $p1Card;
        $tableCards[] = $p2Card;

        echo "プレイヤー1のカードは{$p1Card->suit}の{$p1Card->rank}です。" . PHP_EOL;
        echo "プレイヤー2のカードは{$p2Card->suit}の{$p2Card->rank}です。" . PHP_EOL;

        // 勝敗判定
        if ($p1Card->strength > $p2Card->strength) {
            $count = count($tableCards);
            echo "プレイヤー1が勝ちました。プレイヤー1はカードを{$count}枚もらいました。" . PHP_EOL;
            $p1Hand = array_merge($p1Hand, $tableCards);
            $tableCards = []; // 場札リセット

        } elseif ($p2Card->strength > $p1Card->strength) {
            $count = count($tableCards);
            echo "プレイヤー2が勝ちました。プレイヤー2はカードを{$count}枚もらいました。" . PHP_EOL;
            $p2Hand = array_merge($p2Hand, $tableCards);
            $tableCards = []; // 場札リセット

        } else {
            echo "引き分けです。" . PHP_EOL;
            // ★ここポイント！再帰呼び出しはしない！
            // 何もせずそのまま次のループに行けば、場札（$tableCards）が残ったまま次の勝負ができる！
        }
    }

    // 【2】どちらかの手札が0枚になったら、whileを抜けて結果表示へ
    $p1Count = count($p1Hand);
    $p2Count = count($p2Hand);

    if ($p2Count === 0) {
        echo "プレイヤー2の手札がなくなりました。" . PHP_EOL;
    } else {
        echo "プレイヤー1の手札がなくなりました。" . PHP_EOL;
    }

    echo "プレイヤー1の手札の枚数は{$p1Count}枚です。プレイヤー2の手札の枚数は{$p2Count}枚です。" . PHP_EOL;

    if ($p1Count > $p2Count) {
        echo "プレイヤー1が1位、プレイヤー2が2位です。" . PHP_EOL;
    } else {
        echo "プレイヤー2が1位、プレイヤー1が2位です。" . PHP_EOL;
    }
}

// 呼び出しはこれ1行だけ！
playGame($player1Hand, $player2Hand);