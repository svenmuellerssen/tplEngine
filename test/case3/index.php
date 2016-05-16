<?php

include_once('Template.php');

$template = new Template();
$template->setFullDateFormat('d.m.Y H:i:s +200');
$template->bindTo('<im-tag:title>','Listentest');
// Table tests
$table1[0] = array( array('value' => 'Wert1'),array('value' => '<im-tag:ul-list>'),array('value' => 'Wert3'),array('value' => 'Wert4') );
// array, rows, columns, rows attributes, table attributes
//$table = $template->getTableObject($table1,'valign="top"');

$template->bindHTMLElementTo('<im-tag:table1>','table',$table1,'valign="top"');
// List tests
$ul1 = array('gustatorisch','olfaktorisch','auditiv','<im-tag:ol1-list>');
$ol1 = array('10 Hertz','50 Hertz','100 Hertz','500 Hertz','1000 Hertz','5000 Hertz');
$params = array(3 => 'style="list-style-type: none;"');

//$list = $template->getListObject('ul',$ul1,$params);
//$list1 = $template->getListObject('ol',$ol1);

$template->bindHTMLElementTo('<im-tag:ul-list>','ul',$ul1,$params);
$template->bindHTMLElementTo('<im-tag:ol1-list>','ol',$ol1);


$template->display('index.tpl');
?>