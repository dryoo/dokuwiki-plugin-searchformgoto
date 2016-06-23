<?php
/**
 * A Dokuwiki plugin to add a "Go" button to the search form that will go to a page instead of searching.
 *
 * @license    GPL (see file COPYING)
 * @author     Payton Swick <payton (at) foolord (dot) com>
 *
 * To activate, put this in the template instead of '<?php tpl_searchform() ?>':
 *
 * <?php if (!plugin_isdisabled('searchformgoto')) { tpl_gotoform(); } else { tpl_searchform(); } ?>
 *
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'action.php');

class action_plugin_searchformgoto extends DokuWiki_Action_Plugin {

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

    function register(&$contr) {
        $contr->register_hook('ACTION_ACT_PREPROCESS', 'BEFORE', $this, 'handle_act_preprocess', array());
   }

    
    

    function handle_act_preprocess(&$event, $param) {
        if ($event->data != 'goto') return;
        $event->preventDefault();    

        $event->data = $this->_handle_goto();
    }

    function _handle_goto() {
      global $ID;
      global $QUERY;
      global $conf;
      global $INFO;
      global $lang;

      $s = cleanID($QUERY);
      if(empty($s)) {
        $ACT = 'show';
      } else {
        // Check if the 'Go' or the 'Search' button was pressed.
        if ($_REQUEST['goto'] and $_REQUEST['goto'] == $lang['btn_search']) {
          $ACT = 'search';
        }
        else {
          $ns  = $_REQUEST['current_ns'];
          $page_id = $s;
          if (!preg_match('/\w+:\w+/', $page_id) and preg_match('/\w/', $ns))
            $page_id = $ns.':'.$page_id;

          $ns = (getNS($page_id) ? getNS($page_id) : '');
          $no_ns = (noNS($page_id) ? noNS($page_id) : '');
          resolve_pageid($ns, $no_ns, $exists);
          $ID = $page_id;
          if (!$exists) {
            $ACT = 'search';
          } else {
            $ACT = 'show';
          }
        }
      }
      $INFO = pageinfo();
      $ACT = act_permcheck($ACT);
      return $ACT;
    }
}

