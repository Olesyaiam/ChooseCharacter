<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

const COOKIE_KEY = 'characterState';

/**
 * @param array $array
 * [
 *    'ключ для картинки' => ['name' => черта характера, 'action' => 'ADD' или 'REMOVE'],
 *    'ключ для картинки' => ['name' => черта характера, 'action' => 'ADD' или 'REMOVE'],
 *    ...
 * ]
 * @return string
 *
 */
function htmlList(array $array = array()): string
{
    $output = '<div style="height:400px;overflow-y:scroll;width: 250px; border:3px solid black"><ul>';

    foreach ($array as $number => $characterData) {
        $name = $characterData['name'];

        $output .= '<li style="list-style-type: none;">';
        $output .= '<a href="add.php?trait=' . $name . '">';
        $output .= "<img src='images/characters/$number.png' width=30>";
        $output .= $name;
        $output .= '</a>';
        $output .= '</li>';
    }

    return $output . '</ul></div>';
}

/**
 * Эта функция сохраняет в куки состояние персонажа в виде массива его черт характера
 * которые он имеет или не имеет
 * @param array $doesntHave
 * @param array $has
 */
function setCharacterCookie(array $doesntHave, array $has)
{
    // объеденяем два массива в один
    $array = array(
        'doesntHave' => array_keys($doesntHave),
        'has' => array_keys($has)
    );

    // превращаем массив в текст
    $arrayAsText = json_encode($array);

    // сохраняем массив в куки
    setcookie(COOKIE_KEY, $arrayAsText, time() + 86400, '/'); // Сохраняем в куки 2 массива, объединенные в 1 массив в ключ COOKIE_KEY
    $_COOKIE[COOKIE_KEY] = $arrayAsText;
}

/**
 * Берем из куков массив, который содержит в себе 2 подмассива:
 * has и doesntHave
 */
function getCharacterCookie(): array
{
    $array = json_decode($_COOKIE[COOKIE_KEY], true);
    $doesntHaveNumbers = $array['doesntHave'];
    $hasNumbers = $array['has'];

    $doesntHave = array();
    $has = array();

    $traitsFromFile = getTraitsFromFile();

    foreach ($doesntHaveNumbers as $number) {
        $doesntHave[$number] = $traitsFromFile[$number];
    }

    foreach ($hasNumbers as $number) {
        $has[$number] = $traitsFromFile[$number];
    }

    return array(
        'doesntHave' => $doesntHave,
        'has' => $has
    );
}

function getTraitsFromFile(): array
{
    $text = file_get_contents('text.json');

    return json_decode($text, true);
}

/**
 * Функция вызывается, когда создается новый персонаж.
 * Данная функция сохраняет в куки 2 массива:
 * 1. Пустой  - с теми чертами характера которые он имеет (далее массив будет заполнен)
 * 2. Которые он не имеет (на данный момент все которые существуют)
 */
function initializeCharacter()
{
    $doesntHave = getTraitsFromFile();
    $has = array();

    setCharacterCookie($doesntHave, $has);
}

/**
 * Из колонки "doesn't have" переносим в колонку "has"
 */
function moveCharacterTraitFromDoesntHaveToHas(string $traitName)
{
    $doesntHaveAndHas = getCharacterCookie();
    $doesntHave = $doesntHaveAndHas['doesntHave'];
    $has = $doesntHaveAndHas['has'];

    foreach ($doesntHave as $number => $traitData) {
        if ($traitData['name'] == $traitName) {
            $has[$number] = $traitData;
            unset($doesntHave[$number]);

            break;
        }
    }

    setCharacterCookie($doesntHave, $has);
}

function redirectToIndex()
{
    header('Location: index.php');
}