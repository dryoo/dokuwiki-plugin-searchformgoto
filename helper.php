<?php
/**
 * Searchformgoto Plugin
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Payton Swick <payton (at) foolord (dot) com>
 */
 
// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();
 
class helper_plugin_searchformgoto extends DokuWiki_Plugin {
 
    function getInfo() {
        return array(
                'author' => 'Payton Swick',
                'email'  => 'payton@foolord.com',
                'date'   => @file_get_contents(DOKU_PLUGIN . 'searchformgoto/VERSION'),
                'name'   => 'SearchFormGoto Plugin',
                'desc'   => 'The search form now has a "Go" button which will go to a page instead of searching.',
                'url'    => 'http://wiki.splitbrain.org/plugin:searchformgoto',
                );
    }
	
    function gotoform($ajax=true,$autocomplete=true) {  
        global $lang;
        global $ACT;
        global $ID;
        // don't print the search form if search action has been disabled  
        if (!actionOk('search')) return false;
 
        print '<form action="'.wl().'" accept-charset="utf-8" class="search" id="dw__search"><div class="no">';
        print '<input type="hidden" name="do" value="goto" />';
        print '<input type="hidden" name="current_ns" value="'.cleanID(getNS($ID)).'" />';  
        print '<input type="text" ';
        if($ACT == 'search') print 'value="'.htmlspecialchars($_REQUEST['id']).'" ';  
        if(!$autocomplete) print 'autocomplete="off" ';
        print 'id="qsearch__in" accesskey="f" name="id" class="edit" title="[F]" />';  
        print '<div class="btn-group" role="group" aria-label="...">';
        print '<input type="submit" value="'.$this->getLang('btn_go').'" class="button btn btn-sm btn-success" name="goto" title="'.$this->getLang('btn_go').'" />';
        print '<input type="submit" value="'.$lang['btn_search'].'" class="button btn btn-sm btn-primary" name="goto" title="'.$lang['btn_search'].'" />';
        print '</div>';
        if($ajax) print '<div id="qsearch__out" class="ajax_qsearch JSpopup"></div>';
        print '</div></form>';
        return true;
    }	
}