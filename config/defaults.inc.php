<?php
/*
 * Keyboard Shortcuts NG - configuration defaults
 *
 *
 * The settings specified below may be overriden in your MAIN Roundcube
 * deployment configuration files in YOUR-ROUNDCUBE/config/ dir.
 *
 * WARNING: Do not copy this file to config.inc.php in this same directory.
 *          It will be ignored.
 */


/*
 * This is here to illustrate how you should configure this plugin from main
 * RC configuration file.
 */
$config = array();
$config['keyboard_shortcuts_ng'] = array();



/*
 * Enable/disable predefined keyboard shortcut association maps
 *
 * These association maps are available for rapid consumption - enable and start using.
 * (Multiple can be enabled at once, but shortcuts may override one another.)
 *
 * Below you can find definitions for this plugin, which can be further altered manually.
 */
$config['keyboard_shortcuts_ng']['association_map_ng_awesome_enabled']        = true;  // Recommended by author of this plugin
$config['keyboard_shortcuts_ng']['association_map_os_native_linux_enabled']   = true;  // Linux native shortcuts
$config['keyboard_shortcuts_ng']['association_map_os_native_macosx_enabled']  = false; // TODO
$config['keyboard_shortcuts_ng']['association_map_os_native_windows_enabled'] = false; // TODO
$config['keyboard_shortcuts_ng']['association_map_compat_rc_plugin_enabled']  = false; // Mimic behaviour or RC plugin 'keyboard_shortcuts'



/*
 * Association map - actions vs. keyboard shortcuts vs. contexts configuration array
 *
 * Rules:
 * - more specific setting (context-wise) overrides less-specific one
 * - each context can have dedicated shortcut defined for that action (this use is discouraged!)
 * - if context does not have shortcut defined, main shortcut is used
 * - supplemental keys are detected in alphabetical order ('alt ctrl o' is ok, where 'ctrl alt o' is unacceptable)
 * - shortcuts with only supplemental keys are not supported ('ctrl alt' is not ok, but 'ctrl alt x' is)
 * - final keys are all lower case. Specify as 'shift s' of you mean that.
 *
 * Supported supplemental keys:
 * - 'alt'
 * - 'ctrl'
 * - 'meta'
 * - 'shift'
 *
 * Supported contexts:
 * - 'list'
 * - 'preview'
 * - 'show'
 * - 'compose'
 *
 *
 * Configuration entry keys description:
 * - shortcut:    key combination specification (string for single, array for multiple)
 * - context:     valid contexts for this key combo (string for single, array for multiple)
 * - action:      action ID for this key combo (directly translates to function name in keyboard_shortcuts_ng_actions.js)
 * TODO
 * - action_args: arguments to pass to given action (implementation pending)
 */

// Tmp helpers for more compact configuration specification below:
$_ctxLP  = array('list', 'preview');
$_ctxLPS = array('list', 'preview', 'show');
$_ctxC   = 'compose';

// Init empty arrays
$config['keyboard_shortcuts_ng']['association_map_ng_awesome']        = array(); // Recommended by this plugin author
$config['keyboard_shortcuts_ng']['association_map_os_native_linux']   = array(); // Linux native shortcuts
$config['keyboard_shortcuts_ng']['association_map_os_native_macosx']  = array(); // TODO
$config['keyboard_shortcuts_ng']['association_map_os_native_windows'] = array(); // TODO
$config['keyboard_shortcuts_ng']['association_map_compat_rc_plugin']  = array(); // Compatibilities



/*
 * General actions
 */
$_ksDef = array(
        'shortcut' => array('shift ?'),
            'context' => $_ctxLP,
                'action' => 'ks_ng_help_display',
);
$config['keyboard_shortcuts_ng']['association_map_ng_awesome'][]        = $_ksDef;
$config['keyboard_shortcuts_ng']['association_map_os_native_linux'][]   = array_merge($_ksDef, array('shortcut'=>'f1'));
$config['keyboard_shortcuts_ng']['association_map_compat_rc_plugin'][]  = array_merge($_ksDef, array('shortcut'=>'?'));



$_ksDef = array(
        'shortcut' => 'ctrl r',
            'context' => $_ctxLPS,
                'action' => 'checkmail',
);
$config['keyboard_shortcuts_ng']['association_map_ng_awesome'][]        = $_ksDef;
$config['keyboard_shortcuts_ng']['association_map_os_native_linux'][]   = array_merge($_ksDef, array('shortcut'=>'ctrl r'));
$config['keyboard_shortcuts_ng']['association_map_compat_rc_plugin'][]  = array_merge($_ksDef, array('shortcut'=>'u'));



$_ksDef = array(
        'shortcut' => array('s', 'ctrl f', 'ctrl k'),
            'context' => $_ctxLPS,
                'action' => 'searchbox_focus',
);
$config['keyboard_shortcuts_ng']['association_map_ng_awesome'][]        = $_ksDef;
$config['keyboard_shortcuts_ng']['association_map_os_native_linux'][]   = array_merge($_ksDef, array('shortcut'=>'ctrl f'));
$config['keyboard_shortcuts_ng']['association_map_compat_rc_plugin'][]  = array_merge($_ksDef, array('shortcut'=>'s'));



/*
 * Mailbox/Folder list actions
 */
$_ksDef = array(
        'shortcut' => 'q',
            'context' => $_ctxLP,
                'action' => 'mailboxlist_up',
);
$config['keyboard_shortcuts_ng']['association_map_ng_awesome'][]        = $_ksDef;



$_ksDef = array(
        'shortcut' => 'a',
            'context' => $_ctxLP,
                'action' => 'mailboxlist_down',
);
$config['keyboard_shortcuts_ng']['association_map_ng_awesome'][]        = $_ksDef;



/*
 * Message list actions
 */
$_ksDef = array(
        'shortcut' => 'ctrl a',
            'context' => $_ctxLP,
                'action' => 'messagelist_select_all_on_page',
);
$config['keyboard_shortcuts_ng']['association_map_ng_awesome'][]        = $_ksDef;
$config['keyboard_shortcuts_ng']['association_map_os_native_linux'][]   = $_ksDef;
$config['keyboard_shortcuts_ng']['association_map_compat_rc_plugin'][]  = array_merge($_ksDef, array('shortcut'=>array('a', 'shift a')));



$_ksDef = array(
        'shortcut' => 'ctrl shift a',
            'context' => $_ctxLP,
                'action' => 'messagelist_select_all',
);
$config['keyboard_shortcuts_ng']['association_map_ng_awesome'][]        = $_ksDef;
$config['keyboard_shortcuts_ng']['association_map_os_native_linux'][]   = $_ksDef;



$_ksDef = array(
        'shortcut' => 't',
            'context' => $_ctxLP,
                'action' => 'previewpane_toggle_visibility',
);
$config['keyboard_shortcuts_ng']['association_map_ng_awesome'][]        = $_ksDef;
//$config['keyboard_shortcuts_ng']['association_map_os_native_linux'][]   = $_ksDef;
$config['keyboard_shortcuts_ng']['association_map_compat_rc_plugin'][]  = $_ksDef;



/*
 * Message list actions - threaded view
 */
$_ksDef = array(
        'shortcut' => 'shift c',
            'context' => $_ctxLP,
                'action' => 'threads_collapse_all',
);
$config['keyboard_shortcuts_ng']['association_map_ng_awesome'][]        = $_ksDef;
//$config['keyboard_shortcuts_ng']['association_map_os_native_linux'][]   = $_ksDef;
$config['keyboard_shortcuts_ng']['association_map_compat_rc_plugin'][]  = $_ksDef;



$_ksDef = array(
        'shortcut' => 'shift e',
            'context' => $_ctxLP,
                'action' => 'threads_expand_all',
);
$config['keyboard_shortcuts_ng']['association_map_ng_awesome'][]        = $_ksDef;
//$config['keyboard_shortcuts_ng']['association_map_os_native_linux'][]   = $_ksDef;
$config['keyboard_shortcuts_ng']['association_map_compat_rc_plugin'][]  = $_ksDef;



$_ksDef = array(
        'shortcut' => 'shift u',
            'context' => $_ctxLP,
                'action' => 'threads_expand_unread',
);
$config['keyboard_shortcuts_ng']['association_map_ng_awesome'][]        = $_ksDef;
//$config['keyboard_shortcuts_ng']['association_map_os_native_linux'][]   = $_ksDef;
$config['keyboard_shortcuts_ng']['association_map_compat_rc_plugin'][]  = $_ksDef;



/*
 * Message actions
 */
$_ksDef = array(
        'shortcut' => 'c',
            'context' => $_ctxLPS,
                'action' => 'message_compose',
);
$config['keyboard_shortcuts_ng']['association_map_ng_awesome'][]        = $_ksDef;
//$config['keyboard_shortcuts_ng']['association_map_os_native_linux'][]   = $_ksDef;
$config['keyboard_shortcuts_ng']['association_map_compat_rc_plugin'][]  = $_ksDef;



$_ksDef = array(
        'shortcut' => 'f',
            'context' => $_ctxLPS,
                'action' => 'message_forward',
);
$config['keyboard_shortcuts_ng']['association_map_ng_awesome'][]        = $_ksDef;
//$config['keyboard_shortcuts_ng']['association_map_os_native_linux'][]   = $_ksDef;
$config['keyboard_shortcuts_ng']['association_map_compat_rc_plugin'][]  = $_ksDef;



$_ksDef = array(
        'shortcut' => 'ctrl p',
            'context' => $_ctxLPS,
                'action' => 'message_print',
);
$config['keyboard_shortcuts_ng']['association_map_ng_awesome'][]        = $_ksDef;
$config['keyboard_shortcuts_ng']['association_map_os_native_linux'][]   = $_ksDef;
$config['keyboard_shortcuts_ng']['association_map_compat_rc_plugin'][]  = array_merge($_ksDef, array('shortcut'=>'p'));



$_ksDef = array(
        'shortcut' => 'r',
            'context' => $_ctxLPS,
                'action' => 'message_reply',
);
$config['keyboard_shortcuts_ng']['association_map_ng_awesome'][]        = $_ksDef;
//$config['keyboard_shortcuts_ng']['association_map_os_native_linux'][]   = $_ksDef;
$config['keyboard_shortcuts_ng']['association_map_compat_rc_plugin'][]  = $_ksDef;



$_ksDef = array(
        'shortcut' => 'shift r',
            'context' => $_ctxLPS,
                'action' => 'message_reply_all',
);
$config['keyboard_shortcuts_ng']['association_map_ng_awesome'][]        = $_ksDef;
//$config['keyboard_shortcuts_ng']['association_map_os_native_linux'][]   = $_ksDef;
$config['keyboard_shortcuts_ng']['association_map_compat_rc_plugin'][]  = $_ksDef;



// This one is hardcoded for HTML message composer - iframe/tinymce limitation
$_ksDef = array(
        'shortcut' => 'ctrl enter',
            'context' => $_ctxC,
                'action' => 'message_send',
);
$config['keyboard_shortcuts_ng']['association_map_ng_awesome'][]        = $_ksDef;
$config['keyboard_shortcuts_ng']['association_map_os_native_linux'][]   = $_ksDef;
$config['keyboard_shortcuts_ng']['association_map_compat_rc_plugin'][]  = $_ksDef;



$_ksDef = array(
        'shortcut' => 'm',
            'context' => $_ctxLP,
                'action' => 'message_toggle_flag_read',
);
$config['keyboard_shortcuts_ng']['association_map_ng_awesome'][]        = $_ksDef;
//$config['keyboard_shortcuts_ng']['association_map_os_native_linux'][]   = $_ksDef;
$config['keyboard_shortcuts_ng']['association_map_compat_rc_plugin'][]  = $_ksDef;



$_ksDef = array(
        'shortcut' => 'k',
            'context' => $_ctxLPS,
                'action' => 'message_view_next',
);
$config['keyboard_shortcuts_ng']['association_map_ng_awesome'][]        = $_ksDef;
$config['keyboard_shortcuts_ng']['association_map_os_native_linux'][]   = $_ksDef;
$config['keyboard_shortcuts_ng']['association_map_compat_rc_plugin'][]  = $_ksDef;



$_ksDef = array(
        'shortcut' => 'j',
            'context' => $_ctxLPS,
                'action' => 'message_view_prev',
);
$config['keyboard_shortcuts_ng']['association_map_ng_awesome'][]        = $_ksDef;
$config['keyboard_shortcuts_ng']['association_map_os_native_linux'][]   = $_ksDef;
$config['keyboard_shortcuts_ng']['association_map_compat_rc_plugin'][]  = $_ksDef;



/*
 * // These are already implemented in Roundcube itself
 *
 * $_ksDef = array(
 *         'shortcut' => 'delete',
 *             'context' => $_ctxLPS,
 *                 'action' => 'message_delete_ask',
 * );
 * $config['keyboard_shortcuts_ng']['association_map_ng_awesome'][]        = $_ksDef;
 * $config['keyboard_shortcuts_ng']['association_map_os_native_linux'][]   = $_ksDef;
 * $config['keyboard_shortcuts_ng']['association_map_compat_rc_plugin'][]  = $_ksDef;
 */



$_ksDef = array(
        'shortcut' => 'shift delete',
            'context' => $_ctxLPS,
                'action' => 'message_delete_noask',
);
//$config['keyboard_shortcuts_ng']['association_map_ng_awesome'][]        = $_ksDef; // These are already implemented in Roundcube itself
//$config['keyboard_shortcuts_ng']['association_map_os_native_linux'][]   = $_ksDef; // These are already implemented in Roundcube itself
$config['keyboard_shortcuts_ng']['association_map_compat_rc_plugin'][]  = array_merge($_ksDef, array('shortcut'=>'d'));



/*
 * Association map - local configuration merge strategy
 *
 * If deployment configuration specifies (additional) configuration for this
 * plugin, this setting determines how that configuration is merged with
 * plugin defaults.
 *
 * Possible options:
 * - 'merge'   - use original defaults and override with local changes (default)
 * - 'replace' - replace association map entirely
 */
$config['keyboard_shortcuts_ng']['association_map_merge_strategy'] = 'merge';



return $config;
