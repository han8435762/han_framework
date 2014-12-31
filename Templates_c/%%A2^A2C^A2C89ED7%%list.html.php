<?php /* Smarty version 2.6.17, created on 2014-12-31 16:05:36
         compiled from User/list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'User/list.html', 10, false),)), $this); ?>
<table class="table table-bordered">
    <tr>
        <th>ID</th><th>用户名</th><th>所属组</th><th><a href="javascript:void(0);" id="order_add_time" data-order="<?php echo $this->_tpl_vars['order']; ?>
" onclick="order(this.id);">添加时间[点击排序]</a></th><th>操作</th>
    </tr>
    <?php $_from = ($this->_tpl_vars['users']); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
    <tr>
        <td><?php echo $this->_tpl_vars['item']['user_id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['item']['user_name']; ?>
</td>
        <td><?php echo $this->_tpl_vars['item']['user_group']['group_name']; ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%I:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%I:%S")); ?>
</td>
        <td><a data-user-id="<?php echo $this->_tpl_vars['item']['user_id']; ?>
" data-user-name="<?php echo $this->_tpl_vars['item']['user_name']; ?>
" id="edit_<?php echo $this->_tpl_vars['item']['user_id']; ?>
" data-group-id="<?php echo $this->_tpl_vars['item']['user_group']['group_id']; ?>
" href="javascript:void(0);" onclick="edit_user(this.id);" >编辑</a>|<a href="javascript:void(0);" onclick="delete_user(<?php echo $this->_tpl_vars['item']['user_id']; ?>
);">删除</a></td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
</table>
<script>
    function handle_result(json_string) {
        var json_obj = eval("(" + json_string + ")");
        if (json_obj.result) {
            $('#notice').attr('class', 'label label-success');
            $('#notice').html('提交成功');
            setTimeout(function () {window.location.reload();},500);
        } else {
            $('#notice').attr('class', 'label label-warning');
            $('#notice').html(json_obj.message);
        }
    }

    function add_user() {

        $('#user_name').val('');
        $('#password').val('');

        $('#modal_label').html('添加用户');
        $('#modal_form').attr('action', '<?php echo @ROOT_DOMAIN; ?>
/?c=user&a=add');
        $('#add_modal').modal('toggle');
    }

    function edit_user(id) {
        var obj = $('#' + id);
        var user_id = obj.attr('data-user-id');
        var user_name = obj.attr('data-user-name');
        var user_group_id = obj.attr('data-group-id');

        $('#user_id').val(user_id);
        $('#user_name').val(user_name);
        $("#user_group option[value='" + user_group_id + "']").attr('selected', true);

        $('#modal_label').html('编辑用户');
        $('#modal_form').attr('action', '<?php echo @ROOT_DOMAIN; ?>
/?c=user&a=edit');
        $('#add_modal').modal('toggle');
    }

    function delete_user(user_id) {
        if (confirm('是否要删除这条数据？')) {
            $.post('<?php echo @ROOT_DOMAIN; ?>
/?c=user&a=delete', {user_id:user_id}, function(json_string) {
                var json_obj = eval("(" + json_string + ")");
                if (json_obj.result) {
                    alert('删除成功');
                    window.location.reload();
                } else {
                    alert('删除失败');
                    window.location.reload();
                }
            });
        }
    }

    function order(id) {
        var order = $('#' + id).attr('data-order');
        console.log(order);
        $.get('<?php echo @ROOT_DOMAIN; ?>
', {is_ajax:'1', c:'user', a:'index', order:order}, function(html) {
            if (html) {
                $('#user_list').html(html);
            }
        });
    }
</script>

<form action="<?php echo @ROOT_DOMAIN; ?>
/?c=user&a=add" id="modal_form" method="POST" target="inner_iframe">
    <input type="hidden" name="iframe_callback" id="iframe_callback" value="handle_result"/>
    <input type="hidden" name="user_id" id="user_id" value="" />
    <div id="add_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="modal_label">添加用户</h3>
        </div>
        <div class="modal-body">
            <table>
                <tr>
                    <td>用户名称：</td><td><input type="text" name="user_name" id="user_name"/></td>
                </tr>
                <tr>
                    <td>用户密码：</td><td><input type="text" name="password" id="password"/></td>
                </tr>
                <tr>
                    <td>用户组：</td>
                    <td>
                        <select name="user_group" id="user_group" class="form-control">
                            <?php $_from = ($this->_tpl_vars['user_groups']); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
                            <option value="<?php echo $this->_tpl_vars['item']['group_id']; ?>
"><?php echo $this->_tpl_vars['item']['group_name']; ?>
</option>
                            <?php endforeach; endif; unset($_from); ?>
                        </select>
                    </td>
                </tr>
            </table>
            <span class="label label-warning" id="notice"></span>
        </div>
        <div class="modal-footer">
            <input type="submit" class="btn btn-primary" value="submit" />
        </div>
    </div>
</form>

<iframe style="display:none;" name="inner_iframe"></iframe>