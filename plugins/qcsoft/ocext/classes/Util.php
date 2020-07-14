<?php namespace Qcsoft\Ocext\Classes;

use October\Rain\Database\Schema\Blueprint;

class Util
{
    public static function safedir($path)
    {
        $path = base_path($path);

        if (!is_dir($path))
        {
            mkdir($path);
        }

        return $path;
    }

    public static function tempTable($tableName, $columns)
    {
        if (\Schema::hasTable($tableName))
        {
            \DB::connection()->getPdo()->exec("truncate $tableName");

            return;
        }

        $columnDefs = [];

        foreach ($columns as $key => $value)
        {
            if ($key === 'id')
            {
                throw new \Exception('Column "id" can not have custom type, it is always "increments"');
            }

            if (is_numeric($key))
            {
                $columnName = $value;
                $columnTypeRaw = $value === 'id' ? 'increments' : 'string';
            }
            else
            {
                $columnName = $key;
                $columnTypeRaw = $value;
            }

            $notNull = ends_with($columnName, '!') ||
                ends_with($columnTypeRaw, '!') ||
                $columnName === 'id';

            $columnName = str_replace_last('!', '', $columnName);
            $columnType = str_replace_last('!', '', $columnTypeRaw);

            switch ($columnType)
            {
                case 'bool':
                    $columnType = 'boolean';
                    break;
                case 'int':
                    $columnType = 'integer';
                    break;
                case 'str':
                    $columnType = 'string';
                    break;
                default:
                    $columnType = $columnTypeRaw;
            }

            $columnDefs[$columnName] = [$columnType, $notNull];
        }

        \Schema::create($tableName, function (Blueprint $table) use ($columnDefs)
        {
            $table->engine = 'InnoDB';

            foreach ($columnDefs as $columnName => $columnDef)
            {
                list($method, $notNull) = $columnDef;

                if ($notNull)
                {
                    $table->$method($columnName);
                }
                else
                {
                    $table->$method($columnName)->nullable();
                }
            }
        });
    }

    public static function cachedWebFile($webpath, $cacheFilepath, $dontReturn = false, $binary = false)
    {
        $cacheFilepath = base_path($cacheFilepath);

        if (!file_exists($cacheFilepath))
        {
            $ch = curl_init($webpath);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            if ($binary)
            {
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            }

            $result = curl_exec($ch);

            curl_close($ch);

            file_put_contents($cacheFilepath, $result);

            \Log::info("cachedWebFile($webpath, $cacheFilepath, $dontReturn, $binary)");
        }
        else
        {
            if (!$dontReturn)
            {
                $result = file_get_contents($cacheFilepath);
            }
        }

        return $dontReturn ? null : $result;
    }

}
