<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Disk
    |--------------------------------------------------------------------------
    |
    | This option controls the default disk that will be used when a file is uploaded.
    | You may set this to any of the disks defined in the "disks" array inside
    | the "filesystems.php" config file.
    |
    */

    'disk' => 's3',

    /*
    |--------------------------------------------------------------------------
    | Model
    |--------------------------------------------------------------------------
    |
    | This option specifies the model to be used when inserting file records
    | into the database.
    |
    */

    'model' => '\App\Models\File',

    /*
    |--------------------------------------------------------------------------
    | Default Directory
    |--------------------------------------------------------------------------
    |
    | This option determines the default directory where the uploaded files
    | will be stored. If set to null, files will be stored in the root of the
    | specified disk.
    |
    */

    'directory' => null,

    /*
    |--------------------------------------------------------------------------
    | Default Max Size
    |--------------------------------------------------------------------------
    |
    | This option sets the maximum allowed size for uploaded files, in megabytes (MB).
    | Files larger than this limit will not be uploaded.
    |
    */

    'maxSize' => 10,

    /*
    |--------------------------------------------------------------------------
    | Thumbnail Options
    |--------------------------------------------------------------------------
    |
    | These settings control how thumbnails are generated for image files
    | uploaded to the server. Users can choose between different methods
    | for creating thumbnails.
    |
    */

    'thumbnail' => [
        /*
        |--------------------------------------------------------------------------
        | Thumbnail Enabled
        |--------------------------------------------------------------------------
        |
        | This option specifies whether a thumbnail should be created during upload.
        |
        */

        'enabled' => true,

        /*
        |--------------------------------------------------------------------------
        | Thumbnail Generation Method
        |--------------------------------------------------------------------------
        |
        | This option specifies the method used to create the thumbnail.
        | You can choose between:
        |
        | - 'scale': Proportional scaling of the image to a specified size.
        | - 'resize': Resizing the image to specific width and height dimensions.
        |
        */

        'method' => 'scale', // Options: 'scale', 'resize'

        /*
        |--------------------------------------------------------------------------
        | Scale Options
        |--------------------------------------------------------------------------
        |
        | These settings apply when the 'scale' method is selected. The image
        | will be scaled proportionally to the specified size, maintaining
        | the aspect ratio.
        |
        */

        'scale' => [
            'size' => 300 // The target size (in pixels) for the scaled image's largest dimension (width or height).
        ],

        /*
        |--------------------------------------------------------------------------
        | Resize Options
        |--------------------------------------------------------------------------
        |
        | These settings apply when the 'resize' method is selected. The image
        | will be resized to the exact width and height specified here, which
        | may alter the image's aspect ratio.
        |
        */

        'resize' => [
            'width' => 300,  // The target width (in pixels) for the resized image.
            'height' => 300, // The target height (in pixels) for the resized image.
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Unique File Names
    |--------------------------------------------------------------------------
    |
    | This option controls whether the uploader should generate unique file
    | names for each uploaded file by adding a unique prefix. This helps
    | prevent overwriting files with the same name.
    |
    */

    'unique' => true,

    /*
    |--------------------------------------------------------------------------
    | Prefix
    |--------------------------------------------------------------------------
    |
    | If specified, this value will be prefixed to the file name of every uploaded
    | file. This can be useful for categorizing or organizing files.
    |
    */

    'prefix' => null,

    /*
    |--------------------------------------------------------------------------
    | Signature Options
    |--------------------------------------------------------------------------
    |
    | These settings control the generation of signed URLs, which are used
    | to securely upload files.
    |
    */

    'signature' => [
        /*
        |--------------------------------------------------------------------------
        | Validity Time
        |--------------------------------------------------------------------------
        |
        | This option sets the time (in minutes) that a signed URL remains valid.
        | After this time has passed, the URL will expire, and the file upload
        | will no longer be allowed.
        |
        */
        'valid' => 5 // Validity duration of the signed URL in minutes.
    ]
];
