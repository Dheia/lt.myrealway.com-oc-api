<?php namespace Qcsoft\App\GraphQL;

use GraphQL\Type\Definition\ResolveInfo;
use October\Rain\Database\Collection;

class OcHelper
{
    public static function argsModelQueryMixin()
    {
        return [
            'selectOrderBy' => Types::listOf(Types::string()),
            'selectTake'    => Types::int(),
            'selectWhere'   => Types::listOf(Types::string()),
            'selectWhereIn' => Types::listOf(Types::string()),
            'selectWith'    => Types::listOf(Types::string()),
            'selectScopes'  => Types::listOf(Types::listOf(Types::string())),
        ];
    }

    public static function argsCollectionMixin()
    {
        $collectionClass = new \ReflectionClass(Collection::class);

        dump($collectionClass->getMethods());
        die;
        return [
            'orderBy' => Types::listOf(Types::string()),
            'take'    => Types::int(),
            'where'   => Types::listOf(Types::string()),
            'with'    => Types::listOf(Types::string()),
        ];
    }

    public static function handleEloquentModel($rootValue, $args, ResolveInfo $resolveInfo, $classname)
    {
        $query = $classname::query();

//        $result = \Event::fire('qcsoft.app.graphqlEloquentQuery::beforeApplyArgs', [
//            $query, $rootValue, $args, $resolveInfo, $classname
//        ]);
//dump($result);die;
        $query = static::applyArgsToQuery($query, $args);

        return $query->get();
    }

    public static function handleEloquentModelCount($rootValue, $args, ResolveInfo $resolveInfo, $classname)
    {
        $query = $classname::query();

        $query = static::applyArgsToQuery($query, $args);

        return $query->count();
    }

    public static function applyArgsToQuery($query, $args)
    {
        if (isset($args['selectOrderBy']))
        {
            $query = $query->orderBy(...$args['selectOrderBy']);
        }

        if (isset($args['selectTake']))
        {
            $query = $query->take($args['selectTake']);
        }

        if (isset($args['selectWhere']))
        {
            $query = $query->where(...$args['selectWhere']);
        }

        if (isset($args['selectWhereIn']))
        {
            $column = array_shift($args['selectWhereIn']);

            $query = $query->whereIn($column, $args['selectWhereIn']);
        }

        if (isset($args['selectWith']))
        {
            $query = $query->with($args['selectWith']);
        }

        if (isset($args['selectScopes']))
        {
            foreach ($args['selectScopes'] as $scope)
            {
                $scopeFn = array_shift($scope);

                $query = $query->$scopeFn(...$scope);
            }
        }

        return $query;
    }

}
