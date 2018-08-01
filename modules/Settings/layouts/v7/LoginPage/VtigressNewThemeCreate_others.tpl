{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
-->*}
{strip}
<div class="widget_header row-fluid">
   <div class="span6">
      <h3>Login Page Generation</h3>
   </div>
</div>
<hr/>

<div class="container-fluid">
	<table class="table table-bordered marginLeftZero">
		<tbody>
			<tr class="listViewActionsDiv">
				<th colspan="4">Body Information</th>
			</tr>
			<tr>
				<td class="fieldLabel medium">Sections</td>
				<td class="fieldValue medium">
					<input type="text" name="row" id="row" value="{$ROWS}">&nbsp;X&nbsp;
					<input type="text" name="column" id="column" value="{$COLUMNS}">&nbsp;&nbsp;
					{if $COLUMNS == '' || $ROWS == ''}
					<input type="submit" name="create" id="create" value="Create" onclick="">
					{/if}
				</td>
			</tr>
		</tbody>
	</table>
</div>
<form name="LoginPage" action="index.php?module=LoginPage&parent=Settings&view=CustomLoginPage&x={$ROWS}&y={$COLUMNS}" target="_blank" method="post" class="form-horizontal" id="LoginPage" enctype="multipart/form-data">	
<div class="container-fluid">
	<div class="contents">
		
			<br>
			<table class="table table-bordered">
				<tbody>
					<tr class="listViewActionsDiv">
						<th colspan="5">Header Information <input type='checkbox' name='header' id='idChkHeader'> </th>
					</tr>
					<tr class="container">
						<td class="fieldLabel medium"><span class="redColor">*</span>Login Page Name</td>
						<td class="fieldValue medium">
							<input type='text' name='loginpagename' data-validation-engine="validate[required]" value=''/>
						<br/><b>Note:</b>Please don't use spaces in name
						</td>
						<td colspan='2'>
						</td>
					</tr>
					<tr class="container" >
						<td class="fieldLabel medium"><label class="muted pull-right marginRight10px">Left</label></td>
						<td class="fieldValue medium">						 
							<select name="select_header_left" class="text chzn-select select_page_view" id="idSelectHeaderLeft"> 
								<option value="">Select Value</option>
								<option value="Logo">Logo</option>
								<!--<option value="socialicons">Social Icons</option>
								<option value="WebsiteLinks">Website Links</option>-->
							</select>			
						</td>
						<td class="fieldLabel medium"><label class="muted pull-right marginRight10px">Right</label></td>
						<td class="fieldValue medium">						 
							<select name="select_header_right" class="text chzn-select select_page_view" id="idSelectHeaderRight"> 
								<option value="">Select Value</option>
								<!--<option value="Logo">Logo</option>--->
								<option value="socialicons">Social Icons</option>
								<option value="WebsiteLinks">Website Links</option>
							</select>			
						</td>
					</tr>
					<tr class='container'><td colspan='2' id='idTdLeftHeader'> </td><td colspan='2' id='idTdRightHeader'></td></tr>
					
				</tbody>
			</table>
			<br>
			{if $ROWS!='' && $COLUMNS!=''}	
			<table class="table table-bordered">	
				<tbody>		
					<tr class="container" >	 
						<td class="fieldLabel medium"><label class="muted pull-right marginRight10px">Backgroung Image</label></td>
						<td class="fieldValue medium" colspan="3">
						  <div class="row-fluid">
							 <span class="span10">
								 <input type='file' name='backgroundimage' class='clsBackgroundImage' id='idBackgroundImage' >							   
							 </span>
						  </div>
						</td> 
					</tr>
					{for $x = 1 to $ROWS} 
						<tr id="{$x}" class="container">
						{for $y = 1 to $COLUMNS} 
							 <td style="padding: 15px;" id="{$x}{$y}" name="section[]" class="data">
								<select name="page[]" class="text chzn-select select_page_view" id="{$x}{$y}">
									<option value="">Select Value</option>
									<option value="LoginBox">Login Box</option>
									<option value="ImageSlider">Image Slider</option>
									<!--<option value="ContentSlider">Content Slider</option>-->
									<option value="Content">Content</option>
									<option value="Image">Image</option>
									<option value="Logo">Logo</option>
									<option value="socialicons">Social Icons</option>
									<option value="WebsiteLinks">Website Links</option>
								</select><br>
								<div class="content" id="div_{$x}{$y}">
									
								</div>
							 </td>
						{/for}
						</tr>
					{/for}					
				</tbody>
			</table>
			<br>
			{/if}
			<table class="table table-bordered">
				<tbody>
					<tr class="container">
						<th colspan="5">Footer Information <input type='checkbox' name='footer' id='idCheckboxFooter'> 	</th>
					</tr>
					<tr class="container" >						 		
						
						<td class="fieldLabel medium"><label class="muted pull-right marginRight10px">Left</label></td>
						<td class="fieldValue medium">						 
							<select name="select_footer_left" class="text chzn-select select_page_view" id="idSelectFooterLeft"> 
								<option value="">Select Value</option>
								<option value="Content">Content</option>								
								<option value="socialicons">Social Icons</option>
								<option value="WebsiteLinks">Website Links</option>
							</select>			
						</td>
						<td class="fieldLabel medium"><label class="muted pull-right marginRight10px">Right</label></td>
						<td class="fieldValue medium">						 
							<select name="select_footer_right" class="text chzn-select" id="idSelectFooterRight"> 
								<option value="">Select Value</option>
								<option value="Content">Content</option>
								<option value="socialicons">Social Icons</option>
								<option value="WebsiteLinks">Website Links</option>
							</select>			
						</td>					
					</tr>
					<tr class='container'><td colspan='2' id='idTdLeftFooter'> </td><td colspan='2' id='idTdRightFooter'></td></tr>
				</tbody>
			</table>
			
			<br>				 
				<button class="btn btn-success pull-right generate" name="generate"  type="submit">
				<strong>Preview</strong>
				</button>
				<a class="btn btn-success pull-right save" name="save" id="save" >
				<strong>Save</strong>
				</a>
		
	</div>	
</form>
{/strip}
