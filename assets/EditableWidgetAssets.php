<?php
/**
 * @author: Eugene
 * @date: 21.03.16
 * @time: 11:50
 */

namespace digitalmonk\widgets\EditableWidget\assets;

use yii\web\AssetBundle;

class EditableWidgetAssets extends AssetBundle
{
    public $sourcePath = '@digitalmonk/widgets/EditableWidget/assets/assets';

    public $css = [
        'css/style.css'
    ];

    public $js = [
        'js/script.js'
    ];

    public $depends = [
        'yii\web\YiiAsset'
    ];

    public $publishOptions = [
        'forceCopy' => YII_DEBUG
    ];
}