<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class CreateDtoCommand extends GeneratorCommand
{

    protected $signature = 'make:dto {name}';

    protected function getStub()
    {
        return base_path('stubs/c.dto.stub');
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Application\DTOs';
    }

    protected function getNameInput()
    {
        $name = trim($this->argument('name'));
        return $name . 'Dto';
    }

    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        $nameInput = $this->getNameInput();

        return str_replace('{{ name }}', $nameInput, $stub);

    }
}
