<?php
/**
 * This file is part of Top Stats plugin for MyBB.
 * Copyright (C) 2010-2013 baszaR & LukasAMD & Supryk
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */ 

/**
 * Disallow direct access to this file for security reasons
 * 
 */
if (!defined("IN_MYBB")) exit;

/**
 * Create plugin object
 * 
 */
$plugins->objects['topStats'] = new topStats();

/**
 * Standard MyBB info function
 * 
 */
function topStats_info() 
{
    global $lang;
    $lang->load("topStats");
	return array(
        'name'          => $lang->topStats_Name,
        'description'   => $lang->topStats_NameDesc,
		'website'		=> 'http://mybboard.pl/',
		'author'		=> 'baszaR & LukasAMD & Supryk',
		'authorsite'	=> 'http://mybboard.pl/',
		'version'		=> '1.0.4',
		'guid'			=> '',
		'compatibility' => '18*',
		'codename' => 'topStats_',
	);
}

/**
 * Standard MyBB installation functions 
 * 
 */
function topStats_install()
{
    require_once('topStats.settings.php');
    topStatsInstaller::install();
    rebuild_settings();

}

function topStats_is_installed()
{

    global $mybb;
    return (isset($mybb->settings['topStats_Status_LastThreads']));
}

function topStats_uninstall()
{

    require_once('topStats.settings.php');
    topStatsInstaller::uninstall();
    rebuild_settings();
} 
  
/**
 * Standard MyBB activation functions 
 * 
 */
function topStats_activate()
{

    require_once('topStats.tpl.php');
    topStatsActivator::activate();
}

function topStats_deactivate()
{
    require_once('topStats.tpl.php');
    topStatsActivator::deactivate();
}



/**
 * Plugin Class 
 * 
 */
class topStats
{
    /**
     * Constructor - add plugin hooks
     *      
     */
    public function __construct()
    {
        global $plugins;

        $plugins->hooks["global_start"][10]["topStats_addHooks"] = array("function" => create_function('', 'global $plugins; $plugins->objects[\'topStats\']->addHooks();'));
    }

    /**
     * Add all needed hooks
     *      
     */
    public function addHooks()
    {
        global $lang, $mybb, $plugins, $templatelist, $topStats;

        $topStats = array(
            'LastThreads'   => '',
			'LastActiveThreads'   => '',
            'MostViews'     => '',
            'Posters'       => '',
            'Reputation'    => '',
			'Referrals' => '',
            'TimeOnline'    => '',
            'NewestUsers'   => '',
			'Moderators'   => '',
        );

    	if (!$this->getConfig('Status_All'))
        {
            return;
        }

        $lang->load("topStats");
    	if ($this->getConfig('Status_LastThreads'))
        {
            $plugins->hooks["index_start"][10]["topStats_LastThreads"] = array("function" => create_function('', 'global $plugins; $plugins->objects[\'topStats\']->widget_LastThreads();'));
            $templatelist .= ',topStats_LastThreads,topStats_LastThreadsRow,topStats_LastThreadsAvatar';    
        }
		if ($this->getConfig('Status_LastActiveThreads'))
        {
            $plugins->hooks["index_start"][10]["topStats_LastActiveThreads"] = array("function" => create_function('', 'global $plugins; $plugins->objects[\'topStats\']->widget_LastActiveThreads();'));
            $templatelist .= ',topStats_LastActiveThreads,topStats_LastActiveThreadsRow,topStats_LastActiveThreadsAvatar';    
        }
    	if ($this->getConfig('Status_MostViews'))
        {
            $plugins->hooks["index_start"][10]["topStats_MostViews"] = array("function" => create_function('', 'global $plugins; $plugins->objects[\'topStats\']->widget_MostViews();'));
            $templatelist .= ',topStats_MostViews,topStats_MostViewsRow,topStats_MostViewsAvatar';    
        }
    	if ($this->getConfig('Status_Posters'))
        {
            $plugins->hooks["index_start"][10]["topStats_Posters"] = array("function" => create_function('', 'global $plugins; $plugins->objects[\'topStats\']->widget_Posters();'));
            $templatelist .= ',topStats_Posters,topStats_PostersRow,topStats_PostersAvatar';    
        }
    	if ($this->getConfig('Status_Reputation'))
        {
            $plugins->hooks["index_start"][10]["topStats_Reputation"] = array("function" => create_function('', 'global $plugins; $plugins->objects[\'topStats\']->widget_Reputation();'));
            $templatelist .= ',topStats_Reputation,topStats_ReputationRow,topStats_ReputationAvatar';    
        }
		if ($this->getConfig('Status_Referrals'))
        {
            $plugins->hooks["index_start"][10]["topStats_Referrals"] = array("function" => create_function('', 'global $plugins; $plugins->objects[\'topStats\']->widget_Referrals();'));
            $templatelist .= ',topStats_Referrals,topStats_ReferralsRow,topStats_ReferralsAvatar';    
        }
    	if ($this->getConfig('Status_TimeOnline'))
        {
            $plugins->hooks["index_start"][10]["topStats_TimeOnline"] = array("function" => create_function('', 'global $plugins; $plugins->objects[\'topStats\']->widget_TimeOnline();'));
            $templatelist .= ',topStats_TimeOnline,topStats_TimeOnlineRow,topStats_TimeOnlineAvatar';    
        }
    	if ($this->getConfig('Status_NewestUsers'))
        {
            $plugins->hooks["index_start"][10]["topStats_NewestUsers"] = array("function" => create_function('', 'global $plugins; $plugins->objects[\'topStats\']->widget_NewestUsers();'));
            $templatelist .= ',topStats_NewestUsers,topStats_NewestUsersRow,topStats_NewestUsersAvatar';    
        }   
	    if ($this->getConfig('Status_Moderators'))
        {
            $plugins->hooks["index_start"][10]["topStats_Moderators"] = array("function" => create_function('', 'global $plugins; $plugins->objects[\'topStats\']->widget_Moderators();'));
            $templatelist .= ',topStats_Moderators,topStats_ModeratorsRow,topStats_ModeratorsAvatar';    
        }  
    }
    
    /**
     * Widget with last threads list
     *   
     */ 
    public function widget_LastThreads()
    {   
        global $db, $lang, $mybb, $templates, $theme, $topStats;
		
		require_once MYBB_ROOT."inc/class_parser.php";
		require_once MYBB_ROOT."inc/functions_search.php";
		
		$parser = new postParser;
		
		$permsql = "";
		$onlyusfids = array();
		$group_permissions = forum_permissions();
		foreach($group_permissions as $fid => $forum_permissions)
		{
			if(isset($forum_permissions['canonlyviewownthreads']) && $forum_permissions['canonlyviewownthreads'] == 1)
			{
				$onlyusfids[] = $fid;
			}
		}
		if(!empty($onlyusfids))
		{
			$permsql .= "AND ((t.fid IN(".implode(',', $onlyusfids).") AND t.uid='{$mybb->user['uid']}') OR t.fid NOT IN(".implode(',', $onlyusfids)."))";
		}

		$unsearchforums = get_unsearchable_forums();
		if($unsearchforums)
		{
			$permsql .= " AND t.fid NOT IN ($unsearchforums)";
		}
		$inactiveforums = get_inactive_forums();
		if($inactiveforums)
		{
			$permsql .= " AND t.fid NOT IN ($inactiveforums)";
		}
		
		$tpl['ignore_forums'] = '';
		if(!empty($mybb->settings['topStats_IgnoreForums_LastThreads']))
		{
			$tpl['ignore_forums'] = "AND t.fid NOT IN ({$mybb->settings['topStats_IgnoreForums_LastThreads']})";
		}	

        $tpl['row'] = '';
    
        $sql = "SELECT t.uid, t.tid, t.subject, t.dateline, t.fid, u.usergroup, u.displaygroup, u.avatar, u.avatardimensions, u.username, u.uid
                FROM ".TABLE_PREFIX."threads AS t
                INNER JOIN ".TABLE_PREFIX."users AS u USING (uid) 
                WHERE 1=1 {$tpl['ignore_forums']} {$unapproved_where} {$permsql} AND t.visible='1' AND t.closed NOT LIKE 'moved|%'
                ORDER BY t.tid DESC LIMIT ". (int)$this->getConfig('Limit_LastThreads') ."";
        $result = $db->query($sql);
        while ($row = $db->fetch_array($result))
        {
			$forumpermissions[$row['fid']] = forum_permissions($row['fid']);

			if(isset($forumpermissions[$row['fid']]['canonlyviewownthreads']) && $forumpermissions[$row['fid']]['canonlyviewownthreads'] == 1 && $row['uid'] != $mybb->user['uid'])
			{
				continue;
			}
			
			$subject = $parser->parse_badwords(htmlspecialchars_uni($row['subject']));
			$tpl['subject'] = (my_strlen($subject) > 30) ? my_substr($subject, 0, 30) . "..." : $subject;
            $tpl['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);
    		$tpl['profilelink'] = build_profile_link($tpl['username'], $row['uid']);
            $tpl['date'] = my_date('relative', $row['dateline']);
    		$tpl['subjectlink'] = get_thread_link($row['tid']);
			$useravatar = format_avatar(htmlspecialchars_uni($row['avatar']), $row['avatardimensions'], my_strtolower($this->getConfig('AvatarWidth')));
            (!$this->getConfig('Status_Avatar')) ? '' : eval("\$tpl['avatar'] = \"".$templates->get("topStats_LastThreadsAvatar")."\";");
            eval("\$tpl['row'] .= \"" . $templates->get("topStats_LastThreadsRow") . "\";");
        }
        eval("\$topStats['LastThreads'] = \"" . $templates->get("topStats_LastThreads") . "\";");
    }
	
    /**
     * Widget with last active threads list
     *   
     */ 
    public function widget_LastActiveThreads()
    {   
        global $db, $lang, $mybb, $templates, $theme, $topStats;
		
		require_once MYBB_ROOT."inc/class_parser.php";
		require_once MYBB_ROOT."inc/functions_search.php";
		
		$parser = new postParser;
		
		$permsql = "";
		$onlyusfids = array();
		$group_permissions = forum_permissions();
		foreach($group_permissions as $fid => $forum_permissions)
		{
			if(isset($forum_permissions['canonlyviewownthreads']) && $forum_permissions['canonlyviewownthreads'] == 1)
			{
				$onlyusfids[] = $fid;
			}
		}
		if(!empty($onlyusfids))
		{
			$permsql .= "AND ((t.fid IN(".implode(',', $onlyusfids).") AND t.uid='{$mybb->user['uid']}') OR t.fid NOT IN(".implode(',', $onlyusfids)."))";
		}

		$unsearchforums = get_unsearchable_forums();
		if($unsearchforums)
		{
			$permsql .= " AND t.fid NOT IN ($unsearchforums)";
		}
		$inactiveforums = get_inactive_forums();
		if($inactiveforums)
		{
			$permsql .= " AND t.fid NOT IN ($inactiveforums)";
		}
		
		$tpl['ignore_forums'] = '';
		if(!empty($mybb->settings['topStats_IgnoreForums_LastActiveThreads']))
		{
			$tpl['ignore_forums'] = "AND t.fid NOT IN ({$mybb->settings['topStats_IgnoreForums_LastActiveThreads']})";
		}	

        $tpl['row'] = '';
    
        $sql = "SELECT t.lastposteruid, t.tid, t.subject, t.lastpost, t.fid, u.usergroup, u.displaygroup, u.avatar, u.avatardimensions, u.username, u.uid
                FROM ".TABLE_PREFIX."threads t
                INNER JOIN ".TABLE_PREFIX."users u ON (u.uid=t.lastposteruid) 
                WHERE 1=1 {$tpl['ignore_forums']} {$unapproved_where} {$permsql} AND t.visible='1' AND t.closed NOT LIKE 'moved|%'
                ORDER BY t.lastpost DESC LIMIT ". (int)$this->getConfig('Limit_LastActiveThreads') ."";
        $result = $db->query($sql);
        while ($row = $db->fetch_array($result))
        {
			$forumpermissions[$row['fid']] = forum_permissions($row['fid']);

			if(isset($forumpermissions[$row['fid']]['canonlyviewownthreads']) && $forumpermissions[$row['fid']]['canonlyviewownthreads'] == 1 && $row['uid'] != $mybb->user['uid'])
			{
				continue;
			}
			
			$subject = $parser->parse_badwords(htmlspecialchars_uni($row['subject']));
			$tpl['subject'] = (my_strlen($subject) > 30) ? my_substr($subject, 0, 30) . "..." : $subject;
            $tpl['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);
    		$tpl['profilelink'] = build_profile_link($tpl['username'], $row['uid']);
            $tpl['date'] = my_date('relative', $row['lastpost']);
    		$tpl['subjectlink'] = get_thread_link($row['tid']);
			$useravatar = format_avatar(htmlspecialchars_uni($row['avatar']), $row['avatardimensions'], my_strtolower($this->getConfig('AvatarWidth')));
            (!$this->getConfig('Status_Avatar')) ? '' : eval("\$tpl['avatar'] = \"".$templates->get("topStats_LastActiveThreadsAvatar")."\";");
            eval("\$tpl['row'] .= \"" . $templates->get("topStats_LastActiveThreadsRow") . "\";");
        }
        eval("\$topStats['LastActiveThreads'] = \"" . $templates->get("topStats_LastActiveThreads") . "\";");
    }
    
    /**
     * Widget with most views threads
     *   
     */ 
    public function widget_MostViews()
    {
        global $db, $lang, $mybb, $templates, $theme, $topStats;

		require_once MYBB_ROOT."inc/class_parser.php";
		require_once MYBB_ROOT."inc/functions_search.php";
		
		$parser = new postParser;
		
		$permsql = "";
		$onlyusfids = array();
		$group_permissions = forum_permissions();
		foreach($group_permissions as $fid => $forum_permissions)
		{
			if(isset($forum_permissions['canonlyviewownthreads']) && $forum_permissions['canonlyviewownthreads'] == 1)
			{
				$onlyusfids[] = $fid;
			}
		}
		if(!empty($onlyusfids))
		{
			$permsql .= "AND ((t.fid IN(".implode(',', $onlyusfids).") AND t.uid='{$mybb->user['uid']}') OR t.fid NOT IN(".implode(',', $onlyusfids)."))";
		}

		$unsearchforums = get_unsearchable_forums();
		if($unsearchforums)
		{
			$permsql .= " AND t.fid NOT IN ($unsearchforums)";
		}
		$inactiveforums = get_inactive_forums();
		if($inactiveforums)
		{
			$permsql .= " AND t.fid NOT IN ($inactiveforums)";
		}
		
		$tpl['ignore_forums'] = '';
		
		if(!empty($mybb->settings['topStats_IgnoreForums_MostViews']))
		{
			$tpl['ignore_forums'] = "AND t.fid NOT IN ({$mybb->settings['topStats_IgnoreForums_MostViews']})";
		}		

        $tpl['row'] = '';
    
        $sql = "SELECT t.uid, t.tid, t.subject, t.dateline, t.fid, t.views, u.usergroup, u.displaygroup, u.avatar, u.avatardimensions, u.username, u.uid
                FROM ".TABLE_PREFIX."threads AS t
                INNER JOIN ".TABLE_PREFIX."users AS u USING (uid) 
                WHERE 1=1 {$tpl['ignore_forums']} {$unapproved_where} {$permsql} AND t.visible='1' AND t.closed NOT LIKE 'moved|%'
                ORDER BY t.views DESC LIMIT ". (int)$this->getConfig('Limit_MostViews') ."";
        $result = $db->query($sql);
        while ($row = $db->fetch_array($result))
        {
			$forumpermissions[$row['fid']] = forum_permissions($row['fid']);

			if(isset($forumpermissions[$row['fid']]['canonlyviewownthreads']) && $forumpermissions[$row['fid']]['canonlyviewownthreads'] == 1 && $row['uid'] != $mybb->user['uid'])
			{
				continue;
			}
			
			$subject = $parser->parse_badwords(htmlspecialchars_uni($row['subject']));
			$tpl['subject'] = (my_strlen($subject) > 30) ? my_substr($subject, 0, 30) . "..." : $subject;
            $tpl['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);
    		$tpl['profilelink'] = build_profile_link($tpl['username'], $row['uid']);
            $tpl['date'] = my_date('relative', $row['dateline']);
    		$tpl['subjectlink'] = get_thread_link($row['tid']);
			$tpl['views'] = my_number_format($row['views']);
            $useravatar = format_avatar(htmlspecialchars_uni($row['avatar']), $row['avatardimensions'], my_strtolower($this->getConfig('AvatarWidth')));
            (!$this->getConfig('Status_Avatar')) ? '' : eval("\$tpl['avatar'] = \"".$templates->get("topStats_MostViewsAvatar")."\";");
            eval("\$tpl['row'] .= \"" . $templates->get("topStats_MostViewsRow") . "\";");
        }
        eval("\$topStats['MostViews'] = \"" . $templates->get("topStats_MostViews") . "\";");
    }
    
    /**
     * Widget with most posters list
     *   
     */ 
    public function widget_Posters()
    {
        global $db, $lang, $mybb, $templates, $theme, $topStats;

		$tpl['ignore_groups'] = '';
		
		if(!empty($mybb->settings['topStats_IgnoreGroups_Posters']))
		{
			$tpl['ignore_groups'] = " AND usergroup NOT IN ({$mybb->settings['topStats_IgnoreGroups_Posters']})";
		}		
		
		$lang->topStats_topPosters = $lang->sprintf($lang->topStats_topPosters, (int)$this->getConfig('Limit_Posters'));
		
        $tpl['row'] = '';
    
        $sql = "SELECT username, usergroup, displaygroup, postnum, uid, avatar, avatardimensions
                FROM ".TABLE_PREFIX."users 
				WHERE uid != '' ". $tpl['ignore_groups'] . "
                ORDER BY postnum DESC 
                LIMIT ".(int)$this->getConfig('Limit_Posters')."";
        $result = $db->query($sql);
        while ($row = $db->fetch_array($result))
        {
            $tpl['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);
    		$tpl['profilelink'] = build_profile_link($tpl['username'], $row['uid']);
    		$tpl['postnum'] = my_number_format($row['postnum']);
            $useravatar = format_avatar(htmlspecialchars_uni($row['avatar']), $row['avatardimensions'], my_strtolower($this->getConfig('AvatarWidth')));
            (!$this->getConfig('Status_Avatar')) ? '' : eval("\$tpl['avatar'] = \"".$templates->get("topStats_PostersAvatar")."\";"); 
            eval("\$tpl['row'] .= \"" . $templates->get("topStats_PostersRow") . "\";");
        }
        eval("\$topStats['Posters'] = \"" . $templates->get("topStats_Posters") . "\";");
    }
    /**
     * Widget with reputation list
     *   
     */ 
    public function widget_Reputation()
    {
        global $db, $lang, $mybb, $templates, $theme, $topStats;

		$tpl['ignore_groups'] = '';
		
		if(!empty($mybb->settings['topStats_IgnoreGroups_Reputation']))
		{
			$tpl['ignore_groups'] = " AND usergroup NOT IN ({$mybb->settings['topStats_IgnoreGroups_Reputation']})";
		}	
		
		$lang->topStats_topReputation = $lang->sprintf($lang->topStats_topReputation, (int)$this->getConfig('Limit_Reputation'));
		
        $tpl['row'] = '';
    
        $sql = "SELECT username, usergroup, displaygroup, reputation, uid, avatar, avatardimensions
                FROM ".TABLE_PREFIX."users 
				WHERE uid != '' ". $tpl['ignore_groups'] . "
                ORDER BY reputation DESC 
                LIMIT ". (int) $this->getConfig('Limit_Reputation') ."";
        $result = $db->query($sql);
        while ($row = $db->fetch_array($result))        
        {
            $tpl['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);
    		$tpl['profilelink'] = build_profile_link($tpl['username'], $row['uid']);
    		$tpl['reputation'] = my_number_format($row['reputation']);
            $useravatar = format_avatar(htmlspecialchars_uni($row['avatar']), $row['avatardimensions'], my_strtolower($this->getConfig('AvatarWidth')));
            (!$this->getConfig('Status_Avatar')) ? '' : eval("\$tpl['avatar'] = \"".$templates->get("topStats_ReputationAvatar")."\";");      
            eval("\$tpl['row'] .= \"" . $templates->get("topStats_ReputationRow") . "\";");
        }
        eval("\$topStats['Reputation'] = \"" . $templates->get("topStats_Reputation") . "\";");
    }
	/**
     * Widget with referrals list
     *   
     */ 
    public function widget_Referrals()
    {
        global $db, $lang, $mybb, $templates, $theme, $topStats;

		$tpl['ignore_groups'] = '';
		
		if(!empty($mybb->settings['topStats_IgnoreGroups_Referrals']))
		{
			$tpl['ignore_groups'] = " AND usergroup NOT IN ({$mybb->settings['topStats_IgnoreGroups_Referrals']})";
		}	
		
		$lang->topStats_topReferrals = $lang->sprintf($lang->topStats_topReferrals, (int)$this->getConfig('Limit_Referrals'));
		
        $tpl['row'] = '';
    
        $sql = "SELECT username, usergroup, displaygroup, referrals, uid, avatar, avatardimensions
                FROM ".TABLE_PREFIX."users 
				WHERE uid != '' ". $tpl['ignore_groups'] . "
                ORDER BY referrals DESC 
                LIMIT ". (int) $this->getConfig('Limit_Referrals') ."";
        $result = $db->query($sql);
        while ($row = $db->fetch_array($result))        
        {
			$tpl['limit'] = (int) $this->getConfig('Limit_Referrals');
            $tpl['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);
    		$tpl['profilelink'] = build_profile_link($tpl['username'], $row['uid']);
    		$tpl['referrals'] = my_number_format($row['referrals']);
            $useravatar = format_avatar(htmlspecialchars_uni($row['avatar']), $row['avatardimensions'], my_strtolower($this->getConfig('AvatarWidth')));
            (!$this->getConfig('Status_Avatar')) ? '' : eval("\$tpl['avatar'] = \"".$templates->get("topStats_ReferralsAvatar")."\";");      
            eval("\$tpl['row'] .= \"" . $templates->get("topStats_ReferralsRow") . "\";");
        }
        eval("\$topStats['Referrals'] = \"" . $templates->get("topStats_Referrals") . "\";");
    }

    /**
     * Widget with users online time
     *   
     */ 
    public function widget_TimeOnline()
    {
        global $db, $lang, $mybb, $templates, $theme, $topStats;

		$tpl['ignore_groups'] = '';
		
		if(!empty($mybb->settings['topStats_IgnoreGroups_TimeOnline']))
		{
			$tpl['ignore_groups'] = " AND usergroup NOT IN ({$mybb->settings['topStats_IgnoreGroups_TimeOnline']})";
		}	
		
		$lang->topStats_topOnline = $lang->sprintf($lang->topStats_topOnline, (int)$this->getConfig('Limit_TimeOnline'));
		
        $tpl['row'] = '';

        $sql = "SELECT username, usergroup, displaygroup, TimeOnline, uid, avatar, avatardimensions
                FROM ".TABLE_PREFIX."users 
				WHERE uid != '' ". $tpl['ignore_groups'] . "
                ORDER BY TimeOnline DESC 
                LIMIT ". (int) $this->getConfig('Limit_TimeOnline')."";
        $result = $db->query($sql);
        while ($row = $db->fetch_array($result))        
        {
			$tpl['limit'] = (int) $this->getConfig('Limit_TimeOnline');
            $tpl['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);
    		$tpl['profilelink'] = build_profile_link($tpl['username'], $row['uid']);
    		$tpl['time'] = ($row['TimeOnline'] > 0) ? nice_time($row['TimeOnline'], array('years' => false, 'seconds' => false, 'short' => 1)) : $lang->none_registered;
            $useravatar = format_avatar(htmlspecialchars_uni($row['avatar']), $row['avatardimensions'], my_strtolower($this->getConfig('AvatarWidth')));
            (!$this->getConfig('Status_Avatar')) ? '' : eval("\$tpl['avatar'] = \"".$templates->get("topStats_NewestUsersAvatar")."\";"); 
            eval("\$tpl['row'] .= \"" . $templates->get("topStats_TimeOnlineRow") . "\";");
        }
        eval("\$topStats['TimeOnline'] = \"" . $templates->get("topStats_TimeOnline") . "\";");
    }
	
	    /**
     * Widget with newest users
     *   
     */ 
    public function widget_NewestUsers()
    {
        global $db, $lang, $mybb, $templates, $theme, $topStats;

		$tpl['ignore_groups'] = '';
		
		if(!empty($mybb->settings['topStats_IgnoreGroups_NewestUsers']))
		{
			$tpl['ignore_groups'] = " AND usergroup NOT IN ({$mybb->settings['topStats_IgnoreGroups_NewestUsers']})";
		}	
		
		$lang->topStats_NewestUsers = $lang->sprintf($lang->topStats_NewestUsers, (int)$this->getConfig('Limit_NewestUsers'));
		
        $tpl['row'] = '';
    
        $sql = "SELECT username, usergroup, displaygroup, regdate, postnum, uid, avatar, avatardimensions
                FROM ".TABLE_PREFIX."users 
				WHERE 1=1 {$tpl['ignore_groups']}
                ORDER BY regdate DESC 
                LIMIT ".(int) $this->getConfig('Limit_NewestUsers')."";
        $result = $db->query($sql);
        while ($row = $db->fetch_array($result))        
        {
            $tpl['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);
    		$tpl['profilelink'] = build_profile_link($tpl['username'], $row['uid']);
    		$tpl['date'] = my_date('relative', $row['regdate']);
            $useravatar = format_avatar(htmlspecialchars_uni($row['avatar']), $row['avatardimensions'], my_strtolower($this->getConfig('AvatarWidth')));
            (!$this->getConfig('Status_Avatar')) ? '' : eval("\$tpl['avatar'] = \"".$templates->get("topStats_NewestUsersAvatar")."\";");   
            eval("\$tpl['row'] .= \"" . $templates->get("topStats_NewestUsersRow") . "\";");
        }
        eval("\$topStats['NewestUsers'] = \"" . $templates->get("topStats_NewestUsers") . "\";");
    }
	
	    /**
     * Widget with most activ moderators
     *   
     */ 
    public function widget_Moderators()
    {
        global $db, $lang, $mybb, $templates, $theme, $topStats;
		
		$lang->topStats_topModerators = $lang->sprintf($lang->topStats_topModerators, (int)$this->getConfig('Limit_Moderators'));

        $tpl['row'] = '';
    
        $sql = "SELECT ml.uid, u.username, u.usergroup, u.displaygroup, u.uid, u.avatar, u.avatardimensions, count(*) as totalactions
                FROM ".TABLE_PREFIX."moderatorlog as ml 
				 INNER JOIN ".TABLE_PREFIX."users as u ON (ml.uid=u.uid)
				WHERE 1=1
                GROUP BY ml.uid 
				ORDER BY totalactions  DESC 
                LIMIT ".(int) $this->getConfig('Limit_Moderators')."";
        $result = $db->query($sql);
        while ($row = $db->fetch_array($result))        
        {
            $tpl['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);
    		$tpl['profilelink'] = build_profile_link($tpl['username'], $row['uid']);
    		$tpl['actions'] = my_number_format($row['totalactions']);
            $useravatar = format_avatar(htmlspecialchars_uni($row['avatar']), $row['avatardimensions'], my_strtolower($this->getConfig('AvatarWidth')));
            (!$this->getConfig('Status_Avatar')) ? '' : eval("\$tpl['avatar'] = \"".$templates->get("topStats_ModeratorsAvatar")."\";");   
            eval("\$tpl['row'] .= \"" . $templates->get("topStats_ModeratorsRow") . "\";");
        }
        eval("\$topStats['Moderators'] = \"" . $templates->get("topStats_Moderators") . "\";");
    }
    
    /**
     * Helper function to get variable from config
     * 
     * @param string $name Name of config to get
     * @return string Data config from MyBB Settings
     */
    private function getConfig($name)
    {
        global $mybb;
    
        return $mybb->settings["topStats_{$name}"];
    }

}
