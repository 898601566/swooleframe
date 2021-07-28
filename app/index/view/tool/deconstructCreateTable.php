<div class="layui-card">
  <div class="layui-card-header"><h3>解析建表语句</h3></div>
  <div class="layui-card-body">
    <form action="<?php echo $this->app->url->createUrl('deconstructCreateTable') ?>" method="post" class="layui-form " style="max-width: 800px">
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
            <li class="<?php echo $key == array_keys($ret)[0] ?
                'layui-this' : '' ?>"><?php echo $key ?></li>
          <?php } ?>
      </ul>
      <div class="layui-tab-content">
          <?php foreach ($ret as $key => $value) { ?>
            <div class="m-t-10 m-l-10 layui-tab-item <?php echo $key == array_keys($ret)[0] ? 'layui-show' : '' ?>">
              <button class="btn_copy layui-btn layui-btn-normal" data-clipboard-action="copy" data-clipboard-target="#pre_<?php echo $key ?>">
               复制到剪贴板
              </button>
              <pre class="m-t-10 m-l-10" id="pre_<?php echo $key ?>"><?php echo $value ?></pre>
            </div>
          <?php } ?>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.bootcdn.net/ajax/libs/clipboard.js/2.0.6/clipboard.min.js"></script>

<script>
  $(function () {
    var clipboard = new ClipboardJS('.btn_copy');
    clipboard.on('success', function(e) {
      layer.msg('已复制')
    });
    layui.use('form', function () {
      var form = layui.form;

    })
    layui.use('element', function () {
      var element = layui.element;

      //…
    });
  })
</script>
