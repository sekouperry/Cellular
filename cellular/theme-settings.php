<?php

//

function cellular_form_system_theme_settings_alter(&$form, $form_state) {

    $grid_values = array(
            '100' => t('100%'),
            '90' => t('90%'),
            '80' => t('80%'),
            '75' => t('75%'),
            '70' => t('70%'),
            '60' => t('60%'),
            '50' => t('50%'),
            '40' => t('40%'),
            '33' => t('33%'),
            '30' => t('30%'),
            '25' => t('25%'),
            '20' => t('20%'),
            '10' => t('10%'),
        );
    $form['core_css'] = array(
        '#type' => 'checkbox',
        '#title' => t('Remove core CSS'),
        '#description' => t("Completely strip the core CSS files from theme."),
        '#default_value' => theme_get_setting('remove_core_css'),
    );    
    $form['copyright'] = array(
        '#type' => 'textfield',
        '#title' => t('Copyright'),
        '#description' => t("The name of the person or organization to display indicating copyright ownership. Leave field empty to disable display."),
        '#default_value' => theme_get_setting('copyright'),
    );
    
    $form['layout_settings'] = array(
        '#type' => 'fieldset',
        '#title' => t('Page Layout Settings'),
        '#collapsible' => TRUE,
        '#collapsed' => FALSE
            )
    ;
    $form['layout_settings']['breadcrumb_display'] = array(
        '#type' => 'checkbox',
        '#title' => t('Display Breadcrumbs'),
        '#description' => t("Show breadcrumb navigation on pages."),
        '#default_value' => theme_get_setting('breadcrumb_display'),
    );
    $form['layout_settings']['search_form_label'] = array(
        '#type' => 'checkbox',
        '#title' => t('Display "Search" Label'),
        '#description' => t("Show label for search form"),
        '#default_value' => theme_get_setting('search_form_label'),
    );
    $form['layout_settings']['full_menu'] = array(
        '#type' => 'checkbox',
        '#title' => t('Display Full Menu'),
        '#description' => t("Show the full menu tree in navigation instead of only top level links."),
        '#default_value' => theme_get_setting('full_menu'),
    );
    $form['layout_settings']['banner_class'] = array(
        '#type' => 'textfield',
        '#title' => t('Banner Region Class'),
        '#description' => t("The class name(s) to append to the banner content region."),
        '#default_value' => theme_get_setting('banner_class')
    );
    $form['layout_settings']['hilight_class'] = array(
        '#type' => 'textfield',
        '#title' => t('Hilight Region Class'),
        '#description' => t("The class name(s) to append to the hilighted content region below banner."),
        '#default_value' => theme_get_setting('hilight_class')
    );

    $form['layout_settings']['main_content'] = array(
        '#type' => 'fieldset',
        '#title' => t('Main Content Area'),
        '#description' => t("The width of the Main page content area as a percentage of available screen area- the width of sidebars is automatic based on the remaining area. "),
        '#collapsible' => TRUE,
        '#collapsed' => FALSE
    );
    $form['layout_settings']['main_content']['content_width_default'] = array(
        '#type' => 'select',
        '#title' => t('Main Content - Default Width'),
        '#description' => t("Default width of main content region, as a percentage."),
        '#default_value' => theme_get_setting('content_width_default'),
        '#options' => $grid_values,
    );
    $form['layout_settings']['main_content']['content_width_single_sidebar'] = array(
        '#type' => 'select',
        '#title' => t('Single Sidebar'),
        '#description' => t("Width of main content region with 1 sidebar displayed."),
        '#default_value' => theme_get_setting('content_width_single_sidebar'),
        '#options' => $grid_values,
    );

    $form['layout_settings']['main_content']['content_width_dual_sidebar'] = array(
        '#type' => 'select',
        '#title' => t('Dual Sidebars'),
        '#description' => t("Width of the main content region with 2 sidebars displayed."),
        '#default_value' => theme_get_setting('content_width_dual_sidebar'),
        '#options' => $grid_values,
    );

    $form['layout_settings']['footer'] = array(
        '#type' => 'fieldset',
        '#title' => t('Footer'),
        '#collapsible' => TRUE,
        '#collapsed' => FALSE
    );
    $form['layout_settings']['footer']['footer_left_width'] = array(
        '#type' => 'select',
        '#title' => t('Footer - Left Column Width'),
        '#description' => t("Width of Left Footer region."),
        '#default_value' => theme_get_setting('footer_left_width'), 
        '#options' => $grid_values,
    );
    $form['layout_settings']['footer']['footer_right_width'] = array(
        '#type' => 'select',
        '#title' => t('Footer - Right Column Width'),
        '#description' => t("Width of Right Footer region."),
        '#default_value' => theme_get_setting('footer_right_width'),
        '#options' => $grid_values,
    );
}