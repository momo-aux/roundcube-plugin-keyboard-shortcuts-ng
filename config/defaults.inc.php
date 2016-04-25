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

$config['keyboard_shortcuts_ng']['association_map'] = array(
    // General actions
    array(
        'shortcut' => array('f1', 'shift ?'),
            'context' => $_ctxLP,
                'action' => 'ks_ng_help_display',
    ),
    array(
        'shortcut' => 'ctrl r',
            'context' => $_ctxLPS,
                'action' => 'checkmail',
    ),
    array(
        'shortcut' => array('ctrl f', 'ctrl k'),
            'context' => $_ctxLPS,
                'action' => 'searchbox_focus',
    ),


    // Mailbox/Folder list actions
    array(
        'shortcut' => 'q',
            'context' => $_ctxLP,
                'action' => 'mailboxlist_up',
    ),
    array(
        'shortcut' => 'a',
            'context' => $_ctxLP,
                'action' => 'mailboxlist_down',
    ),


    // Messagelist actions
    array(
        'shortcut' => 'ctrl a',
            'context' => $_ctxLP,
                'action' => 'messagelist_select_all_on_page',
    ),
    array(
        'shortcut' => 'ctrl shift a',
            'context' => $_ctxLP,
                'action' => 'messagelist_select_all',
    ),
    array(
        'shortcut' => 't',
            'context' => $_ctxLP,
                'action' => 'previewpane_toggle_visibility',
    ),
    array(
        'shortcut' => 'shift c',
            'context' => $_ctxLP,
                'action' => 'threads_collapse_all',
    ),
    array(
        'shortcut' => 'shift e',
            'context' => $_ctxLP,
                'action' => 'threads_expand_all',
    ),
    array(
        'shortcut' => 'shift u',
            'context' => $_ctxLP,
                'action' => 'threads_expand_unread',
    ),

    // Message actions
    array(
        'shortcut' => 'c',
            'context' => $_ctxLPS,
                'action' => 'message_compose',
    ),
    array(
        'shortcut' => 'f',
            'context' => $_ctxLPS,
                'action' => 'message_forward',
    ),
    array(
        'shortcut' => 'ctrl p',
            'context' => $_ctxLPS,
                'action' => 'message_print',
    ),
    array(
        'shortcut' => 'r',
            'context' => $_ctxLPS,
                'action' => 'message_reply',
    ),
    array(
        'shortcut' => 'shift r',
            'context' => $_ctxLPS,
                'action' => 'message_reply_all',
    ),

    // This one is hardcoded for HTML message composer - iframe/tinymce limitation
    array(
        'shortcut' => 'ctrl enter',
            'context' => $_ctxC,
                'action' => 'message_send',
    ),

    array(
        'shortcut' => 'm',
            'context' => $_ctxLP,
                'action' => 'message_toggle_flag_read',
    ),
    array(
        'shortcut' => 'k',
            'context' => $_ctxLPS,
                'action' => 'message_view_next',
    ),
    array(
        'shortcut' => 'j',
            'context' => $_ctxLPS,
                'action' => 'message_view_prev',
    ),

/*
 * // These are already implemented in Roundcube itself
 *    array(
 *        'shortcut' => 'delete',
 *            'context' => $_ctxLPS,
 *                'action' => 'message_delete_ask',
 *    ),
 *    array(
 *        'shortcut' => 'shift delete',
 *            'context' => $_ctxLPS,
 *                'action' => 'message_delete_noask',
 *    ),
 */
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
