<?php
/*
replacePiInArray замінює символи, що складають словосполучення "рі"
на значення константи PI і повертає масив з заміненим значенням
*/
function replacePiInArray(array $arr) : array{
    foreach($arr as $key => $value){
        // перевіряємо чи пара двох елементів масиву складає комбінацію "рі"
        // якщо так перший елемент масиву замінюємо на значення константи РІ,
        // а наступний видаляємо і перепроставляємо індеки масиву
        if(($value == "P" || $value == "p") && ($arr[$key+1] == "I" || $arr[$key+1] == "i")){
            $arr[$key] = PI;
            unset($arr[$key+1]);
            array_values($arr);
        }
    }
    return $arr;
}
/* breakArrayIntoOperators розбиває масив на числа і оператори
повертає масив з набору чисел і операторів
 */
function breakArrayIntoOperators(array $arr) : array{
    // створюємо масив символів які не будемо перетворювати в число
    $simvol = array();
    $simvol = ["(",")","*","/","+","-"];
    // створюємо новий масив який міститиме числа і знаки
    $newArr = array();
    // створюємо строку в яку записуватимемо число
    $string_value = "";
    foreach($arr as $key => $value){
        // якщо символ не входить в масив не числових знаків
        // значить це цифра і потрібно записати її в підсумковий рядок
        if(!in_array($value,$simvol) && $value != PI){
            // якщо в числі є кома, заміняємо її на крапку для подальшого перетворення у float
            if ($value == ","){
                $value = ".";
            }
            $string_value = $string_value.$value;
            // якщо значення останнє в масиві записуємо його в $newArr
            if($key == (count($arr)-1)){
                settype($string_value, "float");
                $newArr[] = $string_value;
            }
        } else if($string_value == ""){
            // якщо немає чисел для запису, записуємо в масив оператор
            $newArr[] = $value;
        } else {
            // якщо є число для запису, змінюємо його тип і записуємо в масив
            // після записуємо в масив оператор
            settype($string_value, "float");
            $newArr[] = $string_value;
            $string_value = "";
            $newArr[] = $value;
        }
    }
    return $newArr;
}
/* getMultiplicationAndDivision виконує множення і ділення в переданому масиві
повертає масив де всі операції множення і ділення замінені на результати виконих операцій
 */
function getMultiplicationAndDivision(array $arr) : array{
    // допоки в масиві є множення чи ділення виконуємо по одній операції за раз
    // і записуємо в масив
    while(in_array("*", $arr) || in_array("/", $arr)){
        $arr = array_values(MultiplicationAndDivision($arr));
    }
    return $arr;     
}
/* MultiplicationAndDivision виконує одну операцію множення
чи ділення і повертає масив де дана операція замінена на результат 
її виконання
 */
function MultiplicationAndDivision(array $arr) : array{
    global $error_flag;
    global $error_masage;
    foreach($arr as $key => $value){
        if($value == "/"){
                $arr[$key-1] = $arr[$key-1]/$arr[$key+1];
                unset($arr[$key+1]);
                unset($arr[$key]);
                array_values($arr);
                return $arr;
        }
        if($value == "*"){
            $arr[$key-1] = $arr[$key-1]*$arr[$key+1];
            unset($arr[$key+1]);
            unset($arr[$key]);
            array_values($arr);
            return $arr;
        }   
    }
}
/* getAdditionAndSubtraction виконує всі операції додавання і віднімання в масиві
повертає загальний результат операцій
 */
function getAdditionAndSubtraction($arr){
    // виконуємо додавання і віднімання і
    // повертаємо результат
    if(in_array("+", $arr) || in_array("-", $arr)){
        $result = 0;
        foreach($arr as $key => $value){
            if($key == 0){
                $result = $value;
            } else {
                if($value == "+"){
                    $result = $result + $arr[$key+1];
                }
                if($value == "-"){
                    $result = $result - $arr[$key+1];
                }
            }
        }
        return $result; 
    }    
}
/* getResultOfOperations приймає масив і повертає результат
всіх проведених в ньому математичних операцій
 */
function getResultOfOperations($arr){
    // спочатку виконуємо все множення і ділення в масиві
    // і повертаємо масив результатів
    $arr = getMultiplicationAndDivision($arr);
    // якщо все множення і ділення виконано, а операторів
    // більше не залишилось, повертаємо результат
    if(count($arr) == 1){
        return $arr[0];
    } else {
        // якщо додавання і віднімання ще можна виконати,
        // виконуємо і повертаємо результат
        $result = getAdditionAndSubtraction($arr);
        return $result;
    }
}
/*
actionInParentheses виконує всі операції в підмасиві з перших 
дужок у масиві
 */
function actionInParentheses($arr){
    // оголошуємо масив який буде містити підмасив з дужок
    $newArr = array();
    foreach ($arr as $key => $value) {
        // якщо при переборі масиву тропляється перша дужка
        // записуємо ключ як початок нашого підмасиву
        if ($value == "(") {
            $begin = $key;
        }
        // якщо натрапляємо на закриваючу дужку, записуємо ключ
        // як кінець нашого підмасиву і перериваємо цикл
        if ($value == ")") {
            $end = $key;
            break;
        }
    }
    // записуємо в новий масив всі операції з нашого підмасиву
    // виключаючи дужки
    for ($i = $begin + 1; $i < $end; $i++) {
        $newArr[] = $arr[$i];
    }
    // записуємо результат дій з підмасивом
    $result = getResultOfOperations($newArr);
    // заміняємо відкриваючу дужку на результат операцій
    $arr[$begin] = $result;
    // видаляємо всі значення підмасиву крім значення на місці першої дужки
    for ($i = $begin + 1; $i <= $end; $i++) {
        unset($arr[$i]);
    }
    return array_values($arr);
}
/* getRezultInParentheses приймає масив і виконує всі дії в дужках
повертає масив зі всіма виконаними в дужках діями
 */
function getResultInParentheses($arr){
    while (in_array("(", $arr) || in_array(")", $arr)){
        $arr = actionInParentheses($arr);
    }
    return $arr;
}
/* getGeneralResult приймає строку в якості аргументу і повертає
загальний результат над всіма проведеними в ній математичними операціями
 */
function getGeneralResult($str){
    $arr = str_split($str);
    $arr = replacePiInArray($arr);
    $arr = breakArrayIntoOperators($arr);
    $arr = getResultInParentheses($arr);
    $result = getResultOfOperations($arr);
    return $result;
}
/* перевіряємо вираз на основні помилки
якщо вони існують записуємо відповідний текст в error_masage */
function validateExpression($expression){
    global $error_flag;
    global $error_masage;
    // масив з обов'язковими символами
    $mandatory_symbols = array();
    $mandatory_symbols = ["+","-","*","/"];
    // масив з дозволеними символами
    $permitted_symbols = array();
    $permitted_symbols = ["+","-","*","/",".",",","(",")","0","1","2","3","4","5","6","7","8","9"];
    // масив з символів строки
    $arr_expression = array();
    $arr_expression = str_split($expression);
    // перевіряємо чи введена строка містить не менше 3 символів
    if(strlen($expression) < 3){
        $error_flag = true;
        $error_masage = "Вираз має містити не менше трьох символів!";
        return false;
    }
    // перевіряємо чи присутній хоч один з обо'язкових символів
    $present_mandatory_symbol = false;
    foreach($arr_expression as $operand){
        if(in_array($operand,$mandatory_symbols)){
            $present_mandatory_symbol = true;
        }
    }
    // якщо немає жодного обо'язкового символа видаємо повідомлення про помилку
    if(!$present_mandatory_symbol){
        $error_flag = true;
        $error_masage = "Вираз повинен містити хоча б один із символів (*,/,+,-)!";
        return false;
    }
    // перевіряємо чи є в масиві хоча б один заборонений символ
    $unauthorized_simvol = false;
    foreach($arr_expression as $operand){
        if(!in_array($operand,$permitted_symbols)){
            $unauthorized_simvol = true;
        }
    }
    // якщо в масиві хоча б один заборонений символ видаємо повідомлення про помилку
    if($unauthorized_simvol){
        $error_flag = true;
        $error_masage = "Введені некоректні символи!";
        return false;
    }
    // перевіряємо ділення на 0
    foreach($arr_expression as $key => $operand){
        if($operand == "/" && $arr_expression[$key+1] == 0){
            $error_flag = true;
            $error_masage = "На нуль ділити не можна!";
            return false;
        }
    }
    return true;
}
?>