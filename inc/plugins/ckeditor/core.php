<?php
// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
    die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

/**
 * DEFINE PLUGINLIBRARY
 *
 *   Define the path to the plugin library, if it isn't defined yet.
 */
if(!defined("PLUGINLIBRARY"))
{
    define("PLUGINLIBRARY", MYBB_ROOT."inc/plugins/pluginlibrary.php");
}

/* Functions - start*/
function ckplugin_gettemplate($title, $eslashes=1, $htmlcomments=1) {
	$file = CKEDITOR_PLUGINROOT.'ckeditor.'.$title.'.tpl';
	if(!file_exists($file)) {
		return false;
	}
	$template = file_get_contents($file);
	if($htmlcomments)
	{
		if($mybb->settings['tplhtmlcomments'] == 1)
		{
			$template = "<!-- start: ".htmlspecialchars_uni($title)." -->\n{$template}\n<!-- end: ".htmlspecialchars_uni($title)." -->";
		}
		else
		{
			$template = "\n{$template}\n";
		}
	}
	
	if($eslashes)
	{
		$template = str_replace("\\'", "'", addslashes($template));
	}
	return $template;
}

function is_ckeditor_avilable($file = THIS_SCRIPT)
{
	global $mybb;
	$dsblocations = $mybb->settings['ckeditor_disablelocation'];
	$dsblocations = str_replace(array("\r", " "), '', $dsblocations);
	$dsblocations = explode("\n", $dsblocations);
	if(in_array($file, $dsblocations))
	{
		return false;
	}
	else
	{
		return true;
	}
}

function is_ckeditor_mini($file = THIS_SCRIPT)
{
	global $mybb;
	$dsblocations = $mybb->settings['ckeditor_minilocation'];
	$dsblocations = str_replace(array("\r", " "), '', $dsblocations);
	$dsblocations = explode("\n", $dsblocations);
	if(in_array($file, $dsblocations))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function ckeditor_build($bind="message", $smilies = true) {
	global $db, $mybb, $theme, $templates, $lang, $plugins, $headerinclude;
	$lang->load('ckeditor');
	$codeinsert = $minifolder = '';
	$mini = is_ckeditor_mini();
	if($mini == true)
	{
		$minifolder = 'mini';
	}

	if($mybb->settings['bbcodeinserter'] != 0)
	{
		if($lang->settings['rtl'] == true) {
			$direction = 'rtl';
		} else {
			$direction = 'ltr';
		}
		$smilies = ckesmiliesjs_build();
		$smiliesmap = ckesmiliesjs_build(1);
		$divarea = $jsfiles = '';
		if($mybb->settings['ckeditor_usedivarea']) {
			$divarea = 'divarea,';
		}
		if(!isset($headerinclude) || !stristr('jscripts/ckeditor/ckeditor.js',$headerinclude)) {
			$jsfiles = '<script type="text/javascript" src="<bburl>/jscripts/ckeditor'.$minifolder.'/ckeditor.js"></script><script type="text/javascript" src="<bburl>/jscripts/ckeditor/editor.js"></script>';
		}
		if($jsfiles != '' && THIS_SCRIPT == 'showthread.php') // Quick Quote
		{
			$jsfiles .= '<script type="text/javascript" src="<bburl>/jscripts/Thread.quickquote.js"></script>';
		}
		if(isset($headerinclude))
		{
			$headerinclude .= str_replace('<bburl>', $mybb->asset_url, $jsfiles);
			$jsfiles = '';
		}
		if(defined("IN_ADMINCP"))
		{
			eval("\$codeinsert = \"".ckplugin_gettemplate("codebuttons")."\";");
		}
		else
		{
			eval("\$codeinsert = \"".$templates->get("ckeditor_codebuttons")."\";");
		}
		$codeinsert = str_replace('<bburl>', $mybb->asset_url, $codeinsert);
	}
	
	return $codeinsert;
	
}

function ckesmiliesjs_build($finds = 0)
{
	global $cache, $theme, $templates, $lang, $mybb;

	if($mybb->settings['smilieinserter'] != 0 && $mybb->settings['smilieinsertercols'] && $mybb->settings['smilieinsertertot'])
	{
		if(!$smiliecount)
		{
			$smilie_cache = $cache->read("smilies");
			$smiliecount = count($smilie_cache);
		}

		if(!$smiliecache)
		{
			if(!is_array($smilie_cache))
			{
				$smilie_cache = $cache->read("smilies");
			}
			foreach($smilie_cache as $smilie)
			{
				if($smilie['showclickable'] != 0)
				{
					$smiliecache[$smilie['find']] = $smilie['image'];
				}
			}
		}

		unset($smilie);

		if(is_array($smiliecache))
		{
			reset($smiliecache);
			$mysmilies = $smiliecache;
			arsort($mysmilies);
			$getmore = '';
			if($mybb->settings['smilieinsertertot'] >= $smiliecount)
			{
				$mybb->settings['smilieinsertertot'] = $smiliecount;
			}
			
			$smilies1 = "";
			$smilies2 = "";
			$smilies3 = "";
			$smilies4 = "";
			$smilies5 = "";
			$counter = 0;
			$i = 0;

			foreach($mysmilies as $find => $image)
			{
				$find_multi = explode('\n', $find);
				$find = $find_multi[0];
				$find = addslashes(htmlspecialchars_uni($find));
				$image = addslashes($image);
				$image2 = ((substr($image, 0, 4) != "http" && defined("IN_ADMINCP"))?('../'.$image):$image);
				$smilies1 .= "'".$image2."', ";
				$smilies2 .= "'smilie{$i}', ";
				$smilies3 .= "'smilie{$i}': '".$find."', ";
				$smilies4 .= "'".$image."': 'smilie{$i}', ";
				$smilies5 .= <<<EOF
	bbcodeParser.addBBCode('{$find}', '<img src="{$image2}" data-cke-saved-src="{$image}" title="smilie{$i}" alt="smilie{$i}" class=\"smilie smilie_{$smilie['sid']}\">');
EOF;
				if(substr($image, 0, 4) != "http") {
					$image = $mybb->settings['bburl']."/".$image;
					$smilies4 .= "'".$image."': 'smilie{$i}', ";
				}
				++$i;
				++$counter;
					if($counter == $mybb->settings['smilieinsertercols'])
				{
					$counter = 0;
				}
			}
			if($finds) {
				$clickablesmilies = "var smiliesmap = { {$smilies3} };";
				$clickablesmilies .= "var smilieyurlmap = { {$smilies4} };";
				$clickablesmilies .= $smilies5;
			} else {
				$clickablesmilies = "smiley_images: [\n{$smilies1}\n],\n smiley_descriptions: [\n{$smilies2}\n]";
			}
		}
		else
		{
			$clickablesmilies = "";
		}
	}
	else
	{
		$clickablesmilies = "";
	}

	return $clickablesmilies;
}

function ckeparser_smilies($message)
{
	global $cache, $theme, $templates, $lang, $mybb;

	if($mybb->settings['smilieinserter'] != 0 && $mybb->settings['smilieinsertercols'] && $mybb->settings['smilieinsertertot'])
	{
		if(!$smiliecount)
		{
			$smilie_cache = $cache->read("smilies");
			$smiliecount = count($smilie_cache);
		}

		if(!$smiliecache)
		{
			if(!is_array($smilie_cache))
			{
				$smilie_cache = $cache->read("smilies");
			}
			foreach($smilie_cache as $smilie)
			{
				if($smilie['showclickable'] != 0)
				{
					$smiliecache[$smilie['find']] = $smilie['image'];
				}
			}
		}

		unset($smilie);

		if(is_array($smiliecache))
		{
			reset($smiliecache);
			$mysmilies = $smiliecache;
			arsort($mysmilies);
			$getmore = '';
			if($mybb->settings['smilieinsertertot'] >= $smiliecount)
			{
				$mybb->settings['smilieinsertertot'] = $smiliecount;
			}
			
			$counter = 0;
			$i = 0;

			foreach($mysmilies as $find => $image)
			{
				$find = htmlspecialchars_uni($find);
				$find = explode('\n', $find);
				$image = htmlspecialchars_uni($image);
				$image2 = ((substr($image, 0, 4) != "http" && defined("IN_ADMINCP"))?('../'.$image):$image);
				$message = str_ireplace($find, "<img src=\"{$image2}\" data-cke-saved-src=\"{$image}\" title=\"smilie{$i}\" alt=\"smilie{$i}\" class=\"smilie smilie_{$smilie['sid']}\">", $message);
				++$i;
			}
		}
	}

	return $message;
}

function ckeditor_getthemeeditors() {
	global $setting;
	$select = '<select name="upsetting['.$setting['name'].']">';
	$options = array();
	$editor_theme_root = MYBB_ROOT."jscripts/ckeditor/skins/";
	if($dh = @opendir($editor_theme_root))
	{
		while($dir = readdir($dh))
		{
			if($dir == ".svn" || $dir == "." || $dir == ".." || !is_dir($editor_theme_root.$dir))
			{
				continue;
			}
			$options[$dir] = ucfirst(str_replace('_', ' ', $dir));
			if ($setting['value'] == $dir)
			{
				$select .= '<option value="'.$dir.'" selected="selected">'.$options[$dir].'</option>';
			}
			else
			{
				$select .= '<option value="'.$dir.'">'.$options[$dir].'</option>';
			}
		}
	}
	$select .= '</select>';
	return $select;

}

/* Functions - end */
