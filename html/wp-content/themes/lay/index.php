<?php
get_header();
?>
<div id="intro-region"></div>
<?php
echo Lay_Layout::getLayoutInit();
?>
<div id="search-region"></div>
<?php
if(is_front_page()){
    echo '<noscript><a href="http://laytheme.com">Frontpage made with Lay Theme</a></noscript>';
}
// LayShortcodes::get_shortcode_contents();
get_footer();