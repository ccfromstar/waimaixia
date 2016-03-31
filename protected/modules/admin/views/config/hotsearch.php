<style>
    .glyphicon {font-family: microsoft yahei; font-size: 18px; display: inline-block; -webkit-font-smoothing: antialiased; box-sizing: border-box; cursor: pointer; font-weight: normal;}
    .minus {color: #f30;}
    .plus {color: #069;}
</style>

<script>
    var count = <?php echo count($models);?>;
    function addRule(obj) {
        var trhtml = $('#hotwords tr:eq(1)').html();
        var reg2 = new RegExp('Hotsearch_0', "g");
        trhtml = trhtml.replace(/Hotsearch\[0\]/g, 'Hotsearch['+count+']');
        trhtml = trhtml.replace(reg2, 'Hotsearch_'+count);

        var tr = '<tr></tr>';
        tr = $(tr).append(trhtml);
        tr.find('input.rid').val('');
        tr.find('input.word').val('');

        tr.find('td:last').html('<span class="glyphicon plus" onclick="addRule(this);">＋</span> <span class="glyphicon minus" onclick="minRule(this);">×</span>');
        $(tr).insertAfter($(obj).parent().parent('tr'));
        count++;
    }

    function minRule(obj, delid) {
        var tr = $(obj).parent().parent('tr');
        tr.remove();
        if (delid > 0) {
            $('#ids').val($('#ids').val() + ',' + delid);
        }
    }

</script>

<h3 class="title">
    积分规则设置
</h3>

<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'rule',
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
            'htmlOptions' => array(
                'style' => 'border:none;',
            )
        ));
?>

<div class="grid-view">
    <table class="items" id="hotwords">
        <thead>
            <tr>
                <th>搜索模块</th>
                <th>热词</th>
                <th>排序</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($models as $i => $model):?>
            <tr>
                <td>
                    <?php
                        echo $form->hiddenField($model, '['.$i.']id', array('class' => 'rid'));
                        echo $form->dropDownList($model, '['.$i.']table', $modules, array('class' => 'table'));
                    ?>
                </td>

                <td>
                    <?php echo $form->textField($model, '['.$i.']word', array('class' => 'cusinput word', 'style' => 'width:100px'));?>
                    <?php echo $form->error($model, '['.$i.']word');?>
                </td>

                <td>
                    <?php echo $form->numberField($model, '['.$i.']sort', array('class' => 'cusinput sort', 'style' => 'width:50px'));?>
                    <?php echo $form->error($model, '['.$i.']sort');?>
                </td>

                <td>
                    <span class="glyphicon plus" onclick="addRule(this);">＋</span>
                    <?php if ($i > 0):?>
                    <span class="glyphicon minus" onclick="minRule(this, <?php echo $model->id;?>);">×</span>
                    <?php endif;?>
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<div class="row buttons" style="height:40px;border:none;">
    <?php echo CHtml::hiddenField('ids', '0');?>
    <?php echo CHtml::submitButton('确认提交', array('id' => 'btnSubmit', 'style' => 'cursor:pointer;')); ?>
    <?php echo CHtml::link('恢复', 'javascript:location.reload();', array('class' => 'btn', 'style' => 'padding:3px 8px;')); ?>
</div>
<?php $this->endWidget(); ?>