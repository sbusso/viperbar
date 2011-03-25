<?php
	include_once('../../../wp-config.php');
	include_once('../../../wp-load.php');
	include_once('../../../wp-includes/wp-db.php');

	$stats['submits'] = $wpdb->get_var("select option_value from wp_options where option_name = 'ViperBar_stats_submits'");
	$stats['impressions'] = $wpdb->get_var("select option_value from wp_options where option_name = 'ViperBar_stats_impressions'");
	
	if($stats['impressions'] > 0) {
		$stats['conversion'] = round($stats['submits'] / $stats['impressions'] * 100,2);
	} else {
		$stats['conversion'] = 0;
	}
	
	if($_GET['increment'] == "true") {
		if($_GET['type'] == "impression") {
			if(!$_COOKIE['ViperBar_seen']) {
				setcookie("ViperBar_seen","true");
				$stats['impressions'] = $stats['impressions'] + 1;
				
				$wpdb->query("update wp_options set option_value = '".$stats['impressions']."' where option_name = 'ViperBar_stats_impressions'");
			}
		}
		
		if($_GET['type'] == "submit") {
			$stats['submits'] = $stats['submits'] + 1;
			
			$wpdb->query("update wp_options set option_value = '".$stats['submits']."' where option_name = 'ViperBar_stats_submits'");
		}
	}
	
	if($_GET['reset'] == "true") {
		$stats['submits'] = $stats['submits'] + 1;
		
		$wpdb->query("update wp_options set option_value = '0' where option_name = 'ViperBar_stats_submits'");
		$wpdb->query("update wp_options set option_value = '0' where option_name = 'ViperBar_stats_impressions'");
	}
	
	echo json_encode($stats);
?>