<?php
/**
 * Template Functions
 *
 * This file provides template specific custom functions that are
 * not provided by the DokuWiki core.
 * It is common practice to start each function with an underscore
 * to make sure it won't interfere with future core functions.
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 * @author Klaus Vormweg <klaus.vormweg@gmx.de>
 *
 */

// must be run from within DokuWiki
if (!defined('DOKU_INC')) die();

/** prints the menu
 *  @param void
 *  @return void
*/
function _tpl_mainmenu() {
  require_once(DOKU_INC.'inc/search.php');
  global $conf;

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


  $data = array();
  search($data,$conf['datadir'],'search_universal',$opts);
  $i = 0;
  $data2 = array();
  $first = true;
  foreach($data as $item) {
    if(strpos($item['id'],'playground') !== false) {
      continue;
    }
    if(isset($conf['sidebar'])
        and strpos($item['id'], $conf['sidebar']) !== false) {
      continue;
    }
    if($item['id'] == $start or preg_match('/:'.$start.'$/',$item['id'])
       or preg_match('/(\w+):\1$/',$item['id'])) {
      continue;
    }
    if(array_key_exists($item['id'], $data2)) {
      $data2[$item['id']]['type'] = 'd';
      $data2[$item['id']]['ns'] = $item['id'];
      continue;
    }
    $data2[$item['id']] = $item;
  }
  echo html_buildlist($data2,'idx','_tpl_list_index','_tpl_html_li_index');
}

/** Index item formatter
 *  Callback function for html_buildlist()
 *
 *  @param array $item
 *  @return string html
*/
function _tpl_list_index($item) {
  global $conf;
  $ret = '';
  if($item['type'] == 'd') {
    if(@file_exists(wikiFN($item['id'].':'.$conf['start']))) {
      $ret .= html_wikilink($item['id'].':'.$conf['start'], $item['title']);
    } elseif(@file_exists(wikiFN($item['id'].':'.$item['id']))) {
      $ret .= html_wikilink($item['id'].':'.$item['id'], $item['title']);
    } else {
      $ret .= html_wikilink($item['id'].':', $conf['start']);
    }
  } else {
    $ret .= html_wikilink(':'.$item['id'], $item['title']);
  }
  return $ret;
}

/**
 * Index List item
 *
 * This user function is used in html_buildlist to build the
 * <li> tags for namespaces when displaying the page index
 * it gives different classes to opened or closed "folders"
 *
 * @param array $item
 * @return string html
 */
function _tpl_html_li_index($item) {
  global $INFO;

  $class = '';
  $id = '';

  if($item['type'] == "f") {
    return '<li class="level'.$item['level'].$class.'" '.$id.'>';
  } else {
    return '<li class="closed level'.$item['level'].$class.'">';
  }
}

/**
 * Checks if a media file is readable by the current user
 *
 * @param string $id
 * @return bool
*/
function _tpl_media_isreadable($id) {
  $id = cleanID($id);
  if(auth_quickaclcheck($id)) {
    return true;
  } else {
    return false;
  }
}
