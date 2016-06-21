<?php namespace HerzGarlan\Jobs\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateHerzgarlanJobsDeliveryOrder3 extends Migration
{
    public function up()
    {
        Schema::table('herzgarlan_jobs_delivery_order', function($table)
        {
            $table->string('tracking_no', 225)->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('herzgarlan_jobs_delivery_order', function($table)
        {
            $table->integer('tracking_no')->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
}
