<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class UpdateSentineloneFields extends Migration
{
    private $tableName = 'sentinelone';

    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            // Add new columns
            $table->integer('agent_install_time')->nullable();
            $table->string('agent_operational_state')->nullable();
            $table->string('remote_profiler')->nullable();
            $table->string('network_monitoring')->nullable();
            $table->string('network_extension')->nullable();
            $table->string('content_filter')->nullable();
            $table->string('network_quarantine')->nullable();
            $table->string('compatible_os')->nullable();
            $table->string('connected')->nullable();
            $table->string('site_key')->nullable();

            // Add index for new timestamp column
            $table->index('agent_install_time', 'idx_sentinelone_agent_install_time');
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn([
                'agent_install_time',
                'agent_operational_state',
                'remote_profiler',
                'network_monitoring',
                'network_extension',
                'content_filter',
                'network_quarantine',
                'compatible_os',
                'connected',
                'site_key'
            ]);
            
            // Drop new index
            $table->dropIndex('idx_sentinelone_agent_install_time');
        });
    }
} 