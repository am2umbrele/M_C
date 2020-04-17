<?php

namespace App\Models;

class PatientMetric extends Model
{
    protected static $tableName = 'patient_metrics';
    protected $primaryKey = 'id';
    protected $relationKey = 'patient_id';
    protected $autoGenerateValue = 'unique_id';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $patientId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $uniqueId;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return PatientMetric
     */
    public function setId($id): PatientMetric
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getPatientId(): int
    {
        return $this->patientId;
    }

    /**
     * @param int $patientId
     * @return PatientMetric
     */
    public function setPatientId(int $patientId): PatientMetric
    {
        $this->patientId = $patientId;
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
     * @return PatientMetric
     */
    public function setName(?string $name): PatientMetric
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUniqueId(): ?string
    {
        return $this->uniqueId;
    }

    /**
     * @param string|null $uniqueId
     * @return PatientMetric
     */
    public function setUniqueId(?string $uniqueId): PatientMetric
    {
        $this->uniqueId = $uniqueId;
        return $this;
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function findOneBy($id)
    {
        return Model::findOne(self::class, self::$tableName, $id);
    }

    /**
     * @param $params
     * @return array
     */
    public static function getWhere($params): array
    {
        return Model::where(self::$tableName, $params);
    }

    public function save()
    {
        Model::doSave(self::$tableName, $this);
    }

    public function delete()
    {
        Model::doDelete(self::$tableName, $this);
    }
}