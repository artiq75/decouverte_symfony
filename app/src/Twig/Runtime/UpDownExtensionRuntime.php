<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class UpDownExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function upDown($value)
    {
        $value = strtolower($value);

        for ($i = 0; $i < strlen($value); $i += 2) {
            $value[$i] = strtoupper($value[$i]);
        }

        return $value;
    }
}
