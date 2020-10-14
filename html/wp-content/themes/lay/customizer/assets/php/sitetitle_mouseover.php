<?php

$section = 'sitetitle_mouseover_options';

$wp_customize->add_section( $section, 
   array(
      'title' => 'Site Title Mouseover',
      'priority' => 10,
      'capability' => 'edit_theme_options',
      'panel' => 'sitetitle_panel'
   ) 
);

$wp_customize->add_setting( 'stmouseover_color',
   array(
      'default' => Customizer::$defaults['color'],
      'type' => 'theme_mod',
      'capability' => 'edit_theme_options',
      'transport' => 'refresh',
   ) 
);      
$wp_customize->add_control( new WP_Customize_Color_Control(
   $wp_customize,
   'stmouseover_color',
   array(
      'label' => 'Text Color',
      'section' => $section,
      'settings' => 'stmouseover_color',
      'priority'   => 40
   )
) );

$wp_customize->add_setting( 'stmouseover_opacity',
   array(
      'default' => '100',
      'type' => 'theme_mod',
      'capability' => 'edit_theme_options',
      'transport' => 'refresh',
   ) 
);      
$wp_customize->add_control( new WP_Customize_Control(
   $wp_customize,
   'stmouseover_opacity',
   array(
      'label' => 'Opacity (%)',
      'section' => $section,
      'settings' => 'stmouseover_opacity',
      'type' => 'number',
      'input_attrs' => array('min' => '0', 'max' => '100'),
      'priority' => 41,
   )
) );

$wp_customize->add_setting( 'stmouseover_underline_strokewidth',
   array(
      'default' => Customizer::$defaults['underline_width'],
      'type' => 'theme_mod',
      'capability' => 'edit_theme_options',
      'transport' => 'refresh',
   ) 
);      
$wp_customize->add_control( new WP_Customize_Control(
   $wp_customize,
   'stmouseover_underline_strokewidth',
   array(
      'label' => 'Underline Strokewidth',
      'section' => $section,
      'settings' => 'stmouseover_underline_strokewidth',
      'type'           => 'number',
      'input_attrs' => array('min' => '1'),
      'priority' => 80,
   )
) );