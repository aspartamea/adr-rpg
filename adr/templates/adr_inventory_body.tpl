<script language="Javascript" type="text/javascript">
<!-- 
function setCheckboxes(theForm, elementName, isChecked)
{
	var chkboxes = document.forms[theForm].elements[elementName];
	var count = chkboxes.length;

	if (count) 
	{
		for (var i = 0; i < count; i++) 
		{
			chkboxes[i].checked = isChecked;
	    	}
	} 
	else 
	{
    		chkboxes.checked = isChecked;
	} 
	return true;
} 

//--> 
</script>

<form method="post" action="{S_ITEMS_ACTION}" name="items_form" >

<!-- BEGIN main -->
<!-- BEGIN owner -->
<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<td align="center">
		<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
		<tr>
			<td align="center"><span class="gensmall">&nbsp;&nbsp;{L_WEIGHT}: {WEIGHT}</td>
		</tr>
		<tr>
			<td align="center">&nbsp;<img src="adr/images/misc/bar_orange_begin.gif" width="6" height="13" /><img src="adr/images/misc/bar_orange_middle.gif" width="{WEIGHT_PERCENT_WIDTH}" height="13" border="0" /><img src="adr/images/misc/bar_emp.gif" width="{WEIGHT_PERCENT_EMPTY}" height="13" border="0" /><img src="adr/images/misc/bar_orange_end.gif" width="6" height="13" /></td>
		</tr>
		</table>
		<!-- BEGIN overweight -->
		<tr>
			<td align="center"><span class="genbig"><b>{main.owner.overweight.L_WEIGHT_MSG}</b></span></td>
		</tr>
		<!-- END overweight -->
	</td>
</table>
<!-- END owner -->
<br />
<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr>
		<td align="center" nowrap="nowrap">
			<span class="genmed">
			<a href="#" onclick="setCheckboxes('items_form', 'item_box[]', true); return false;" class="gensmall">{L_CHECK_ALL}</a>&nbsp;/&nbsp;
			<a href="#" onclick="setCheckboxes('items_form', 'item_box[]', false); return false;" class="gensmall">{L_UNCHECK_ALL}</a>
			</span>
		</td>
		<td align="center" nowrap="nowrap"><span class="genmed">
			{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;
			{L_ORDER}&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;
			{L_SELECT_CAT}&nbsp;:&nbsp;{SELECT_CAT}&nbsp;&nbsp;
			<input type="submit" value="{L_SORT}" class="liteoption" /></span>
		</td>
	</tr>
</table>

<table cellspacing="1" cellpadding="4" border="0" align="center" class="forumline" width="100%">
	<tr>
		<!-- BEGIN owner -->
		<th align="center">{L_ACTION}</th>
		<!-- END owner -->
		<th align="center">{L_ITEM_NAME}:</th>
		<th align="left">{L_ITEM_DESC}:</th>
		<th align="center">{L_ITEM_PRICE}</th>
		<th align="center">{L_ITEM_QUALITY}</th>
		<th align="center">{L_ITEM_POWER}</th>
		<th align="center">{L_ITEM_WEIGHT}</th>
		<th align="center">{L_ITEM_DURATION}</th>
		<th align="center">{L_ITEM_TYPE}</th>
	</tr>
	<!-- BEGIN items -->
	<tr>
		<!-- BEGIN owner -->
		<td class="{main.items.ROW_CLASS}" align="center" ><input type="checkbox" name="item_box[]" value="{main.items.ITEM_ID}" /></td>
		<!-- END owner -->
		<td class="{main.items.ROW_CLASS}" align="center"><img style="border:0" src="./adr/images/items/{main.items.ITEM_IMG}"></a><br /><a href="{main.items.U_ITEM_INFO}"><span class="gen"><b>{main.items.ITEM_NAME}</b></span></a></td>
		<td class="{main.items.ROW_CLASS}" align="left">
			<span class="gensmall"><i>{main.items.ITEM_DESC}</i></span>
			<br><span class="gensmall">
        	<!-- BEGIN crit_hit -->
			<br><b>{main.items.crit_hit.L_CRIT_HIT}:</b>&nbsp;{main.items.crit_hit.CRIT_HIT}</b>
			<!-- END crit_hit -->
			<!-- BEGIN resist_chars -->
			<br><b>{main.items.resist_chars.L_CHAR_RESIST_LIST}:</b>&nbsp;{main.items.resist_chars.CHAR_RESIST_LIST}
			<!-- END resist_chars -->
        	<!-- BEGIN align_restrict -->
			<br><b>{main.items.align_restrict.L_ALIGN_LIST}:</b>&nbsp;{main.items.align_restrict.ALIGN_LIST}
			<!-- END align_restrict -->
      		<!-- BEGIN class_restrict -->
			<br><b>{main.items.class_restrict.L_CLASS_LIST}:</b>&nbsp;{main.items.class_restrict.CLASS_LIST}
			<!-- END class_restrict -->
        	<!-- BEGIN element_restrict -->
			<br><b>{main.items.element_restrict.L_ELEMENT_LIST}:</b>&nbsp;{main.items.element_restrict.ELEMENT_LIST}
			<!-- END element_restrict -->
        	<!-- BEGIN race_restrict -->
			<br><b>{main.items.race_restrict.L_RACE_LIST}:</b>&nbsp;{main.items.race_restrict.RACE_LIST}
			<!-- END race_restrict -->
        	<!-- BEGIN stolen_info -->
			<br><br>*{main.items.stolen_info.L_STOLEN_INFO}
			<!-- END stolen_info -->
        	<!-- BEGIN donated_info -->
			<br>**{main.items.donated_info.L_DONATED_INFO}
			<!-- END donated_info -->
			</span>
		</td>
		<td class="{main.items.ROW_CLASS}" align="center"><span class="gensmall">{main.items.ITEM_PRICE}</span></td>
		<td class="{main.items.ROW_CLASS}" align="center"><span class="gensmall">{main.items.ITEM_QUALITY}</span></td>
		<td class="{main.items.ROW_CLASS}" align="center"><span class="gensmall">{main.items.ITEM_POWER}</span></td>
		<td class="{main.items.ROW_CLASS}" align="center"><span class="gensmall">{main.items.ITEM_WEIGHT}</span></td>
		<td class="{main.items.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gensmall">{main.items.ITEM_DURATION}/{main.items.ITEM_DURATION_MAX}</span></td>
		<td class="{main.items.ROW_CLASS}" align="center"><span class="gensmall">{main.items.ITEM_TYPE}</span></td>
	</tr>
	<!-- END items -->
	<tr> 
		<td class="catBottom" colspan="{COLSPAN}" height="28" align="left">&nbsp;</td>
	</tr>
</table>

<table width="100%" cellspacing="2" border="0" align="center" cellpadding="2">
	<tr> 
		<td align="left" valign="top">{ACTION_LIST}&nbsp;<input type="submit" value="{L_SUBMIT}" class="mainoption"/></td>
	</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td><span class="nav">{PAGE_NUMBER}</span></td>
	</tr>
	<tr>
		<td align="left"><span class="gensmall"><span class="nav">{PAGINATION}</span></td>
	</tr>
</table>

<!-- END main -->

<!-- BEGIN view_item -->
</form>
<form method="post" action="{S_MODE_ACTION}">
<br />

<table width="50%" cellpadding="3" cellspacing="1" border="0" align="center" class="forumline">
		<tr height="30"> 
			<th align="center" colspan="2" width="20%" >{ITEM_NAME}{L_ITEM_INFO}</th>
		</tr>
		<tr height="30">
			<td class="{ROW_CLASS}" align="center" width="100%" colspan="2">&nbsp;<p>{ITEM_IMG}</p>
			<p><span class="gen">&nbsp;</span></td>
		</tr>
		<tr height="30">
			<td class="{ROW_CLASS_2}" align="center" width="40%"><span class="gen">{L_ITEM_DESC}</span></td>
			<td class="{ROW_CLASS_2}" align="center" width="60%"><span class="gen">{ITEM_DESC}</span></td>
		</tr>
		<tr height="30">
			<td class="{ROW_CLASS}" align="center" width="40%"><span class="gen">{L_ITEM_ELEMENT}</span></td>
			<td class="{ROW_CLASS}" align="center" width="60%"><span class="gen">{ITEM_ELEMENT}</span></td>
		</tr>
		<tr height="30">
			<td class="{ROW_CLASS_2}" align="center" width="40%"><span class="gen">{L_ITEM_POWER}</span></td>
			<td class="{ROW_CLASS_2}" align="center" width="60%"><span class="gen">{ITEM_POWER}</span></td>
		</tr>
		<tr height="30">
			<td class="{ROW_CLASS}" align="center" width="40%"><span class="gen">{L_ITEM_ADD_POWER}</span></td>
			<td class="{ROW_CLASS}" align="center" width="60%"><span class="gen">{ITEM_ADD_POWER}</span></td>
		</tr>
		<tr height="30">
			<td class="{ROW_CLASS_2}" align="center" width="40%"><span class="gen">{L_ITEM_MP}</span></td>
			<td class="{ROW_CLASS_2}" align="center" width="60%"><span class="gen">{ITEM_MP}</span></td>
		</tr>
		<tr height="30">
			<td class="{ROW_CLASS}" align="center" width="40%"><span class="gen">{L_ITEM_WEIGHT}</span></td>
			<td class="{ROW_CLASS}" align="center" width="60%"><span class="gen">{ITEM_WEIGHT}</span></td>
		</tr>
		<tr height="30">
			<td class="{ROW_CLASS_2}" align="center" width="40%"><span class="gen">{L_ITEM_DURA}</span></td>
			<td class="{ROW_CLASS_2}" align="center" width="60%"><span class="gen">{ITEM_DURA} / {ITEM_DURA_MAX}</span></td>
		</tr>
		<tr height="30">
			<td class="{ROW_CLASS}" align="center" width="40%"><span class="gen">{L_ITEM_QUALITY}</span></td>
			<td class="{ROW_CLASS}" align="center" width="60%"><span class="gen">{ITEM_QUALITY}</span></td>
		</tr>
		<tr height="30">
			<td class="{ROW_CLASS_2}" align="center" width="40%"><span class="gen">{L_ITEM_PRICE}</span></td>
			<td class="{ROW_CLASS_2}" align="center" width="60%"><span class="gen">{ITEM_PRICE} {ITEM_POINTS}</span></td>
		</tr>		<tr height="30"> 
			<td class="catBottom" colspan="2" height="28" align="center">&nbsp;</td>
		</tr>
	</table>

</form>
<form method="post" action="{S_MODE_ACTION}">
<!-- END view_item -->
</form>
<br clear="all" />
