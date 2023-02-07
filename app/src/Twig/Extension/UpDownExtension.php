<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\UpDownExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UpDownExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('up_down', [UpDownExtensionRuntime::class, 'UpDown']),
        ];
    }

    // public function getFunctions(): array
    // {
    //     return [
    //         new TwigFunction('function_name', [UpDownExtensionRuntime::class, 'doSomething']),
    //     ];
    // }
}
