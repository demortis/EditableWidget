<?php
/**
 * @author: Eugene
 * @date: 16.03.16
 * @time: 8:27
 */

namespace digitalmonk\widgets\EditableWidget;

use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\InputWidget;

class EditableWidget extends InputWidget
{
    //TODO: 1. Проверить на PHP5.
    //TODO: 2. Предотвратить отправку на сервер если валидация не пройдена

    /**
     * @var string
     * input type по умолчанию
     */
    public $inputType = 'textarea';

    /**
     * @var string
     * Класс поля input
     */
    private $class = 'editable-input';

    /**
     * @var string
     * Класс при пустом значении
     */
    public $emptyClass = 'empty-editable';

    /**
     * @var string
     * Имя формы, в основном для использования в ActiveForm
     */
    public $name = 'editable-input';

    /**
     * @var string
     * Значение по умолчанию
     */
    public $emptyText = 'Не задано';

    /**
     * @var array
     * Настройки input
     */
    public $inputOptions = [];
    /**
     * @var array
     * Настройки виджета
     */
    public $settings = [];

    /**
     * @var array
     * Настройки ajax
     */
    public $ajaxOptions = [];

    /**
     * @var bool
     * Обрамлять ли поле в ActiveForm
     */
    public $inForm = true;

    /**
     * @var string
     * Тип ajax-запроса
     */
    private $ajaxType = 'POST';

    /**
     * Тип ajax-ответа
     * @var string
     */
    private $ajaxDataType = 'JSON';

    /**
     * @var string
     * JavaScript выражение
     */
    private $ajaxBeforeSend;

    /**
     * @var string
     * JavaScript выражение
     */
    private $ajaxData = 'data';

    /**
     * @var string
     * JavaScript выражение
     */
    private $ajaxError;

    /**
     * @var string
     * JavaScript выражение
     */
    private $ajaxSuccess;

    /**
     * @var array
     * Массив параметров для передачи во view
     */
    private $params = [];

    /**
     * @var string
     * Тип поля: Active или не Active
     */
    private $input = 'hiddenInput';

    /**
     * @throws \yii\base\InvalidConfigException
     * Метод иницализации виджета
     */
    public function init()
    {
        parent::init();

        if($this->hasModel())
        {
            $this->inputType = 'active'.ucfirst($this->inputType);
            $this->input = 'active'.ucfirst($this->input);
            $param_one = $this->model;
            $param_two = $this->attribute;
        } else {
            $param_one = $this->name;
            $param_two = Html::encode($this->value);
        }

        $defaultSettings = [
            'class' => !isset($this->value) ? $this->emptyClass : ''
        ];

        $this->settings = array_merge($defaultSettings, $this->settings);

        $defaultInputOptions = [
            'class' => $this->class
        ];

        $this->inputOptions = array_merge($defaultInputOptions, $this->inputOptions);

        $defaultAjaxOptions = [
            'type' => $this->ajaxType,
            'dataType' => $this->ajaxDataType,
            'data' => new JsExpression($this->ajaxData),
            'beforeSend' => new JsExpression($this->ajaxBeforeSend ?: 'function(jqXHR, settings){alert($form.find(".has-error").length);  }'),
            'error' => new JsExpression($this->ajaxError ?: 'function(jqXHR, textStatus, errorThrown){
                console.error(textStatus);
            }'),
            'success' => new JsExpression($this->ajaxSuccess ?: 'function(data){
                if(data === true){
                    $this.replaceWith("'.addslashes(\yii\helpers\Html::{$this->input}($param_one, $param_two, $this->inputOptions)).'");
                    $form.find("input.'.$this->class.'").val($this.val());
                    $link.children("span").text($this.val());
                    $link.show();
                }
            }')
        ];

        $this->ajaxOptions = array_merge($defaultAjaxOptions, $this->ajaxOptions);



        $this->params = [
            'id' => $this->id,
            'input' => $this->input,
            'inputType' => $this->inputType,
            'param_one' => $param_one,  // model или name, зависит от наличия модели
            'param_two' => $param_two,  // attribute или value, зависит от наличия модели
            'settings' => $this->settings,
            'inputOptions' => $this->inputOptions,
            'ajaxOptions' => $this->ajaxOptions,
            'value' => $this->getValue(),
            'emptyText' => $this->emptyText,
            'inForm' => $this->inForm
        ];
    }

    /**
     * @return string
     * Метод запускающий виджет
     */
    public function run()
    {
        return $this->render('index', $this->params);
    }

    /**
     * @return string
     * Метод возвращает значение поля
     */
    private function getValue()
    {
        if($this->hasModel())
            return $this->model->{$this->attribute} ?: $this->emptyText;

        return $this->value ?: $this->emptyText;
    }
}