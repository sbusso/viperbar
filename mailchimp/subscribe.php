<?php
	include_once('../../../../wp-config.php');
	include_once('../../../../wp-load.php');
	include_once('../../../../wp-includes/wp-db.php');
	require_once 'inc/MCAPI.class.php';
	
	$options = unserialize($wpdb->get_var("select option_value from wp_options where option_name = 'ViperBar_options'"));
	
	$api = new MCAPI($options['form_id_mailchimp']);
	
	$merge_vars = $api->listMergeVars($options['form_id_mailchimp_list']);
	
	if(!$api->errorCode) {
		$passable_merge_vars = array();
	
		if(sizeof($merge_vars) > 0) {
			foreach($merge_vars as $var) {
				$passable_merge_vars[$var['tag']] = $_REQUEST[$var['tag']];
			}
		}
	}
	
	//echo "<pre>".print_r($merge_vars,true)."</pre>";
	
	$retval = $api->listSubscribe($options['form_id_mailchimp_list'], $_REQUEST['EMAIL'], $passable_merge_vars);
	
	if ($api->errorCode){
		echo "Unable to load listSubscribe()!\n";
		echo "\tCode=".$api->errorCode."\n";
		echo "\tMsg=".$api->errorMessage."\n";
	} else {
		header("Location: ".$_POST['redirect']."?Viper_thanks=y");
		exit;
	}
?>