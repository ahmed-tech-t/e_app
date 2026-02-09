<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class CreateServiceCommand extends GeneratorCommand
{

    protected $signature = 'make:service {name}';

    protected function getStub()
    {
        return base_path('stubs/c.service.stub');
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Application\Services';
    }

    protected function getNameInput()
    {
        $name = trim($this->argument('name'));
        return str_ends_with($name, 'Service') ? $name : $name . 'Service';
    }

    function modelName()
    {
        return trim($this->argument('name'));
    }


    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        $nameInput = $this->getNameInput();
        $model = $this->modelName();

        $stub = str_replace('{{ name }}', $nameInput, $stub);

        $stub = str_replace('{{ model }}', $model, $stub);
        return $stub;
    }
}
