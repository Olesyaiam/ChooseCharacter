<?php
require_once 'functions.php';
//setcookie('test', '345');
//print_R($_COOKIE);
//die;
if (!array_key_exists(COOKIE_KEY, $_COOKIE)) {
    initializeCharacter();
}

$arrayDoesntHaveAndHas = getCharacterCookie();

echo '<table border="0" >';
echo '<tr>';
echo '<td>';
echo htmlList($arrayDoesntHaveAndHas['doesntHave']);
echo '</td>';

echo '<td>';
echo '<img src="images/arrow.png" width="100">';
echo '</td>';

echo '<td>';
echo htmlList($arrayDoesntHaveAndHas['has']);
echo '</td>';
echo '</tr>';
echo '</table>';

echo '<form action="clear.php" method="GET"><input type="submit" value="Очистить"></form>';

