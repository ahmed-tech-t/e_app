<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class CreateEntityCommand extends GeneratorCommand
{

    protected $signature = 'make:entity {name}';

    protected function getStub()
    {
        return base_path('stubs/c.entity.stub');
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Domain\Entities';
    }

    protected function getNameInput()
    {
        $name = trim($this->argument('name'));
        return $name . 'Entity';
    }

    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        $nameInput = $this->getNameInput();

        return str_replace('{{ name }}', $nameInput, $stub);

    }
}
