﻿
(function(){var toolbox=function(){this.toolbars=[];this.focusCommandExecuted=false;};toolbox.prototype.focus=function(){for(var t=0,toolbar;toolbar=this.toolbars[t++];){for(var i=0,item;item=toolbar.items[i++];){if(item.focus){item.focus();return;}}}};var commands={toolbarFocus:{modes:{wysiwyg:1,source:1},readOnly:1,exec:function(editor){if(editor.toolbox){editor.toolbox.focusCommandExecuted=true;if(CKEDITOR.env.ie||CKEDITOR.env.air)
setTimeout(function(){editor.toolbox.focus();},100);else
editor.toolbox.focus();}}}};CKEDITOR.plugins.add('toolbar',{requires:'button',lang:'af,ar,bg,bn,bs,ca,cs,cy,da,de,el,en,en-au,en-ca,en-gb,eo,es,et,eu,fa,fi,fo,fr,fr-ca,gl,gu,he,hi,hr,hu,id,is,it,ja,ka,km,ko,ku,lt,lv,mk,mn,ms,nb,nl,no,pl,pt,pt-br,ro,ru,si,sk,sl,sq,sr,sr-latn,sv,th,tr,tt,ug,uk,vi,zh,zh-cn',init:function(editor){var endFlag;var itemKeystroke=function(item,keystroke){var next,toolbar;var rtl=editor.lang.dir=='rtl',toolbarGroupCycling=editor.config.toolbarGroupCycling,rightKeyCode=rtl?37:39,leftKeyCode=rtl?39:37;toolbarGroupCycling=toolbarGroupCycling===undefined||toolbarGroupCycling;switch(keystroke){case 9:case CKEDITOR.SHIFT+9:while(!toolbar||!toolbar.items.length){toolbar=keystroke==9?((toolbar?toolbar.next:item.toolbar.next)||editor.toolbox.toolbars[0]):((toolbar?toolbar.previous:item.toolbar.previous)||editor.toolbox.toolbars[editor.toolbox.toolbars.length-1]);if(toolbar.items.length){item=toolbar.items[endFlag?(toolbar.items.length-1):0];while(item&&!item.focus){item=endFlag?item.previous:item.next;if(!item)
toolbar=0;}}}
if(item)
item.focus();return false;case rightKeyCode:next=item;do{next=next.next;if(!next&&toolbarGroupCycling)next=item.toolbar.items[0];}
while(next&&!next.focus);if(next)
next.focus();else
itemKeystroke(item,9);return false;case 40:if(item.button&&item.button.hasArrow){editor.once('panelShow',function(evt){evt.data._.panel._.currentBlock.onKeyDown(40);});item.execute();}else{itemKeystroke(item,keystroke==40?rightKeyCode:leftKeyCode);}
return false;case leftKeyCode:case 38:next=item;do{next=next.previous;if(!next&&toolbarGroupCycling)next=item.toolbar.items[item.toolbar.items.length-1];}
while(next&&!next.focus);if(next)
next.focus();else{endFlag=1;itemKeystroke(item,CKEDITOR.SHIFT+9);endFlag=0;}
return false;case 27:editor.focus();return false;case 13:case 32:item.execute();return false;}
return true;};editor.on('uiSpace',function(event){if(event.data.space!=editor.config.toolbarLocation)
return;event.removeListener();editor.toolbox=new toolbox();var labelId=CKEDITOR.tools.getNextId();var output=['<span id="',labelId,'" class="cke_voice_label">',editor.lang.toolbar.toolbars,'</span>','<span id="'+editor.ui.spaceId('toolbox')+'" class="cke_toolbox" role="group" aria-labelledby="',labelId,'" onmousedown="return false;">'];var expanded=editor.config.toolbarStartupExpanded!==false,groupStarted,pendingSeparator;if(editor.config.toolbarCanCollapse&&editor.elementMode!=CKEDITOR.ELEMENT_MODE_INLINE)
output.push('<span class="cke_toolbox_main"'+(expanded?'>':' style="display:none">'));var toolbars=editor.toolbox.toolbars,toolbar=getToolbarConfig(editor);for(var r=0;r<toolbar.length;r++){var toolbarId,toolbarObj=0,toolbarName,row=toolbar[r],items;if(!row)
continue;if(groupStarted){output.push('</span>');groupStarted=0;pendingSeparator=0;}
if(row==='/'){output.push('<span class="cke_toolbar_break"></span>');continue;}
items=row.items||row;for(var i=0;i<items.length;i++){var item=items[i],canGroup;if(item){if(item.type==CKEDITOR.UI_SEPARATOR){pendingSeparator=groupStarted&&item;continue;}
canGroup=item.canGroup!==false;if(!toolbarObj){toolbarId=CKEDITOR.tools.getNextId();toolbarObj={id:toolbarId,items:[]};toolbarName=row.name&&(editor.lang.toolbar.toolbarGroups[row.name]||row.name);output.push('<span id="',toolbarId,'" class="cke_toolbar"',(toolbarName?' aria-labelledby="'+toolbarId+'_label"':''),' role="toolbar">');toolbarName&&output.push('<span id="',toolbarId,'_label" class="cke_voice_label">',toolbarName,'</span>');output.push('<span class="cke_toolbar_start"></span>');var index=toolbars.push(toolbarObj)-1;if(index>0){toolbarObj.previous=toolbars[index-1];toolbarObj.previous.next=toolbarObj;}}
if(canGroup){if(!groupStarted){output.push('<span class="cke_toolgroup" role="presentation">');groupStarted=1;}}else if(groupStarted){output.push('</span>');groupStarted=0;}
function addItem(item){var itemObj=item.render(editor,output);index=toolbarObj.items.push(itemObj)-1;if(index>0){itemObj.previous=toolbarObj.items[index-1];itemObj.previous.next=itemObj;}
itemObj.toolbar=toolbarObj;itemObj.onkey=itemKeystroke;itemObj.onfocus=function(){if(!editor.toolbox.focusCommandExecuted)
editor.focus();};}
if(pendingSeparator){addItem(pendingSeparator);pendingSeparator=0;}
addItem(item);}}
if(groupStarted){output.push('</span>');groupStarted=0;pendingSeparator=0;}
if(toolbarObj)
output.push('<span class="cke_toolbar_end"></span></span>');}
if(editor.config.toolbarCanCollapse)
output.push('</span>');if(editor.config.toolbarCanCollapse&&editor.elementMode!=CKEDITOR.ELEMENT_MODE_INLINE){var collapserFn=CKEDITOR.tools.addFunction(function(){editor.execCommand('toolbarCollapse');});editor.on('destroy',function(){CKEDITOR.tools.removeFunction(collapserFn);});editor.addCommand('toolbarCollapse',{readOnly:1,exec:function(editor){var collapser=editor.ui.space('toolbar_collapser'),toolbox=collapser.getPrevious(),contents=editor.ui.space('contents'),toolboxContainer=toolbox.getParent(),contentHeight=parseInt(contents.$.style.height,10),previousHeight=toolboxContainer.$.offsetHeight,minClass='cke_toolbox_collapser_min',collapsed=collapser.hasClass(minClass);if(!collapsed){toolbox.hide();collapser.addClass(minClass);collapser.setAttribute('title',editor.lang.toolbar.toolbarExpand);}else{toolbox.show();collapser.removeClass(minClass);collapser.setAttribute('title',editor.lang.toolbar.toolbarCollapse);}
collapser.getFirst().setText(collapsed?'\u25B2':'\u25C0');var dy=toolboxContainer.$.offsetHeight-previousHeight;contents.setStyle('height',(contentHeight-dy)+'px');editor.fire('resize');},modes:{wysiwyg:1,source:1}});editor.setKeystroke(CKEDITOR.ALT+(CKEDITOR.env.ie||CKEDITOR.env.webkit?189:109),'toolbarCollapse');output.push('<a title="'+(expanded?editor.lang.toolbar.toolbarCollapse:editor.lang.toolbar.toolbarExpand)
+'" id="'+editor.ui.spaceId('toolbar_collapser')
+'" tabIndex="-1" class="cke_toolbox_collapser');if(!expanded)
output.push(' cke_toolbox_collapser_min');output.push('" onclick="CKEDITOR.tools.callFunction('+collapserFn+')">','<span class="cke_arrow">&#9650;</span>','</a>');}
output.push('</span>');event.data.html+=output.join('');});editor.on('destroy',function(){if(this.toolbox)
{var toolbars,index=0,i,items,instance;toolbars=this.toolbox.toolbars;for(;index<toolbars.length;index++){items=toolbars[index].items;for(i=0;i<items.length;i++){instance=items[i];if(instance.clickFn)
CKEDITOR.tools.removeFunction(instance.clickFn);if(instance.keyDownFn)
CKEDITOR.tools.removeFunction(instance.keyDownFn);}}}});editor.on('uiReady',function(){var toolbox=editor.ui.space('toolbox');toolbox&&editor.focusManager.add(toolbox,1);});editor.addCommand('toolbarFocus',commands.toolbarFocus);editor.setKeystroke(CKEDITOR.ALT+121,'toolbarFocus');editor.ui.add('-',CKEDITOR.UI_SEPARATOR,{});editor.ui.addHandler(CKEDITOR.UI_SEPARATOR,{create:function(){return{render:function(editor,output){output.push('<span class="cke_toolbar_separator" role="separator"></span>');return{};}};}});}});function getToolbarConfig(editor){var removeButtons=editor.config.removeButtons;removeButtons=removeButtons&&removeButtons.split(',');function buildToolbarConfig(){var lookup=getItemDefinedGroups();var toolbar=CKEDITOR.tools.clone(editor.config.toolbarGroups)||getPrivateToolbarGroups(editor);for(var i=0;i<toolbar.length;i++){var toolbarGroup=toolbar[i];if(toolbarGroup=='/')
continue;else if(typeof toolbarGroup=='string')
toolbarGroup=toolbar[i]={name:toolbarGroup};var items,subGroups=toolbarGroup.groups;if(subGroups){for(var j=0,sub;j<subGroups.length;j++){sub=subGroups[j];items=lookup[sub];items&&fillGroup(toolbarGroup,items);}}
items=lookup[toolbarGroup.name];items&&fillGroup(toolbarGroup,items);}
return toolbar;}
function getItemDefinedGroups(){var groups={},itemName,item,itemToolbar,group,order;for(itemName in editor.ui.items){item=editor.ui.items[itemName];itemToolbar=item.toolbar||'others';if(itemToolbar){itemToolbar=itemToolbar.split(',');group=itemToolbar[0];order=parseInt(itemToolbar[1]||-1,10);groups[group]||(groups[group]=[]);groups[group].push({name:itemName,order:order});}}
for(group in groups){groups[group]=groups[group].sort(function(a,b){return a.order==b.order?0:b.order<0?-1:a.order<0?1:a.order<b.order?-1:1;});}
return groups;}
function fillGroup(toolbarGroup,uiItems){if(uiItems.length){if(toolbarGroup.items)
toolbarGroup.items.push(editor.ui.create('-'));else
toolbarGroup.items=[];var item,name;while((item=uiItems.shift())){name=typeof item=='string'?item:item.name;if(!removeButtons||CKEDITOR.tools.indexOf(removeButtons,name)==-1){item=editor.ui.create(name);if(!item)
continue;if(!editor.addFeature(item))
continue;toolbarGroup.items.push(item);}}}}
function populateToolbarConfig(config){var toolbar=[],i,group,newGroup;for(i=0;i<config.length;++i){group=config[i];newGroup={};if(group=='/')
toolbar.push(group);else if(CKEDITOR.tools.isArray(group)){fillGroup(newGroup,CKEDITOR.tools.clone(group));toolbar.push(newGroup);}
else if(group.items){fillGroup(newGroup,CKEDITOR.tools.clone(group.items));newGroup.name=group.name;toolbar.push(newGroup);}}
return toolbar;}
var toolbar=editor.config.toolbar;if(typeof toolbar=='string')
toolbar=editor.config['toolbar_'+toolbar];return(editor.toolbar=toolbar?populateToolbarConfig(toolbar):buildToolbarConfig());}
CKEDITOR.ui.prototype.addToolbarGroup=function(name,previous,subgroupOf){var toolbarGroups=getPrivateToolbarGroups(this.editor),atStart=previous===0,newGroup={name:name};if(subgroupOf){subgroupOf=CKEDITOR.tools.search(toolbarGroups,function(group){return group.name==subgroupOf;});if(subgroupOf){!subgroupOf.groups&&(subgroupOf.groups=[]);if(previous){previous=CKEDITOR.tools.indexOf(subgroupOf.groups,previous);if(previous>=0){subgroupOf.groups.splice(previous+1,0,name);return;}}
if(atStart)
subgroupOf.groups.splice(0,0,name);else
subgroupOf.groups.push(name);return;}else{previous=null;}}
if(previous){previous=CKEDITOR.tools.indexOf(toolbarGroups,function(group){return group.name==previous;});}
if(atStart)
toolbarGroups.splice(0,0,name);else if(typeof previous=='number')
toolbarGroups.splice(previous+1,0,newGroup);else
toolbarGroups.push(name);};function getPrivateToolbarGroups(editor){return editor._.toolbarGroups||(editor._.toolbarGroups=[{name:'document',groups:['mode','document','doctools']},{name:'clipboard',groups:['clipboard','undo']},{name:'editing',groups:['find','selection','spellchecker']},{name:'forms'},'/',{name:'basicstyles',groups:['basicstyles','cleanup']},{name:'paragraph',groups:['list','indent','blocks','align','bidi']},{name:'links'},{name:'insert'},'/',{name:'styles'},{name:'colors'},{name:'tools'},{name:'others'},{name:'about'}]);}})();CKEDITOR.UI_SEPARATOR='separator';CKEDITOR.config.toolbarLocation='top';