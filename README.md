<h1 align="center">
    Bagisto Vite Parser
</h1>
<br/>
<div align="center">
  <img src="./preview/images/bagisto-vite-parser-gymmed.png" alt="Parses bagisto vite document."/>
</div>

Neat way to locate documents build with vite laravel library in bagisto project.
Helps to find manifest.json while using config/bagisto-vite.

## Installation

You can install the package via composer:

```bash
composer require gymmed/bagisto-vite-parser
```

## Use Case

[Dompdf](https://github.com/dompdf/dompdf) and its laravel wrapper, [laravel-dompdf](https://github.com/barryvdh/laravel-dompdf),
only support direct CSS or external CSS file links. However, these CSS files require real relative paths, while Vite in Laravel uses hashed filenames to avoid name collisions. This library provides an easy way to retrieve the correct CSS paths from config/bagisto-vite, specifically designed for [Bagisto](https://github.com/bagisto/bagisto), not for general Laravel usage.

## Usage

In your config/bagisto-vite.php, add a new entry for your package under the viters section. Example:

```php
    return [
        'viters' => [
            ...
            'myNamespace' => [
                'hot_file'                 => 'myNamespace-default-vite.hot',
                'build_directory'          => 'themes/myNamespace/default/build',
                'package_assets_directory' => 'src/Resources/assets',
            ],
        ],
    ];
```

This should correspond to the laravel plugin configuration in your package's vite.config.js. Example:

```php
plugins: [
    ...
    vue(),

    laravel({
        hotFile: "../../../public/myNamespace-default-vite.hot",
        publicDirectory: "../../../public",
        buildDirectory: "themes/myNamespace/default/build",
        input: [
            "src/Resources/assets/css/app.css",
            "src/Resources/assets/js/app.js",
        ],
        refresh: true,
    }),
],
```

To get documents real paths we write:

```php
use GymMed\BagistoViteParser;

//provide full path
$viteDocumentsPaths = BagistoViteParser::getDocumentsPaths(
    [
        'src/Resources/assets/css/app.css',
        'src/Resources/assets/js/app.js'
    ],
    'myNamespace'
);
```

returned results:

```php
array:2 [▼
  0 => "...\bagisto\public\themes/myNamespace/befault/build/assets/app-2bf84331.css"
  1 => "...\bagisto\public\themes/myNamespace/default/build/assets/app-c35c0f3a.js"
]
```

and you can get single document path:

```php
use GymMed\BagistoViteParser;

//provide full path
$viteDocumentsPaths = BagistoViteParser::getDocumentPath(
    'src/Resources/assets/css/app.css',
    'myNamespace'
);
```
