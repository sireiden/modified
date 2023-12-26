<?php


function get_efficiency_array() {
    $efficiency_array   = array (array ('id' => '', 'text' => 'Keine'));
    $efficiency_array[] = array ('id' => 'A+++', 'text' => 'Klasse A+++');
    $efficiency_array[] = array ('id' => 'A++', 'text' => 'Klasse A++');
    $efficiency_array[] = array ('id' => 'A+', 'text' => 'Klasse A+');
    $efficiency_array[] = array ('id' => 'A', 'text' => 'Klasse A');
    $efficiency_array[] = array ('id' => 'B', 'text' => 'Klasse B');
    $efficiency_array[] = array ('id' => 'C', 'text' => 'Klasse C');
    $efficiency_array[] = array ('id' => 'D', 'text' => 'Klasse D');
    $efficiency_array[] = array ('id' => 'E', 'text' => 'Klasse E');
    $efficiency_array[] = array ('id' => 'F', 'text' => 'Klasse F');
    $efficiency_array[] = array ('id' => 'G', 'text' => 'Klasse G');
    $efficiency_array[] = array ('id' => 'A-DAH', 'text' => 'Klasse A (neu 2021)');
    $efficiency_array[] = array ('id' => 'B-DAH', 'text' => 'Klasse B (neu 2021)');
    $efficiency_array[] = array ('id' => 'C-DAH', 'text' => 'Klasse C (neu 2021)');
    $efficiency_array[] = array ('id' => 'D-DAH', 'text' => 'Klasse D (neu 2021)');
    $efficiency_array[] = array ('id' => 'E-DAH', 'text' => 'Klasse E (neu 2021)');
    $efficiency_array[] = array ('id' => 'F-DAH', 'text' => 'Klasse F (neu 2021)');
    $efficiency_array[] = array ('id' => 'G-DAH', 'text' => 'Klasse G (neu 2021)');
    return $efficiency_array;
}

function get_directbuy_array() {
    $directbuy_array   = array (array ('id' => 1, 'text' => 'Ja'));
    $directbuy_array[] = array ('id' => -1, 'text' => 'Nein');
    $directbuy_array[] = array ('id' => 0, 'text' => 'Nicht festgelegt');
    return $directbuy_array;
}