<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\View\Concerns\Identity\NumberCounterIdentity;

class NumberCounterWidget extends BaseWidget
{
    use NumberCounterIdentity;
}
