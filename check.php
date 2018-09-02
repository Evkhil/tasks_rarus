<?php

$inputs = json_decode($_POST['inputs']);
$resultArray = array();

function checkFormat($element) {
    $result = "";
    $currentType = $element->type;
    $currentValue = stripslashes(htmlspecialchars(strip_tags(trim($element->value)), ENT_QUOTES));
    $currentLength = strlen($currentValue);

    switch ($currentType) {
        case "tel":
            if(!$currentLength) {
                $result = "Пустое поле";
            } elseif (!preg_match("/^[0-9-]+$/",$currentValue)) {
                $result = "Неверный формат";
            }
            break;
        case "email":
            if(!filter_var($currentValue, FILTER_VALIDATE_EMAIL)) {
                $result = "Неверный формат";
            }
            break;
        case "file":
            break;
        default:
            if(!$currentLength) {
                $result = "Пустое поле";
            }
            break;
    }
    return $result;
}

for($i = 0; $i < count($inputs); $i++) {
    $currentElement = $inputs[$i];
    $result = checkFormat($currentElement);
    if (!empty($result)) {
        $currentIndex = $currentElement->index;
        $errorArray = ["index" => $currentIndex, "message" => $result];
        $resultArray[$currentIndex] = $errorArray;
    }
}

if (!count($resultArray)) {
    $resultArray = 'success';
}

echo is_array($resultArray) ? json_encode($resultArray) : $resultArray;