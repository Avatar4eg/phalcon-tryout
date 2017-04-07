<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class RecordingsMigration_100
 */
class RecordingsMigration_100 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('recordings', [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 10,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'time_start',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'size' => 1,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'time_end',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'size' => 1,
                            'after' => 'time_start'
                        ]
                    ),
                    new Column(
                        'path',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 256,
                            'after' => 'time_end'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY'),
                    new Index('start_idx', ['time_start'], 'UNIQUE'),
                    new Index('end_idx', ['time_end'], 'UNIQUE')
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '2',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_unicode_ci'
                ],
            ]
        );
    }

    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {

    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {

    }

}
