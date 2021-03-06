<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>系统设置</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/Public/lib/layui-v2.6.3/css/layui.css" media="all">
    <link rel="stylesheet" href="/Public/css/public.css" media="all">
    <style>
        .layui-form-item .layui-input-company {width: auto;padding-right: 10px;line-height: 38px;}
    </style>
</head>
<body>
<div class="layuimini-container">
    <div class="layuimini-main">
        <div class="layui-form layuimini-form">
            <div class="layui-form-item">
                <label class="layui-form-label required">项目名称</label>
                <div class="layui-input-block">
                    <input type="text" name="project_name" lay-verify="required" lay-reqtext="网站域名不能为空" placeholder="请输入网站名称"  value="<?php echo ($info["project_name"]); ?>" class="layui-input">
                    <tip>填写自己部署网站的名称。</tip>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label required">logo</label>
                <div class="layui-input-block">
                    <div class="layui-upload">
                        <button type="button" class="layui-btn" id="test1">上传图片</button>
                        <div class="layui-upload-list">
                            <img class="layui-upload-img" width="100" src="<?php echo ($info["project_logo"]); ?>" id="demo1">
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden"  id="logo" name="project_logo" lay-verify="required" value="" class="layui-input">

            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn layui-btn-normal" lay-submit lay-filter="setting">确认保存</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/Public/lib/layui-v2.6.3/layui.js" charset="utf-8"></script>
<script>
    layui.use(['form','upload', 'element', 'layer'], function () {
        var form = layui.form
            ,upload=layui.upload
            , layer = layui.layer
            ,element =layui.element
            , $ = layui.$;

        //常规使用 - 普通图片上传
        var uploadInst = upload.render({
            elem: '#test1'
            ,auto: true
            ,url: '/index.php/Admin/Index/uploadLogo' //此处用的是第三方的 http 请求演示，实际使用时改成您自己的上传接口即可。
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                obj.preview(function(index, file, result){
                    $('#demo1').attr('src', result); //图片链接（base64）
                });
                layer.msg('上传中', {icon: 16, time: 1000});
            }
            ,done: function(res){
                //如果上传失败
                if(res.code > 0){
                    return layer.msg('上传失败');
                }
                //上传成功的一些操作
                layer.msg('上传完毕', {icon: 1});
                $('#logo').val(res.img_path); //置空上传失败的状态

            }
            ,error: function(){
                //演示失败状态，并实现重传
                var demoText = $('#demoText');
                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                demoText.find('.demo-reload').on('click', function(){
                    uploadInst.upload();
                });
            }
        });


        //监听提交
        form.on('submit(setting)', function (data) {
            data = data.field;
            $.ajax({
                url:"/index.php/Admin/Index/saveSetting",
                type:"post",
                dataType:'json',
                data:data,
                success:function (res){
                    console.log(res);
                    if(res.code==1){
                        layer.msg(res.msg);
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