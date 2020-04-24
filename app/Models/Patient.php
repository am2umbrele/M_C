<?php

namespace App\Models;

class Patient extends Model
{
    protected static $tableName = 'patients';
    protected $primaryKey = 'id';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return Patient
     */
    public function setId($id): Patient
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return Patient
     */
    public function setName(?string $name): Patient
    {
        $this->name = $name;
        return $this;
    }
}