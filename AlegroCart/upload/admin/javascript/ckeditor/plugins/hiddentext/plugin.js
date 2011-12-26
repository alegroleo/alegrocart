CKEDITOR.plugins.add( 'hiddentext',
{
	init: function( editor )
	{
		editor.addCommand( 'inserthiddentext',
			{
				exec : function( editor )
				{    
					
					editor.insertHtml( '<hidden>*Hidden Description*');
				}
			});
		editor.ui.addButton( 'hiddentext',
		{
			label: 'Hidden Text',
			command: 'inserthiddentext',
			icon: this.path + 'images/hiddentext.gif'
		} );
	}
} );