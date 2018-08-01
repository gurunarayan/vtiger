<?php /* Smarty version Smarty-3.1.7, created on 2018-07-27 06:47:56
         compiled from "/var/www/html/vtigercrmvt/includes/runtime/../../layouts/v7/modules/Settings/LoginPage/VtigressNewThemeCreate.tpl" */ ?>
<?php /*%%SmartyHeaderCode:170891285b5ac01c4148a1-06872563%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9afbe26958023b40de14acfbfcdbc90e7ddb720b' => 
    array (
      0 => '/var/www/html/vtigercrmvt/includes/runtime/../../layouts/v7/modules/Settings/LoginPage/VtigressNewThemeCreate.tpl',
      1 => 1517827672,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '170891285b5ac01c4148a1-06872563',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'ROWS' => 0,
    'COLUMNS' => 0,
    'x' => 0,
    'y' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5b5ac01c5cefe',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5b5ac01c5cefe')) {function content_5b5ac01c5cefe($_smarty_tpl) {?>
<div class="widget_header row-fluid"><div class="span6"><h3>Login Page Generation</h3></div></div><hr/><form name="LoginPage" action="index.php?module=LoginPage&parent=Settings&view=CustomLoginPage&x=<?php echo $_smarty_tpl->tpl_vars['ROWS']->value;?>
&y=<?php echo $_smarty_tpl->tpl_vars['COLUMNS']->value;?>
" target="_blank" method="post" class="form-horizontal" id="LoginPage" enctype="multipart/form-data"><div class="container-fluid"><div class="contents"><br><table class="table table-bordered"><tbody><tr class="listViewActionsDiv"><th colspan="5">Header Information</th></tr><tr class="container"><td class="fieldLabel medium"><span class="redColor">*</span>Login Page Name</td><td class="fieldValue medium"><input type='text' name='loginpagename' data-validation-engine="validate[required]" value=''/><br/><b>Note:</b>Please don't use spaces in name</td><td colspan='2'></td></tr></tbody></table><br><?php if ($_smarty_tpl->tpl_vars['ROWS']->value!=''&&$_smarty_tpl->tpl_vars['COLUMNS']->value!=''){?><table class="table table-bordered"><tbody><tr class="container" ><td class="fieldLabel medium"><label class="muted pull-right marginRight10px">Logo Image</label></td><td class="fieldValue medium" colspan="3"><div class="row-fluid"><span class="span10"><input type='file' name='logo' class='logo' id='idLogo' ></span></div></td></tr><?php $_smarty_tpl->tpl_vars['x'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['x']->step = 1;$_smarty_tpl->tpl_vars['x']->total = (int)ceil(($_smarty_tpl->tpl_vars['x']->step > 0 ? $_smarty_tpl->tpl_vars['ROWS']->value+1 - (1) : 1-($_smarty_tpl->tpl_vars['ROWS']->value)+1)/abs($_smarty_tpl->tpl_vars['x']->step));
if ($_smarty_tpl->tpl_vars['x']->total > 0){
for ($_smarty_tpl->tpl_vars['x']->value = 1, $_smarty_tpl->tpl_vars['x']->iteration = 1;$_smarty_tpl->tpl_vars['x']->iteration <= $_smarty_tpl->tpl_vars['x']->total;$_smarty_tpl->tpl_vars['x']->value += $_smarty_tpl->tpl_vars['x']->step, $_smarty_tpl->tpl_vars['x']->iteration++){
$_smarty_tpl->tpl_vars['x']->first = $_smarty_tpl->tpl_vars['x']->iteration == 1;$_smarty_tpl->tpl_vars['x']->last = $_smarty_tpl->tpl_vars['x']->iteration == $_smarty_tpl->tpl_vars['x']->total;?><tr id="<?php echo $_smarty_tpl->tpl_vars['x']->value;?>
" class="container"><?php $_smarty_tpl->tpl_vars['y'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['y']->step = 1;$_smarty_tpl->tpl_vars['y']->total = (int)ceil(($_smarty_tpl->tpl_vars['y']->step > 0 ? $_smarty_tpl->tpl_vars['COLUMNS']->value+1 - (1) : 1-($_smarty_tpl->tpl_vars['COLUMNS']->value)+1)/abs($_smarty_tpl->tpl_vars['y']->step));
if ($_smarty_tpl->tpl_vars['y']->total > 0){
for ($_smarty_tpl->tpl_vars['y']->value = 1, $_smarty_tpl->tpl_vars['y']->iteration = 1;$_smarty_tpl->tpl_vars['y']->iteration <= $_smarty_tpl->tpl_vars['y']->total;$_smarty_tpl->tpl_vars['y']->value += $_smarty_tpl->tpl_vars['y']->step, $_smarty_tpl->tpl_vars['y']->iteration++){
$_smarty_tpl->tpl_vars['y']->first = $_smarty_tpl->tpl_vars['y']->iteration == 1;$_smarty_tpl->tpl_vars['y']->last = $_smarty_tpl->tpl_vars['y']->iteration == $_smarty_tpl->tpl_vars['y']->total;?><td style="padding: 15px;" id="<?php echo $_smarty_tpl->tpl_vars['x']->value;?>
<?php echo $_smarty_tpl->tpl_vars['y']->value;?>
" name="section[]" class="data"><select name="page[]" class="text chzn-select select_page_view" id="<?php echo $_smarty_tpl->tpl_vars['x']->value;?>
<?php echo $_smarty_tpl->tpl_vars['y']->value;?>
"><option value="">Select Value</option><option value="LoginBox">Login Box</option><option value="ImageSlider">Image Slider</option></select><br><div class="content" id="div_<?php echo $_smarty_tpl->tpl_vars['x']->value;?>
<?php echo $_smarty_tpl->tpl_vars['y']->value;?>
"></div></td><?php }} ?></tr><?php }} ?></tbody></table><br><?php }?><table class="table table-bordered"><tbody><tr class="container"><th colspan="5">Footer Information <input type='checkbox' name='footer' id='idCheckboxFooter'> 	</th></tr><tr class="container" ><td class="fieldLabel medium"><label class="muted pull-right marginRight10px">Left</label></td><td class="fieldValue medium"><select name="select_footer_left" class="text chzn-select select_page_view" id="idSelectFooterLeft"><option value="">Select Value</option><option value="Content">Content</option><option value="socialicons">Social Icons</option><option value="WebsiteLinks">Website Links</option></select></td><td class="fieldLabel medium"><label class="muted pull-right marginRight10px">Right</label></td><td class="fieldValue medium"><select name="select_footer_right" class="text chzn-select" id="idSelectFooterRight"><option value="">Select Value</option><option value="Content">Content</option><option value="socialicons">Social Icons</option><option value="WebsiteLinks">Website Links</option></select></td></tr><tr class='container'><td colspan='2' id='idTdLeftFooter'> </td><td colspan='2' id='idTdRightFooter'></td></tr></tbody></table><br><button class="btn btn-success pull-right generate" name="generate"  type="submit"><strong>Preview</strong></button><a class="btn btn-success pull-right save" name="save" id="save" ><strong>Save</strong></a></div></form>
<?php }} ?>