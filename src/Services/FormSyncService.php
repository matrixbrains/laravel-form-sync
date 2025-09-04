<?php

namespace Matrixbrains\FormSync\Services;

use Illuminate\Support\Facades\File;
use Matrixbrains\FormSync\Helpers\RuleParser;

class FormSyncService
{
    public function sync($requestClass)
    {
        if (!class_exists($requestClass)) {
            $requestClass = "App\\Http\\Requests\\" . $requestClass;
        }

        if (!class_exists($requestClass)) {
            throw new \Exception("FormRequest class {$requestClass} not found.");
        }

        $request = new $requestClass;
        $rules = $request->rules();
        $messages = method_exists($request, 'messages') ? $request->messages() : [];

        $schema = RuleParser::toSchema($rules, $messages);

        $file = resource_path("forms/".strtolower(class_basename($requestClass)).".json");
        File::ensureDirectoryExists(dirname($file));
        File::put($file, json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return $file;
    }

    public function syncAll()
    {
        $count = 0;
        foreach (glob(app_path("Http/Requests/*.php")) as $file) {
            $class = "App\\Http\\Requests\\".basename($file, ".php");
            $this->sync($class);
            $count++;
        }
        return $count;
    }
}
