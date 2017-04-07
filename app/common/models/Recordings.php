<?php
namespace PhalconTryout\Models;

use Phalcon\Mvc\Model;

class Recordings extends Model
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
     * @Column(type="string", nullable=true)
     */
    protected $time_start;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    protected $time_end;

    /**
     *
     * @var string
     * @Column(type="string", length=256, nullable=true)
     */
    protected $path;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Recordings
     */
    public function setId(int $id): Recordings
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimeStart(): string
    {
        return $this->time_start;
    }

    /**
     * @param string $time_start
     * @return Recordings
     */
    public function setTimeStart(string $time_start): Recordings
    {
        $this->time_start = $time_start;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimeEnd(): string
    {
        return $this->time_end;
    }

    /**
     * @param string $time_end
     * @return Recordings
     */
    public function setTimeEnd(string $time_end): Recordings
    {
        $this->time_end = $time_end;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return Recordings
     */
    public function setPath(string $path): Recordings
    {
        $this->path = $path;
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
            'recording_id',
            'program_id',
            Programs::class,
            'id',
            ['alias' => 'programs']
        );
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'recordings';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Recordings[]|Recordings
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Recordings
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * @param array|null $parameters
     * @return Model\ResultsetInterface
     */
    public function getPrograms($parameters = null)
    {
        return $this->getRelated('programs', $parameters);
    }
}
