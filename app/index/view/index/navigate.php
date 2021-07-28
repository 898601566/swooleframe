
<div class="layui-container">
  <div class="layui-row">
    <div class="layui-col-md4">
      1/3
    </div>
    <div class="layui-col-md4">
      1/3
    </div>
    <div class="layui-col-md4">
      1/3
    </div>
  </div>
</div>
<p>依赖注入(dependency injection)</p>
<p>控制反转(Inversion of Control):控制的是外部对象</p>
<p>聚合和关联在语法上很像,需要根据语义划分</p>

<!--
三角形实线	泛化
三角形虚线	实现
空心菱形实线    聚合
实心菱形实线    组合
(箭头)实线	关联
箭头虚线	依赖
-->
<div class="col-md-3">
  <ul>
    <li><b>PHP默认函数测试</b></li>
    <li><a href='<?php echo $this->app->url->createUrl('DefaultFunction/pdo') ?>'>pdo</a></li>
    <li><a href='<?php echo $this->app->url->createUrl('DefaultFunction/sort') ?>'>sort</a></li>
    <li><a href='<?php echo $this->app->url->createUrl('DefaultFunction/ob') ?>'>ob</a></li>
    <li><a href='<?php echo $this->app->url->createUrl('DefaultFunction/arrayTest') ?>'>array</a></li>
    <li><a href='<?php echo $this->app->url->createUrl('DefaultFunction/stringTest') ?>'>string</a></li>
    <li><a href='<?php echo $this->app->url->createUrl('DefaultFunction/exceptionHandlerTest') ?>'>set_exception_handler</a></li>
  </ul>
</div>
<div class="col-md-3">
  <ul>
    <li><b>创建型模式-单工厂建原抽</b></li>
    <li><a href='<?php echo $this->app->url->createUrl('mode/singleton/index') ?>'>单例</a></li>
    <li><a href='<?php echo $this->app->url->createUrl('mode/factory_method/index') ?>'>工厂方法</a></li>
    <li><a href='<?php echo $this->app->url->createUrl('mode/builder/index') ?>'>建造者</a></li>
    <li><a href='<?php echo $this->app->url->createUrl('mode/prototype/index') ?>'>原型</a></li>
    <li><a href='<?php echo $this->app->url->createUrl('mode/abstract_factory/index') ?>'>抽象工厂</a></li>
    <li><b>结构型模式-代修桥适合享元门</b></li>
    <li><a href='<?php echo $this->app->url->createUrl('mode/adapter/index') ?>'>适配器</a></li>
    <li><a href='<?php echo $this->app->url->createUrl('mode/bridge/index') ?>'>桥接</a></li>
    <li><a href='<?php echo $this->app->url->createUrl('mode/composite/index') ?>'>合成</a></li>
    <li><a href='<?php echo $this->app->url->createUrl('mode/decorator/index') ?>'>修饰</a></li>
    <li><a href='<?php echo $this->app->url->createUrl('mode/facade/index') ?>'>门面</a></li>
    <li><a href='<?php echo $this->app->url->createUrl('mode/flyweight/index') ?>'>享元</a></li>
    <li><a href='<?php echo $this->app->url->createUrl('mode/proxy/index') ?>'>代理</a></li>
    <li><b>行为型模式-策模访叠被,责观命中状翻</b></li>
    <li><a href='<?php echo $this->app->url->createUrl('mode/responsibility_chain/index') ?>'>责任链</a></li>
    <li><a href='<?php echo $this->app->url->createUrl('mode/command/index') ?>'>命令</a></li>
    <li><a href='<?php echo $this->app->url->createUrl('mode/interpreter/index') ?>'>翻译者</a></li>
    <li><a href='<?php echo $this->app->url->createUrl('mode/iteration/index') ?>'>迭代</a></li>
    <li><a href='<?php echo $this->app->url->createUrl('mode/mediator/index') ?>'>中介者</a></li>
    <li><a href='<?php echo $this->app->url->createUrl('mode/memento/index') ?>'>备忘录</a></li>
    <li><a href='<?php echo $this->app->url->createUrl('mode/observer/index') ?>'>观察者</a></li>
    <li><a href='<?php echo $this->app->url->createUrl('mode/state/index') ?>'>状态</a></li>
    <li><a href='<?php echo $this->app->url->createUrl('mode/strategy/index') ?>'>策略</a></li>
    <li><a href='<?php echo $this->app->url->createUrl('mode/template_method/index') ?>'>模板</a></li>
    <li><a href='<?php echo $this->app->url->createUrl('mode/visitor/index') ?>'>访问者</a></li>
  </ul>
</div>
<div class="col-md-3">
  <ul>
    <li><b>工具</b></li>
    <li><a href='<?php echo $this->app->url->createUrl('tool/urlToRaw') ?>'>urldecode,适配doclever</a></li>
    <li><a href='<?php echo $this->app->url->createUrl('tool/regularExpressionSource') ?>'>正则表达式基础</a></li>
    <li><a href='<?php echo $this->app->url->createUrl('tool/regularExpression') ?>'>正则表达式示例</a></li>
  </ul>
</div>
<div class="col-md-3">
  <ul>

  </ul>
</div>
<ul>


</ul>
