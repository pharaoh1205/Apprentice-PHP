<?php

// 1. カードクラス
class Card {
    public string $suit;
    public string $rank;
    public int $strength;

    public function __construct(string $suit, string $rank, int $strength) {
        $this->suit = $suit;
        $this->rank = $rank;
        $this->strength = $strength;
    }

    // ジョーカー判定
    public function isJoker(): bool {
        return $this->suit === "ジョーカー";
    }

    // スペードのA（世界一）判定
    public function isSpadeA(): bool {
        return $this->suit === "スペード" && $this->rank === "A";
    }
}


//ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー


// 2. デッキクラス
class Deck {
    private array $cards = [];

    public function __construct() {
        $suits = ["ハート", "ダイヤ", "クローバー", "スペード"];
        $ranks = [
            "2" => 2, "3" => 3, "4" => 4, "5" => 5, "6" => 6, "7" => 7, "8" => 8, "9" => 9, "10" => 10,
            "J" => 11, "Q" => 12, "K" => 13, "A" => 14
        ];

        foreach ($suits as $suit) {
            foreach ($ranks as $rank => $strength) {
                $this->cards[] = new Card($suit, $rank, $strength);
            }
        }

        // ★追加ルール：ジョーカー1枚を追加（強さは最強の15）
        $this->cards[] = new Card("ジョーカー", "Joker", 15);
    }

    public function shuffle(): void {
        shuffle($this->cards);
    }

    public function deal(int $count): array {
        return array_splice($this->cards, 0, $count);
    }

    // 残りの枚数を取得（あまりのカードの分配用）
    public function getCardCount(): int {
        return count($this->cards);
    }
}


//ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー


// 3. プレイヤークラス
class Player {
    public function __construct(
        public string $name,
        private array $hand = []
    ) {}

    public function setHand(array $cards): void {
        $this->hand = $cards;
    }

    public function drawCard(): ?Card {
        return array_shift($this->hand);
    }

    public function addCards(array $cards): void {
        $this->hand = array_merge($this->hand, $cards);
    }

    public function getHandCount(): int {
        return count($this->hand);
    }

    public function hasHand(): bool {
        return $this->getHandCount() > 0;
    }
}


//ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー


// 4. ゲームの進行・審判クラス（多人数対応版）
class WarGame {
    /** @var Player[] */
    private array $players;

    // 配列で全員を受け取る
    public function __construct(array $players) {
        $this->players = $players;
    }

    public function start(): void {
        // ❶ カードの配布（ジョーカー込み53枚を均等に配る）
        $deck = new Deck();  // インスタンス化
        $deck->shuffle();    // シャッフル
        
        $playerCount = count($this->players);
        $totalCards = $deck->getCardCount(); // 53枚
        $dealCount = (int)floor($totalCards / $playerCount); // 一人当たりに均等に配る枚数

        foreach ($this->players as $player) {
            $player->setHand($deck->deal($dealCount));
        }

        // 配りきれなかったあまりのカードがあれば場札（テーブル）にセットしておく
        $tableCards = $deck->deal($deck->getCardCount());

        echo "カードが配られました。" . PHP_EOL;

        // ❷ 誰かの手札がなくなるまでループ
        while ($this->isAllPlayersHaveHand()) {
            echo "戦争！" . PHP_EOL;

            $playedCards = []; // 今ターン出されたカードリスト [['player' => Player, 'card' => Card], ...]
            $maxStrength = 0;

            // 全員がカードを1枚ずつ出す
            foreach ($this->players as $player) {
                $card = $player->drawCard();
                $tableCards[] = $card;
                
                // 表示の切り替え（ジョーカーの場合は「ジョーカーです。」と表示）
                if ($card->isJoker()) {
                    echo "{$player->name}のカードはジョーカーです。" . PHP_EOL;
                } else {
                    echo "{$player->name}のカードは{$card->suit}の{$card->rank}です。" . PHP_EOL;
                }

                // 出されたカードをリストに追加（上書きしないように [] を使用）
                $playedCards[] = [
                    'player' => $player,
                    'card'   => $card
                ];

                // 一番強いカードの数値を更新
                if ($card->strength > $maxStrength) {
                    $maxStrength = $card->strength;
                }
            }

            // ★勝敗判定（追加ルール考慮）
            $winners = $this->judgeWinners($playedCards, $maxStrength);

            // 勝敗判定（ $winners[]の中にいるのが1人だけなら勝ち、2人以上いたら引き分け）
            if (count($winners) === 1) {
                $winner = $winners[0];
                $gotCount = count($tableCards); // 貰うカードの枚数
                echo "{$winner->name}が勝ちました。{$winner->name}はカードを{$gotCount}枚もらいました。" . PHP_EOL;
                $winner->addCards($tableCards); // 勝者がカードを貰う処理
                $tableCards = []; // 場札リセット
            } else {
                echo "引き分けです。" . PHP_EOL;
            }
        }

        // 決着処理
        $this->showResult();
    }

    // ★ 勝者決定ロジック（追加ルール判定）
    private function judgeWinners(array $playedCards, int $maxStrength): array {
        // 1. ジョーカーが出ているか？（最強）
        foreach ($playedCards as $played) {
            if ($played['card']->isJoker()) {
                return [$played['player']];
            }
        }

        // 2. 通常通り、一番強い数字を出したプレイヤーを集める
        $topPlayers = [];//『スペードのA（世界一ルール）』という逆転ルールがあるのでAをだしただけでは勝者になれるわけでではなくなったため$winnersから$topPlayersに変更
        foreach ($playedCards as $played) {
            if ($played['card']->strength === $maxStrength) {
                $topPlayers[] = $played['player'];
            }
        }

        // 3. 一番強いカードが「A（14）」で、複数人被った場合の特例（世界一ルール）
        if ($maxStrength === 14 && count($topPlayers) >= 2) {
            foreach ($playedCards as $played) {
                if ($played['card']->isSpadeA()) {
                    return [$played['player']]; // スペードのAを出した人が総取り！
                }
            }
        }

        // 特例に該当しなければそのまま返す（1人なら勝ち、2人以上なら引き分け）
        return $topPlayers;
    }

    // 全員が手札を持っているか確認
    private function isAllPlayersHaveHand(): bool {
        foreach ($this->players as $player) {
            if (!$player->hasHand()) {
                return false; // 1人でも手札が0になったら false
            }
        }
        return true;
    }

    // 結果表示
    private function showResult(): void {
        // 手札がなくなったプレイヤーを表示
        foreach ($this->players as $player) {
            if (!$player->hasHand()) {
                echo "{$player->name}の手札がなくなりました。" . PHP_EOL;
            }
        }

        // 各自の枚数を表示
        $countsText = [];
        foreach ($this->players as $player) {
            $countsText[] = "{$player->name}の手札の枚数は{$player->getHandCount()}枚です。";
        }
        echo implode('', $countsText) . PHP_EOL;

        // 手札が多い順にソート（順位づけ）
        $sortedPlayers = $this->players; // 修正: [] を外して配列をそのまま代入
        usort($sortedPlayers, function($a, $b) {
            return $b->getHandCount() <=> $a->getHandCount(); // 降順
        });

        // 順位表示
        $rankText = [];
        foreach ($sortedPlayers as $index => $player) {
            $rank = $index + 1;//indexは0からだから1足してあげる
            $rankText[] = "{$player->name}が{$rank}位";
        }
        echo implode('、', $rankText) . "です。" . PHP_EOL;
        echo "戦争を終了します。" . PHP_EOL;
    }
}


//ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー


// ==========================================
// 実行部分（入力処理）
// ==========================================
echo "戦争を開始します。" . PHP_EOL;

// 人数の入力
echo "プレイヤーの人数を入力してください（2〜5）: ";
$playerNum = (int)trim(fgets(STDIN));

// 名前入力 ＆ プレイヤー作成
$players = [];
for ($i = 1; $i <= $playerNum; $i++) {
    echo "プレイヤー{$i}の名前を入力してください: ";
    $name = trim(fgets(STDIN));
    $players[] = new Player($name); // Playerクラスのインスタンス化
}

// ゲーム開始
$game = new WarGame($players);
$game->start();