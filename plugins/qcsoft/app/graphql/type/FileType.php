<?php

namespace Qcsoft\App\GraphQL\Type;

use GraphQL\Type\Definition\ResolveInfo;
use Qcsoft\App\GraphQL\AppContext;
use Qcsoft\App\GraphQL\Types;
use GraphQL\Type\Definition\ObjectType;
use System\Models\File;

class FileType extends ObjectType
{
    use ResolveFieldTrait;

    public function __construct()
    {
        $config = [
            'name'         => 'File',
            'description'  => 'File',
            'fields'       => function ()
            {
                return [
                    'id'              => Types::id(),
                    'disk_name'       => Types::string(),
                    'file_name'       => Types::string(),
                    'file_size'       => Types::int(),
                    'content_type'    => Types::string(),
                    'title'           => Types::string(),
                    'description'     => Types::string(),
                    'field'           => Types::string(),
                    'attachment_id'   => Types::string(),
                    'attachment_type' => Types::string(),
                    'is_public'       => Types::boolean(),
                    'sort_order'      => Types::int(),
                    'created_at'      => Types::string(),
                    'updated_at'      => Types::string(),
                    'thumb'           => [
                        'type' => Types::string(),
                        'args' => [
                            'w'    => Types::int(),
                            'h'    => Types::int(),
                            'mode' => Types::string(),
                        ]
                    ],
                    'path'            => Types::string(),
                ];
            },
            'resolveField' => [$this, 'resolveField'],
        ];

        parent::__construct($config);
    }

    public function resolveThumb(File $file, $args, AppContext $appContext, ResolveInfo $resolveInfo)
    {
        return $file->getThumb($args['w'], $args['h'], ['mode' => $args['mode']]);
    }
}
