<?php
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

function imgbb_info()
{
	return array(
		"name"			=> "Simple Image Upload",
		"description"	=> "This mod integrates image hosting with MyBB. It makes image upload very simple. All images are hosted on remote image hosting service, not on your forum. When user uploads image, script creates a thumbnail for image and appends bbcode to post user is typing. User doesn't need to know anything about bbcode.",
		"website"		=> "http://imgbb.com/mod",
		"author"		=> "Sium",
		"authorsite"	=> "http://imgbb.com/",
		"version"		=> "2.0.1",
		"guid" 			=> "c8c51ff9657563a71feda18327c11337",
		"compatibility" => "*"
	);
}

$plugins->add_hook("pre_output_page","imgbb");

function imgbb($page)
{
	return str_replace("</body>",'<script async type="text/javascript" src="jscripts/imgbb.js" charset="utf-8"></script>'."\n".'</body>',$page);
}
?>