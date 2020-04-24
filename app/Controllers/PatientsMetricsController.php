<?php

namespace App\Controllers;

use App\Config\Request;
use App\Models\PatientMetric;

class PatientsMetricsController extends BaseController
{
    /**
     * @param $patientId
     */
    public function index($patientId)
    {
        $allPatientMetrics = PatientMetric::getWhere(['patient_id' => $patientId]);

        echo json_encode($allPatientMetrics);
    }

    /**
     * @param integer $patientId
     * @param string $uniqueId
     */
    public function get($patientId, $uniqueId)
    {
        $patientMetrics = PatientMetric::getWhere(['patient_id' => $patientId, 'unique_id' => $uniqueId]);

        echo json_encode($patientMetrics);
    }

    /**
     * @param $patientId
     */
    public function create($patientId)
    {
        $request = Request::all();

        $patientMetric = (new PatientMetric())
            ->setPatientId($patientId)
            ->setName($request->name);

        $patientMetric->doSave($patientMetric);
    }

    /**
     * @param $patientId
     * @param $uniqueId
     */
    public function update($patientId, $uniqueId)
    {
        $request = Request::all();

        $patientMetric = PatientMetric::findOneBy(['patient_id' => $patientId, 'unique_id' => $uniqueId]);
        $patientMetric->setPatientId($request->patientId ?? $patientId)
            ->setName($request->name)
            ->doSave($patientMetric);
    }

    /**
     * @param $patientId
     * @param $uniqueId
     */
    public function delete($patientId, $uniqueId)
    {
        $patientMetric = PatientMetric::findOneBy(['patient_id' => $patientId, 'unique_id' => $uniqueId]);
        $patientMetric->delete($patientMetric);
    }
}