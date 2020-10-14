<?php

$section = 'links_in_texts_mouseover_options';
$panel = 'linksintexts_panel';

$wp_customize->add_section( $section, 
   array(
      'title' => 'Links in Texts Mouseover',
      'priority' => 10,
      'capability' => 'edit_theme_options',
      'panel' => $panel
   ) 
);

$wp_customize->add_setting( 'link_hover_color',
   array(
      'default' => '#00f',
      'type' => 'theme_mod',
      'capability' => 'edit_theme_options',
      'transport' => 'refresh',
   ) 
);      
$wp_customize->add_control( new WP_Customize_Color_Control(
   $wp_customize,
   'link_hover_color',
   array(
      'label' => 'Text Color',
      'section' => $section,
      'settings' => 'link_hover_color',
      'priority'   => 10
   )
) );

$wp_customize->add_setting( 'link_hover_opacity',
   array(
      'default' => '100',
      'type' => 'theme_mod',
      'capability' => 'edit_theme_options',
      'transport' => 'refresh',
   ) 
);      
$wp_customize->add_control( new WP_Customize_Control(
   $wp_customize,
   'link_hover_opacity',
   array(
      'label' => 'Opacity (%)',
      'section' => $section,
      'settings' => 'link_hover_opacity',
      'type' => 'number',
      'input_attrs' => array('min' => '0', 'max' => '100'),
      'priority' => 20,
   )
) );

$wp_customize->add_setting( 'link_hover_underline_strokewidth',
   array(
      'default' => 0,
      'type' => 'theme_mod',
      'capability' => 'edit_theme_options',
      'transport' => 'refresh',
   ) 
);      
$wp_customize->add_control( new WP_Customize_Control(
   $wp_customize,
   'link_hover_underline_strokewidth',
   array(
      'label' => 'Underline Strokewidth',
      'section' => $section,
      'settings' => 'link_hover_underline_strokewidth',
      'type'           => 'number',
      'priority' => 30,
   )
) );