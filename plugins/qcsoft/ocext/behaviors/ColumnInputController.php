<?php namespace Qcsoft\Ocext\Behaviors;

use Backend\Behaviors\ListController;
use Backend\Classes\ControllerBehavior;

class ColumnInputController extends ControllerBehavior
{
    public function __construct($controller)
    {
        parent::__construct($controller);

        $this->addCss(['column-input.scss']);
        $this->addJs('column-input.js');
    }

    public function onColumnInputSave()
    {
        $recordKey = \Request::input('recordKey');
        $newValue = \Request::input('newValue');
        list($modelClass, $attribute) = explode('::', \Request::input('attribute'));

        $record = $modelClass::find($recordKey);

        $record->$attribute = $newValue;

        $record->save();
    }

}
