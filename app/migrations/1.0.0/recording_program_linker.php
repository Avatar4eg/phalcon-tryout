<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class RecordingProgramLinkerMigration_100
 */
class RecordingProgramLinkerMigration_100 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('recording_program_linker', [
                'columns' => [
                    new Column(
                        'recording_id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 10,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'program_id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 10,
                            'after' => 'recording_id'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['recording_id', 'program_id'], 'PRIMARY'),
                    new Index('IDX_479158438CA9A845', ['recording_id'], null),
                    new Index('IDX_479158433EB8070A', ['program_id'], null)
                ],
                'references' => [
                    new Reference(
                        'FK_479158433EB8070A',
                        [
                            'referencedTable' => 'programs',
                            'columns' => ['program_id'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'RESTRICT',
                            'onDelete' => 'RESTRICT'
                        ]
                    ),
                    new Reference(
                        'FK_479158438CA9A845',
                        [
                            'referencedTable' => 'recordings',
                            'columns' => ['recording_id'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'RESTRICT',
                            'onDelete' => 'RESTRICT'
                        ]
                    )
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '',
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
