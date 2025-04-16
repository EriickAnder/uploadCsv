<?php

namespace App\Helpers;

use Ramsey\Uuid\Uuid;

class upload
{
    /**
     * Faz o upload de um arquivo para o armazenamento especificado.
     *
     * @param \Illuminate\Http\UploadedFile $file Arquivo enviado para upload.
     * @param string $folder Pasta de destino onde o arquivo será armazenado.
     * @return string|\Illuminate\Http\JsonResponse Caminho do arquivo armazenado ou resposta JSON em caso de erro.
     * @throws \Exception Caso ocorra algum erro durante o upload.
     */
    public static function uploadStorage($file, $folder)
    {
        try {
            $fileName = Uuid::uuid4() . '.' . $file->getClientOriginalExtension();;
            $path = $file->storeAs($folder, $fileName);
            return $path;
        } catch (\Exception $e) {
            return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
}
