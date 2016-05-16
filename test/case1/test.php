<?php
//### Database connection ###
//$host 	= 'localhost';
//$db 	= 'test';
//$user	= 'root';
//$pass	= 'root';
// Download a page
//		if(file_exists($file_path)){
//			
//			header("Content-Type: application/octetstream");
//			header("Content-Disposition: attachment; filename=".$file);
//			header("Content-Transfer-Encoding: binary");
//			header("Content-Length: ".filesize($file_path));
//			
//			readfile($file_path);
//		}
//
//if($db_handle = mysql_connect($host,$user,$pass)){
//	mysql_selectdb($db);
//}
//else{
//	return false;
//}
require_once('../../Template.php');
$template = new Template();
$template->bindTo('<im-tag.first_test>','Hallo');
$test_array = array('Value1'=>23,'Value2'=>24);
$template->bindSelectFieldTo('<im-tag.select_test>','select_test',$test_array,null);
$template->bindInputTo('<im-tag.radio_test>','radio_test',67,'radio');
$test_array2 = array('Value3'=>52, 'Value4'=>76);
$list_html = '<table cellpadding="0" cellspacing="0" border="0">';
// table list per hand
while (list($key,$val) =each($test_array2)){
	
//	$list_html .= '<tr><td><input type="radio" name="'.$key.'" value="'.$val.'"/></td></tr>';
	$list_html .= '<tr><td>'.$key.'<input type="radio" name="hall" value="'.$val.'"/></td></tr>';
}
$list_html .= '</table>';
$template->bindTo('<im-tag.radio_list_test>',$list_html);
// table generate by Template-Engine
//$test_array3 = array(
//					0 => array('Name1','Wert1','checkbox','checked="checked"'),
//					1 => array('Name2','Wert2','radio'),
//					2 => array('Name3','Wert3','text','maxlength="3"'),
//					3 => array('Name4','Wert4','button'),
//				);
$template->bindTo('<im-tag.test_word>','Section hello<-- /here I am/ -->',false,false,false);
$string = $template->getSection('index.tpl','<im-tag.section_test_list>',false,true);
$template->bindTo('<im-tag.section_test_list>',$string,true);
$template->display('index.tpl');
?>
