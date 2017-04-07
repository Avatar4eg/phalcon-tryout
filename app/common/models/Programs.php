<?php
namespace PhalconTryout\Models;

use Phalcon\Mvc\Model;

class Programs extends Model
{
    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $id;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=true)
     */
    protected $title;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Programs
     */
    public function setId(int $id): Programs
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Programs
     */
    public function setTitle(string $title): Programs
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema('phalcon-tryout');
        $this->hasManyToMany(
            'id',
            RecordingProgramLinker::class,
            'program_id',
            'recording_id',
            Recordings::class,
            'id',
            ['alias' => 'recordings']
        );
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'programs';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Programs[]|Programs
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Programs
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * @param array|null $parameters
     * @return Model\ResultsetInterface
     */
    public function getRecordings($parameters = null)
    {
        return $this->getRelated('recordings', $parameters);
    }
}
