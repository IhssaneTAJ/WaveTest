<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CurrencyExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('toEuro', [$this, 'convertToEuro']),   
        ];
    }

    public function convertToEuro($price)
    {
        return $price/10;
    }

    public function convertToDollar($price)
    {
        return $price/9;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('toDollar', [$this, 'convertToDollar']),
        ];
    }
}
