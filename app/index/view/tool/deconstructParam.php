<div class="layui-card">
  <div class="layui-card-header"><h3>解析变量名</h3></div>
  <div class="layui-card-body">
    <form action="<?php echo $this->app->url->createUrl('deconstructParam') ?>" method="post" class="layui-form " style="max-width: 800px">
      <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">source</label>
        <div class="layui-input-block">
          <textarea name="source" class="layui-textarea" rows="10"><?php echo $source ?></textarea>
        </div>
      </div>
      <div class="layui-form-item">
        <div class="layui-input-block">
          <button class="layui-btn" lay-submit type="submit">提交</button>
          <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
      </div>
    </form>
    <div class="layui-tab layui-tab-card">
      <ul class="layui-tab-title">
          <?php foreach ($ret as $key => $value) { ?>
            <li <?php echo $key == array_keys($ret)[0] ? 'class="layui-this"' : '' ?>><?php echo $key ?></li>
          <?php } ?>
      </ul>
      <div class="layui-tab-content">
          <?php foreach ($ret as $key => $value) { ?>
            <div class="layui-tab-item <?php echo $key == array_keys($ret)[0] ? 'layui-show' : '' ?>">
              <pre><?php echo $value ?></pre>
            </div>
          <?php } ?>
      </div>
    </div>
  </div>
</div>

<script>
  $(function () {
    layui.use('form', function () {
      var form = layui.form;

    })
    layui.use('element', function () {
      var element = layui.element;

      //…
    });
  })
</script>
