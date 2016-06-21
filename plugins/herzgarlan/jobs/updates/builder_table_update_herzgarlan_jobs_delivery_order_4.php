<?php namespace HerzGarlan\Jobs\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateHerzgarlanJobsDeliveryOrder4 extends Migration
{
    public function up()
    {
        Schema::table('herzgarlan_jobs_delivery_order', function($table)
        {
            $table->text('delivery_status')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('herzgarlan_jobs_delivery_order', function($table)
        {
            $table->dropColumn('delivery_status');
        });
    }
}
