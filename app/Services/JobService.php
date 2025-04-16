<?php

namespace App\Services;

use App\Models\JobLog;
use Illuminate\Support\Facades\Log;

class JobService
{
    public function update(string $uuid, array $data): void
    {
        $log = JobLog::where('uuid', $uuid)->first();

        if ($log) {
            $log->update($data);
        }
    }

    public function markAsProcessing(string $uuid): void
    {
        $this->update($uuid, [
            'status' => 'processing',
            'started_at' => now(),
        ]);
    }

    public function markAsSuccess(string $uuid): void
    {
        $this->update($uuid, [
            'status' => 'success',
            'finished_at' => now(),
        ]);
    }

    public function markAsFailed(string $uuid, string $error): void
    {
        $this->update($uuid, [
            'status' => 'failed',
            'error' => $error,
            'finished_at' => now(),
        ]);
    }


    public function jobSearchStatus(string $uuid)
    {
        try {
            $log = JobLog::select('uuid', 'status', 'started_at', 'finished_at', 'created_at', 'updated_at')
                ->where('uuid', $uuid)
                ->first();
            if ($log) {
                return response()->json($log);
            } else {
                # Gosto de devolver [] em caso de resultado vazio, para padronizar sempre o retorno e tratamento
                return response()->json([], 204);
            }
        } catch (\Exception $e) {
            Log::error("Erro ao tratar requisicao do job: {$uuid}");
            return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
}
