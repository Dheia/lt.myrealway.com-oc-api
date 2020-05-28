<?php return function() {
\Schema::create('ydnnov_catalog_category', function($table)
{
    $table->engine = 'InnoDB';
    $table->increments('id');
    $table->integer('parent_id')->nullable();
    $table->integer('nest_left')->nullable();
    $table->integer('nest_right')->nullable();
    $table->integer('nest_depth')->nullable();
    $table->string('name')->nullable();
    $table->text('description')->nullable();
});

\Schema::create('ydnnov_catalog_product', function($table)
{
    $table->engine = 'InnoDB';
    $table->increments('id');
    $table->integer('main_category_id')->nullable();
    $table->string('name')->nullable();
    $table->text('description')->nullable();
});

\Schema::create('ydnnov_catalog_category_product', function($table)
{
    $table->engine = 'InnoDB';
    $table->increments('id');
    $table->integer('category_id')->nullable();
    $table->integer('product_id')->nullable();
});

\Schema::create('ydnnov_catalog_bundle', function($table)
{
    $table->engine = 'InnoDB';
    $table->increments('id');
    $table->string('name')->nullable();
    $table->text('description')->nullable();
    $table->integer('price_override')->nullable();
});

\Schema::create('ydnnov_catalog_bundle_product', function($table)
{
    $table->engine = 'InnoDB';
    $table->increments('id');
    $table->integer('bundle_id')->nullable();
    $table->integer('product_id')->nullable();
});

\Schema::create('ydnnov_catalog_filter', function($table)
{
    $table->engine = 'InnoDB';
    $table->increments('id');
    $table->string('type')->nullable();
    $table->string('name')->nullable();
    $table->text('description')->nullable();
});

\Schema::create('ydnnov_catalog_filteroption', function($table)
{
    $table->engine = 'InnoDB';
    $table->increments('id');
    $table->integer('filter_id')->nullable();
    $table->string('name')->nullable();
    $table->text('description')->nullable();
});

\Schema::create('ydnnov_catalog_filteroption_product', function($table)
{
    $table->engine = 'InnoDB';
    $table->increments('id');
    $table->integer('filteroption_id')->nullable();
    $table->integer('product_id')->nullable();
});

};