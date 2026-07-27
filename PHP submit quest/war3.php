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
                //→後から shuffle() や deal()（カードを配る処理）で使いたいから、消えない場所（$this->cards）にカードをしまっておくため
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
    public function drawCard(): ?Card {//カード（Card クラスのデータ）を1枚返すよ！
     //カードを返せるかもしれないし、手ぶら（null）で帰ってくるかもしれない」という不確定な状態だから?(クエスチョン)
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

// 4. ゲームの進行・審判クラス
class WarGame {
    private Player $player1; //Playerは型指定（PlayerクラスのインスタンスのみOK）プレイヤー1用スロット
    private Player $player2; //プレイヤー2用スロット

    public function __construct(Player $p1, Player $p2) {
        $this->player1 = $p1;  //$this->player1 はさっき上で作成したゲーム機の中の「プレイヤー1用スロット」$p1 ➔ 外から運ばれてきたプレイヤー1
        $this->player2 = $p2;  //外から受け取った $p2 をゲーム機の中のポケット（$this->player2）にガッチャンコと差し込んで保存
    }

    public function start(): void {
        // ❶山札を作って配る
        $deck = new Deck(); //Deckクラスのインスタンス化
        $deck->shuffle();   //シャッフル
        $this->player1->setHand($deck->deal(26));
        $this->player2->setHand($deck->deal(26));

        echo "戦争を始めます" . PHP_EOL;
        echo "カードが配られました" . PHP_EOL;

        $tableCards = [];

        // ❷両者手札がある間ループ
        while ($this->player1->hasHand() && $this->player2->hasHand()) {
            echo "戦争！" . PHP_EOL;

            $card1 = $this->player1->drawCard();
            $card2 = $this->player2->drawCard();

            $tableCards[] = $card1;
            $tableCards[] = $card2;

            echo "{$this->player1->name}のカードは{$card1->suit}の{$card1->rank}です。" . PHP_EOL;
            echo "{$this->player2->name}のカードは{$card2->suit}の{$card2->rank}です。" . PHP_EOL;

            if ($card1->strength > $card2->strength) {
                $count = count($tableCards);
                echo "{$this->player1->name}が勝ちました。{$this->player1->name}はカードを{$count}枚もらいました。" . PHP_EOL;
                $this->player1->addCards($tableCards);
                $tableCards = [];
            } elseif ($card2->strength > $card1->strength) {
                $count = count($tableCards);
                echo "{$this->player2->name}が勝ちました。{$this->player2->name}はカードを{$count}枚もらいました。" . PHP_EOL;
                $this->player2->addCards($tableCards);
                $tableCards = [];
            } else {
                echo "引き分けです。" . PHP_EOL;
            }
        }

        // 決着処理
        $this->showResult();
    }

    // ❸ 結果表示（showResult）
    private function showResult(): void {
        $p1Count = $this->player1->getHandCount();
        $p2Count = $this->player2->getHandCount();

        // どっちの手札がなくなったかを判定
        if ($p1Count === 0) {
            echo "{$this->player1->name}の手札がなくなりました。" . PHP_EOL;
        } else {
            echo "{$this->player2->name}の手札がなくなりました。" . PHP_EOL;
        }

        // 最終的な枚数を表示
        echo "{$this->player1->name}の手札の枚数は{$p1Count}枚です。{$this->player2->name}の手札の枚数は{$p2Count}枚です。" . PHP_EOL;

        // 順位を発表
        if ($p1Count > $p2Count) {
            echo "{$this->player1->name}が1位、{$this->player2->name}が2位です。" . PHP_EOL;
        } else {
            echo "{$this->player2->name}が1位、{$this->player1->name}が2位です。" . PHP_EOL;
        }
        echo "戦争を終了します。" . PHP_EOL;
    }
}

// 実行（呼び出し側はこれだけ！）
$game = new WarGame(
    new Player("プレイヤー1"),
    new Player("プレイヤー2")
);
$game->start();


