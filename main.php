<?php
/**
 * DokuWiki Monochrome Template based on Starter
 *
 * @link     tbd
 * @author   Anika Henke <anika@selfthinker.org>
 * @author   klaus Vormweg <klaus.vormweg@gmx.de>
 * @license  GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

if (!defined('DOKU_INC')) die(); /* must be run from within DokuWiki */
@require_once(dirname(__FILE__).'/tpl_functions.php'); /* include hook for template functions */
header('X-UA-Compatible: IE=edge');

$versionarr = getVersionData();
$version = substr($versionarr['date'], 0, 10);

$showSidebar = FALSE;
if ($ID != $conf['sidebar']):
  $showSidebar = page_findnearest($conf['sidebar']) && ($ACT=='show');
endif;
echo '<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="', $conf['lang'],
'" lang="', $conf['lang'], '" dir="', $lang['direction'], '" class="no-js">
<head>
    <meta charset="UTF-8" />
    <title>';
tpl_pagetitle();
echo ' [', strip_tags($conf['title']), ']</title>
    <script>(function(H){H.className=H.className.replace(/\bno-js\b/,\'js\')})(document.documentElement)</script>',"\n";
tpl_metaheaders();
echo '    <meta name="viewport" content="width=device-width,initial-scale=1" />', tpl_favicon(array('favicon', 'mobile'));
tpl_includeFile('meta.html');
if($version < '2017-02-19') {
  echo '<link rel="stylesheet" type="text/css" href="css/adminpage.css"/>',"\n";
}
echo '</head>
<body>',"\n";
/* with these Conditional Comments you can better address IE issues in CSS files,
   precede CSS rules by #IE8 for IE8 (div closes at the bottom) */
echo '    <!--[if lte IE 8 ]><div id="IE8"><![endif]-->',"\n";

/* the "dokuwiki__top" id is needed somewhere at the top, because that's where the "back to top" button/link links to */
/* tpl_classes() provides useful CSS classes; if you choose not to use it, the 'dokuwiki' class at least
   should always be in one of the surrounding elements (e.g. plugins and templates depend on it) */
echo '    <div id="dokuwiki__site">
      <div id="dokuwiki__top" class="site ', tpl_classes();
if ($showSidebar):
  echo ' hasSidebar';
endif;
echo '">';
html_msgarea(); /* occasional error and info messages on top of the page */
tpl_includeFile('header.html');
echo "\n",'        <!-- ********** HEADER ********** -->
        <div id="dokuwiki__header">
          <div class="pad">
            <div class="headings">',"\n";
/* how to insert logo: upload your logo into the data/media folder (root of the media manager) as 'logo.png' */
if (file_exists(DOKU_INC.'data/media/logo.png')):
  if ($ACT != 'denied'):
    tpl_link(wl(),'<img src="'.ml('logo.png').'" alt="'.$conf['title'].'" />',' accesskey="h" title="[H]"');
  endif;
else:
  tpl_link(wl(),'<img src="'.tpl_basedir().'images/headerpic.png" alt="'.$conf['title'].'" />',' accesskey="h" title="[H]"');
endif;
echo '                <h1>';
tpl_link(wl(),$conf['title'],'accesskey="h" title="[H]"');
echo '</h1>',"\n";
if ($conf['tagline']):
  echo '                    <p class="claim">', $conf['tagline'], '</p>',"\n";
endif;
echo '                <ul class="a11y skip">
                    <li><a href="#dokuwiki__content">', $lang['skip_to_content'], '</a></li>
                </ul>
                <div class="clearer"></div>
            </div>

            <div class="tools">',"\n";
if($ACT != 'denied'):
  echo '                <!-- SITE TOOLS -->
                <div id="dokuwiki__sitetools">
                    <h3 class="a11y">', $lang['site_tools'], '</h3>',"\n";
  tpl_searchform();
  echo '                </div> <!-- #dokuwiki__sitetools -->',"\n";
endif;
echo '            </div> <!-- .tools -->
            <div class="clearer"></div>',"\n";

echo '            <!-- BREADCRUMBS -->
          <div class="breadcrumbs">',"\n";
if($ACT != 'denied'):
  echo '            <!-- MENU -->
        	    <nav class="mainmenu">
              <input type="checkbox" id="hamburger" class="hamburger" />
              <label for="hamburger" class="hamburger" title="Menu"><img src="',tpl_basedir(),'images/icon-menu.png"  alt="Menu"> <span class="vishelp">Menu</span></label>',"\n";
  _tpl_mainmenu();
  echo '            </nav>',"\n";
  if ($conf['breadcrumbs']):
    tpl_breadcrumbs();
  elseif ($conf['youarehere']):
    tpl_youarehere();
  endif;
endif;
echo '</div>',"\n";
echo '            <div class="clearer"></div>
            <hr class="a11y" />
        </div></div><!-- /header -->

        <div class="wrapper">',"\n";
echo '            <!-- ********** CONTENT ********** -->
            <div id="dokuwiki__content">
              <div class="pad">',"\n";
tpl_flush(); /* flush the output buffer */
tpl_includeFile('pageheader.html');
echo '                <div class="page">
                    <!-- wikipage start -->',"\n";
tpl_content(); /* the main content */
echo '                    <!-- wikipage stop -->
                    <div class="clearer"></div>
                </div>',"\n";
tpl_flush();
tpl_includeFile('pagefooter.html');
echo '            </div></div><!-- /content -->
            <!-- ********** ASIDE ********** -->',"\n";
if ($showSidebar):
  echo '                <div id="dokuwiki__aside">
                  <div class="pad aside include group">';
  tpl_includeFile('sidebarheader.html');
  tpl_include_page($conf['sidebar'], 1, 1); /* includes the nearest sidebar page */
  tpl_includeFile('sidebarfooter.html');
  echo '                    <div class="clearer"></div>
                </div>
              </div><!-- /aside -->',"\n";
endif;
echo '            <div class="clearer"></div>
            <hr class="a11y" />

            <!-- USER TOOLS and PAGE ACTIONS -->',"\n";
if ($ACT != 'denied'):
  echo '                <div id="dokuwiki__pagetools">',"\n";
  if ($conf['useacl']):
    echo '                        <h3 class="a11y">', $lang['user_tools'], '</h3>
                      <ul>',"\n";
    echo (new \dokuwiki\Menu\UserMenu())->getListItems();
    echo '                        </ul>',"\n";
  endif;
  echo '                      <h3 class="a11y">', $lang['page_tools'], '</h3>
                    <ul class="pagetools">',"\n";
  echo (new \dokuwiki\Menu\PageMenu())->getListItems();
  echo '                    </ul>
                    <h3 class="a11y">', $lang['site_tools'], '</h3>',"\n";
  echo '                    <ul class="sitetools">';
  echo (new \dokuwiki\Menu\SiteMenu())->getListItems();
  echo '                      </ul>
                </div>',"\n";
endif;
echo '        </div><!-- /wrapper -->

        <!-- ********** FOOTER ********** -->
        <div id="dokuwiki__footer">
          <div class="pad">
            <div class="doc">';
tpl_pageinfo(); /* 'Last modified' etc */
if (!empty($_SERVER['REMOTE_USER'])):
  echo '</div><div class="user">';
  tpl_userinfo();
endif;
echo "</div>","\n";
tpl_license('button'); /* content license, parameters: img=*badge|button|0, imgonly=*0|1, return=*0|1 */
echo '        </div>
      </div><!-- /footer -->',"\n";
tpl_includeFile('footer.html');
echo '    </div>
    </div><!-- /site -->

    <div class="no">';
tpl_indexerWebBug(); /* provide DokuWiki housekeeping, required in all templates */
echo '</div>
    <!--[if lte IE 8 ]></div><![endif]-->
</body>
</html>';
