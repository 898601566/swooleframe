<div class="layui-card">
    <div class="layui-card-header"><h3>抄底计算器</h3></div>
    <div class="layui-card-body">
        <form action="<?php echo $this->app->url->createUrl('bottomReadingCalculator') ?>" method="post" class="layui-form "
              style="max-width: 800px">
            <div class="layui-form-item">
                <label class="layui-form-label">基数</label>
                <div class="layui-input-block">
                    <input type="text" name="base" required lay-verify="required" placeholder="请输入基数"
                           style="width: 200px" autocomplete="off" class="layui-input"
                           value="<?php echo $param['base'] ?>">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">比率</label>
                <div class="layui-input-block">
                    <input type="text" name="rate" required lay-verify="required" placeholder="请输入比率"
                           style="width: 200px" autocomplete="off" class="layui-input" value="<?php echo
                    $param['rate'] ?>">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit type="submit">提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
        <div class="layui-card-body">
            <table class="layui-table">
                <colgroup>
                    <col width="200">
                    <col width="200">
                    <col width="200">
                    <col>
                </colgroup>
                <thead>
                <tr>
                    <?php foreach ($ret['title'] as $key => $value) { ?>
                        <th><?php echo $value ?></th>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($ret['data'] as $key => $value) { ?>
                    <tr>
                        <?php foreach ($ret['title'] as $key2 => $value2) { ?>
                            <td><?php echo $value[$key2] ?></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
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
