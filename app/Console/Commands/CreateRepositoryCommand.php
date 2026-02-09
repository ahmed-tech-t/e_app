<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;

class CreateRepositoryCommand extends GeneratorCommand
{
    // ده الاسم اللي هتكتبه في الـ Terminal
    protected $signature = 'make:repo {name}';


    /**
     * @inheritDoc
     */
    protected function getStub()
    {
        return base_path('stubs/c.repo.stub');

    }

    // بنحدد الفولدر اللي الملفات هتروح فيه أوتوماتيك
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Domain\Repo';
    }

    // ميثود لتبديل الـ {{ name }} بالاسم اللي هتبعته
    protected function replaceInsideStub($stub, $name)
    {
        return str_replace('{{ name }}', $name, $stub);
    }

    // هنا بنتحكم في اسم الملف اللي هيتكرت
    protected function getNameInput()
    {
        $name = trim($this->argument('name'));

        // لو أنت كتبت SaleUnit، هيخليها SaleUnitRepo
        // ولو أنت كتبت أصلاً SaleUnitRepo، مش هيكررها
        return str_ends_with($name, 'Repo') ? $name : $name . 'Repo';
    }

    // بنعدل الـ buildClass عشان نطبق التغييرات
    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);
        // هنا بنجيب الاسم اللي أنت كتبته في الكومند (مثلاً SaleUnit)
        $nameInput = $this->getNameInput();
        return str_replace('{{ name }}', $nameInput, $stub);
    }
}
