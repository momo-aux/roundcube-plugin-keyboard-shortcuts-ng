/**
 * Keyboard Shortcuts Actions container
 *
 * Object that contains all actions available to associate with keyboard shortcuts
 */
var ks_ng_actions = new Object();



/**
 * Here follow defitions of actions
 */





/*
 *******************************************************************************
 *
 * General actions
 *
 *******************************************************************************
 */
ks_ng_actions.ks_ng_help_display = function (context, rcmail)
{
    ks_ng_help_display();
    return false;
}



ks_ng_actions.checkmail = function (context, rcmail)
{
    rcmail.command('checkmail');
    return false;
}



ks_ng_actions.searchbox_focus = function (context, rcmail)
{
//ks_ng_debug("Executing action: " + arguments.callee.name);
    $('#quicksearchbox').focus();
    $('#quicksearchbox').select();
    return false;
}





/*
 *******************************************************************************
 *
 * List view actions
 *
 *******************************************************************************
 */
ks_ng_actions.messagelist_select_all_on_page = function (context, rcmail)
{
    rcmail.command('select-all', 'page');
    return false;
}



ks_ng_actions.messagelist_select_all = function (context, rcmail)
{
    rcmail.command('select-all', '');
    return false;
}



ks_ng_actions.previewpane_toggle_visibility = function (context, rcmail)
{
    $('#mailpreviewtoggle')[0].click();
    return false;
}



ks_ng_actions.threads_collapse_all = function (context, rcmail)
{
    rcmail.command('collapse-all');
    return false;
}



ks_ng_actions.threads_expand_all = function (context, rcmail)
{
    rcmail.command('expand-all');
    return false;
}



ks_ng_actions.threads_expand_unread = function (context, rcmail)
{
    rcmail.command('expand-unread');
    return false;
}





/*
 *******************************************************************************
 *
 * Message actions
 *
 *******************************************************************************
 */
ks_ng_actions.message_compose = function (context, rcmail)
{
    rcmail.command('compose');
    return false;
}



ks_ng_actions.message_delete = function (context, rcmail)
{
    rcmail.command('delete');
    return false;
}



ks_ng_actions.message_forward = function (context, rcmail)
{
//    if (('list' == context) && (rcmail.message_list.selection.length != 1)) {
//        return false;
//    }
    rcmail.command('forward');
    return false;
}



ks_ng_actions.message_print = function (context, rcmail)
{
    if (('list' == context) && (rcmail.message_list.selection.length != 1)) {
        return false;
    }
    rcmail.command('print');
    return false;
}



ks_ng_actions.message_reply = function (context, rcmail)
{
    if (('list' == context) && (rcmail.message_list.selection.length != 1)) {
        return false;
    }
    rcmail.command('reply');
    return false;
}



ks_ng_actions.message_reply_all = function (context, rcmail)
{
    if (('list' == context) && (rcmail.message_list.selection.length != 1)) {
        return false;
    }
    rcmail.command('reply-all');
    return false;
}



ks_ng_actions.message_send = function (context, rcmail)
{
    // Fuse: do not act/send email if focus is not on compose body
    if (!$("*:focus").is("#composebody")) {
      return true;
    }

    $('.button.send').click();
    return false;
}



ks_ng_actions.message_view_next = function (context, rcmail)
{
    // FIXME next page in list mode, next message in preview mode
    rcmail.command('nextmessage');
    return false;
}



ks_ng_actions.message_view_prev = function (context, rcmail)
{
    // FIXME prev page in list mode, prev message in preview mode
    rcmail.command('previousmessage');
    return false;
}



ks_ng_actions.message_toggle_flag_read = function (context, rcmail)
{
    var uid = rcmail.message_list.get_selection();

    if (uid && uid.length > 0) {
        var mid = rcmail.message_list.rows[uid[0]].id;
        if ($('tr#' + mid).hasClass('unread')) {
            rcmail.command('mark', 'read');
        } else {
            rcmail.command('mark', 'unread');
        }
        return false;
    } else {
        return true;
    }
}
