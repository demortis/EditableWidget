<?php
/**
 * @author: Eugene
 * @date: 16.03.16
 * @time: 8:27
 */

namespace digitalmonk\widgets\EditableWidget;

use yii\widgets\InputWidget;

class EditableWidget extends InputWidget
{
    public function init()
    {

    }

    public function run()
    {
        return $this->render('index');
    }
}