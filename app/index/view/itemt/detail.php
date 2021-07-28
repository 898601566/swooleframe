ID：<?php echo $item['id'] ?><br />
Name：<?php echo isset($item['item_name']) ? $item['item_name'] : '' ?>

<br />
<br />
<a class="big" href="<?php echo $this->app->url->createUrl('index');?>">返回</a>
