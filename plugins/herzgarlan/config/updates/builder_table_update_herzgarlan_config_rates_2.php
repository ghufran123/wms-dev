<?php namespace HerzGarlan\Config\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateHerzgarlanConfigRates2 extends Migration
{
    public function up()
    {
        Schema::table('herzgarlan_config_rates', function($table)
        {
            $table->text('short_description')->nullable(false)->change();
            $table->string('operator', 255)->nullable(false)->change();
        });
    }
    
    public function down()
    {
        Schema::table('herzgarlan_config_rates', function($table)
        {
            $table->text('short_description')->nullable()->change();
            $table->string('operator', 255)->nullable()->change();
        });
    }
}
