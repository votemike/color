<?php namespace Votemike\Color\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Votemike\Color\Color;

class BasketTest extends TestCase
{

    public function testRetrievingValues()
    {
        $color = new Color(0, 0, 0, 0);
        $this->assertSame(0, $color->getRed());
        $this->assertSame(0, $color->getGreen());
        $this->assertSame(0, $color->getBlue());
        $this->assertSame(0.0, $color->getAlpha());

        $color = new Color(255, 255, 255, 0.5);
        $this->assertSame(255, $color->getRed());
        $this->assertSame(255, $color->getGreen());
        $this->assertSame(255, $color->getBlue());
        $this->assertSame(0.5, $color->getAlpha());

        $color = new Color(255, 255, 255);
        $this->assertSame(255, $color->getRed());
        $this->assertSame(255, $color->getGreen());
        $this->assertSame(255, $color->getBlue());
        $this->assertSame(1.0, $color->getAlpha());
    }

    public function testPassingHighRGBValueCausesError()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('R, G and B values must be between 0 and 255');
        new Color(256, 0, 0, 0);
    }

    public function testPassingLowRGBValueCausesError()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('R, G and B values must be between 0 and 255');
        new Color(-1, 0, 0, 0);
    }

    public function testPassingHighAlphaValueCausesError()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Alpha value must be between 0 and 1');
        new Color(0, 0, 0, 1.1);
    }

    public function testPassingLowAlphaValueCausesError()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Alpha value must be between 0 and 1');
        new Color(0, 0, 0, -0.1);
    }

    public function testToRgba()
    {
        $color = new Color(255, 255, 255, 1);
        $this->assertSame('rgba(255,255,255,1)', $color->toRgba());
        $color = new Color(255, 255, 255, 0);
        $this->assertSame('rgba(255,255,255,0)', $color->toRgba());
        $color = new Color(0, 0, 0, 0);
        $this->assertSame('rgba(0,0,0,0)', $color->toRgba());
    }

    public function testToHex()
    {
        $color = new Color(255, 255, 255, 1);
        $this->assertSame('#ffffff', $color->toHex());
        $color = new Color(255, 255, 255);
        $this->assertSame('#ffffff', $color->toHex());
        $color = new Color(255, 255, 255, 0.5);
        $this->assertSame('', $color->toHex());
    }

    public function testToX11()
    {
        $color = new Color(1, 2, 3);
        $this->assertEmpty($color->toX11());
        $color = new Color(0, 0, 0, 1);
        $this->assertSame('black', $color->toX11());
        $color = new Color(0, 0, 0, 0.5);
        $this->assertEmpty($color->toX11());
        $color = new Color(255, 255, 255);
        $this->assertSame('white', $color->toX11());
    }

    public function testGetColorFromRgba()
    {
        $color = Color::fromRgba('0,0,0,0');
        $this->checkRgba(0, 0, 0, 0.0, $color);
        $color = Color::fromRgba('255, 255, 255, 1');
        $this->checkRgba(255, 255, 255, 1.0, $color);
        $color = Color::fromRgba('255, 255, 255, 0.5');
        $this->checkRgba(255, 255, 255, 0.5, $color);
    }

    private function checkRgba(int $r, int $g, int $b, float $a, Color $color)
    {
        $this->assertSame($r, $color->getRed());
        $this->assertSame($g, $color->getGreen());
        $this->assertSame($b, $color->getBlue());
        $this->assertSame($a, $color->getAlpha());
    }

    public function testGetColorFromRgb()
    {
        $color = Color::fromRgb('0,0,0');
        $this->checkRgba(0, 0, 0, 1.0, $color);
    }

    public function testGetColorFromHex()
    {
        $color = Color::fromHex('000000');
        $this->checkRgba(0, 0, 0, 1, $color);
        $color = Color::fromHex('FFFFFF');
        $this->checkRgba(255, 255, 255, 1, $color);
    }

    public function testGetColorFromShortHex()
    {
        $color = Color::fromShortHex('F00');
        $this->checkRgba(255, 0, 0, 1, $color);
        $color = Color::fromShortHex('00F');
        $this->checkRgba(0, 0, 255, 1, $color);
    }

    public function testGetColorFromX11()
    {
        $color = Color::fromX11('red');
        $this->checkRgba(255, 0, 0, 1.0, $color);
        $color = Color::fromX11('rebeccapurple');
        $this->checkRgba(102, 51, 153, 1.0, $color);
    }

    public function testGetColorFromHsla()
    {
        $color = Color::fromHsla('180, 0%, 85%, 1');
        $this->checkRgba(217, 217, 217, 1.0, $color);
        $color = Color::fromHsla('0, 100%, 50%, 1');
        $this->checkRgba(255, 0, 0, 1.0, $color);
        $color = Color::fromHsla('120, 100%, 50%, 1');
        $this->checkRgba(0, 255, 0, 1.0, $color);
        $color = Color::fromHsla('240, 100%, 50%, 1');
        $this->checkRgba(0, 0, 255, 1.0, $color);
        $color = Color::fromHsla('120, 50%, 50%, 0.5');
        $this->checkRgba(64, 191, 64, 0.5, $color);
        $color = Color::fromHsla('60, 50%, 50%, 0.5');
        $this->checkRgba(191, 191, 64, 0.5, $color);
    }

    public function testGetColorFromHsl()
    {
        $color = Color::fromHsl('0, 100%, 10%');
        $this->checkRgba(51, 0, 0, 1.0, $color);
        $color = Color::fromHsl('50, 50%, 50%');
        $this->checkRgba(191, 170, 64, 1.0, $color);
        $color = Color::fromHsl('100, 100%, 50%');
        $this->checkRgba(85, 255, 0, 1.0, $color);
        $color = Color::fromHsl('150, 100%, 10%');
        $this->checkRgba(0, 51, 26, 1.0, $color);
        $color = Color::fromHsl('200, 100%, 80%');
        $this->checkRgba(153, 221, 255, 1.0, $color);
        $color = Color::fromHsl('250, 100%, 60%');
        $this->checkRgba(85, 51, 255, 1.0, $color);
        $color = Color::fromHsl('300, 100%, 40%');
        $this->checkRgba(204, 0, 204, 1.0, $color);
        $color = Color::fromHsl('350, 100%, 20%');
        $this->checkRgba(102, 0, 17, 1.0, $color);
    }
}
