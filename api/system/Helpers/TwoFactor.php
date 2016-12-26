<?php
namespace PHP_REST_API\Helpers;

/*
 * Enums for two factor
 */
abstract class TwoFactor extends BasicEnum {
    const None = 0;
    const Email = 1;
    const SMS = 2;
    const Device = 3;
}
