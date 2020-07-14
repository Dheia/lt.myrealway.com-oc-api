public function cart_4() 
{
    Schema::create('qcsoft_app_cart', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id')->unsigned();
        $table->string('session_key', 191)->nullable();
        $table->integer('customer_id')->nullable();
    });
}

public function cartitem_5() 
{
    Schema::create('qcsoft_app_cartitem', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id')->unsigned();
        $table->integer('cart_id')->nullable();
        $table->string('sellable_type', 191)->nullable();
        $table->integer('sellable_id')->nullable();
        $table->integer('quantity')->nullable();
    });
}

public function page_7() 
{
    Schema::create('qcsoft_app_page', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id')->unsigned();
        $table->string('owner_type', 191);
        $table->integer('owner_id')->nullable();
        $table->string('path', 191);
        $table->text('custom_h1_title')->nullable();
        $table->string('custom_seo_title', 191)->nullable();
        $table->text('seo_desc')->nullable();
    });
}

public function filter_14() 
{
    Schema::create('qcsoft_app_filter', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id')->unsigned();
        $table->string('name', 191)->nullable();
    });
}

public function filteroption_15() 
{
    Schema::create('qcsoft_app_filteroption', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id')->unsigned();
        $table->integer('filter_id');
        $table->string('name', 191);
        $table->integer('sort_order')->nullable();
    });
}

