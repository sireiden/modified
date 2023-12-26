<?php
/* Funktion entfernt überflüssigen und falschen Word-Code aus Textbereichen 
 * Alles von <!--[if bis [endif]--> wird entfernt
 * */

function remove_word_code($text) {
    $text = preg_replace('/<!--\s*\[if[^\]]*]>.*?<!\[endif\]-->/is',"",$text);
    return $text;
}