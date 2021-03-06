<?php
/**
 * Elgg widgets layout
 *
 * @uses $vars['content']          Optional display box at the top of layout
 * @uses $vars['num_columns']      Number of widget columns for this layout (3)
 * @uses $vars['show_add_widgets'] Display the add widgets button and panel (true)
 * @uses $vars['exact_match']      Widgets must match the current context (false)
 * @uses $vars['show_access']      Show the access control (true)
 */

//$num_columns = elgg_extract('num_columns', $vars, 3);
$show_add_widgets = elgg_extract('show_add_widgets', $vars, true);
$exact_match = elgg_extract('exact_match', $vars, false);//default FALSE
$show_access = elgg_extract('show_access', $vars, true);

//var_dump($vars);

//before elgg_get_page_owner_entity();
$owner = elgg_get_page_owner_entity();

$context = elgg_get_context();
//trigger plugin hook to get values
$available_widgets_context = elgg_trigger_plugin_hook("available_widgets_context", "widget_manager", array(), $context);

$widget_types = elgg_get_widget_types($available_widgets_context);

elgg_push_context('widgets');

//default $widgets = elgg_get_widgets($owner->guid, $context);
$widgets = elgg_get_widgets($owner->guid, $context);

if (elgg_can_edit_widget_layout($context)){
	if ($show_add_widgets){
		echo elgg_view('page/layouts/widgets/add_button');
	}
	
	//echo 'Current guid: '.$owner;
	//echo '__Current context: '.$context;

	$params = array(
		'widgets' => $widgets,
		'context' => $context,
		'exact_match' => $exact_match
	);
	//['widgets'][8][0]
	//$params['widgets'][9][0]->widget_manager_show_edit = false;
	//var_dump($params['widgets'][9][0]);
	echo elgg_view('page/layouts/widgets/add_panel', $params);
}
	
$column_info = elgg_get_plugin_setting('index_layout', 'homepage_cms');
$columns = explode("\n", $column_info);

$column_index = 0;
foreach ($columns as $row) {
  $rows = explode("|", $row);
  
  foreach ($rows as $width) {
    $column_index++;
    
		if (isset($widgets[$column_index])) {
				$column_widgets = $widgets[$column_index];
			} else {
				$column_widgets = array();
			}
		
		echo "<div class=\"elgg-widgets\" id=\"elgg-widget-col-$column_index\">";
		if(elgg_is_admin_logged_in()){
		  echo "<div class=\"homepage-cms-column-header\">----- Column $column_index -----</div>";
		}
		
			if (sizeof($column_widgets) > 0) {
				foreach ($column_widgets as $widget) {
					if (array_key_exists($widget->handler, $widget_types)) {
						echo elgg_view_entity($widget, array('show_access' => $show_access));
					}
				}
			}
		elseif(!elgg_is_admin_logged_in()){
		  echo "&nbsp;";
		}

		echo "</div>";
    
  }
  
  echo '<div style="clear: both; height: 0px;"></div>';
}

/*
	for ($column_index = 1; $column_index <= $num_columns; $column_index++) {
		if (isset($widgets[$column_index])) {
			$column_widgets = $widgets[$column_index];
		} else {
			$column_widgets = array();
		}
	
		echo "<div class=\"elgg-widgets\" id=\"elgg-widget-col-$column_index\">";
    if(elgg_is_admin_logged_in()){
      echo "<div style=\"background-color: yellow; border: 1px solid brown;\">$column_index</div>";
    }
		if (sizeof($column_widgets) > 0) {
			foreach ($column_widgets as $widget) {
				if (array_key_exists($widget->handler, $widget_types)) {
					echo elgg_view_entity($widget, array('show_access' => $show_access));
				}
			}
		}
		echo '</div>';
	}
*/

elgg_pop_context();

echo elgg_view('graphics/ajax_loader', array('id' => 'elgg-widget-loader'));
