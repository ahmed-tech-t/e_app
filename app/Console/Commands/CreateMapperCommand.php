<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class CreateMapperCommand extends GeneratorCommand
{

    protected $signature = 'make:mapper {name}';

    protected function getStub()
    {
        return base_path('stubs/c.mapper.stub');
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Application\Mapper';
    }

    protected function getNameInput()
    {
        $name = trim($this->argument('name'));
        return str_ends_with($name, 'Mapper') ? $name : $name . 'Mapper';
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
