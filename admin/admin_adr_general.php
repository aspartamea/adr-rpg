<?php
/***************************************************************************
 *                              admin_adr_general.php
 *                            ------------------
 *   begin                : 01/02/2004
 *
 *
 ***************************************************************************/

define('IN_PHPBB', 1);
define('IN_ADR_ADMIN', 1);
define('IN_ADR_CHARACTER', 1);
define('IN_ADR_SHOPS', 1);
define('IN_ADR_SETTINGS', 1);
define('IN_ADR_VAULT', 1);
define('IN_ADR_BATTLE', 1);
define('IN_ADR_TEMPLE', 1);
define('IN_ADR_CELL', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Adr_settings']['Adr_misc_settings'] = "$filename?mode=misc";
	$module['Adr_settings']['Adr_items_settings'] = "$filename?mode=items";
	$module['Adr_settings']['Adr_skills_settings'] = "$filename?mode=skills";
	$module['Adr_settings']['Adr_temple_settings'] = "$filename?mode=temple";
	$module['Adr_settings']['Adr_Jail'] = "$filename?mode=cell";
	$module['Adr_vault']['Adr_vault_settings'] = "$filename?mode=vault";
	$module['Adr_battle']['Adr_battle_settings'] = "$filename?mode=battle";
	$module['Adr_settings']['Adr_display_settings'] = "$filename?mode=display";
	return;
}

$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require("pagestart.$phpEx");
include($phpbb_root_path . 'adr/includes/adr_global.'.$phpEx);

adr_template_file('admin/config_adr_general_body.tpl');

if( isset($_POST['mode']) || isset($_GET['mode']) )
{
	$mode = ( isset($_POST['mode']) ) ? $_POST['mode'] : $_GET['mode'];
	$mode = htmlspecialchars($mode);
}
else
{
	$mode = "misc";
}
$template->assign_block_vars($mode,array());

$submit = isset($_POST['submit']); 

$sql = "SELECT * FROM  " . ADR_GENERAL_TABLE ; 
if (!$result = $db->sql_query($sql)) 
{
	message_die(GENERAL_MESSAGE, $lang['Adr_character_lack']);
}
while( $row = $db->sql_fetchrow($result) )
{
	$adr_general[$row['config_name']] = $row['config_value'];
}

if ( $mode == "items" )
{
	// Get and display all the items type
	$sql = "SELECT * FROM  " . ADR_SHOPS_ITEMS_TYPE_TABLE . "
		WHERE item_type_id <> 0
		ORDER BY item_type_id ";  
	if (!$result = $db->sql_query($sql)) 
	{
		message_die(GENERAL_MESSAGE, $lang['Adr_character_lack']);
	}
	$items_type = $db->sql_fetchrowset($result);

	for ( $t = 0 ; $t < count($items_type) ; $t ++ )
	{
		$template->assign_block_vars('items.type_items' , array(
			'L_ITEMS_MODIFIER_PRICE_TYPE' => $lang[$items_type[$t]['item_type_lang']],
			'ITEMS_TYPE_ID' => $items_type[$t]['item_type_id'],
			'ADR_ITEMS_MODIFIER_PRICE_TYPE' => $items_type[$t]['item_type_base_price']
		));
	}

	// Get and display all the items quality
	$ssql = "SELECT * FROM  " . ADR_SHOPS_ITEMS_QUALITY_TABLE . "
		WHERE item_quality_id <> 0
		ORDER BY item_quality_id ";  
	if (!$sresult = $db->sql_query($ssql)) 
	{
		message_die(GENERAL_MESSAGE, $lang['Adr_character_lack']);
	}
	$items_quality = $db->sql_fetchrowset($sresult);

	for ( $q = 0 ; $q < count($items_quality) ; $q ++ )
	{
		$template->assign_block_vars('items.quality_items' , array(
			'L_ITEMS_MODIFIER_PRICE_QUALITY' => $lang[$items_quality[$q]['item_quality_lang']],
			'ITEMS_QUALITY_ID' => $items_quality[$q]['item_quality_id'],
			'ADR_ITEMS_MODIFIER_PRICE_QUALITY' => $items_quality[$q]['item_quality_modifier_price']
		));
	}
}

$topic_config = explode( '-' , $board_config['Adr_topics_display']);
$cache_config = explode('-', $board_config['Adr_use_cache_system']);

$template->assign_vars(array(
	'SHOP_DURA' => $board_config['Adr_shop_duration'],
	'SHOP_TAX' => $board_config['Adr_shop_tax'],
	'WH_DURA' => $board_config['Adr_warehouse_duration'],
	'WH_TAX' => $board_config['Adr_warehouse_tax'],
	'THIEF_ENABLE' => ( $board_config['Adr_thief_enable'] ? 'CHECKED' :'' ),
	'NO_THIEF_ENABLE' => ( !$board_config['Adr_thief_enable'] ? 'CHECKED' :'' ),
	'THIEF_POINTS' => $board_config['Adr_thief_points'],
	'SP_SKILLS_CHECKED' => ( $board_config['Adr_skill_sp_enable'] ? 'CHECKED' :'' ),
	'NO_SP_SKILLS_CHECKED' => ( !$board_config['Adr_skill_sp_enable'] ? 'CHECKED' :'' ),
	'SP_CHARACTER_CHECKED' => ( $board_config['Adr_character_sp_enable'] ? 'CHECKED' :'' ),
	'NO_SP_CHARACTER_CHECKED' => ( !$board_config['Adr_character_sp_enable'] ? 'CHECKED' :'' ),
	'ENABLE_RPG_CHECKED' => ( $adr_general['Adr_disable_rpg'] ? 'CHECKED' :'' ),
	'NO_ENABLE_RPG_CHECKED' => ( !$adr_general['Adr_disable_rpg'] ? 'CHECKED' :'' ),
	'POSTS_CHECKED' => ( $adr_general['posts_enable'] ? 'CHECKED' :'' ),
	'NO_POSTS_CHECKED' => ( !$adr_general['posts_enable'] ? 'CHECKED' :'' ),
	'POSTS_MIN' => $adr_general['posts_min'],
	'LIMIT_ENABLE_CHECKED' => ( $adr_general['Adr_character_limit_enable'] ? 'CHECKED' :'' ),
	'NO_LIMIT_ENABLE_CHECKED' => ( !$adr_general['Adr_character_limit_enable'] ? 'CHECKED' :'' ),
	'REGEN_PERIOD' => $adr_general['Adr_limit_regen_duration'],
	'BATTLE_LIMIT' => $adr_general['Adr_character_battle_limit'],
	'SKILL_LIMIT' => $adr_general['Adr_character_skill_limit'],
	'TRADING_LIMIT' => $adr_general['Adr_character_trading_limit'],
	'THIEF_LIMIT' => $adr_general['Adr_character_thief_limit'],
	'EXPERIENCE_NEW' => $board_config['Adr_experience_for_new'],
	'WEIGHT_ENABLE_CHECKED' => ( $adr_general['weight_enable'] ? 'CHECKED' :'' ),
	'NO_WEIGHT_ENABLE_CHECKED' => ( !$adr_general['weight_enable'] ? 'CHECKED' :'' ),
	'CHARACTER_AGE' => $board_config['Adr_character_age'],
	'EXPERIENCE_REPLY' => $board_config['Adr_experience_for_reply'],
	'EXPERIENCE_EDIT' => $board_config['Adr_experience_for_edit'],
	'CHARACTER_STATS_MAX' => $adr_general['max_characteristic'],
	'CHARACTER_STATS_MIN' => $adr_general['min_characteristic'],
	'CHARACTER_REROLL_CHECKED' => ( $adr_general['allow_reroll'] ? 'CHECKED' :'' ),
	'NO_CHARACTER_REROLL_CHECKED' => ( !$adr_general['allow_reroll'] ? 'CHECKED' :'' ),
	'CHARACTER_DELETE_CHECKED' => ( $adr_general['allow_character_delete'] ? 'CHECKED' :'' ),
	'NO_CHARACTER_DELETE_CHECKED' => ( !$adr_general['allow_character_delete'] ? 'CHECKED' :'' ),
	'BATTLE_USE_CHECKED' => ( $adr_general['battle_enable'] ? 'CHECKED' :'' ),
	'NO_BATTLE_USE_CHECKED' => ( !$adr_general['battle_enable'] ? 'CHECKED' :'' ),
	'BATTLE_PVP_USE_CHECKED' => ( $adr_general['battle_pvp_enable'] ? 'CHECKED' :'' ),
	'NO_BATTLE_PVP_USE_CHECKED' => ( !$adr_general['battle_pvp_enable'] ? 'CHECKED' :'' ),
	'SHOP_STEAL_CHECKED' => ( $adr_general['allow_shop_steal'] ? 'CHECKED' :'' ),
	'NO_SHOP_STEAL_CHECKED' => ( !$adr_general['allow_shop_steal'] ? 'CHECKED' :'' ),
	'SHOP_STEAL' => $adr_general['allow_shop_steal'],
	'NO_STEAL_SELL_CHECKED' => (!$adr_general['Adr_shop_steal_sell'] ? 'CHECKED' : ''),
	'STEAL_SELL_CHECKED' => ($adr_general['Adr_shop_steal_sell'] ? 'CHECKED' : ''),
	'SHOP_STEAL_MIN_LVL' => $adr_general['Adr_shop_steal_min_lvl'],
	'NO_SHOP_STEAL_SHOW_CHECKED' => (!$adr_general['Adr_shop_steal_show'] ? 'CHECKED' : ''),
	'SHOP_STEAL_SHOW_CHECKED' => ($adr_general['Adr_shop_steal_show'] ? 'CHECKED' : ''),
	'CACHE_INT' => intval($adr_general['Adr_cache_interval']),
	'NEW_SHOP_PRICE' => $adr_general['new_shop_price'],
	'ITEMS_MODIFIER_POWER' => $adr_general['item_modifier_power'],
	'SKILL_TRADING_POWER' => $adr_general['skill_trading_power'],
	'FAIL_STEAL_AMEND' => $adr_general['skill_thief_failure_damage'],
	'VAULT_USE_CHECKED' => ( $adr_general['vault_enable'] ? 'CHECKED' :'' ),
	'NO_VAULT_USE_CHECKED' => ( !$adr_general['vault_enable'] ? 'CHECKED' :'' ),
	'VAULT_USE_LOAN_CHECKED' => ( $adr_general['vault_loan_enable'] ? 'CHECKED' :'' ),
	'NO_VAULT_USE_LOAN_CHECKED' => ( !$adr_general['vault_loan_enable'] ? 'CHECKED' :'' ),
	'VAULT_INTERESTS_RATE' => $adr_general['interests_rate'],
	'VAULT_INTERESTS_TIME' => $adr_general['interests_time'],
	'VAULT_INTERESTS_TIME_EXPLAIN' =>$lang['Adr_vault_time_explain'].adr_make_time($adr_general['interests_time']), 
	'VAULT_LOAN_INTERESTS' => $adr_general['loan_interests'],
	'VAULT_LOAN_INTERESTS_TIME' => $adr_general['loan_interests_time'],
	'VAULT_LOAN_INTERESTS_TIME_EXPLAIN' => $lang['Adr_vault_time_explain'].adr_make_time($adr_general['loan_interests_time']),
	'VAULT_LOAN_MAX_SUM' => $adr_general['loan_max_sum'],
	'VAULT_LOAN_REQUIREMENTS' => $adr_general['loan_requirements'],
	'MONSTERS_STATS_MODIFIER' => $adr_general['battle_monster_stats_modifier'],
	'MONSTERS_MODIFIER_TYPE_1' => ( !$adr_general['battle_calc_type'] ? 'CHECKED' :'' ),
	'MONSTERS_MODIFIER_TYPE_2' => ( $adr_general['battle_calc_type'] ? 'CHECKED' :'' ),
	'MONSTERS_BASE_EXP_MIN' => $adr_general['battle_base_exp_min'],
	'MONSTERS_BASE_EXP_MAX' => $adr_general['battle_base_exp_max'],
	'MONSTERS_EXP_MODIFIER' => $adr_general['battle_base_exp_modifier'],
	'MONSTERS_SP_MODIFIER' => $adr_general['battle_base_sp_modifier'],
	'MONSTERS_BASE_REWARD_MIN' => $adr_general['battle_base_reward_min'],
	'MONSTERS_BASE_REWARD_MAX' => $adr_general['battle_base_reward_max'],
	'MONSTERS_REWARD_MODIFIER' => $adr_general['battle_base_reward_modifier'],
	'PLAYERS_BASE_EXP_MIN' => $adr_general['pvp_base_exp_min'],
	'PLAYERS_BASE_EXP_MAX' => $adr_general['pvp_base_exp_max'],
	'PLAYERS_EXP_MODIFIER' => $adr_general['pvp_base_exp_modifier'],
	'PLAYERS_BASE_REWARD_MIN' => $adr_general['pvp_base_reward_min'],
	'PLAYERS_BASE_REWARD_MAX' => $adr_general['pvp_base_reward_max'],
	'PLAYERS_REWARD_MODIFIER' => $adr_general['pvp_base_reward_modifier'],
	'TEMPLE_HEAL' => $adr_general['temple_heal_cost'],
	'TEMPLE_RESURRECT' => $adr_general['temple_resurrect_cost'],
	'CELL_CAUTION_CHECKED' => ( $adr_general['cell_allow_user_caution'] ? 'CHECKED' :'' ),
	'CELL_JUDGE_CHECKED' => ( $adr_general['cell_allow_user_judge'] ? 'CHECKED' :'' ),
	'CELL_BLANK_CHECKED' => ( $adr_general['cell_allow_user_blank'] ? 'CHECKED' :'' ),
	'NO_CELL_CAUTION_CHECKED' => ( !$adr_general['cell_allow_user_caution'] ? 'CHECKED' :'' ),
	'NO_CELL_JUDGE_CHECKED' => ( !$adr_general['cell_allow_user_judge'] ? 'CHECKED' :'' ),
	'NO_CELL_BLANK_CHECKED' => ( !$adr_general['cell_allow_user_blank'] ? 'CHECKED' :'' ),
	'CELL_BLANK' => $adr_general['cell_amount_user_blank'],
	'CELL_VOTERS' => $adr_general['cell_user_judge_voters'],
	'CELL_POSTS' => $adr_general['cell_user_judge_posts'],
	'FAIL_STEAL_PUNISHMENT0' => ( !$adr_general['skill_thief_failure_punishment'] ? 'CHECKED' :'' ),
	'FAIL_STEAL_PUNISHMENT1' => ( ( $adr_general['skill_thief_failure_punishment'] == 1  ) ? 'CHECKED' :'' ),
	'FAIL_STEAL_PUNISHMENT2' => ( ( $adr_general['skill_thief_failure_punishment'] == 2  ) ? 'CHECKED' :'' ),
	'FAIL_STEAL_TYPE0' => ( ( $adr_general['skill_thief_failure_type'] == 1 ) ? 'CHECKED' :'' ),
	'FAIL_STEAL_TYPE1' => ( ( $adr_general['skill_thief_failure_type'] == 2 ) ? 'CHECKED' :'' ),
	'FAIL_STEAL_TYPE2' => ( ( $adr_general['skill_thief_failure_type'] == 3 ) ? 'CHECKED' :'' ),
	'FAIL_STEAL_TIME' => $adr_general['skill_thief_failure_time'],
	'ITEM_POWER_LEVEL' => ( $adr_general['item_power_level'] ? 'CHECKED' :'' ),
	'NO_ITEM_POWER_LEVEL' => ( !$adr_general['item_power_level'] ? 'CHECKED' :'' ),
	'TRAINING_ALLOW_CHANGE_CHECKED' => ( $adr_general['training_allow_change'] ? 'CHECKED' :'' ),
	'NO_TRAINING_ALLOW_CHANGE_CHECKED' => ( !$adr_general['training_allow_change'] ? 'CHECKED' :'' ),
	'TRAINING_CHANGE_COST' => $adr_general['training_change_cost'],
	'TRAINING_SKILL_COST' => $adr_general['training_skill_cost'],
	'TRAINING_CHARAC_COST' => $adr_general['training_charac_cost'],
	'TRAINING_UPGRADE_COST' => $adr_general['training_upgrade_cost'],
	'LEVEL_UP_PENALTY' => $adr_general['next_level_penalty'],
	'DISPLAY_PROFILE_ALLOW_CHECKED' => ( $board_config['Adr_profile_display'] ? 'CHECKED' :'' ),
	'NO_DISPLAY_PROFILE_ALLOW_CHECKED' => ( !$board_config['Adr_profile_display'] ? 'CHECKED' :'' ),
	'NO_DISPLAY_TOPICS_LEVEL_CHECKED' => ( !$topic_config[0] ? 'CHECKED' :'' ),
	'DISPLAY_TOPICS_LEVEL_CHECKED' => ( $topic_config[0] ? 'CHECKED' :'' ),
	'NO_DISPLAY_TOPICS_CLASS_CHECKED' => ( !$topic_config[1] ? 'CHECKED' :'' ),
	'TEXT_DISPLAY_TOPICS_CLASS_CHECKED' => ( $topic_config[1] == '1' ? 'CHECKED' :'' ),
	'PIC_DISPLAY_TOPICS_CLASS_CHECKED' => ( $topic_config[1] == '2' ? 'CHECKED' :'' ),
	'NO_DISPLAY_TOPICS_RACE_CHECKED' => ( !$topic_config[2] ? 'CHECKED' :'' ),
	'TEXT_DISPLAY_TOPICS_RACE_CHECKED' => ( $topic_config[2] == '1' ? 'CHECKED' :'' ),
	'PIC_DISPLAY_TOPICS_RACE_CHECKED' => ( $topic_config[2] == '2' ? 'CHECKED' :'' ),
	'NO_DISPLAY_TOPICS_ELEMENT_CHECKED' => ( !$topic_config[3] ? 'CHECKED' :'' ),
	'TEXT_DISPLAY_TOPICS_ELEMENT_CHECKED' => ( $topic_config[3] == '1' ? 'CHECKED' :'' ),
	'PIC_DISPLAY_TOPICS_ELEMENT_CHECKED' => ( $topic_config[3] == '2' ? 'CHECKED' :'' ),
	'NO_DISPLAY_TOPICS_ALIGNMENT_CHECKED' => ( !$topic_config[4] ? 'CHECKED' :'' ),
	'TEXT_DISPLAY_TOPICS_ALIGNMENT_CHECKED' => ( $topic_config[4] == '1' ? 'CHECKED' :'' ),
	'PIC_DISPLAY_TOPICS_ALIGNMENT_CHECKED' => ( $topic_config[4] == '2' ? 'CHECKED' :'' ),
	'NO_DISPLAY_TOPICS_LINK_CHECKED' => ( !$topic_config[5] ? 'CHECKED' :'' ),
	'DISPLAY_TOPICS_LINK_CHECKED' => ( $topic_config[5] ? 'CHECKED' :'' ),
	'PVP_DEFIES' => $adr_general['battle_pvp_defies_max'],
	'NO_DISPLAY_TOPICS_PVP_CHECKED' => ((!$topic_config[6]) ? 'CHECKED' : ''),
	'DISPLAY_TOPICS_PVP_CHECKED' => (($topic_config[6]) ? 'CHECKED' : ''),
	'NO_DISPLAY_TOPICS_RANK_CHECKED' => ((!$topic_config[7]) ? 'CHECKED' : ''),
	'DISPLAY_TOPICS_RANK_CHECKED' => (($topic_config[7]) ? 'CHECKED' : ''),
	'NO_DISPLAY_TOPICS_BATTLE_STATS_CHECKED' => ((!$topic_config[8]) ? 'CHECKED' : ''),
	'DISPLAY_TOPICS_BATTLE_STATS_CHECKED' => (($topic_config[8]) ? 'CHECKED' : ''),
	'CACHE_ALIGNMENTS' => ($cache_config[0]) ? 'checked' : '',
	'CACHE_CLASSES' => ($cache_config[1]) ? 'checked' : '',
	'CACHE_CONFIG' => ($cache_config[2]) ? 'checked' : '',
	'CACHE_ELEMENTS' => ($cache_config[3]) ? 'checked' : '',
	'CACHE_ITEM_QUALITY' => ($cache_config[4]) ? 'checked' : '',
	'CACHE_ITEM_TYPE' => ($cache_config[5]) ? 'checked' : '',
	'CACHE_POSTERS' => ($cache_config[6]) ? 'checked' : '',
	'CACHE_RACES' => ($cache_config[7]) ? 'checked' : '',
	'CACHE_SKILLS' => ($cache_config[8]) ? 'checked' : '',
	'CACHE_MONSTERS' => ($cache_config[9]) ? 'checked' : '',
));

if ( $submit )
{
	if ( $mode == "display" )
	{
		$display_profile = intval($_POST['Adr_profile_display']);
		$display_topics = intval($_POST['display0']).'-';
		$display_topics .= intval($_POST['display1']).'-';
		$display_topics .= intval($_POST['display2']).'-';
		$display_topics .= intval($_POST['display3']).'-';
		$display_topics .= intval($_POST['display4']).'-';
		$display_topics .= intval($_POST['display5']).'-';
		$display_topics .= intval($_POST['display6']).'-';
		$display_topics .= intval($_POST['display7']).'-';
		$display_topics .= intval($_POST['display8']).'-';
		$display_topics .= intval($_POST['display9']);

		$sql= "UPDATE ". CONFIG_TABLE . " SET config_value = '$display_profile' WHERE config_name = 'Adr_profile_display' ";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			adr_previous( Adr_character_general_update_error , admin_adr_general , '' );
		} 

		$sql= "UPDATE ". CONFIG_TABLE . " SET config_value = '$display_topics' WHERE config_name = 'Adr_topics_display' ";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			adr_previous( Adr_character_general_update_error , admin_adr_general , '' );
		} 
	}

	if ( $mode == "misc" )
	{
		$name = htmlspecialchars($_POST['name']);
		$exp_new = intval($_POST['exp_new']);
		$exp_reply = intval($_POST['exp_reply']);
		$exp_edit = intval($_POST['exp_edit']);
		$weight_enable = intval($_POST['weight_enable']);
		$enable_rpg = intval($_POST['enable_rpg']);
		$limit_enable = intval($_POST['limit_enable']);
		$regen_period = intval($_POST['regen_period']);
		$battle_limit = intval($_POST['battle_limit']);
		$skill_limit = intval($_POST['skill_limit']);
		$trading_limit = intval($_POST['trading_limit']);
		$thief_limit = intval($_POST['thief_limit']);
		$posts = intval($_POST['posts']);
		$posts_min = intval($_POST['posts_min']);
		$character_age = intval($_POST['character_age']);
		$shop_tax = intval($_POST['shop_tax']);
		$shop_dura = intval($_POST['shop_dura']);
		$wh_tax = intval($_POST['wh_tax']);
		$wh_dura = intval($_POST['wh_dura']);

		##=== START: new cache format
		$cache = intval($_POST['Adr_cache_alignment']).'-';
		$cache .= intval($_POST['Adr_cache_class']).'-';
		$cache .= intval($_POST['Adr_cache_config']).'-';
		$cache .= intval($_POST['Adr_cache_element']).'-';
		$cache .= intval($_POST['Adr_cache_item_quality']).'-';
		$cache .= intval($_POST['Adr_cache_item_type']).'-';
		$cache .= intval($_POST['Adr_cache_posters']).'-';
		$cache .= intval($_POST['Adr_cache_race']).'-';
		$cache .= intval($_POST['Adr_cache_skill']).'-';
		$cache .= intval($_POST['Adr_cache_monster']);
		##=== END: new cache format

		##== START: Here we need to update all character quota's if quota value has changed
		if(($battle_limit != $adr_general['Adr_character_battle_limit']) || ($skill_limit != $adr_general['Adr_character_skill_limit']) || ($trading_limit != $adr_general['Adr_character_trading_limit']) || (($thief_limit != $adr_general['Adr_character_thief_limit'])))
		{
			$sql = "SELECT * FROM ". ADR_CHARACTERS_TABLE."";
			$result = $db->sql_query($sql);
			if (!$result)
				message_die(GENERAL_ERROR, 'Could not obtain character list', '', __LINE__, __FILE__, $sql);
			$characters = $db->sql_fetchrowset($result);

			for($c = 0; $c < count($characters); $c++){
				$character_id = $characters[$c]['character_id'];

				$sql = "UPDATE " . ADR_CHARACTERS_TABLE . "
					SET character_battle_limit = $battle_limit,
						character_skill_limit = $skill_limit,
						character_trading_limit = $trading_limit,
						character_thief_limit = $thief_limit
					WHERE character_id = '$character_id'";
				if(!($results = $db->sql_query($sql))){
					message_die(GENERAL_MESSAGE, 'Error changing user quota limits');}
			}
		}
		##== END: Here we need to update all character quota's if quota value has changed

		$sql= "UPDATE ". CONFIG_TABLE . " SET config_value = '$name' WHERE config_name = 'Adr_character_page_name' ";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			adr_previous( Adr_character_general_update_error , admin_adr_general , '' );
		} 

		$sql= "UPDATE ". CONFIG_TABLE . " SET config_value = '$exp_new' WHERE config_name = 'Adr_experience_for_new' ";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			adr_previous( Adr_character_general_update_error , admin_adr_general , '' );
		} 

		$sql= "UPDATE ". CONFIG_TABLE . " SET config_value = '$exp_reply' WHERE config_name = 'Adr_experience_for_reply' ";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			adr_previous( Adr_character_general_update_error , admin_adr_general , '' );
		} 

		$sql= "UPDATE ". CONFIG_TABLE . " SET config_value = '$exp_edit' WHERE config_name = 'Adr_experience_for_edit' ";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			adr_previous( Adr_character_general_update_error , admin_adr_general , '' );
		} 

		$sql= "UPDATE ". CONFIG_TABLE . " SET config_value = '$cache' WHERE config_name = 'Adr_use_cache_system' ";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			adr_previous( Adr_character_general_update_error , admin_adr_general , '' );
		} 

		$sql= "UPDATE ". ADR_GENERAL_TABLE . " SET config_value = '$weight_enable' WHERE config_name = 'weight_enable' ";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			adr_previous( Adr_character_general_update_error , admin_adr_general , '' );
		}

		$sql= "UPDATE ". ADR_GENERAL_TABLE . " SET config_value = '$enable_rpg' WHERE config_name = 'Adr_disable_rpg' ";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			adr_previous( Adr_character_general_update_error , admin_adr_general , '' );
		} 

		$sql= "UPDATE ". ADR_GENERAL_TABLE . " SET config_value = '$limit_enable' WHERE config_name = 'Adr_character_limit_enable' ";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			adr_previous( Adr_character_general_update_error , admin_adr_general , '' );
		} 

		$sql= "UPDATE ". ADR_GENERAL_TABLE . " SET config_value = '$regen_period' WHERE config_name = 'Adr_limit_regen_duration' ";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			adr_previous( Adr_character_general_update_error , admin_adr_general , '' );
		}  

		$sql= "UPDATE ". ADR_GENERAL_TABLE . " SET config_value = '$battle_limit' WHERE config_name = 'Adr_character_battle_limit' ";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			adr_previous( Adr_character_general_update_error , admin_adr_general , '' );
		}  

		$sql= "UPDATE ". ADR_GENERAL_TABLE . " SET config_value = '$skill_limit' WHERE config_name = 'Adr_character_skill_limit' ";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			adr_previous( Adr_character_general_update_error , admin_adr_general , '' );
		} 

		$sql= "UPDATE ". ADR_GENERAL_TABLE . " SET config_value = '$trading_limit' WHERE config_name = 'Adr_character_trading_limit' ";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			adr_previous( Adr_character_general_update_error , admin_adr_general , '' );
		} 

		$sql= "UPDATE ". ADR_GENERAL_TABLE . " SET config_value = '$thief_limit' WHERE config_name = 'Adr_character_thief_limit' ";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			adr_previous( Adr_character_general_update_error , admin_adr_general , '' );
		} 

		$sql= "UPDATE ". ADR_GENERAL_TABLE . " SET config_value = '$posts' WHERE config_name = 'posts_enable' ";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			adr_previous( Adr_character_general_update_error , admin_adr_general , '' );
		} 

		$sql= "UPDATE ". ADR_GENERAL_TABLE . " SET config_value = '$posts_min' WHERE config_name = 'posts_min' ";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			adr_previous( Adr_character_general_update_error , admin_adr_general , '' );
		}

		$sql= "UPDATE ". CONFIG_TABLE . " SET config_value = '$character_age' WHERE config_name = 'Adr_character_age' ";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			adr_previous( Adr_character_general_update_error , admin_adr_general , '' );
		}  


		$sql= "UPDATE ". CONFIG_TABLE . " SET config_value = '$wh_dura' WHERE config_name = 'Adr_warehouse_duration' ";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			adr_previous( Adr_character_general_update_error , admin_adr_general , '' );
		} 

		$sql= "UPDATE ". CONFIG_TABLE . " SET config_value = '$wh_tax' WHERE config_name = 'Adr_warehouse_tax' ";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			adr_previous( Adr_character_general_update_error , admin_adr_general , '' );
		} 

		$sql= "UPDATE ". CONFIG_TABLE . " SET config_value = '$shop_dura' WHERE config_name = 'Adr_shop_duration' ";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			adr_previous( Adr_character_general_update_error , admin_adr_general , '' );
		} 

		$sql= "UPDATE ". CONFIG_TABLE . " SET config_value = '$shop_tax' WHERE config_name = 'Adr_shop_tax' ";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			adr_previous( Adr_character_general_update_error , admin_adr_general , '' );
		} 
	}

	if ( $mode == "items" )
	{
		// Update the items type
		$tsql = array();
		while( list(,$item_type) = @each($items_type) )
		{
			$tsql[] = "UPDATE " . ADR_SHOPS_ITEMS_TYPE_TABLE . " 
				SET item_type_base_price = '" . intval($_POST['item_type'][$item_type['item_type_id']]) . "'
				WHERE item_type_id = '" . $item_type['item_type_id'] . "' ";
		}
		for( $i = 0; $i < count($tsql); $i++ )
		{
			if ( !($tresult = $db->sql_query($tsql[$i])) )
			{
				message_die(GENERAL_ERROR,"", __LINE__, __FILE__, $tsql[$i]);
			}
		}	

		// Update the items quality
		$qsql = array();
		while( list(,$item_quality) = @each($items_quality) )
		{
			$qsql[] = "UPDATE " . ADR_SHOPS_ITEMS_QUALITY_TABLE . " 
				SET item_quality_modifier_price = '" . intval($_POST['item_quality'][$item_quality['item_quality_id']]) . "'
				WHERE item_quality_id = '" . $item_quality['item_quality_id'] . "' ";
		}
		for( $i = 0; $i < count($qsql); $i++ )
		{
			if ( !($qresult = $db->sql_query($qsql[$i])) )
			{
				message_die(GENERAL_ERROR,"", __LINE__, __FILE__, $qsql[$i]);
			}
		}
	}

	if ( $mode == "skills" )
	{
		$allow_sp_skills = intval($_POST['allow_sp_skills']);
		$allow_sp_character = intval($_POST['allow_sp_character']);

		$sql= "UPDATE ". CONFIG_TABLE . " SET config_value = '$allow_sp_skills' WHERE config_name = 'Adr_skill_sp_enable' ";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			adr_previous( Adr_character_general_update_error , admin_adr_general , '' );
		} 

		$sql= "UPDATE ". CONFIG_TABLE . " SET config_value = '$allow_sp_character' WHERE config_name = 'Adr_character_sp_enable' ";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			adr_previous( Adr_character_general_update_error , admin_adr_general , '' );
		} 
	}	

	if ( $mode == "battle" )
	{
		$thief_enable = intval($_POST['thief_enable']);
		$thief_points = intval($_POST['thief_points']);
		$monster_modifier_type = intval($_POST['monster_modifier_type']);

		$sql= "UPDATE ". CONFIG_TABLE . " SET config_value = '$thief_enable' WHERE config_name = 'Adr_thief_enable' ";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			adr_previous( Adr_character_general_update_error , admin_adr_general , '' );
		} 

		$sql= "UPDATE ". CONFIG_TABLE . " SET config_value = '$thief_points' WHERE config_name = 'Adr_thief_points' ";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			adr_previous( Adr_character_general_update_error , admin_adr_general , '' );
		} 

		$sql= "UPDATE ". ADR_GENERAL_TABLE . " SET config_value = '$monster_modifier_type' WHERE config_name = 'battle_calc_type' ";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			adr_previous( Adr_character_general_update_error , admin_adr_general , '' );
		}
	}	

	$sql = "SELECT * FROM " . ADR_GENERAL_TABLE;
	if(!$result = $db->sql_query($sql))
	{
		message_die(CRITICAL_ERROR, "Could not query config information config table", "", __LINE__, __FILE__, $sql);
	}
	else
	{
		while( $row = $db->sql_fetchrow($result) )
		{
			$config_name = $row['config_name'];
			$config_value = $row['config_value'];
			$default_config[$config_name] = $config_value;
		
			$new[$config_name] = ( isset($_POST[$config_name]) ) ? $_POST[$config_name] : $default_config[$config_name];

			$sql = "UPDATE " . ADR_GENERAL_TABLE . " SET
				config_value = '" . str_replace("\'", "''", $new[$config_name]) . "'
				WHERE config_name = '$config_name'";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Failed to update general configuration for $config_name", "", __LINE__, __FILE__, $sql);
			}
		}
	}

	adr_update_all_cache_infos();
	adr_previous( Adr_character_general_update_success , admin_adr_general , "mode=$mode" ); 
}

$template->assign_vars(array(
	'CACHE_ALIGNMENTS_CHECK' => (@fopen($phpbb_root_path . './adr/cache/cache_alignments'.'.' . $phpEx, 'a+')) ? '<font="#00C000">WRITABLE</font>' : '<font="#FF0000">NON-WRITABLE</font>',
	'CACHE_CLASSES_CHECK' => (@fopen($phpbb_root_path . './adr/cache/cache_classes'.'.' . $phpEx, 'a+')) ? '<font="	#00C000">WRITABLE</font>' : '<font="#FF0000">NON-WRITABLE</font>',
	'CACHE_CONFIG_CHECK' => (@fopen($phpbb_root_path . './adr/cache/cache_config'.'.' . $phpEx, 'a+')) ? '<font="	#00C000">WRITABLE</font>' : '<font="#FF0000">NON-WRITABLE</font>',
	'CACHE_ELEMENTS_CHECK' => (@fopen($phpbb_root_path . './adr/cache/cache_elements'.'.' . $phpEx, 'a+')) ? '<font="	#00C000">WRITABLE</font>' : '<font="#FF0000">NON-WRITABLE</font>',
	'CACHE_ITEM_QUALITY_CHECK' => (@fopen($phpbb_root_path . './adr/cache/cache_item_quality'.'.' . $phpEx, 'a+')) ? '<font="	#00C000">WRITABLE</font>' : '<font="#FF0000">NON-WRITABLE</font>',
	'CACHE_ITEM_TYPE_CHECK' => (@fopen($phpbb_root_path . './adr/cache/cache_item_type'.'.' . $phpEx, 'a+')) ? '<font="	#00C000">WRITABLE</font>' : '<font="#FF0000">NON-WRITABLE</font>',
	'CACHE_POSTERS_CHECK' => (@fopen($phpbb_root_path . './adr/cache/cache_posters'.'.' . $phpEx, 'a+')) ? '<font="	#00C000">WRITABLE</font>' : '<font="#FF0000">NON-WRITABLE</font>',
	'CACHE_RACES_CHECK' => (@fopen($phpbb_root_path . './adr/cache/cache_races'.'.' . $phpEx, 'a+')) ? '<font="	#00C000">WRITABLE</font>' : '<font="#FF0000">NON-WRITABLE</font>',
	'CACHE_SKILLS_CHECK' => (@fopen($phpbb_root_path . './adr/cache/cache_skills'.'.' . $phpEx, 'a+')) ? '<font="	#00C000">WRITABLE</font>' : '<font="#FF0000">NON-WRITABLE</font>',
	'CACHE_MONSTERS_CHECK' => (@fopen($phpbb_root_path . './adr/cache/cache_monsters'.'.' . $phpEx, 'a+')) ? '<font="	#00C000">WRITABLE</font>' : '<font="#FF0000">NON-WRITABLE</font>',
	'L_DAY' => $lang['Adr_admin_days'],
	'L_HOUR' => $lang['Adr_admin_hours'],
	'L_MINUTE' => $lang['Adr_admin_mins'],
	'L_CHARACTER_TAX' => $lang['Adr_admin_character_tax'],
	'L_SHOP_TAX' => $lang['Adr_admin_character_shop_tax'],
	'L_SHOP_DURA' => $lang['Adr_admin_character_shop_dura'],
	'L_WH_TAX' => $lang['Adr_admin_character_wh_tax'],
	'L_WH_DURA' => $lang['Adr_admin_character_wh_dura'],
	'L_SKILLS_PAY' => $lang['Adr_character_skills_pay'],
	'L_SP_SKILLS' => $lang['Adr_character_sp_skills'],
	'L_SP_CHARACTER' => $lang['Adr_character_sp_character'],
	'L_POINTS' => $board_config['points_name'],
	'L_SP' => $lang['Adr_sp'],
	'L_SKILLS' => $lang['Adr_character_skills'],
	'L_CHARACTER_POSTS' => $lang['Adr_admin_posts'],
	'L_CHARACTER_POSTS_MIN' => $lang['Adr_admin_posts_min'],
	'L_REGEN_PERIOD_TITLE' => $lang['Adr_admin_regen_period_title'],
	'L_LIMIT_ENABLE' => $lang['Adr_admin_regen_enable'],
	'L_REGEN_PERIOD' => $lang['Adr_admin_regen_period'],
	'L_BATTLE_LIMIT' => $lang['Adr_admin_battle_limit'],
	'L_SKILL_LIMIT' => $lang['Adr_admin_skill_limit'],
	'L_TRADING_LIMIT' => $lang['Adr_admin_trading_limit'],
	'L_THIEF_LIMIT' => $lang['Adr_admin_thief_limit'],
	'L_SKILL_TRADING' => $lang['Adr_skill_trading_desc'],
	'L_SKILL_TRADING_POWER'=> $lang['Adr_skill_trading_power'],
	'L_SKILL_THIEF' => $lang['Adr_skill_thief_desc'],
	'L_FAIL_STEAL_AMEND' => $lang['Adr_skill_thief_failure_amend'],
	'L_FAIL_STEAL_AMEND_EXPLAIN' => $lang['Adr_skill_thief_failure_amend_explain'],
	'L_POINTS' => $board_config['points_name'],
	'L_ENABLE_RPG_TITLE' => $lang['Adr_admin_enable_rpg_title'],
	'L_ENABLE_RPG' => $lang['Adr_admin_enable_rpg'],
	'L_ON' => $lang['On'],
	'L_OFF' => $lang['Off'],
	'L_CHARACTER_AGE' => $lang['Adr_admin_character_age'],
	'L_EXPERIENCE' => $lang['Adr_admin_experience_posting'],
	'L_WEIGHT_ENABLE_TITLE' => $lang['Adr_admin_weight_enable_title'],
	'L_WEIGHT_ENABLE' => $lang['Adr_admin_weight_enable'],
	'L_EXPERIENCE_NEW' => $lang['Adr_admin_experience_posting_new'],
	'L_EXPERIENCE_REPLY' => $lang['Adr_admin_experience_posting_reply'],
	'L_EXPERIENCE_EDIT' => $lang['Adr_admin_experience_posting_edit'],
	'L_ALLOW_STEAL_SHOPS' => $lang['Adr_admin_allow_steal'],
	'L_SHOP_STEAL_SELL' => $lang['Adr_admin_allow_steal_sell'],
	'L_SHOP_STEAL_SELL_EXPLAIN' => $lang['Adr_admin_allow_steal_sell_ex'],
	'L_SHOP_STEAL_MIN_LVL' => $lang['Adr_admin_allow_steal_lvl'],
	'L_SHOP_STEAL_MIN_LVL_EXPLAIN' => $lang['Adr_admin_allow_steal_lvl_ex'],
	'L_SHOP_STEAL_SHOW' => $lang['Adr_admin_steal_show'],
	'L_SHOP_STEAL_SHOW_EXPLAIN' => $lang['Adr_admin_steal_show_ex'],
	'L_CACHE_INT' => $lang['Adr_admin_cache_int'],
	'L_CACHE_INT_EX' => $lang['Adr_admin_cache_int_ex'],
	'L_NEW_SHOP_PRICE' => $lang['Adr_admin_new_shop_price'],
	'L_ADR_ITEMS_BASE_PRICE' => $lang['Adr_admin_general_item_base_price_settings'],
	'L_ADR_ITEMS_MODIFIER_PRICE' => $lang['Adr_admin_general_item_modifier_settings'],
	'L_ADR_ITEMS_MODIFIER_PRICE_POWER' => $lang['Adr_admin_general_item_modifier_power_settings'],
	'L_ADR_ITEMS_MODIFIER_PRICE_POWER_EXPLAIN' => $lang['Adr_admin_general_item_modifier_power_settings_explain'],
	'L_ADR_ITEMS_MODIFIER_PRICE_QUALITY' => $lang['Adr_admin_general_item_modifier_quality_settings'],
	'L_ADR_ITEMS_MODIFIER_PRICE_QUALITY_EXPLAIN' => $lang['Adr_admin_general_item_modifier_quality_settings_explain'],
	'L_ADR_ITEMS_MODIFIER_PRICE_TYPE' => $lang['Adr_admin_general_item_modifier_type_settings'],
	'L_ADR_ITEMS_MODIFIER_PRICE_TYPE_EXPLAIN' => $lang['Adr_admin_general_item_modifier_type_settings_explain'],
	'L_ADR_MODIFIER_POWER' => $lang['Adr_admin_general_item_modifier_power'],
	'L_ADR_SETTINGS' => $lang['Adr_admin_general_settings'],
	'L_ADR_SHOP_SETTINGS' => $lang['Adr_admin_general_shop_settings'],
	'L_ADR_SETTINGS_EXPLAIN' => $lang['Adr_admin_general_settings_explain'],
	'L_ADR_CHARACTER_CREATION' => $lang['Adr_admin_general_character_creation'],
	'L_CHARACTER_REROLL' => $lang['Adr_admin_general_character_allow_reroll'],
	'L_CHARACTER_DELETE' => $lang['Adr_admin_general_character_allow_delete'],
	'L_CHARACTER_STATS_MAX' => $lang['Adr_admin_general_character_stats_max'],
	'L_CHARACTER_STATS_MIN' => $lang['Adr_admin_general_character_stats_min'],
	'L_SUBMIT' => $lang['Submit'],
	'L_YES' => $lang['Yes'],
	'L_NO' => $lang['No'],
	'L_TEMPLE_SETTINGS' => $lang['Adr_temple_settings'],
	'L_TEMPLE_SETTINGS_EXPLAIN' => $lang['Adr_temple_settings_explain'],
	'L_TEMPLE_HEAL' => $lang['Adr_temple_heal_cost'],
	'L_TEMPLE_RESURRECT' => $lang['Adr_temple_resurrect_cost'],
	'L_BATTLE_SETTINGS' => $lang['Adr_battle_settings'],
	'L_BATTLE_SETTINGS_EXPLAIN' => $lang['Adr_battle_settings_explain'],
	'L_BATTLE_THIEF' => $lang['Adr_battle_thief'],
	'L_BATTLE_THIEF_ENABLE' => $lang['Adr_battle_thief_enable'],
	'L_BATTLE_THIEF_POINTS' => $lang['Adr_battle_thief_points'],
	'L_BATTLE_USE' => $lang['Adr_battle_use'],
	'L_BATTLE_MONSTERS' => $lang['Adr_battle_monsters'],
	'L_MONSTERS_STATS_MODIFIER' => $lang['Adr_battle_monsters_stats_modifier'],
	'L_MONSTERS_STATS_MODIFIER_EXPLAIN' => $lang['Adr_battle_monsters_stats_modifier_explain'],
	'L_MONSTERS_MODIFIER_TYPE' => $lang['Adr_battle_monsters_modifier_type'],
	'L_MONSTERS_MODIFIER_TYPE_EXPLAIN' => $lang['Adr_battle_monsters_modifier_type_explain'],
	'L_MONSTERS_MODIFIER_TYPE_1' => $lang['Adr_battle_monsters_modifier_type_1'],
	'L_MONSTERS_MODIFIER_TYPE_2' => $lang['Adr_battle_monsters_modifier_type_2'],
	'L_MONSTERS_BASE_EXP_MIN' => $lang['Adr_battle_monster_stats_exp_min'],
	'L_MONSTERS_BASE_EXP_MAX' => $lang['Adr_battle_monster_stats_exp_max'],
	'L_MONSTERS_EXP_MODIFIER' => $lang['Adr_battle_monster_stats_exp_modifier'],
	'L_MONSTERS_EXP_MODIFIER_EXPLAIN' => $lang['Adr_battle_monster_stats_exp_modifier_explain'],
	'L_MONSTERS_SP_MODIFIER' => $lang['Adr_battle_monster_stats_sp_modifier'],
	'L_MONSTERS_SP_MODIFIER_EXPLAIN' => $lang['Adr_battle_monster_stats_sp_modifier_explain'],
	'L_MONSTERS_BASE_REWARD_MIN' => $lang['Adr_battle_monster_stats_reward_min'],
	'L_MONSTERS_BASE_REWARD_MAX' => $lang['Adr_battle_monster_stats_reward_max'],
	'L_MONSTERS_REWARD_MODIFIER' => $lang['Adr_battle_monster_stats_reward_modifier'],
	'L_MONSTERS_REWARD_MODIFIER_EXPLAIN' => $lang['Adr_battle_monster_stats_reward_modifier_explain'],
	'L_BATTLE_PLAYERS' => $lang['Adr_pvp'],
	'L_PVP_DEFIES' => $lang['Adr_pvp_defies_max'],
	'L_PLAYERS_BASE_EXP_MIN' => $lang['Adr_battle_monster_stats_exp_min'],
	'L_PLAYERS_BASE_EXP_MAX' => $lang['Adr_battle_monster_stats_exp_max'],
	'L_PLAYERS_EXP_MODIFIER' => $lang['Adr_battle_monster_stats_exp_modifier'],
	'L_PLAYERS_EXP_MODIFIER_EXPLAIN' => $lang['Adr_pvp_stats_exp_modifier_explain'],
	'L_PLAYERS_BASE_REWARD_MIN' => $lang['Adr_battle_monster_stats_reward_min'],
	'L_PLAYERS_BASE_REWARD_MAX' => $lang['Adr_battle_monster_stats_reward_max'],
	'L_PLAYERS_REWARD_MODIFIER' => $lang['Adr_battle_monster_stats_reward_modifier'],
	'L_PLAYERS_REWARD_MODIFIER_EXPLAIN' => $lang['Adr_pvp_stats_reward_modifier_explain'],
	'L_VAULT_SETTINGS' => $lang['Adr_vault_settings'],
	'L_VAULT_SETTINGS_EXPLAIN' => $lang['Adr_vault_settings_explain'],
	'L_VAULT_USE' => $lang['Adr_vault_use'],
	'L_VAULT_NAME' => $lang['Adr_vault_settings_name'],
	'L_VAULT_INTERESTS_RATE' => $lang['Adr_vault_interests_rate'],
	'L_VAULT_INTERESTS_RATE_EXPLAIN' => $lang['Adr_vault_interests_rate_explain'],
	'L_VAULT_INTERESTS_TIME' => $lang['Adr_vault_interests_time'],
	'L_VAULT_INTERESTS_TIME_EXPLAIN' => $lang['Adr_vault_interests_time_explain'],
	'L_VAULT_USE_LOAN' => $lang['Adr_vault_loan_use'],
	'L_VAULT_LOAN_INTERESTS' => $lang['Adr_vault_loan_interests'],
	'L_VAULT_LOAN_INTERESTS_EXPLAIN' => $lang['Adr_vault_loan_interests_explain'],
	'L_VAULT_LOAN_INTERESTS_TIME' => $lang['Adr_vault_loan_interests_time'],
	'L_VAULT_LOAN_INTERESTS_TIME_EXPLAIN' => $lang['Adr_vault_loan_interests_time_explain'],
	'L_VAULT_LOAN_MAX_SUM' => $lang['Adr_vault_max_sum'],
	'L_VAULT_LOAN_MAX_SUM_EXPLAIN' => $lang['Adr_vault_max_sum_explain'],
	'L_VAULT_LOAN_REQUIREMENTS' => $lang['Adr_vault_requirements'],
	'L_VAULT_LOAN_REQUIREMENTS_EXPLAIN' => $lang['Adr_vault_requirements_explain'],
	'L_CELL_SETTINGS' => $lang['Adr_Jail'],
	'L_CELL_SETTINGS_EXPLAIN' => $lang['Adr_cell_settings_explain'],
	'L_CELL_CAUTION' => $lang['Adr_cell_settings_caution'],
	'L_CELL_JUDGE' => $lang['Adr_cell_settings_judge'],
	'L_CELL_BLANK' => $lang['Adr_cell_settings_blank'],
	'L_CELL_BLANK_SUM' => $lang['Adr_cell_settings_blank_sum'],
	'L_CELL_VOTERS' => $lang['Adr_cell_settings_voters'],
	'L_CELL_POSTS' => $lang['Adr_cell_settings_posts'],
	'L_FAIL_STEAL_PUNISHMENT' => $lang['Adr_fail_steal_punishment'],
	'L_FAIL_STEAL_PUNISHMENT0' => $lang['Adr_fail_steal_punishment0'],
	'L_FAIL_STEAL_PUNISHMENT1' => $lang['Adr_fail_steal_punishment1'],
	'L_FAIL_STEAL_PUNISHMENT2' => $lang['Adr_fail_steal_punishment2'],
	'L_FAIL_STEAL_TYPE' => $lang['Adr_fail_steal_type'],
	'L_FAIL_STEAL_TYPE0' => $lang['Adr_fail_steal_type0'],
	'L_FAIL_STEAL_TYPE1' => $lang['Adr_fail_steal_type1'],
	'L_FAIL_STEAL_TYPE2' => $lang['Adr_fail_steal_type2'],
	'L_FAIL_STEAL_TIME' => $lang['Adr_fail_steal_time'],
	'L_ITEM_POWER_LEVEL' => $lang['Adr_battle_item_power_level'],
	'L_ITEM_POWER_LEVEL_EXPLAIN' => $lang['Adr_battle_item_power_level_explain'],
	'L_TRAINING' => $lang['Adr_town_training_grounds_admin'], 
	'L_TRAINING_ALLOW_CHANGE' => $lang['Adr_town_training_grounds_admin_change_allow'],
	'L_TRAINING_CHANGE_COST' => $lang['Adr_town_training_grounds_admin_change_cost'],
	'L_TRAINING_SKILL_COST' => $lang['Adr_town_training_grounds_admin_skill_cost'],
	'L_TRAINING_CHARAC_COST' => $lang['Adr_town_training_grounds_admin_charac_cost'],
	'L_TRAINING_UPGRADE_COST' => $lang['Adr_town_training_grounds_admin_upgrade_cost'],
	'L_USE_CACHE' => $lang['Adr_use_cache'],
	'L_USE_CACHE_EXPLAIN' => $lang['Adr_use_cache_explain'],
	'L_LEVEL_UP_PENALTY' => $lang['Adr_next_level_penalty'],
	'L_LEVEL_UP_PENALTY_EXPLAIN' => $lang['Adr_next_level_penalty_explain'],
	'L_ADR_DISPLAY' => $lang['Adr_display_settings'],
	'L_DISPLAY_PROFILE' => $lang['Adr_display_profile'],
	'L_DISPLAY_PROFILE_ALLOW' => $lang['Adr_display_profile_allow'],
	'L_DISPLAY_TOPICS' => $lang['Adr_display_topics'],
	'L_DISPLAY_TOPICS_LEVEL' => $lang['Adr_display_topics_level'],
	'L_DISPLAY_TOPICS_PVP' => $lang['Adr_display_topics_pvp'],
	'L_DISPLAY_TOPICS_RANK' => $lang['Adr_display_topics_rank'],
	'L_DISPLAY_TOPICS_BATTLE_STATS' => $lang['Adr_display_topics_battle_stats'],
	'L_TEXT' => $lang['Adr_display_topics_text'],
	'L_PIC' => $lang['Adr_display_topics_pic'],
	'L_DISPLAY_TOPICS_CLASS' => $lang['Adr_display_topics_class'],
	'L_DISPLAY_TOPICS_RACE' => $lang['Adr_display_topics_race'],
	'L_DISPLAY_TOPICS_ELEMENT' => $lang['Adr_display_topics_element'],
	'L_DISPLAY_TOPICS_ALIGNMENT' => $lang['Adr_display_topics_alignment'],
	'L_DISPLAY_TOPICS_LINK' => $lang['Adr_display_topics_link'],
	'L_BATTLE_PVP_USE' => $lang['Adr_pvp_enable_pvp'],
	'S_ADR_ACTION' => append_sid("admin_adr_general.$phpEx?mode=$mode"))
);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>
