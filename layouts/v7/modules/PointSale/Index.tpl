{strip}
{literal}
 <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" />  	    
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css" rel="stylesheet">
	 <link href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css" rel="stylesheet">
	 <link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet">	
<style>
 dl {
    display: flex;
    flex-direction: row;
	margin-bottom:0px;
	margin-top:0px;
}
  div {
    dt {
      display: block;
    }
  }
	
	.customProduct>img {
		height:50px;
	}
	#contact_name{
		font-weight:bolder;
		font-size:15px;
		
	}
	
	label, input { display:block; }
		input.text { margin-top:12px; width:95%; padding: .4em; }
		fieldset { padding:0; border:0; margin-top:25px; }
		h1 { font-size: 1.2em; margin: .6em 0; }
		div#users-contain { width: 350px; margin: 20px 0; }
		div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
		div#users-contain table td, div#users-contain table th { border: 1px solid #eee; text-align: left; }
		.ui-dialog .ui-state-error { margin-top:150px; }
		.validateTips { border: 1px solid transparent; }
    
	input[type=number]::-webkit-inner-spin-button, 
	input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    margin: 0; 
</style>
{/literal}
<div class="dashBoardContainer" style="background: #f5f5f5;">

	<div class="row">
		<div class="col-xs-6">
			<h2 style="margin-left: 20px;"class="pull-left">POS</h2>
		</div>
		<div class="col-xs-6" style="padding-top: 20px;">
			<div class="pull-right">
				<div class="btn-toolbar">
					<span class="btn-group">
						<button style="" class="btn addButton" id='cancelBtn'><a class="cancelLink" href="javascript:history.back()" type="reset">Cancel</a></button>
					</span>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="gridster ready" style="" >
	<input type='hidden' value='' name='posid' id='posid'/>
	<input type='hidden' value='' name='saleno' id='saleno'/>
	<input type='hidden' value='' name='contactid' id='contactid'/>
	<input type='hidden' value='{$CURRENCY_SETTINGS}' name='currency_settings' id='currency_settings'/>
	<input type='hidden' value='{$CURRENCY}' name='currency' id='currency'/>
	<input type='hidden' value="{$TAX_SETTINGS}" name='tax_settings' id='tax_settings'/>
	<div class="row-fluid" id='cartBlock' >
			
		<div class="col-xs-5">	
			<div class="col-xs-12" style='float:left;border:none;'><center><h3>BILL OF SALE</h3></center></div>
			<div class="col-xs-12" style="overflow: auto;max-height:400px; border: 1px solid #dddddd;height:400px;">
			<table id='productdiv' class="table  listview-table ">
				<thead>
					<tr>
						<th style="font-size:larger;">Item Name</th>
						<th style="font-size:larger;">Price</th>
						<th style="font-size:larger;">Stock</th>
						<th style="font-size:larger;">Qty</th>
						<th style="font-size:larger;">Total</th>
						<th width='3%'></th>
					</tr>
				</thead>
				<tbody id="producttable">
					
				</tbody>
			</table>
			<table id='totalTable' class="table table-bordered listview-table " style='display:none;'>
				<tbody>
					<tr >
						<td colspan='3' style="text-align:right;"><b style='font-size:large;'>Total:</b></td>
						<td id='total' style='font-size:large;'>0.00</td>
					</tr>
				</tbody>
			</table>
			</div>
			
			<div class="span6" style="border: 1px solid #dddddd; height:50px;">
				<span class="span">
					<img id='contactBtn' src="layouts/vlayout/skins/images/summary_Contact.png" class="summaryImg">
					
				</span>
				<span style="margin-top:15px;" id='contact_name' class="span3" title=""></span>
				<button style="float:right;margin-top:10px;margin-right:10px;display:none;" class="btn addButton" id='paymentBtn'>Payment</button>
	
			</div>
		</div>
	
		
		<div class="col-md-7" id='productsBlock' >
			<div class="col-md-4" style='float:left;'><center><h3>PRODUCTS</h3></center></div>
			<div class="col-md-4" style='margin-top:12px;'>
				<select id="getCategory" class="form-control" style="width:200px;">
						<option value="All" data-id="">{'All'}</option>
						{foreach item=data from=$CATEGORY}
							<option id="{$data}"  class="{$data}" value="{$data}" > {$data}</option>
						{/foreach}
					
				</select>
			</div>
			<div class="col-md-4" style='margin-top:12px;'>
				<input type='text' class="form-control" name='barcode_num' value="" placeholder="scan barcode" onkeydown="search(this)" style="width:200px;"/>
			</div>
			<div class="col-md-11 col-md-offset-1" style="background-color:lavender; height:430px; max-height:430px;overflow: auto;">
				<div id='ajaxLoadP'>
				{foreach from=$ALLPRODUCTS key=k item=data}
					{assign var=qty value=$data['qtyinstock']|string_format:"%.2f"}
					<span class="col-sm-1" data-product-id="{$data['productid']}">
						<a title="" id="{$data['productid']}" onclick="customProduct({$data['productid']});" ><img class="thumbnail" id="{$data['productid']}" 
						{if $data['path'] eq ''} src="no-image.png" {else} src="{$data['path']}{$data['attachmentsid']}_{$data['name']}"{/if} height="50" width="40" {if $qty <= 0.00} style="border-color:red;border-width:1px;border-style:solid;height:50px;" {else} style="height:50px;" {/if} >
							<div style='font-size:10px;height:15px;'>{$data['productname']|truncate:10}</div>
							<div style='font-size:10px;height:15px;'>{$data['unitprice']}</div>
						</a>
					</span>	
				{/foreach}
				</div>
			</div>	
		</div> <!-- Product Block-->
		
		
		<div id='paymentBlock' style='display:none;' class="span7">
			<center><h3>PAYMENT</h3></center>
			<div class="col-md-6 col-md-push-1">
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<td><button class="btn btn-large btn-block" id='backBtn' type="button">Back</button></td>
							<td><button class="btn btn-large btn-block" style="float:right;display:none;" id='proceed' onclick="proceed();" type="button">Proceed</button></td>
						</tr>
					</thead>
					<tbody id='tblbody1'>
						<tr>
							<td><button class="btn btn-large btn-block" type="button" id='cashBtn' style="background: #e2e2e2;">CASH</button></td>
							<td id='paymentCash' style="font-size:x-large;"></td>
						</tr>
					</tbody>
					<tbody id='' style='display:none;'>
						<table class="table table-striped table-bordered" id='tblbody2' style=''>
							<thead>
								<tr>
									<th width='30%' style="font-size:large;">Due</th>
									<th width='30%' style="font-size:large;">Tendered</th>
									<th width='30%' style="font-size:large;">Change</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td id='dueField' style="font-size: x-large;text-align:center;"></td>
									<td><input type='text' id='tenderedField' name="tenderedField" readonly value='' style="font-size: x-large;"/></td>
									<td id='changeField' style="font-size: x-large;text-align:center;"></td>
								</tr>
								
								<tr>
								<td style="background-color: #f9f9f9;"></td>
								<td style="background-color: #f9f9f9;">
									<div class="numpad">
										<center>
											<table class="table listview-table table-bordered" id="keypad" cellpadding="5" cellspacing="3" style="width:10px;">
												<tr>
													<td data-id="1" onclick="addCode(this);"><button class="btn addButton" style="width:54px;height:54px; background:#bfb9b9; font-size:x-large;" >1</button></td>
													<td data-id="2" onclick="addCode(this);"><button class="btn addButton" style="width: 54px;height: 54px; background:#bfb9b9; font-size: x-large;" >2</button></td>
													<td data-id="3" onclick="addCode(this);"><button class="btn addButton" style="width: 54px;height: 54px; background:#bfb9b9; font-size: x-large;" >3</button></td>
													<td data-id="{$CURRENCY_SETTINGS[0]}" onclick="addCode(this);"><button class="btn addButton" style="width: 75px;height: 54px; background:#bfb9b9; font-size: large;" >{'+'}{$CURRENCY_SETTINGS[0]}</button></td>
												</tr>
												<tr>
													<td data-id="4" onclick="addCode(this);"><button class="btn addButton" style="width: 54px;height: 54px; background:#bfb9b9; font-size: x-large;" >4</button></td>
													<td data-id="5" onclick="addCode(this);"><button class="btn addButton" style="width: 54px;height: 54px; background:#bfb9b9; font-size: x-large;" >5</button></td>
													<td data-id="6" onclick="addCode(this);"><button class="btn addButton" style="width: 54px;height: 54px; background:#bfb9b9; font-size: x-large;" >6</button></td>
													<td data-id="{$CURRENCY_SETTINGS[1]}" onclick="addCode(this);"><button class="btn addButton" style="width: 75px;height: 54px; background:#bfb9b9; font-size: large;" >{'+'}{$CURRENCY_SETTINGS[1]}</button></td>
												</tr>
												<tr>
													<td data-id="7" onclick="addCode(this);"><button class="btn addButton" style="width: 54px;height: 54px; background:#bfb9b9; font-size: x-large;" >7</button></td>
													<td data-id="8" onclick="addCode(this);"><button class="btn addButton" style="width: 54px;height: 54px; background:#bfb9b9; font-size: x-large;" >8</button></td>
													<td data-id="9" onclick="addCode(this);"><button class="btn addButton" style="width: 54px;height: 54px; background:#bfb9b9; font-size: x-large;" >9</button></td>
													<td data-id="{$CURRENCY_SETTINGS[2]}" onclick="addCode(this);"><button class="btn addButton" style="width: 75px;height: 54px; background:#bfb9b9; font-size: large;" >{'+'}{$CURRENCY_SETTINGS[2]}</button></td>
												</tr>
												<tr>
													<td data-id="." onclick="addCode(this);"><button class="btn addButton" style="width: 54px;height: 54px; background:#bfb9b9; font-size: x-large;" >.</button></td>
													<td data-id="0" onclick="addCode(this);"><button class="btn addButton" style="width: 54px;height: 54px; background:#bfb9b9; font-size: x-large;" >0</button></td>
													<td data-id="C" onclick="addCode(this);"><button class="btn addButton" style="width: 54px;height: 54px; background:#bfb9b9; font-size: x-large;" >C</button></td>
													<td data-id="B" onclick="addCode(this);"><button class="btn addButton" style="width: 75px;height: 54px; background:#bfb9b9; font-size: large;" ><-</button></td>
												</tr>
											</table>
										</center>
									</div>
								</td>
								<td style="background-color: #f9f9f9;"></td>
							</tr>
							</tbody>
						</table>
					</tbody>
				</table>
			</div>
		</div> <!-- Payment Block-->	
	</div>	<!-- Cart Block-->

	
	<div class="col-md-7 col-md-offset-2" id='receiptBLock' style="text-align: center;display:none;">
		<!--<span><button class="btn btn-large btn-block" type="button">Print Receipt</button></span>
		<span><button class="btn btn-large btn-block" type="button" onclick="location.href='index.php?module=PointSale&view=List'" >Exit</button></span>-->
		<div id="receipt_print">
			<span><button class="btn btn-large btn-block" type="button" onclick="location.href='index.php?module=PointSale&view=List'" >Exit</button></span>	
		</div>
		
		<div class="container" id='receipt'>
		<div class="row">
			<div class="col-xs-8" style="align:center;">
				<div class="row" id='receipt_no'>
					
				</div>

				<div class="row" style="margin-left:0px;">
					<div class="text-center">
						<h1>Receipt</h1>
					</div>
					</span>
					<table class="table table-hover">
						<thead>
							<tr>
								<th>Product</th>
								<th>Qty</th>
								<th class="text-center">Price</th>
								<th class="text-center">Total</th>
								{if $TAX_SETTINGS eq '1'}
								<th class="text-center">Total With Tax</th>
								{/if}
							</tr>
						</thead>
						<tbody id='receiptTable'>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
		</div>
	</div>
	
	
	<div class="col-md-6 col-md-offset-3" id='posContact' style="text-align: center;display:none;">
	<div id="dialog-form" title="Create Contact">
		<p class="validateTips">All form fields are required.</p>

		<form>
			<fieldset>
				<label for="name">Name</label>
				<input type="text" name="name" id="name" value="" class="input-large ">
				
				<label for="mobile">Mobile</label>
				<input type="text" name="mobile" id="mobile" value="" class="input-large">
				<label for="city">City</label>
				<input type="text" name="city" id="city" value="" class="input-large">
				<label for="state">State</label>
				<input type="text" name="state" id="state" value="" class="input-large">
				<label for="country">Country</label>
				<input type="text" name="country" id="country" value="" class="input-large">

				<!-- Allow form submission with keyboard without duplicating the dialog button -->
				<input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
			</fieldset>
		</form>
	</div>
	<button id="create-user">Add Customer</button>
		<table class="table table-striped table-bordered" id="idReportDataTable"   >
				<thead>
					<tr>
						<th class="idThNameRow">Name</th>
						<th class="idThNameRow">Phone</th>
					</tr>
				</thead>
				<tfoot>
					<tr id="idTrTfoot">
						<th></th>
						<th></th>
					</tr>
				</tfoot>
				<tbody id='users'>				
					{foreach from=$CONTACT key=k item=data}
						<tr onclick="getContact({$data['contactid']});">
							<td id='contact_{$data["contactid"]}'>{$data['name']}</td>
							<td>{$data['mobile']}</td>
						</tr>
					{/foreach}					
				</tbody>
		</table>
	</div>
	
</div>
	

{literal}
<script type="text/javascript" src="libraries/bootstrap/js/bootbox.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js" ></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js" ></script>
<script>
function addCode(elem){
	var dataId = $(elem).data("id");
	
		if(dataId == 'C'){
			$('input[name="tenderedField"]').attr('value', 0);
			$("#changeField").html('');
			
		}else if(dataId == '10' || dataId == '20' || dataId == '50' || dataId == '100' || dataId == '200' || dataId == '500' || dataId == '1000'){
			var getVal = $('input[name="tenderedField"]').val();
			if(getVal == ''){
				$('input[name="tenderedField"]').val(0);
			}
			getVal = $('input[name="tenderedField"]').val();
			var addten = parseFloat(getVal) + parseFloat(dataId);
			$('input[name="tenderedField"]').attr('value', addten);
			var dInput = $("#tenderedField").val();
		
			var total = parseFloat($("#total").html());
			var returnVal = dInput - total;
			$("#changeField").html(returnVal.toFixed(2));
			
			if(dInput >= total){
				$("#changeField").css('-webkit-text-fill-color','green');
				$("#proceed").show();
			}else{
				$("#changeField").css('-webkit-text-fill-color','red');
				$("#proceed").hide();
			}
			
		}else if(dataId == 'B'){
			var fieldval = $('input[name="tenderedField"]').val();
			var backspace = fieldval.slice(0,-1);
			if(backspace == ''){
				backspace = 0;
			}
			$("#tenderedField").attr('value', backspace);
			var dInput = $("#tenderedField").val();
		
			var total = parseFloat($("#total").html());
			var returnVal = dInput - total;
			$("#changeField").html(returnVal.toFixed(2));
			
			if(dInput >= total){
				$("#changeField").css('-webkit-text-fill-color','green');
				$("#proceed").show();
			}else{
				$("#changeField").css('-webkit-text-fill-color','red');
				$("#proceed").hide();
			}
			
		}else{
			var getVal = $("#tenderedField").val();

			if(getVal == '0'){
				$("#tenderedField").val('');
			}
			var updatef = $("#tenderedField").val()+""+dataId;
			$("#tenderedField").attr('value', updatef);
			var dInput = $("#tenderedField").val();
		
			var total = parseFloat($("#total").html());
			var returnVal = dInput - total;
			$("#changeField").html(returnVal.toFixed(2));
			
			if(dInput >= total){
				$("#changeField").css('-webkit-text-fill-color','green');
				$("#proceed").show();
			}else{
				$("#changeField").css('-webkit-text-fill-color','red');
				$("#proceed").hide();
			}
		}		
}

function plus(id){
	var productid = id;
	var getqty = parseFloat($("#qty_"+id).val());
	var add = getqty + 1;
	var stock = parseFloat($("#text_"+id).val());
	if(add > stock){
		alert("Quantity is not Present");
	}else{
		$("#qty_"+id).val(add);
	}
	updateStock(stock,productid);
	updatePrice();
	
}

function minus(id){
	var productid = id;
	var getqty = parseFloat($("#qty_"+id).val());
	var add = getqty - 1;
	if(add > 0){
		var stock = parseFloat($("#text_"+id).val());
		if(add > stock){
			alert("Quantity is not Present");
		}else{
			$("#qty_"+id).val(add);
		}
		updateStock(stock,productid);
		updatePrice();
	}
	
	
}

$(document).ready(function(){
	
	$("#getCategory").on('change',function(){
		//alert(this.value);
		var category = this.value;
			var params = {};
			params['module'] = 'PointSale';
			params['action'] = 'IndexAjax';
			params['mode'] = 'getCatVal';
			params['category'] = category;
			AppConnector.request(params).then(
				function(data) {
					if (data.success){
						var val = jQuery.parseJSON(data.result.result);
						$("#ajaxLoadP").html('');
						jQuery.each(val, function( index, value ) {
						
						var image = 'no-image.png';
						
						if(value['path'] != null || value['attachmentsid'] != null || value['name'] != null){
							image = value['path']+""+value['attachmentsid']+"_"+value['name'];		
						}
						var productname = value['productname'];
						var product_name = productname.substring(0, 10)+'...';
						
						if(parseFloat(value['qtyinstock']).toFixed(2) <= 0.00){
							
							var styles = 'border-color:red;border-width:1px;border-style:solid;';
						}else{
							var styles = '';
						}
						
						$("#ajaxLoadP").append("<span class='col-sm-1' data-product-id='"+value['productid']+"'><a title='' id='"+value['productid']+"' class='customProduct' onclick='customProduct("+value['productid']+");' ><img class='thumbnail' id='"+value['productid']+"' src='"+image+"' height='50' width='40' style='"+styles+"'><div style='font-size:10px;height:15px;' title='"+value['productname']+"' >"+product_name+"</div><div style='font-size:10px;height:15px;'>"+value['unitprice']
						+"</div></a></span>");
						});
					}
						
				},
				function(error) {
					//progressIndicatorElement.progressIndicator(hideparams);
					alert("Very serious error. Investigate!!");
				}	
			);
	});
	var dialog, form,

			// From http://www.whatwg.org/specs/web-apps/current-work/multipage/states-of-the-type-attribute.html#e-mail-state-%28type=email%29
			emailRegex = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/,
			name = $( "#name" ),
			mobile = $( "#mobile" ),
			//street = $( "#street" ),
			country = $( "#country" ),
			city = $( "#city" ),
			state = $( "#state" ),
			allFields = $( [] ).add( name ).add( mobile ).add( city ).add( state ).add( country ),
			tips = $( ".validateTips" );

		function updateTips( t ) {
			tips
				.text( t )
				.addClass( "ui-state-highlight" );
			setTimeout(function() {
				tips.removeClass( "ui-state-highlight", 1500 );
			}, 500 );
		}

		function addUser() {
			var valid = true;
			allFields.removeClass( "ui-state-error" );
				$.ajax({
					url: "index.php?module=PointSale&action=Create&name="+name.val()+"&mobile="+mobile.val()+"&state="+state.val()+"&city="+city.val()+"&country="+country.val(), 
					dataType: 'json', 
					success: function(data){
						var id = data.data1;
						$("#contactid").val(id);
						$("#contact_name").html(name.val());
						$("#cartBlock").show();
						$("#back").css('display','none');
						$("#posContact").css('display','none');
						$("#selectedcontact").show();
					
						$("#users").append( "<tr onclick='getContact("+id+");'><td id='contact_"+id+"'>"+name.val()+"</td><td >"+mobile.val()+ "</td></tr>" );
						dialog.dialog( "close" );
						return valid;
					}
				});	
		}

		dialog = $( "#dialog-form" ).dialog({
			autoOpen: false,
			height: 400,
			width: 350,
			margintop: 150,
			modal: true,
			buttons: {
				"Create Customer": addUser,
				Cancel: function() {
					dialog.dialog( "close" );
				}
			},
			close: function() {
				form[ 0 ].reset();
				allFields.removeClass( "ui-state-error" );
			}
		});

		form = dialog.find( "form" ).on( "submit", function( event ) {
			event.preventDefault();
			addUser();
		});

		$( "#create-user" ).button().on( "click", function() {
			dialog.dialog( "open" );
		});
	//$("#selectedcontact").hide();
	$('#idReportDataTable').DataTable({
		
	});
	$("#paymentBtn").on('click',function(){
		$("#paymentCash").html($("#total").html());
		$("#paymentBlock").show();
		$("#productsBlock").hide();
		$("#tblbody1").show();
		$("#tblbody2").hide();
		
		$("#cashBtn").on('click',function(){
			$("#tblbody1").hide();
			$("#tblbody2").show();
			$("#dueField").html($("#total").html());
			
		});
	});
	
	$("#backBtn").on('click',function(){
		$("#productsBlock").show();
		$("#paymentBlock").hide();
	});
	
	$("#contactBtn").on('click',function(){
		$("#cartBlock").hide();
		$("#paymentBtn").hide();
		$("#posContact").show();
		$("#paymentBtn").show();
		//$("#back").css('display','block');
	});
	
	$("#cancelBtn").on('click',function(){
		var answer = confirm('Are you sure you want to Cancel this?');
			if (answer){
			  window.location.reload();
			}
	});
	
	$("#tenderedField").on('keyup',function(){
		
		var dInput = parseFloat(this.value);
		var total = parseFloat($("#total").html());
		var returnVal = dInput - total;
		$("#changeField").html(returnVal.toFixed(2));
		
		if(dInput >= total){	
			$("#proceed").show();
		}else{
			$("#proceed").hide();
		}
	});
	
	
});

function search(ele) {
    if(event.key === 'Enter') {
        alert(ele.value);
		$("#totalTable").show();
		var code_val =  ele.value;
		var  params = {};
		params['module'] = 'PointSale';
		params['action'] = 'IndexAjax';
		params['mode'] = 'getProductWithCode';
		params['code'] = code_val;
		AppConnector.request(params).then(
			function(data) {
				if (data.success){
					var p_array = jQuery.parseJSON(data.result.result);
					if(p_array.length != 0){
						var p_data = p_array[0];
						//var productid = p_data.productid;
						var total = p_data.unit_price;
						var price = parseFloat(total).toFixed(2);
						var quantity = parseFloat(p_data.qtyinstock).toFixed(2);
						
						var product_tax = p_data.taxpercent
						if(product_tax == null){
							var taxpercent = parseFloat(0.00).toFixed(2);
						}else{
							var taxpercent = parseFloat(product_tax).toFixed(2);
						}
							
						if(quantity != 0 && quantity >= 1.00 ){
							jQuery("#producttable").append("<tr class='product' id='product"+p_data.productid+"' ><input type='hidden' name='taxpercent' id='taxpercent_"+p_data.productid+"' value="+taxpercent+"/><td>"+p_data.productname+"</td> <td>"+price+"</td><input type='hidden' id='text_"+p_data.productid+"' value="+quantity+"/><td id='tqty_"+p_data.productid+"'>"+quantity+"</td> <td style='cursor: pointer;'  class='qty' ><i onclick='plus("+p_data.productid+");' class='icon-plus'></i>&nbsp;&nbsp;<input style='width:35px;display:inherit;' type='number' name='qty_"+p_data.productid+"' value='1' min=0 id='qty_"+p_data.productid+"'/>&nbsp;&nbsp;<i class='icon-minus' onclick='minus("+p_data.productid+");'></i></td>  <input type='hidden' id='actual"+p_data.productid+"' value="+price+"/><td class='price' id='price_"+p_data.productid+"'>"+price+" </td><td><a class='deleteRecordButton' onclick='deleteBH("+p_data.productid+");'><i title='Delete' class='fa fa-trash alignMiddle'></i></a></td> </tr>");
							$("#total").html(price);
							
							updateStock(quantity,p_data.productid);
							updatePrice();
							$("#paymentBtn").show();
							
						}else{
							alert("Quantity is not Sufficient");
						}
					}else{
						alert("No product available");
					}
					
				}
				$('input[name="barcode_num"]').attr('value','');
			},
			function(error) {
				//progressIndicatorElement.progressIndicator(hideparams);
				alert("Very serious error. Investigate!!");
			}	
		);
    }
}

function customProduct(id){
	
		$("#totalTable").show();
		var productid = id;
		//alert(productid);
		if($("#product"+productid).length == 0) { // If prduct is alrady selected
			//alert('Product is not present');
		
			//$("#customProduct").val(productid).find("option[value=" + productid +"]").attr('selected', true);
			var params = {};
			params['module'] = 'PointSale';
			params['action'] = 'IndexAjax';
			params['mode'] = 'addItem';
			params['productid'] = productid;
			//console.log(params);
			AppConnector.request(params).then(
				function(data) {
					//console.log(data);
					if (data.success){
						var p_array = jQuery.parseJSON(data.result.result);
						var p_data = p_array[0];
						//console.log();
						var total = p_data.unit_price;
						var price = parseFloat(total).toFixed(2);
						var quantity = parseFloat(p_data.qtyinstock).toFixed(2);
						
						var product_tax = p_data.taxpercent
						if(product_tax == null){
							var taxpercent = parseFloat(0.00).toFixed(2);
						}else{
							var taxpercent = parseFloat(product_tax).toFixed(2);
						}
						
						if(quantity != 0 && quantity >= 1.00){
							jQuery("#producttable").append("<tr class='product' id='product"+p_data.productid+"' ><input type='hidden' name='taxpercent' id='taxpercent_"+p_data.productid+"' value="+taxpercent+"/><td style='font-size:larger;'>"+p_data.productname+"</td> <td style='font-size:larger;'>"+price+"</td><input type='hidden' id='text_"+p_data.productid+"' value="+quantity+"/><td style='font-size:larger;' id='tqty_"+p_data.productid+"'>"+quantity+"</td> <td style='cursor: pointer;'font-size:larger;' class='qty' ><i onclick='plus("+productid+");' class='fa fa-plus'></i>&nbsp;&nbsp;<input style='width:40px;display:inherit;font-size:larger;' type='number' name='qty_"+p_data.productid+"' value='1' min=0 id='qty_"+p_data.productid+"'/>&nbsp;&nbsp;<i class='fa fa-minus' onclick='minus("+productid+");'></i></td>  <input type='hidden' id='actual"+p_data.productid+"' value="+price+"/><td style='font-size:larger;' class='price' id='price_"+p_data.productid+"'>"+price+" </td><td><a class='deleteRecordButton' onclick='deleteBH("+p_data.productid+");'><i title='Delete' class='fa fa-trash alignMiddle'></i></a></td> </tr>");
							$("#total").html(price);
							
							updateStock(quantity,p_data.productid);
							updatePrice();
							$("#paymentBtn").show();
							
						}else{
							alert("Quantity is not Sufficient");
						}
						
						
					}
				},
				function(error) {
					//progressIndicatorElement.progressIndicator(hideparams);
					alert("Very serious error. Investigate!!");
				}	
			);	
			
		}else{
			//alert('Product is already selected');
			var getqty = $("#qty_"+productid).val();
			var gettqty = $("#tqty_"+productid).html();
			if(gettqty == 0){
				alert("Sorry ! This much of quantity is not available");
			}else{
				var add = parseFloat(getqty) + 1;
				$("#qty_"+productid).attr('value',add);
				var stock = $("#text_"+productid).val();
				updateStock(stock,productid);
			}
			//alert(2);
			updatePrice();
		}

}

function updateStock(stock,productid){
	var stock = parseFloat(stock);
	var id  = productid;
	var qty = $('#qty_'+id).val();
	var tqty = $('#tqty_'+id).html();
	var price = parseFloat($('#actual'+id).val());
	
	var deduct_qty = stock - qty;
	$("#tqty_"+id).html(deduct_qty);
	var taxpercent = parseFloat($('#taxpercent_'+id).val());
	//console.log(taxpercent);
	var taxvalue = parseFloat(((qty * price)* taxpercent)/100);
	var totalval = (qty * price) + taxvalue;
	$('#price_'+id).html(parseFloat(totalval).toFixed(2));
							
}

function updatePrice(){
	var IDs = [];
	$(".product").find(".price").each(function(){
		var price = $("#"+this.id).html();

		IDs.push(price); 
	});
	$("#total").html(IDs.reduce(function(prev, cur) {
		return parseFloat(parseFloat(prev) + parseFloat(cur)).toFixed(2);
	}));

}

function cellOnclick(id,quantity,price){
	var id  = id;
	var my_text=prompt('Enter Quantity',1);
	
	var tqty = $('#text_'+id).val();
	if (my_text > tqty) {
		//alert("Sorry ! This much of quantity is not available");
		my_text = 1;
	}
    if(my_text)
	$('#qty_'+id).val(my_text);
	$('#price_'+id).html(parseFloat(my_text*price).toFixed(2));
	var stock = $("#text_"+id).val();
	
	updateStock(stock,id);
	updatePrice();
}

function deleteBH(id){
	$("#product"+id).remove();
	var tbody = $("#productdiv tbody");
	if (tbody.children().length == 0) {
		$("#total").html('');
		$("#totalTable").hide();
		$("#paymentBtn").hide();
	}
	updatePrice();
	$("#paymentCash").html($("#total").html());
}

function getContact(id){
	$("#contactid").val(id);
	$("#contact_name").html('');
	$("#cartBlock").show();
	$("#payment").show();
	$("#back").css('display','none');
	$("#posContact").css('display','none');
	$("#contact_name").html($("#contact_"+id).html());
	$("#selectedcontact").show();
	
}

function proceed(){
	if($("#contactid").val() == ''){
		alert("Please select/add the Customer");
	}else{
		$("#cartBlock").hide();
		$("#productsBlock").hide();
		$("#paymentBlock").hide();
		$("#receiptBLock").show();
	
	var TableData = new Array();
	
	$("#producttable").find('tr').each(function (i, el) {
		var id = this.id;
		productId = id.replace('product','');
		var $tds = $(this).find('td'),
		productName = $tds.eq(0).text(),
		price = $tds.eq(1).text(),
		totalQuantity = $tds.eq(2).text(),
		selectedQuantity = $("#qty_"+productId).val(),
		taxpercent = parseFloat($("#taxpercent_"+productId).val()).toFixed(2);
		
		TableData[i]={
			"productId" : productId
			,"productName" : productName
			, "price" : price
			, "totalQuantity" : totalQuantity
			, "selectedQuantity" : selectedQuantity
			, "taxpercent" : taxpercent
		}
	});
	//console.log(TableData);
	
	var params1 = {};
	params1['module'] = 'PointSale';
	params1['action'] = 'IndexAjax';
	params1['mode'] = 'saveData';
	params1['tabledata'] = JSON.stringify(TableData);
	params1['total'] = $("#dueField").html();
	params1['paid'] = $("#tenderedField").val();
	params1['return'] = $("#changeField").html();
	params1['contactid'] = $("#contactid").val();

	AppConnector.request(params1).then(
		function(data) {
			$('#receipt_print').append("<span><button class='btn btn-large btn-block' type='button' onclick='downloadPopup("+data.result.id+");'>Print Receipt</button></span>");
			$("#receipt_no").append("<div class='span2'><p><strong></strong></p></div><div class='span4'><p><em>Date: "+data.result.date+"</em></p><p><em>Receipt #: "+data.result.sale+"</em></p></div>");
			
			var totalvaluewithtax = 0.00;
			var totalvaluewithouttax = 0.00;
			
			for (i = 0; i < TableData.length; i++) {
			var qty = TableData[i]['selectedQuantity'];
			var price = TableData[i]['price'];
			var taxpercent = TableData[i]['taxpercent'];
			var total = price * qty;
			
			totalvaluewithouttax = totalvaluewithouttax + total;
			var totalvalue = parseFloat(total + (total * (taxpercent /100))).toFixed(2);
			totalvaluewithtax = totalvaluewithtax + (total + (total * (taxpercent /100)));
			
				if($('input[name="tax_settings"]').val() == 1){
					$("#receiptTable").append("<tr><td class='col-md-7'><em>"+TableData[i]['productName']+"</em></h4></td><td class='col-md-1' style='text-align: center'>"+qty+"</td><td class='col-md-1 text-center'>"+price+"</td><td class='col-md-1 text-center'>"+total+"</td><td class='col-md-1 text-center'>"+totalvalue+"</td></tr>");
				}else{
					$("#receiptTable").append("<tr><td class='col-md-7'><em>"+TableData[i]['productName']+"</em></h4></td><td class='col-md-1' style='text-align: center'>"+qty+"</td><td class='col-md-1 text-center'>"+price+"</td><td class='col-md-1 text-center'>"+total+"</td></tr>");
				}
			}
			
			var tenderedfieldVal = $("#tenderedField").val();
			var totalSellQty = data.result.total_sel_qty;
			//console.log(parseFloat(totalSellQty).toFixed(2));
			if($('input[name="tax_settings"]').val() == 1){
					$("#receiptTable").append("<tr><td></td><td colspan=3 class='text-right'><p><strong>Total Amount: </strong></p></td><td class='text-center'><p><strong>"+$("#dueField").html()+"</strong></p></td></tr><tr><td></td><td colspan=3 class='text-right'><p><strong>Cash Tendered: </strong></p></td><td class='text-center'><p><strong>"+parseFloat(tenderedfieldVal).toFixed(2)+"</strong></p></td></tr><tr><td></td><td colspan=3 class='text-right'><p><strong>Change Returned: </strong></p></td><td class='text-center'><p><strong>"+$("#changeField").html()+"</strong></p></td></tr><tr><td></td><td colspan=3 class='text-right'><p><strong>Total Items: </strong></p></td><td class='text-center'><p><strong>"+parseFloat(totalSellQty).toFixed(2)+"</strong></p></td></tr>");
			}else{
				
				$("#receiptTable").append("<tr><td></td><td colspan=2 class='text-right'><p><strong>Total Amount: </strong></p></td><td class='text-center'><p><strong>"+(totalvaluewithouttax).toFixed(2)+"</strong></p></td></tr><tr><td></td><td colspan=2 class='text-right'><p><strong>Total With Tax: </strong></p></td><td class='text-center'><p><strong>"+(totalvaluewithtax).toFixed(2)+"</strong></p></td></tr><tr><td></td><td colspan=2 class='text-right'><p><strong>Cash Tendered: </strong></p></td><td class='text-center'><p><strong>"+parseFloat(tenderedfieldVal).toFixed(2)+"</strong></p></td></tr><tr><td></td><td colspan=2 class='text-right'><p><strong>Change Returned: </strong></p></td><td class='text-center'><p><strong>"+$("#changeField").html()+"</strong></p></td></tr><tr><td></td><td colspan=2 class='text-right'><p><strong>Total Items: </strong></p></td><td class='text-center'><p><strong>"+parseFloat(totalSellQty).toFixed(2)+"</strong></p></td></tr>");
			}
		},
		function(error) {
			alert("Very serious error. Investigate!!");
		}	
	);	
		//$("#receiptTable").append("<tr><td></td><td></td><td class='text-right'><p><strong>Total: </strong></p></td><td class='text-center'><p><strong>"+$("#dueField").html()+"</strong></p></td></tr>");
		
	}
}
	function downloadPopup(id){
		var URL = 'index.php?module=PointSale&view=Print&mode=GetPrintReport&record='+id;
		window.open(URL,'_blank');
	}
</script>
{/literal}
 