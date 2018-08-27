<?php
/**
 * Plugin Name: AutoMedia 4.0 for MyBB 1.8.*
 * Copyright © 2009-2018 doylecc
 * http://mybbplugins.tk
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 */


// Disallow direct access to this file for security reasons
if (!defined("IN_MYBB")) {
    die("Direct initialization of this file is not allowed.<br /><br />
        Please make sure IN_MYBB is defined.");
}


// Load User-CP Menu Item
$plugins->add_hook("usercp_menu", "automedia_ucp_menu", 39);

function automedia_ucp_menu()
{
    global $lang, $templates, $usercpmenu;

    if (!isset($lang->av_ucp_menu)) {
        $lang->load("automedia");
    }
    $usercpmenu .= eval($templates->render('automedia_ucp_menu'));
}


// User-CP Settings Page
$plugins->add_hook("usercp_start", "automedia_ucp_settings");

function automedia_ucp_settings()
{
    global $header, $headerinclude, $usercpnav, $footer, $mybb, $theme, $db, $lang, $templates;

    $av_checked_yes = ' checked="checked"';
    $av_checked_no = $ucpset = '';

    if (!isset($lang->av_ucp_yes)) {
        $lang->load("automedia");
    }

    // Load the page
    if ($mybb->input['action'] == "userautomedia") {
        if (isset($mybb->user['automedia_use']) && $mybb->user['automedia_use'] == 'N') {
            $av_checked_yes = '';
            $av_checked_no = ' checked="checked"';
            // Load status template deactivated
            $ucpset = eval($templates->render('automedia_ucpstatus_down'));
        } elseif (isset($mybb->user['automedia_use']) && $mybb->user['automedia_use'] == 'Y') {
            // Load status template activated
            $ucpset = eval($templates->render('automedia_ucpstatus_up'));
        }

        add_breadcrumb($lang->nav_usercp, "usercp.php");
        add_breadcrumb("AutoMedia");
        $automedia_ucp = eval($templates->render('automedia_usercp'));
        output_page($automedia_ucp);
    // Update the users setting
    } elseif ($mybb->input['action'] == "do_automedia" && $mybb->request_method == "post") {
        // Verify incoming POST request
        verify_post_check($mybb->get_input('my_post_key'));
        $uid = (int)$mybb->user['uid'];
        $updated_record = array(
            "automedia_use" => $db->escape_string($mybb->input['automedia'])
        );
        if ($db->update_query("users", $updated_record, "uid='".$uid."'")) {
            redirect("usercp.php?action=userautomedia", $lang->av_ucp_submit_success);
        }
    } else {
        return;
    }
}


// Parse [amoff] Mycode Tags and Playlist MyCodes
$plugins->add_hook("parse_message", "automedia_mycode", -1);

function automedia_mycode($message)
{
    // Parse [amoff] MyCode for disabling embedding
    $message = str_replace(array('[amoff]', '[/amoff]'), array('<span class="am_noembed">', '</span>'), $message);

    // Parse playlist MyCodes
    $message = str_replace(
        array("[amplist]\n","[/amplist]","[source]", "[/source]\n","[ampl]","[/ampl]"),
        array('<div class="amplist">', '</div>','<source src=', ' />','<span class="ampl">','</span>'),
        $message
    );

    return $message;
}


// The Embed Function - And Insert MyCode Buttons For Disabling Embedding And mp3 Playlist MyCode
$plugins->add_hook("pre_output_page", "automedia_embed");

function automedia_embed($page)
{
    global $mybb, $templates, $amoff, $ampl, $automedia_version, $lang, $current_page, $embedscript;
    global $embedly, $urlembed, $am_active, $am_forums, $am_groups, $am_special, $am_attach, $editsig;

    $this_scripts = array(
        'usercp.php',
        'showthread.php',
        'private.php',
        'newthread.php',
        'newreply.php',
        'editpost.php',
        'calendar.php',
        'portal.php',
        'modcp.php',
        'member.php'
    );

    // Don't load the scripts everywhere
    if (!in_array($current_page, $this_scripts)) {
        return;
    }

    $automedia_version = AUTOMEDIA_VER;
    $am_footer = $embedscript = '';
    $am_forums = $am_groups = 1;
    $am_active = $am_special = $am_attach = $embedly = $urlembed = $editsig = 0;

    // Check for special site settings
    if ($mybb->settings['av_adultsites'] == 1) {
        $am_special = automedia_special();
    }

    // Get the settings for the forums if embedding is not allowed in all forums
    if ($mybb->settings['av_forums'] != -1) {
        global $fid;

        $avfid = 0;
        if (isset($fid)) {
            $avfid = (int)$fid;
        } else {
            $avfid = $mybb->get_input('fid', MyBB::INPUT_INT);
        }

        // Find the set fid's in settings
        $fids = explode(',', $mybb->settings['av_forums']);
        if (!in_array($avfid, $fids)) {
            $am_forums = 0;
        }
    }

    // Find the excluded groups in settings
    if ($mybb->settings['av_groups'] != '' && $mybb->usergroup['cancp'] != 1) {
        if (is_member($mybb->settings['av_groups']) || $mybb->settings['av_groups'] == -1) {
            $am_groups = 0;
        }
    }

    // Check settings and guest permissions
    if ($mybb->settings['av_enable'] == 1 && $mybb->user['uid'] != 0 && $mybb->user['automedia_use'] != 'N' ||
        $mybb->settings['av_enable'] == 1 && $mybb->user['uid'] == 0 && $mybb->settings['av_guest'] == 1
    ) {
        $am_active = 1;

        // Do we have permission to see/download attachments?
        if ($mybb->settings['av_attachments'] == 1 && $mybb->usergroup['candlattachments'] == 1) {
            $am_attach = 1;
        }

        // Load the mediaelement stylesheets
        $player_styles = eval($templates->render('automedia_player_styles'));
        $page = str_replace("</head>", $player_styles."\n</head>", $page);

        // Embed.ly or Urlembed.com activated?
        if (isset($mybb->settings['av_embedly'])
            && ($mybb->settings['av_embedly'] == 'free' || $mybb->settings['av_embedly'] == 'paid')
        ) {
            // Embed.ly
            if ($mybb->settings['av_embedly'] == 'free') {
                $embedly = 1;
                $embedscript = '<script async src="//cdn.embedly.com/widgets/platform.js" charset="UTF-8"></script>';
            // Urlembed.com
            } elseif ($mybb->settings['av_embedly'] == 'paid') {
                $urlembed = 1;
                $embedscript = '<script src="//urlembed.com/static/js/script.js"></script>';
            }
        }

        // Are we editing the signature?
        if ($current_page == 'usercp.php'
            && ($mybb->input['action'] == 'editsig'
            || $mybb->input['action'] == 'do_editsig')
        ) {
            $editsig = 1;
        }

        // Load the variables and scripts
        $am_footer = eval($templates->render('automedia_footer'));

        // Insert the automedia and mediaelement scripts
        $page = str_replace("</body>", $am_footer."\n</body>", $page);
    }

    ##################################################################

    // Display the code buttons
    if ($mybb->settings['av_codebuttons'] == 1) {
        global $lang;
        if (!isset($lang->av_amoff)) {
            if (isset($lang)) {
                $lang->load("automedia");
            } else {
                $GLOBALS['lang']->load("automedia");
                $lang = $GLOBALS['lang'];
            }
        }
        $automedia_version = AUTOMEDIA_VER;
        $amoff = $lang->av_amoff;
        $ampl = $lang->av_ampl;
        $am_codebuttons = $am_codebuttons_footer = '';
        $am_buttons = $am_pm_buttons = 0;

        // Load buttons only where they're needed
        switch ($current_page) {
            case "newthread.php":
            case "calendar.php":
            case "newreply.php":
            case "editpost.php":
            case "modcp.php":
                $am_buttons = 1;
                break;
            case "usercp.php":
                if ($editsig == 1) {
                    $am_buttons = 1;
                }
                break;
            case "showthread.php":
                if ($mybb->settings['quickreply'] == 1) {
                    $am_buttons = 1;
                }
                break;
            case "private.php":
                if ($mybb->input['action'] == "read") {
                    $am_buttons = 1;
                } elseif ($mybb->input['action'] == "send") {
                    $am_pm_buttons = 1;
                }
                break;
        }

        if ($am_buttons == 1) {
            // Insert MyCode buttons
            $am_codebuttons = eval($templates->render('automedia_codebuttons'));
            $page = str_replace('</textarea>', '</textarea>'.$am_codebuttons.'', $page);
            // Load MyCode buttons script
            $am_codebuttons_footer = eval($templates->render('automedia_codebuttons_footer'));
            $page = str_replace("</body>", $am_codebuttons_footer."\n</body>", $page);
        }

        // Display codebuttons for private messages
        if ($am_pm_buttons == 1) {
            // Insert MyCode buttons
            $am_codebuttons = eval($templates->render('automedia_codebuttons_private'));
            $am_find = '<label><input type="checkbox" class="checkbox" name="options[signature]"';
            $page = str_replace($am_find, $am_codebuttons."\n".$am_find, $page);
            // Load MyCode buttons script
            $am_codebuttons_footer = eval($templates->render('automedia_codebuttons_footer'));
            $page = str_replace("</body>", $am_codebuttons_footer."\n</body>", $page);
        }
    }

    // Remove deprecated [amquote] tags from old plugin versions
    $page = str_replace(array('[amquote]', '[/amquote]'), '', $page);

    return $page;
}


// Check Permissions For Special Sites Embedding
function automedia_special()
{
    global $mybb;

    // Get the settings for the forums
    if ($mybb->settings['av_adultforums'] != -1) {
        if ($mybb->settings['av_adultforums'] == '') {
            return false;
        }

        global $fid;

        $avfid = 0;
        if (isset($fid)) {
            $avfid = (int)$fid;
        } else {
            $avfid = $mybb->get_input('fid', MyBB::INPUT_INT);
        }

        // Find the set fid's in settings
        $fids = explode(',', $mybb->settings['av_adultforums']);
        if (!in_array($avfid, $fids)) {
            return false;
        }
    }

    // Find the excluded groups in settings
    if ($mybb->settings['av_adultgroups'] != -1 || $mybb->usergroup['cancp'] != 1) {
        if ($mybb->settings['av_adultgroups'] == '' || !is_member($mybb->settings['av_adultgroups'])) {
            return false;
        }
    }

    // Activate the embedding
    return true;
}
