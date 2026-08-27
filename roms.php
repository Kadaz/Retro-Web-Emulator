<?php

header('Content-Type: application/json; charset=utf-8');

$romFolder = __DIR__ . DIRECTORY_SEPARATOR . 'roms';

$systems = [

    'snes' => [
        'name' => 'SNES',
        'core' => 'snes',
        'extensions' => ['sfc', 'smc']
    ],

    'nes' => [
        'name' => 'NES',
        'core' => 'nes',
        'extensions' => ['nes', 'fds']
    ],

    'segaMS' => [
        'name' => 'Master System',
        'core' => 'segaMS',
        'extensions' => ['sms']
    ],

    'segaMD' => [
        'name' => 'Mega Drive / Genesis',
        'core' => 'segaMD',
        'extensions' => ['md', 'gen', 'smd', 'bin']
    ],

    'segaGG' => [
        'name' => 'Game Gear',
        'core' => 'segaGG',
        'extensions' => ['gg']
    ],

    'gb' => [
        'name' => 'Game Boy',
        'core' => 'gb',
        'extensions' => ['gb']
    ],

    'gbc' => [
        'name' => 'Game Boy Color',
        'core' => 'gbc',
        'extensions' => ['gbc']
    ],

    'gba' => [
        'name' => 'Game Boy Advance',
        'core' => 'gba',
        'extensions' => ['gba']
    ]

];

$result = [];

foreach ($systems as $systemId => $system) {

    $result[$systemId] = [];

}


if (!is_dir($romFolder)) {

    echo json_encode([
        'success' => false,
        'error' => 'ROM folder not found: roms/'
    ]);

    exit;

}


$files = scandir($romFolder);


foreach ($files as $file) {

    if ($file === '.' || $file === '..') {
        continue;
    }


    $fullPath =
        $romFolder .
        DIRECTORY_SEPARATOR .
        $file;


    if (!is_file($fullPath)) {
        continue;
    }


    $extension =
        strtolower(
            pathinfo(
                $file,
                PATHINFO_EXTENSION
            )
        );


    foreach ($systems as $systemId => $system) {

        if (
            in_array(
                $extension,
                $system['extensions'],
                true
            )
        ) {

            $result[$systemId][] = [

                'name' => $file,

                'url' =>
                    'roms/' .
                    rawurlencode($file)

            ];

        }

    }

}


/*
Sort ROMs alphabetically
*/

foreach ($result as $systemId => &$roms) {

    usort(
        $roms,
        function ($a, $b) {

            return strnatcasecmp(
                $a['name'],
                $b['name']
            );

        }
    );

}

unset($roms);


echo json_encode([
    'success' => true,
    'systems' => $result
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);