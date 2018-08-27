<?php
/**
 * My Image Upload
 * 
 * PHP Version 5
 * 
 * @category MyBB_18
 * @package  My Image Upload
 * @author   Poeja <poejanetwork@gmail.com>
 * @license  https://creativecommons.org/licenses/by-nc/4.0/ CC BY-NC 4.0
 * @link     https://zonadiskusi.com/mybb
 */

if (!defined('IN_MYBB')) {
    die('This file cannot be accessed directly.');
}
if (!defined("PLUGINLIBRARY"))
{
    define("PLUGINLIBRARY", MYBB_ROOT."inc/plugins/pluginlibrary.php");
} 
if (defined('IN_ADMINCP')) {
    $plugins->add_hook('admin_config_settings_begin', 'myimg_lang');
} else {
// New thread
$plugins->add_hook('newthread_do_newthread_start', 'myimg_handle_attachments');
$plugins->add_hook('newthread_start', 'myimg_handle_attachments');
$plugins->add_hook('newthread', 'myimg_handle_attachments');

    $plugins->add_hook('showthread_start', 'MyAdfly_thread');
}

/**
 * Return plugin info
 *
 * @return array
 */
function myimg_info()
{
    global $mybb, $lang;
    
	myimg_lang();

    return [
        'name'          => $lang->myimg_title,
        'description'   => $lang->myimg_desc,
        'website'       => $lang->myimg_url,
        'author'        => 'Poeja Network',
        'authorsite'    => $lang->myimg_poeja,
        'version'       => '1.0',
        'compatibility' => '18*',
        'codename'      => 'myimg',
    ];
}

/**
 * Install the plugin
 *
 * @return void
 */
function myimg_install()
{
    global $db, $mybb, $lang, $cache, $PL;
    
    $collation = $db->build_create_table_collation();
    
    if (!$db->table_exists('myimg')) {
        $db->write_query(
            "CREATE TABLE ".TABLE_PREFIX."myimg (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `tid` int(10) unsigned NOT NULL,
                `from` varchar(255) NOT NULL default '',
                `to` varchar(255) NOT NULL default '',
                PRIMARY KEY (`id`)
            ) ENGINE=MyISAM{$collation};"
        );
    }
   
    
    $group = [
        'name'        => 'myimg',
        'title'       => $db->escape_string($lang->setting_group_myimg),
        'description' => $db->escape_string($lang->setting_group_myimg_desc),
    ];
    $gid = $db->insert_query('settinggroups', $group);
    
    $settings = [
        'myimg_id'     => [
            'title'       => $db->escape_string($lang->setting_myimg_id),
            'description' => $db->escape_string($lang->setting_myimg_id_desc),
            'value'       => '',
            'optionscode' => 'text',
        ],
        'myimg_api'    => [
            'title'       => $db->escape_string($lang->setting_myimg_api),
            'description' => $db->escape_string($lang->setting_myimg_api_desc),
            'value'       => '',
            'optionscode' => 'text',
        ],
        'myimg_authdomain'    => [
            'title'       => $db->escape_string($lang->setting_myimg_authdomain),
            'description' => $db->escape_string($lang->setting_myimg_authdomain_desc),
            'value'       => '',
            'optionscode' => 'text',
        ],
        'myimg_databaseurl'    => [
            'title'       => $db->escape_string($lang->setting_myimg_databaseurl),
            'description' => $db->escape_string($lang->setting_myimg_databaseurl_desc),
            'value'       => '',
            'optionscode' => 'text',
        ],
        'myimg_storagebucket'    => [
            'title'       => $db->escape_string($lang->setting_myimg_storagebucket),
            'description' => $db->escape_string($lang->setting_myimg_storagebucket_desc),
            'value'       => '',
            'optionscode' => 'text',
        ],
        'myimg_messagingsenderid'    => [
            'title'       => $db->escape_string($lang->setting_myimg_messagingsenderid),
            'description' => $db->escape_string($lang->setting_myimg_messagingsenderid_desc),
            'value'       => '',
            'optionscode' => 'text',
        ],
        'myimg_groups' => [
            'title'       => $db->escape_string($lang->setting_myimg_groups),
            'description' => $db->escape_string($lang->setting_myimg_groups_desc),
            'value'       => '',
            'optionscode' => 'groupselect',
        ]
    ];
    
    foreach ($settings as $key => $setting) {
        $setting['name'] = $key;
        $setting['gid']  = $gid;
        
        $db->insert_query('settings', $setting);
    }
    rebuild_settings();
		// Add the css and templates
	if (!file_exists(PLUGINLIBRARY))
	{
    flash_message($lang->myalerts_pluginlibrary_missing, "error");
    admin_redirect("index.php?module=config-plugins");
	}

	$PL or require_once PLUGINLIBRARY;

	if ((int) $PL->version < 9)
	{
    flash_message('This plugin requires PluginLibrary 9 or newer', 'error');
    admin_redirect('index.php?module=config-plugins');
	}
	$stylesheet = @file_get_contents(MYBB_ROOT.'inc/plugins/MyImg/myimg.css');
	$PL->stylesheet('myimg.css', $stylesheet); 
	
	// Add templates	   
	$dir       = new DirectoryIterator(dirname(__FILE__) . '/MyImg/templates');
	$templates = array();
	foreach ($dir as $file) {
		if (!$file->isDot() and !$file->isDir() and pathinfo($file->getFilename(), PATHINFO_EXTENSION) == 'html') {
			$templates[$file->getBasename('.html')] = file_get_contents($file->getPathName());
		}
	}
	
	$PL->templates('myimg', 'MyImage Upload', $templates);
	
	require_once MYBB_ROOT . 'inc/adminfunctions_templates.php';	
	find_replace_templatesets('newthread', '#' . preg_quote('</textarea>') . '#i', '</textarea>{$myimgbox}{$myimgbox_js}');
	find_replace_templatesets('newthread', '#' . preg_quote('{$attachbox}') . '#i', ' ');
	
		// Add the plugin to cache
    $info = myimg_info();
    $poeja_plugins = $cache->read('poeja_plugins');
    $poeja_plugins[$info['name']] = [
        'title' => $info['name'],
        'version' => $info['version']
    ];
    $cache->update('poeja_plugins', $poeja_plugins);
}

/**
 * Check if plugin is installed
 *
 * @return bool
 */
function myimg_is_installed()
{
    global $db, $mybb, $cache;
    
	$info = myimg_info();
    $installed = $cache->read("poeja_plugins");
    if ($installed[$info['name']]) {
        return true;
    }
	
    if (isset($mybb->settings['myimg_id'])
        && isset($mybb->settings['myimg_api'])
        && isset($mybb->settings['myimg_authdomain'])
        && isset($mybb->settings['myimg_databaseurl'])
        && isset($mybb->settings['myimg_storagebucket'])
        && isset($mybb->settings['myimg_messagingsenderid'])
        && isset($mybb->settings['myimg_groups'])
        && $db->table_exists('myimg')
    ) {
        return true;
    }
    
    return false;
}

/**
 * Uninstall the plugin
 *
 * @return void
 */
function myimg_uninstall()
{
    global $db, $PL, $cache;
    
	if (!file_exists(PLUGINLIBRARY))
	{
    flash_message($lang->myalerts_pluginlibrary_missing, "error");
    admin_redirect("index.php?module=config-plugins");
	}
	$PL or require_once PLUGINLIBRARY;
	$PL->stylesheet_delete('myimg.css'); 
	$PL->templates_delete('myimg');
	$PL->settings_delete('myimg');
	
	require MYBB_ROOT."/inc/adminfunctions_templates.php";
	find_replace_templatesets('newthread', '#' . preg_quote('{$myimgbox}{$myimgbox_js}') . '#i', ' ');
    rebuild_settings();
    
    if ($db->table_exists('myimg')) {
        $db->drop_table('myimg');
    }
	
	// Remove the plugin from cache
	$info = myimg_info();
    $poeja_plugins = $cache->read('poeja_plugins');
    unset($poeja_plugins[$info['name']]);
    $cache->update('poeja_plugins', $poeja_plugins);
	
}

/**
 * Remove attachment box then replace
 *
 * @return void
 */
function myimg_handle_attachments()
{
	global $mybb, $attachedfile, $lang, $templates, $myimgbox, $myimgbox_js;
	
	if (is_member($mybb->settings['myimg_groups'])) {
		
	if($mybb->settings['myimg_id'] && $mybb->settings['myimg_api'] && $mybb->settings['myimg_authdomain'] && $mybb->settings['myimg_databaseurl'] && $mybb->settings['myimg_storagebucket'] && $mybb->settings['myimg_messagingsenderid']){
		
		eval("\$myimgbox = \"".$templates->get("myimg_box")."\";");
		eval("\$myimgbox_js = \"".$templates->get("myimg_js")."\";");
	
	}else{
		$myimgbox = '<div class="myimgboxes_box largetext"><img src="images/MyImg/warn.png"> Missing parameter in plugin setting.</div>';
	}
	
	}
}

/**
 * Load the lang
 *
 * @return object $lang
 */
function myimg_lang()
{
    global $lang;
    
    $lang->load('myimg');
    
    return $lang;
}
