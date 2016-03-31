<style>
    .glyphicon {font-family: microsoft yahei; font-size: 18px; display: inline-block; -webkit-font-smoothing: antialiased; box-sizing: border-box; cursor: pointer; font-weight: normal;}
    .minus {color: #f30;}
    .plus {color: #069;}
</style>

<script>
    var rules = <?php echo count($models);?>;
    function addRule(obj) {
        var trhtml = $('#rules tr:eq(1)').html();
        var reg2 = new RegExp('Scorerule_0', "g");
        trhtml = trhtml.replace(/Scorerule\[0\]/g, 'Scorerule['+rules+']');
        trhtml = trhtml.replace(reg2, 'Scorerule_'+rules);

        var tr = '<tr></tr>';
        tr = $(tr).append(trhtml);
        tr.find('select.rstatus').val('1');
        tr.find('select.rtype').val('0');
        tr.find('input.rid').val('');
        tr.find('input.rname').val('');
        tr.find('input.rcode').val('');
        tr.find('input.rscore').val('50');
        tr.find('input.rnums').val('0').attr('readonly', 'readonly');
        tr.find('input.rintro').val('');
        tr.find('td:last').html('<span class="glyphicon plus" onclick="addRule(this);">＋</span> <span class="glyphicon minus" onclick="minRule(this);">×</span>');
        $(tr).insertAfter($(obj).parent().parent('tr'));
        rules++;
    }

    function minRule(obj, delid) {
        var tr = $(obj).parent().parent('tr');
        tr.remove();
        if (delid > 0) {
            $('#ids').val($('#ids').val() + ',' + delid);
        }
    }

    function changetype(obj) {
        var type = $(obj).val();
        var nums = $(obj).parent().parent('tr').find('input.rnums');

        switch (type) {
            case '0':
                $(nums).val('0').attr('readonly', true);
                break;
            case '1':
                $(nums).val('1').attr('readonly', true);
                break;
            case '2':
                $(nums).attr('readonly', false);
                break;
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
    <table class="items" id="rules">
        <thead>
            <tr>
                <th>规则状态</th>
                <th>规则类型</th>
                <th>规则名称</th>
                <th>规则代码</th>
                <th>奖励积分</th>
                <th>累计次数</th>
                <th>规则说明</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($models as $i => $model):?>
            <tr>
                <td>
                    <?php
                        echo $form->hiddenField($model, '['.$i.']id', array('class' => 'rid'));
                        echo $form->dropDownList($model, '['.$i.']status', Yii::app()->params['status'], array('class' => 'rstatus'));
                    ?>
                </td>
                <td>
                    <?php echo $form->dropDownList($model, '['.$i.']type', Yii::app()->params['sruletype'], array('class' => 'rtype', 'onchange' => 'changetype(this);'));?>
                </td>
                <td>
                    <?php echo $form->textField($model, '['.$i.']name', array('class' => 'cusinput rname', 'style' => 'width:100px'));?>
                    <?php echo $form->error($model, '['.$i.']name');?>
                </td>
                <td>
                    <?php echo $form->textField($model, '['.$i.']code', array('class' => 'cusinput rcode', 'style' => 'width:100px'));?>
                    <?php echo $form->error($model, '['.$i.']code');?>
                </td>
                <td>
                    <?php echo $form->numberField($model, '['.$i.']score', array('class' => 'cusinput rscore', 'style' => 'width:50px'));?>
                    <?php echo $form->error($model, '['.$i.']score');?>
                </td>
                <td>
                    <?php echo $form->numberField($model, '['.$i.']nums', array('class' => 'cusinput rnums', 'style' => 'width:50px', 'readonly' => 'readonly'));?>
                    <?php echo $form->error($model, '['.$i.']nums');?>
                </td>
                <td><?php echo $form->textField($model, '['.$i.']intro', array('class' => 'cusinput rintro', 'style' => 'width:150px'));?></td>
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