<?php

return [
    /*
     * Disque par défaut pour stocker les médias
     */
    'disk_name' => env('MEDIA_DISK', 'secure_uploads'),

    /*
     * Taille maximale d'upload (en Ko)
     * Par défaut : 10MB = 10240 Ko
     */
    'max_file_size' => 1024 * 1024 * 10,

    /*
     * Classe du modèle Media
     * Ne changez pas sauf si vous étendez le modèle
     */
    'media_model' => Spatie\MediaLibrary\MediaCollections\Models\Media::class,

    /*
     * Configuration des conversions d'images
     */
    'image_generators' => [
        Spatie\MediaLibrary\Conversions\ImageGenerators\Image::class,
        Spatie\MediaLibrary\Conversions\ImageGenerators\Webp::class,
        Spatie\MediaLibrary\Conversions\ImageGenerators\Pdf::class,
        Spatie\MediaLibrary\Conversions\ImageGenerators\Svg::class,
        Spatie\MediaLibrary\Conversions\ImageGenerators\Video::class,
    ],

    /*
     * Moteur de manipulation d'images
     * 'gd' ou 'imagick'
     */
    'image_driver' => env('IMAGE_DRIVER', 'gd'),

    /*
     * Chemin des conversions
     */
    'conversions_disk' => env('CONVERSIONS_DISK', 'public'),

    /*
     * Queue pour les jobs de conversion
     */
    'queue_name' => env('MEDIA_QUEUE', 'default'),

    /*
     * Préfixe pour les fichiers de conversions
     */
    'conversion_file_namer' => Spatie\MediaLibrary\Support\FileNamer\DefaultFileNamer::class,

    /*
     * Classe pour générer les chemins de fichiers
     */
    'path_generator' => Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator::class,

    /*
     * URL du CDN (optionnel)
     */
    'cdn_url' => env('CDN_URL', ''),

    /*
     * Préfixe des URLs
     */
    'url_generator' => Spatie\MediaLibrary\Support\UrlGenerator\DefaultUrlGenerator::class,

    /*
     * Responsive images (désactivé par défaut)
     */
    'generate_thumbnails_for_temporary_uploads' => false,

    /*
     * ⚠️ SECTION MEDIA LIBRARY PRO (PAYANT - NON UTILISÉ) ⚠️
     * Vous pouvez supprimer ou commenter ces lignes
     */
    // 'enable_temporary_uploads_session_affinity' => true,
    // 'temporary_upload_model' => Spatie\MediaLibraryPro\Models\TemporaryUpload::class,

    /*
     * Responsive images (version gratuite limitée)
     */
    'responsive_images' => [
        'use_tiny_placeholders' => true,
        'tiny_placeholder_generator' => Spatie\MediaLibrary\ResponsiveImages\TinyPlaceholderGenerator\Blurred::class,
    ],

    /*
     * Remote configuration (S3, etc.)
     */
    'remote' => [
        'extra_headers' => [
            'CacheControl' => 'max-age=604800',
        ],
    ],
];
