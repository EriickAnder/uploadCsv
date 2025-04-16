<?php

namespace App\Services;

use App\Helpers\upload;
use App\Jobs\ProcessCsvJob;
use App\Models\JobLog;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UploadCsvService
{
    public function processCsv($file)
    {
        try {

            # Realiza o upoad do arquivo
            $path = upload::uploadStorage($file, 'upload');

            # Chama o job e passo o caminho do arquivo, nesse caso no storage
            # Caso estivesse usando um serviço externo, bastava passar aqui
            $job = new ProcessCsvJob($path);
            # Starto para a fila de jobs
            dispatch($job);
            #Capturo o uuid gerado no job
            $uuid = $job->uuid;

            #CRia o job na tabela de log
            JobLog::create([
                'uuid' => $uuid,
                'status' => 'created',
                'file_path' => $path,
                'created_at' => now(),
            ]);

            return [
                'message' => 'Importação agendada com sucesso!',
                'file_path' => $path,
                'job_id' => $uuid,
            ];
        } catch (\Exception $e) {
            Log::error("Erro ao importar o arquivo: {$path}");
            return ['error' => 'Erro ao processar o arquivo: ' . $e->getMessage()];
        }
    }
}
