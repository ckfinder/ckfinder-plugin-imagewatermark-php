CKFinder ImageWatermark plugin
==============================

This plugin adds a watermark to images uploaded with CKFinder.

**Supported images extensions:** jpg, jpeg, gif, png

## Configuration options

To set custom image to use as a watermark add following option to main CKFinder config file (usually named `config.php`):

```php
// ...
'ImageWatermark' => array(
    'imagePath' => __DIR__ . '/custom/image/path/stamp.png'
),
```

**Note:** make sure your image path is absolute.


To change default watermark position you need to add `position` option in plugin configuration node:

```php
// ...
'ImageWatermark' => array(
    'imagePath' => __DIR__ . '/custom/image/path/stamp.png', // Use also custom image
    'position' => array(
        'right'  => 0,
        'bottom' => 0
    )
),
```

The `position` option takes two arguments corresponding to image borders. Possible `position` keys values:
`top`, `right`, `bottom`, `left`. Suboptions `top`-`bottom` and `left`-`right` are mutually exclusive, and can't
be used together. Each position suboption can take as value an integer - a distance to chosen border in
pixels, or string 'center' to make watermark centered between current and opposite border.


A few examples of watermark (▣) position for used `position` option:

```php
'ImageWatermark' => array(                  ┌────────────────┐
    'position' => array(                    │                │
        'right'  => 0,                      │                │
        'bottom' => 0                       │              ▣ │
    )                                       └────────────────┘
),
```


```php
'ImageWatermark' => array(                  ┌────────────────┐
    'position' => array(                    │                │
        'right'  => 'center',               │                │
        'bottom' => 0                       │        ▣       │
    )                                       └────────────────┘
),
```

```php
'ImageWatermark' => array(                  ┌────────────────┐
    'position' => array(                    │                │
        'right'  => 0,                      │               ▣│
        'bottom' => 'center'                │                │
    )                                       └────────────────┘
),
```