<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Disk
    |--------------------------------------------------------------------------
    |
    | This option controls the default disk that will be used when a file is uploaded. You may set this to
    | any of the disks defined in the "disk" array inside the "filesystems.php" config file.
    |
    */

    'disk' => 's3',

    /*
    |--------------------------------------------------------------------------
    | Model
    |--------------------------------------------------------------------------
    |
    | This option controls the model to be used when inserting files into the database.
    |
    */

    'model' => \App\Models\File::class,

    /*
    |--------------------------------------------------------------------------
    | Default Directory
    |--------------------------------------------------------------------------
    |
    | This option controls the default directory to which the file will be uploaded.
    |
    */

    'directory' => null,

    /*
    |--------------------------------------------------------------------------
    | Default Max Size
    |--------------------------------------------------------------------------
    |
    | This option controls the default allowed maximum size in MB of the file being uploaded.
    |
    */

    'maxSize' => 10,

    /*
    |--------------------------------------------------------------------------
    | Thumbnail Options
    |--------------------------------------------------------------------------
    */

    'thumbnail' => [
        /*
        |--------------------------------------------------------------------------
        | Default Thumbnail Dimensions
        |--------------------------------------------------------------------------
        |
        | These options control the default width and height in pixels of the thumbnail that will be
        | generated when the file being uploaded is an image.
        |
        */

        'width' => 256,
        'height' => 256,
    ],

    /*
    |--------------------------------------------------------------------------
    | Unique File Names
    |--------------------------------------------------------------------------
    |
    | This option controls whether the uploader should add a unique prefix to guarantee a unique
    | file name for every uploaded file.
    |
    */

    'unique' => true,

    /*
    |--------------------------------------------------------------------------
    | Prefix
    |--------------------------------------------------------------------------
    |
    | If this option is not null, then every file that is uploaded will have its name prefixed
    | with the specified value.
    |
    */

    'prefix' => null,

    /*
    |--------------------------------------------------------------------------
    | Signature Options
    |--------------------------------------------------------------------------
    */

    'signature' => [
        /*
        |--------------------------------------------------------------------------
        | Validity Time
        |--------------------------------------------------------------------------
        |
        | This option sets the validity time of the generated signature URL in minutes. For example, if
        | the value is set to 5 then the client will have 5 minutes to upload the file using the
        | generated URL before it expires.
        |
        */
        'valid' => 5
    ]
];