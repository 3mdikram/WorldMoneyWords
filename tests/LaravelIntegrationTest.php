<?php

declare(strict_types=1);

namespace AmountToWords\Tests;

use AmountToWords\AmountToWords as Converter;
use AmountToWords\Laravel\AmountToWordsServiceProvider;
use AmountToWords\ConversionOptions;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use mdikram\Facades\AmountToWords;
use PHPUnit\Framework\TestCase;

final class LaravelIntegrationTest extends TestCase
{
    protected function tearDown(): void
    {
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication(null);
        Container::setInstance(null);

        parent::tearDown();
    }

    public function testLaravelClassesAreAutoloadableFromMainPackage(): void
    {
        self::assertTrue(class_exists(AmountToWordsServiceProvider::class));
        self::assertTrue(class_exists(AmountToWords::class));
        self::assertTrue(class_exists(\AmountToWords\Laravel\Facades\AmountToWords::class));
    }

    public function testFacadeResolvesRegisteredConverter(): void
    {
        $container = new Container();
        Container::setInstance($container);
        Facade::setFacadeApplication($container);

        (new AmountToWordsServiceProvider($container))->register();

        self::assertInstanceOf(Converter::class, $container->make('amount-to-words'));
        self::assertSame(
            'One Thousand Two Hundred Thirty Four Rupees and Fifty Paise Only',
            AmountToWords::convert('1234.50', 'IN', 'INR'),
        );
    }

    public function testFacadeSupportsShortPakistaniCurrencyName(): void
    {
        $container = new Container();
        Container::setInstance($container);
        Facade::setFacadeApplication($container);
        (new AmountToWordsServiceProvider($container))->register();

        self::assertSame(
            'Seven Hundred Forty Five Rupees and Sixty One Paisa Only',
            AmountToWords::convert(
                '745.61',
                'PK',
                'PKR',
                new ConversionOptions(shortCurrencyName: true),
            ),
        );
    }
}
