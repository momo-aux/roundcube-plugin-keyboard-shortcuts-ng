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
     * RC instance
     */
    protected $rc = NULL;



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
     * Association map configuration - actions vs. keyboard shortcuts vs. contexts configuration array
     *
     * See config/defaults.inc.php for details
     */
    protected $association_map = NULL;



    /*
     * Initialization
     *
     */
    function init()
    {
        // This plugin is required
        $this->require_plugin('jqueryui');

        // Get RC instance
        $this->rc = rcmail::get_instance();

        // Read configuration defaults
        $configAll             = require __DIR__ .'/config/defaults.inc.php';
        $configPlugin          = $configAll['keyboard_shortcuts_ng'];
        $this->association_map = $configPlugin['association_map'];

        // Merge local configuration overrides
        $configPluginLocal = $this->rc->config->get('keyboard_shortcuts_ng', NULL);
        if (NULL != $configPluginLocal) {
            if (isset($configPluginLocal['association_map'])) {
                // We have local overrides, ql

                // Get merge strategy
                if (isset($configPluginLocal['association_map_merge_strategy'])) {
                    $configMergeStrategy = $configPluginLocal['association_map_merge_strategy'];
                } else {
                    $configMergeStrategy = $configPlugin['association_map_merge_strategy'];
                }

                switch ($configMergeStrategy) {
                    case "merge":
                        $this->association_map = array_merge($this->association_map, $configPluginLocal['association_map']);
                        break;

                    case "replace":
                        $this->association_map = $configPluginLocal['association_map'];
                        break;

                    default:
                        throw new Exception("Unsupported association map merge strategy: $configMergeStrategy");
                }
            }
        }

        // Pass configuration to frontend
        $this->rc->output->set_env('ks_ng_supported_contexts', $this->supported_contexts);
        $this->rc->output->set_env('ks_ng_config',   $this->association_map);

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
