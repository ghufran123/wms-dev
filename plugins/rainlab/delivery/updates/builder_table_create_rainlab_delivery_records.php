<?php namespace Rainlab\Delivery\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateRainlabDeliveryRecords extends Migration
{
    public function up()
    {
        Schema::create('rainlab_delivery_records', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('rainlab_delivery_records');
    }
}
