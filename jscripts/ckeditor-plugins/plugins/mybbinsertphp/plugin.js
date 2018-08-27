/*
 Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.plugins.add('mybbinsertphp',
	{
		requires: 'dialog',
		lang: 'en,fa', // %REMOVE_LINE_CORE%
		icons: 'mybbinsertphp', // %REMOVE_LINE_CORE%
		init: function (editor) {
			if (CKEDITOR.config.mybbinsertphp_class) {
				CKEDITOR.addCss(
					'div.' + CKEDITOR.config.mybbinsertphp_class + ' {' +
					'text-align:left;direction:ltr;' + CKEDITOR.config.mybbinsertphp_style +
					'}' +
					'div.' + CKEDITOR.config.mybbinsertphp_class + ':before {' +
					'display:block;border-bottom: 1px solid #ccc;font-weight: bold;padding-bottom: 3px;margin: 0 0 10px 0;content:\'' + editor.lang.mybbinsertphp.code + ':\'' +
					'}'
				);
			}
			// allowed and required content is the same for this plugin
			var required = CKEDITOR.config.mybbinsertphp_class ? ( 'div( ' + CKEDITOR.config.mybbinsertphp_class + ' )' ) : 'div';
			editor.addCommand('mybbinsertphp', new CKEDITOR.dialogCommand('mybbinsertphp', {
				allowedContent: required,
				requiredContent: required
			}));
			editor.ui.addButton && editor.ui.addButton('mybbinsertphp',
				{
					label: editor.lang.mybbinsertphp.title,
					icon: this.path + 'icons/mybbinsertphp.png',
					command: 'mybbinsertphp',
					toolbar: 'insert,99'
				});

			if (editor.contextMenu) {
				editor.addMenuGroup('code');
				editor.addMenuItem('mybbinsertphp',
					{
						label: editor.lang.mybbinsertphp.edit,
						icon: this.path + 'icons/mybbinsertphp.png',
						command: 'mybbinsertphp',
						group: 'code'
					});
				editor.contextMenu.addListener(function (element) {
					if (element)
						element = element.getAscendant('div', true);
					if (element && !element.isReadOnly() && element.hasClass(editor.config.mybbinsertphp_class))
						return {mybbinsertphp: CKEDITOR.TRISTATE_OFF};
					return null;
				});
			}

			CKEDITOR.dialog.add('mybbinsertphp', function (editor) {
				return {
					title: editor.lang.mybbinsertphp.title,
					minWidth: 540,
					minHeight: 380,
					contents: [
						{
							id: 'general',
							label: editor.lang.mybbinsertphp.code,
							elements: [
								{
									type: 'textarea',
									id: 'contents',
									label: editor.lang.mybbinsertphp.code,
									cols: 140,
									rows: 22,
									inputStyle: 'cursor:auto;' +
										'tab-size:4;' +
										'text-align:left!important;direction:ltr!important;',
									validate: CKEDITOR.dialog.validate.notEmpty(editor.lang.mybbinsertphp.notEmpty),
									required: true,
									setup: function (element) {
										var html = element.getHtml();
										if (html) {
											var div = document.createElement('div');
											div.innerHTML = html.replace("\n", "<br>")
											this.setValue(div.firstChild.nodeValue);
										}
									},
									commit: function (element) {
										var value = CKEDITOR.tools.htmlEncode(this.getValue());
										value = value.replace(/\n/g, "<br>");
										value = value.replace(/\$/g, '&#36;');
										value = value.replace(/\$([0-9])/g, '\\\$\\1');
										value = value.replace(/\\/g, '&#92;');
										value = value.replace(/\t/g, '&nbsp;&nbsp;&nbsp;&nbsp;');
										value = value.replace(/  /g, '&nbsp;&nbsp;');
										element.setHtml(value);
									}
								}
							]
						}
					],
					onShow: function () {
						var sel = editor.getSelection(),
							element = sel.getStartElement();
						if (element)
							element = element.getAscendant('div', true);

						if (!element || element.getName() != 'div' || !element.hasClass(editor.config.mybbinsertphp_class)) {
							element = editor.document.createElement('div');
							this.insertMode = true;
						}
						else
							this.insertMode = false;

						this.pre = element;
						this.setupContent(this.pre);
					},
					onOk: function () {
						if (editor.config.mybbinsertphp_class)
							this.pre.setAttribute('class', editor.config.mybbinsertphp_class);

						if (this.insertMode)
							editor.insertElement(this.pre);

						this.commitContent(this.pre);
					}
				};
			});
		}
	});

if (typeof(CKEDITOR.config.mybbinsertphp_style) == 'undefined')
	CKEDITOR.config.mybbinsertphp_style = 'background: #fff;border: 1px solid #ccc;padding: 10px;margin: 1px 0;';
if (typeof(CKEDITOR.config.mybbinsertphp_class) == 'undefined')
	CKEDITOR.config.mybbinsertphp_class = 'codeblock-php';
