﻿
(function(){function addSafely(collection,element,database){if(!element.is||!element.getCustomData('block_processed')){element.is&&CKEDITOR.dom.element.setMarker(database,element,'block_processed',true);collection.push(element);}}
function getNonEmptyChildren(element){var retval=[];var children=element.getChildren();for(var i=0;i<children.count();i++){var child=children.getItem(i);if(!(child.type===CKEDITOR.NODE_TEXT&&(/^[ \t\n\r]+$/).test(child.getText())))
retval.push(child);}
return retval;}
function divDialog(editor,command){var divLimitDefinition=(function(){var definition=CKEDITOR.tools.extend({},CKEDITOR.dtd.$blockLimit);if(editor.config.div_wrapTable){delete definition.td;delete definition.th;}
return definition;})();var dtd=CKEDITOR.dtd.div;function getDivContainer(element){var container=editor.elementPath(element).blockLimit;if(container.isReadOnly())
container=container.getParent();if(editor.config.div_wrapTable&&container.is(['td','th'])){var parentPath=editor.elementPath(container.getParent());container=parentPath.blockLimit;}
return container;}
function setupFields(){this.foreach(function(field){if(/^(?!vbox|hbox)/.test(field.type)){if(!field.setup){field.setup=function(element){field.setValue(element.getAttribute(field.id)||'',1);};}
if(!field.commit){field.commit=function(element){var fieldValue=this.getValue();if('dir'==field.id&&element.getComputedStyle('direction')==fieldValue)
return;if(fieldValue)
element.setAttribute(field.id,fieldValue);else
element.removeAttribute(field.id);};}}});}
function createDiv(editor){var containers=[];var database={};var containedBlocks=[],block;var selection=editor.getSelection(),ranges=selection.getRanges();var bookmarks=selection.createBookmarks();var i,iterator;var blockTag=editor.config.enterMode==CKEDITOR.ENTER_DIV?'div':'p';for(i=0;i<ranges.length;i++){iterator=ranges[i].createIterator();while((block=iterator.getNextParagraph())){if(block.getName()in divLimitDefinition&&!block.isReadOnly()){var j,childNodes=block.getChildren();for(j=0;j<childNodes.count();j++)
addSafely(containedBlocks,childNodes.getItem(j),database);}else{while(!dtd[block.getName()]&&!block.equals(ranges[i].root))
block=block.getParent();addSafely(containedBlocks,block,database);}}}
CKEDITOR.dom.element.clearAllMarkers(database);var blockGroups=groupByDivLimit(containedBlocks);var ancestor,blockEl,divElement;for(i=0;i<blockGroups.length;i++){var currentNode=blockGroups[i][0];ancestor=currentNode.getParent();for(j=1;j<blockGroups[i].length;j++)
ancestor=ancestor.getCommonAncestor(blockGroups[i][j]);divElement=new CKEDITOR.dom.element('div',editor.document);for(j=0;j<blockGroups[i].length;j++){currentNode=blockGroups[i][j];while(!currentNode.getParent().equals(ancestor))
currentNode=currentNode.getParent();blockGroups[i][j]=currentNode;}
var fixedBlock=null;for(j=0;j<blockGroups[i].length;j++){currentNode=blockGroups[i][j];if(!(currentNode.getCustomData&&currentNode.getCustomData('block_processed'))){currentNode.is&&CKEDITOR.dom.element.setMarker(database,currentNode,'block_processed',true);if(!j)
divElement.insertBefore(currentNode);divElement.append(currentNode);}}
CKEDITOR.dom.element.clearAllMarkers(database);containers.push(divElement);}
selection.selectBookmarks(bookmarks);return containers;}
function groupByDivLimit(nodes){var groups=[],lastDivLimit=null,path,block;for(var i=0;i<nodes.length;i++){block=nodes[i];var limit=getDivContainer(block);if(!limit.equals(lastDivLimit)){lastDivLimit=limit;groups.push([]);}
groups[groups.length-1].push(block);}
return groups;}
function commitInternally(targetFields){var dialog=this.getDialog(),element=dialog._element&&dialog._element.clone()||new CKEDITOR.dom.element('div',editor.document);this.commit(element,true);targetFields=[].concat(targetFields);var length=targetFields.length,field;for(var i=0;i<length;i++){field=dialog.getContentElement.apply(dialog,targetFields[i].split(':'));field&&field.setup&&field.setup(element,true);}}
var styles={};var containers=[];return{title:editor.lang.div.title,minWidth:400,minHeight:165,contents:[{id:'info',label:editor.lang.common.generalTab,title:editor.lang.common.generalTab,elements:[{type:'hbox',widths:['50%','50%'],children:[{id:'elementStyle',type:'select',style:'width: 100%;',label:editor.lang.div.styleSelectLabel,'default':'',items:[[editor.lang.common.notSet,'']],onChange:function(){commitInternally.call(this,['info:elementStyle','info:class','advanced:dir','advanced:style']);},setup:function(element){for(var name in styles)
styles[name].checkElementRemovable(element,true)&&this.setValue(name,1);},commit:function(element){var styleName;if((styleName=this.getValue())){var style=styles[styleName];style.applyToObject(element);}
else
element.removeAttribute('style');}},{id:'class',type:'text',requiredContent:'div(cke-xyz)',label:editor.lang.common.cssClass,'default':''}]}]},{id:'advanced',label:editor.lang.common.advancedTab,title:editor.lang.common.advancedTab,elements:[{type:'vbox',padding:1,children:[{type:'hbox',widths:['50%','50%'],children:[{type:'text',id:'id',requiredContent:'div[id]',label:editor.lang.common.id,'default':''},{type:'text',id:'lang',requiredContent:'div[lang]',label:editor.lang.common.langCode,'default':''}]},{type:'hbox',children:[{type:'text',id:'style',requiredContent:'div{cke-xyz}',style:'width: 100%;',label:editor.lang.common.cssStyle,'default':'',commit:function(element){element.setAttribute('style',this.getValue());}}]},{type:'hbox',children:[{type:'text',id:'title',requiredContent:'div[title]',style:'width: 100%;',label:editor.lang.common.advisoryTitle,'default':''}]},{type:'select',id:'dir',requiredContent:'div[dir]',style:'width: 100%;',label:editor.lang.common.langDir,'default':'',items:[[editor.lang.common.notSet,''],[editor.lang.common.langDirLtr,'ltr'],[editor.lang.common.langDirRtl,'rtl']]}]}]}],onLoad:function(){setupFields.call(this);var dialog=this,stylesField=this.getContentElement('info','elementStyle');editor.getStylesSet(function(stylesDefinitions){var styleName,style;if(stylesDefinitions){for(var i=0;i<stylesDefinitions.length;i++){var styleDefinition=stylesDefinitions[i];if(styleDefinition.element&&styleDefinition.element=='div'){styleName=styleDefinition.name;styles[styleName]=style=new CKEDITOR.style(styleDefinition);if(editor.filter.check(style)){stylesField.items.push([styleName,styleName]);stylesField.add(styleName,styleName);}}}}
stylesField[stylesField.items.length>1?'enable':'disable']();setTimeout(function(){dialog._element&&stylesField.setup(dialog._element);},0);});},onShow:function(){if(command=='editdiv'){this.setupContent(this._element=CKEDITOR.plugins.div.getSurroundDiv(editor));}},onOk:function(){if(command=='editdiv')
containers=[this._element];else
containers=createDiv(editor,true);var size=containers.length;for(var i=0;i<size;i++){this.commitContent(containers[i]);!containers[i].getAttribute('style')&&containers[i].removeAttribute('style');}
this.hide();},onHide:function(){if(command=='editdiv')
this._element.removeCustomData('elementStyle');delete this._element;}};}
CKEDITOR.dialog.add('creatediv',function(editor){return divDialog(editor,'creatediv');});CKEDITOR.dialog.add('editdiv',function(editor){return divDialog(editor,'editdiv');});})();