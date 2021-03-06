<?php 
/***************************************************************************
 *					adr_character_equipment.php
 *				------------------------
 *	begin 			: 28/03/2004
 *	copyright		: Malicious Rabbit / Dr DLP
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *
 ***************************************************************************/

define('IN_PHPBB', true); 
define('IN_ADR_SHOPS', true); 
define('IN_ADR_CHARACTER', true); 
define('IN_ADR_BATTLE', true); 
define('IN_ADR_EQUIPMENT', true); 
$phpbb_root_path = './'; 
include($phpbb_root_path . 'extension.inc'); 
include($phpbb_root_path . 'common.'.$phpEx);

$loc = 'character';
$sub_loc = 'adr_character_equipment';

//
// Start session management
$userdata = session_pagestart($user_ip, PAGE_ADR); 
init_userprefs($userdata); 
// End session management
//

include($phpbb_root_path . 'adr/includes/adr_global.'.$phpEx);

$user_id = $userdata['user_id'];

// Sorry , only logged users ...
if ( !$userdata['session_logged_in'] )
{
	$redirect = "adr_character_inventory.$phpEx";
	$redirect .= ( isset($user_id) ) ? '&user_id=' . $user_id : '';
	header('Location: ' . append_sid("login.$phpEx?redirect=$redirect", true));
}

include($phpbb_root_path . 'includes/page_header.'.$phpEx);

if ( (!( isset($_POST[POST_USERS_URL]) || isset($_GET[POST_USERS_URL]))) || ( empty($_POST[POST_USERS_URL]) && empty($_GET[POST_USERS_URL])))
{ 
	$view_userdata = $userdata; 
} 
else 
{ 
	$view_userdata = get_userdata(intval($_GET[POST_USERS_URL])); 
} 
$searchid = $view_userdata['user_id'];


// Get the general config
$adr_general = adr_get_general_config();

adr_enable_check();
adr_ban_check($user_id);
adr_character_created_check($user_id);

// See if the user has ever created a character or no
$char = adr_get_user_infos($searchid);

// Deny access if user is imprisioned
if($userdata['user_cell_time']){
	adr_previous(Adr_shops_no_thief, adr_cell, '');}

$equip_armor = $char['equip_armor'];
$equip_buckler = $char['equip_buckler'];
$equip_helm = $char['equip_helm'];
$equip_gloves = $char['equip_gloves'];
$equip_amulet = $char['equip_amulet'];
$equip_ring = $char['equip_ring'];

adr_template_file('adr_equipment_body.tpl');

$submit = isset($_POST['equip']);

if ( $submit && ( $user_id == $searchid ) )
{
	$armor = intval($_POST['item_armor']);
	$buckler = intval($_POST['item_buckler']);
	$helm = intval($_POST['item_helm']);
	$gloves = intval($_POST['item_gloves']);
	$amulet = intval($_POST['item_amulet']);
	$ring = intval($_POST['item_ring']);

	$sql = " UPDATE " . ADR_CHARACTERS_TABLE . "
		SET equip_armor = $armor,
			equip_buckler = $buckler,
			equip_helm = $helm,
			equip_gloves = $gloves,
			equip_amulet = $amulet,
			equip_ring = $ring
		WHERE character_id = $user_id ";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update equipment', '', __LINE__, __FILE__, $sql);
	}

	adr_previous( Adr_equip_done , adr_character_equipment , '' );
}

if ( $user_id == $searchid )
{
	$template->assign_block_vars('owner',array());

	### START restriction checks ###
	$item_sql = adr_make_restrict_sql($char);
	### END restriction checks ###

	// First select the available items
	$sql = " SELECT * FROM " . ADR_SHOPS_ITEMS_TABLE . "
		WHERE item_in_shop = 0 
		AND item_duration > 0
		AND item_in_warehouse = 0
		$item_sql
		AND item_owner_id = $user_id ";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query battle list', '', __LINE__, __FILE__, $sql);
	}
	$items = $db->sql_fetchrowset($result);

	// Prepare the items list
	$armor_list = '<select name="item_armor">';
	$armor_list .= '<option value = "0" >' . $lang['Adr_battle_no_armor'] . '</option>';
	$buckler_list = '<select name="item_buckler">';
	$buckler_list .= '<option value = "0" >' . $lang['Adr_battle_no_buckler'] . '</option>';
	$helm_list = '<select name="item_helm">';
	$helm_list .= '<option value = "0" >' . $lang['Adr_battle_no_helm'] . '</option>';
	$gloves_list = '<select name="item_gloves">';
	$gloves_list .= '<option value = "0" >' . $lang['Adr_battle_no_gloves'] . '</option>';
	$amulet_list = '<select name="item_amulet">';
	$amulet_list .= '<option value = "0" >' . $lang['Adr_battle_no_amulet'] . '</option>';
	$ring_list = '<select name="item_ring">';
	$ring_list .= '<option value = "0" >' . $lang['Adr_battle_no_ring'] . '</option>';

	for ( $i = 0 ; $i < count($items) ; $i ++ )
	{
		$item_power = $items[$i]['item_power'] + $items[$i]['item_add_power'];

		if ( $items[$i]['item_type_use'] == 7 )
		{
			$armor_selected = ( $equip_armor == $items[$i]['item_id'] ) ? 'selected' : '';
			$armor_list .= '<option value = "'.$items[$i]['item_id'].'" '.$armor_selected.' >' . adr_get_lang($items[$i]['item_name']) . ' ( ' . $lang['Adr_items_power'] . ' : ' . $item_power . ' - ' . $lang['Adr_items_duration'] . ' : ' . $items[$i]['item_duration'] . ' )'.'</option>';
		}
		if ( $items[$i]['item_type_use'] == 8 )
		{
			$buckler_selected = ( $equip_buckler == $items[$i]['item_id'] ) ? 'selected' : '';
			$buckler_list .= '<option value = "'.$items[$i]['item_id'].'" '.$buckler_selected.'>' . adr_get_lang($items[$i]['item_name']) . ' ( ' . $lang['Adr_items_power'] . ' : ' . $item_power . ' - ' . $lang['Adr_items_duration'] . ' : ' . $items[$i]['item_duration'] . ' )'.'</option>';
		}
		if ( $items[$i]['item_type_use'] == 9 )
		{
			$helm_selected = ( $equip_helm == $items[$i]['item_id'] ) ? 'selected' : '';
			$helm_list .= '<option value = "'.$items[$i]['item_id'].'" '.$helm_selected.'>' . adr_get_lang($items[$i]['item_name']) . ' ( ' . $lang['Adr_items_power'] . ' : ' . $item_power . ' - ' . $lang['Adr_items_duration'] . ' : ' . $items[$i]['item_duration'] . ' )'.'</option>';
		}
		if ( $items[$i]['item_type_use'] == 10 )
		{
			$gloves_selected = ( $equip_gloves == $items[$i]['item_id'] ) ? 'selected' : '';
			$gloves_list .= '<option value = "'.$items[$i]['item_id'].'" '.$gloves_selected.'>' . adr_get_lang($items[$i]['item_name']) . ' ( ' . $lang['Adr_items_power'] . ' : ' . $item_power . ' - ' . $lang['Adr_items_duration'] . ' : ' . $items[$i]['item_duration'] . ' )'.'</option>';
		}
		if ( $items[$i]['item_type_use'] == 13 )
		{
			$amulet_selected = ( $equip_amulet == $items[$i]['item_id'] ) ? 'selected' : '';
			$amulet_list .= '<option value = "'.$items[$i]['item_id'].'" '.$amulet_selected.'>' . adr_get_lang($items[$i]['item_name']) . ' ( ' . $lang['Adr_items_power'] . ' : ' . $item_power . ' - ' . $lang['Adr_items_duration'] . ' : ' . $items[$i]['item_duration'] . ' )'.'</option>';
		}
		if ( $items[$i]['item_type_use'] == 14 )
		{
			$ring_selected = ( $equip_ring == $items[$i]['item_id'] ) ? 'selected' : '';
			$ring_list .= '<option value = "'.$items[$i]['item_id'].'" '.$ring_selected.'>' . adr_get_lang($items[$i]['item_name']) . ' ( ' . $lang['Adr_items_power'] . ' : ' . $item_power . ' - ' . $lang['Adr_items_duration'] . ' : ' . $items[$i]['item_duration'] . ' )'.'</option>';
		}
	}

	$armor_list .= '</select>';
	$buckler_list .= '</select>';
	$helm_list .= '</select>';
	$gloves_list .= '</select>';
	$amulet_list .= '</select>';
	$ring_list .= '</select>';

	$equip_title = $lang['Adr_equip_title'];
	$equip_larmor = $lang['Adr_battle_select_armor'];
	$equip_lbuckler = $lang['Adr_battle_select_buckler'];
	$equip_lhelm = $lang['Adr_battle_select_helm'];
	$equip_lgloves = $lang['Adr_battle_select_gloves'];
	$equip_lamulet = $lang['Adr_battle_select_amulet'];
	$equip_lring = $lang['Adr_battle_select_ring'];

}

else
{
	$template->assign_block_vars('see',array());

	$equip_title = sprintf($lang['Adr_equip_title_of'],$char['character_name']);
	$equip_larmor = $lang['Adr_equip_armor'];
	$equip_lbuckler = $lang['Adr_equip_buckler'];
	$equip_lhelm = $lang['Adr_equip_helm'];
	$equip_lgloves = $lang['Adr_equip_gloves'];
	$equip_lamulet = $lang['Adr_equip_amulet'];
	$equip_lring = $lang['Adr_equip_ring'];

	$armor_list = $lang['Adr_battle_no_armor'];
	$buckler_list = $lang['Adr_battle_no_buckler'];
	$helm_list = $lang['Adr_battle_no_helm'];
	$gloves_list = $lang['Adr_battle_no_gloves'];
	$amulet_list = $lang['Adr_battle_no_amulet'];
	$ring_list = $lang['Adr_battle_no_ring'];

	$sql = " SELECT * FROM " . ADR_SHOPS_ITEMS_TABLE . "
		WHERE item_in_shop = 0 
		AND item_duration > 0
		AND item_in_warehouse = 0
		AND item_monster_thief = 0 
		AND item_owner_id = $searchid ";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query items list', '', __LINE__, __FILE__, $sql);
	}
	$items = $db->sql_fetchrowset($result);

	for ( $i = 0 ; $i < count($items) ; $i ++ )
	{
		if ( $items[$i]['item_type_use'] == 7 && $items[$i]['item_id'] == $equip_armor )
		{
			$armor_list = adr_get_lang($items[$i]['item_name']) . ' ( ' . $lang['Adr_items_power'] . ' : ' . $items[$i]['item_power'] . ' - ' . $lang['Adr_items_duration'] . ' : ' . $items[$i]['item_duration'] . ' )';
			$armor_pic = $items[$i]['item_icon'];
		}
		if ( $items[$i]['item_type_use'] == 8 && $items[$i]['item_id'] == $equip_buckler )
		{
			$buckler_list = adr_get_lang($items[$i]['item_name']) . ' ( ' . $lang['Adr_items_power'] . ' : ' . $items[$i]['item_power'] . ' - ' . $lang['Adr_items_duration'] . ' : ' . $items[$i]['item_duration'] . ' )';
			$buckler_pic = $items[$i]['item_icon'];
		}
		if ( $items[$i]['item_type_use'] == 9 && $items[$i]['item_id'] == $equip_helm )
		{
			$helm_list = adr_get_lang($items[$i]['item_name']) . ' ( ' . $lang['Adr_items_power'] . ' : ' . $items[$i]['item_power'] . ' - ' . $lang['Adr_items_duration'] . ' : ' . $items[$i]['item_duration'] . ' )';
			$helm_pic = $items[$i]['item_icon'];
		}
		if ( $items[$i]['item_type_use'] == 10 && $items[$i]['item_id'] == $equip_gloves )
		{
			$gloves_list = adr_get_lang($items[$i]['item_name']) . ' ( ' . $lang['Adr_items_power'] . ' : ' . $items[$i]['item_power'] . ' - ' . $lang['Adr_items_duration'] . ' : ' . $items[$i]['item_duration'] . ' )';
			$gloves_pic = $items[$i]['item_icon'];
		}
		if ( $items[$i]['item_type_use'] == 13 && $items[$i]['item_id'] == $equip_amulet )
		{
			$amulet_list = adr_get_lang($items[$i]['item_name']) . ' ( ' . $lang['Adr_items_power'] . ' : ' . $items[$i]['item_power'] . ' - ' . $lang['Adr_items_duration'] . ' : ' . $items[$i]['item_duration'] . ' )';
			$amulet_pic = $items[$i]['item_icon'];
		}
		if ( $items[$i]['item_type_use'] == 14 && $items[$i]['item_id'] == $equip_ring )
		{
			$ring_list = adr_get_lang($items[$i]['item_name']) . ' ( ' . $lang['Adr_items_power'] . ' : ' . $items[$i]['item_power'] . ' - ' . $lang['Adr_items_duration'] . ' : ' . $items[$i]['item_duration'] . ' )';
			$ring_pic = $items[$i]['item_icon'];
		}
	}
}



$template->assign_vars(array(
	'ARMOR_IMG' => $armor_pic,
	'BUCKLER_IMG' => $buckler_pic,
	'HELM_IMG' => $helm_pic,
	'GLOVES_IMG' => $gloves_pic,
	'AMULET_IMG' => $amulet_pic,
	'RING_IMG' => $ring_pic,
	'SELECT_ARMOR'  => $armor_list,
	'SELECT_BUCKLER' => $buckler_list,
	'SELECT_HELM' => $helm_list,
	'SELECT_GLOVES' => $gloves_list,
	'SELECT_AMULET' => $amulet_list,
	'SELECT_RING' => $ring_list, 
	'L_EQUIPMENT' => $equip_title,
	'L_SELECT_ARMOR'  => $equip_larmor,
	'L_SELECT_BUCKLER' => $equip_lbuckler,
	'L_SELECT_HELM' => $equip_lhelm,
	'L_SELECT_GLOVES' => $equip_lgloves,
	'L_SELECT_AMULET' => $equip_lamulet,
	'L_SELECT_RING' => $equip_lring,
	'L_EQUIP' => $lang['Adr_equip'],
));

include($phpbb_root_path . 'adr/includes/adr_header.'.$phpEx);

$template->pparse('body');
include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
 
?> 
