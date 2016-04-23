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
ks_ng_actions.ks_ng_help_display = function (context, rcmailobj, windowobj)
{
    ks_ng_help_display();
    return false;
}



ks_ng_actions.checkmail = function (context, rcmailobj, windowobj)
{
    rcmailobj.command('checkmail');
    return false;
}



ks_ng_actions.searchbox_focus = function (context, rcmailobj, windowobj)
{
//ks_ng_debug("Executing action: " + arguments.callee.name);
    windowobj.$('#quicksearchbox').focus();
    windowobj.$('#quicksearchbox').select();
    return false;
}





/*
 *******************************************************************************
 *
 * List view actions
 *
 *******************************************************************************
 */
ks_ng_actions.messagelist_select_all_on_page = function (context, rcmailobj, windowobj)
{
    rcmailobj.command('select-all', 'page');
    return false;
}



ks_ng_actions.messagelist_select_all = function (context, rcmailobj, windowobj)
{
    rcmailobj.command('select-all', '');
    return false;
}



ks_ng_actions.previewpane_toggle_visibility = function (context, rcmailobj, windowobj)
{
    $('#mailpreviewtoggle')[0].click();
    return false;
}



ks_ng_actions.threads_collapse_all = function (context, rcmailobj, windowobj)
{
    rcmailobj.command('collapse-all');
    return false;
}



ks_ng_actions.threads_expand_all = function (context, rcmailobj, windowobj)
{
    rcmailobj.command('expand-all');
    return false;
}



ks_ng_actions.threads_expand_unread = function (context, rcmailobj, windowobj)
{
    rcmailobj.command('expand-unread');
    return false;
}





/*
 *******************************************************************************
 *
 * Message actions
 *
 *******************************************************************************
 */
ks_ng_actions.message_compose = function (context, rcmailobj, windowobj)
{
    rcmailobj.command('compose');
    return false;
}



ks_ng_actions.message_delete = function (context, rcmailobj, windowobj)
{
    rcmailobj.command('delete');
    return false;
}



ks_ng_actions.message_forward = function (context, rcmailobj, windowobj)
{
//    if (('list' == context) && (rcmailobj.message_list.selection.length != 1)) {
//        return false;
//    }
    rcmailobj.command('forward');
    return false;
}



ks_ng_actions.message_print = function (context, rcmailobj, windowobj)
{
    if (('list' == context) && (rcmailobj.message_list.selection.length != 1)) {
        return false;
    }
    rcmailobj.command('print');
    return false;
}



ks_ng_actions.message_reply = function (context, rcmailobj, windowobj)
{
    if (('list' == context) && (rcmailobj.message_list.selection.length != 1)) {
        return false;
    }
    rcmailobj.command('reply');
    return false;
}



ks_ng_actions.message_reply_all = function (context, rcmailobj, windowobj)
{
    if (('list' == context) && (rcmailobj.message_list.selection.length != 1)) {
        return false;
    }
    rcmailobj.command('reply-all');
    return false;
}



ks_ng_actions.message_send = function (context, rcmailobj, windowobj)
{
    // Fuse: do not act/send email if focus is not on compose body
    if (!$("*:focus").is("#composebody")) {
      return true;
    }

    $('.button.send').click();
    return false;
}



ks_ng_actions.message_view_next = function (context, rcmailobj, windowobj)
{
    // FIXME next page in list mode, next message in preview mode
    rcmailobj.command('nextmessage');
    return false;
}



ks_ng_actions.message_view_prev = function (context, rcmailobj, windowobj)
{
    // FIXME prev page in list mode, prev message in preview mode
    rcmailobj.command('previousmessage');
    return false;
}



ks_ng_actions.message_toggle_flag_read = function (context, rcmailobj, windowobj)
{
    var uid = rcmailobj.message_list.get_selection();

    if (uid && uid.length > 0) {
        var mid = rcmailobj.message_list.rows[uid[0]].id;
        if ($('tr#' + mid).hasClass('unread')) {
            rcmailobj.command('mark', 'read');
        } else {
            rcmailobj.command('mark', 'unread');
        }
        return false;
    } else {
        return true;
    }
}
