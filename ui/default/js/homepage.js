$(document).ready(function(){
	$("textarea.editor").ckeditor(function(){},{
			contentsCss:				DIR_TEMPLATE_WEB+'/bootstrap/css/bootstrap.min.css',
			disableNativeSpellChecker:	false,
			enterMode:					CKEDITOR.ENTER_BR,
			fontSize_defaultLabel:		'Default (13px)',
			fontSize_sizes:				'Smaller (11px)/11px;Default (13px)/13px;Bigger (15px)/15px',
			forcePasteAsPlainText:		true,
			format_tags:				'p;h1;h2;h3;h4;h5;h6;pre',
			shiftEnterMode:				CKEDITOR.ENTER_P,
			skin:						'BootstrapCK-Skin,'+DIR_TEMPLATE_WEB+'/ckeditor/',
			startupFocus:				true,
			stylesSet:					[
				{	name:	"Alert",	element:	"p",	attributes:	{	class:	'alert'	}},
				{	name:	"Alert: Danger",	element:	"p",	attributes:	{	class:	'alert alert-danger'	}},
				{	name:	"Alert: Info",		element:	"p",	attributes:	{	class:	'alert alert-info'	}},
				{	name:	"Alert: Success",	element:	"p",	attributes:	{	class:	'alert alert-success'	}},
				{	name:	"Button",			element:	"a",	attributes: {	class:	'btn'	}},
				{	name:	"Button: Danger",			element:	"a",	attributes: {	class:	'btn btn-danger'	}},
				{	name:	"Button: Info",			element:	"a",	attributes: {	class:	'btn btn-info'	}},
				{	name:	"Button: Primary",			element:	"a",	attributes: {	class:	'btn btn-primary'	}},
				{	name:	"Button: Success",			element:	"a",	attributes: {	class:	'btn btn-success'	}},
				{	name:	"Button: Warning",			element:	"a",	attributes: {	class:	'btn btn-warning'	}},
				{	name:	"Label",	element:	"span",	attributes:	{	class:	'label'	}},
				{	name:	"Label: Important",	element:	"span",	attributes:	{	class:	'label label-important'	}},
				{	name:	"Label: Info",	element:	"span",	attributes:	{	class:	'label label-info'	}},
				{	name:	"Label: Success",	element:	"span",	attributes:	{	class:	'label label-success'	}},
				{	name:	"Label: Warning",	element:	"span",	attributes:	{	class:	'label label-warning'	}},
				{	name:	"Table: Bordered",	element:	"table",	attributes:	{	class:	'table-bordered'	}},
				{	name:	"Table: Condensed",	element:	"table",	attributes:	{	class:	'table-condensed'	}},
				{	name:	"Table: Lined",	element:	"table",	attributes:	{	class:	'table'	}},
				{	name:	"Table: Striped",	element:	"table",	attributes:	{	class:	'table-striped'	}},
				{	name:	"Table: Fancy",	element:	"table",	attributes:	{	class:	'table-bordered table-striped'	}},
				{	name:	"Table: Fancy + Condensed",	element:	"table",	attributes:	{	class:	'table-bordered table-striped table-condensed'	}}
			],
			toolbar:					'Custom',
			toolbar_Custom:	[
				{ name:	'styles',		items: ['Bold','Italic','Strike','Subscript','Superscript','Styles','Format','FontSize'] },
				{ name: 'links',		items: ['Link','Unlink'] },
				{ name: 'table',		items: ['Table'] },
				{ name: 'clipboard',	items: ['Cut','Copy','Paste','-','Undo','Redo','RemoveFormat'] }
			],
			toolbarCanCollapse:			false
		}
	);
});