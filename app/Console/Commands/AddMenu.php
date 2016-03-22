<?php

namespace App\Console\Commands;

use App\Menu;
use Illuminate\Console\Command;

class AddMenu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:menu {name} {price}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add menu';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Menu::create(['name' => $this->argument('name'), 'price' => $this->argument('price')]);
    }
}
