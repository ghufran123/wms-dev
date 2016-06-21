<?php namespace Rainlab\Delivery\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateRainlabDeliveryRecords11 extends Migration
{
    public function up()
    {
        Schema::table('rainlab_delivery_records', function($table)
        {
            $table->string('tracking_no', 100)->change();
            $table->string('product', 100)->change();
            $table->string('customer', 100)->change();
            $table->string('from_postal', 100)->change();
            $table->string('destination_postal', 100)->change();
            $table->string('type_service', 100)->change();
        });
    }
    
    public function down()
    {
        Schema::table('rainlab_delivery_records', function($table)
        {
            $table->string('tracking_no', 255)->change();
            $table->string('product', 255)->change();
            $table->string('customer', 255)->change();
            $table->string('from_postal', 255)->change();
            $table->string('destination_postal', 255)->change();
            $table->string('type_service', 255)->change();
        });
    }
}
