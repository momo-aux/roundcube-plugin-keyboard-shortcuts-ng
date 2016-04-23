<?php
/**
 * keyboard_shortcuts
 *
 * Enables some common tasks to be executed with keyboard shortcuts
 *
 * @version 1.4 - 07.07.2010
 * @author Patrik Kullman / Roland 'rosali' Liebl / Cor Bosman <roundcube@wa.ter.net>
 * @licence GNU GPL
 *
 **/
 /** *
 **/

/**
 * Shortcuts, list view:
 * ?:	Show shortcut help
 * a:	Select all visible messages
 * A:	Mark all as read (as Google Reader)
 * c:	Compose new message
 * d:	Delete message
 * f:	Forward message
 * j:	Go to previous page of messages (as Gmail)
 * k:	Go to next page of messages (as Gmail)
 * m:	Mark message(s) read/unread (as Thunderbird)
 * p:	Print message
 * r:	Reply to message
 * R:	Reply to all of message
 * s:	Jump to quicksearch
 * u:	Check for new mail (update)
 *
 * Shortcuts, threads view:
 * E:   Expand all
 * C:   Collapse all
 * U:   Expand Unread
 *
 * Shortcuts, mail view:
 * d:	Delete message
 * f:	Forward message
 * j:	Go to previous message (as Gmail)
 * k:	Go to next message (as Gmail)
 * p:	Print message
 * r:	Reply to message
 * R:	Reply to all of message
 */

class keyboard_shortcuts_ng extends rcube_plugin
{



    /*
     * List of contexts supported
     *
     * Mainly here to protect the rest of roundcube from unexpected behaviour,
     * for example if in future new context is implemented that could be broken
     * by simply installing this plugin.
     */
    protected $supported_contexts = array(
        'list'      => true,
        'preview'   => true,
        'show'      => true,
        'compose'   => true,
    );



    /*
     * Actions vs. keyboard shortcuts vs. contexts configuration array
     *
     * Rules:
     * - more specific setting (context-wise) overrides less-specific one
     * - each context can have dedicated shortcut defined for that action (this use is discouraged!)
     * - if context does not have shortcut defined, main shortcut is used
     * - see above for list of supported contexts
     * - supplemental keys are detected in alphabetical order ('alt ctrl o' is ok, where 'ctrl alt o' is unacceptable)
     *
     * Supported supplemental keys:
     * - 'alt'
     * - 'ctrl'
     * - 'meta'
     * - 'shift'
     *
     *
     * Configuration examples:
     *
     *     Valid in one context only:
     *
     *         'message_send'                   => array('shortcut' => 'ctrl enter', 'context' => array('compose' => array())),
     *
     *
     *     Valid in multiple (but not all) contexts:
     *
     *         'messagelist_select_all_on_page' => array('shortcut' => 'ctrl a',     'context' => array('list'    => array(), 'preview' => array())),
     *
     *
     *     Valid in all contexts - DO NOT USE, weird things start to happen (unable to type in compose window and such):
     *
     *         'searchbox_focus'                => array('shortcut' => 'ctrl s',     'context' => NULL),
     *
     *
     *     'shortcut' key can be either string or array of strings:
     *
     *          'shortcut' => 'ctrl s',
     *          'shortcut' => array('ctrl s', 'alt s'),
     *
     *
     */
    protected $config = array(
        // General actions
        'ks_ng_help_display'             => array('shortcut' => 'shift ?',      'context' => array('list'=>true, 'preview'=>true)),
        'checkmail'                      => array('shortcut' => 'u',            'context' => array('list'=>true, 'preview'=>true, 'show'=>true)),
        'searchbox_focus'                => array('shortcut' => 's',            'context' => array('list'=>true, 'preview'=>true, 'show'=>true)),
        // TODO also bind shift+k to searchbox (similar to browser's ctrl+k)

        // List actions
        'messagelist_select_all_on_page' => array('shortcut' => 'shift a',      'context' => array('list'=>true, 'preview'=>true)),
        'messagelist_select_all'         => array('shortcut' => 'ctrl shift a', 'context' => array('list'=>true, 'preview'=>true)),
        'previewpane_toggle_visibility'  => array('shortcut' => 't',            'context' => array('list'=>true, 'preview'=>true)),
        'threads_collapse_all'           => array('shortcut' => 'shift c',      'context' => array('list'=>true, 'preview'=>true)),
        'threads_expand_all'             => array('shortcut' => 'shift e',      'context' => array('list'=>true, 'preview'=>true)),
        'threads_expand_unread'          => array('shortcut' => 'shift u',      'context' => array('list'=>true, 'preview'=>true)),

        // Message actions
        'message_compose'                => array('shortcut' => 'c',            'context' => array('list'=>true, 'preview'=>true, 'show'=>true)),
        'message_forward'                => array('shortcut' => 'f',            'context' => array('list'=>true, 'preview'=>true, 'show'=>true)),
        'message_print'                  => array('shortcut' => 'p',            'context' => array('list'=>true, 'preview'=>true, 'show'=>true)),
        'message_reply'                  => array('shortcut' => 'r',            'context' => array('list'=>true, 'preview'=>true, 'show'=>true)),
        'message_reply_all'              => array('shortcut' => 'shift r',      'context' => array('list'=>true, 'preview'=>true, 'show'=>true)),
        // This one is hardcoded for HTML message composer - iframe/tinymce limitation
        'message_send'                   => array('shortcut' => 'ctrl enter',   'context' => array('compose' => true)),
        'message_toggle_flag_read'       => array('shortcut' => 'm',            'context' => array('list'=>true, 'preview'=>true)),
        'message_view_next'              => array('shortcut' => 'k',            'context' => array('list'=>true, 'preview'=>true, 'show'=>true)),
        'message_view_prev'              => array('shortcut' => 'j',            'context' => array('list'=>true, 'preview'=>true, 'show'=>true)),
        // Dangerous, therefore disabled by default
        // 'message_delete'                 => array('shortcut' => 'd',            'context' => array('list'=>true, 'preview'=>true, 'show'=>true)),
    );



    function init()
    {
      // only init in authenticated state and if newuserdialog is finished
      // do not init on compose (css incompatibility with compose_addressbook plugin
      $rcmail = rcmail::get_instance();
      $this->require_plugin('jqueryui');

        $rcmail->output->set_env('ks_ng_supported_contexts', $this->supported_contexts);
        $rcmail->output->set_env('ks_ng_config',   $this->config);

      if($_SESSION['username'] && empty($_SESSION['plugin.newuserdialog'])){
        $this->include_stylesheet('keyboard_shortcuts_ng.css');
        $this->include_script('keyboard_shortcuts_ng_actions.js');
        $this->include_script('keyboard_shortcuts_ng.js');
        $this->add_hook('template_container', array($this, 'html_output'));
        $this->add_texts('localization', true);
      }
    }

    function html_output($p) {
      if ($p['name'] == "listcontrols") {
        $rcmail = rcmail::get_instance();
        $skin  = $rcmail->config->get('skin');

        if(!file_exists(__DIR__ .'/skins/' . $skin . '/images/keyboard.png')){
          $skin = "default";
        }

        $this->load_config();
        $keyboard_shortcuts = $rcmail->config->get('keyboard_shortcuts_extras', array());

        $c = "";
        $c .= '<span id="keyboard_shortcuts_title">' . $this->gettext("title") . ":&nbsp;</span><a id='keyboard_shortcuts_link' href='#' class='button' title='".$this->gettext("keyboard_shortcuts")." ".$this->gettext("show")."' onclick='return keyboard_shortcuts_show_help()'><img align='top' src='plugins/keyboard_shortcuts_ng/skins/".$skin."/images/keyboard.png' alt='".$this->gettext("keyboard_shortcuts")." ".$this->gettext("show")."' /></a>\n";
        $c .= "<div id='keyboard_shortcuts_help'>";
        $c .= "<div><h4>".$this->gettext("mailboxview")."</h4>";
        $c .= "<div class='shortcut_key'>?</div> ".$this->gettext('help')."<br class='clear' />";
        $c .= "<div class='shortcut_key'>a</div> ".$this->gettext('selectallvisiblemessages')."<br class='clear' />";
        $c .= "<div class='shortcut_key'>A</div> ".$this->gettext('markallvisiblemessagesasread')."<br class='clear' />";
        $c .= "<div class='shortcut_key'>m</div> ".$this->gettext('markselected')."<br class='clear' />";
        $c .= "<div class='shortcut_key'>c</div> ".$this->gettext('compose')."<br class='clear' />";
        $c .= "<div class='shortcut_key'>d</div> ".$this->gettext('deletemessage')."<br class='clear' />";
        $c .= "<div class='shortcut_key'>f</div> ".$this->gettext('forwardmessage')."<br class='clear' />";
        $c .= "<div class='shortcut_key'>j</div> ".$this->gettext('previouspage')."<br class='clear' />";
        $c .= "<div class='shortcut_key'>k</div> ".$this->gettext('nextpage')."<br class='clear' />";
        $c .= "<div class='shortcut_key'>p</div> ".$this->gettext('printmessage')."<br class='clear' />";
        $c .= "<div class='shortcut_key'>r</div> ".$this->gettext('replytomessage')."<br class='clear' />";
        $c .= "<div class='shortcut_key'>R</div> ".$this->gettext('replytoallmessage')."<br class='clear' />";
        $c .= "<div class='shortcut_key'>s</div> ".$this->gettext('quicksearch')."<br class='clear' />";
        $c .= "<div class='shortcut_key'>t</div> ".$this->gettext('mailpreviewtoggle')."<br class='clear' />";
        $c .= "<div class='shortcut_key'>u</div> ".$this->gettext('checkmail')."<br class='clear' />";
        $c .= "<div class='shortcut_key'> </div> <br class='clear' />";
        $c .= "</div>";

        if(!is_object($rcmail->imap)){
          $rcmail->imap_connect();
        }
        $threading_supported = $rcmail->imap->get_capability('thread=references')
          || $rcmail->imap->get_capability('thread=orderedsubject')
          || $rcmail->imap->get_capability('thread=refs');

        if ($threading_supported) {
          $c .= "<div><h4>".$this->gettext("threads")."</h4>";
          $c .= "<div class='shortcut_key'>E</div> ".$this->gettext('expand-all')."<br class='clear' />";
          $c .= "<div class='shortcut_key'>C</div> ".$this->gettext('collapse-all')."<br class='clear' />";
          $c .= "<div class='shortcut_key'>U</div> ".$this->gettext('expand-unread')."<br class='clear' />";
          $c .= "<div class='shortcut_key'> </div> <br class='clear' />";
          $c .= "</div>";
        }
        $c .= "<div><h4>".$this->gettext("messagesdisplaying")."</h4>";
        $c .= "<div class='shortcut_key'>d</div> ".$this->gettext('deletemessage')."<br class='clear' />";
        $c .= "<div class='shortcut_key'>c</div> ".$this->gettext('compose')."<br class='clear' />";
        $c .= "<div class='shortcut_key'>f</div> ".$this->gettext('forwardmessage')."<br class='clear' />";
        $c .= "<div class='shortcut_key'>j</div> ".$this->gettext('previousmessage')."<br class='clear' />";
        $c .= "<div class='shortcut_key'>k</div> ".$this->gettext('nextmessage')."<br class='clear' />";
        $c .= "<div class='shortcut_key'>p</div> ".$this->gettext('printmessage')."<br class='clear' />";
        $c .= "<div class='shortcut_key'>r</div> ".$this->gettext('replytomessage')."<br class='clear' />";
        $c .= "<div class='shortcut_key'>R</div> ".$this->gettext('replytoallmessage')."<br class='clear' />";
        $c .= "<div class='shortcut_key'> </div> <br class='clear' />";
        $c .= "</div></div>";

        $rcmail->output->set_env('ks_functions', array('63' => 'ks_help'));

        $p['content'] = $c . $p['content'];
      }
      return $p;
    }
}
