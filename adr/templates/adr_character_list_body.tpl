<form method="post" action="{S_LIST_ACTION}">
<br />
<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr> 
		<td align="center" nowrap="nowrap"><span class="genmed">{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;<input type="submit" value="{L_SORT}" class="liteoption" /></span></td>
	</tr>
</table>

<table cellspacing="1" cellpadding="4" border="0" align="center" class="forumline" width="100%">
	<tr>
		<th align="center">{L_USERNAME}</th>
		<th align="center">{L_CHARACTER_NAME}</th>
		<th align="center">{L_LEVEL}</th>
		<th align="center">{L_SHOP_NAME}</th>
		<th align="center">{L_ITEMS}</th>
	</tr>
	<!-- BEGIN characters -->
	<tr>
		<td class="{characters.ROW_CLASS}" align="center"><span class="gen"><b>{characters.USERNAME}</b></span></a><br />{characters.AVATAR}</td>
		<td class="{characters.ROW_CLASS}" align="center"><a href="{characters.U_CHARACTER_NAME}"><span class="gen">{characters.CHARACTER_NAME}</span></a></td>
		<td class="{characters.ROW_CLASS}" align="center"><span class="gen">{characters.CHARACTER_LEVEL}</span></td>
		<td class="{characters.ROW_CLASS}" align="center"><a href="{characters.U_SHOP_NAME}"><span class="gen">{characters.SHOP_NAME}</span></a></td>
		<td class="{characters.ROW_CLASS}" align="center"><a href="{characters.U_INVENTORY}"><span class="gen">{L_ITEMS}</span></a></td>
	</tr>
	<!-- END characters -->
	<tr> 
		<td class="catBottom" colspan="5" height="28">&nbsp;</td>
	</tr>
</table>

<table width="100%" cellspacing="2" border="0" align="center" cellpadding="2">
	<tr> 
		<td align="right" valign="top"></td>
	</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr> 
		<td><span class="nav">{PAGE_NUMBER}</span></td>
		<td align="right"><span class="gensmall"><span class="nav">{PAGINATION}</span></td>
	</tr>
</table>
</form>
<br clear="all" />
