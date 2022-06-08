<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>layui</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/Public/lib/layui-v2.6.3/css/layui.css" media="all">
    <link rel="stylesheet" href="/Public/css/public.css" media="all">
</head>
<body>
<div class="layuimini-container">
    <div class="layuimini-main">
        <input type="hidden" id="uid" value="<?php echo ($id); ?>">
        <table class="layui-hide" id="currentTableId2" lay-filter="currentTableFilter"></table>

        <script type="text/html" id="currentTableBar">
            <a class="layui-btn layui-btn-normal layui-btn-xs data-count-edit" lay-event="edit">编辑</a>
            <a class="layui-btn layui-btn-xs layui-btn-danger data-count-delete" lay-event="delete">删除</a>
            <a class="layui-btn layui-btn-warm   layui-btn-xs data-count-edit" lay-event="provide">发放</a>
            <a class="layui-btn layui-btn-primary layui-btn-xs data-count-edit" lay-event="detail">明细</a>
        </script>

    </div>
</div>
<script src="/Public/lib/layui-v2.6.3/layui.js" charset="utf-8"></script>
<script>
    layui.use(['form', 'table'], function () {
        var $ = layui.jquery,
            form = layui.form,
            table = layui.table;
         var uid = $("#uid").val();
         console.log(uid);

        table.render({
            elem: '#currentTableId2',
            url: '/index.php/Admin/Index/getUserOrderList/uid/'+uid,
            toolbar: '#toolbarDemo',
            defaultToolbar: ['filter', 'exports', 'print', {
                title: '提示',
                layEvent: 'LAYTABLE_TIPS',
                icon: 'layui-icon-tips'
            }],
            cols: [[
                {type: "checkbox", width: 50},
                {field: 'id', width: 80, title: 'ID', sort: true},
                {field: 'address', width: 350, title: '收益人'},
                {field: 'type_desc', width: 100, title: '收益类型'},
                {field: 'desc', width: 100, title: '收益描述'},
                {field: 'amount', width: 100, title: '收益数量', },
                {field: 'pledge_amount', width: 100, title: '订单金额', },
                {field: 'p_uid', width: 100, title: '订单UID', },
                {field: 'n_address', width: 350, title: '贡献者', },
                 {field: 'state_desc', width: 100, title: '状态', },
                  {field: 'end_time', width: 180, title: '到期时间', },
                {field: 'create_time', width: 180, title: '生成时间', },

                // {title: '操作', minWidth: 150, toolbar: '#currentTableBar', align: "center"}
            ]],
            limits: [10, 15, 20, 25, 50, 100],
            limit: 15,
            page: true,
            skin: 'line'
        });

        // 监听搜索操作
        form.on('submit(data-search-btn)', function (data) {
            var result = JSON.stringify(data.field);
            //执行搜索重载
            table.reload('currentTableId', {
                page: {
                    curr: 1
                }
                , where: {
                    address: data.field.address
                }
            }, 'data');

            return false;
        });

    });
</script>

</body>
</html>