CKFinder ImageWatermark plugin
==============================

This plugin adds a watermark to images uploaded with CKFinder.

**Supported images extensions:** jpg, jpeg, gif, png.

## Configuration options

To set custom image to use as a watermark add following option to main CKFinder config file (usually named `config.php`):

```php
// ...
$config['ImageWatermark'] = [
    'imagePath' => __DIR__ . '/custom/image/path/stamp.png'
];
```

**Note:** make sure your image path is absolute, and for best results use transparent png image.


To change default watermark position you need to add `position` option in plugin configuration node:

```php
// ...
$config['ImageWatermark'] = [
    'imagePath' => __DIR__ . '/custom/image/path/stamp.png', // Use also custom image
    'position' => [
        'right'  => 0,
        'bottom' => 0
    ]
];
```

The `position` option takes two arguments corresponding to image borders. Possible `position` keys values:
`top`, `right`, `bottom`, `left`. Suboptions `top`-`bottom` and `left`-`right` are mutually exclusive, and can't
be used together. Each position suboption can take as value an integer - a distance to chosen border measured in
pixels, or string 'center' to make watermark centered between current and opposite border.


A few examples of watermark (▣) position for used `position` option:

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