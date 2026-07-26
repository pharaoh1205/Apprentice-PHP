<?php


// カードクラスの定義
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




//デッキクラス作成（カードの生成・シャッフル・配布を担当）
//❶__construct()：52枚のカードを自動で生成して箱に入れる
//❷shuffle()：カードをシャッフルして混ぜる
//❸deal()：指定された枚数（今回は26枚ずつ）切り取って配る
class Deck 
{
    private array $cards = [];
    public function __construct(){

        //カードの作成
        $suits = ["ハート", "ダイヤ", "クローバー", "スペード"];
        $ranks = ["2" => 2, "3" => 3, "4" => 4, "5" => 5, "6" => 6, "7" => 7, "8" => 8, "9" => 9, "10" => 10, "J" => 11, 
        "Q" => 12, "K" => 13, "A" => 14 ];

        //デッキにカード格納
        foreach($suits as $suit){
            foreach($ranks as $rank => $strength){
            $this->cards[] = new Card($suit, $rank, $strength);//なぜ $this->cards にカードを入れるのか？
            //→「後から shuffle() や deal()（カードを配る処理）で使いたいから、消えない場所（$this->cards）にカードをしまっておく」**ため
            }
        }

    }

    // カードをシャッフルするだけのロボット
    public function shuffle(): void {
        shuffle($this->cards);
    }// return がない（何も返さない）
    
		
		//山札からカードを配る
    public function deal(int $count): array {
        return array_splice($this->cards, 0, $count);
    }
}





// プレイヤーのクラス（自分の手札管理・カードを出す・もらうを担当）
class Player {

    //❶コンストラクタ（初期化）
    public function __construct(
        public string $name,  //誰でも外から見られるように public に
        private array $hand = []//自分の手札を他人に勝手に覗かれない・書き換えられないように private で隠してガードしている
    ) {}

    //❷カードを配られる（setHand）
    public function setHand(array $cards): void {
        $this->hand = $cards;
    }


    //❸カードを1枚出す（drawCard）
    public function drawCard(): ?Card {
        return array_shift($this->hand);
    }


    //❹勝ったカードをもらう（addCards）
    public function addCards(array $cards): void {
        $this->hand = array_merge($this->hand, $cards);
    }


    //❺手札の枚数を数える・確認する（getHandCount / hasHand）
    public function getHandCount(): int {
        return count($this->hand);
    }

    public function hasHand(): bool {
        return $this->getHandCount() > 0;
    }
}




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