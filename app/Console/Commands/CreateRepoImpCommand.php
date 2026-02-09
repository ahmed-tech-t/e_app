<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class CreateRepoImpCommand extends GeneratorCommand
{
    // ده الاسم اللي هتكتبه في الـ Terminal
    protected $signature = 'make:repoImpl {name}';

    /**
     * @inheritDoc
     */
    protected function getStub()
    {
        return base_path('stubs/c.repo.impl.stub');
    }

    // بنحدد الفولدر اللي الملفات هتروح فيه أوتوماتيك
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Infrastructure\Persistence\repo';
    }


    protected function getNameInput()
    {
        $name = trim($this->argument('name'));
        $formattedName = str_ends_with($name, 'Repo') ? $name : $name . 'Repo';
        return 'E' . $formattedName;
    }


    function modelName()
    {
        return trim($this->argument('name'));
    }


    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);
        // هنا بنجيب الاسم اللي أنت كتبته في الكومند (مثلاً SaleUnit)
        $nameInput = $this->getNameInput();
        $model = $this->modelName();

        $stub = str_replace('{{ name }}', $nameInput, $stub);

        $stub = str_replace('{{ model }}', $model, $stub);
        return $stub;
    }
}
