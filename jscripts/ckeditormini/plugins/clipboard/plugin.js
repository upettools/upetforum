﻿'use strict';(function(){CKEDITOR.plugins.add('clipboard',{requires:'dialog',lang:'af,ar,bg,bn,bs,ca,cs,cy,da,de,el,en,en-au,en-ca,en-gb,eo,es,et,eu,fa,fi,fo,fr,fr-ca,gl,gu,he,hi,hr,hu,id,is,it,ja,ka,km,ko,ku,lt,lv,mk,mn,ms,nb,nl,no,pl,pt,pt-br,ro,ru,si,sk,sl,sq,sr,sr-latn,sv,th,tr,tt,ug,uk,vi,zh,zh-cn',icons:'copy,copy-rtl,cut,cut-rtl,paste,paste-rtl',hidpi:true,init:function(editor){var textificationFilter;initClipboard(editor);CKEDITOR.dialog.add('paste',CKEDITOR.getUrl(this.path+'dialogs/paste.js'));editor.on('paste',function(evt){var data=evt.data.dataValue,blockElements=CKEDITOR.dtd.$block;if(data.indexOf('Apple-')>-1){data=data.replace(/<span class="Apple-converted-space">&nbsp;<\/span>/gi,' ');if(evt.data.type!='html')
data=data.replace(/<span class="Apple-tab-span"[^>]*>([^<]*)<\/span>/gi,function(all,spaces){return spaces.replace(/\t/g,'&nbsp;&nbsp; &nbsp;');});if(data.indexOf('<br class="Apple-interchange-newline">')>-1){evt.data.startsWithEOL=1;evt.data.preSniffing='html';data=data.replace(/<br class="Apple-interchange-newline">/,'');}
data=data.replace(/(<[^>]+) class="Apple-[^"]*"/gi,'$1');}
if(data.match(/^<[^<]+cke_(editable|contents)/i)){var tmp,editable_wrapper,wrapper=new CKEDITOR.dom.element('div');wrapper.setHtml(data);while(wrapper.getChildCount()==1&&(tmp=wrapper.getFirst())&&tmp.type==CKEDITOR.NODE_ELEMENT&&(tmp.hasClass('cke_editable')||tmp.hasClass('cke_contents'))){wrapper=editable_wrapper=tmp;}
if(editable_wrapper)
data=editable_wrapper.getHtml().replace(/<br>$/i,'');}
if(CKEDITOR.env.ie){data=data.replace(/^&nbsp;(?: |\r\n)?<(\w+)/g,function(match,elementName){if(elementName.toLowerCase()in blockElements){evt.data.preSniffing='html';return'<'+elementName;}
return match;});}else if(CKEDITOR.env.webkit){data=data.replace(/<\/(\w+)><div><br><\/div>$/,function(match,elementName){if(elementName in blockElements){evt.data.endsWithEOL=1;return'</'+elementName+'>';}
return match;});}else if(CKEDITOR.env.gecko){data=data.replace(/(\s)<br>$/,'$1');}
evt.data.dataValue=data;},null,null,3);editor.on('paste',function(evt){var dataObj=evt.data,type=dataObj.type,data=dataObj.dataValue,trueType,defaultType=editor.config.clipboard_defaultContentType||'html';if(type=='html'||dataObj.preSniffing=='html')
trueType='html';else
trueType=recogniseContentType(data);if(trueType=='htmlifiedtext')
data=htmlifiedTextHtmlification(editor.config,data);else if(type=='text'&&trueType=='html'){data=htmlTextification(editor.config,data,textificationFilter||(textificationFilter=getTextificationFilter(editor)));}
if(dataObj.startsWithEOL)
data='<br data-cke-eol="1">'+data;if(dataObj.endsWithEOL)
data+='<br data-cke-eol="1">';if(type=='auto')
type=(trueType=='html'||defaultType=='html')?'html':'text';dataObj.type=type;dataObj.dataValue=data;delete dataObj.preSniffing;delete dataObj.startsWithEOL;delete dataObj.endsWithEOL;},null,null,6);editor.on('paste',function(evt){var data=evt.data;editor.insertHtml(data.dataValue,data.type);setTimeout(function(){editor.fire('afterPaste');},0);},null,null,1000);editor.on('pasteDialog',function(evt){setTimeout(function(){editor.openDialog('paste',evt.data);},0);});}});function initClipboard(editor){var preventBeforePasteEvent=0,preventPasteEvent=0,inReadOnly=0,mainPasteEvent=CKEDITOR.env.ie?'beforepaste':'paste';addListeners();addButtonsCommands();editor.getClipboardData=function(options,callback){var beforePasteNotCanceled=false,dataType='auto',dialogCommited=false;if(!callback){callback=options;options=null;}
editor.on('paste',onPaste,null,null,0);editor.on('beforePaste',onBeforePaste,null,null,1000);if(getClipboardDataDirectly()===false){editor.removeListener('paste',onPaste);if(beforePasteNotCanceled&&editor.fire('pasteDialog',onDialogOpen)){editor.on('pasteDialogCommit',onDialogCommit);editor.on('dialogHide',function(evt){evt.removeListener();evt.data.removeListener('pasteDialogCommit',onDialogCommit);setTimeout(function(){if(!dialogCommited)
callback(null);},10);});}else
callback(null);}
function onPaste(evt){evt.removeListener();evt.cancel();callback(evt.data);}
function onBeforePaste(evt){evt.removeListener();beforePasteNotCanceled=true;dataType=evt.data.type;}
function onDialogCommit(evt){evt.removeListener();evt.cancel();dialogCommited=true;callback({type:dataType,dataValue:evt.data});}
function onDialogOpen(){this.customTitle=(options&&options.title);}};function addButtonsCommands(){addButtonCommand('Cut','cut',createCutCopyCmd('cut'),10,1);addButtonCommand('Copy','copy',createCutCopyCmd('copy'),20,4);addButtonCommand('Paste','paste',createPasteCmd(),30,8);function addButtonCommand(buttonName,commandName,command,toolbarOrder,ctxMenuOrder){var lang=editor.lang.clipboard[commandName];editor.addCommand(commandName,command);editor.ui.addButton&&editor.ui.addButton(buttonName,{label:lang,command:commandName,toolbar:'clipboard,'+toolbarOrder});if(editor.addMenuItems){editor.addMenuItem(commandName,{label:lang,command:commandName,group:'clipboard',order:ctxMenuOrder});}}}
function addListeners(){editor.on('key',onKey);editor.on('contentDom',addListenersToEditable);editor.on('selectionChange',function(evt){inReadOnly=evt.data.selection.getRanges()[0].checkReadOnly();setToolbarStates();});if(editor.contextMenu){editor.contextMenu.addListener(function(element,selection){inReadOnly=selection.getRanges()[0].checkReadOnly();return{cut:stateFromNamedCommand('cut'),copy:stateFromNamedCommand('copy'),paste:stateFromNamedCommand('paste')};});}}
function addListenersToEditable(){var editable=editor.editable();editable.on(mainPasteEvent,function(evt){if(CKEDITOR.env.ie&&preventBeforePasteEvent)
return;pasteDataFromClipboard(evt);});CKEDITOR.env.ie&&editable.on('paste',function(evt){if(preventPasteEvent)
return;preventPasteEventNow();evt.data.preventDefault();pasteDataFromClipboard(evt);if(!execIECommand('paste'))
editor.openDialog('paste');});if(CKEDITOR.env.ie){editable.on('contextmenu',preventBeforePasteEventNow,null,null,0);editable.on('beforepaste',function(evt){if(evt.data&&!evt.data.$.ctrlKey&&!evt.data.$.shiftKey)
preventBeforePasteEventNow();},null,null,0);}
editable.on('beforecut',function(){!preventBeforePasteEvent&&fixCut(editor);});var mouseupTimeout;editable.attachListener(CKEDITOR.env.ie?editable:editor.document.getDocumentElement(),'mouseup',function(){mouseupTimeout=setTimeout(function(){setToolbarStates();},0);});editor.on('destroy',function(){clearTimeout(mouseupTimeout);});editable.on('keyup',setToolbarStates);}
function createCutCopyCmd(type){return{type:type,canUndo:type=='cut',startDisabled:true,exec:function(data){function tryToCutCopy(type){if(CKEDITOR.env.ie)
return execIECommand(type);try{return editor.document.$.execCommand(type,false,null);}catch(e){return false;}}
this.type=='cut'&&fixCut();var success=tryToCutCopy(this.type);if(!success)
alert(editor.lang.clipboard[this.type+'Error']);return success;}};}
function createPasteCmd(){return{canUndo:false,async:true,exec:function(editor,data){var fire=function(data,withBeforePaste){data&&firePasteEvents(data.type,data.dataValue,!!withBeforePaste);editor.fire('afterCommandExec',{name:'paste',command:cmd,returnValue:!!data});},cmd=this;if(typeof data=='string')
fire({type:'auto',dataValue:data},1);else
editor.getClipboardData(fire);}};}
function preventPasteEventNow(){preventPasteEvent=1;setTimeout(function(){preventPasteEvent=0;},100);}
function preventBeforePasteEventNow(){preventBeforePasteEvent=1;setTimeout(function(){preventBeforePasteEvent=0;},10);}
function execIECommand(command){var doc=editor.document,body=doc.getBody(),enabled=false,onExec=function(){enabled=true;};body.on(command,onExec);(CKEDITOR.env.version>7?doc.$:doc.$.selection.createRange())['execCommand'](command);body.removeListener(command,onExec);return enabled;}
function firePasteEvents(type,data,withBeforePaste){var eventData={type:type};if(withBeforePaste){if(editor.fire('beforePaste',eventData)===false)
return false;}
if(!data)
return false;eventData.dataValue=data;return editor.fire('paste',eventData);}
function fixCut(){if(!CKEDITOR.env.ie||CKEDITOR.env.quirks)
return;var sel=editor.getSelection(),control,range,dummy;if((sel.getType()==CKEDITOR.SELECTION_ELEMENT)&&(control=sel.getSelectedElement())){range=sel.getRanges()[0];dummy=editor.document.createText('');dummy.insertBefore(control);range.setStartBefore(dummy);range.setEndAfter(control);sel.selectRanges([range]);setTimeout(function(){if(control.getParent()){dummy.remove();sel.selectElement(control);}},0);}}
function getClipboardDataByPastebin(evt,callback){var doc=editor.document,editable=editor.editable(),cancel=function(evt){evt.cancel();},blurListener;if(doc.getById('cke_pastebin'))
return;var sel=editor.getSelection();var bms=sel.createBookmarks();if(CKEDITOR.env.ie)
sel.root.fire('selectionchange');var pastebin=new CKEDITOR.dom.element((CKEDITOR.env.webkit||editable.is('body'))&&!CKEDITOR.env.ie?'body':'div',doc);pastebin.setAttributes({id:'cke_pastebin','data-cke-temp':'1'});var containerOffset=0,offsetParent,win=doc.getWindow();if(CKEDITOR.env.webkit){editable.append(pastebin);pastebin.addClass('cke_editable');if(!editable.is('body')){if(editable.getComputedStyle('position')!='static')
offsetParent=editable;else
offsetParent=CKEDITOR.dom.element.get(editable.$.offsetParent);containerOffset=offsetParent.getDocumentPosition().y;}}else{editable.getAscendant(CKEDITOR.env.ie?'body':'html',1).append(pastebin);}
pastebin.setStyles({position:'absolute',top:(win.getScrollPosition().y-containerOffset+10)+'px',width:'1px',height:Math.max(1,win.getViewPaneSize().height-20)+'px',overflow:'hidden',margin:0,padding:0});var isEditingHost=pastebin.getParent().isReadOnly();if(isEditingHost){pastebin.setOpacity(0);pastebin.setAttribute('contenteditable',true);}
else
pastebin.setStyle(editor.config.contentsLangDirection=='ltr'?'left':'right','-1000px');editor.on('selectionChange',cancel,null,null,0);if(CKEDITOR.env.webkit||CKEDITOR.env.gecko)
blurListener=editable.once('blur',cancel,null,null,-100);isEditingHost&&pastebin.focus();var range=new CKEDITOR.dom.range(pastebin);range.selectNodeContents(pastebin);var selPastebin=range.select();if(CKEDITOR.env.ie){blurListener=editable.once('blur',function(evt){editor.lockSelection(selPastebin);});}
var scrollTop=CKEDITOR.document.getWindow().getScrollPosition().y;setTimeout(function(){if(CKEDITOR.env.webkit)
CKEDITOR.document.getBody().$.scrollTop=scrollTop;blurListener&&blurListener.removeListener();if(CKEDITOR.env.ie)
editable.focus();sel.selectBookmarks(bms);pastebin.remove();var bogusSpan;if(CKEDITOR.env.webkit&&(bogusSpan=pastebin.getFirst())&&(bogusSpan.is&&bogusSpan.hasClass('Apple-style-span')))
pastebin=bogusSpan;editor.removeListener('selectionChange',cancel);callback(pastebin.getHtml());},0);}
function getClipboardDataDirectly(){if(CKEDITOR.env.ie){editor.focus();preventPasteEventNow();var focusManager=editor.focusManager;focusManager.lock();if(editor.editable().fire(mainPasteEvent)&&!execIECommand('paste')){focusManager.unlock();return false;}
focusManager.unlock();}else{try{if(editor.editable().fire(mainPasteEvent)&&!editor.document.$.execCommand('Paste',false,null))
throw 0;}catch(e){return false;}}
return true;}
function onKey(event){if(editor.mode!='wysiwyg')
return;switch(event.data.keyCode){case CKEDITOR.CTRL+86:case CKEDITOR.SHIFT+45:var editable=editor.editable();preventPasteEventNow();!CKEDITOR.env.ie&&editable.fire('beforepaste');return;case CKEDITOR.CTRL+88:case CKEDITOR.SHIFT+46:editor.fire('saveSnapshot');setTimeout(function(){editor.fire('saveSnapshot');},50);}}
function pasteDataFromClipboard(evt){var eventData={type:'auto'};var beforePasteNotCanceled=editor.fire('beforePaste',eventData);getClipboardDataByPastebin(evt,function(data){data=data.replace(/<span[^>]+data-cke-bookmark[^<]*?<\/span>/ig,'');beforePasteNotCanceled&&firePasteEvents(eventData.type,data,0,1);});}
function setToolbarStates(){if(editor.mode!='wysiwyg')
return;var pasteState=stateFromNamedCommand('paste');editor.getCommand('cut').setState(stateFromNamedCommand('cut'));editor.getCommand('copy').setState(stateFromNamedCommand('copy'));editor.getCommand('paste').setState(pasteState);editor.fire('pasteState',pasteState);}
function stateFromNamedCommand(command){if(inReadOnly&&command in{paste:1,cut:1})
return CKEDITOR.TRISTATE_DISABLED;if(command=='paste')
return CKEDITOR.TRISTATE_OFF;var sel=editor.getSelection(),ranges=sel.getRanges(),selectionIsEmpty=sel.getType()==CKEDITOR.SELECTION_NONE||(ranges.length==1&&ranges[0].collapsed);return selectionIsEmpty?CKEDITOR.TRISTATE_DISABLED:CKEDITOR.TRISTATE_OFF;}}
function recogniseContentType(data){if(CKEDITOR.env.webkit){if(!data.match(/^[^<]*$/g)&&!data.match(/^(<div><br( ?\/)?><\/div>|<div>[^<]*<\/div>)*$/gi))
return'html';}else if(CKEDITOR.env.ie){if(!data.match(/^([^<]|<br( ?\/)?>)*$/gi)&&!data.match(/^(<p>([^<]|<br( ?\/)?>)*<\/p>|(\r\n))*$/gi))
return'html';}else if(CKEDITOR.env.gecko){if(!data.match(/^([^<]|<br( ?\/)?>)*$/gi))
return'html';}else
return'html';return'htmlifiedtext';}
function htmlifiedTextHtmlification(config,data){function repeatParagraphs(repeats){return CKEDITOR.tools.repeat('</p><p>',~~(repeats/2))+(repeats%2==1?'<br>':'');}
data=data.replace(/\s+/g,' ').replace(/> +</g,'><').replace(/<br ?\/>/gi,'<br>');data=data.replace(/<\/?[A-Z]+>/g,function(match){return match.toLowerCase();});if(data.match(/^[^<]$/))
return data;if(CKEDITOR.env.webkit&&data.indexOf('<div>')>-1){data=data.replace(/^(<div>(<br>|)<\/div>)(?!$|(<div>(<br>|)<\/div>))/g,'<br>').replace(/^(<div>(<br>|)<\/div>){2}(?!$)/g,'<div></div>');if(data.match(/<div>(<br>|)<\/div>/)){data='<p>'+data.replace(/(<div>(<br>|)<\/div>)+/g,function(match){return repeatParagraphs(match.split('</div><div>').length+1);})+'</p>';}
data=data.replace(/<\/div><div>/g,'<br>');data=data.replace(/<\/?div>/g,'');}
if(CKEDITOR.env.gecko&&config.enterMode!=CKEDITOR.ENTER_BR){if(CKEDITOR.env.gecko)
data=data.replace(/^<br><br>$/,'<br>');if(data.indexOf('<br><br>')>-1){data='<p>'+data.replace(/(<br>){2,}/g,function(match){return repeatParagraphs(match.length/4);})+'</p>';}}
return switchEnterMode(config,data);}
function getTextificationFilter(editor){var filter=new CKEDITOR.htmlParser.filter();var replaceWithParaIf={blockquote:1,dl:1,fieldset:1,h1:1,h2:1,h3:1,h4:1,h5:1,h6:1,ol:1,p:1,table:1,ul:1},stripInlineIf=CKEDITOR.tools.extend({br:0},CKEDITOR.dtd.$inline),allowedIf={p:1,br:1,'cke:br':1},knownIf=CKEDITOR.dtd,removeIf=CKEDITOR.tools.extend({area:1,basefont:1,embed:1,iframe:1,map:1,object:1,param:1},CKEDITOR.dtd.$nonBodyContent,CKEDITOR.dtd.$cdata);var flattenTableCell=function(element){delete element.name;element.add(new CKEDITOR.htmlParser.text(' '));},squashHeader=function(element){var next=element,br,el;while((next=next.next)&&next.name&&next.name.match(/^h\d$/)){br=new CKEDITOR.htmlParser.element('cke:br');br.isEmpty=true;element.add(br);while((el=next.children.shift()))
element.add(el);}};filter.addRules({elements:{h1:squashHeader,h2:squashHeader,h3:squashHeader,h4:squashHeader,h5:squashHeader,h6:squashHeader,img:function(element){var alt=CKEDITOR.tools.trim(element.attributes.alt||''),txt=' ';if(alt&&!alt.match(/(^http|\.(jpe?g|gif|png))/i))
txt=' ['+alt+'] ';return new CKEDITOR.htmlParser.text(txt);},td:flattenTableCell,th:flattenTableCell,$:function(element){var initialName=element.name,br;if(removeIf[initialName])
return false;element.attributes={};if(initialName=='br')
return element;if(replaceWithParaIf[initialName])
element.name='p';else if(stripInlineIf[initialName])
delete element.name;else if(knownIf[initialName]){br=new CKEDITOR.htmlParser.element('cke:br');br.isEmpty=true;if(CKEDITOR.dtd.$empty[initialName])
return br;element.add(br,0);br=br.clone();br.isEmpty=true;element.add(br);delete element.name;}
if(!allowedIf[element.name])
delete element.name;return element;}}},{applyToAll:true});return filter;}
function htmlTextification(config,data,filter){var fragment=new CKEDITOR.htmlParser.fragment.fromHtml(data),writer=new CKEDITOR.htmlParser.basicWriter();fragment.writeHtml(writer,filter);data=writer.getHtml();data=data.replace(/\s*(<\/?[a-z:]+ ?\/?>)\s*/g,'$1').replace(/(<cke:br \/>){2,}/g,'<cke:br />').replace(/(<cke:br \/>)(<\/?p>|<br \/>)/g,'$2').replace(/(<\/?p>|<br \/>)(<cke:br \/>)/g,'$1').replace(/<(cke:)?br( \/)?>/g,'<br>').replace(/<p><\/p>/g,'');var nested=0;data=data.replace(/<\/?p>/g,function(match){if(match=='<p>'){if(++nested>1)
return'</p><p>';}else{if(--nested>0)
return'</p><p>';}
return match;}).replace(/<p><\/p>/g,'');return switchEnterMode(config,data);}
function switchEnterMode(config,data){if(config.enterMode==CKEDITOR.ENTER_BR){data=data.replace(/(<\/p><p>)+/g,function(match){return CKEDITOR.tools.repeat('<br>',match.length/7*2);}).replace(/<\/?p>/g,'');}else if(config.enterMode==CKEDITOR.ENTER_DIV)
data=data.replace(/<(\/)?p>/g,'<$1div>');return data;}})();