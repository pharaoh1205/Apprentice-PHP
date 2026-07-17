
<?php



function print_names($names){
    foreach($names as $key => $name){
        // 0番から始まるので、出力用に1を足したIDを作る
        $id = $key + 1;
        
        echo "{$id}. {$name}\n";
    }
}

print_names(['上田', '田仲', '堀田']);