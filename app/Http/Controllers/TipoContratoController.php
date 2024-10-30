<?php

namespace App\Http\Controllers;

use App\Models\TipoContrato;
use App\Http\Requests\StoreTipoContratoRequest;
use App\Http\Requests\UpdateTipoContratoRequest;

class TipoContratoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTipoContratoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTipoContratoRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TipoContrato  $tipoContrato
     * @return \Illuminate\Http\Response
     */
    public function show(TipoContrato $tipoContrato)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TipoContrato  $tipoContrato
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoContrato $tipoContrato)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTipoContratoRequest  $request
     * @param  \App\Models\TipoContrato  $tipoContrato
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTipoContratoRequest $request, TipoContrato $tipoContrato)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TipoContrato  $tipoContrato
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoContrato $tipoContrato)
    {
        //
    }
}
