<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\OrcamentoController;
use Illuminate\Console\Command;

class CheckOrcamentos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CheckOrcamentoClient:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checa a cada 6 horas se existe algum orçamento criado se sim dispara o formulário';

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
     * @return int
     */
    public function handle()
    {
        $controller = new OrcamentoController();
        $controller->checkOrcamentoClient();
    }
}
