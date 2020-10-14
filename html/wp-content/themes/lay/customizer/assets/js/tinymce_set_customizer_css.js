tinymce.PluginManager.add('setcustomizercss', function( editor ) {
	if(typeof customizerPassedData !== 'undefined') {
		var css = String()
		+'a{'
			+'color:'+customizerPassedData.linkcolor+';'
			+'border-bottom-style: solid;'
			+'border-bottom-width: '+customizerPassedData.linkUnderlineStrokeWidth+'px;'
		+'}'
		+'body{'
			+'background-color:rgb(235,235,235)!important;'
		+'}';

		editor.on('PreInit', function(e){
			editor.contentStyles.push(css);
		});
	}
} );