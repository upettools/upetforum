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


// Reapply the template edits
function automedia_reapply_template_edits($sid = false)
{
    require_once MYBB_ROOT."/inc/adminfunctions_templates.php";

    find_replace_templatesets(
        "usercp_nav_misc",
        "#".preg_quote(
            '</a></td></tr><tr><td class="trow1 smalltext"><a href="usercp.php?action=userautomedia"'
            .' class="usercp_nav_item usercp_nav_options">{$lang->av_ucp_menu}'
        )."#s",
        '',
        1,
        $sid,
        -1
    );
    find_replace_templatesets(
        "usercp_nav_misc",
        "#".preg_quote(
            '<tr><td class="trow1 smalltext"><a href="usercp.php?action=userautomedia" class="usercp_nav_item'
            .' usercp_nav_options">AutoMedia</a></td></tr>'
        )."#s",
        '',
        1,
        $sid,
        -1
    );
    find_replace_templatesets(
        "member_profile_signature",
        "#".preg_quote(
            ' profilesig'
        )."#s",
        '',
        1,
        $sid,
        -1
    );
    find_replace_templatesets(
        "postbit_attachments_attachment",
        "#".preg_quote(
            'class="attachembed" '
        )."#s",
        '',
        1,
        $sid,
        -1
    );
    find_replace_templatesets(
        "member_profile_signature",
        '#'.preg_quote('">{$memprofile').'#s',
        ' profilesig">{$memprofile'
    );
    find_replace_templatesets(
        "postbit_attachments_attachment",
        '#'.preg_quote('href="attachment.php').'#s',
        'class="attachembed" href="attachment.php'
    );
}


// Settinggroup for peeker
$plugins->add_hook("admin_config_settings_change", "automedia_settings_change");

function automedia_settings_change()
{
    global $db, $mybb, $automedia_settings_peeker;

    $result = $db->simple_select("settinggroups", "gid", "name='AutoMedia Global'", array("limit" => 1));
    $group = $db->fetch_array($result);
    $automedia_settings_peeker = ($mybb->input["gid"] == $group["gid"]) && ($mybb->request_method!="post");
}


// Add the peekers
$plugins->add_hook("admin_settings_print_peekers", "automedia_settings_peek");

function automedia_settings_peek(&$peekers)
{
    global $automedia_settings_peeker;

    if ($automedia_settings_peeker) {
    // Peeker for adult sites settings
        $peekers[] = 'new Peeker($(".setting_av_adultsites"), $("#row_setting_av_adultguest"),/1/,true)';
        $peekers[] = 'new Peeker($(".setting_av_adultsites"), $("#row_setting_av_adultgroups"),/1/,true)';
        $peekers[] = 'new Peeker($(".setting_av_adultsites"), $("#row_setting_av_adultforums"),/1/,true)';
    }
}


// Set action handler
$plugins->add_hook("admin_tools_action_handler", "automedia_admin_tools_action_handler");

function automedia_admin_tools_action_handler(&$actions)
{
    $actions['automedia'] = array('active' => 'automedia', 'file' => 'automedia');
}


// Apply template edits
$plugins->add_hook("admin_load", "automedia_admin");

function automedia_admin()
{
    global $mybb, $lang, $page, $run_module, $action_file;

    if ($page->active_action != 'automedia') {
        return false;
    }

    if ($run_module == 'tools' && $action_file == 'automedia') {
        // Reapply template edits
        if ($mybb->input['action'] == "templateedits") {
            if (!verify_post_check($mybb->input['my_post_key'])) {
                flash_message($lang->invalid_post_verify_key2, 'error');
                admin_redirect("index.php?module=config-plugins");
            } else {
                automedia_reapply_template_edits();
                admin_redirect("index.php?module=config-plugins");
            }
            exit();
        }
    }
}


// Hook for updated settings
$plugins->add_hook('admin_config_settings_start', 'automedia_language_change');
/**
 * Change settings language strings after switching ACP language
 *
 */
function automedia_language_change()
{
    global $mybb, $db, $lang;
    // Load language strings in plugin function
    if (!isset($lang->av_group_global_descr)) {
        $lang->load("automedia");
    }

    // Get settings language string
    $query = $db->simple_select("settinggroups", "*", "name='AutoMedia Global'");
    $amgroup = $db->fetch_array($query);

    if ($amgroup['description'] != $lang->av_group_global_descr) {
        automedia_settings_lang();
    }
}


function automedia_settings_lang()
{
    global $mybb, $db, $lang;
    // Load language strings in plugin function
    if (!isset($lang->av_group_global_descr)) {
        $lang->load("automedia");
    }

    // Update setting group
    $updated_record_gr = array(
        "title" => $db->escape_string($lang->av_group_global_title),
        "description" => $db->escape_string($lang->av_group_global_descr)
            );
    $db->update_query('settinggroups', $updated_record_gr, "name='AutoMedia Global'");

    // Update settings
    $updated_record1 = array(
        "title" => $db->escape_string($lang->av_enable_title),
        "description" => $db->escape_string($lang->av_enable_descr)
            );
    $db->update_query('settings', $updated_record1, "name='av_enable'");

    $updated_record2 = array(
        "title" => $db->escape_string($lang->av_guest_title),
        "description" => $db->escape_string($lang->av_guest_descr)
            );
    $db->update_query('settings', $updated_record2, "name='av_guest'");

    $updated_record3 = array(
        "title" => $db->escape_string($lang->av_groups_title),
        "description" => $db->escape_string($lang->av_groups_descr)
            );
    $db->update_query('settings', $updated_record3, "name='av_groups'");

    $updated_record4 = array(
        "title" => $db->escape_string($lang->av_forums_title),
        "description" => $db->escape_string($lang->av_forums_descr)
            );
    $db->update_query('settings', $updated_record4, "name='av_forums'");

    $updated_record5 = array(
        "title" => $db->escape_string($lang->av_adultsites_title),
        "description" => $db->escape_string($lang->av_adultsites_descr)
            );
    $db->update_query('settings', $updated_record5, "name='av_adultsites'");

    $updated_record6 = array(
        "title" => $db->escape_string($lang->av_adultguest_title),
        "description" => $db->escape_string($lang->av_adultguest_descr)
            );
    $db->update_query('settings', $updated_record6, "name='av_adultguest'");

    $updated_record7 = array(
        "title" => $db->escape_string($lang->av_adultgroups_title),
        "description" => $db->escape_string($lang->av_adultgroups_descr)
            );
    $db->update_query('settings', $updated_record7, "name='av_adultgroups'");

    $updated_record8 = array(
        "title" => $db->escape_string($lang->av_adultforums_title),
        "description" => $db->escape_string($lang->av_adultforums_descr)
            );
    $db->update_query('settings', $updated_record8, "name='av_adultforums'");

    $updated_record9 = array(
        "title" => $db->escape_string($lang->av_signature_title),
        "description" => $db->escape_string($lang->av_signature_descr)
            );
    $db->update_query('settings', $updated_record9, "name='av_signature'");

    $updated_record11 = array(
        "title" => $db->escape_string($lang->av_width_title),
        "description" => $db->escape_string($lang->av_width_descr)
            );
    $db->update_query('settings', $updated_record11, "name='av_width'");

    $updated_record12 = array(
        "title" => $db->escape_string($lang->av_height_title),
        "description" => $db->escape_string($lang->av_height_descr)
            );
    $db->update_query('settings', $updated_record12, "name='av_height'");

    $updated_record13 = array(
        "title" => $db->escape_string($lang->av_embedly_title),
        "description" => $db->escape_string($lang->av_embedly_descr),
        "optionscode" => "radio
none=".$db->escape_string($lang->av_embedly_none_embed)."
free=".$db->escape_string($lang->av_embedly_free_embed)."
paid=".$db->escape_string($lang->av_embedly_paid_embed)."",
        "disporder" => 13
            );
    $db->update_query('settings', $updated_record13, "name='av_embedly'");

    $updated_record14 = array(
        "title" => $db->escape_string($lang->av_attachments_title),
        "description" => $db->escape_string($lang->av_attachments_descr),
        "disporder" => 14
            );
    $db->update_query('settings', $updated_record14, "name='av_attachments'");

    $updated_record15 = array(
        "title" => $db->escape_string($lang->av_codebuttons_title),
        "description" => $db->escape_string($lang->av_codebuttons_descr),
        "disporder" => 15
            );
    $db->update_query('settings', $updated_record15, "name='av_codebuttons'");

    $updated_record16 = array(
        "title" => $db->escape_string($lang->av_quote_title),
        "description" => $db->escape_string($lang->av_quote_descr),
        "disporder" => 16
            );
    $db->update_query('settings', $updated_record16, "name='av_quote'");

    $updated_record17 = array(
        "title" => $db->escape_string($lang->av_attachdl_title),
        "description" => $db->escape_string($lang->av_attachdl_descr),
        "disporder" => 17
            );
    $db->update_query('settings', $updated_record17, "name='av_attachdl'");

    $updated_record18 = array(
        "title" => $db->escape_string($lang->av_local_title),
        "description" => $db->escape_string($lang->av_local_descr),
        "disporder" => 18
            );
    $db->update_query('settings', $updated_record18, "name='av_local'");

    rebuild_settings();
}
