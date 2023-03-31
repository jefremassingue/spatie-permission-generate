<?php

namespace Jefre\SpatiePermissionGenerate;

use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class SpatiePermissionGenerate{

    private static $ignore_classes;
    public function __construct()
    {
        self::$ignore_classes = explode(",", config('spatie-permission-generate.ignore_classes_files')  ?? '');
    }
    public static function synchronizelPermission()
    {
        if (self::allPermission()) {
            return true;
        } else {
            return false;
        }
    }

    private static function classList()
    {
        // $path = __DIR__;
        $path = base_path(config('spatie-permission-generate.controllers_root_path'));
        $fqcns = array();
        // dd($path);

        $allFiles = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
        $phpFiles = new \RegexIterator($allFiles, '/\.php$/');
        foreach ($phpFiles as $phpFile) {
            $content = file_get_contents($phpFile->getRealPath());
            $tokens = token_get_all($content);
            $namespace = '';
            for ($index = 0; isset($tokens[$index]); $index++) {
                if (!isset($tokens[$index][0])) {
                    continue;
                }
                if (T_NAMESPACE === $tokens[$index][0]) {
                    $index += 2; // Skip namespace keyword and whitespace
                    while (isset($tokens[$index]) && is_array($tokens[$index])) {
                        $namespace .= $tokens[$index++][1];
                    }
                }
                if (T_CLASS === $tokens[$index][0] && T_WHITESPACE === $tokens[$index + 1][0] && T_STRING === $tokens[$index +
                    2][0]) {
                    $index += 2; // Skip class keyword and whitespace
                    if ($namespace != 'App\Http\Controllers\Auth') {
                        $fqcns[] = $namespace . '\\' . $tokens[$index][1];
                    }


                    # break if you have one class per file (psr-4 compliant)
                    # otherwise you'll need to handle class constants (Foo::class)
                    break;
                }
            }
        }

        return $fqcns;
    }

    private static function allPermission()
    {
        $status = false;
        self::$ignore_classes = explode(",", config('spatie-permission-generate.ignore_classes_files')  ?? '');

        foreach (self::classList() as $class_item) {

            // $class = str_replace('App\Http\Controllers\\', '', $class_item);
            $class = explode('\\', $class_item);
            $class = $class[count($class) -1];
            // dd(!in_array($class, self::ignore_classes));
            if (
                !in_array($class, self::$ignore_classes ?? [])
            ) {

                $methods = self::get_this_class_methods($class_item);
                foreach ($methods as $method_name) {
                    if (
                        '__construct' != $method_name &&
                        'get_this_class_methods' != $method_name &&
                        substr($method_name, 0, 3) != "___" // Para egnorar methods que iniciam cm ___
                    ) {

                        $class_name = str_replace('Controllers', '', $class);
                        $class_name = str_replace('Controller', '', $class);
                        // echo $class_name . '-' . $method_name . "\n";
                        // dd([$class, $class_name]);
                        $permission_name = str_replace('Controler', '', $class_name . '-' . $method_name);
                        $permission_name = str_replace('\\', '-', $permission_name);
                        // dd($permission_name);
                        if (!Permission::where('name', $permission_name)->first()) {
                            $permission = Permission::create([
                                'name' => Str::lower($permission_name),
                                'guard_name' => 'web',
                            ]);
                            if ($permission) {
                                $status = true;
                            }
                        }
                    }
                }
            }
        }


        return $status;
    }


    private static function get_this_class_methods($class)
    {
        $array1 = get_class_methods($class);
        if ($parent_class = get_parent_class($class)) {
            $array2 = get_class_methods($parent_class);
            $array3 = array_diff($array1, $array2);
        } else {
            $array3 = $array1;
        }

        return $array3;
    }

}
