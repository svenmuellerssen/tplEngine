<?php
include('../Template.php');

//$template = new Template('./','UTF-8',TRUE,'./');
$template = new Template();
$template->setCaching(TRUE);
$template->setTemplatePath('./');
$template->setCachePath('./');
$template->setExit(TRUE);
$template->checkSiteCache();
//#################
$string1 = '<meta name="author" content="Rainer Wahnsinn">';
$string2 = '<meta name="keywords" content="HTML, Meta-Informationen,Suchprogramme, HTTP-Protokoll">';
$string3 = 'Hier war eine Sektion';
$string4 = '<meta name="keywords" lang="de" content="Ferien, Griechenland, Sonnenschein">';
$string5 = '<meta name="keywords" lang="en" content="holiday, Greece, sunshine">';
$string6 = 'Hier ist die erste Zelle in der 1. Sektion.';
$string7 = 'Hier ist die zweite Zelle mit der 2. Sektion in der 1. Sektion.';
$template->bindTo('<im-tag:head>',$string1);
$template->bindTo('<im-tag:head>',$string2);
//$template->bindTo('<im-tag:test_section_content>',$string6);
//if(!$template->bindTo('<im-tag:test_section>',null,true,true))
//{
//  echo "section 1: false";
//}
//if(!$template->bindTo('<im-tag:test_section2>',null,true,true))
//{
//  echo "section 2: false";
//}
// //$template->bindTo('<im-tag:test_section>',$string3,true);
//$template->bindTo('<im-tag:head>',$string4);
//$template->bindTo('<im-tag:head>',$string5);
//$template->bindTo('<im-tag:test_section2_content>',$string7);
//##################

$template->bindTo('<im-tag:logo>',$string3);
$template->bindTo('<im-tag:spacer>',$string6);
$template->bindTo('<im-tag:irgendwas>',$string7);
$template->bindTo('<im-tag:title>',$string3);
$template->bindTo('<im-tag:header>',$template->getTemplate('header.tpl',true));
$template->display('index.tpl');

echo "Halllo";
?>