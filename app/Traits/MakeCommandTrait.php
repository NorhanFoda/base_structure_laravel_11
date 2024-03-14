<?php

namespace App\Traits;

trait MakeCommandTrait
{
    protected string $ds = DIRECTORY_SEPARATOR;

    public function saveTemplateCopy($filePath, $fileName, $template): string
    {
        file_put_contents($filePath . $this->ds . $fileName, $template);
        return $fileName . ' has been created';
    }
    public function getTemplate($name): string
    {
        return file_get_contents(base_path('stubs/' . $name . '.stub'));
    }
    public function getAppPath($path): string
    {
        $ds = $this->ds;
        $appBase = app_path() . $ds;
        $path = str_replace('/', $ds, $path);
        $namespace = $path . $ds;
        return $appBase . $namespace;
    }
    public function makeDirectory($directory): ?bool
    {
        if (!is_dir($directory)) {
            return mkdir($directory, 0755, true);
        }
        return null;
    }

    public function getBaseControllerNamespace(): string
    {
        $ds = $this->ds;
        return 'App'. $ds . 'Http' . $ds . 'Controllers' . $ds;
    }

    public function getRepositoriesPath(): string
    {
        $ds = $this->ds;
        $appBase = app_path() . $ds;
        $repositoriesNamespace = 'Repositories' . $ds;
        return $appBase . $repositoriesNamespace;
    }

    public function getVueResourcesPath(): string
    {
        $ds = $this->ds;
        $appBase = base_path() . $ds;
        $resourcesPath = 'resources' . $ds . 'app' . $ds . 'views' . $ds;
        return $appBase . $resourcesPath;
    }

    public function getVueCompositionsPath(): string
    {
        $ds = $this->ds;
        $appBase = base_path() . $ds;
        $compositionsPath = 'resources' . $ds . 'app' . $ds . 'api' . $ds;
        return $appBase . $compositionsPath;
    }
    
    public function getVueRoutePath(): string
    {
        $ds = $this->ds;
        $appBase = base_path() . $ds;
        $compositionsPath = 'resources' . $ds . 'app' . $ds . 'router' . $ds . 'routes' . $ds;
        return $appBase . $compositionsPath;
    }

    public function getNamespace(): string
    {
        if ($this->namespace != "") {
            return  $this->ds . str_replace('/', $this->ds, $this->namespace) . $this->ds;
        }else{
            return "";
        }
    }

    public function getRequestPath($type): string
    {
        $ds = $this->ds;
        $requestNamespace = 'Http' . $ds . 'Requests' . $ds;
        return app_path() . $ds . $requestNamespace;
    }

    public function getResourcePath(): string
    {
        $ds = $this->ds;
        $resourceNamespace = 'Http' . $ds . 'Resources' . $ds;
        return app_path() . $ds. $resourceNamespace;
    }
}
