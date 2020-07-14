<?php namespace Qcsoft\App\Widgets;

use Backend\Widgets\Lists;

class VLists extends Lists
{
    public function render()
    {
        $this->prepareVars();
        return [$this->getId() => $this->vars];
//        return $this->vars;
    }

    public function onRefresh()
    {
        $this->prepareVars();
        return [$this->getId() => $this->vars];
//        return ['#'.$this->getId() => $this->makePartial('list')];
    }

    protected function loadAssets()
    {
//        $this->addJs('js/october.list.js', 'core');
    }

    public function prepareVars()
    {
        parent::prepareVars();

        $this->vars['records'] = collect($this->vars['records'])->each(function ($record)
        {
            $columnValues = [];

            foreach ($this->vars['columns'] as $key => $column)
            {
                $columnValues[$key] = $this->getColumnValueRaw($record, $column);
            }

            $record['columnValues'] = $columnValues;
        });
    }

}
