﻿
(function(){CKEDITOR.plugins.add('enterkey',{init:function(editor){editor.addCommand('enter',{modes:{wysiwyg:1},editorFocus:false,exec:function(editor){enter(editor);}});editor.addCommand('shiftEnter',{modes:{wysiwyg:1},editorFocus:false,exec:function(editor){shiftEnter(editor);}});editor.setKeystroke([[13,'enter'],[CKEDITOR.SHIFT+13,'shiftEnter']]);}});var whitespaces=CKEDITOR.dom.walker.whitespaces(),bookmark=CKEDITOR.dom.walker.bookmark();CKEDITOR.plugins.enterkey={enterBlock:function(editor,mode,range,forceMode){range=range||getRange(editor);if(!range)
return;var doc=range.document;var atBlockStart=range.checkStartOfBlock(),atBlockEnd=range.checkEndOfBlock(),path=editor.elementPath(range.startContainer),block=path.block,blockTag=(mode==CKEDITOR.ENTER_DIV?'div':'p'),newBlock;if(atBlockStart&&atBlockEnd){if(block&&(block.is('li')||block.getParent().is('li'))){if(!block.is('li'))
block=block.getParent();var blockParent=block.getParent(),blockGrandParent=blockParent.getParent(),firstChild=!block.hasPrevious(),lastChild=!block.hasNext(),selection=editor.getSelection(),bookmarks=selection.createBookmarks(),orgDir=block.getDirection(1),className=block.getAttribute('class'),style=block.getAttribute('style'),dirLoose=blockGrandParent.getDirection(1)!=orgDir,enterMode=editor.enterMode,needsBlock=enterMode!=CKEDITOR.ENTER_BR||dirLoose||style||className,child;if(blockGrandParent.is('li')){if(firstChild||lastChild)
block[firstChild?'insertBefore':'insertAfter'](blockGrandParent);else
block.breakParent(blockGrandParent);}
else if(!needsBlock){block.appendBogus(true);if(firstChild||lastChild){while((child=block[firstChild?'getFirst':'getLast']()))
child[firstChild?'insertBefore':'insertAfter'](blockParent);}
else{block.breakParent(blockParent);while((child=block.getLast()))
child.insertAfter(blockParent);}
block.remove();}else{if(path.block.is('li')){newBlock=doc.createElement(mode==CKEDITOR.ENTER_P?'p':'div');if(dirLoose)
newBlock.setAttribute('dir',orgDir);style&&newBlock.setAttribute('style',style);className&&newBlock.setAttribute('class',className);block.moveChildren(newBlock);}
else
newBlock=path.block;if(firstChild||lastChild)
newBlock[firstChild?'insertBefore':'insertAfter'](blockParent);else{block.breakParent(blockParent);newBlock.insertAfter(blockParent);}
block.remove();}
selection.selectBookmarks(bookmarks);return;}
if(block&&block.getParent().is('blockquote')){block.breakParent(block.getParent());if(!block.getPrevious().getFirst(CKEDITOR.dom.walker.invisible(1)))
block.getPrevious().remove();if(!block.getNext().getFirst(CKEDITOR.dom.walker.invisible(1)))
block.getNext().remove();range.moveToElementEditStart(block);range.select();return;}}
else if(block&&block.is('pre')){if(!atBlockEnd){enterBr(editor,mode,range,forceMode);return;}}
var splitInfo=range.splitBlock(blockTag);if(!splitInfo)
return;var previousBlock=splitInfo.previousBlock,nextBlock=splitInfo.nextBlock;var isStartOfBlock=splitInfo.wasStartOfBlock,isEndOfBlock=splitInfo.wasEndOfBlock;var node;if(nextBlock){node=nextBlock.getParent();if(node.is('li')){nextBlock.breakParent(node);nextBlock.move(nextBlock.getNext(),1);}}else if(previousBlock&&(node=previousBlock.getParent())&&node.is('li')){previousBlock.breakParent(node);node=previousBlock.getNext();range.moveToElementEditStart(node);previousBlock.move(previousBlock.getPrevious());}
if(!isStartOfBlock&&!isEndOfBlock){if(nextBlock.is('li')){var walkerRange=range.clone();walkerRange.selectNodeContents(nextBlock);var walker=new CKEDITOR.dom.walker(walkerRange);walker.evaluator=function(node){return!(bookmark(node)||whitespaces(node)||node.type==CKEDITOR.NODE_ELEMENT&&node.getName()in CKEDITOR.dtd.$inline&&!(node.getName()in CKEDITOR.dtd.$empty));};node=walker.next();if(node&&node.type==CKEDITOR.NODE_ELEMENT&&node.is('ul','ol'))
(CKEDITOR.env.needsBrFiller?doc.createElement('br'):doc.createText('\xa0')).insertBefore(node);}
if(nextBlock)
range.moveToElementEditStart(nextBlock);}else{var newBlockDir;if(previousBlock){if(previousBlock.is('li')||!(headerTagRegex.test(previousBlock.getName())||previousBlock.is('pre'))){newBlock=previousBlock.clone();}}else if(nextBlock)
newBlock=nextBlock.clone();if(!newBlock){if(node&&node.is('li'))
newBlock=node;else{newBlock=doc.createElement(blockTag);if(previousBlock&&(newBlockDir=previousBlock.getDirection()))
newBlock.setAttribute('dir',newBlockDir);}}
else if(forceMode&&!newBlock.is('li'))
newBlock.renameNode(blockTag);var elementPath=splitInfo.elementPath;if(elementPath){for(var i=0,len=elementPath.elements.length;i<len;i++){var element=elementPath.elements[i];if(element.equals(elementPath.block)||element.equals(elementPath.blockLimit))
break;if(CKEDITOR.dtd.$removeEmpty[element.getName()]){element=element.clone();newBlock.moveChildren(element);newBlock.append(element);}}}
newBlock.appendBogus();if(!newBlock.getParent())
range.insertNode(newBlock);newBlock.is('li')&&newBlock.removeAttribute('value');if(CKEDITOR.env.ie&&isStartOfBlock&&(!isEndOfBlock||!previousBlock.getChildCount())){range.moveToElementEditStart(isEndOfBlock?previousBlock:newBlock);range.select();}
range.moveToElementEditStart(isStartOfBlock&&!isEndOfBlock?nextBlock:newBlock);}
range.select();range.scrollIntoView();},enterBr:function(editor,mode,range,forceMode){range=range||getRange(editor);if(!range)
return;var doc=range.document;var blockTag=(mode==CKEDITOR.ENTER_DIV?'div':'p');var isEndOfBlock=range.checkEndOfBlock();var elementPath=new CKEDITOR.dom.elementPath(editor.getSelection().getStartElement());var startBlock=elementPath.block,startBlockTag=startBlock&&elementPath.block.getName();var isPre=false;if(!forceMode&&startBlockTag=='li'){enterBlock(editor,mode,range,forceMode);return;}
if(!forceMode&&isEndOfBlock&&headerTagRegex.test(startBlockTag)){var newBlock,newBlockDir;if((newBlockDir=startBlock.getDirection())){newBlock=doc.createElement('div');newBlock.setAttribute('dir',newBlockDir);newBlock.insertAfter(startBlock);range.setStart(newBlock,0);}else{doc.createElement('br').insertAfter(startBlock);if(CKEDITOR.env.gecko)
doc.createText('').insertAfter(startBlock);range.setStartAt(startBlock.getNext(),CKEDITOR.env.ie?CKEDITOR.POSITION_BEFORE_START:CKEDITOR.POSITION_AFTER_START);}}else{var lineBreak;if(startBlockTag=='pre'&&CKEDITOR.env.ie&&CKEDITOR.env.version<8)
lineBreak=doc.createText('\r');else
lineBreak=doc.createElement('br');range.deleteContents();range.insertNode(lineBreak);if(!CKEDITOR.env.needsBrFiller)
range.setStartAt(lineBreak,CKEDITOR.POSITION_AFTER_END);else{doc.createText('\ufeff').insertAfter(lineBreak);if(isEndOfBlock){(startBlock||elementPath.blockLimit).appendBogus();}
lineBreak.getNext().$.nodeValue='';range.setStartAt(lineBreak.getNext(),CKEDITOR.POSITION_AFTER_START);}}
range.collapse(true);range.select();range.scrollIntoView();}};var plugin=CKEDITOR.plugins.enterkey,enterBr=plugin.enterBr,enterBlock=plugin.enterBlock,headerTagRegex=/^h[1-6]$/;function shiftEnter(editor){return enter(editor,editor.activeShiftEnterMode,1);}
function enter(editor,mode,forceMode){forceMode=editor.config.forceEnterMode||forceMode;if(editor.mode!='wysiwyg')
return;if(!mode)
mode=editor.activeEnterMode;var path=editor.elementPath();if(!path.isContextFor('p')){mode=CKEDITOR.ENTER_BR;forceMode=1;}
editor.fire('saveSnapshot');if(mode==CKEDITOR.ENTER_BR)
enterBr(editor,mode,null,forceMode);else
enterBlock(editor,mode,null,forceMode);editor.fire('saveSnapshot');}
function getRange(editor){var ranges=editor.getSelection().getRanges(true);for(var i=ranges.length-1;i>0;i--){ranges[i].deleteContents();}
return ranges[0];}})();