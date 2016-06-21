<?php namespace HerzGarlan\Config\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateHerzgarlanConfigRates5 extends Migration
{
    public function up()
    {
        Schema::table('herzgarlan_config_rates', function($table)
        {
            $table->decimal('value', 8, 2)->nullable(false)->unsigned(false)->default(0.00)->change();
        });
    }
    
    public function down()
    {
        Schema::table('herzgarlan_config_rates', function($table)
        {
            $table->integer('value')->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
}
