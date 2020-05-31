<?php return [
new class {
public function up()
{
    Schema::create('ydnnov_catalog_category', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id');
        $table->integer('parent_id')->nullable();
        $table->integer('nest_left')->nullable();
        $table->integer('nest_right')->nullable();
        $table->integer('nest_depth')->nullable();
        $table->string('path')->nullable();
        $table->string('h1_title')->nullable();
        $table->string('seo_title')->nullable();
        $table->string('seo_desc')->nullable();
        $table->string('name')->nullable();
        $table->text('description')->nullable();
    });
}

public function down()
{
    Schema::dropIfExists('ydnnov_catalog_category');
}
},
new class {
public function up()
{
    Schema::create('ydnnov_catalog_product', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id');
        $table->integer('main_category_id')->nullable();
        $table->string('path')->nullable();
        $table->string('h1_title')->nullable();
        $table->string('seo_title')->nullable();
        $table->string('seo_desc')->nullable();
        $table->string('name')->nullable();
        $table->text('description')->nullable();
    });
}

public function down()
{
    Schema::dropIfExists('ydnnov_catalog_product');
}
},
new class {
public function up()
{
    Schema::create('ydnnov_catalog_category_product', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id');
        $table->integer('category_id')->nullable();
        $table->integer('product_id')->nullable();
    });
}

public function down()
{
    Schema::dropIfExists('ydnnov_catalog_category_product');
}
},
new class {
public function up()
{
    Schema::create('ydnnov_catalog_bundle', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id');
        $table->string('name')->nullable();
        $table->text('description')->nullable();
        $table->integer('price_override')->nullable();
    });
}

public function down()
{
    Schema::dropIfExists('ydnnov_catalog_bundle');
}
},
new class {
public function up()
{
    Schema::create('ydnnov_catalog_bundle_product', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id');
        $table->integer('bundle_id')->nullable();
        $table->integer('product_id')->nullable();
    });
}

public function down()
{
    Schema::dropIfExists('ydnnov_catalog_bundle_product');
}
},
new class {
public function up()
{
    Schema::create('ydnnov_catalog_filter', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id');
        $table->string('type')->nullable();
        $table->string('name')->nullable();
        $table->text('description')->nullable();
    });
}

public function down()
{
    Schema::dropIfExists('ydnnov_catalog_filter');
}
},
new class {
public function up()
{
    Schema::create('ydnnov_catalog_filteroption', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id');
        $table->integer('filter_id')->nullable();
        $table->string('name')->nullable();
        $table->text('description')->nullable();
        $table->integer('sort_order')->nullable();
    });
}

public function down()
{
    Schema::dropIfExists('ydnnov_catalog_filteroption');
}
},
new class {
public function up()
{
    Schema::create('ydnnov_catalog_filteroption_product', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id');
        $table->integer('filteroption_id')->nullable();
        $table->integer('product_id')->nullable();
    });
}

public function down()
{
    Schema::dropIfExists('ydnnov_catalog_filteroption_product');
}
}];