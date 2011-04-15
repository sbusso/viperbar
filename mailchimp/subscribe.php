<?php
	require_once('../../../../wp-config.php');
	require_once('../../../../wp-load.php');
	require_once('../../../../wp-includes/wp-db.php');
	require_once("inc/MCAPI.class.php");
	
	$options = unserialize($wpdb->get_var("select option_value from ".$wpdb->options." where option_name = 'ViperBar_options'"));
	
	$ViperBar_api = new MCAPI($options['form_id_mailchimp']);
	
	$merge_vars = $ViperBar_api->listMergeVars($options['form_id_mailchimp_list']);
	
	if(!$ViperBar_api->errorCode) {
		$passable_merge_vars = array();
	
		if(sizeof($merge_vars) > 0) {
			foreach($merge_vars as $var) {
				$passable_merge_vars[$var['tag']] = $_REQUEST[$var['tag']];
			}
		}
	}
	
	$retval = $ViperBar_api->listSubscribe($options['form_id_mailchimp_list'], $_REQUEST['EMAIL'], $passable_merge_vars);
	
	if ($ViperBar_api->errorCode){
		echo "Unable to load listSubscribe()!\n";
		echo "\tCode=".$ViperBar_api->errorCode."\n";
		echo "\tMsg=".$ViperBar_api->errorMessage."\n";
	} else {
		header("Location: ".$_POST['redirect']."?Viper_thanks=y");
		exit;
	}
?>