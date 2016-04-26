# Roundcube plugin - Keyboard Shortcuts NG

Plugin that enables keyboard shortcuts, and makes associations configurable
by Roundcube admin.

Supports _almost all_ possible keyboard shortcuts (any combination of alt, ctrl,
meta and shift keys), and all of character, number, punctuation, direction, F1-F12
keys with addition of backspace and delete key + ctrl+enter for send mail.

Initial sets of keyboard shortcuts are ready to be used:
- ng_awesome - default association map for this plugin, enabled by default
- os_native_linux
- os_native_macosx (TODO)
- os_native_windows (TODO)
- compat_rc_plugin - mimics behavour of [keyboard_shortcuts](https://github.com/corbosman/keyboard_shortcuts) plugin



## ng_awesome keyboard map - your participation is awesome!

*ng_awesome* keyboard shortcut association map is destined to be the best
available keyboard map ever! But, to get there, your suggestions,
arguments and code are required.

The idea behind *ng_awesome* is (for now) the following:
- use as many os-native shortcuts as possible, because user is already used to those,
- make non-os-native shortcuts intuitive,
- make reading mail possible with as little hand movement as possible,
- do not make it dangerous (letter 'd' for deleting email, for example, when 'delete' key is already there).

The initial idea (rought, untested!) for mail reading is this:
- 'q' and 'a' is for selecting mailboxes/folders,
- 'up' and 'down' keys are for selecting email (default),
- 'space' selects email and opens it in preview pane (default),
- 'w' and 'a' keys are for scrolling preview pane up and down

Now, about shortcuts above: I would like to replace up/down with 'p'/'l'
combination, and use up/down keys for preview pane scrolling - placement
of keys aligns more naturally with on-screen content positions.
Another comment is that space key is not natural for opening email in preview
pane, if preview pane is already visible. I generally tend towards hitting enter
and expecting email to open in preview pane instead in new window/refreshed
window in email-view mode.

Anyhow: issues, comments and pull requests are welcome. Beware that pull requests will
probably be integrated very slowly, as additional comments will be waited for.



## Installation

Like any normal Roundcube plugin. Use composer.json.



## Configuration

See [config/defaults.inc.php](config/defaults.inc.php) for details.
Local overrides are done _in your main Roundcube configuration file_.
Files you create in plugin/keyboard_shortcuts_ng/config/ are ignored.



## TODO

Details that need attention of developers:
- when previewpane is empty, 'c' for compose shortcut does not work - 404 because of skins/theme/... src= value
- fix help output to display actual associations
- move association_hash computation to frontend/JS



## Legacy

This plugin came to life as an offspring of original
[keyboard_shortcuts](https://github.com/corbosman/keyboard_shortcuts) plugin,
joined by a series of merges of almost all unmerged changes throughout GitHub,
fueled by major refactoring and implementation of configurable key associations.

Remaining parts of keyboard_shortcuts are pending for replacement or rewrite:
- help screen
- .css file
- content in localization/
- content in skins/



## License

This plugin is distributed under the GNU Affero General Public License Version 3.
Please read through the file LICENSE for more information about this license.
