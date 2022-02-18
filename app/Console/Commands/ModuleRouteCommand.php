<?php

namespace App\Console\Commands;

use App\Models\Module;
use App\Models\ModuleRoute;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\Echo_;

class ModuleRouteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:route';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ModuleRoute tablosundaki title satırını route nameleri ile günceller.';

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
        $modules = DB::table('modules')->get()->toArray();
        $routeCollection = Route::getRoutes();
        for($i = 0; $i < count($modules); $i++){
            foreach ($routeCollection as $route) {
                $this->createOrPass($route->getName(),$modules[$i]);
            }
        }
        echo "\033[32m route_module tablosu güncelleme işlemi başarılı.\033[0m\n";
    }

    public function createOrPass($route,$module){
        if (Str::before($route,'.') == Str::lower($module->name)){
            $operation = $this->getOperation(Str::after($route,'.'));
            ModuleRoute::updateOrCreate(
              ['route_name' => $route],
              ['module_id' => $module->id, 'title' => $module->title . " " . $operation, 'type' => Str::after($route,'.')]
            );
        }elseif(Str::between($route,'to-','.') == Str::lower($module->name)){
            $operation = $this->getOperation(Str::after($route,'.'));
            $relTitle = DB::table('modules')->where('name',Str::ucfirst(Str::before($route,'-to')))->first()->title;
            $title = $module->title . " " . $relTitle . " ilişkisi " . $operation;
            ModuleRoute::updateOrCreate(
                ['route_name' => $route],
                ['module_id' => $module->id, 'title' => $title, 'type' => Str::after($route,'.')]
            );
        }
    }

    public function getOperation($case){
        switch ($case){
            case 'create':
                return 'oluşturma';
            case 'update' :
                return 'güncelleme';
            case 'delete' :
                return 'silme';
            case 'login';
                return 'giriş';
            case 'read':
            case 'view' :
                return 'görüntüleme';
        }
    }
}
