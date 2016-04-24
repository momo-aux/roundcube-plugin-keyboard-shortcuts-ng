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



$config = array();
$config['keyboard_shortcuts_ng'] = array();



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
$config['keyboard_shortcuts_ng']['association_map'] = array(
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
