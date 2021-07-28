<div class="layui-card">
  <div class="layui-card-header"><h3>正则表达式处理PHP数组</h3></div>
  <div class="layui-card-body">
    <div class="layui-row">
      <pre name="source" class="layui-textarea" rows="10"><?php echo $log ?></pre>
    </div>
    <div class="layui-row m-t-10">
      <a class="layui-btn" href="<?php echo $this->app->url->createUrl('sqlLog') ?>">刷新</a>
      <a class="layui-btn layui-btn-primary" href="<?php echo $this->app->url->createUrl('sqlLog', ['clear' => 1]) ?>">重置</a>
    </div>
  </div>
</div>
