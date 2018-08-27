<?php
/*  latest threads to be placed on index page */
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}
// Plugin hook
$plugins->add_hook("index_end", "latestthreads");

// Plugin info
function latestthreads_info()
{
	return array(
		"name"			=> "latest threads",
		"description"	=> "to add latest threads on index",
		"website"		=> "...",
		"author"		=> "...",
		"authorsite"	=> "...",
		"version"		=> "1.0",
		"guid" 		=> "",
		"compatibility" => "1*"
		
	);
}
// Run plugin

function latestthreads()
{
    global $mybb, $db, $latestthreads;    
	
	$max = 10;
	
	$query = $db->query("SELECT * FROM ".TABLE_PREFIX."threads ORDER BY `tid` DESC LIMIT $max"); 
    $latestthreads = '<marquee scrollamount=3>';
	while($result = $db->fetch_array($query))
		{
        $latestthreads .= "*&nbsp;<a href=\"showthread.php?tid={$result['tid']}\">".htmlspecialchars_uni($result['subject'])."</a>";
        $latestthreads .= "&nbsp;&nbsp;";
    	}
		$latestthreads .= '</marquee>';
		
		return $latestthreads;
}
?>