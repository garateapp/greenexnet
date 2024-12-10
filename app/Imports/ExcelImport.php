<?php

namespace App\Imports;


use Maatwebsite\Excel\Concerns\ToArray;

class ExcelImport implements ToArray
{
    public function array(array $array)
    {
        // Leer celdas específicas (por coordenadas)
        $specificCells = [
            'A1' => $array[0][0] ?? null, // Celda A1 (Primera fila, primera columna)
            'B2' => $array[1][1] ?? null, // Celda B2 (Segunda fila, segunda columna)
            'C3' => $array[2][2] ?? null, // Celda C3 (Tercera fila, tercera columna)
        ];

        return $specificCells;
    }
}
