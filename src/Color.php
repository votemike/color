<?php namespace Votemike\Color;

use InvalidArgumentException;
use stdClass;

class Color
{
    /**
     * @var float
     */
    private $alpha;

    /**
     * @var int
     */
    private $blue;

    /**
     * @var int
     */
    private $green;

    /**
     * @var int
     */
    private $red;

    public function __construct(int $red, int $green, int $blue, float $alpha = 1.0)
    {
        if ($red < 0 || $red > 255 || $green < 0 || $green > 255 || $blue < 0 || $blue > 255) {
            throw new InvalidArgumentException('R, G and B values must be between 0 and 255');
        }
        if ($alpha < 0 || $alpha > 1.0) {
            throw new InvalidArgumentException('Alpha value must be between 0 and 1');
        }
        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
        $this->alpha = $alpha;
    }

    public static function fromHex(string $hex): Color
    {
        list($redHex, $greenHex, $blueHex) = str_split($hex, 2);

        return new self(hexdec($redHex), hexdec($greenHex), hexdec($blueHex));
    }

    public static function fromHsl(string $hsl): Color
    {
        return self::fromHsla($hsl . ',1');
    }

    public static function fromHsla(string $hsla): Color
    {
        list($hue, $saturation, $lightness, $alpha) = explode(',', $hsla);
        $h = $hue / 360;
        $s = str_replace('%', '', $saturation) / 100;
        $l = str_replace('%', '', $lightness) / 100;
        //shortcut
        if ($l <= 0) {
            return new self(0, 0, 0, $alpha);
        } elseif ($l >= 100) {
            return new self(255, 255, 255, $alpha);
        } elseif ($s <= 0) {
            $level = round(255 * $l);

            return new self($level, $level, $level, $alpha);
        }
        if ($l < 0.5) {
            $temp = $l * (1 + $s);
        } else {
            $temp = $l + $s - $l * $s;
        }
        $temp2 = 2 * $l - $temp;
        $red = self::parseHslColor($h + 1 / 3, $temp, $temp2);
        $green = self::parseHslColor($h, $temp, $temp2);
        $blue = self::parseHslColor($h - 1 / 3, $temp, $temp2);

        return new Color($red, $green, $blue, $alpha);
    }

    public static function fromRgb(string $rgb): Color
    {
        return self::fromRgba($rgb . ',1');
    }

    public static function fromRgba(string $rgba): Color
    {
        list($red, $green, $blue, $alpha) = explode(',', $rgba);

        return new self($red, $green, $blue, $alpha);
    }

    public static function fromShortHex(string $hex): Color
    {
        return new self(hexdec($hex[0] . $hex[0]), hexdec($hex[1] . $hex[1]), hexdec($hex[2] . $hex[2]));
    }

    public static function fromX11(string $x11): Color
    {
        $colors = self::grabColorNameInfo();

        return self::fromRgb($colors->$x11->rgb);
    }

    private static function grabColorNameInfo(): stdClass
    {
        return json_decode(file_get_contents('src/colors.json'));
    }

    private static function parseHslColor(float $color, float $temp1, float $temp2): int
    {
        if ($color < 0) {
            $color = $color + 1;
        } elseif ($color > 1) {
            $color = $color - 1;
        }

        if ($color < 1 / 6) {
            $color = $temp2 + ($temp1 - $temp2) * 6 * $color;
        } elseif ($color < 1 / 2) {
            $color = $temp1;
        } elseif ($color < 2 / 3) {
            $color = $temp2 + ($temp1 - $temp2) * (2 / 3 - $color) * 6;
        } else {
            $color = $temp2;
        }

        return (int)round(255 * $color);
    }

    public function getAlpha(): float
    {
        return $this->alpha;
    }

    public function getBlue(): int
    {
        return $this->blue;
    }

    public function getGreen(): int
    {
        return $this->green;
    }

    public function getRed(): int
    {
        return $this->red;
    }

    public function toHex(): string
    {
        if ($this->alpha < 1) {
            return '';
        }

        return '#' . sprintf('%02x', $this->red) . sprintf('%02x', $this->green) . sprintf('%02x', $this->blue);
    }

    public function toRgba(): string
    {
        return 'rgba(' . $this->red . ',' . $this->green . ',' . $this->blue . ',' . $this->alpha . ')';
    }

    public function toX11(): string
    {
        if ($this->alpha < 1) {
            return '';
        }

        $colors = (array)self::grabColorNameInfo();
        $color = array_filter(
            $colors,
            function ($c) {
                return $c->rgb == $this->red . ',' . $this->green . ',' . $this->blue;
            }
        );

        if (empty($color)) {
            return '';
        }

        return key($color);
    }
}
