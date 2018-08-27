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
    die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

// Caching templates
if (my_strpos($_SERVER['PHP_SELF'], 'usercp.php')) {
    global $templatelist;
    if (isset($templatelist)) {
        $templatelist .= ',';
    }
    $templatelist .= 'automedia_codebuttons,automedia_codebuttons_footer,automedia_codebuttons_private';
    $templatelist .= ',automedia_footer,automedia_player_styles,automedia_usercp,automedia_ucp_menu';
    $templatelist .= ',automedia_ucpstatus_up,automedia_ucpstatus_down';
} elseif (my_strpos($_SERVER['PHP_SELF'], 'showthread.php') ||
        my_strpos($_SERVER['PHP_SELF'], 'private.php') ||
        my_strpos($_SERVER['PHP_SELF'], 'newthread.php') ||
        my_strpos($_SERVER['PHP_SELF'], 'newreply.php') ||
        my_strpos($_SERVER['PHP_SELF'], 'editpost.php') ||
        my_strpos($_SERVER['PHP_SELF'], 'calendar.php') ||
        my_strpos($_SERVER['PHP_SELF'], 'modcp.php') ||
        my_strpos($_SERVER['PHP_SELF'], 'portal.php') ||
        my_strpos($_SERVER['PHP_SELF'], 'member.php')) {
    global $templatelist;
    if (isset($templatelist)) {
        $templatelist .= ',';
    }
    $templatelist .= 'automedia_codebuttons,automedia_codebuttons_footer,automedia_codebuttons_private';
    $templatelist .= ',automedia_footer,automedia_player_styles';
}

// Plugin version
define('AUTOMEDIA_VER', '4002');
define('AUTOMEDIA_NR', '4.0.2');

// Plugin Info
function automedia_info()
{
    global $lang, $plugins_cache;

    if (!isset($lang->av_plugin_descr)) {
        $lang->load("automedia");
    }

    $am_info = array(
        "name"          => $lang->av_plugin_title,
        "description"   => $lang->av_plugin_descr,
        "website"       => "http://mybbplugins.tk",
        "author"        => "doylecc",
        "authorsite"    => "http://mybbplugins.tk",
        "version"       => "4.0.2",
        "compatibility"     => "18*",
        "codename"      => "automedia"
        );

    // PHP 5.3 is required for the plugin
    if (version_compare(PHP_VERSION, '5.3.0', '<')) {
        $am_info['description'] .= "  <ul><li style=\"list-style-image: url(styles/default/images/icons/error.png)\">"
        .$lang->av_php_version
        ."</li></ul>";
    } else {
        // Add more links
        if (automedia_is_installed()
            && is_array($plugins_cache)
            && is_array($plugins_cache['active'])
            && $plugins_cache['active']['automedia']
        ) {
            $am_info['description'] .= @automedia_more_info();
        }
    }
    return $am_info;
}


// Get additional info
function automedia_more_info()
{
    global $lang, $db, $mybb;

    $result = $db->simple_select('settinggroups', 'gid', "name = 'AutoMedia Global'");
    $set = $db->fetch_array($result);
    $unsupported = $lang->av_unsupported;

    $status = "<ul></li>
    <li style=\"list-style-image: url(styles/default/images/icons/custom.png)\">
    <a href=\"index.php?module=config-settings&amp;action=change&amp;gid=".(int)$set['gid']."\">"
    .$lang->automedia_settings."</a></li>
    <li style=\"list-style-image: url(styles/default/images/icons/make_default.png)\">
    <a href=\"index.php?module=tools-automedia&amp;action=templateedits&amp;my_post_key="
    .$mybb->post_code."\">".$lang->automedia_template_edits1."</a> ".$lang->automedia_template_edits2."</li>
    </ul>\n";

    return $status;
}


// Load the install/admin functions in ACP.
if (defined("IN_ADMINCP")) {
    require_once MYBB_ROOT."inc/plugins/automedia/automedia_install.php";
    require_once MYBB_ROOT."inc/plugins/automedia/automedia_admincp.php";
} else {
    // Load the frontend functions
    require_once MYBB_ROOT."inc/plugins/automedia/automedia_functions.php";
}


// Build and empty cache
function automedia_cache($clear = false)
{
    global $cache;
    if ($clear == true) {
        $cache->update('automedia', false);
    } else {
        // Get the plugin version
        $version = array(
            'name' => 'automedia',
            'version' => AUTOMEDIA_VER,
            'release' => AUTOMEDIA_NR
        );
        $cache->update('automedia', $version);
    }
}
