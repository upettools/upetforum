<?php
###################################
# Plugin AutoMedia for MyBB*#
# (c) 2009-2018 by doylecc    #
# Website: http://mybbplugins.tk #
###################################

$l['av_plugin_title'] = "AutoMedia";
$l['av_plugin_descr'] = "Embeds automatically videos and music from different sites in posts.<br />For more information view <a href=\"../inc/plugins/automedia/automedia_doc_en.html\" target=\"_blank\">documentation</a>.";
$l['av_unsupported'] = "cURL and fsockopen is not supported on your server! Without it many files CANNOT be embedded if embed.ly isn't enabled in the plugin settings. For more information view Readme.txt - \"cURL and fsockopen support.\"";
$l['av_php_version'] = "PHP Version too old! AutoMedia 3.x requires PHP 5.3.0 or higher.";
$l['av_group_global_title'] = "AutoMedia Global";
$l['av_group_global_descr'] = "Global Settings for the AutoMedia Plugin";
$l['av_enable_title'] = "Enable AutoMedia for \"AutoMedia Sites\"?";
$l['av_enable_descr'] = "Select if you want the selected media from \"AutoMedia Sites\" to be shown.";
$l['av_guest_title'] = "Enable AutoMedia for Guests?";
$l['av_guest_descr'] = "Select if you want the selected media to be shown for guests.";
$l['av_groups_title'] = "Disallow AutoMedia for the following Usergroups:";
$l['av_groups_descr'] = "Please select the usergroup(s) you wish the selected media not to be shown.";
$l['av_forums_title'] = "Show AutoMedia in the following Forums:";
$l['av_forums_descr'] = "Please select the forum(s) you wish to enable AutoMedia in.";
$l['av_adultsites_title'] = "Enable embedding of Adult Sites?";
$l['av_adultsites_descr'] = "Select if you want the adult media to be shown.";
$l['av_adultguest_title'] = "Enable Adult Site Videos for Guests?";
$l['av_adultguest_descr'] = "Select if you want the adult media to be shown for guests.";
$l['av_adultgroups_title'] = "Allow Adult Site Videos for the following Usergroups:";
$l['av_adultgroups_descr'] = "Please select the usergroup(s) you wish the adult media to be shown.";
$l['av_adultforums_title'] = "Allow Adult Site Videos for the following Forums:";
$l['av_adultforums_descr'] = "Please select the forum(s) you wish the adult media to be shown.";
$l['av_signature_title'] = "Enable AutoMedia for \"AutoMedia Sites\" in signatures?";
$l['av_signature_descr'] = "Select if you want the selected media from \"AutoMedia Sites\" to be shown in signatures.";
$l['av_flashadmin_title'] = "Permission for embedding of flash files";
$l['av_flashadmin_descr'] = "Select who is allowed to embed FLV and SWF flash files.";
$l['av_flashadmin_admins'] = "Admins";
$l['av_flashadmin_mods'] = "Admins, Supermods, Mods";
$l['av_flashadmin_all'] = "All Users";
$l['av_width_title'] = "Max. width of embedded media.";
$l['av_width_descr'] = "Pleae enter the max. width in pixel.";
$l['av_height_title'] = "Max. height of embedded media.";
$l['av_height_descr'] = "Please enter the max. height in pixel.";
$l['av_embera_title'] = "Use libraries for embedding content";
$l['av_embera_descr'] = "Use the Embera PHP library with support for more than 65 providers or the oEmbed library for embedding?";
$l['av_embera_embera'] = "Use Embera PHP library";
$l['av_embera_oembed'] = "Use oEmbed jQuery library";
$l['av_embera_both'] = "Use Embera and oEmbed jQuery libraries";
$l['av_embera_none'] = "Use None";
$l['av_embedly_title'] = "Use Embed Service";
$l['av_embedly_descr'] = "Use <a href=\"http://embed.ly\" title=\"embed.ly\" target=\"_blank\" rel=\"noopener\">embed.ly</a> or <a href=\"http://urlembed.com\" title=\"urlembed.com\" target=\"_blank\" rel=\"noopener\">urlembed.com</a> embed service";
$l['av_embedly_none_embed'] = "Do not use any Embed Service";
$l['av_embedly_free_embed'] = "Use free embed.ly cards without API key and registration";
$l['av_embedly_paid_embed'] = "Use free urlembed.com embedding without API key and registration";
$l['av_attachments_title'] = "Embed Attachments?";
$l['av_attachments_descr'] = "Embed Video/Audio attachments which are inserted into the post?";
$l['av_codebuttons_title'] = "Show codebuttons for MP3 Playlist and Deactivation MyCodes";
$l['av_codebuttons_descr'] = "Choose if you want the codebuttons for inserting the MP3 Playlist ([ampl][/ampl]) and Deactivation ([amoff][/amoff]) MyCodes to be shown.";
$l['av_quote_title'] = "AutoMedia in Quotes?";
$l['av_quote_descr'] = "Select if you want the videos to be shown in quoted posts.";
$l['av_attachdl_title'] = "Display Attachment Links";
$l['av_attachdl_descr'] = "Additionally display the download links of embedded attachments below the post?";
$l['av_local_title'] = "Embed Local Links";
$l['av_local_descr'] = "Select if you want embed internal forum links.";

$l['av_templategroup'] = "AutoMedia Plugin";
$l['av_delete_confirm'] = "Try to delete all AutoMedia plugin files?";
$l['av_undeleted'] = "<p><b>AutoMedia was not able to delete the following files and directories:</b></p>";

$l['automedia'] = "AutoMedia";
$l['automedia_settings'] = "Plugin settings";
$l['can_view_automedia'] = "Can view AutoMedia modules";
$l['automedia_modules'] = "Manage installed custom AutoMedia modules";
$l['automedia_modules_description1'] = "Shows currently installed and active custom modules.<br /> To remove deactivated custom modules delete the according PHP files in the folder <strong>inc/plugins/automedia/mediasites</strong> via FTP.<br />To add new modules upload the according PHP files into the same folder and activate it here.";
$l['automedia_modules_description2'] = "Shows currently installed and active custom modules.";
$l['automedia_adult'] = "Installed Adult Sites modules";
$l['automedia_adult_description1'] = "Shows currently installed and active adult modules.<br /> To remove deactivated custom modules delete the according PHP files in the folder <strong>inc/plugins/automedia/special</strong> via FTP.<br />To add new modules upload the according PHP files into the same folder and activate it here.";
$l['automedia_adult_description2'] = "List modules:";
$l['automedia_modules_options'] = "Options";
$l['automedia_modules_viewcode'] = "Shows the embed code";
$l['automedia_modules_showcode'] = "Show code";
$l['automedia_modules_deleted'] = "Module successful deactivated";
$l['automedia_modules_active'] = "Module successful activated";
$l['automedia_modules_notfound'] = "Module not found!";
$l['automedia_modules_activate'] = "<span style=\"color:#EE0000;\">Activate</span>";
$l['automedia_modules_activateall'] = "<strong>Activate all</strong>";
$l['automedia_modules_deactivate'] = "<span style=\"color:#00AA00;\">Deactivate</span>";
$l['automedia_modules_missing_sitesfolder'] = "<span style=\"color:#EE0000;\">Folder inc/plugins/automedia/<strong>mediasites</strong> doesn't exist!</span>";
$l['automedia_modules_missing_specialfolder'] = "<span style=\"color:#EE0000;\">Folder inc/plugins/automedia/<strong>special</strong> doesn't exist!</span>";
$l['automedia_template_edits1'] = "Reapply template edits";
$l['automedia_template_edits2'] = "(e.g. after reverting your templates)";
$l['automedia_oembed'] = "AutoMedia oEmbed API";
$l['automedia_oembed_desc'] = "Active AutoMedia oEmbed Libraries and Services";
$l['automedia_modules_embedly'] = "urlembed.com oEmbed API is activated in <a href=\"index.php?module=config&amp;action=change&amp;search=automedia\" title=\"\" target=\"_blank\" rel=\"noopener\">AutoMedia settings</a>. For all supported providers <a href=\"http://urlembed.com/provider\" title=\"urlembed.com\" target=\"_blank\" rel=\"noopener\"><b>click here</b></a>.";
$l['automedia_modules_embedly_free'] = "Embed.ly oEmbed Cards Free Service is activated in <a href=\"index.php?module=config&amp;action=change&amp;search=automedia\" title=\"\" target=\"_blank\" rel=\"noopener\">AutoMedia settings</a>. For all supported providers <a href=\"http://embed.ly/providers\" title=\"Embed.ly\" target=\"_blank\" rel=\"noopener\"><b>click here</b></a>.";
$l['automedia_modules_success'] = "Activated";
$l['automedia_modules_fail'] = "Deactivated";
$l['automedia_modules_status'] = "Status:";
$l['automedia_modules_embedcode'] = "Embed Code";
