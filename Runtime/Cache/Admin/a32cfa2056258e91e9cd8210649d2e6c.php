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
        <script type="text/html" id="toolbarDemo">
            <div class="layui-btn-container">
                <button class="layui-btn layui-btn-normal layui-btn-sm data-add-btn" lay-event="add"> 添加 </button>
                <button class="layui-btn layui-btn-sm layui-btn-danger data-delete-btn" lay-event="delete"> 删除 </button>
            </div>
        </script>

        <table class="layui-hide" id="currentTableId" lay-filter="currentTableFilter"></table>

        <script type="text/html" id="currentTableBar">
            <a class="layui-btn layui-btn-normal layui-btn-xs data-count-edit" lay-event="edit">编辑</a>
            <a class="layui-btn layui-btn-xs layui-btn-danger data-count-delete" lay-event="delete">删除</a>
            <a class="layui-btn layui-btn-xs layui-btn-primary data-count-edit" lay-event="open">开启</a>
        </script>

    </div>
</div>
<script src="/Public/lib/layui-v2.6.3/layui.js" charset="utf-8"></script>
<script>
    layui.use(['form', 'table'], function () {
        var $ = layui.jquery,
            form = layui.form,
            table = layui.table;

        table.render({
            elem: '#currentTableId',
            url: '/index.php/Admin/Index/getList',
            toolbar: '#toolbarDemo',
            defaultToolbar: ['filter', 'exports', 'print', {
                title: '提示',
                layEvent: 'LAYTABLE_TIPS',
                icon: 'layui-icon-tips'
            }],
            cols: [[
                {type: "checkbox", width: 50},
                {field: 'id', width: 80, title: 'ID', sort: true},
                {field: 'name', width: 80, title: '圈子名'},
                {field: 'state', width: 80, title: '状态'},
                {field: 'one_day', width: 80, title: '1天'},
                {field: 'five_day', width: 80, title: '5天'},
                {field: 'ten_day', width: 80,title: '10天'},
                {field: 'twenty_day', width: 80, title: '20天' },
                {field: 'one_level', width: 120, title: '1000MPC'},
                {field: 'two_level', width: 120, title: '2000MPC'},
                {field: 'three_level', width: 135, title: '3000MPC',align: "center"},
                {field: 'four_level', width: 235, title: '4000MPC~10000MPC',align: "center"},
                {field: 'five_level', width: 235, title: '11000MPC~20000MPC',align: "center"},
                {title: '操作', minWidth: 180, toolbar: '#currentTableBar', align: "center"}
            ]],
            limits: [10, 15, 20, 25, 50, 100],
            limit: 15,
            page: true,
            skin: 'line'
        });

        // 监听搜索操作
        form.on('submit(data-search-btn)', function (data) {
            var result = JSON.stringify(data.field);
            layer.alert(result, {
                title: '最终的搜索信息'
            });

            //执行搜索重载
            table.reload('currentTableId', {
                page: {
                    curr: 1
                }
                , where: {
                    searchParams: result
                }
            }, 'data');

            return false;
        });

        /**
         * toolbar监听事件
         */
        table.on('toolbar(currentTableFilter)', function (obj) {
            if (obj.event === 'add') {  // 监听添加操作
                var index = layer.open({
                    title: '添加圈子',
                    type: 2,
                    shade: 0.2,
                    maxmin:true,
                    shadeClose: true,
                    area: ['100%', '100%'],
                    content: "/index.php/Admin/Index/add",
                });
                $(window).on("resize", function () {
                    layer.full(index);
                });
            } else if (obj.event === 'delete') {  // 监听删除操作
                var checkStatus = table.checkStatus('currentTableId')
                    , data = checkStatus.data;
                layer.alert(JSON.stringify(data));
            }
        });

        //监听表格复选框选择
        table.on('checkbox(currentTableFilter)', function (obj) {
            console.log(obj)
        });

        table.on('tool(currentTableFilter)', function (obj) {
            var data = obj.data;
            if (obj.event === 'edit') {
                console.log(data);
                var index = layer.open({
                    title: '编辑用户',
                    type: 2,
                    shade: 0.2,
                    maxmin:true,
                    shadeClose: true,
                    area: ['100%', '100%'],
                    content: '/index.php/Admin/Index/edit/id/'+data.id,
                });
                $(window).on("resize", function () {
                    layer.full(index);
                });
                return false;
            } else if (obj.event === 'delete') {
                layer.confirm('真的删除行么', function (index) {

                    $.ajax({
                        url:"/index.php/Admin/Index/del/id/"+data.id,
                        type:"post",
                        dataType:'json',
                        data:data,
                        success:function (res){
                            console.log(res);
                            if (res.code == 1) {
                                var index = layer.msg(res.msg, function () {
                                    // 关闭弹出层
                                    // 关闭弹出层
                                    layer.close(index);
                                    location.reload();
                                });
                            } else {
                                layer.msg(res.msg);
                            }
                        }
                    })
                });
            }
        });

    });
</script>

</body>
</html>