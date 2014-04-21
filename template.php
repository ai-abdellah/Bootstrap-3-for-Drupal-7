<?php

function cbcare_preprocess_page(&$variables) {
 switch (current_path()) {
  case 'user/login':
   $variables['title'] = 'Accesso';
   unset($variables['tabs']);
   break;
  case 'user/password':
   $variables['title'] = 'Recupera la password';
   unset($variables['tabs']);
   break; 
  case 'contact':
   $variables['title'] = 'Lascia un messaggio';
   break;
  }
}

function cbcare_theme() {
 $items = array();
 $items['user_login'] = array(
  'render element' => 'form',
  'path' => drupal_get_path('theme', 'cbcare') . '/templates',
  'template' => 'user-login',
  'preprocess functions' => array(
     'cbcare_preprocess_user_login'
  ),
 );
 $items['user_register_form'] = array(
  'render element' => 'form',
  'path' => drupal_get_path('theme', 'cbcare') . '/templates',
  'template' => 'user-register-form',
  'preprocess functions' => array(
   'cbcare_preprocess_user_register_form'
  ),
 );
 $items['user_pass'] = array(
  'render element' => 'form',
  'path' => drupal_get_path('theme', 'cbcare') . '/templates',
  'template' => 'user-pass',
  'preprocess functions' => array(
   'cbcare_preprocess_user_pass'
  ),
 );
 return $items;
}

function cbcare_preprocess_user_login(&$variables) {
 $variables['form']['#attributes']['class'][] = 'form-horizontal';
}
function cbcare_preprocess_user_register_form(&$variables) { }
function cbcare_preprocess_user_pass(&$variables) { }

function cbcare_button($variables) {
  $element = $variables['element'];
  $element['#attributes']['type'] = 'submit';
  element_set_attributes($element, array('id', 'name', 'value'));
 
  $element['#attributes']['class'][] = 'form-' . $element['#button_type'];
  $element['#attributes']['class'][] = 'btn';

  if($element['#button_type'] == 'submit'){
    $element['#attributes']['class'][] = 'btn-primary';
    $element['#attributes']['class'][] = 'btn-lg';
  }
  if (!empty($element['#attributes']['disabled'])) {
    $element['#attributes']['class'][] = 'form-button-disabled';
  }
 
  return '<button' . drupal_attributes($element['#attributes']) . '>'.$element['#attributes']['value'].'</button>';
}

function cbcare_textfield($variables) {
 $element = $variables['element'];
 $output = '';
 if($element['#name'] == 'name' && current_path() == 'user/login')
  $output = '<div class="input-group"><span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>';
 
 $element['#attributes']['type'] = 'text';

 if(isset($variables['element']['#description']))
  $element['#attributes']['placeholder'] = $variables['element']['#description'];
 
 element_set_attributes($element, array('id', 'name', 'value', 'size', 'maxlength'));

 _form_set_class($element, array('form-text', 'form-control', 'input-lg-3'));
 
 $extra = '';
 if ($element['#autocomplete_path'] && drupal_valid_path($element['#autocomplete_path'])) {
  drupal_add_library('system', 'drupal.autocomplete');
  $element['#attributes']['class'][] = 'form-autocomplete';

  $attributes = array();
  $attributes['type'] = 'hidden';
  $attributes['id'] = $element['#attributes']['id'] . '-autocomplete';
  $attributes['value'] = url($element['#autocomplete_path'], array('absolute' => TRUE));
  $attributes['disabled'] = 'disabled';
  $attributes['class'][] = 'autocomplete';
  $extra = '<input' . drupal_attributes($attributes) . ' />';
 }
 
 $output .= '<input' . drupal_attributes($element['#attributes']) . ' />';
 
 if($element['#name'] == 'name'  && current_path() == 'user/login')
  $output .= '</div>'; 

 return $output . $extra;
}

function cbcare_password($variables) {
 $element = $variables['element'];
 $element['#attributes']['type'] = 'password';
 element_set_attributes($element, array('id', 'name', 'size', 'maxlength'));
 _form_set_class($element, array('form-text', 'form-control'));
 
 $output = '';
 // login form adding glyphicon.
 if($element['#name'] == 'pass')
  $output = '<div class="input-group"><span class="input-group-addon"><span class="glyphicon glyphicon-eye-close"></span></span>';
 
 $output .= '<input' . drupal_attributes($element['#attributes']) . ' />';

 if($element['#name'] == 'pass') 
  $output .= '</div>';

 return $output;
}

function cbcare_textarea($variables) {
 $element = $variables['element'];
 element_set_attributes($element, array('id', 'name', 'cols', 'rows'));
 _form_set_class($element, array('form-textarea'));

 $wrapper_attributes = array(
   'class' => array('form-textarea-wrapper'),
 );

 if (!empty($element['#resizable'])) {
  drupal_add_library('system', 'drupal.textarea');
  $wrapper_attributes['class'][] = 'resizable';
 }

 $element['#attributes']['class'][] = 'form-control';

 $output = '<div' . drupal_attributes($wrapper_attributes) . '>';
 $output .= '<textarea' . drupal_attributes($element['#attributes']) . '>' . check_plain($element['#value']) . '</textarea>';
 $output .= '</div>';

 return $output;
}

function cbcare_form_element($variables) {
 $element = &$variables['element'];
 
 $element += array(
  '#title_display' => 'before',
 );
 
 if (isset($element['#markup']) && !empty($element['#id']))
  $attributes['id'] = $element['#id'];

 $attributes['class'] = array('form-item');
 if (!empty($element['#type']))
  $attributes['class'][] = 'form-type-' . strtr($element['#type'], '_', '-');

 if (!empty($element['#name']))
  $attributes['class'][] = 'form-item-' . strtr($element['#name'], array(' ' => '-', '_' => '-', '[' => '-', ']' => ''));

 if (!empty($element['#attributes']['disabled']))
  $attributes['class'][] = 'form-disabled';

 if (isset($element['#parents']) && form_get_error($element))
  $attributes['class'][] = 'has-error';
 
 if($element['#type'] != 'radio')
  $attributes['class'][] = 'form-group';
 
 $output = '<div' . drupal_attributes($attributes) . '>' . "\n";
 
 if (!isset($element['#title']))
  $element['#title_display'] = 'none';

 $prefix = isset($element['#field_prefix']) ? '<span class="field-prefix">' . $element['#field_prefix'] . '</span> ' : '';
 $suffix = isset($element['#field_suffix']) ? ' <span class="field-suffix">' . $element['#field_suffix'] . '</span>' : '';
 
 switch ($element['#title_display']) {
  case 'before':
  case 'invisible':
   $output .= ' ' . theme('form_element_label', $variables);
   $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
   break;
  case 'after':
   $output .= ' ' . $prefix . $element['#children'] . $suffix;
   $output .= ' ' . theme('form_element_label', $variables) . "\n";
   break;
  case 'none':
  case 'attribute':
   $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
   break;
  }

  if (!empty($element['#description']))
   $output .= '<div class="description">' . $element['#description'] . "</div>\n";
 
  $output .= "</div>\n";
 
  return $output;
}

function cbcare_form_alter(&$form, &$form_state, $form_id) {
 if($form_id == 'user_login') {
  $form['name']['#description'] = '';
  $form['pass']['#description'] = '';
 }
}

function cbcare_html_head_alter(&$head_elements) {
  unset($head_elements['system_meta_generator']);
}

function cbcare_preprocess_image(&$variables) {
 
 if (isset($variables['style_name'])) {
  if($variables['style_name'] == 'marchio')
   $variables['attributes']['class'][] = 'img-thumbnail';
  elseif ($variables['style_name'] == 'slideshow')
   $variables['attributes']['class'][] = 'img-responsive';
 }
}

function cbcare_js_alter(&$javascript) {
  $jquery_path = drupal_get_path('theme','cbcare') . '/js/jquery-1.11.0.min.js';

  $javascript[$jquery_path] = $javascript['misc/jquery.js'];
  $javascript[$jquery_path]['version'] = '1.11.0';
  $javascript[$jquery_path]['data'] = $jquery_path;

  unset($javascript['misc/jquery.js']);
}

function cbcare_css_alter(&$css) {
 $exclude = array(
  'modules/field/theme/field.css' => FALSE,
  'modules/node/node.css' => FALSE,
  'modules/system/system.base.css' => FALSE,
  'modules/system/system.menus.css' => FALSE,
  'modules/system/system.messages.css' => FALSE,
  'modules/system/system.theme.css' => FALSE,
  'modules/user/user.css' => FALSE,
 );

 $css = array_diff_key($css, $exclude);
}

function cbcare_menu_tree__main_menu(&$variables) {
  return '<ul class="nav navbar-nav">' . $variables['tree'] . '</ul>';
}

function cbcare_menu_link(array $variables) {
  $element = $variables['element'];
  $sub_menu = '';

  if ($element['#below']) {
   unset($element['#below']['#theme_wrappers']);
   $sub_menu = '<ul class="dropdown-menu">' . drupal_render($element['#below']) . '</ul>';
   $element['#attributes']['class'][] = "dropdown";
   $element['#title'] .= ' <span class="caret"></span>';
   $element['#attributes']['class'][] = 'dropdown';
   $element['#localized_options']['html'] = TRUE;

   $element['#localized_options']['attributes']['data-target'] = '#';
   $element['#localized_options']['attributes']['class'][] = 'dropdown-toggle';
   $element['#localized_options']['attributes']['data-toggle'] = 'dropdown';
  }

  $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}

function cbcare_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];
  if (!empty($breadcrumb)) {
   $breadcrumb[] = drupal_get_title();
   $output = '<ol class="breadcrumb">';

   foreach($breadcrumb as $crumb) {
    if(end($breadcrumb) !== $crumb)
     $output .= "<li>$crumb</li>";
    else
     $output .= "<li class=\"active\">$crumb</li>";
   }

   $output .= '</ol>';
   return $output;
  }
}

function cbcare_status_messages($variables) {
 $display = $variables['display'];
 $output = '';

 $status_heading = array(
  'status' => t('Status message'),
  'error' => t('Error message'),
  'warning' => t('Warning message'),
 );

 foreach (drupal_get_messages($display) as $type => $messages) {
  $conv_type = convert_status($type);
  $output .= "<div class=\"alert $conv_type alert-dismissable\">\n";
  $output .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
  if (count($messages) > 1) {
   $output .= " <ul>\n";
   foreach ($messages as $message) {
    $output .= '  <li>' . $message . "</li>\n";
   }
   $output .= " </ul>\n";
  } else {
   $output .= $messages[0];
  }
   $output .= "</div>\n";
 }
 return $output;
}

function convert_status($type) {
 switch($type) {
  case 'status':
   return 'alert-success';
   break;
  case 'warning':
   return 'alert-warning';
   break;
  case 'error':
   return 'alert-danger';
   break;
  default:
   return 'alert-info';
   break;
 }
}

function cbcare_menu_local_tasks(&$variables) {
 $output = '';

 if (!empty($variables['primary'])) {
  $variables['primary']['#prefix'] = '<h2 class="sr-only">' . t('Primary tabs') . '</h2>';
  $variables['primary']['#prefix'] .= '<ul class="nav nav-tabs primary">';
  $variables['primary']['#suffix'] = '</ul>';
  $output .= drupal_render($variables['primary']);
 }
 if (!empty($variables['secondary'])) {
  $variables['secondary']['#prefix'] = '<h2 class="sr-only">' . t('Secondary tabs') . '</h2>';
  $variables['secondary']['#prefix'] .= '<ul class="nav nav-tabs secondary">';
  $variables['secondary']['#suffix'] = '</ul>';
  $output .= drupal_render($variables['secondary']);
 }

 return $output;
}
