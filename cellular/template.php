<?php

/* * ************
  Cellular:: template.php

  Preprocess variables for use in templates

 * ************** */

function cellular_html_head_alter(&$head_elements) {
    /* Check head_elements 
      foreach ($head_elements as $key => $element) {
      echo $key . "\n";
      }
     */

    $remove = array(
        'system_meta_generator',
        'metatag_shortlink'
    );

    foreach ($remove as $key) {
        if (isset($head_elements[$key])) {
            unset($head_elements[$key]);
        }
    }
}

/* * ************
  css_alter()
 * ************** */

//Consolidate stylesheets into /css/cellular/drupal-core.css
function cellular_css_alter(&$css) {
    if (theme_get_setting('remove_core_css') != 0){
        $exclude = array(
            'modules/system/system.base.css' => FALSE,
            'modules/system/system.menus.css' => FALSE,
            'modules/system/system.messages.css' => FALSE,
            'modules/system/system.theme.css' => FALSE,
            'modules/block/block.css' => FALSE,
            'modules/comment/comment.css' => FALSE,
            'modules/field/theme/field.css' => FALSE,
            'modules/filter/filter.css' => FALSE,
            'modules/help/help.css' => FALSE,
            'modules/menu/menu.css' => FALSE,
            'modules/node/node.css' => FALSE,
            'modules/search/search.css' => FALSE,
            'modules/user/user.css' => FALSE
        );

        $css = array_diff_key($css, $exclude);
    }

    if (user_is_anonymous()) {
        uasort($css, 'cellular_sort_css_js');
        $i = 0;
        foreach ($css as $name => $style) {
            $css[$name]['weight'] = $i++;
            $css[$name]['group'] = CSS_DEFAULT;
            $css[$name]['every_page'] = FALSE;
        }
    }
}

/* * ************
  js_alter()
 * ************** */

function cellular_js_alter(&$javascript) {
    if (user_is_anonymous()) {
        // Move Google Analytics up so it can't break groups.
        foreach ($javascript as $name => $script) {
            if ($script['type'] == 'inline' &&
                    strstr($script['data'], '_gaq.push')) {
                $javascript[$name]['weight'] = -1000;
                $javascript[$name]['group'] = JS_LIBRARY;
            }
        }

        // Replace JQuery with the same version from Google's CDN.
        if (isset($javascript['misc/jquery.js'])) {
            $javascript['misc/jquery.js']['data'] =
                    '//ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js';
            $javascript['misc/jquery.js']['type'] = 'external';
            $javascript['misc/jquery.js']['weight'] = -100;
        }

        uasort($javascript, 'cellular_sort_css_js');

        $i = 0;
        foreach ($javascript as $name => $script) {
            $javascript[$name]['weight'] = $i++;
            $javascript[$name]['group'] = JS_DEFAULT;
            $javascript[$name]['every_page'] = FALSE;
        }
    }
}

/* * ************
  Custom sort order to optimize styles & scripts
 * ************** */

function cellular_sort_css_js($a, $b) {
    // Normalize the order of browser keys.
    if (isset($a['browsers'])) {
        ksort($a['browsers']);
        ksort($b['browsers']);
    }

    // Sort by integer weight values.  This allows explicit weights to break out
    // of a group, but the 0.001 automatically added to preserve insert order
    // cannot break a group.
    if ($return = ((int) $a['weight'] - (int) $b['weight'])) {
        return $return;
    }
    // Group common browsers together.
    elseif (isset($a['browsers']) && $return =
            strcmp(serialize($a['browsers']), serialize($b['browsers']))) {
        return $return;
    }
    // Group media types together.
    elseif (isset($a['media']) && $return = strcmp($a['media'], $b['media'])) {
        return $return;
    }
    // Finally, order by weight.  Multiply by 1000 to undo
    // the division in drupal_add_css.
    elseif ($return = ($a['weight'] * 1000 - $b['weight'] * 1000)) {
        return $return;
    } else {
        return 0;
    }
}

/* * ************
  preprocess_html()
 * ************** */

function cellular_preprocess_html(&$vars) {
// Get path to theme
    $vars['theme_path'] = drupal_get_path('theme', 'cellular');

// Add common meta elements to $head  
    $metas = array(
        // robots
        $robots = array(
    'attr_type' => 'name',
    'attr_val' => 'robots',
    'content' => 'index, follow',
    'weight' => 2
        ),
        // viewport
        $viewport = array(
    'attr_type' => 'name',
    'attr_val' => 'viewport',
    'content' => 'width=device-width, initial-scale=1',
    'weight' => 1
        ),
        $ie_render_engine = array(
    'attr_type' => 'http-equiv',
    'attr_val' => 'X-UA-Compatible',
    'content' => 'IE=edge,chrome=1',
    'weight' => -999
        ),
    );

    $base_weight = 0;
    foreach ($metas as $meta) {
        $tag = array(
            '#type' => 'html_tag',
            '#tag' => 'meta',
            '#attributes' => array(
                $meta['attr_type'] => $meta['attr_val'],
                'content' => $meta['content']
            ),
            '#weight' => $meta['weight']
        );
        drupal_add_html_head($tag, 'meta_' . $meta['attr_val']);
    }


// Add favicons
    $favicons = array(
        'apple-touch-icon' => array(
            'rel' => 'apple-touch-icon',
            'href' => $vars['theme_path'] . '/apple-touch-icon.png'
        ),
        'favicon' => array(
            'rel' => 'shortcut icon',
            'href' => $vars['theme_path'] . '/favicon.ico'
        )
    );

    foreach ($favicons as $favicon) {
        drupal_add_html_head_link(array(
            'rel' => $favicon['rel'],
            'href' => drupal_strip_dangerous_protocols($favicon['href'])
                )
        );
    }


    // Add conditional CSS for IE9 and below.
    drupal_add_css($vars['theme_path'] . '/css/cellular/ie.css', array(
        'group' => CSS_THEME, 'browsers' => array(
            'IE' => 'lte IE 9',
            '!IE' => FALSE
        ),
        'weight' => 999,
        'preprocess' => FALSE)
    );
}

/* * ************
  preprocess_node()
 * ************** */

function cellular_preprocess_node(&$vars) {
    /*
      $vars['hook'] = 'node';

      if ($vars['page']) {

      if (arg(0) == 'node' && is_numeric(arg(1))) {
      $nid = arg(1);
      $node = node_load($nid);

      return $node;
      }
      }

     */
}

/* * ************
  preprocess_page()
 * ************** */

function cellular_preprocess_page(&$vars) {
    $vars['hook'] = 'page';

    // Link site name to frontpage
    $vars['site_name'] = l($vars['site_name'], '<front>');

    // Set copyright if provided
    $copyright = theme_get_setting('copyright');
    if (!empty($copyright)) {
        $vars['copyright'] = "&copy; " . date("Y") . " " . $copyright;
    }


    // Render the full main menu tree; use css to hide sub-menus
    $main_menu_tree = menu_tree_all_data('main-menu');
    $vars['full_menu_tree'] = menu_tree_output($main_menu_tree);

    // Set vars for content layout divs  

    $vars['banner_class'] = theme_get_setting('banner_class');
    $vars['hilight_class'] = theme_get_setting('hilight_class');

    $vars['content_width_default'] = 'cell-' . theme_get_setting('content_width_default');
    $vars['content_width_single_sidebar'] = 'cell-' . theme_get_setting('content_width_single_sidebar');
    $vars['content_width_dual_sidebar'] = 'cell-' . theme_get_setting('content_width_dual_sidebar');

    $vars['single_sidebar_width'] = 'cell-' . round(100 - theme_get_setting('content_width_single_sidebar'), -1);
    $vars['dual_sidebar_width'] = 'cell-' . round((100 - theme_get_setting('content_width_dual_sidebar')) / 2, -1);

    $vars['footer_left_width'] = 'cell-' . theme_get_setting('footer_left_width');
    $vars['footer_right_width'] = 'cell-' . theme_get_setting('footer_right_width');
}

/* * ************
  preprocess_region()
 * ************** */

function cellular_preprocess_region(&$vars) {
    /*

     */
}

/* * ************
  preprocess_block()
 * ************** */

function cellular_preprocess_block(&$vars) {
    $vars['hook'] = 'block';

    $vars['title'] = !empty($vars['block']->subject) ? $vars['block']->subject : '';

    // Hide block titles in the header region.
    if ($vars['block']->region == 'header') {
        $vars['title_attributes_array']['class'][] = 'hidden';
    }
}

/* * ************
  preprocess_comment()
 * ************** */

function cellular_preprocess_comment(&$vars) {
    $vars['hook'] = 'comment';

    $vars['title_attributes_array']['class'][] = 'comment-title';

    $vars['content_attributes_array']['class'][] = 'comment-content';

    $vars['submitted'] = t('Submitted by !username on !datetime', array(
        '!username' => $vars['author'],
        '!datetime' => $vars['created'],
            ));

    if (isset($vars['content']['links'])) {
        $vars['links'] = $vars['content']['links'];
        unset($vars['content']['links']);
    }
}

/* * ************
  preprocess_fieldset()
 * **************

  function cellular_preprocess_fieldset(&$vars) {
  $element = $vars['element'];
  if (!empty($element['#id'])) {
  $vars['attributes']['id'] = $element['#id'];
  }
  $vars['attributes']['class'][] = 'fieldset';
  $vars['attributes'] = isset($element['#attributes']) ? $element['#attributes'] : array();

  $description = !empty($element['#description']) ? "<div class='description'>{$element['#description']}</div>" : '';
  $children = !empty($element['#children']) ? $element['#children'] : '';
  $value = !empty($element['#value']) ? $element['#value'] : '';
  $vars['content'] = $description . $children . $value;
  $vars['title'] = !empty($element['#title']) ? $element['#title'] : '';
  $vars['hook'] = 'fieldset';
  } */

/* * ************
  textarea()
 * **************

  function cellular_textarea(&$vars) {
  $element = $vars['element'];
  $element['#attributes']['name'] = $element['#name'];
  $element['#attributes']['id'] = $element['#id'];
  $element['#attributes']['cols'] = $element['#cols'];
  $element['#attributes']['rows'] = $element['#rows'];
  _form_set_class($element, array('form-textarea'));

  $wrapper_attributes = array(
  'class' => array('form-textarea-wrapper'),
  );

  // Add resizable behavior.
  if (!empty($element['#resizable'])) {
  $wrapper_attributes['class'][] = 'resizable';
  }

  $output = '<div' . drupal_attributes($wrapper_attributes) . '>';
  $output .= '<textarea' . drupal_attributes($element['#attributes']) . '>' . check_plain($element['#value']) . '</textarea>';
  $output .= '</div>';
  return $output;
  }
 */

/* * ************
  breadcrumb()
 * ************** */

function cellular_breadcrumb(&$vars) {
    $breadcrumb = $vars['breadcrumb'];
    $show_breadcrumb = theme_get_setting('breadcrumb_display');
    $breadcrumb_separator = '&#187;';

    if ($show_breadcrumb == true) {
        if (!empty($breadcrumb)) {
            $heading = '<h3 class="hidden">' . t('You are here') . '</h3>';
            $output = '';

            foreach ($breadcrumb as $key => $val) {
                if ($key == 0) {
                    $output .= '<li class="home">' . $val . '</li>';
                } else {
                    $output .= '<li>' . $breadcrumb_separator . $val . '</li>';
                }
            }
            return $heading . '<ol id="breadcrumb">' . $output . '</ol>';
        }
    }
    return '';
}