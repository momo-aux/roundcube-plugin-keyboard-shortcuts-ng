/**
 * Roundcube plugin - Keyboard Shortcuts NG
 *
 * Configurable keyboard shortcuts for Roundcube Webmail
 *
 * @author Bostjan Skufca <bostjan {at} a2o.si>
 * @author Teon d.o.o.
 * @licence GNU AGPL
 *
 * Inspired by:
 * - Patrik Kullman / Roland 'rosali' Liebl / Cor Bosman <roundcube@wa.ter.net>
 * - https://github.com/corbosman/keyboard_shortcuts
 * - https://plugins.roundcube.net/packages/cor/keyboard_shortcuts
 */
$(function() {

    // initialize a dialog window
    $('#keyboard_shortcuts_help').dialog({
        autoOpen: false,
        draggable: true,
        modal: false,
        resizable: false,
        width: 750,
        title: rcmail.gettext("keyboard_shortcuts.keyboard_shortcuts")
    });



    /*
     * Set up main keyboard event listener
     *
     * We are using keydown event instead of keypress (or keyup) to be able
     * and override browser-specific shortcuts. Keypress does not capture ctrl+key
     * events, and keyup is already too late for those events.
     */
    $(document).keydown(
        function (e)
        {
//ks_ng_debug('event=keydown')
            return ks_ng_keypress_event_handler(e);
        }
    );



    /**
     * Workaround: when HTML message composing is in use, ctrl+enter shortcut needs special handling
     *
     * If we hit ctrl-enter, and we're composing, and we have focus (in the HTML
     * editor iframe), then send email. The catch is to hook into TinyMCE event handlers,
     * not into parent iframe.
     */
    $(window.setTimeout(function() {

        // Do not enable this in non-compose tasks
        if (rcmail.env.action != 'compose') {
            return;
        }

        // Capture TinyMCE keyboard events as first event handler
        rcmail.editor.editor.on('keydown', function(e) {

            if (rcmail.env.action == 'compose' && (e.which == 13 || e.which == 10) && e.ctrlKey) {
                return ks_ng_keypress_event_handler(e, 'compose', 'ctrl enter', false, {'is_html_compose':true});
                // These are not needed if false is returned.
                //e.preventDefault();
                //e.stopPropagation();
            }
        }, true);   // True to insert it at the beginning of event handling chain

    }, 1000));



    /**
     * Workaround: if focus is on an empty preview pane, this iframe must pass on keyboard events.
     */
    $(window.setTimeout(function() {
        $('#messagecontframe').contents().keydown(function (e) {
            return ks_ng_keypress_event_handler(e, 'preview');
        });
    }, 1000));



    /*
     * Keypress event handler
     *
     * @param    eventObj      Keypress event object
     * @param    null|string   Context     (null to detect, string to force)
     * @param    null|string   Keypress ID (null to detect from event itself, string to force)
     * @param    bool          Check if execution is appropriate (disable if any text field has context and such)
     * @param    Object        Action arguments
     */
    function ks_ng_keypress_event_handler (e, cur_context=null, keypress_id=null, check_if_appropriate_situation=true, action_args={})
    {
//ks_ng_debug('----');
//ks_ng_debug('arg ctxt: ' + cur_context);
//ks_ng_debug('arg k_id: ' + keypress_id);
//ks_ng_debug('arg chk:  ' + check_if_appropriate_situation);

        // Check if we are in supported context, and return if not
        // See .php comments for details.
        // (only autodetermine if not passed as an function argument)
        if (null == cur_context) {
            cur_context = rcmail.env.action;
        }
        if ('' == cur_context) {
            cur_context = 'list';
        }

        supported_contexts = rcmail.env.ks_ng_supported_contexts;
        if (typeof supported_contexts[cur_context] == 'undefined') {
            ks_ng_error('Unsupported context: ' + cur_context);
            return true;
        }


        // Get supporting objects
        if (cur_context == 'preview') {
            rcmailobj = window.parent.rcmail;
            windowobj = window.parent;
        } else {
            rcmailobj = rcmail;
            windowobj = window;
        }


        // Generate appropriate keypress id, to be searched in shortcut
        // config array for appropriate action. If partial keypress is detected
        // (i.e. ctrl key only, before final character key is pressed), ignore it.
        // (only autodetermine if not passed as an function argument)
        if (null == keypress_id) {
            keypress_id = ks_ng_generate_keypress_id(e);
        }
        if (null == keypress_id) {
//ks_ng_debug('partial keypress, ignoring');
            return true;
        }


        // Do not enable shortcuts if current focus is on any form element (input, textarea)
        // that expects typed input. Exception is ctrl+enter shortcut for sending email.
        // (skip this check if explicitly told so by function argument)
        if (true == check_if_appropriate_situation) {
            if ($("*:focus").is("textarea, input")) {
                if ($("*:focus").is("textarea") && 'ctrl enter' == keypress_id) {
                    // Continue processing below
                } else {
                    return true;
                }
            }
        }
//ks_ng_debug('ctxt: ' + cur_context);
//ks_ng_debug('k_id: ' + keypress_id);
//ks_ng_debug('chk:  ' + check_if_appropriate_situation);


        // Find appropriate action
        ks_ng_association_hash = rcmail.env.ks_ng_association_hash;
        action_data = ks_ng_find_action(keypress_id, cur_context, ks_ng_association_hash);
        if (null == action_data) {
//ks_ng_debug('no action defined for this keypress_id: ' + keypress_id);
            // Return true so that the event processing continues
            return true;
        }
//ks_ng_debug('matching action found: ' + action_data.id);

        // Merge action arguments - the ones passed by JS override those provided by PHP/associative_hash
        action_args_to_pass = $.extend({}, action_args, action_data.args);


        // Execute found action
        return ks_ng_exec_action(action_data.id, cur_context, rcmailobj, windowobj, action_args_to_pass);
    }
});



/**
 * Generate keypress ID
 *
 * Keypress ID is unique for each valid keypress combination and serves for matching
 * keypresses to corresponding configured actions.
 *
 * @param    object   Keypress event
 * @return   string   Keypress ID
 */
function ks_ng_generate_keypress_id (e)
{
    id = '';

    // Detect additional supported keys pressed
    altKey = false;
    if (e.altKey) {
        altKey = true;
        id += 'alt ';
    }

    ctrlKey = false;
    if (e.ctrlKey) {
        ctrlKey = true;
        id += 'ctrl ';
    }

    metaKey = false;
    if (e.metaKey) {
        metaKey = true;
        id += 'meta ';
    }

    shiftKey = false;
    if (e.shiftKey) {
        shiftKey = true;
        id += 'shift ';
    }


    // Get key code to evaluate
    keycode = e.which
//ks_ng_debug('keycode: ' + keycode);


    // Key that is actually pressed - evaluate
    switch (keycode) {
        case 8:
            id += 'backspace'; break;

        case 10:
        case 13:
            id += 'enter'; break;

        case 27:
            id += 'esc';  break;

        case 37:
            id += 'left';  break;
        case 38:
            id += 'up';    break;
        case 39:
            id += 'right'; break;
        case 40:
            id += 'down';  break;

        case 46:
            id += 'delete'; break;

        case 186:
            id += ';';     break;
        case 187:
            id += '=';     break;
        case 188:
            id += ',';     break;
        case 189:
            id += '?';     break;
        case 190:
            id += '.';     break;
        case 191:
            id += '-';     break;
        case 192:
//            id += '~';     break;
            id += '`';     break;

        case 219:
            id += '[';     break;
        case 220:
            id += '\\';    break;
        case 221:
            id += ']';     break;
        case 222:
            id += "'";     break;
        case 219:
            id += '[';     break;

        default:
            // f1-f12
            if ((112 <= keycode) && (keycode <= 123)) {
                id += 'f' + (keycode - 111)
                break;
            }

            // characters & numbers
            if (
                ((48  <= keycode) && (keycode <=  57)) // numbers
                ||
                ((65  <= keycode) && (keycode <=  90)) // characters
            ) {
                id += String.fromCharCode(keycode).toLowerCase();
                break;
            }

            // Unsupported keypress, or partial keypress
//ks_ng_debug('keycode out of acceptable range: ' + keycode);
            return null;
    }

    id = id.trim();
//ks_ng_debug('final detected keypress: ' + id);

    return id;
}



/**
 * Search through given configuration array and find the most specific action for given keypress_id
 *
 * TODO: This algorithm is in DIRE need of optimization because 3 loops per keypress
 * is an overkill.
 *
 * @param    string        Keypress ID string
 * @param    string        Context ID
 * @param    array         Configuration hash (array of 'keypress_id (context)' => array('action' => 'actionName') data)
 * @return   string|null   Action ID, or not
 */
function ks_ng_find_action (search_keypress_id, search_context, association_hash)
{
    search_hash_key = search_keypress_id + ' (' + search_context + ')'
    if (association_hash.hasOwnProperty(search_hash_key)) {
        action_data = {
            id:   association_hash[search_hash_key].action_id,
            args: association_hash[search_hash_key].action_args,
        };
//ks_ng_debug('Found action: ' + action_id);
        return action_data;
    }

//ks_ng_debug('No keyboard association defined for: ' + search_hash_key);
    // Shortcut was not found
    return null;
}



/**
 * Execute given action
 *
 * We provide one layer of indirection for ? purpose
 *
 * @param    string        Action ID to execute
 * @param    string        Context ID
 * @param    array         rcmail instance
 * @param    Object        action arguments (if specific args need to be passed to action)
 * @return   ?
 */
function ks_ng_exec_action (action_id, context, rcmailobj, windowobj, action_args)
{
    // ks_ng_actions is defined in another file:   ./keyboard_shortcuts_actions.js

    // Check if even defined
    if (typeof ks_ng_actions[action_id] != 'function') {
        ks_ng_error('Action not implemented in ks_ng_actions object: ' + action_id);
        return false;
    }

    // Call that method
    return ks_ng_actions[action_id](context, rcmailobj, windowobj, action_args);
}



/*
 * Open the display-help dialog
 */
function ks_ng_help_display () {
    $('#keyboard_shortcuts_help').dialog('open');
}
function keyboard_shortcuts_show_help () {
    ks_ng_help_display();
}



/**
 * Helper fuction: debugging output
 */
function ks_ng_debug (msg)
{
    console.log('KSNG: ' + msg);
}



/**
 * Helper fuction: error output
 */
function ks_ng_error (msg)
{
    console.log('ERROR in plugin keyboard_shortcuts_ng: ' + msg);
}
