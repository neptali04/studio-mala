// https://github.com/WordPress/WordPress/blob/ab3bca05c8103a8a73ae482d149d7821d44e4b30/wp-content/themes/twentyseventeen/assets/js/customize-preview.js
// https://richtabor.com/wordpress-customizer-context-aware-previews/
// https://wordpress.stackexchange.com/questions/300963/passing-data-from-customize-controls-js-to-the-customize-preview-js

wp.customize.bind( 'ready', function() {

    wp.customize.section( 'intro_section', function( section ) {
        section.expanded.bind( function( isExpanding ) {
            if ( isExpanding ) {
                wp.customize.previewer.send( 'customizer_showintro' );
            } else {
                wp.customize.previewer.send( 'customizer_hideintro' );
            }
        });
    });

    wp.customize.panel( 'mobile_panel', function( panel ) {
        panel.expanded.bind( function( isExpanding ) {
            if ( isExpanding ) {
                jQuery('.preview-mobile').click();
            } else {
                jQuery('.preview-desktop').click();
            }
        });
    });

    wp.customize.section( 'mobile_css', function( section ) {
        section.expanded.bind( function( isExpanding ) {
            if ( isExpanding ) {
                jQuery('.preview-mobile').click();
            } else {
                jQuery('.preview-desktop').click();
            }
        });
    });

});