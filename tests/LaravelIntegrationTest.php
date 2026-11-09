<?php

declare(strict_types=1);

namespace AmountToWords\Tests;

use AmountToWords\AmountToWords as Converter;
use AmountToWords\Laravel\AmountToWordsServiceProvider;
use AmountToWords\Laravel\Facades\AmountToWords;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
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
}
