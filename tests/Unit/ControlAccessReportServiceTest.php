<?php

namespace Tests\Unit;

use App\Services\ControlAccessReportService;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ControlAccessReportServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);

        DB::purge('sqlite');
        DB::reconnect('sqlite');

        Schema::create('personals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo');
            $table->string('rut')->nullable();
            $table->string('nombre')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('control_access_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('fecha');
            $table->string('personal_id');
            $table->string('nombre')->nullable();
            $table->string('departamento')->nullable();
            $table->timestamp('primera_entrada')->nullable();
            $table->timestamp('ultima_salida')->nullable();
        });

        Schema::create('attendances', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('personal_id');
            $table->timestamp('timestamp');
        });
    }

    public function test_build_for_range_marks_missing_attendance_and_excludes_deleted_personals(): void
    {
        $start = Carbon::create(2026, 1, 29, 0, 0, 0, 'America/Santiago');
        $end = (clone $start)->endOfDay();

        DB::table('personals')->insert([
            [
                'id' => 1,
                'codigo' => 'A1',
                'rut' => '11.111.111-1',
                'nombre' => 'Persona Uno',
                'deleted_at' => null,
            ],
            [
                'id' => 2,
                'codigo' => 'B2',
                'rut' => '22.222.222-2',
                'nombre' => 'Persona Dos',
                'deleted_at' => $start->copy()->addHours(10),
            ],
            [
                'id' => 3,
                'codigo' => 'C3',
                'rut' => '33.333.333-3',
                'nombre' => 'Persona Tres',
                'deleted_at' => $end->copy()->addHours(2),
            ],
        ]);

        DB::table('control_access_logs')->insert([
            [
                'fecha' => $start->copy()->addHours(8),
                'personal_id' => 'A1',
                'nombre' => 'Persona Uno',
                'departamento' => 'Packing',
                'primera_entrada' => $start->copy()->addHours(8),
                'ultima_salida' => $start->copy()->addHours(18),
            ],
            [
                'fecha' => $start->copy()->addHours(9),
                'personal_id' => 'B2',
                'nombre' => 'Persona Dos',
                'departamento' => 'Packing',
                'primera_entrada' => $start->copy()->addHours(9),
                'ultima_salida' => $start->copy()->addHours(17),
            ],
            [
                'fecha' => $start->copy()->addHours(7),
                'personal_id' => 'C3',
                'nombre' => 'Persona Tres',
                'departamento' => 'Packing',
                'primera_entrada' => $start->copy()->addHours(7),
                'ultima_salida' => $start->copy()->addHours(16),
            ],
        ]);

        DB::table('attendances')->insert([
            [
                'personal_id' => 1,
                'timestamp' => $start->copy()->addHours(9),
            ],
        ]);

        $service = app(ControlAccessReportService::class);
        $rows = $service->buildForRange($start, $end);

        $this->assertCount(2, $rows);

        $rowA1 = $rows->firstWhere('personal_id', 'A1');
        $rowC3 = $rows->firstWhere('personal_id', 'C3');

        $this->assertNotNull($rowA1);
        $this->assertNotNull($rowC3);
        $this->assertSame(0, (int) $rowA1->sin_asistencia);
        $this->assertSame(1, (int) $rowC3->sin_asistencia);
    }
}
