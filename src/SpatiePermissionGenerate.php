<?php

namespace Jefre\SpatiePermissionGenerate;

use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class SpatiePermissionGenerate
{
    /**
     * Array of classes to ignore when scanning for methods.
     *
     * @var array
     */
    private static $ignore_classes;

    /**
     * Array of methods and functions to ignore when scanning for methods.
     *
     * @var array
     */
    private static $ignore_methods_and_functions;

    /**
     * Array of controller class suffixes to look for when scanning for methods.
     *
     * @var array
     */
    private static $controller_classes_suffixes;

    /**
     * The name of the default guard.
     *
     * @var string
     */
    private static $default_guard;

    /**
     * Initialize configuration.
     */
    private static function initConfiguration()
    {
        self::$ignore_classes = explode(",", config('spatie-permission-generate.ignore_classes_files') ?? '');
        self::$ignore_methods_and_functions = explode(",", config('spatie-permission-generate.ignore_methods_and_functions') ?? '');
        self::$controller_classes_suffixes = explode(",", config('spatie-permission-generate.controller_classes_suffixes') ?? 'Controller');
        self::$default_guard = config('spatie-permission-generate.default_guard');
    }

    public function __construct()
    {
        self::initConfiguration();
    }

    /**
     * Synchronize permissions for all controller classes and methods.
     *
     * @return bool True if synchronization was successful, false otherwise.
     */
    public static function syncPermissions(): bool
    {
        self::initConfiguration();
        return self::generatePermissions();
    }

    /**
     * @deprecated Use syncPermissions instead.
     */
    public static function synchronizelPermission(): bool
    {
        return self::syncPermissions();
    }

    /**
     * Change the guard name for permissions with a given prefix.
     *
     * @param string $prefix The prefix of the permissions to be changed.
     * @param string $guard  The new guard name.
     *
     * @return int The number of permissions that were updated.
     */
    public static function changeGuardWithPrefix($prefix = 'api-', $guard = 'api')
    {
        return Permission::where('name', 'like', $prefix . '%')->update(['guard_name' => $guard]);
    }

    /**
     * @deprecated Use changeGuardWithPrefix instead.
     */
    public static function chanteGuardWithPrefix($prefix = 'api-', $guard = 'api')
    {
        return self::changeGuardWithPrefix($prefix, $guard);
    }

    /**
     * Returns a list of all classes found in the configured directory.
     *
     * @return array An array of fully-qualified class names.
     */
    private static function classList()
    {
        $path = base_path(config('spatie-permission-generate.controllers_root_path'));
        $fqcns = array();

        if (!file_exists($path)) {
            return [];
        }

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
                if (T_CLASS === $tokens[$index][0] && T_WHITESPACE === $tokens[$index + 1][0] && T_STRING === $tokens[$index + 2][0]) {
                    $index += 2; // Skip class keyword and whitespace
                    $fqcns[] = $namespace . '\\' . $tokens[$index][1];
                    break;
                }
            }
        }

        return $fqcns;
    }

    /**
     * Core logic to generate permissions.
     *
     * @return bool True if synchronization was successful, false otherwise.
     */
    private static function generatePermissions()
    {
        $status = false;

        $controllersRootPath = config('spatie-permission-generate.controllers_root_path');
        // Extract the last directory name from the path to use as a delimiter
        $pathParts = explode('/', $controllersRootPath);
        $lastPathPart = end($pathParts);

        foreach (self::classList() as $class_item) {

            // Logic to determine the relative class name for permission naming
            // e.g. App\Http\Controllers\Api\UserController -> Api\UserController
            // if lastPathPart is "Controllers"

            $classParts = explode($lastPathPart . '\\', $class_item);
            if (count($classParts) > 1) {
                $classNameRelative = end($classParts);
            } else {
                // Fallback: use class name only if path matching fails
                 $parts = explode('\\', $class_item);
                 $classNameRelative = end($parts);
            }

            if (!in_array($classNameRelative, self::$ignore_classes ?? []) && !in_array($class_item, self::$ignore_classes ?? [])) {

                $methods = self::get_this_class_methods($class_item);
                foreach ($methods as $method_name) {
                    if (
                        !in_array($method_name, self::$ignore_methods_and_functions) &&
                        !Str::startsWith($method_name, '___')
                    ) {
                        $permissionGroup = $classNameRelative;
                        foreach (self::$controller_classes_suffixes as $suffix) {
                            $permissionGroup = str_replace($suffix, '', $permissionGroup);
                        }

                        $permission_name = strtolower(str_replace('\\', '-', $permissionGroup . '-' . $method_name));

                        if (!Permission::where('name', $permission_name)->exists()) {
                            $permission = Permission::create([
                                'name' => $permission_name,
                                'guard_name' => self::$default_guard,
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
        if (!class_exists($class)) {
            return [];
        }
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
