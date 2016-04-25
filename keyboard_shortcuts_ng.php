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
     * Association hash
     *
     * Optimized config hash, for faster lookups on frontend
     *
     * See config/defaults.inc.php for details
     */
    protected $association_hash = NULL;



    /*
     * Initialization
     *
     */
    public function init ()
    {
        // This plugin is required
        $this->require_plugin('jqueryui');

        // Get RC instance
        $this->rc = rcmail::get_instance();

        // Read configuration defaults, merge in local overrides
        $configAll         = require __DIR__ .'/config/defaults.inc.php';
        $configPlugin      = $configAll['keyboard_shortcuts_ng'];
        $configPluginLocal = $this->rc->config->get('keyboard_shortcuts_ng', array());
        $configPlugin      = array_merge($configPlugin, $configPluginLocal);

        // Merge enabled built-it association maps
        $this->association_map = array();
        foreach ($configPlugin as $configKey => $configVal) {
            if (!preg_match('/^(association_map_.+)_enabled$/', $configKey, $m)) {
                continue;
            }
            $mapId = $m[1];
            if (true == $configVal) {
                $this->association_map = array_merge($this->association_map, $configPlugin[$mapId]);
            }
        }

        // Merge local association map overrides
        if (isset($configPlugin['association_map'])) {

            switch ($configPlugin['association_map_merge_strategy']) {
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

        // Now that config is complete, generate association hash
        $this->regenerate_association_hash();

        // Pass configuration to frontend
        $this->rc->output->set_env('ks_ng_supported_contexts', $this->supported_contexts);
        $this->rc->output->set_env('ks_ng_association_hash',   $this->association_hash);

        // Only load this plugin once user is logged in and when newuserdialog is complete
        if ($_SESSION['username'] && empty($_SESSION['plugin.newuserdialog'])) {
            $this->include_stylesheet('keyboard_shortcuts_ng.css');
            $this->include_script('keyboard_shortcuts_ng_actions.js');
            $this->include_script('keyboard_shortcuts_ng.js');
        }

        // Enable translations
        $this->add_texts('localization', true);
    }




    /*
     * Regenerate association hash from configuration
     *
     * Builds array/hash for one-dimensional searching.
     */
    public function regenerate_association_hash ()
    {
        $newAssocHash = array();

        foreach ($this->association_map as $action => $aData) {
            $shortcuts   = (is_string($aData['shortcut']) ? array($aData['shortcut']) : $aData['shortcut']);
            $contexts    = (is_string($aData['context'])  ? array($aData['context'])  : $aData['context']);
            $action_data = array(
                'action_id'   => $aData['action'],
                'action_args' => (isset($aData['action_args']) ? $aData['action_args']  : array()),
            );

            foreach ($shortcuts as $shortcut) {
                foreach ($contexts as $context) {
                    $hashEntryKey = "$shortcut ($context)";
                    $newAssocHash[$hashEntryKey] = $action_data;
                }
            }
        }

        $this->association_hash = $newAssocHash;
    }
}
