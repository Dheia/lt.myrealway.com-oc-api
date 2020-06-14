<?php namespace Qcsoft\Ocext\Behaviors;

use Backend\Classes\ControllerBehavior;
use October\Rain\Support\Collection;

class MaintenanceController extends ControllerBehavior
{
    public function __construct($controller)
    {
        parent::__construct($controller);
    }

    public function index()
    {
        return $this->maintenanceGetActionNames()->map(function ($action)
        {
            return '<p><a href="' . $this->controller->actionUrl($action) . '">' . $action . '</a></p>';
        })
            ->implode('');
    }

    /**
     * @return Collection
     * @throws \ReflectionException
     */
    public function maintenanceGetActionNames()
    {
        return collect((new \ReflectionClass($this->controller))
            ->getMethods(\ReflectionMethod::IS_PUBLIC))
            ->filter(function (\ReflectionMethod $method)
            {
                return $method->getDeclaringClass()->getName() === get_class($this->controller);
            })
            ->map(function (\ReflectionMethod $method)
            {
                return $method->getName();
            })
            ->diff(['__construct', 'index']);
    }

}
