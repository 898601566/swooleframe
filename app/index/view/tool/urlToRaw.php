<div class="layui-card">
  <div class="layui-card-header"><h3>F12传值格式化</h3></div>
  <div class="layui-card-body">
    <form action="<?php echo $this->app->url->createUrl('urlToRaw') ?>" method="post" class="layui-form " style="max-width:
    800px">
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
        <li class="layui-this">转换后字符串</li>
        <li>转换后表格</li>
      </ul>
      <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
          <pre><?php echo $result ?></pre>
        </div>
        <div class="layui-tab-item">
          <table class="layui-table">
            <thead>
            <tr>
              <th>#</th>
              <th>参数</th>
              <th>实例</th>
              <!--          <th>注释</th>-->
            </tr>
            </thead>
            <tbody>
            <?php foreach ($print_arr as $key => $value) { ?>
              <tr>
                <th scope="row"><?php echo $key; ?></th>
                <td><?php echo !empty($value[0]) ? htmlentities($value[0]) : ''; ?></td>
                <td><?php echo !empty($value[1]) ? htmlentities($value[1]) : ''; ?></td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>
