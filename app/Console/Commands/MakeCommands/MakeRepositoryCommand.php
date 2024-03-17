<?php

namespace App\Console\Commands\MakeCommands;

use App\Traits\MakeCommandTrait;
use Exception;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\text;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Console\Command\Command as CommandAlias;

class MakeRepositoryCommand extends Command
{
    use MakeCommandTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repo';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'it takes modelName required,
    namespace & api are optional params for web / api controllers & requests and vuejs and routes';
    protected bool $api = false;
    protected bool $web = false;
    protected bool $mobile = false;
    protected bool $vue = false;
    protected bool $createRoute = true;
    protected bool $createVueRoute = true;
    protected string $modelName;
    protected string $modelObject;
    protected string $namespace;
    protected string $apiMiddleware;

    protected const APP_TYPE_API = 'api';
    protected const APP_TYPE_WEB = 'web';
    protected const APP_TYPE_MOBILE = 'mobile';
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
    public function handle(): int
    {
        $this->getData();
        $this->info('Creating Repository for ' . $this->modelName);
        $this->info($this->createModel());
        $this->info($this->createRepository());
        $this->info($this->createContract());

        if ($this->web) {
            $this->info($this->createController('web'));
            $this->info($this->createRequest('web'));
            if ($this->createRoute) {
                $this->info($this->createRoute('web'));
            }
        }
        if ($this->api) {
            $this->info($this->createController('api'));
            $this->info($this->createRequest('api'));
            $this->info($this->createResource());
            if ($this->createRoute) {
                $this->info($this->createRoute('api'));
            }
        }
        if ($this->mobile && $this->createRoute) {
            $this->info($this->createRoute('mobile'));
        }
        if($this->vue) {
            $this->info($this->createVuePages());
            $this->info($this->createVueRoute());
        }
        return CommandAlias::SUCCESS;
    }

    private function getData(): void
    {
        // get model name
        $this->modelName = text(
            label: 'Enter model name',
            placeholder: 'E.g. User',
            required: true
        );
        $this->modelObject = Str::camel($this->modelName);
        $this->modelName = ucfirst($this->modelObject);

        // get namespace
        $this->namespace = ucfirst(text(
            label: 'Enter namespace',
        ));

        // get app type
        $appType = multiselect(
            label: 'Select app type?',
            options: [
                self::APP_TYPE_API => 'Api',
                self::APP_TYPE_WEB => 'Web',
                self::APP_TYPE_MOBILE => 'Mobile',
            ],
            default: [self::APP_TYPE_API],
            required: true
        );
        $this->api = in_array(self::APP_TYPE_API, $appType);
        $this->web = in_array(self::APP_TYPE_WEB, $appType);
        $this->mobile = in_array(self::APP_TYPE_MOBILE, $appType);

        // ask to create vuejs pages
        $this->vue = confirm(
            label: 'Create vuejs components and composition file?',
            required: true
        );

        // ask to create routes
        $this->createRoute = confirm(
            label: 'Create the route for you?',
            default: true,
            required: true
        );

        // get middleware
        if ($this->createRoute) {
            $this->apiMiddleware = text(
                label: 'Enter middleware for routes',
                placeholder: 'E.g. auth:sanctum'
            );   
        }

        // ask to create vuejs routes
        if ($this->vue) {
            $this->createVueRoute = confirm(
                label: 'Create vuejs routes for you?',
                default: true,
                required: true
            );
        }
    }

    public function createModel(): string
    {
        $this->call('make:model', ['name' => $this->modelName, '-m' => true]);
        return Artisan::output();
    }

    public function createRepository(): string
    {
        $modelName = $this->modelName;
        $template = file_get_contents(base_path('stubs/repository.stub'));
        $templateClass = $this->replaceTemplateContent($template);
        $directory = $this->getRepositoriesPath();
        $this->makeDirectory($directory . 'SQL');
        $filePath = $directory . 'SQL';
        $fileName = $modelName . 'Repository' . '.php';
        return $this->saveTemplateCopy($filePath, $fileName, $templateClass);
    }

    public function createVuePages(): string
    {
        $modelName = $this->modelName;
        return $this->createIndexPage($modelName) .', '.
        $this->createFormPage($modelName). ', '.
        $this->createViewPage($modelName). ', '.
        $this->createVueComposition($modelName);
    }

    public function createIndexPage($modelName): string
    {
        $template = file_get_contents(base_path('stubs/vue/pages/index-page.stub'));
        $templateClass = $this->replaceTemplateContent($template);
        $directory = $this->getVueResourcesPath();
        $this->makeDirectory($directory . Str::lower($modelName));
        $filePath = $directory . Str::lower($modelName);
        $fileName = $modelName . 'Index' . '.vue';
        return $this->saveTemplateCopy($filePath, $fileName, $templateClass);
    }

    public function createFormPage($modelName): string
    {
        $template = file_get_contents(base_path('stubs/vue/pages/form-page.stub'));
        $templateClass = $this->replaceTemplateContent($template);
        $directory = $this->getVueResourcesPath();
        $this->makeDirectory($directory . Str::lower($modelName));
        $filePath = $directory . Str::lower($modelName);
        $fileName = $modelName . 'Form' . '.vue';
        return $this->saveTemplateCopy($filePath, $fileName, $templateClass);
    }

    public function createViewPage($modelName): string
    {
        $template = file_get_contents(base_path('stubs/vue/pages/view-page.stub'));
        $templateClass = $this->replaceTemplateContent($template);
        $directory = $this->getVueResourcesPath();
        $this->makeDirectory($directory . Str::lower($modelName));
        $filePath = $directory . Str::lower($modelName);
        $fileName = $modelName . 'View' . '.vue';
        return $this->saveTemplateCopy($filePath, $fileName, $templateClass);
    }

    public function createVueComposition($modelName): string
    {
        $template = file_get_contents(base_path('stubs/vue/model-api.stub'));
        $templateClass = $this->replaceTemplateContent($template);
        $directory = $this->getVueCompositionsPath();
        $filePath = $directory;
        $fileName = Str::lower($modelName) . '.api.js';
        return $this->saveTemplateCopy($filePath, $fileName, $templateClass);
    }

    public function createContract(): string
    {
        $modelName = $this->modelName;
        $template = file_get_contents(base_path('stubs/contract.stub'));
        $templateClass = $this->replaceTemplateContent($template, $modelName);
        $directory = $this->getRepositoriesPath();
        $this->makeDirectory($directory . 'Contracts');
        $filePath = $directory . 'Contracts';
        $fileName = $modelName . 'Contract' . '.php';
        return $this->saveTemplateCopy($filePath, $fileName, $templateClass);
    }

    public function createController($type): string
    {
        $file = base_path($type == 'web' ? 'stubs/custom-controller.web.stub' : 'stubs/custom-controller.api.stub');
        $template =  file_get_contents($file);
        $templateClass = $this->replaceTemplateContent($template);
        $directory = $this->getControllerPath($type);
        $this->makeDirectory($directory);
        $fileName = $this->modelName . 'Controller' . '.php';
        return $this->saveTemplateCopy($directory, $fileName, $templateClass);
    }

    public function getControllerPath($type): string
    {
        $ds = $this->ds;
        $controllerNamespace = 'Http' . $ds . 'Controllers' . $ds . $this->getNamespace();
        if ($type == 'api') {
            $controllerNamespace = 'Http' . $ds . 'Controllers' . $ds . 'Api' . $ds . 'V1' . $ds . $this->getNamespace();
        }
        return app_path() . $ds . $controllerNamespace;
    }

    public function createRequest($type): string
    {
        $template = file_get_contents(base_path('stubs/custom-request.stub'));
        $templateClass = $this->replaceTemplateContent($template);
        $directory = $this->getRequestPath($type);
        $this->makeDirectory($directory);
        $fileName = $this->modelName . 'Request' . '.php';
        return $this->saveTemplateCopy($directory, $fileName, $templateClass);
    }

    public function createRoute($type): string
    {
        $ds = $this->ds;
        $controllerNamespace = $this->getBaseControllerNamespace() . 'Api' . $ds . 'V1' . $this->getNamespace();
        $controllerNamespace = str_replace('/', '\\', $controllerNamespace);
        $controllerName = $this->modelName . 'Controller';
        $useStatement = "use $controllerNamespace\\{$controllerName};";
        $routesFilePath = base_path("routes/$type.php");
        $routesContent = File::get($routesFilePath); 
        // add use statment 
        if (!preg_match('/^use.*?'.$controllerName.';/m', $routesContent)) {
            $routesContent = preg_replace('/^(use .*?;)/m', "$1\n$useStatement", $routesContent, 1);
        }
        $routeName = Str::plural(Str::lower($this->modelName));
        $route = "Route::apiResource('$routeName', $controllerName::class);";
        if ($this->apiMiddleware) { 
            $route = "Route::apiResource('$routeName', $controllerName::class)->middleware('{$this->apiMiddleware}');";
        } 
        $routesContent .= "\n$route\n";
        File::put($routesFilePath, $routesContent);
        return "Route added to routes/$type.php successfully.";
    }

    public function createVueRoute(): string
    {
        $ds = $this->ds;

        $routeFilePath = $this->getVueRoutePath() . '/dashboard-routes.js';
        $routesContent = File::get($routeFilePath); 
        $lastBracePosition = strrpos($routesContent, '}');
        if ($lastBracePosition === false) {
            throw new Exception('No closing brace found in the file.');
        }
        $commaPosition = strpos($routesContent, ',', $lastBracePosition);
        if ($commaPosition !== false) {
            $routesContent = substr_replace($routesContent, '', $commaPosition, 1);
        }

        $modelName = ucfirst($this->modelName);
        $routeName = Str::plural(Str::lower($this->modelName));
        $componentsPath = "@views" . $ds . $routeName . $ds;
        $indexPath = $componentsPath . $modelName . 'Index.vue';
        $formPath = $componentsPath . $modelName . 'Form.vue';
        $viewPath = $componentsPath . $modelName . 'View.vue';
        $newRoute =  <<<EOD
        ,{
            path: "/$routeName",
            name: "$routeName",
            component: () => import("$indexPath"),
            meta: {
                requiresAuth: true,
                title: t("sidebar.$routeName"),
                action: "read",
                module: "$modelName",
            },
        },
        ,{
            path: "/$routeName/create",
            name: "$routeName.create",
            component: () => import("$formPath"),
            meta: {
                requiresAuth: true,
                title: t("sidebar.$routeName"),
                action: "create",
                module: "$modelName",
            },
        },
        ,{
            path: "/$routeName/edit/:id?",
            name: "$routeName.edit",
            component: () => import("$formPath"),
            meta: {
                requiresAuth: true,
                title: t("sidebar.$routeName"),
                action: "update",
                module: "$modelName",
            },
        },
        ,{
            path: "/$routeName/view/:id?",
            name: "$routeName.show",
            component: () => import("$viewPath"),
            meta: {
                requiresAuth: true,
                title: t("sidebar.$routeName"),
                action: "read",
                module: "$modelName",
            },
        },

        EOD;

        $newFileContent = substr_replace($routesContent, $newRoute, $lastBracePosition + 1, 0);
        File::put($routeFilePath, $newFileContent);
        return "Routes added to dashboard-routes.js successfully.";
    }

    public function createResource(): string
    {
        $template = file_get_contents(base_path('stubs/custom-resource.stub'));
        $templateClass = $this->replaceTemplateContent($template);
        $directory = $this->getResourcePath();
        $this->makeDirectory($directory);
        $fileName = $this->modelName . 'Resource' . '.php';
        return $this->saveTemplateCopy($directory, $fileName, $templateClass);
    }
    
    public function replaceTemplateContent($template): array|string
    {
        $search = [
            '{{modelName}}',
            '{{modelNameLower}}',
            '{{modelNamePlural}}',
            '{{namespace}}',
            '{{modelObject}}'
        ];
        $namespace = $this->namespace;
        $namespace = mb_substr($namespace,  -1) == '/' ? substr($namespace, 0, -1) : $namespace;
        $replace = [
            $this->modelName,
            Str::lower($this->modelName),
            Str::plural(Str::lower($this->modelName)),
            ($namespace) ? '\\' . str_replace('/', '\\', $namespace) : '',
            $this->modelObject ?? '',
        ];
        return str_replace($search, $replace, $template);
    }
}
