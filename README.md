# CKFinder 3 ImageWatermark Plugin

This is an official CKFinder 3 plugin that adds a watermark to images uploaded with CKFinder.

**Supported image extensions:** `jpg`, `jpeg`, `gif`, `png`.

## Plugin Installation

See the [Plugin Installation and Configuration](http://docs.cksource.com/ckfinder3-php/plugins.html#plugins_installation_and_configuration) documentation.

## Configuration Options

To set a custom image to use as a watermark add the following option to the main CKFinder configuration file (usually named `config.php`):

```php
// ...
$config['ImageWatermark'] = [
    'imagePath' => __DIR__ . '/custom/image/path/stamp.png'
];
```

**Note:** Make sure that your image path is absolute, and use a transparent `png` image for best results.

To change the default watermark position you need to add the `position` option in the plugin configuration node:

```php
// ...
$config['ImageWatermark'] = [
    'imagePath' => __DIR__ . '/custom/image/path/stamp.png', // Also use a custom image.
    'position' => [
        'right'  => 0,
        'bottom' => 0
    ]
];
```

The `position` option takes two arguments corresponding to image borders.

Possible `position` key values are: `top`, `right`, `bottom`, `left`.

Suboptions `top`-`bottom` and `left`-`right` are mutually exclusive, and cannot be used together.

Each position suboption can take an integer as a value. This integer denotes the distance to the selected border measured in
pixels. Alternatively, you can use the `'center'` string to make the watermark centered between the current and the opposite border.

Here are a few examples of the watermark (▣) position for the following `position` options used:

```php
$config['ImageWatermark'] = [               ┌────────────────┐
    'position' => [                         │                │
        'right'  => 0,                      │                │
        'bottom' => 0                       │              ▣ │
    ]                                       └────────────────┘
];
```


```php
$config['ImageWatermark'] = [               ┌────────────────┐
    'position' => [                         │                │
        'right'  => 'center',               │                │
        'bottom' => 0                       │        ▣       │
    ]                                       └────────────────┘
];
```

```php
$config['ImageWatermark'] = [               ┌────────────────┐
    'position' => [                         │                │
        'right'  => 0,                      │               ▣│
        'bottom' => 'center'                │                │
    ]                                       └────────────────┘
];
```

## License

Copyright (c) 2007-2016, CKSource - Frederico Knabben. All rights reserved.
For license details see: [LICENSE.md](https://github.com/ckfinder/ckfinder-plugin-imagewatermark-php/blob/master/LICENSE.md).