{*<!--
/* ********************************************************************************
 * The content of this file is subject to the Time Tracker ("License");
 * You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is VTExperts.com
 * Portions created by VTExperts.com. are Copyright(C) VTExperts.com.
 * All Rights Reserved.
 * ****************************************************************************** */
-->*}
{strip}
<div class="container-fluid">
    <div class="widget_header row-fluid">
        <h3>{'POS Settings'}</h3>
    </div>
    <hr>
    <div class="clearfix"></div>
    <form action="index.php" id="formSettings">
        <input type="hidden" name="module" value="PointSale"/>
        <input type="hidden" name="action" value="IndexAjax"/>
        <input type="hidden" name="mode" value="posSettings"/>
        <div class="summaryWidgetContainer">
            <ul class="nav nav-tabs massEditTabs">
                <li class="active">
                    <a href="#module_tax_settings" data-toggle="tab">
                        <strong>{'Tax Settings'}</strong>
                    </a>
                </li>
                <li>
                    <a href="#module_currency_settings" data-toggle="tab">
                        <strong>{'Currency Settings'}</strong>
                    </a>
                </li>
            </ul>
            <div class="tab-content massEditContent">
			
				<div class="tab-pane active" id="module_tax_settings">
					<div class="widgetContainer" style="padding: 20px 5px 5px 20px;">
						<div id="stepone">
							<br/>
							<b>Display Tax Calculation</b>
							<br/>
							<br/>
							<input type="radio" name="taxstatus" class="taxstatus" value="1" {if $TAX eq '1'}Checked{/if} >&nbsp;Individually For Single Products<br/>
							<input type="radio" name="taxstatus" class="taxstatus" value="2" {if $TAX eq '2'}Checked{/if} >&nbsp;Display Total Tax at the end.<br/>
						</div>
					</div>
                </div>
                
                <div class="tab-pane" id="module_currency_settings">
                    <div class="widgetContainer" style="padding: 20px 5px 5px 20px;">
						<div id="steptwo">
							<br/>
							<b>To Display Currency in Numeric Pad</b>
							<br/>
							<ul style="list-style-type: none;">
							   <li><input class="BoxSelect" name="BoxSelect[]" type="checkbox" value="10" {if $CURRENCY[0] eq '10' || $CURRENCY[1] eq '10' || $CURRENCY[2] eq '10'}Checked{/if}>&nbsp;10</li>
							   
							   <li><input class="BoxSelect" name="BoxSelect[]" type="checkbox" value="20" {if $CURRENCY[0] eq '20' || $CURRENCY[1] eq '20' || $CURRENCY[2] eq '20'}Checked{/if}>&nbsp;20</li>
							   
							   <li><input class="BoxSelect" name="BoxSelect[]" type="checkbox" value="50" {if $CURRENCY[0] eq '50' || $CURRENCY[1] eq '50' || $CURRENCY[2] eq '50'}Checked{/if}>&nbsp;50</li>
							   
							   <li><input class="BoxSelect" name="BoxSelect[]" type="checkbox" value="100" {if $CURRENCY[0] eq '100' || $CURRENCY[1] eq '100' || $CURRENCY[2] eq '100'}Checked{/if}>&nbsp;100</li>
							   
							   <li><input class="BoxSelect" name="BoxSelect[]" type="checkbox" value="200" {if $CURRENCY[0] eq '200' || $CURRENCY[1] eq '200' || $CURRENCY[2] eq '200'}Checked{/if}>&nbsp;200</li>
							   
							   <li><input class="BoxSelect" name="BoxSelect[]" type="checkbox" value="500" {if $CURRENCY[0] eq '500' || $CURRENCY[1] eq '500' || $CURRENCY[2] eq '500'}Checked{/if}>&nbsp;500</li>
							   
							   <li><input class="BoxSelect" name="BoxSelect[]" type="checkbox" value="1000" {if $CURRENCY[0] eq '1000' || $CURRENCY[1] eq '1000' || $CURRENCY[2] eq '1000'}Checked{/if}>&nbsp;1000</li>
							</ul>
							
						</div>
                    </div>
                </div>

            <div style="margin-top: 20px;">
                <span>
                    <button class="btn btn-success" type="button" id="btnSavePOSSettings">{vtranslate('LBL_SAVE')}</button>
                </span>
            </div>
        </div>
    </form>
</div>
{/strip}

<script type="text/javascript">

</script>