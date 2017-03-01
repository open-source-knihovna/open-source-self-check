<?php
/* 
	checkout processing page
*/
session_start();
require __DIR__ . '/../vendor/autoload.php';
include_once('../config.php');

//set some variables
$response_message='';
$action_message='';

if (!empty($_SESSION['patron_barcode'])){
	$_SESSION['checkouts']=$patron_info['fixed']['ChargedCount']; //checkouts
	$_SESSION['checkouts_this_session']=$_SESSION['checkouts_this_session']+1;
	
	echo '
	<tr>
	<td class="cko_item" style="color:#666;width:25px" id="item_left_'.$item_barcode.'_'.$_SESSION['checkouts_this_session'].'">'.$_SESSION['checkouts_this_session'].'. </td>
	<td class="cko_item" style="width:80%;">'.$title.'</td>
	<td class="cko_item" id="item_right_'.$item_barcode.'_'.$_SESSION['checkouts_this_session'].'">'.$due_date.'</td>
	</tr>
	<script type="text/javascript">';
	//the javascript variables make up the elements of the receipt
	echo '
	var title="<tr><td>Title: '.str_replace('"','\"',$title).'</td></tr>";
	var call_number="<tr><td>Call Number: '.str_replace('"','\"',$call_number).'</td></tr>";
	var due_date="<tr><td>Date Due: '.$due_date.'</td></tr>";
	var item_barcode="<tr><td>Item ID: '.$item_barcode.'</td></tr>";
	
	var item='.implode('+',$receipt_item_list_elements).'+"<tr><td>&nbsp;</td></tr>";
	
	$(document).ready(function(){
		$("#item_list .loading,#pre_cko_buttons").hide();
		$("#cko_buttons").show();
		$("#cko_count").html("'.$_SESSION['checkouts'].'");
		$("#print_item_list table tbody").append(item);
		$("#item_list").attr({ scrollTop: $("#item_list").attr("scrollHeight") });
	';
	
	//Action Balloon
	if (!empty($action_balloon[$item_type]) && $action_balloon[$item_type]['trigger']=='item type'){	
		 $action_message=$action_balloon[$item_type]['action_message'];
	} else if (!empty($action_balloon[$permanent_location]) && $action_balloon[$permanent_location]['trigger']=='permanent location'){	
		 $action_message=$action_balloon[$permanent_location]['action_message'];
	}
		
	if (!empty($action_message) && empty($_SESSION['action_'.$item_type])){
		if (empty($_SESSION['action_balloon_count'])){ 	/*determine which side of the screen to show the action balloon (they'd overlap if consecutive items were to trigger balloons on the same side) */
			echo "$('.qtip').remove();"; //get rid on any existing balloons
			$action_balloon_position='target: "leftMiddle", tooltip: "rightMiddle"'; //if no previous action balloons put the balloon on the left
			$action_balloon_corner='rightMiddle';
			$_SESSION['action_balloon_count']=1; //set this variable so we know next time if there's an existing balloon
			$attach_to_element_id='item_left_'.$item_barcode.'_'.$_SESSION['checkouts_this_session'];
		} else {
			$action_balloon_position='target: "rightMiddle", tooltip: "leftMiddle"';
			$action_balloon_corner='leftMiddle';
			$_SESSION['action_balloon_count']=''; //reset balloon count
			$attach_to_element_id='item_right_'.$item_barcode.'_'.$_SESSION['checkouts_this_session'];
		}
	echo '
		$("#'.$attach_to_element_id.'").qtip( {
			content: "<p style=\'text-align:center;font-weight:bold;color:#333\'>'.str_replace('"','\"',$action_message).'</p>",
			show: { ready: true,effect:{type:"fade",length:0}},
			hide: { when: "never"},
			position: {corner: {'.$action_balloon_position.' }},
			style: { width:"140px",tip: {corner:"'.$action_balloon_corner.'",color:"'.$action_balloon_bg_color.'" }, border: { width: 1,color:"'.$action_balloon_bg_color.'" ,radius:5},background: "'.$action_balloon_bg_color.'"}
	 	});
		$.dbj_sound.play("'.$note_sound.'");';
		$_SESSION['action_'.$item_type]=1;
	} 
	//End Action Balloon
	 echo '
	});
	</script>';
	
	exit;
	
}

?>
