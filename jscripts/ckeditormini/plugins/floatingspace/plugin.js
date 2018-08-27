﻿
(function(){var win=CKEDITOR.document.getWindow(),pixelate=CKEDITOR.tools.cssLength;CKEDITOR.plugins.add('floatingspace',{init:function(editor){editor.on('loaded',function(){attach(this);},null,null,20);}});function scrollOffset(side){var pageOffset=side=='left'?'pageXOffset':'pageYOffset',docScrollOffset=side=='left'?'scrollLeft':'scrollTop';return(pageOffset in win.$)?win.$[pageOffset]:CKEDITOR.document.$.documentElement[docScrollOffset];}
function attach(editor){var config=editor.config,topHtml=editor.fire('uiSpace',{space:'top',html:''}).html,layout=(function(){var mode,editable,spaceRect,editorRect,viewRect,spaceHeight,pageScrollX,dockedOffsetX=config.floatSpaceDockedOffsetX||0,dockedOffsetY=config.floatSpaceDockedOffsetY||0,pinnedOffsetX=config.floatSpacePinnedOffsetX||0,pinnedOffsetY=config.floatSpacePinnedOffsetY||0;function updatePos(pos,prop,val){floatSpace.setStyle(prop,pixelate(val));floatSpace.setStyle('position',pos);}
function changeMode(newMode){var editorPos=editable.getDocumentPosition();switch(newMode){case'top':updatePos('absolute','top',editorPos.y-spaceHeight-dockedOffsetY);break;case'pin':updatePos('fixed','top',pinnedOffsetY);break;case'bottom':updatePos('absolute','top',editorPos.y+(editorRect.height||editorRect.bottom-editorRect.top)+dockedOffsetY);break;}
mode=newMode;}
return function(evt){if(!(editable=editor.editable()))
return;evt&&evt.name=='focus'&&floatSpace.show();floatSpace.removeStyle('left');floatSpace.removeStyle('right');spaceRect=floatSpace.getClientRect();editorRect=editable.getClientRect();viewRect=win.getViewPaneSize();spaceHeight=spaceRect.height;pageScrollX=scrollOffset('left');if(!mode){mode='pin';changeMode('pin');layout(evt);return;}
if(spaceHeight+dockedOffsetY<=editorRect.top)
changeMode('top');else if(spaceHeight+dockedOffsetY>viewRect.height-editorRect.bottom)
changeMode('pin');else
changeMode('bottom');var mid=viewRect.width/2,alignSide=(editorRect.left>0&&editorRect.right<viewRect.width&&editorRect.width>spaceRect.width)?(editor.config.contentsLangDirection=='rtl'?'right':'left'):(mid-editorRect.left>editorRect.right-mid?'left':'right'),offset;if(spaceRect.width>viewRect.width){alignSide='left';offset=0;}
else{if(alignSide=='left'){if(editorRect.left>0)
offset=editorRect.left;else
offset=0;}
else{if(editorRect.right<viewRect.width)
offset=viewRect.width-editorRect.right;else
offset=0;}
if(offset+spaceRect.width>viewRect.width){alignSide=alignSide=='left'?'right':'left';offset=0;}}
var scroll=mode=='pin'?0:alignSide=='left'?pageScrollX:-pageScrollX;floatSpace.setStyle(alignSide,pixelate((mode=='pin'?pinnedOffsetX:dockedOffsetX)+offset+scroll));};})();if(topHtml){var floatSpaceTpl=new CKEDITOR.template('<div'+' id="cke_{name}"'+' class="cke {id} cke_reset_all cke_chrome cke_editor_{name} cke_float cke_{langDir} '+CKEDITOR.env.cssClass+'"'+' dir="{langDir}"'+' title="'+(CKEDITOR.env.gecko?' ':'')+'"'+' lang="{langCode}"'+' role="application"'+' style="{style}"'+
(editor.title?' aria-labelledby="cke_{name}_arialbl"':' ')+'>'+
(editor.title?'<span id="cke_{name}_arialbl" class="cke_voice_label">{voiceLabel}</span>':' ')+'<div class="cke_inner">'+'<div id="{topId}" class="cke_top" role="presentation">{content}</div>'+'</div>'+'</div>'),floatSpace=CKEDITOR.document.getBody().append(CKEDITOR.dom.element.createFromHtml(floatSpaceTpl.output({content:topHtml,id:editor.id,langDir:editor.lang.dir,langCode:editor.langCode,name:editor.name,style:'display:none;z-index:'+(config.baseFloatZIndex-1),topId:editor.ui.spaceId('top'),voiceLabel:editor.title}))),changeBuffer=CKEDITOR.tools.eventsBuffer(500,layout),uiBuffer=CKEDITOR.tools.eventsBuffer(100,layout);floatSpace.unselectable();floatSpace.on('mousedown',function(evt){evt=evt.data;if(!evt.getTarget().hasAscendant('a',1))
evt.preventDefault();});editor.on('focus',function(evt){layout(evt);editor.on('change',changeBuffer.input);win.on('scroll',uiBuffer.input);win.on('resize',uiBuffer.input);});editor.on('blur',function(){floatSpace.hide();editor.removeListener('change',changeBuffer.input);win.removeListener('scroll',uiBuffer.input);win.removeListener('resize',uiBuffer.input);});editor.on('destroy',function(){win.removeListener('scroll',uiBuffer.input);win.removeListener('resize',uiBuffer.input);floatSpace.clearCustomData();floatSpace.remove();});if(editor.focusManager.hasFocus)
floatSpace.show();editor.focusManager.add(floatSpace,1);}}})();