<?php

namespace Qcsoft\App\GraphQL;

use GraphQL\Type\Definition\ResolveInfo;
use Qcsoft\App\GraphQL\Data\User;

/**
 * Instance available in all GraphQL resolvers as 3rd argument
 */
class AppContext
{
    /**
     * @var string
     */
    public $rootUrl;

    /**
     * @var \mixed
     */
    public $request;

}
