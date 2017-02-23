## Color/Colour

[![Build Status](https://travis-ci.org/votemike/color.svg?branch=master)](https://travis-ci.org/votemike/color)

Immutable object representing a CSS color with the abilty to format as hex, rgba and X11 strings.

## Usage
```php
// Construct with R,G,B,A values. Alpha may be ommited and will default to 1
$color = new Color(0, 100, 200);
$color = new Color(0, 100, 200, 0.5);
  
// Create Color object from string
$color = Color::fromRgba('0,0,0,0');
$color = Color::fromRgb('0,0,0');
$color = Color::fromHex('000000');
$color = Color::fromShortHex('F00');
$color = Color::fromX11('rebeccapurple');
$color = Color::fromHsla('180, 0%, 85%, 1');
$color = Color::fromHsl('0, 100%, 10%');
  
// Get strings for different formats
$color = new Color(255, 255, 255, 1);
$color->toHex(); // #ffffff
$color->toRgba(); // rgba(255,255,255,1)
$color->toX11(); // white
  
// If a color can't be translated, it will be an empty string
$color = new Color(0, 0, 0, 0.5);
$color->toHex(); // ''
$color->toRgba(); // rgba(255,255,255,0.5)
$color->toX11(); // ''
```

## TODO
* Create a toHsla() method
