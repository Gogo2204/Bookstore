<?php
ob_start();
$action = $_GET['action'];
require 'functions.php';
$crud = new Action();

switch($action){
	case 'login_admin':
		$login = $crud->login_admin();
		if($login)
			echo $login;
		break;
	case 'login_user':
		$login = $crud->login_user();
		if($login)
			echo $login;
		break;
	case 'logout_admin':
		$logout = $crud->logout_admin();
		if($logout)
			echo $logout;
		break;
	case 'logout_user':	
		$logout = $crud->logout_user();
		if($logout)
			echo $logout;
		break;
	case 'save_author':
		$save = $crud->save_author();
		if($save)
			echo $save;
		break;
	case 'delete_author':
		$delete = $crud->delete_author();
		if($delete)
			echo $delete;
		break;
	case "save_category":
		$save = $crud->save_category();
		if($save)
			echo $save;
		break;
	case "delete_category":	
		$delete = $crud->delete_category();
		if($delete)
			echo $delete;
		break;
	case "save_publisher":
		$save = $crud->save_publisher();
		if($save)
			echo $save;
		break;
	case "delete_publisher":
		$delete = $crud->delete_publisher();
		if($delete)
			echo $delete;
		break;
	case "save_book":
		$save = $crud->save_book();
		if($save)
			echo $save;
		break;
	case "delete_book":		
		$delete = $crud->delete_book();
		if($delete)
			echo $delete;
		break;
	case 'send_feedback':
		$send = $crud->send_feedback();
		if($send)
			echo $send;
		break;
	case 'signup':
		$save = $crud->signup();
		if($save)
			echo $save;
		break;
	case 'update_account':
		$save = $crud->update_account();
		if($save)
			echo $save;
		break;
	case "add_to_cart":
		$save = $crud->add_to_cart();
		if($save)
			echo $save;
		break;
	case "decrease_quantity_in_cart":
		$save = $crud->decrease_quantity_in_cart();
		if($save)
			echo $save;
		break;
	case "increase_quantity_in_cart":
		$save = $crud->increase_quantity_in_cart();
		if($save)
			echo $save;
		break;
	case "remove_from_cart":
		$delsete = $crud->remove_from_cart();
		if($delsete)
			echo $delsete;
		break;
	case "make_order":
		$save = $crud->make_order();
		if($save)
			echo $save;
		break;
	case "edit_customer":
		$save = $crud->edit_customer();
		if($save)
			echo $save;
		break;
} 
ob_end_flush();
?>
