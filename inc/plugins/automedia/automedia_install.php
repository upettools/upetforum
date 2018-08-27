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


// Plugin installed?
function automedia_is_installed()
{
    global $db;

    if ($db->field_exists('automedia_use', 'users')) {
        return true;
    }
        return false;
}


// Install the Plugin
function automedia_install()
{
    global $db, $mybb, $lang, $cache;

    if ($db->field_exists('automedia_use', 'users')) {
        $db->drop_column("users", "automedia_use");
    }

    if ($db->table_exists('automedia')) {
        $db->drop_table('automedia');
    }

    // Add the templates
    automedia_templates_add();

    // Add the stylesheet
    automedia_css_add();

    // DELETE ALL POSSIBLE FORMER PLUGIN SETTINGS TO AVOID DUPLICATES
    $query = $db->simple_select('settinggroups', 'gid', 'name="AutoMedia Sites"');
    $ams = $db->fetch_array($query);
    $db->delete_query('settinggroups', "gid='".$ams['gid']."'");
    $query2 = $db->simple_select('settinggroups', 'gid', 'name="AutoMedia Global"');
    $amg = $db->fetch_array($query2);
    $db->delete_query('settinggroups', "gid='".$amg['gid']."'");
    $query3 = $db->simple_select('settinggroups', 'gid', 'name="AutoMedia"');
    $am = $db->fetch_array($query3);
    $db->delete_query('settinggroups', "gid='".$am['gid']."'");
    $db->delete_query('settings', "gid='".$ams['gid']."'");
    $db->delete_query('settings', "gid='".$amg['gid']."'");
    $db->delete_query('settings', "gid='".$am['gid']."'");

/**
 *
 * Add Settings
 *
 **/

    // If MyBB version >= 1.8.1 use numeric optionscode
    $optionscode = 'numeric';
    // Else still use text optionscode
    if ($mybb->version < "1.8.1") {
        $optionscode = 'text';
    }

    $query = $db->simple_select("settinggroups", "COUNT(*) as amrows");
    $rows = $db->fetch_field($query, "amrows");

    // Add Settinggroup for Global Settings
    $automedia_group = array(
        "name" => "AutoMedia Global",
        "title" => $db->escape_string($lang->av_group_global_title),
        "description" => $db->escape_string($lang->av_group_global_descr),
        "disporder" => $rows+1,
        "isdefault" => 0
    );
    $db->insert_query("settinggroups", $automedia_group);
    $gid2 = $db->insert_id();

    // Add Settings for Global Settinggroup
    $automedia_1 = array(
        "name" => "av_enable",
        "title" => $db->escape_string($lang->av_enable_title),
        "description" => $db->escape_string($lang->av_enable_descr),
        "optionscode" => "yesno",
        "value" => 1,
        "disporder" => 1,
        "gid" => (int)$gid2
        );
    $db->insert_query("settings", $automedia_1);

    $automedia_2 = array(
        "name" => "av_guest",
        "title" => $db->escape_string($lang->av_guest_title),
        "description" => $db->escape_string($lang->av_guest_descr),
        "optionscode" => "yesno",
        "value" => 1,
        "disporder" => 2,
        "gid" => (int)$gid2
        );
    $db->insert_query("settings", $automedia_2);

    $automedia_3 = array(
        "name" => "av_groups",
        "title" => $db->escape_string($lang->av_groups_title),
        "description" => $db->escape_string($lang->av_groups_descr),
        "optionscode" => "groupselect",
        "value" => '',
        "disporder" => 3,
        "gid" => (int)$gid2
        );
    $db->insert_query("settings", $automedia_3);

    $automedia_4 = array(
        "name" => "av_forums",
        "title" => $db->escape_string($lang->av_forums_title),
        "description" => $db->escape_string($lang->av_forums_descr),
        "optionscode" => "forumselect",
        "value" => "-1",
        "disporder" => 4,
        "gid" => (int)$gid2
        );
    $db->insert_query("settings", $automedia_4);

    $automedia_5= array(
        "name" => "av_adultsites",
        "title" => $db->escape_string($lang->av_adultsites_title),
        "description" => $db->escape_string($lang->av_adultsites_descr),
        "optionscode" => "yesno",
        "value" => 0,
        "disporder" => 5,
        "gid" => (int)$gid2
        );
    $db->insert_query("settings", $automedia_5);

    $automedia_6 = array(
        "name" => "av_adultguest",
        "title" => $db->escape_string($lang->av_adultguest_title),
        "description" => $db->escape_string($lang->av_adultguest_descr),
        "optionscode" => "yesno",
        "value" => 0,
        "disporder" => 6,
        "gid" => (int)$gid2
        );
    $db->insert_query("settings", $automedia_6);

    $automedia_7 = array(
        "name" => "av_adultgroups",
        "title" => $db->escape_string($lang->av_adultgroups_title),
        "description" => $db->escape_string($lang->av_adultgroups_descr),
        "optionscode" => "groupselect",
        "value" => "-1",
        "disporder" => 7,
        "gid" => (int)$gid2
        );
    $db->insert_query("settings", $automedia_7);

    $automedia_8 = array(
        "name" => "av_adultforums",
        "title" => $db->escape_string($lang->av_adultforums_title),
        "description" => $db->escape_string($lang->av_adultforums_descr),
        "optionscode" => "forumselect",
        "value" => "-1",
        "disporder" => 8,
        "gid" => (int)$gid2
        );
    $db->insert_query("settings", $automedia_8);

    $automedia_9 = array(
        "name" => "av_signature",
        "title" => $db->escape_string($lang->av_signature_title),
        "description" => $db->escape_string($lang->av_signature_descr),
        "optionscode" => "yesno",
        "value" => 0,
        "disporder" => 9,
        "gid" => (int)$gid2
        );
    $db->insert_query("settings", $automedia_9);

    // add setting for max width
    $automedia_11 = array(
        "name" => "av_width",
        "title" => $db->escape_string($lang->av_width_title),
        "description" => $db->escape_string($lang->av_width_descr),
        "optionscode" => $optionscode,
        "value" => "900",
        "disporder" => 11,
        "gid" => (int)$gid2
        );
    $db->insert_query("settings", $automedia_11);

    // add setting for max height
    $automedia_12 = array(
        "name" => "av_height",
        "title" => $db->escape_string($lang->av_height_title),
        "description" => $db->escape_string($lang->av_height_descr),
        "optionscode" => $optionscode,
        "value" => "600",
        "disporder" => 12,
        "gid" => (int)$gid2
        );
    $db->insert_query("settings", $automedia_12);

    $automedia_13 = array(
        "name" => "av_embedly",
        "title" => $db->escape_string($lang->av_embedly_title),
        "description" => $db->escape_string($lang->av_embedly_descr),
        "optionscode" => "select\nnone=".$db->escape_string($lang->av_embedly_none_embed)
            ."\nfree=".$db->escape_string($lang->av_embedly_free_embed)
            ."\npaid=".$db->escape_string($lang->av_embedly_paid_embed)."",
        "value" => "free",
        "disporder" => 13,
        "gid" => (int)$gid2
        );
    $db->insert_query("settings", $automedia_13);

    $automedia_14 = array(
        "name" => "av_attachments",
        "title" => $db->escape_string($lang->av_attachments_title),
        "description" => $db->escape_string($lang->av_attachments_descr),
        "optionscode" => "yesno",
        "value" => 0,
        "disporder" => 14,
        "gid" => (int)$gid2
        );
    $db->insert_query("settings", $automedia_14);

    $automedia_15 = array(
        "name" => "av_codebuttons",
        "title" => $db->escape_string($lang->av_codebuttons_title),
        "description" => $db->escape_string($lang->av_codebuttons_descr),
        "optionscode" => "yesno",
        "value" => 1,
        "disporder" => 15,
        "gid" => (int)$gid2
        );
    $db->insert_query("settings", $automedia_15);

    $automedia_16 = array(
        "name" => "av_quote",
        "title" => $db->escape_string($lang->av_quote_title),
        "description" => $db->escape_string($lang->av_quote_descr),
        "optionscode" => "yesno",
        "value" => 1,
        "disporder" => 16,
        "gid" => (int)$gid2
        );
    $db->insert_query("settings", $automedia_16);

    $automedia_17 = array(
        "name" => "av_attachdl",
        "title" => $db->escape_string($lang->av_attachdl_title),
        "description" => $db->escape_string($lang->av_attachdl_descr),
        "optionscode" => "yesno",
        "value" => 1,
        "disporder" => 17,
        "gid" => (int)$gid2
        );
    $db->insert_query("settings", $automedia_17);

    $automedia_18 = array(
        "name" => "av_local",
        "title" => $db->escape_string($lang->av_local_title),
        "description" => $db->escape_string($lang->av_local_descr),
        "optionscode" => "yesno",
        "value" => 0,
        "disporder" => 18,
        "gid" => (int)$gid2
        );
    $db->insert_query("settings", $automedia_18);

    // Add users setting
    if (!$db->field_exists("automedia_use", "users")) {
        $db->add_column('users', 'automedia_use', 'VARCHAR(1) NOT NULL DEFAULT "Y"');
    }

    // Refresh settings.php
    rebuild_settings();

}


// Uninstall the Plugin
function automedia_uninstall()
{
    global $db, $cache;

    // Remove the extra column
    if ($db->field_exists('automedia_use', 'users')) {
        $db->drop_column("users", "automedia_use");
    }
    // Delete table automedia
    if ($db->table_exists('automedia')) {
        $db->drop_table('automedia');
    }

    // DELETE ALL SETTINGS
    $query = $db->simple_select('settinggroups', 'gid', 'name="AutoMedia Sites"');
    $ams = $db->fetch_array($query);
    $db->delete_query('settinggroups', "gid='".$ams['gid']."'");
    $query2 = $db->simple_select('settinggroups', 'gid', 'name="AutoMedia Global"');
    $amg = $db->fetch_array($query2);
    $db->delete_query('settinggroups', "gid='".$amg['gid']."'");
    $query3 = $db->simple_select('settinggroups', 'gid', 'name="AutoMedia"');
    $am = $db->fetch_array($query3);
    $db->delete_query('settinggroups', "gid='".$am['gid']."'");
    $db->delete_query('settings', "gid='".$ams['gid']."'");
    $db->delete_query('settings', "gid='".$amg['gid']."'");
    $db->delete_query('settings', "gid='".$am['gid']."'");

    // Refresh settings.php
    rebuild_settings();

    // Delete cache
    if (is_object($cache->handler)) {
        $cache->handler->delete('automedia');
    }
    // Delete database cache
    $db->delete_query("datacache", "title='automedia'");

    // Delete the templates and templategroup
    $db->delete_query("templategroups", "prefix = 'automedia'");
    $db->delete_query("templates", "title LIKE 'automedia_%'");
    // Delete old template
    $db->delete_query("templates", "title = 'usercp_automedia'");

    // Delete stylesheet
    $db->delete_query("themestylesheets", "name = 'automedia.css'");

    require_once MYBB_ADMIN_DIR."inc/functions_themes.php";

    $query = $db->simple_select("themes", "tid");
    while ($theme = $db->fetch_array($query)) {
        update_theme_stylesheet_list($theme['tid'], 0, 1);
    }


/**
 *
 * Delete [amquote], [amoff] and [ampl] tags
 *
 **/
    // Delete [amquote], [/amquote]
    $query_amquote_open = $db->simple_select('posts', '*', 'message like "%[amquote]%"');
    $result_amquote_open = $db->num_rows($query_amquote_open);
    if ($result_amquote_open > 0) {
        $query = $db->query("UPDATE ".TABLE_PREFIX."posts SET message = replace(message, '[amquote]', '')");
    }
    $query_amquote_close = $db->simple_select('posts', '*', 'message like "%[/amquote]%"');
    $result_amquote_close = $db->num_rows($query_amquote_close);
    if ($result_amquote_close > 0) {
        $query = $db->query("UPDATE ".TABLE_PREFIX."posts SET message = replace(message, '[/amquote]', '')");
    }
    // Delete [amoff], [/amoff]
    $query_amoff_open = $db->simple_select('posts', '*', 'message like "%[amoff]%"');
    $result_amoff_open = $db->num_rows($query_amoff_open);
    if ($result_amoff_open > 0) {
        $query = $db->query("UPDATE ".TABLE_PREFIX."posts SET message = replace(message, '[amoff]', '')");
    }
    $query_amoff_close = $db->simple_select('posts', '*', 'message like "%[/amoff]%"');
    $result_amoff_close = $db->num_rows($query_amoff_close);
    if ($result_amoff_close > 0) {
        $query = $db->query("UPDATE ".TABLE_PREFIX."posts SET message = replace(message, '[/amoff]', '')");
    }
    // Delete [ampl], [/ampl]
    $query_ampl_open = $db->simple_select('posts', '*', 'message like "%[ampl]%"');
    $result_ampl_open = $db->num_rows($query_ampl_open);
    if ($result_ampl_open > 0) {
        $query = $db->query("UPDATE ".TABLE_PREFIX."posts SET message = replace(message, '[ampl]', '')");
    }
    $query_ampl_close = $db->simple_select('posts', '*', 'message like "%[/ampl]%"');
    $result_ampl_close = $db->num_rows($query_ampl_close);
    if ($result_ampl_close > 0) {
        $query = $db->query("UPDATE ".TABLE_PREFIX."posts SET message = replace(message, '[/ampl]', '')");
    }
    // Delete [amplist], [/amplist] and [source] / [/source]
    $query_amplist_open = $db->simple_select('posts', '*', 'message like "%[amplist]%"');
    $result_amplist_open = $db->num_rows($query_amplist_open);
    if ($result_amplist_open > 0) {
        $query = $db->query("UPDATE ".TABLE_PREFIX."posts SET message = replace(message, '[amplist]', '')");
    }
    $query_amplist_close = $db->simple_select('posts', '*', 'message like "%[/amplist]%"');
    $result_amplist_close = $db->num_rows($query_amplist_close);
    if ($result_amplist_close > 0) {
        $query = $db->query("UPDATE ".TABLE_PREFIX."posts SET message = replace(message, '[/amplist]', '')");
    }

    $query_source_open = $db->simple_select('posts', '*', 'message like "%[source]%"');
    $result_source_open = $db->num_rows($query_source_open);
    if ($result_source_open > 0) {
        $query = $db->query("UPDATE ".TABLE_PREFIX."posts SET message = replace(message, '[source]', '')");
    }
    $query_source_close = $db->simple_select('posts', '*', 'message like "%[/source]%"');
    $result_source_close = $db->num_rows($query_source_close);
    if ($result_source_close > 0) {
        $query = $db->query("UPDATE ".TABLE_PREFIX."posts SET message = replace(message, '[/source]', '')");
    }

/**
 *
 * Delete MyCodes
 *
 **/
    // Delete MyCode to parse [amquote] tags
    $amquoteresult = $db->simple_select(
        'mycode',
        'cid',
        "title = 'AutoMedia Quotes (AutoMedia Plugin)'",
        array('limit' => 1)
    );
    $amquotegroup = $db->fetch_array($amquoteresult);
    if (!empty($amquotegroup['cid'])) {
        $db->delete_query('mycode', "cid='".$amquotegroup['cid']."'");
        $cache->update_mycode();
    }
    // Delete MyCode to parse [amoff] tags
    $amoffresult = $db->simple_select(
        'mycode',
        'cid',
        "title = 'AutoMedia Links (AutoMedia Plugin)'",
        array('limit' => 1)
    );
    $amoffgroup = $db->fetch_array($amoffresult);

    if (!empty($amoffgroup['cid'])) {
        $db->delete_query('mycode', "cid='".$amoffgroup['cid']."'");
        $cache->update_mycode();
    }
}


//Activate the Plugin
function automedia_activate()
{
    global $db, $mybb, $lang, $cache;

    $lang->load('automedia');

    // Do we have to upgrade?
    $upgrade = $cache->read('automedia');
    $up = 1;
    if (is_array($upgrade)
        && isset($upgrade['name'])
        && isset($upgrade['version'])
        && isset($upgrade['release'])
    ) {
        if ($upgrade['name'] == 'automedia'
            && $upgrade['version'] == AUTOMEDIA_VER
        ) {
            $up = 0;
        }
    }
    // If we don't have a cache version
    $query_upgr = $db->simple_select(
        "themestylesheets",
        "*",
        "name = 'automedia.css' AND stylesheet LIKE '%.urlembed%'"
    );
    $result_upgr = $db->num_rows($query_upgr);
    // Do the upgrade
    if (!$result_upgr || $up == 1) {
        automedia_upgrade();
        automedia_cache();
    }

    // Update new setting
    $query = $db->simple_select("settinggroups", "gid", "name='AutoMedia Global'");
    $amgid = $db->fetch_array($query);
    if ($amgid) {
        $gid = $amgid['gid'];
    }
    $query_attachdl = $db->simple_select("settings", "*", "name='av_attachdl'");
    $result = $db->num_rows($query_attachdl);

    if (!$result) {
        $automedia_17 = array(
            "name" => "av_attachdl",
            "title" => $db->escape_string($lang->av_attachdl_title),
            "description" => $db->escape_string($lang->av_attachdl_descr),
            "optionscode" => "yesno",
            "value" => 1,
            "disporder" => 17,
            "gid" => (int)$gid
            );
        $db->insert_query("settings", $automedia_17);
    }

    $query_attachments = $db->simple_select("settings", "*", "name='av_attachments'");
    $result_att = $db->num_rows($query_attachments);

    if (!$result_att) {
        $automedia_14 = array(
            "name" => "av_attachments",
            "title" => $db->escape_string($lang->av_attachments_title),
            "description" => $db->escape_string($lang->av_attachments_descr),
            "optionscode" => "yesno",
            "value" => 0,
            "disporder" => 14,
            "gid" => (int)$gid
            );
        $db->insert_query("settings", $automedia_14);
    }

    $query_local = $db->simple_select("settings", "*", "name='av_local'");
    $result_local = $db->num_rows($query_local);

    if (!$result_local) {
        $automedia_18 = array(
            "name" => "av_local",
            "title" => $db->escape_string($lang->av_local_title),
            "description" => $db->escape_string($lang->av_local_descr),
            "optionscode" => "yesno",
            "value" => 0,
            "disporder" => 18,
            "gid" => (int)$gid
            );
        $db->insert_query("settings", $automedia_18);
    }

    // Change embed.ly option to radio buttons
    $query_embedlyfree = $db->simple_select("settings", "optionscode", "name='av_embedly'");
    $result_embedlyfree = $db->fetch_field($query_embedlyfree, "optionscode");

    if (my_strpos($result_embedlyfree, 'radio') === false) {
        $updated_record = array(
            "title" => $db->escape_string($lang->av_embedly_title),
            "description" => $db->escape_string($lang->av_embedly_descr),
            "optionscode" => "radio
none=".$db->escape_string($lang->av_embedly_none_embed)."
free=".$db->escape_string($lang->av_embedly_free_embed)."
paid=".$db->escape_string($lang->av_embedly_paid_embed)."",
            "value" => "free"
                );
        $db->update_query('settings', $updated_record, "name='av_embedly'");
    }

    // Update settings language phrases
    automedia_settings_lang();

    // Update the settings
    rebuild_settings();

/**
 *   Delete template editings applied by former versions
 *
 **/
    require_once MYBB_ROOT."/inc/adminfunctions_templates.php";

    find_replace_templatesets(
        "usercp_nav_misc",
        "#".preg_quote(
            '<tr><td class="trow1 smalltext"><a href="usercp.php?action=userautomedia"'
            .' class="usercp_nav_item usercp_nav_options">{$lang->av_ucp_menu}</a></td></tr>'
        )."#s",
        '',
        '',
        false
    );
    find_replace_templatesets(
        "usercp_nav_misc",
        "#".preg_quote(
            '<tr><td class="trow1 smalltext"><a href="usercp.php?action=userautomedia"'
            .' class="usercp_nav_item usercp_nav_options">AutoMedia</a></td></tr>'
        )."#s",
        '',
        '',
        false
    );
    find_replace_templatesets(
        'usercp_editsig',
        '#\n{\$amsigpreview}<br /><br />#',
        '',
        '',
        false
    );
    find_replace_templatesets(
        "member_profile_signature",
        "#".preg_quote(
            ' profilesig'
        )."#s",
        '',
        '',
        false
    );
    find_replace_templatesets(
        "postbit_attachments_attachment",
        "#".preg_quote(
            'class="attachembed" '
        )."#s",
        '',
        '',
        false
    );
    // Add the new template edits
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

    // If we are upgrading...add the new templates
    $query_tpl = $db->simple_select('templategroups', '*', 'prefix="automedia"');
    $result_template = $db->num_rows($query_tpl);

    if (!$result_template) {
        automedia_templates_add();
    }

    // If we are upgrading...check if the stylesheet still exists
    $query_css = $db->simple_select('themestylesheets', '*', 'name="automedia.css" AND tid="1"');
    $result_css = $db->num_rows($query_css);

    if (!$result_css) {
        automedia_css_add();
    }

    // Update the template versions to match the MyBB version
    $updated_version = array(
        "version" => $db->escape_string($mybb->version_code)
    );
    $db->update_query('templates', $updated_version, "title like'automedia_%' AND sid=-2");

}


// Deactivate the Plugin
function automedia_deactivate()
{
    global $db, $mybb, $cache;

    // Delete deprecated settings
    $db->delete_query('settings', 'name="av_embera"');
    $db->delete_query('settings', 'name="av_embedly_key"');
    $db->delete_query('settings', 'name="av_embedly_click"');
    $db->delete_query('settings', 'name="av_embedly_links"');
    $db->delete_query('settings', 'name="av_embedly_card"');
    $db->delete_query('settings', 'name="av_flashadmin"');

    automedia_cache(true);

/**
 *
 * Restore templates
 *
 **/
    require_once MYBB_ROOT."/inc/adminfunctions_templates.php";

    find_replace_templatesets(
        "usercp_nav_misc",
        "#".preg_quote(
            '</a></td></tr><tr><td class="trow1 smalltext"><a href="usercp.php?action=userautomedia"'
            .' class="usercp_nav_item usercp_nav_options">{$lang->av_ucp_menu}'
        )."#s",
        '',
        '',
        false
    );
    find_replace_templatesets(
        "usercp_nav_misc",
        "#".preg_quote(
            '<tr><td class="trow1 smalltext"><a href="usercp.php?action=userautomedia"'
            .' class="usercp_nav_item usercp_nav_options">AutoMedia</a></td></tr>'
        )."#s",
        '',
        '',
        false
    );
    find_replace_templatesets(
        "member_profile_signature",
        "#".preg_quote(
            ' profilesig'
        )."#s",
        '',
        '',
        false
    );
    find_replace_templatesets(
        "postbit_attachments_attachment",
        "#".preg_quote(
            'class="attachembed" '
        )."#s",
        '',
        '',
        false
    );

    // Delete master templates for upgrade
    $db->delete_query("templategroups", "prefix = 'automedia'");
    $db->delete_query("templates", "title LIKE 'automedia_%' AND sid='-2'");
}


/**
 * Add the templates.
 *
 */
function automedia_templates_add()
{
    global $db, $lang, $mybb;

    $lang->load("automedia");

/**
 * Add templategroup and templates
 *
 **/
    $templategrouparray = array(
        'prefix' => 'automedia',
        'title'  => $db->escape_string($lang->av_templategroup),
        'isdefault' => 1
    );
    $db->insert_query("templategroups", $templategrouparray);

/*
 * Load Templates from XML file
 *
 */
    $templatefile = MYBB_ROOT.'/inc/plugins/automedia/templates.xml';

    if (file_exists($templatefile)) {
        // Load the content
        $contents = @file_get_contents($templatefile);
        require_once MYBB_ROOT."inc/class_xml.php";
        $parser = new XMLParser($contents);
        $tree = $parser->get_tree();
    }
    // We have XML content
    if (is_array($tree) && is_array($tree['automedia'])) {
        foreach ($tree['automedia']['template'] as $tpl) {
            if (is_array($tpl['title'])
                && is_array($tpl['tpl'])
            ) {
                // Fill the template
                $am_template = array(
                    "title" => $db->escape_string($tpl['title']['value']),
                    "template" => $db->escape_string($tpl['tpl']['value']),
                    "sid"       => -2,
                    "version"   => $mybb->version_code,
                    "dateline"  => TIME_NOW
                );
                $db->insert_query("templates", $am_template);
            }
        }
    }
}

/**
 * Add the styles.
 *
 */
function automedia_css_add()
{
    global $db;

    $am_styles = array(
        'name' => 'automedia.css',
        'tid' => 1,
        'attachedto' => '',
        'stylesheet' => '.am_embed,
.urlembed,
.oembedall-container {
    width: 90%;
    text-align:center;
    margin: auto auto;
}

.am_embed iframe,
.urlembed iframe,
.oembedall-container iframe {
    max-width: 100%;
}

.embedly-card {
    text-align:center;
    margin: auto auto;
}

.embedly-card-hug {
    width: 100%;
}

[id^=player_],
#player {
    border: none;
}

.mediawrapper{
    width: 430px;
    min-height: 300px;
}         ',
        'cachefile' => $db->escape_string(str_replace('/', '', 'automedia.css')),
        'lastmodified' => TIME_NOW
    );

    require_once MYBB_ADMIN_DIR."inc/functions_themes.php";
    $sid = $db->insert_query('themestylesheets', $am_styles);
    $db->update_query(
        'themestylesheets',
        array(
            'cachefile' => 'automedia.css'
        ),
        "sid = '".(int)$sid."'",
        1
    );

    $tids = $db->simple_select('themes', 'tid');
    while ($theme = $db->fetch_array($tids)) {
        cache_stylesheet($theme['tid'], "automedia.css", $am_styles['stylesheet']);
        update_theme_stylesheet_list($theme['tid'], 0, 1);
    }
}

// Hook for removing all AutoMedia files on uninstall
$plugins->add_hook("admin_config_plugins_deactivate_commit", "automedia_remove_files");

/**
 * Try to remove all AutoMedia files on uninstall
 *
 */
function automedia_remove_files()
{
    global $mybb, $lang;

    // Only use confirm action for this plugin
    if (!isset($mybb->input['plugin']) || $mybb->input['plugin'] != 'automedia') {
        return;
    }

    // Skip if the plugin gets deactivated without uninstalling
    if (!isset($mybb->input['uninstall']) || $mybb->input['uninstall'] != 1) {
        return;
    }

    // Load language strings
    if (!isset($lang->av_delete_confirm)) {
        $lang->load('automedia');
    }

    // Don't delete any files if user clicks "No"
    if (isset($mybb->input['no']) && $mybb->input['no']) {
        return;
    }
    if (!verify_post_check($mybb->input['my_post_key'])) {
        flash_message($lang->invalid_post_verify_key2, 'error');
        admin_redirect("index.php?module=config-plugins");
    } else {
        if ($mybb->request_method == 'post') {
            /*
             * Try to delete all AutoMedia language files
             *
             */
            $langdir = MYBB_ROOT."inc/languages/";
            $inst_langs = array();
            if (is_dir($langdir)) {
                $langobjects = @scandir($langdir);
            }
            $undeleted_files = $undeleted_dirs = array();

            // Find all installed language folders
            if (is_array($langobjects)) {
                foreach ($langobjects as $langobject) {
                    if ($langobject != "." && $langobject != "..") {
                        if (filetype($langdir.$langobject) == "dir") {
                            $inst_langs[] = $langobject;
                        }
                    }
                }
                // Delete AutoMedia files in all language folders
                foreach ($inst_langs as $inst_lang) {
                    @unlink($langdir.$inst_lang.'/automedia.lang.php');
                    @unlink($langdir.$inst_lang.'/admin/automedia.lang.php');
                    if (file_exists($langdir.$inst_lang.'/automedia.lang.php')) {
                        $undeleted_files[] = 'inc/languages/'.$inst_lang.'/automedia.lang.php';
                    }
                    if (file_exists($langdir.$inst_lang.'/admin/automedia.lang.php')) {
                        $undeleted_files[] = 'inc/languages/'.$inst_lang.'/admin/automedia.lang.php';
                    }
                }
            }

            // Delete the plugin file
            $plugindir = MYBB_ROOT."inc/plugins/";
            @unlink($plugindir.'automedia.php');
            if (file_exists($plugindir.'automedia.php')) {
                $undeleted_files[] = 'inc/plugins/automedia.php';
            }

            /*
             * Try to delete the automedia plugin folder and its contents
             *
             */
            $am_dir = MYBB_ROOT."inc/plugins/automedia/";

            if (is_dir($am_dir)) {
                $am_objects = @scandir($am_dir);
                if (is_array($am_objects)) {
                    foreach ($am_objects as $am_object) {
                        if ($am_object != "." && $am_object != "..") {
                            if (filetype($am_dir.$am_object) == "dir") {
                                @automedia_rrmdir($am_dir.$am_object);
                                if (is_dir($am_dir.$am_object)) {
                                    $undeleted_dirs[] = 'inc/plugins/automedia/'.$am_object.'/';
                                }
                            } else {
                                @unlink($am_dir.$am_object);
                                if (file_exists($am_dir.$am_object)) {
                                    $undeleted_files[] = 'inc/plugins/automedia/'.$am_object;
                                }
                            }
                        }
                    }
                }
                 @rmdir($am_dir);
                if (is_dir($am_dir)) {
                    $undeleted_dirs[] = 'inc/plugins/automedia/';
                }
            }

            /*
             * Try to delete the automedia jscript folder and its contents
             *
             */
            $js_dir = MYBB_ROOT."jscripts/automedia/";

            if (is_dir($js_dir)) {
                $js_objects = @scandir($js_dir);
                if (is_array($js_objects)) {
                    foreach ($js_objects as $js_object) {
                        if ($js_object != "." && $js_object != "..") {
                            if (filetype($js_dir.$js_object) == "dir") {
                                @automedia_rrmdir($js_dir.$js_object);
                                if (is_dir($js_dir.$js_object)) {
                                    $undeleted_dirs[] = 'jscripts/automedia/'.$js_object.'/';
                                }
                            } else {
                                @unlink($js_dir.$js_object);
                                if (file_exists($js_dir.$js_object)) {
                                    $undeleted_files[] = 'jscripts/automedia/'.$js_object;
                                }
                            }
                        }
                    }
                }
                 @rmdir($js_dir);
                if (is_dir($js_dir)) {
                    $undeleted_dirs[] = 'jscripts/automedia/';
                }
            }

            // Delete the image files
            $imagedir = MYBB_ROOT."images/";
            @unlink($imagedir.'amoff.png');
            @unlink($imagedir.'ampl.png');
            @unlink($imagedir.'mod-off.png');
            @unlink($imagedir.'mod-on.png');


            // Check for undeleted files and folders
            if (file_exists($imagedir.'amoff.png')) {
                $undeleted_files[] = 'images/amoff.png';
            }
            if (file_exists($imagedir.'ampl.png')) {
                $undeleted_files[] = 'images/ampl.png';
            }
            if (file_exists($imagedir.'mod-off.png')) {
                $undeleted_files[] = 'images/mod-off.png';
            }
            if (file_exists($imagedir.'mod-on.png')) {
                $undeleted_files[] = 'images/mod-on.png';
            }

            $message = $und_files = $und_dirs = '';
            foreach ($undeleted_files as $undeleted_file) {
                $und_files .= '
                    <li>'.$undeleted_file.'</li>';
            }
            foreach ($undeleted_dirs as $undeleted_dir) {
                $und_dirs .= '
                    <li>'.$undeleted_dir.'</li>';
            }

            // Display a list of undeleted files and folders
            if ($und_files != '' || $und_dirs != '') {
                $message .= '<div class="success">'
                .$lang->success_plugin_uninstalled
                .'</div>
                <div class="error">'
                .$lang->av_undeleted
                .'<ul>'
                .$und_dirs
                .$und_files.'
                </ul>
                </div>';
            }

            if ($message != '') {
                flash_message($message);
                admin_redirect("index.php?module=config-plugins");
            }
        } else {
            // Display Yes and No buttons
            $link = 'index.php?'.htmlspecialchars($_SERVER['QUERY_STRING']);
            $GLOBALS['page']->output_confirm_action($link, $lang->av_delete_confirm);
            exit;
        }
    }
}

// Delete directory recursively
function automedia_rrmdir($dir)
{
    if (is_dir($dir)) {
        $objects = @scandir($dir);
        if (is_array($objects)) {
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir."/".$object)) {
                        @automedia_rrmdir($dir."/".$object);
                    } else {
                        @unlink($dir."/".$object);
                    }
                }
            }
            reset($objects);
        }
        @rmdir($dir);
    }
}

// Upgrade function
function automedia_upgrade()
{
    global $mybb, $db, $cache;

    // Delete table automedia
    if ($db->table_exists('automedia')) {
        $db->drop_table('automedia');
    }

    // Delete the stylesheet here only if it needs an upgrade
    $db->delete_query("themestylesheets", "name = 'automedia.css'");

    require_once MYBB_ADMIN_DIR."inc/functions_themes.php";

    $query = $db->simple_select("themes", "tid");
    while ($theme = $db->fetch_array($query)) {
        update_theme_stylesheet_list($theme['tid'], 0, 1);
    }

    // Add stylesheet if not exists
    $query_css = $db->simple_select("themestylesheets", "*", "name = 'automedia.css' AND tid='1'");
    $result_css = $db->num_rows($query_css);

    if (!$result_css) {
        automedia_css_add();
    }
}
