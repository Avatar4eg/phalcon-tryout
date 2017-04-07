<?php
namespace PhalconTryout\Models;

use Phalcon\Mvc\Model;

class RecordingProgramLinker extends Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=10, nullable=false)
     */
    public $recording_id;

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=10, nullable=false)
     */
    public $program_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema('phalcon-tryout');
        $this->belongsTo(
            'recording_id',
            Recordings::class,
            'id',
            ['alias' => 'recording']
        );
        $this->belongsTo(
            'program_id',
            Programs::class,
            'id',
            ['alias' => 'program']
        );
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'recording_program_linker';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return RecordingProgramLinker[]|RecordingProgramLinker
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return RecordingProgramLinker
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
