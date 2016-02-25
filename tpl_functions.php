<?php
/**
 * Template Functions
 *
 * This file provides template specific custom functions that are
 * not provided by the DokuWiki core.
 * It is common practice to start each function with an underscore
 * to make sure it won't interfere with future core functions.
 */

// must be run from within DokuWiki
if (!defined('DOKU_INC')) die();

/* prints the menu */
function _tpl_mainmenu() {
  require_once(DOKU_INC.'inc/search.php');
  global $conf;
  global $ID;

/* options for search() function */
  $opts = array(
   'depth' => 0,
   'listfiles' => true,
   'listdirs'  => true,
   'pagesonly' => true,
   'firsthead' => true,
   'sneakyacl' => true
  );

  if(isset($conf['start'])) {
    $start = $conf['start'];
  } else {
    $start = 'start';
  }

  $ns = dirname(str_replace(':','/',$ID));
  if($ns == '.') $ns ='';
  $ns  = utf8_encodeFN(str_replace(':','/',$ns));

  $data = array();
 	search($data,$conf['datadir'],'search_universal',$opts);
  $i = 0;
  $data2 = array();
	$first = true;
  foreach($data as $item) {
    if(strpos($item['id'],'playground') !== false
       or strpos($item['id'], $conf['sidebar']) !== false) {
      continue;
    }
    if(strpos($item['id'],$menufilename) !== false and $item['level'] == 1) {
    	continue;
    }
    if($item['id'] == $start or preg_match('/:'.$start.'$/',$item['id'])) {
      continue;
    }
    $data2[] = $item;
  }
  echo html_buildlist($data2,'idx','_tpl_list_index','html_li_index');
}

/* Index item formatter
 * Callback function for html_buildlist()
*/
function _tpl_list_index($item){
  global $ID;
  global $conf;
  $ret = '';
  if($item['type']=='d'){
    if(@file_exists(wikiFN($item['id'].':'.$conf['start']))) {
      $ret .= html_wikilink($item['id'].':'.$conf['start'], $item['title']);
    } else {
      $ret .= html_wikilink($item['id'].':', $item['title']);
    }
  } else {
    $ret .= html_wikilink(':'.$item['id'], $item['title']);
  }
  return $ret;
}
