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
    <style>
        body {
            background-color: #ffffff;
        }
    </style>
</head>
<body>
<div class="layui-form layuimini-form">
    <div class="layui-form-item">
        <label class="layui-form-label required">圈子名称</label>
        <div class="layui-input-block">
            <input type="text" name="name" lay-verify="required" lay-reqtext="圈子名称不能为空" placeholder="请输入圈子名称" value="" class="layui-input">
            <tip>填写圈子的名称。</tip>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">圈子状态</label>
        <div class="layui-input-block">
            <input type="radio" name="state" value="0" title="关闭" checked="">
            <input type="radio" name="state" value="1" title="开启">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label required">1天</label>
        <div class="layui-input-inline">
            <input type="number" name="one_day" lay-verify="required|number" lay-reqtext="百分比不能为空" placeholder="%" value="" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">请填写1天的%</div>
    </div>
    <div class="layui-form-item"><label class="layui-form-label required">5天</label>
        <div class="layui-input-inline">
            <input type="number" name="five_day" lay-verify="required" lay-reqtext="百分比不能为空" placeholder="" value="" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">请填写5天的%</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label required">10天</label>
        <div class="layui-input-inline">
            <input type="number" name="ten_day" lay-verify="required" lay-reqtext="百分比不能为空" placeholder="" value="" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">请填写10天的%</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label required">20天</label>
        <div class="layui-input-inline">
            <input type="number" name="twenty_day" lay-verify="required" lay-reqtext="百分比不能为空" placeholder="" value="" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">请填写20天的%</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label required">1000MPC</label>
        <div class="layui-input-inline">
            <input type="number" name="one_level" lay-verify="required" lay-reqtext="百分比不能为空" placeholder="" value="" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">请输入1000MPC可拿百分比</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label required">2000MPC</label>
        <div class="layui-input-inline">
            <input type="number" name="two_level" lay-verify="required" lay-reqtext="百分比不能为空" placeholder="" value="" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">请输入2000MPC可拿百分比</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label required">3000MPC</label>
        <div class="layui-input-inline">
            <input type="number" name="three_level" lay-verify="required" lay-reqtext="百分比不能为空" placeholder="" value="" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">请输入3000MPC可拿百分比</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label required">4000~10000MPC</label>
        <div class="layui-input-inline">
            <input type="number" name="four_level" lay-verify="required" lay-reqtext="百分比不能为空" placeholder="" value="" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">请输入4000~10000MPC可拿百分比</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label required">11000MPC~20000MPC</label>
        <div class="layui-input-inline">
            <input type="number" name="five_level" lay-verify="required" lay-reqtext="百分比不能为空" placeholder="" value="" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">请输入11000MPC~20000MPC可拿百分比</div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn layui-btn-normal" lay-submit lay-filter="saveBtn">确认保存</button>
        </div>
    </div>
</div>
<script src="/Public/lib/layui-v2.6.3/layui.js" charset="utf-8"></script>
<script>
    layui.use(['form'], function () {
        var form = layui.form,
            layer = layui.layer,
            $ = layui.$;

        //监听提交
        form.on('submit(saveBtn)', function (data) {

            data = data.field;
            $.ajax({
                url:"/index.php/Admin/Index/saveAdd",
                type:"post",
                dataType:'json',
                data:data,
                success:function (res){
                    console.log(res);
                    if(res.code==1){
                        var index = layer.msg(res.msg, function () {
                            // 关闭弹出层
                            // 关闭弹出层
                            layer.close(index);

                            var iframeIndex = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(iframeIndex);
                            parent.location.reload();
                        });
                    }else{
                        layer.msg(res.msg);
                    }
                }
            })
            return false;
        });

    });
</script>
</body>
</html>