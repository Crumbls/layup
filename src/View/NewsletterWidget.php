<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\View\Concerns\Identity\NewsletterIdentity;

class NewsletterWidget extends BaseWidget
{
    use NewsletterIdentity;
}
