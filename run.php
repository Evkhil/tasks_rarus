<?php
// скрипт проверяет только 1 переданный параметр, т.к. в задании не было сказано о нескольких параметрах

$paramArray = $_SERVER['argv']; // получаем массив переданных параметров
$checkString = $paramArray[1]; // первый параметр это имя файла, второй - переданная строка
$correctString = true; // корретное расставление скобок
$bracketsInString = 0; // количество скобок в строке

$k = 0;
$stringLength = strlen($checkString);

for($i = 0; $i < $stringLength; $i++) {
    $currentSymbol = $checkString[$i];
    if($currentSymbol == "(") {
        $k++;
        $bracketsInString += 1;
    }  elseif($currentSymbol == ")") {
        $k--;
        $bracketsInString += 1;
    };

    if($k < 0) {
        $correctString = false;
    }
}

if($bracketsInString == 0) {
    echo "Переданная строка не содержит скобок! Дальнейшая проверка невозможна!";
} else {
    $result = ($k == 0);
    if($result){
        echo "Количество скобок совпадает!\n";
    } else {
        echo "Количество скобок не совпадает!\n";
    }

    $text = ($result && $correctString) ? "корректно!" : "некорректно!"; // если разное кол-во скобок то автоматически считаем что расстановка некорретна
    echo "Расставление скобок в строке " . $text;
}


?>