<?php namespace Rainlab\Delivery\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateRainlabDeliveryRecords2 extends Migration
{
    public function up()
    {
        Schema::table('rainlab_delivery_records', function($table)
        {
            $table->string('tracking_no')->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('rainlab_delivery_records', function($table)
        {
            $table->integer('tracking_no')->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
}
