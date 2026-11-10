<?php

namespace Jefre\SpatiePermissionGenerate\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static bool synchronizelPermission()
 * @method static int changeGuardWithPrefix(string $prefix = 'api-', string $guard = 'api')
 *
 * @see \Jefre\SpatiePermissionGenerate\SpatiePermissionGenerate
 */
class SpatiePermissionGenerate extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Jefre\SpatiePermissionGenerate\SpatiePermissionGenerate::class;
    }
}
