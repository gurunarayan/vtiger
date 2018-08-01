<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_LoginPage_SaveAjax_Action extends Settings_Vtiger_Basic_Action {
	public function process(Vtiger_Request $request) {
		$selecteditem = $request->get('item');//Ex: LoginBox,ContentSlider..
		$dropdownid = $request->get('dropdownid'); // ex: 11,12,13..
		switch ($selecteditem) {
			case LoginBox:
				$result['content']='<input type="hidden" name="'.$dropdownid.'" value="'.$selecteditem.'">';
				$result['htmldata']='<table>
				<tr>
				   <td>Title</td>
				   <td><input type="text" name="loginbox_title" value=""></td>
				</tr>
				<tr>
				   <td>Sub Title</td>
				   <td><input type="text" name="loginbox_sub_title" value=""></td>
				</tr>
				<tr>
				   <td>Font Color</td>
				   <td><input type="color" name="loginbox_font_color" value="#fd9a00"></td>
				</tr>
			 </table>';
				break;
			case ImageSlider:
				$result['content']='<input type="hidden" name="'.$dropdownid.'" value="'.$selecteditem.'">';
				$result['htmldata']= '<input type="file" name="sliderimages_'.$dropdownid.'[]" multiple="multiple">';
				break;
			/*case ContentSlider:
				$result['content']='<input type="hidden" name="'.$dropdownid.'" value="'.$selecteditem.'">';
				$result['htmldata']= '<h1>' . $selecteditem . '</h1>';
				break;*/
			case Content:
				$result['content']='<input type="hidden" name="'.$dropdownid.'" value="'.$selecteditem.'">';
				$result['htmldata']= '<textarea name="'.$selecteditem.'_'.$dropdownid.'" value="" rows="5">';
				break;
			case Image:
				$result['content']='<input type="hidden" name="'.$dropdownid.'" value="'.$selecteditem.'">';
				$result['htmldata']= '<input type="file" name="image_'.$dropdownid.'">';
				break;
			case Logo:
				$result['content']='<input type="hidden" name="'.$dropdownid.'" value="'.$selecteditem.'">';
				$result['htmldata']= '<input type="file" name="logo_'.$dropdownid.'">';
				break;
			case socialicons:
				$result['content']='<input type="hidden" name="'.$dropdownid.'" value="'.$selecteditem.'">';
				$result['htmldata']= '<br><p>Please Enter Username only. </p><img src="layouts/vlayout/skins/images/facebook.png"  class="icons" style="vertical-align: middle;">&nbsp;<input type="text" name="si_facebook_'.$dropdownid.'" value="" placeholder="Facebook Link"><br>'.
				'<img src="layouts/vlayout/skins/images/twitter.png" name="twitter_icon_'.$dropdownid.'" class="icons" style="vertical-align: middle;">&nbsp;<input type="text" name="si_twitter_'.$dropdownid.'" value="" placeholder="Twitter Link"><br>'.
				'<img src="layouts/vlayout/skins/images/linkedin.png" name="linkedin_icon" class="icons" style="vertical-align: middle;">&nbsp;<input type="text" name="si_linkedin_'.$dropdownid.'" value="" placeholder="LinkedIn Link">';
				break;
			case WebsiteLinks:
				$result['content']='<input type="hidden" name="'.$dropdownid.'" value="'.$selecteditem.'">';
				$result['htmldata']= '<br>
						<input type="text" name="wlTitle1_'.$dropdownid.'" placeholder="Title">&nbsp;<input type="text" name="wlUrl1_'.$dropdownid.'" placeholder="URL"><br>
						<input type="text" name="wlTitle2_'.$dropdownid.'" placeholder="Title">&nbsp;<input type="text" name="wlUrl2_'.$dropdownid.'" placeholder="URL"><br>
						<input type="text" name="wlTitle3_'.$dropdownid.'" placeholder="Title">&nbsp;<input type="text" name="wlUrl3_'.$dropdownid.'" placeholder="URL">';
				break;
			default:
				$result['htmldata']= '';
			}
		$response = new Vtiger_Response();
		$response->setEmitType(Vtiger_Response::$EMIT_JSON);
		$response->setResult($result);
		$response->emit();
	}
}
