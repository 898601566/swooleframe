<h1>正则表达式</h1>
<table class="table table-striped">
    <thead>
    <tr>
        <th>#</th>
        <th>表达式</th>
        <th>功能</th>
        <!--          <th>注释</th>-->
    </tr>
    </thead>
    <tbody>
    <?php foreach ($content as $key => $value) { ?>
        <tr>
            <th scope="row"><?php echo $key; ?></th>
            <td><?php echo htmlentities($value['pattern']); ?></td>
            <td><?php echo htmlentities($value['function']); ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>