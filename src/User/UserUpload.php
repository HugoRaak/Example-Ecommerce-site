<?php

declare(strict_types=1);

namespace App\User;

use Framework\Upload;

final class UserUpload extends Upload
{
    protected string $path = 'uploads/article';

    /**
     * @var mixed[]
     */
    protected array $formats = [
        'thumb' => [200, 200],
        'icon' => [80, 80]
    ];
}
