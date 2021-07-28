<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>layout 后台大布局 - Layui</title>
  <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
  <link rel="stylesheet" href="http://www.layuicdn.com/layui/css/layui.css">
  <link rel="stylesheet" href="/static/zhengze/global.css">
  <link rel="stylesheet" href="/static/zhengze/space.css">

  <script src="http://cdn.bootcss.com/jquery/3.4.1/jquery.js"></script>
  <script src="/static/template-web/template-web.js"></script>
  <script src="http://www.layuicdn.com/layui/layui.js"></script>

</head>
<body class="layui-layout-body">

<div class="layui-layout layui-layout-admin">
  <div class="layui-header">
    <div class="layui-logo">fastswoole <a href="<?php echo $this->app->url->createUrl('index/index') ?>">首页</a></div>
  </div>

  <div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
      <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
      <ul class="layui-nav layui-nav-tree" lay-filter="test">
        <dd class="layui-nav-item">
          <a href="javascript:void();">设计模式</a>
          <dl class="layui-nav-child">
            <dd>
              <a href="javascript:void();">创建型模式-单工厂建原抽</a>
              <dl class="layui-nav-child">
                <dd><a href='<?php echo $this->app->url->createUrl('mode/singleton/index') ?>'>单例</a></dd>
                <dd><a href='<?php echo $this->app->url->createUrl('mode/factory_method/index') ?>'>工厂方法</a></dd>
                <dd><a href='<?php echo $this->app->url->createUrl('mode/builder/index') ?>'>建造者</a></dd>
                <dd><a href='<?php echo $this->app->url->createUrl('mode/prototype/index') ?>'>原型</a></dd>
                <dd><a href='<?php echo $this->app->url->createUrl('mode/abstract_factory/index') ?>'>抽象工厂</a></dd>
              </dl>
            </dd>
            <dd>
              <a href="javascript:void();">结构型模式-代修桥适合享元门</a>
              <dl class="layui-nav-child">
                <dd><a href='<?php echo $this->app->url->createUrl('mode/adapter/index') ?>'>适配器</a></dd>
                <dd><a href='<?php echo $this->app->url->createUrl('mode/bridge/index') ?>'>桥接</a></dd>
                <dd><a href='<?php echo $this->app->url->createUrl('mode/composite/index') ?>'>合成</a></dd>
                <dd><a href='<?php echo $this->app->url->createUrl('mode/decorator/index') ?>'>修饰</a></dd>
                <dd><a href='<?php echo $this->app->url->createUrl('mode/facade/index') ?>'>门面</a></dd>
                <dd><a href='<?php echo $this->app->url->createUrl('mode/flyweight/index') ?>'>享元</a></dd>
                <dd><a href='<?php echo $this->app->url->createUrl('mode/proxy/index') ?>'>代理</a></dd>
              </dl>
            </dd>
            <dd>
              <a href="javascript:void();">行为型模式-策模访叠被,责观命中状翻</a>
              <dl class="layui-nav-child">
                <dd><a href='/mode/responsibility_chain/index'>责任链</a></dd>
                <dd><a href='/mode/command/index'>命令</a></dd>
                <dd><a href='/mode/interpreter/index'>翻译者</a></dd>
                <dd><a href='/mode/iteration/index'>迭代</a></dd>
                <dd><a href='/mode/mediator/index'>中介者</a></dd>
                <dd><a href='/mode/memento/index'>备忘录</a></dd>
                <dd><a href='/mode/observer/index'>观察者</a></dd>
                <dd><a href='/mode/state/index'>状态</a></dd>
                <dd><a href='/mode/strategy/index'>策略</a></dd>
                <dd><a href='/mode/template_method/index'>模板</a></dd>
                <dd><a href='/mode/visitor/index'>访问者</a></dd>
              </dl>
            </dd>
          </dl>
          </li>
          <li class="layui-nav-item">
            <a href="javascript:void();">工具</a>
            <dl class="layui-nav-child">
                <?php foreach ($tool_methods as $key => $value) {
                    if ($value->name != '__construct') { ?>
                      <dd><a href='/index/tool/<?php echo $value->name ?>'><?php echo $value->name ?></a></dd>
                    <?php }
                } ?>
            </dl>
          </li>
        </dd>
      </ul>
    </div>
  </div>

  <div class="layui-body">

    <div style="padding: 15px;" class="layui-container"><?php echo $html_content ?? '' ?></div>
  </div>

  <div class="layui-footer">
    <!-- 底部固定区域 -->
    © layui.com - 底部固定区域
  </div>
</div>

<script>
  //JavaScript代码区域
  $(function () {
    layui.use(['form', 'element', 'laydate'], function () {
      var form = layui.form;
      var layer = layui.layer;
      var element = layui.element;
      var laydate = layui.laydate;
      if ($("[href='" + window.location.pathname + "']")) {
        $("[href='" + window.location.pathname + "']").addClass("layui-this");
        $("[href='" + window.location.pathname + "']").parents('li').addClass("layui-nav-itemed");
      }
    });
  });
</script>
</body>
</html>
