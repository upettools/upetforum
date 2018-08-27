﻿
(function(){var template='<a id="{id}"'+' class="cke_button cke_button__{name} cke_button_{state} {cls}"'+
(CKEDITOR.env.gecko&&!CKEDITOR.env.hc?'':' href="javascript:void(\'{titleJs}\')"')+' title="{title}"'+' tabindex="-1"'+' hidefocus="true"'+' role="button"'+' aria-labelledby="{id}_label"'+' aria-haspopup="{hasArrow}"'+' aria-disabled="{ariaDisabled}"';if(CKEDITOR.env.gecko&&CKEDITOR.env.mac)
template+=' onkeypress="return false;"';if(CKEDITOR.env.gecko)
template+=' onblur="this.style.cssText = this.style.cssText;"';template+=' onkeydown="return CKEDITOR.tools.callFunction({keydownFn},event);"'+' onfocus="return CKEDITOR.tools.callFunction({focusFn},event);" '+
(CKEDITOR.env.ie?'onclick="return false;" onmouseup':'onclick')+'="CKEDITOR.tools.callFunction({clickFn},this);return false;">'+'<span class="cke_button_icon cke_button__{iconName}_icon" style="{style}"';template+='>&nbsp;</span>'+'<span id="{id}_label" class="cke_button_label cke_button__{name}_label" aria-hidden="false">{label}</span>'+'{arrowHtml}'+'</a>';var templateArrow='<span class="cke_button_arrow">'+
(CKEDITOR.env.hc?'&#9660;':'')+'</span>';var btnArrowTpl=CKEDITOR.addTemplate('buttonArrow',templateArrow),btnTpl=CKEDITOR.addTemplate('button',template);CKEDITOR.plugins.add('button',{lang:'ca,cs,de,el,en,en-gb,eo,fa,fi,fr,gl,he,hu,it,ja,km,nb,nl,pl,pt,pt-br,ro,ru,sl,sv,tt,uk,vi,zh-cn',beforeInit:function(editor){editor.ui.addHandler(CKEDITOR.UI_BUTTON,CKEDITOR.ui.button.handler);}});CKEDITOR.UI_BUTTON='button';CKEDITOR.ui.button=function(definition){CKEDITOR.tools.extend(this,definition,{title:definition.label,click:definition.click||function(editor){editor.execCommand(definition.command);}});this._={};};CKEDITOR.ui.button.handler={create:function(definition){return new CKEDITOR.ui.button(definition);}};CKEDITOR.ui.button.prototype={render:function(editor,output){var env=CKEDITOR.env,id=this._.id=CKEDITOR.tools.getNextId(),stateName='',command=this.command,clickFn;this._.editor=editor;var instance={id:id,button:this,editor:editor,focus:function(){var element=CKEDITOR.document.getById(id);element.focus();},execute:function(){this.button.click(editor);},attach:function(editor){this.button.attach(editor);}};var keydownFn=CKEDITOR.tools.addFunction(function(ev){if(instance.onkey){ev=new CKEDITOR.dom.event(ev);return(instance.onkey(instance,ev.getKeystroke())!==false);}});var focusFn=CKEDITOR.tools.addFunction(function(ev){var retVal;if(instance.onfocus)
retVal=(instance.onfocus(instance,new CKEDITOR.dom.event(ev))!==false);return retVal;});var selLocked=0;instance.clickFn=clickFn=CKEDITOR.tools.addFunction(function(){if(selLocked){editor.unlockSelection(1);selLocked=0;}
instance.execute();});if(this.modes){var modeStates={};function updateState(){var mode=editor.mode;if(mode){var state=this.modes[mode]?modeStates[mode]!=undefined?modeStates[mode]:CKEDITOR.TRISTATE_OFF:CKEDITOR.TRISTATE_DISABLED;state=editor.readOnly&&!this.readOnly?CKEDITOR.TRISTATE_DISABLED:state;this.setState(state);if(this.refresh)
this.refresh();}}
editor.on('beforeModeUnload',function(){if(editor.mode&&this._.state!=CKEDITOR.TRISTATE_DISABLED)
modeStates[editor.mode]=this._.state;},this);editor.on('activeFilterChange',updateState,this);editor.on('mode',updateState,this);!this.readOnly&&editor.on('readOnly',updateState,this);}else if(command){command=editor.getCommand(command);if(command){command.on('state',function(){this.setState(command.state);},this);stateName+=(command.state==CKEDITOR.TRISTATE_ON?'on':command.state==CKEDITOR.TRISTATE_DISABLED?'disabled':'off');}}
if(this.directional){editor.on('contentDirChanged',function(evt){var el=CKEDITOR.document.getById(this._.id),icon=el.getFirst();var pathDir=evt.data;if(pathDir!=editor.lang.dir)
el.addClass('cke_'+pathDir);else
el.removeClass('cke_ltr').removeClass('cke_rtl');icon.setAttribute('style',CKEDITOR.skin.getIconStyle(iconName,pathDir=='rtl',this.icon,this.iconOffset));},this);}
if(!command)
stateName+='off';var name=this.name||this.command,iconName=name;if(this.icon&&!(/\./).test(this.icon)){iconName=this.icon;this.icon=null;}
var params={id:id,name:name,iconName:iconName,label:this.label,cls:this.className||'',state:stateName,ariaDisabled:stateName=='disabled'?'true':'false',title:this.title,titleJs:env.gecko&&!env.hc?'':(this.title||'').replace("'",''),hasArrow:this.hasArrow?'true':'false',keydownFn:keydownFn,focusFn:focusFn,clickFn:clickFn,style:CKEDITOR.skin.getIconStyle(iconName,(editor.lang.dir=='rtl'),this.icon,this.iconOffset),arrowHtml:this.hasArrow?btnArrowTpl.output():''};btnTpl.output(params,output);if(this.onRender)
this.onRender();return instance;},setState:function(state){if(this._.state==state)
return false;this._.state=state;var element=CKEDITOR.document.getById(this._.id);if(element){element.setState(state,'cke_button');state==CKEDITOR.TRISTATE_DISABLED?element.setAttribute('aria-disabled',true):element.removeAttribute('aria-disabled');if(!this.hasArrow){state==CKEDITOR.TRISTATE_ON?element.setAttribute('aria-pressed',true):element.removeAttribute('aria-pressed');}else{var newLabel=state==CKEDITOR.TRISTATE_ON?this._.editor.lang.button.selectedLabel.replace(/%1/g,this.label):this.label;CKEDITOR.document.getById(this._.id+'_label').setText(newLabel);}
return true;}else
return false;},getState:function(state){return this._.state;},toFeature:function(editor){if(this._.feature)
return this._.feature;var feature=this;if(!this.allowedContent&&!this.requiredContent&&this.command)
feature=editor.getCommand(this.command)||feature;return this._.feature=feature;}};CKEDITOR.ui.prototype.addButton=function(name,definition){this.add(name,CKEDITOR.UI_BUTTON,definition);};})();