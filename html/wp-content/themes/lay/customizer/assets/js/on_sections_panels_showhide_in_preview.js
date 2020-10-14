// https://github.com/WordPress/WordPress/blob/ab3bca05c8103a8a73ae482d149d7821d44e4b30/wp-content/themes/twentyseventeen/assets/js/customize-preview.js
// https://richtabor.com/wordpress-customizer-context-aware-previews/
// https://wordpress.stackexchange.com/questions/300963/passing-data-from-customize-controls-js-to-the-customize-preview-js

// when I bind preview ready here, it is also called when a control causes a refresh of the website
// that is not the case if i bind to preview-ready in "on_sections_panels_showhide_in_controls.js"
wp.customize.bind( 'preview-ready', function() {

    // if intro section is already expanded, when an intro control was used, causing the website to refresh
    var intro_section = parent.wp.customize.section( 'intro_section' );
    if(typeof intro_section != "undefined"){
        if(intro_section.expanded()){
            window.laytheme.trigger('customizer_showintro');
        }
    }

    wp.customize.preview.bind( 'customizer_showintro', function() {
        window.laytheme.trigger('customizer_showintro');
    });
    wp.customize.preview.bind( 'customizer_hideintro', function() {
        window.laytheme.trigger('customizer_hideintro');
    });
});