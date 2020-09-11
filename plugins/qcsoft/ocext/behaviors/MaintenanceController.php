<?php namespace Qcsoft\Ocext\Behaviors;

use Backend\Classes\ControllerBehavior;
use October\Rain\Support\Collection;

class MaintenanceController extends ControllerBehavior
{
    public function __construct($controller)
    {
        parent::__construct($controller);
    }

    public function index($activeCmd = null)
    {
        return $this->maintenanceGetActionNames()
            ->map(function ($action) use ($activeCmd)
            {
                $url = $this->controller->actionUrl($action);
                $style = $action === $activeCmd ? 'text-decoration: underline': '';

                return <<<EOT
<p><a href="$url" style="$style">$action</a></p>
EOT;
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
