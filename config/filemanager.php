<?php

return [
    'name'        => 'FileManager',
    'allowed_ext' => ['jpeg', 'jpg', 'png', 'webp', 'gif', 'doc', 'docx', 'xls', 'xlsx', 'pdf', 'zip', 'rar', 'mp4', 'avi', 'mov', 'mkv', 'webm'],

    /**
     * Bitta yuklanadigan fayl uchun maksimal hajm (KB).
     * 20 MB = 20480 KB.
     */
    'max_size_kb' => 20480,

    /**
     * Extension va uning ruxsat etilgan MIME turlari.
     */
    'ext_mime_map' => [
        'jpeg' => ['image/jpeg'],
        'jpg'  => ['image/jpeg'],
        'png'  => ['image/png'],
        'webp' => ['image/webp'],
        'gif'  => ['image/gif'],
        'doc'  => [
            'application/msword',
            'application/zip',
            'application/octet-stream',
            'application/x-zip-compressed',
            'application/x-zip',
        ],
        'docx' => [
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/zip',
            'application/octet-stream',
            'application/x-zip-compressed',
            'application/x-zip',
            'application/msword',
        ],
        'xls'  => [
            'application/vnd.ms-excel',
            'application/zip',
            'application/octet-stream',
            'application/x-zip-compressed',
        ],
        'xlsx' => [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/zip',
            'application/octet-stream',
            'application/x-zip-compressed',
            'application/x-zip',
            'application/vnd.ms-excel',
        ],
        'pdf'  => ['application/pdf', 'application/octet-stream'],
        'zip'  => ['application/zip', 'application/x-zip-compressed', 'application/octet-stream'],
        'rar'  => ['application/x-rar-compressed', 'application/vnd.rar', 'application/octet-stream'],
        'mp4'  => ['video/mp4'],
        'avi'  => ['video/x-msvideo', 'video/avi'],
        'mov'  => ['video/quicktime'],
        'mkv'  => ['video/x-matroska'],
        'webm' => ['video/webm'],
    ],

    'thumbs' => [
        'icon'   => ['w' => 50,   'h' => 50,  'q' => 80, 'slug' => 'icon'],
        'small'  => ['w' => 320,  'h' => 240, 'q' => 70, 'slug' => 'small'],
        'low'    => ['w' => 640,  'h' => 480, 'q' => 70, 'slug' => 'low'],
        'normal' => ['w' => 1024, 'h' => 728, 'q' => 70, 'slug' => 'normal'],
    ],

    'images_ext' => ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp'],

    'cdn_domain' => env('CDN_DOMAIN'),
];
