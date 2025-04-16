<?php

namespace Tests\Feature;

use App\Models\JobLog;
use App\Services\JobService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Ramsey\Uuid\Uuid;
use Str;
use Tests\TestCase;

class JobServiceTest extends TestCase
{
    use RefreshDatabase;

    protected JobService $jobService;

    #php artisan test --filter=JobServiceTest
    protected function setUp(): void
    {
        parent::setUp();
        $this->jobService = new JobService();
    }

    public function test_mark_as_processing_updates_status_and_start_time()
    {
        $uuid = Uuid::uuid4();

        JobLog::create(['uuid' => $uuid, 'status' => 'pending']);

        $this->jobService->markAsProcessing($uuid);

        $log = JobLog::where('uuid', $uuid)->first();

        $this->assertEquals('processing', $log->status);
        $this->assertNotNull($log->started_at);
    }

    public function test_mark_as_success_updates_status_and_finish_time()
    {
        $uuid = Uuid::uuid4();

        JobLog::create(['uuid' => $uuid, 'status' => 'processing']);

        $this->jobService->markAsSuccess($uuid);

        $log = JobLog::where('uuid', $uuid)->first();

        $this->assertEquals('success', $log->status);
        $this->assertNotNull($log->finished_at);
    }

    public function test_mark_as_failed_updates_status_error_and_finish_time()
    {
        $uuid = Uuid::uuid4();
        $error = 'Erro qualquer';

        JobLog::create(['uuid' => $uuid, 'status' => 'processing']);

        $this->jobService->markAsFailed($uuid, $error);

        $log = JobLog::where('uuid', $uuid)->first();

        $this->assertEquals('failed', $log->status);
        $this->assertEquals($error, $log->error);
        $this->assertNotNull($log->finished_at);
    }


}
