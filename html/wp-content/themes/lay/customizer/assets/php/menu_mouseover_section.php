<?php

$section = 'navigation_mouseover_options';

$wp_customize->add_section( $section, 
   array(
      'title' => 'Menu Point Mouseover',
      'priority' => 35,
      'capability' => 'edit_theme_options',
      'panel' => 'navigation_panel'
   ) 
);

$wp_customize->add_setting( 'navmouseover_color',
   array(
      'default' => Customizer::$defaults['color'],
      'type' => 'theme_mod',
      'capability' => 'edit_theme_options',
      'transport' => 'refresh',
   ) 
);      
$wp_customize->add_control( new WP_Customize_Color_Control(
   $wp_customize,
   'navmouseover_color',
   array(
      'label' => 'Text Color',
      'section' => $section,
      'settings' => 'navmouseover_color',
      'priority'   => 40
   )
) );

$wp_customize->add_setting( 'navmouseover_opacity',
   array(
      'default' => '100',
      'type' => 'theme_mod',
      'capability' => 'edit_theme_options',
      'transport' => 'refresh',
   ) 
);      
$wp_customize->add_control( new WP_Customize_Control(
   $wp_customize,
   'navmouseover_opacity',
   array(
      'label' => 'Opacity (%)',
      'section' => $section,
      'settings' => 'navmouseover_opacity',
      'type'           => 'number',
      'input_attrs' => array('min' => '0', 'max' => '100'),
      'priority' => 41,
   )
) );

$wp_customize->add_setting( 'navmouseover_underline_strokewidth',
   array(
      'default' => Customizer::$defaults['underline_width'],
      'type' => 'theme_mod',
      'capability' => 'edit_theme_options',
      'transport' => 'refresh',
   ) 
);      
$wp_customize->add_control( new WP_Customize_Control(
   $wp_customize,
   'navmouseover_underline_strokewidth',
   array(
      'label' => 'Underline Strokewidth',
      'section' => $section,
      'settings' => 'navmouseover_underline_strokewidth',
      'type'           => 'number',
      'input_attrs' => array('min' => '1'),
      'priority' => 80,
   )
) );

?>