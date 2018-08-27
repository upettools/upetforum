// automedia.js for AutoMedia plugin 4.0
"use strict";

var SCRIPT = AM_SCRIPT;
var ACTIVE = AM_ACTIVE;
var GROUPS = AM_GROUPS;
var FORUMS = AM_FORUMS;
var SIGNATURE = AM_SIGNATURE;
var EDITSIG = AM_EDITSIG;
var EMBEDLY = AM_EMBEDLY;
var URLEMBED = AM_URLEMBED;
var CARDTHEME = AM_CARDTHEME;
var MAXWIDTH = AM_MAXWIDTH;
var MAXHEIGHT = AM_MAXHEIGHT;
var HEIGHT = '100%';
var WIDTH = '100%';
var PATH = AM_PATH;
var DLLINK = AM_DLLINK;
var ATTACH = AM_ATTACH;
var LOCAL = AM_LOCAL;
var QUOTES = AM_QUOTES;
var TIMEOUT = AM_TIMEOUT;
if (typeof AM_SPECIAL === 'undefined') {
    var SPECIAL = 0;
} else {
    var SPECIAL = 1;
}

// Embedding disabled by [amoff] MyCode
var amoffMycode = function () {
    $('.am_noembed').find('.mycode_url, .embedly-card, .urlembed').addClass('amoff');
    $('.amoff').removeClass('mycode_url').removeClass('embedly-card').removeClass('urlembed');
}

// Embed specific media files
var embedMedia = function (page) {
    page.each(function() {
        var file = $(this).attr('href');

        // Check if local link embedding is enabled
        if (LOCAL < 1 && file.indexOf(PATH) !== -1) {
            $(this).attr('class', 'amoff');

        }

        if (!$(this).hasClass('amoff')) {
            // Embed audio files
            if (file.split('.').pop() == 'mp3' || file.split('.').pop() == 'm4a' || file.split('.').pop() == 'ogg' || file.split('.').pop() == 'wav') {
                $(this).outerHTML('<div class="am_embed"><audio id="player" controls="control" preload="none"><source src="'+file+'" type="audio/'+file.split('.').pop()+'" /></audio></div>');
            }

            // Embed video files
            // mp4, m4v, mp4v, webm
            if (file.split('.').pop() == 'mp4' || file.split('.').pop() == 'm4v' || file.split('.').pop() == 'mp4v' || file.split('.').pop() == 'webm') {
                $(this).outerHTML("<div class=\"am_embed\">\n<video id=\"player\" style=\"width:"+WIDTH+";height:"+HEIGHT+";width="+WIDTH+" height="+HEIGHT+"\" controls=\"controls\" width=\""+WIDTH+"\" height=\""+HEIGHT+"\"><source src=\""+file+"\" type=\"video/"+file.split('.').pop()+"\" />\n<object type=\"application/x-shockwave-flash\" data=\""+PATH+"/inc/plugins/automedia/mediaplayer/flowplayer-3.2.18.swf\" width=\""+WIDTH+"\" height=\""+HEIGHT+"\" style=\"max-width:"+MAXWIDTH+";max-height:"+MAXHEIGHT+";\">\n<param name=\"movie\" value=\""+PATH+"/inc/plugins/automedia/mediaplayer/flowplayer-3.2.18.swf\" />\n<param name=\"allowFullScreen\" value=\"true\" />\n<param name=\"wmode\" value=\"transparent\" />\n<param name=\"flashVars\" value=\"config={'playlist':[{'url':'"+file+"','autoPlay':false}]}\" />\n</object>\n</video>\n</div>");
            }
            // ogg
            if (file.split('.').pop() == 'ogg') {
                $(this).outerHTML("<div class=\"am_embed\">\n<video id=\"player\"  controls=\"controls\" width="+WIDTH+" height="+HEIGHT+"><source src=\""+file+"\" type=\"video/ogg\" />\n<object type=\"application/x-shockwave-flash\" data=\""+PATH+"/inc/plugins/automedia/mediaplayer/flowplayer-3.2.18.swf\" width=\""+WIDTH+"\" height=\""+HEIGHT+"\">\n<param name=\"movie\" value=\""+PATH+"/inc/plugins/automedia/mediaplayer/flowplayer-3.2.18.swf\" />\n<param name=\"allowFullScreen\" value=\"true\" />\n<param name=\"wmode\" value=\"transparent\" />\n<param name=\"flashVars\" value=\"config={'playlist':[{'url':'"+file+"','autoPlay':false}]}\" />\n</object>\n</video>\n</div>");
            }
            // avi, divx, mkv
            if (file.split('.').pop() == 'avi' || file.split('.').pop() == 'mkv' || file.split('.').pop() == 'divx') {
                $(this).outerHTML("<div class=\"am_embed\"><object classid=\"clsid:67DABFBF-D0AB-41fa-9C46-CC0F21721616\" width="+WIDTH+" height="+HEIGHT+" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\" codebase=\"http://go.divx.com/plugin/DivXBrowserPlugin.cab\"><param name=\"custommode\" value=\"none\" /><param name=\"autoPlay\" value=\"false\" /><param name=\"src\" value=\""+file+"\" /><embed type=\"video/divx\" src=\""+file+"\" custommode=\"none\" width=\""+WIDTH+"\" height=\""+HEIGHT+"\" tyle=\"max-width:"+MAXWIDTH+";max-height:"+MAXHEIGHT+";\" autoPlay=\"false\"  pluginspage=\"http://go.divx.com/plugin/download/\"></embed></object><br />No video? <a href=\"http://www.divx.com/software/divx-plus/web-player\" target=\"_blank\">Download</a> the DivX Plus Web Player.</div>");
            }
            // flv
            if (file.split('.').pop() == 'flv') {
                $(this).outerHTML("<div class=\"am_embed\"><object id=\"flowplayer\" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\" data=\""+PATH+"/inc/plugins/automedia/mediaplayer/flowplayer-3.2.18.swf\" type=\"application/x-shockwave-flash\"><param name=\"movie\" value=\""+PATH+"/inc/plugins/automedia/mediaplayer/flowplayer-3.2.18.swf\" /><param name=\"allowfullscreen\" value=\"true\" /><param name=\"flashvars\" value='config={\"clip\":{\"url\":\""+file+"\",\"autoPlay\":false}}' /></object></div>");
            }
            // mpg, mpeg
            if (file.split('.').pop() == 'mpg' || file.split('.').pop() == 'mpeg') {
                $(this).outerHTML("<div class=\"am_embed\"><object id=\"ImageWindow\" classid=\"clsid:CLSID:05589FA1-C356-11CE-BF01-00AA0055595A\" width="+WIDTH+" height="+HEIGHT+" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\"><param name=\"src\" value=\""+FILE+"\" /><param name=\"autostart\" value=\"0\" /><embed src=\""+FILE+"\" type=\"video/mpeg\" width=\""+WIDTH+"\" height=\""+HEIGHT+"\" style=\"max-width:"+MAXWIDTH+";max-height:"+MAXHEIGHT+";\" autostart=\"false\"></embed></object></div>");
            }
            // mov
            if (file.split('.').pop() == 'mov') {
                $(this).outerHTML("<div class=\"am_embed\"><object classid=\"clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B\" width="+WIDTH+" height="+HEIGHT+" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\" codebase=\"http://www.apple.com/qtactivex/qtplugin.cab\"><param name=\"src\" value=\""+FILE+"\" /><param name=\"autoplay\" value=\"false\" /><param name=\"controller\" value=\"true\" /><embed src=\""+FILE+"\" width=\""+WIDTH+"\" height=\""+HEIGHT+"\" style=\"max-width:"+MAXWIDTH+";max-height:"+MAXHEIGHT+";\" autoplay=\"false\" controller=\"true\" pluginspage=\"http://www.apple.com/quicktime/download/\"></embed></object></div>");
            }
            // ra, rm, ram, rpm, rv, smil
            if (file.split('.').pop() == 'ra' || file.split('.').pop() == 'rm' || file.split('.').pop() == 'ram' || file.split('.').pop() == 'rpm' || file.split('.').pop() == 'rv' || file.split('.').pop() == 'smil') {
                $(this).outerHTML("<div class=\"am_embed\"><object id=\"RVOCX\" classid=\"clsid:CFCDAA03-8BE4-11CF-B84B-0020AFBBCCFA\" width="+WIDTH+" height="+HEIGHT+" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\"><param name=\"controls\" value=\"ImageWindow\" /><param name=\"autostart\" value=\"false\" /><param name=\"src\" value=\""+FILE+"\" /><embed src=\""+FILE+"\" type=\"audio/x-pn-realaudio-plugin\" controls=\"ImageWindow\" width=\""+WIDTH+"\" height=\""+HEIGHT+"\" style=\"max-width:"+MAXWIDTH+";max-height:"+MAXHEIGHT+";\" autostart=\"false\"></embed></object></div>");
            }
            // swf
            if (file.split('.').pop() == 'swf') {
                $(this).outerHTML("<div class=\"am_embed\"><object classid=\"clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B\" width="+WIDTH+" height="+HEIGHT+" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\"><param name=\"src\" value=\""+FILE+"\" /><param name=\"menu\" value=\"true\" /><param name=\"autostart\" value=\"0\" /><embed src=\""+FILE+"\" width=\""+WIDTH+"\" height=\""+HEIGHT+"\" style=\"max-width:"+MAXWIDTH+";max-height:"+MAXHEIGHT+";\" type=\"application/x-shockwave-flash\" menu=\"false\" autostart=\"false\"></embed></object></div>");
            }
            // wmv, wma
            if (file.split('.').pop() == 'wmv' || file.split('.').pop() == 'wma') {
                $(this).outerHTML("<div class=\"am_embed\"><object id=\"ImageWindow\" classid=\"clsid:22D6F312-B0F6-11D0-94AB-0080C74C7E95\" type=\"application/x-oleobject\" width="+WIDTH+" height="+HEIGHT+" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\"><param name=\"src\" value=\""+FILE+"\" /><param name=\"ShowControls\" value=\"true\" \><param name=\"ShowStatusBar\" value=\"false\" /><embed name=\"MediaPlayer\" src=\""+FILE+"\" type=\"application/x-mplayer2\" width=\""+WIDTH+"\" height=\""+HEIGHT+"\" style=\"max-width:"+MAXWIDTH+";max-height:"+MAXHEIGHT+";\" autostart=\"false\" ShowControls=\"1\" ShowStatusBar=\"1\"></embed></object></div>");
            }

            // Embed from a few sites if embed.ly and urlembed.com are disabled
            if (EMBEDLY !== 1 && URLEMBED !== 1) {
                if (file.indexOf('youtube.com/') !== -1 || file.indexOf('youtu.be') !== -1) {
                    var ematch = file.match(/(?:v=|v\/|embed\/|youtu\.be\/)(.{11})/);
                    var eid = ematch.pop();
                    $(this).outerHTML("<div class=\"am_embed\"><iframe width=\"560\" height=\"315\" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\" src=\"//www.youtube-nocookie.com/embed/"+eid+"\" frameborder=\"0\" allowfullscreen></iframe></div>");
                }
                if (file.indexOf('dailymotion.com/video/') !== -1 || file.indexOf('dai.ly/') !== -1) {
                    var ematch = file.match(/(dailymotion\.com\/video\/|dai\.ly\/)([^_]+)/);
                    var eid = ematch.pop();
                    $(this).outerHTML("<div class=\"am_embed\"><iframe frameborder=\"0\" width=\"480\" height=\"270\" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\" src=\"//www.dailymotion.com/embed/video/"+eid+"\" allowfullscreen></iframe></div>");
                }
                if (file.indexOf('liveleak.com/') !== -1) {
                    var ematch = file.match(/liveleak\.com\/(?:view\?i=)([^\/]+)/);
                    var eid = ematch.pop();
                    $(this).outerHTML("<div class=\"am_embed\"><iframe frameborder=\"0\" width=\"500\" height=\"300\" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\" src=\"https://www.liveleak.com/ll_embed?i="+eid+"\" allowfullscreen></iframe></div>");
                }
                if (file.indexOf('metacafe.com/watch/') !== -1) {
                    var ematch = file.match(/metacafe\.com\/watch\/([^\/]+)/);
                    var eid = file.split('watch/').pop();
                    $(this).outerHTML("<div class=\"am_embed\"><iframe frameborder=\"0\" width=\"440\" height=\"248\" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\" src=\"http://www.metacafe.com/embed/"+eid+"\" allowFullScreen></iframe></div>");
                }
                if (file.indexOf('veoh.com/watch/') !== -1) {
                    var ematch = file.match(/veoh\.com\/watch\/([^\/]+)/);
                    var eid = ematch.pop();
                    $(this).outerHTML("<div class=\"am_embed\"><iframe frameborder=\"0\" width=\"410\" height=\"341\" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\" src=\"//www.veoh.com/swf/webplayer/WebPlayer.swf?videoAutoPlay=0&permalinkId="+eid+"\" allowfullscreen></iframe></div>");
                }
                if (file.indexOf('vimeo.com/') !== -1) {
                    var eid = file.split('vimeo.com/').pop();
                    $(this).outerHTML("<div class=\"am_embed\"><iframe frameborder=\"0\" width=\"500\" height=\"375\" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\" src=\"//player.vimeo.com/video/"+eid+"\" allowfullscreen></iframe></div>");
                }
                if (file.indexOf('twitch.tv/') !== -1) {
                    var ematchA = file.match(/twitch\.tv\/(?:[\w+_-]+)\/v\/(\d+)/);
                    if (ematchA) {
                        var eid = ematchA.pop();
                    }
                    var ematchB = file.match(/twitch\.tv\/videos\/(\d+)/);
                    if (ematchB) {
                        var eid = ematchB.pop();
                    }
                    if (eid) {
                        $(this).outerHTML("<div class=\"am_embed\"><iframe frameborder=\"0\" width=\"620\" height=\"378\" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\" src=\"https://player.twitch.tv/?video=v"+eid+"&amp;autoplay=false\" scrolling=\"no\" allowfullscreen></iframe></div>");
                    }
                    var ematchC = file.match(/twitch\.tv\/(?:[\w+_-]+)/);
                    var cid = ematchC[0].split('twitch.tv/').pop();
                    if (cid) {
                        $(this).outerHTML("<div class=\"am_embed\"><iframe frameborder=\"0\" width=\"620\" height=\"378\" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\" src=\"https://player.twitch.tv/?channel="+cid+"&amp;autoplay=false\" scrolling=\"no\" allowfullscreen></iframe></div>");
                    }
                }
                if (file.indexOf('facebook.com/') !== -1) {
                    var ematchA = file.match(/facebook\.com\/(.*?)\/videos\/(vb.(\d+)\/)?(\d+)/);
                    if (ematchA) {
                        var eid = ematchA.pop();
                    }
                    var ematchB = file.match(/facebook\.com\/video\/video\.php\?v=(\d+)/);
                    if (ematchB) {
                        var eid = ematchB.pop();
                    }
                    $(this).outerHTML("<div class=\"am_embed\"><iframe frameborder=\"0\" width=\"625\" height=\"350\" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\" src=\"https://www.facebook.com/video/embed?video_id="+eid+"\" scrolling=\"no\" allowfullscreen></iframe></div>");
                }
                if (file.indexOf('instagram.com/p/') !== -1) {
                    var ematch = file.match(/instagram\.com\/p\/([A-Za-z0-9-_]+)/);
                    var eid = ematch.pop();
                    $(this).outerHTML("<div class=\"am_embed\"><iframe frameborder=\"0\" width=\"500\" height=\"800\" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\" src=\"https://www.instagram.com/p/"+eid+"/embed\"></iframe></div>");
                }
                if (file.indexOf('twitter.com/') !== -1) {
                    var ematch = file.match(/twitter\.com\/(.*?)\/status\/(\d+)/);
                    var eid = ematch[0];
                    $(this).outerHTML("<div class=\"am_embed\"><blockquote class=\"twitter-tweet\"><a href=\"https://"+eid+"\">"+eid+"</a></blockquote><script async src=\"https://platform.twitter.com/widgets.js\" charset=\"utf-8\"></script></div>");
                }
            }

            // Embed special media
            if (SPECIAL == 1) {
                if (file.indexOf('gotporn.com/') !== -1) {
                    var ematch = file.match(/(gotporn\.com\/(.*?)\/video-)(\d){1,10}/g);
                    var eid = ematch[0].split('video-').pop();
                    $(this).outerHTML("<div class=\"am_embed\"><iframe width=\"640\" height=\"480\" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\" src=\"https://www.gotporn.com/video/"+eid+"/embedframe\" frameborder=\"0\" allowfullscreen></iframe></div>");
                }
                if (file.indexOf('hotmovs.com/videos/') !== -1) {
                    var ematch = file.match(/(hotmovs\.com\/videos\/)(\d){1,10}/g);
                    var eid = ematch[0].split('/').pop();
                    $(this).outerHTML("<div class=\"am_embed\"><iframe title=\""+eid+"\" scrolling=\"no\"width=\"800\" height=\"475\" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\" src=\"http://hotmovs.com/embed/"+eid+"\" frameborder=\"0\"></iframe></div>");
                }
                if (file.indexOf('keezmovies.com/video/') !== -1) {
                    var eid = file.split('/').pop();
                    $(this).outerHTML("<iframe src=\"http://www.keezmovies.com/embed/"+eid+"\" frameborder=\"0\" width=\"608\" height=\"490\" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\" scrolling=\"no\" name=\"keezmovies_embed_video\"></iframe></div>");
                }
                if (file.indexOf('porn.com/videos/') !== -1) {
                    var eid = file.split('-').pop();
                    $(this).outerHTML("<div class=\"am_embed\"><iframe title=\""+eid+"\" scrolling=\"no\"width=\"600\" height=\"484\" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\" src=\"https://www.porn.com/videos/embed/"+eid+"\" frameborder=\"0\"></iframe></div>");
                }
                if (file.indexOf('porn8.com/videos/') !== -1) {
                    var ematch = file.match(/(porn8\.com\/videos\/)(\d){1,10}/g);
                    var eid = ematch[0].split('/').pop();
                    $(this).outerHTML("<div class=\"am_embed\"><iframe scrolling=\"no\" width=\"600\" height=\"476\" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\" src=\"http://www.porn8.com/videos/embed/"+eid+"\" frameborder=\"0\"></iframe></div>");
                }
                if (file.indexOf('pornhub.com/view_video.php?viewkey=') !== -1) {
                    var ematch = file.match(/(pornhub\.com\/view_video\.php\?viewkey=)([a-z\d]){1,20}/g);
                    var eid = ematch[0].split('=').pop();
                    $(this).outerHTML("<div class=\"am_embed\"><iframe src=\"https://www.pornhub.com/embed/"+eid+"\" frameborder=\"0\" allowfullscreen=\"1\"  width=\"560\" height=\"340\" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\" scrolling=\"no\"></iframe></div>");
                }
                if (file.indexOf('redtube.com/') !== -1) {
                    var ematch = file.match(/(redtube\.com\/)(\d){1,20}/g);
                    var eid = ematch[0].split('/').pop();
                    $(this).outerHTML("<div class=\"am_embed\"><iframe src=\"https://embed.redtube.com/?id="+eid+"&bgcolor=000000\" frameborder=\"0\" width=\"434\" height=\"344\" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\" scrolling=\"no\"></iframe></div>");
                }
                if (file.indexOf('sunporno.com/videos/') !== -1) {
                    var ematch = file.match(/(sunporno\.com\/videos\/)(\d){1,10}/g);
                    var eid = ematch[0].split('/').pop();
                    $(this).outerHTML("<div class=\"am_embed\"><iframe src=\"https://embeds.sunporno.com/embed/"+eid+"\" frameborder=0 width=\"650\" height=\"518\" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\"></iframe></div>");
                }
                if (file.indexOf('tube8.com/') !== -1) {
                    var ematch = file.match(/(tube8\.com\/([a-z])*\/?([a-z])*\/?([a-z\-\d])?\/)([a-z\-\d])*\/?(\d){1,10}\//g);
                    var before = ematch[0].match(/(tube8\.com\/)([a-z])*\//g);
                    var eid = ematch[0].replace(new RegExp(before, "g"), before+"embed/");
                    alert(eid);
                    $(this).outerHTML("<div class=\"am_embed\"><iframe src=\"https://www."+eid+"\" frameborder=0 width=\"608\" height=\"342\" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\" allowfullscreen=\"true\" name=\"t8_embed_video\"></iframe></div>");
                }
                if (file.indexOf('upornia.com/videos/') !== -1) {
                    var ematch = file.match(/(upornia\.com\/videos\/)(\d){1,10}/g);
                    var eid = ematch[0].split('/').pop();
                    $(this).outerHTML("<div class=\"am_embed\"><iframe title=\""+eid+"\" scrolling=\"no\" width=\"608\" height=\"342\" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\" src=\"http://upornia.com/embed/"+eid+"\" frameborder=\"0\"></iframe></div>");
                }
                if (file.indexOf('xhamster.com/movies/') !== -1 || file.indexOf('xhamster.com/videos/') !== -1) {
                    var ematch = file.match(/(xhamster\.com\/movies\/)(\d){1,10}/g);
                    if (ematch) {
                        var eid = ematch[0].split('/').pop();
                    } else {
                        var ematch = file.match(/(xhamster\.com\/videos\/([a-z\-\d])*)(\d){1,10}/g);
                        var eid = ematch[0].split('-').pop();
                    }
                    $(this).outerHTML("<div class=\"am_embed\"><iframe width=\"640\" height=\"480\" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\" src=\"https://xhamster.com/xembed.php?video="+eid+"\" frameborder=\"0\" scrolling=\"no\"></iframe></div>");
                }
                if (file.indexOf('xvideos.com/video') !== -1) {
                    var ematch = file.match(/(xvideos\.com\/video)(\d){1,14}/g);
                    var eid = ematch[0].split('/video').pop();
                    $(this).outerHTML("<div class=\'am_embed\'><iframe src=\"https://www.xvideos.com/embedframe/"+eid+"\" frameborder=0 width=510 height=400 style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\" scrolling=no></iframe></div>");
                }
                if (file.indexOf('xxxymovies.com/') !== -1) {
                    var ematch = file.match(/(xxxymovies\.com\/)(\d){1,10}/g);
                    if (ematch) {
                        var eid = ematch[0].split('/').pop();
                    } else {
                        var ematch = file.match(/(xxxymovies\.com\/videos\/)(\d){1,10}/g);
                        var eid = ematch[0].split('/').pop();
                    }
                    $(this).outerHTML("<div class=\"am_embed\"><iframe  width=\"640\" height=\"480\" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\" src=\"http://xxxymovies.com/embed/"+eid+"\" frameborder=\"0\" allowfullscreen ></iframe></div>");
                }
                if (file.indexOf('youporn.com/watch/') !== -1) {
                    var ematch = file.match(/(youporn\.com\/watch\/)(\d){1,10}\/([\w\-])*\//g);
                    var eid = ematch[0].split('youporn.com/watch/').pop();
                    $(this).outerHTML("<div class=\"am_embed\"><iframe src='https://www.youporn.com/embed/"+eid+"' frameborder=0 width=\"560\" height=\"315\" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\" scrolling=no name='yp_embed_video'></iframe></div>");
                }
            }
        }
    });
}

// Embed attachments
var embedAttach = function (pageattach) {
    pageattach.each(function() {
        var attachment = $(this).attr('href');
        var attachtext = $(this).html();
        var aid = attachment.split('=').pop();
        if (attachment.indexOf('attachment.php') !== -1) {
            // mp3
            if ($(this).html().split('.').pop() == 'mp3' && aid > 0) {
                $(this).outerHTML("<div class=\"am_embed\" style=\"margin:0;\"><audio id=\"player\" controls=\"control\" preload=\"none\"><source src=\""+attachment+"\" type=\"audio/mpeg\" /></audio><span class=\"attachtitle\"><a href=\""+attachment+"\" title=\"[attachment="+aid+"]\">"+attachtext+"</a></span></div>");
            }
            // aac
            if ($(this).html().split('.').pop() == 'aac' && aid > 0) {
                $(this).outerHTML("<div class=\"am_embed\" style=\"margin:0;\"><audio id=\"player\" controls=\"control\" preload=\"none\"><source src=\""+attachment+"\" type=\"audio/x-aac\" /></audio><span class=\"attachtitle\"><a href=\""+attachment+"\" title=\"[attachment="+aid+"]\">"+attachtext+"</a></span></div>");
            }
            // ogg
            if ($(this).html().split('.').pop() == 'ogg' && aid > 0) {
                $(this).outerHTML("<div class=\"am_embed\" style=\"margin:0;\"><audio id=\"player\" controls=\"control\" preload=\"none\"><source src=\""+attachment+"\" type=\"audio/ogg\" /></audio><span class=\"attachtitle\"><a href=\""+attachment+"\" title=\"[attachment="+aid+"]\">"+attachtext+"</a></span></div>");
            }
            // wav
            if ($(this).html().split('.').pop() == 'wav' && aid > 0) {
                $(this).outerHTML("<div class=\"am_embed\" style=\"margin:0;\"><audio id=\"player\" controls=\"control\" preload=\"none\"><source src=\""+attachment+"\" type=\"audio/x-wav\" /></audio><span class=\"attachtitle\"><a href=\""+attachment+"\" title=\"[attachment="+aid+"]\">"+attachtext+"</a></span></div>");
            }
            // flv
            if ($(this).html().split('.').pop() == 'flv' && aid > 0) {
                $(this).outerHTML("<div class=\"am_embed\"><object id=\"flowplayer\" style=\"max-width="+MAXWIDTH+" max-height="+MAXHEIGHT+"\" data=\""+PATH+"/inc/plugins/automedia/mediaplayer/flowplayer-3.2.18.swf\" type=\"application/x-shockwave-flash\"><param name=\"movie\" value=\""+PATH+"/inc/plugins/automedia/mediaplayer/flowplayer-3.2.18.swf\" /><param name=\"allowfullscreen\" value=\"true\" /><param name=\"flashvars\" value='config={\"clip\":{\"url\":\""+attachment+"\",\"autoPlay\":false}}' /></object><span class=\"attachtitle\"><a href=\""+attachment+"\" title=\"[attachment="+aid+"]\">"+attachtext+"</a></span></div>");
            }
            // mp4, m4v, mp4v, webm
            if (aid > 0 && ($(this).html().split('.').pop() == 'mp4' || $(this).html().split('.').pop() == 'm4v' || $(this).html().split('.').pop() == 'mp4v' || $(this).html().split('.').pop() == 'webm')) {
                $(this).outerHTML("<div class=\"am_embed\">\n<video id=\"player\" style=\"width:"+WIDTH+";height:"+HEIGHT+";width="+WIDTH+" height="+HEIGHT+"\" controls=\"controls\" width=\""+WIDTH+"\" height=\""+HEIGHT+"\"><source src=\""+attachment+"\" type=\"video/"+$(this).html().split('.').pop()+"\" />\n<object type=\"application/x-shockwave-flash\" data=\""+PATH+"/inc/plugins/automedia/mediaplayer/flowplayer-3.2.18.swf\" width=\""+WIDTH+"\" height=\""+HEIGHT+"\" style=\"max-width:"+MAXWIDTH+";max-height:"+MAXHEIGHT+";\">\n<param name=\"movie\" value=\""+PATH+"/inc/plugins/automedia/mediaplayer/flowplayer-3.2.18.swf\" />\n<param name=\"allowFullScreen\" value=\"true\" />\n<param name=\"wmode\" value=\"transparent\" />\n<param name=\"flashVars\" value=\"config={'playlist':[{'url':'"+attachment+"','autoPlay':false}]}\" />\n</object>\n</video>\n<span class=\"attachtitle\"><a href=\""+attachment+"\" title=\"[attachment="+aid+"]\">"+attachtext+"</a></span></div>");
            }
        }
    });
}

// Finally embed all
var amEmbed = function () {
    // Parse old playlists
    var opl_id = 0;
    var oldPlayL = $(".ampl");
    if (QUOTES !== 1) {
        var oldPlayL = $(".post_body > .ampl");
    }
    oldPlayL.each(function () {
        var oldsongs = $(this).html().split('|');
        var oldsong = '';
        for (var i = 0; i < oldsongs.length; i++) {
            oldsong += '<source src="'+oldsongs[i]+'" title="" />';
        };
        $(this).outerHTML('<div class="am_embed"><div class="mediawrapper"><audio id="mejs_'+opl_id+'" type="audio/mp3" controls="controls">'+oldsong+'</audio></div></div>');
        ++opl_id;
    });

    // Parse audio playlists
    var pl_id = 0;
    var playL = $(".amplist");
    if (QUOTES !== 1) {
        var playL = $(".post_body > .amplist");
    }
    playL.each(function () {
        var playlist = $(this).html();
        $(this).outerHTML('<div class="am_embed"><div class="mediawrapper"><audio id="mejs_'+pl_id+'" type="audio/mp3" controls="controls">'+playlist+'</audio></div></div>');
        ++pl_id;
    });

    // Embed files and sites
    var page = $(".post_body>.mycode_url");
    if (SCRIPT == 'portal.php') {
        var page = $(".mycode_url");
    }
    $(amoffMycode());
    $(embedMedia(page));

    // Embed some audio and video attachments
    if (ATTACH == 1) {
        // Find the attachments
        var pageattach = $(".post_body>a");
        if (SCRIPT == 'portal.php') {
            var pageattach = $('.attachembed')
        }

        $(embedAttach(pageattach));

        // Hide download link?
        if (DLLINK == 0) {
            $('.attachtitle').hide();
        }
    }

    // Resize
    $(".am_embed").css("width", MAXWIDTH);
    $(".am_embed").css("max-width", "100%");

    // Use embed.ly
    if (EMBEDLY == 1) {
        $(".post_body > .mycode_url").addClass('embedly-card');

        if (QUOTES == 1) {
            $(".post_body").find(".mycode_quote > .mycode_url").addClass('embedly-card');
        }

        if (SCRIPT == 'portal.php') {
            $(".mycode_url").addClass('embedly-card');
        }
        $(".embedly-card").attr('data-card-controls', '0').attr('data-card-theme', CARDTHEME);
        $(".embedly-card").removeClass('mycode_url');
        $(".embedly-card").css("width", MAXWIDTH);
        $(".embedly-card").css("max-width", "100%");
    // Use urlembed.com
    } else if (URLEMBED == 1) {
        $(".post_body > .mycode_url").addClass('urlembed');

        if (QUOTES == 1) {
            $(".post_body").find(".mycode_quote > .mycode_url").addClass('urlembed');
        }

        if (SCRIPT == 'portal.php') {
            $(".mycode_url").addClass('urlembed');
        }
        $(".urlembed").removeClass('mycode_url');
        $('.urlembed').wrap( "<div class='am_embed'></div>" );
    }

    // Use embed.ly for signatures
    if (EMBEDLY == 1 && SIGNATURE == 1) {
        $(".signature > .mycode_url").addClass('embedly-card');
        $(".profilesig > a").addClass('embedly-card');

        if (QUOTES == 1) {
            $(".signature").find(".mycode_quote > .mycode_url").addClass('embedly-card');
            $(".profilesig").find(".mycode_quote > .mycode_url").addClass('embedly-card');
        }

        if (EDITSIG == 1) {
            $(".mycode_url").addClass('embedly-card');

            if (QUOTES !== 1) {
                $(".mycode_quote > .mycode_url").removeClass('embedly-card');
            }
        }
        $(".embedly-card").attr('data-card-controls', '0').attr('data-card-theme', CARDTHEME);
        $(".embedly-card").removeClass('mycode_url');
        $(".embedly-card").css("width", MAXWIDTH);
        $(".embedly-card").css("max-width", "100%");
    // Use urlembed.com for signatures
    } else if (URLEMBED == 1 && SIGNATURE == 1) {
        $(".signature > .mycode_url").addClass('urlembed');
        $(".profilesig > a").addClass('urlembed');

        if (QUOTES == 1) {
            $(".signature").find(".mycode_quote > .mycode_url").addClass('urlembed');
            $(".profilesig").find(".mycode_quote > .mycode_url").addClass('urlembed');
        }

        if (EDITSIG == 1) {
            $(".mycode_url").addClass('urlembed');

            if (QUOTES !== 1) {
                $(".mycode_quote > .mycode_url").removeClass('urlembed');
            }
        }
        $(".urlembed").removeClass('mycode_url');
        $('.urlembed').wrap( "<div class='am_embed'></div>" );
    }

    // Give the mediaelement player instances unique id's
    var plid = 0;
    $("#player").each(function () {
        $(this).html($(this).html().replace(/id="player"/gi, "id=\"player"+plid+"\""));
        ++plid;
    });

    // Mediaelement player
    $('.am_embed, .urlembed, .oembedall-container, .embedly-card').css('width', MAXWIDTH);
    $('.am_embed, .urlembed, .oembedall-container, .embedly-card').css('max-width', '100%');
};

// Set the player for playlists
var mePlaylist = function(){
    $("[id^=mejs_]").mediaelementplayer({
        loop: true,
        shuffle: false,
        playlist: true,
        audioHeight: 30,
        audioWidth: 430,
        playlistposition: "bottom",
        features: ["playlistfeature", "prevtrack", "playpause", "nexttrack", "loop", "shuffle", "playlist", "current", "progress", "duration", "volume"],
    });
};

// Load the mediaelement player
var mePlayer = function(){
    $("video, audio").mediaelementplayer({
        loop: false,
        shuffle: false,
        playlist: false,
        audioHeight: 30,
        fullscreen: true,
        features: ["playpause", "loop", "current", "progress", "duration", "volume", "fullscreen"],
    });
};

// Helper function to replace outer html (To avoid XSS, we want to avoid inserting HTML directly into the document and instead, programmatically create DOM nodes and append them to the DOM.)
jQuery.fn.outerHTML = function(s) {
    return s
        ? this.before(s).remove()
        : jQuery("<div>").append(this.eq(0).clone()).html();
};

/**
 * Start embedding
 **/

// Embedding on page loaded
jQuery(document).ready(function($)
{
    // Plugin has to be active - forums, groups have to be allowed
    if (ACTIVE < 1 || FORUMS < 1 || GROUPS < 1) {
        return false;
    } else {
        // Find the links in posts
        $(amEmbed);
        $(mePlaylist);
        $(mePlayer);

        // Log player errors
        var plc = $("[id^=player_]").length;
        for (var i = 1; i <= plc; i++) {
            var players = "player_" + i;
            document.getElementById(""+players+"").addEventListener('error', function failed(e) {
                // video/audio playback failed - show a message saying why
                // to get the source of the video/audio element use $(this).src
                switch (e.target.error.code) {
                 case e.target.error.MEDIA_ERR_ABORTED:
                  console.log('AutoMedia: You aborted the video/audio playback.');
                   break;
                 case e.target.error.MEDIA_ERR_NETWORK:
                   console.log('AutoMedia: A network error caused the video/audio download to fail.');
                   break;
                 case e.target.error.MEDIA_ERR_DECODE:
                  console.log('AutoMedia: The playback was aborted due to a corruption problem or because the video/audio used features your browser did not support.');
                   break;
                 case e.target.error.MEDIA_ERR_SRC_NOT_SUPPORTED:
                        console.log('AutoMedia: At least one embedded video/audio file could not be loaded, either because the server or network failed or because the format is not supported by your browser.');
                   break;
                 default:
                  console.log('AutoMedia: An unknown error occurred.');
                   break;
               }
            }, true);
        }
    }
});

// Embedding after quick reply
$("#quick_reply_submit").on("click", function() {
    // Plugin has to be active - forums, groups have to be allowed
    if (ACTIVE < 1 || FORUMS < 1 || GROUPS < 1 ) {
        return false;
    } else {
        var pidarray = new Array();
        setTimeout(function() {
            $(amEmbed);
            $(mePlaylist);
            $(mePlayer);
            $("[id^=pid_]").each(function() {
                pidarray.push($(this).attr("id"));
            });
            var newpid = pidarray.pop();
            $("#"+newpid+"").html(function(index,html){
                if (EMBEDLY == 1) {
                    // Embed all links of the post
                    $(this).find(".mycode_url").each(function () {
                        $(amoffMycode());
                        html = html.replace(/class=\"mycode_url\"/,"class=\'embedly-card\' data-card-controls=\'0\' data-card-theme=\'"+CARDTHEME+"\'");//
                    });
                    return html;
                } else if (URLEMBED == 1) {
                    $("#"+newpid+"").html($("#"+newpid+"").html()+"<script src=\"//urlembed.com/static/js/script.js\"></script>");
                    // Embed all links of the post
                    $(this).find(".mycode_url").each(function () {
                        $(amoffMycode());
                        html = html.replace(/class=\"mycode_url\"/,"class=\'urlembed\'");
                    });
                    return html;
                }
            });
        }, TIMEOUT);
    }
});

// Detect quick edit
$(document).on("click", "[id^=quick_edit_post_]", function() {
    var trigger = $("[id^=quickedit_]").length;
});

// Embedding after quick edit
$(document).on("blur", "[id^=quickedit_]", function() {
    var msgpid = $(this).closest("[id^=pid_]").attr("id");
    var reload = 0;
    setTimeout(function() {
        if ($("[id^=quickedit_]").length < trigger) {
            $("#"+msgpid+"").html(function(index,html){
                if (EMBEDLY == 1) {
                    if (html.match("embedly-card") || html.match("am_embed"))  {
                       reload = 1;
                       $("#"+msgpid+".post_body").load(document.URL + " #"+msgpid+".post_body");
                    }
                    // Embed all links of the post
                    $(this).find(".mycode_url").each(function () {
                        $(amoffMycode());
                        html = html.replace(/class=\"mycode_url\"/,"class=\'embedly-card\' data-card-controls=\'0\' data-card-theme=\'"+CARDTHEME+"\'");//
                    });
                    return html;
                } else if (URLEMBED == 1) {
                    reload = 1;
                    $("#"+msgpid+"").html($("#"+msgpid+"").html()+"<script src=\"//urlembed.com/static/js/script.js\"></script>");
                    if (html.match("am_embed"))  {
                       reload = 1;
                       $("#"+msgpid+".post_body").load(document.URL + " #"+msgpid+".post_body");
                    }
                    // Embed all links of the post
                    $(this).find(".mycode_url").each(function () {
                        $(amoffMycode());
                        html = html.replace(/class=\"mycode_url\"/,"class=\'urlembed\'");
                    });
                    return html;
                } else {
                    if (html.match("am_embed"))  {
                        reload = 1;
                        $("#"+msgpid+".post_body").load(document.URL + " #"+msgpid+".post_body");
                    }
                }
            });
            // Embed specific media files
            if (reload == 0) {
                if (EMBEDLY == 1) {
                    var page =  $("#"+msgpid+".post_body > .embedly-card");
                } else if (URLEMBED == 1) {
                    var page =  $("#"+msgpid+".post_body > .urlembed");
                } else {
                    var page =  $("#"+msgpid+".post_body > .mycode_url");
                }
                $(amoffMycode());
                $(embedMedia(page));
            }
        }
    }, TIMEOUT);
    // We have to reload the post if we cancel quick edit with embed.ly
    setTimeout(function() {
        if ($("[id^=quickedit_]").length < trigger) {
            $("#"+msgpid+"").html(function(index,html){
                if (EMBEDLY == 1 &&  reload > 0) {
                    // Embed all links of the post
                    $(this).find(".mycode_url").each(function () {
                        $(amoffMycode());
                        html = html.replace(/class=\"mycode_url\"/,"class=\'embedly-card\' data-card-controls=\'0\' data-card-theme=\'"+CARDTHEME+"\'");//
                    });
                    return html;
                }
            });
            if (reload > 0) {
                if (EMBEDLY == 1) {
                    var page =  $("#"+msgpid+".post_body > .embedly-card");
                    $(amoffMycode());
                    $(embedMedia(page));
                } else if (URLEMBED == 1) {
                    var page =  $("#"+msgpid+".post_body > .mycode_url, .urlembed");
                    $(amoffMycode());
                    $(embedMedia(page));
                    $("#"+msgpid+"").html(function(index,html){
                        $("#"+msgpid+"").html($("#"+msgpid+"").html()+"<script src=\"//urlembed.com/static/js/script.js\"></script>");
                        $(this).find(".mycode_url").each(function () {
                            $(amoffMycode());
                            html = html.replace(/class=\"mycode_url\"/,"class=\'urlembed\'");
                        });
                        return html;
                    });
                } else {
                    var page =  $("#"+msgpid+".post_body > .mycode_url");
                    $(amoffMycode());
                    $(embedMedia(page));
                }
            }
            // Playlists
            var pl_id = 0;
            $("#"+msgpid+".post_body > .amplist").each(function () {
                var playlist = $(this).html();
                $(this).outerHTML('<div class="am_embed"><div class="mediawrapper"><audio id="mejs_'+pl_id+'" type="audio/mp3" controls="controls">'+playlist+'</audio></div></div>');
                ++pl_id;
            });
            var opl_id = 0;
            $("#"+msgpid+".post_body > .ampl").each(function () {
                var oldsongs = $(this).html().split('|');
                var oldsong = '';
                for (var i = 0; i < oldsongs.length; i++) {
                    oldsong += '<source src="'+oldsongs[i]+'" title="" />';
                };
                $(this).outerHTML('<div class="am_embed"><div class="mediawrapper"><audio id="mejs_'+opl_id+'" type="audio/mp3" controls="controls">'+oldsong+'</audio></div></div>');
                ++opl_id;
            });
            // Attachments
            if (ATTACH == 1) {
                var pageattach = $("#"+msgpid+".post_body > .attachembed");
                $(embedAttach(pageattach));
            }
            $(mePlaylist);
            $(mePlayer);
            $('.urlembed').wrap( "<div class='am_embed'></div>" );
            $('.urlembed').css( "overflow", "hidden" );
            $(".urlembed, .am_embed, .embedly-card").css("width", MAXWIDTH);
            $(".urlembed, .am_embed, .embedly-card").css("max-width", "100%");
            // Hide download link?
            if (DLLINK == 0) {
                $('.attachtitle').hide();
            }
        }
    }, TIMEOUT+300);
});
