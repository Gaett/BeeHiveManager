<?php
require_once 'page.class.php';

$page = new Page();

$html = "";
$html.= $page->generateHead();
$options = $page->generateOptionFromLocalisation();

try {
    if (is_null($_GET['local'])) {
        $_GET['local'] = '0';
    }
} catch (Exception $e) {
    // ici rien ne va se passer
}

$charts = $page->generateAllChartFromLocal($_GET['local']);

$html.= $page->generateBody($options,$charts);
$html.= $page->generateScriptAndEnd($_GET['local']);

echo $html;
