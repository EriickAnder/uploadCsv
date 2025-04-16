<?php

namespace App\Jobs;

use App\Models\JobLog;
use App\Models\User;
use App\Services\JobService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class ProcessCsvJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    # Caminho do arquivo que será utilizado no instanciamento do JOB
    protected $path;
    public string $uuid;
    public function __construct(string $path)
    {
        # Aqui eu crio um uuid para cada job, evitando devolver um id simples incremental
        $this->uuid = Uuid::uuid4();
        $this->path = $path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(JobService $logger)
    {
        $logger->markAsProcessing($this->uuid);
        try {
            DB::beginTransaction();

            # Busca o arquivo
            $handle = fopen(storage_path("app/{$this->path}"), 'r');

            # Tenta abrir o arquivo e em caso de erro regista em log
            if (!$handle) {
                ## Atualiza o log do job na tabela
                $logger->markAsFailed($this->uuid, "Erro ao abrir o arquivo: {$this->path}");
                Log::error("Erro ao abrir o arquivo: {$this->path}");
                return;
            }

            #Captura o nome das colunas
            $header = fgetcsv($handle);
            # Contador
            $imported = 0;
            $errors = [];

            # Intera o arquivo lnha por linha
            while (($row = fgetcsv($handle)) !== false) {
                $data = array_combine($header, $row);

                #Validador de acordo com o solicitado
                $validator = Validator::make($data, [
                    'name' => 'required|string|min:3',
                    'email' => 'required|email|unique:users,email',
                    'date_of_birth' => 'required|date_format:Y-m-d',
                ]);

                ## Deixei capturando as linhas que não conseguem ser validadas e salvo em log
                if ($validator->fails()) {
                    $errors[] = [
                        'row' => $row,
                        'errors' => $validator->errors()->all(),
                    ];
                    continue;
                }

                ## Se der tudo certo, persiste no banco
                User::create($data);
                $imported++;
            }
            fclose($handle);

            ## Atualiza o log do job na tabela
            Log::info("Importação finalizada: {$imported} registros inseridos.");

            # Salvo os erros da importação em log para uso posterior, caso necessário
            if ($errors) {
                Log::warning("Ocorreram erros durante a importação:", $errors);
            }
            DB::commit();

            ## Atualiza o log do job na tabela
            $logger->markAsSuccess($this->uuid);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Erro ao processar importação CSV: {$e->getMessage()}", [
                'path' => $this->path,
            ]);
            ## Atualiza o log do job na tabela
            $logger->markAsFailed($this->uuid, $e->getMessage());
            throw $e;
        }
    }
}
