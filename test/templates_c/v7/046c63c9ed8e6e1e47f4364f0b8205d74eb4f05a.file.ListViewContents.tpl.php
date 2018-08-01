<?php /* Smarty version Smarty-3.1.7, created on 2018-07-27 06:47:51
         compiled from "/var/www/html/vtigercrmvt/includes/runtime/../../layouts/v7/modules/Settings/LoginPage/ListViewContents.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3162349575b5ac017e14095-24750300%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '046c63c9ed8e6e1e47f4364f0b8205d74eb4f05a' => 
    array (
      0 => '/var/www/html/vtigercrmvt/includes/runtime/../../layouts/v7/modules/Settings/LoginPage/ListViewContents.tpl',
      1 => 1517823368,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3162349575b5ac017e14095-24750300',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
    'THEMELIST' => 0,
    'WIDTHTYPE' => 0,
    'WIDTH' => 0,
    'i' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5b5ac017e5d4b',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5b5ac017e5d4b')) {function content_5b5ac017e5d4b($_smarty_tpl) {?>
<div class="widget_header row-fluid"><div class="span6"><h3>Login Themes</h3></div></div><hr/><div class="row-fluid"><span class="span8 btn-toolbar"><button class="btn addButton"><a href='index.php?module=LoginPage&parent=Settings&view=NewTheme'><i class="icon-plus"></i>&nbsp;<strong>Add Theme</strong></a></button></span></div><div class="listViewEntriesDiv" style='overflow-x:auto;'><span class="listViewLoadingImageBlock hide modal" id="loadingListViewModal"><img class="listViewLoadingImage" src="<?php echo vimage_path('loading.gif');?>
" alt="no-image" title="<?php echo vtranslate('LBL_LOADING',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"/><p class="listViewLoadingMsg"><?php echo vtranslate('LBL_LOADING_LISTVIEW_CONTENTS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
........</p></span><table class="table table-bordered listViewEntriesTable" id="Themes" width="60%"><thead><tr class="listViewHeaders"><th>S.No</th><th>Theme Name</th><th>Status</th><th>Preview</th><th>Action</th></tr></thead><tbody><?php  $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['i']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['THEMELIST']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['i']->key => $_smarty_tpl->tpl_vars['i']->value){
$_smarty_tpl->tpl_vars['i']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['i']->key;
?><tr><td class="listViewEntryValue <?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
"  width="<?php echo $_smarty_tpl->tpl_vars['WIDTH']->value;?>
%" nowrap><?php echo $_smarty_tpl->tpl_vars['i']->value['id'];?>
</td><td nowrap class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['i']->value['name'];?>
</td><td nowrap class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
"><input type='radio' class="radio" name='themestatus' data-display="<?php echo $_smarty_tpl->tpl_vars['i']->value['name'];?>
" value=""							<?php if ($_smarty_tpl->tpl_vars['i']->value['status']==1){?>checked<?php }?>						/></td><td nowrap class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
"><a target='_blank' class="btn btn-success" href="<?php echo $_smarty_tpl->tpl_vars['i']->value['previewurl'];?>
"><strong>Preview</strong></a></td><td nowrap class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
"><a class="deleteRecordButton" data-name="<?php echo $_smarty_tpl->tpl_vars['i']->value['name'];?>
"><i title="Delete" class="icon-trash alignMiddle"></i></a></td></tr><?php } ?></tbody></table></div><?php }} ?>