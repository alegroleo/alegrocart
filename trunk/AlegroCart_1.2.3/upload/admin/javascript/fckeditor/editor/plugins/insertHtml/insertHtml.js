

	var dialog = window.parent;
	var editorWindow = dialog.InnerDialogLoaded();
	var editorInstance = editorWindow.FCK;
	var FCKConfig = editorWindow.FCKConfig;
	var FCKTools = editorWindow.FCKTools;
	var FCKBrowserInfo = editorWindow.FCKBrowserInfo;


	// onload
	window.onload = function()
	{
		var insertHtmlTextArea = document.getElementById('insertHtmlTextArea');
		// set the seize of the textarea
		insertHtmlTextArea.style.width = (FCKConfig.insertHtml_textareaWidth || 300) + 'px';
		insertHtmlTextArea.style.height = (FCKConfig.insertHtml_textareaHeight || 100) + 'px';
		// load default content
		insertHtmlTextArea.value = FCKConfig.insertHtml_snippet;

		// resize around textarea
		// for IE this must be done before translating the dialog or the dialog will be to wide; also IE needs an approximate resize before autofitting or the dialog width will be to large
		if (FCKBrowserInfo.IsIE) dialog.Sizer.ResizeDialog(parseInt(FCKConfig.insertHtml_textareaWidth || 300), parseInt(FCKConfig.insertHtml_textareaHeight || 100) + 130);
		dialog.SetAutoSize(true);

		// recenter dialog
		setTimeout(function(){ // after a dummy delay, needed for webkit
			var topWindowSize = FCKTools.GetViewPaneSize(dialog.top.window);
			dialog.frameElement.style.left = Math.round((topWindowSize.Width - dialog.frameElement.offsetWidth) / 2) + 'px';
			dialog.frameElement.style.top = Math.round((topWindowSize.Height - dialog.frameElement.offsetHeight) / 2).toString() + 'px';;
		}, 0);

		// translate the dialog box texts
		editorWindow.FCKLanguageManager.TranslatePage(document);

		// activate the "OK" button
		dialog.SetOkButton(true);
	}

	// dialog's 'ok' button function to insert the Html
	function Ok()
	{
		if (insertHtmlTextArea.value)
		{
			editorInstance.InsertHtml(insertHtmlTextArea.value);
			editorWindow.FCKUndo.SaveUndoStep();

			return true; // makes the dialog to close
		}
	}

