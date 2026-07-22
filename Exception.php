
<?php

function calculate($num1, $num2, $operator)
{
    $output = match($operator){
        "+" => $num1 + $num2,
        "-" => $num1 - $num2,
        "*" => $num1 * $num2,
        "/" => $num1 / $num2,
        default => throw new exception("「{$operator}は未対応です」") 
    };
}

echo "1番目の整数を入力してください:" . PHP_EOL;
$num1 = fgets(STDIN);

echo "2番目の整数を入力してください:" . PHP_EOL;
$num2 = fgets(STDIN);

echo "演算子(+, -, *, /)を入力してください:" . PHP_EOL;
$operator = fgets(STDIN);

try {
    $result = calculate($num1, $num2, $operator);
    echo $result . PHP_EOL;
} 
catch (DivisionByZeroError $e) {
    echo "ゼロによる割り算は許可されていません";
}
catch (Exception $e) {
    echo "num1、 num2 には整数を入力してください";
}
catch (Exception $e) {
    echo "演算子には +、-、*、/ のいずれかを使用してください";
}