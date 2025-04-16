<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadCsvRequest;
use App\Services\UploadCsvService;
use Illuminate\Http\Request;

class UploadController
{
    /**
     * Utiliza um request pesonalizado para validar a extensão do arquivo
     */
    public function uploadCSV(UploadCsvRequest $request, UploadCsvService $serviceUploadCsv)
    {
        try {
            $processCsv =  $serviceUploadCsv->processCsv($request->file('archive'));

            return response()->json(['message' =>   $processCsv], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
}
