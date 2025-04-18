<?php

namespace Tests\Integration\Migrations;

use Grimzy\LaravelMysqlSpatial\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Builder;

class CreateTables extends Migration
{
    private Builder $schema;

    public function __construct()
    {
        $this->schema = app('db')->getSchemaBuilder();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!$this->schema->hasTable('geometry')) {
            $this->schema->create('geometry', function (Blueprint $table) {
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
                $table->increments('id');
                $table->geometry('geo')->default(null)->nullable();
                $table->point('location', srid: 0);  // required to be not null in order to add an index
                $table->lineString('line', srid: 0)->default(null)->nullable();
                $table->polygon('shape', srid: 0)->default(null)->nullable();
                $table->multiPoint('multi_locations', srid: 0)->default(null)->nullable();
                $table->multiLineString('multi_lines', srid: 0)->default(null)->nullable();
                $table->multiPolygon('multi_shapes', srid: 0)->default(null)->nullable();
                $table->geometryCollection('multi_geometries', srid: 0)->default(null)->nullable();
                $table->timestamps();
            });
        }

        if (!$this->schema->hasTable('no_spatial_fields')) {
            $this->schema->create('no_spatial_fields', function (Blueprint $table) {
                $table->increments('id');
                $table->geometry('geometry', srid: 3857)->default(null)->nullable();
            });
        }

        if (!$this->schema->hasTable('with_srid')) {
            $this->schema->create('with_srid', function (Blueprint $table) {
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
                $table->increments('id');
                $table->geometry('geo', srid: 3857)->default(null)->nullable();
                $table->point('location', 3857)->default(null)->nullable();
                $table->lineString('line', 3857)->default(null)->nullable();
                $table->polygon('shape', 3857)->default(null)->nullable();
                $table->multiPoint('multi_locations', 3857)->default(null)->nullable();
                $table->multiLineString('multi_lines', 3857)->default(null)->nullable();
                $table->multiPolygon('multi_shapes', 3857)->default(null)->nullable();
                $table->geometryCollection('multi_geometries', 3857)->default(null)->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema->dropIfExists('geometry');
        $this->schema->dropIfExists('no_spatial_fields');
        $this->schema->dropIfExists('with_srid');
    }
}
