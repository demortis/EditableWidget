<?php
/**
 * @author: Eugene
 * @date: 16.03.16
 * @time: 14:13
 */

\digitalmonk\widgets\EditableWidget\assets\EditableWidgetAssets::register($this);

?>
<?php
    if($inForm)
        if(!$param_one instanceof \yii\base\Model) \yii\widgets\ActiveForm::begin();
?>
<div class="editable-input-box" id="<?=$this->context->id?>">
    <?=\yii\helpers\Html::tag('a', '<span>'.$value.'</span><sup class="editable-input-ico">'.\yii\bootstrap\Html::icon('pencil').'</sup>', $settings)?>
    <?=\yii\helpers\Html::$input($param_one, $param_two, $inputOptions)?>
</div>

<?php
    if($inForm)
        if(!$param_one instanceof \yii\base\Model) \yii\widgets\ActiveForm::end();
?>

<?php
    $this->registerJs('
        $("#'.$this->context->id.'").on("click", "a", function(){
            var $this = $(this),
                $parent = $this.hide().parent(),
                $input = $("'.addslashes(\yii\helpers\Html::$inputType($param_one, $param_two, $inputOptions)).'");
                
            $this.text() === "'.$emptyText.'" ? $input.empty() : $input.val($this.text());
            $parent.find("input.'.$inputOptions['class'].'").replaceWith($input).val();
            $input.focus(); 
        });

        $("#'.$this->context->id.'").on("blur", ".'.$inputOptions['class'].'", function(){
            var $this = $(event.target),
                $link = $this.prev("a"),
                $form = $this.closest("form"),
                $input = $("'.addslashes(\yii\helpers\Html::$input($param_one, $param_two, $inputOptions)).'");

                
            $form.submit(function(event){
                    event.preventDefault();
            });
        
            if($this.val() !== $link.text())
            {
                $($form).data("yiiActiveForm").submitting = true;
                $($form).yiiActiveForm("validate");
        
                var data = $form.serializeArray();
                for(var i = 0, l = data.length; i < l; i++)
                {
                    data[i].value = $.trim(data[i].value.replace(/(\r\n|\n|\r)/gm, " "));
                }
                data = $.param(data);
                      

                $.ajax('.\yii\helpers\Json::encode($ajaxOptions).');
            } else {
                $this.replaceWith($input);
                $link.show();
            }
        });
    ');

?>
