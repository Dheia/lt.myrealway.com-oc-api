<?php
namespace Qcsoft\App\GraphQL;

use Exception;
use Qcsoft\App\GraphQL\Type\BundleProductCustomergroupType;
use Qcsoft\App\GraphQL\Type\BundleProductType;
use Qcsoft\App\GraphQL\Type\BundleType;
use Qcsoft\App\GraphQL\Type\CatalogitemItemType;
use Qcsoft\App\GraphQL\Type\CatalogitemRelevantitemType;
use Qcsoft\App\GraphQL\Type\CatalogitemType;
use Qcsoft\App\GraphQL\Type\CategoryType;
use Qcsoft\App\GraphQL\Type\FileType;
use Qcsoft\App\GraphQL\Type\FilteroptionType;
use Qcsoft\App\GraphQL\Type\FilterType;
use Qcsoft\App\GraphQL\Type\CustompageType;
use Qcsoft\App\GraphQL\Type\PageOwnerType;
use Qcsoft\App\GraphQL\Type\PageType;
use Qcsoft\App\GraphQL\Type\ProductType;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\Type;

/**
 * Acts as a registry and factory for your types.
 *
 * As simplistic as possible for the sake of clarity of this example.
 * Your own may be more dynamic (or even code-generated).
 */
class Types
{
    private static $types = [];
    const LAZY_LOAD_GRAPHQL_TYPES = false;

    public static function bundleProductCustomergroup() { return static::get(BundleProductCustomergroupType::class); }
    public static function bundleProduct() { return static::get(BundleProductType::class); }
    public static function bundle() { return static::get(BundleType::class); }
    public static function catalogitemItem() { return static::get(CatalogitemItemType::class); }
    public static function catalogitem() { return static::get(CatalogitemType::class); }
    public static function catalogitemRelevantitem() { return static::get(CatalogitemRelevantitemType::class); }
    public static function category() { return static::get(CategoryType::class); }
    public static function file() { return static::get(FileType::class); }
    public static function filter() { return static::get(FilterType::class); }
    public static function filteroption() { return static::get(FilteroptionType::class); }
    public static function custompage() { return static::get(CustompageType::class); }
    public static function pageOwner() { return static::get(PageOwnerType::class); }
    public static function page() { return static::get(PageType::class); }
    public static function product() { return static::get(ProductType::class); }

    public static function get($classname)
    {
        return static::LAZY_LOAD_GRAPHQL_TYPES ? function() use ($classname) {
            return static::byClassName($classname);
        } : static::byClassName($classname);
    }

    protected static function byClassName($classname) {
        $parts = explode("\\", $classname);
        $cacheName = strtolower(preg_replace('~Type$~', '', $parts[count($parts) - 1]));
        $type = null;

        if (!isset(self::$types[$cacheName])) {
            if (class_exists($classname)) {
                $type = new $classname();
            }

            self::$types[$cacheName] = $type;
        }

        $type = self::$types[$cacheName];

        if (!$type) {
            throw new Exception("Unknown graphql type: " . $classname);
        }
        return $type;
    }

    public static function byTypeName($shortName, $removeType=true)
    {
        $cacheName = strtolower($shortName);
        $type = null;

        if (isset(self::$types[$cacheName])) {
            return self::$types[$cacheName];
        }

        $method = lcfirst($shortName);
        if(method_exists(get_called_class(), $method)) {
            $type = self::{$method}();
        }

        if(!$type) {
            throw new Exception("Unknown graphql type: " . $shortName);
        }
        return $type;
    }

    /**
     * @return \GraphQL\Type\Definition\BooleanType
     */
    public static function boolean()
    {
        return Type::boolean();
    }

    /**
     * @return \GraphQL\Type\Definition\FloatType
     */
    public static function float()
    {
        return Type::float();
    }

    /**
     * @return \GraphQL\Type\Definition\IDType
     */
    public static function id()
    {
        return Type::id();
    }

    /**
     * @return \GraphQL\Type\Definition\IntType
     */
    public static function int()
    {
        return Type::int();
    }

    /**
     * @return \GraphQL\Type\Definition\StringType
     */
    public static function string()
    {
        return Type::string();
    }

    /**
     * @param Type $type
     * @return ListOfType
     */
    public static function listOf($type)
    {
        return new ListOfType($type);
    }

    /**
     * @param Type $type
     * @return NonNull
     */
    public static function nonNull($type)
    {
        return new NonNull($type);
    }
}
