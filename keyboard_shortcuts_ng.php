<?php
/**
 * Roundcube plugin - Keyboard Shortcuts NG
 *
 * Enables some common tasks to be executed with keyboard shortcuts
 *
 * @author Bostjan Skufca
 * @licence GNU AGPL
 *
 * Inspired by:
 * - Patrik Kullman / Roland 'rosali' Liebl / Cor Bosman <roundcube@wa.ter.net>
 * - https://github.com/corbosman/keyboard_shortcuts
 * - https://plugins.roundcube.net/packages/cor/keyboard_shortcuts
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
        // This plugin is required
        $this->require_plugin('jqueryui');

        // Pass configuration to JS/frontend
        $rcmail->output->set_env('ks_ng_supported_contexts', $this->supported_contexts);
        $rcmail->output->set_env('ks_ng_config',   $this->config);

        // Only load this plugin once user is logged in and when newuserdialog is complete
        if ($_SESSION['username'] && empty($_SESSION['plugin.newuserdialog'])) {
            $this->include_stylesheet('keyboard_shortcuts_ng.css');
            $this->include_script('keyboard_shortcuts_ng_actions.js');
            $this->include_script('keyboard_shortcuts_ng.js');
        }

        // Enable translations
        $this->add_texts('localization', true);
    }
}
