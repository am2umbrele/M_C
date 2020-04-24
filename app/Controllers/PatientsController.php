<?php

namespace App\Controllers;

use App\Config\Request;
use App\Models\Patient;

class PatientsController extends BaseController
{
    public function index()
    {
        $allPatients = Patient::all();

        echo json_encode($allPatients);
    }

    /**
     * @param $id
     */
    public function get($id)
    {
        $patient = Patient::getWhere(['id' => $id]);

        echo json_encode($patient);
    }

    public function create()
    {
        $request = Request::all();

        $patient = (new Patient())
            ->setName($request->name);

        $patient->doSave($patient);
    }

    /**
     * @param $id
     */
    public function update($id)
    {
        $request = Request::all();

        $patient = Patient::findOneBy(['id' => $id]);
        $patient->setName($request->name)
            ->doSave($patient);
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $patient = Patient::findOneBy(['id' => $id]);
        $patient->delete();
    }
}