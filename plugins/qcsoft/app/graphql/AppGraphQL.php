<?php namespace Qcsoft\App\GraphQL;

use Qcsoft\App\GraphQL\Type\QueryType;
use GraphQL\Type\Schema;
use GraphQL\GraphQL;
use GraphQL\Error\FormattedError;
use GraphQL\Error\DebugFlag;

class AppGraphQL
{
    public static function execute($query, $variables, $appContext)
    {
        $schema = new Schema([
            'query'      => new QueryType(),
            'typeLoader' => function ($name)
            {
                return Types::byTypeName($name, true);
            }
        ]);

        $result = GraphQL::executeQuery(
            $schema,
            $query,
            null,
            $appContext,
            (array)$variables
        );

        return $result;
    }

    public function handle()
    {
        // Disable default PHP error reporting - we have better one for debug mode (see below)
        ini_set('display_errors', 0);

        $debug = DebugFlag::NONE;
        if (!empty($_GET['debug']))
        {
            set_error_handler(function ($severity, $message, $file, $line) use (&$phpErrors)
            {
                \Log::info($message, $file, $line);

                throw new ErrorException($message, 0, $severity, $file, $line);
            });
            $debug = DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE;
        }

        try
        {
            $appContext = new AppContext();
            $appContext->rootUrl = 'http://178.19.16.34:10001/graphql';
            $appContext->request = $_REQUEST;

            // Parse incoming query and variables
            if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false)
            {
                $raw = file_get_contents('php://input') ?: '';
                $data = json_decode($raw, true) ?: [];
            }
            else
            {
                $data = $_REQUEST;
            }

            $data += ['query' => null, 'variables' => null];

            if (null === $data['query'])
            {
                $data['query'] = '{hello}';
            }

            // GraphQL schema to be passed to query executor:
            $schema = new Schema([
                'query'      => new QueryType(),
                'typeLoader' => function ($name)
                {
                    return Types::byTypeName($name, true);
                }
            ]);

            $result = GraphQL::executeQuery(
                $schema,
                $data['query'],
                null,
                $appContext,
                (array)$data['variables']
            );
            $output = $result->toArray($debug);
            $httpStatus = 200;
        } catch (\Exception $error)
        {
            $httpStatus = 500;
            $output['errors'] = [
                FormattedError::createFromException($error, $debug)
            ];
        }

        header('Content-Type: application/json', true, $httpStatus);

        echo json_encode($output);
    }
}
