<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RunAllCommand extends Command
{
    // The name and signature of the console command
    protected $signature = 'create {name}';


    public function handle()
    {
        $name = $this->argument('name');

        $this->info("Building the $name empire...");

        $this->call('make:model', ['name' => 'App/Infrastructure/Persistence/Models/' . $name, '-m' => true]);
        $this->call('make:entity', ['name' => $name]);
        $this->call('make:mapper', ['name' => $name]);
        $this->call('make:dto', ['name' => $name]);
        $this->call('make:repo', ['name' => $name]);
        $this->call('make:repoImpl', ['name' => $name]);
        $this->call('make:service', ['name' => $name]);
        $this->call('make:request', ['name' => 'App/Interfaces/Http/Requests/' . $name . '/Create' . $name . 'Request']);
        $this->call('make:request', ['name' => 'App/Interfaces/Http/Requests/' . $name . '/Update' . $name . 'Request']);
        $this->call('make:resource', ['name' => 'App/Interfaces/Http/Resources/' . $name . 'Resource']);
        $this->call('make:controller', ['name' => $name]);
    }
}