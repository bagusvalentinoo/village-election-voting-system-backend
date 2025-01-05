<?php

/**
 * Is not null array filter
 *
 * @return Closure
 */
function isNotNullArrayFilter(): Closure
{
    return fn($val) => !is_null($val);
}
