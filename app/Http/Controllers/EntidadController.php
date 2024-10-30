<?php

namespace App\Http\Controllers;

use App\Models\Entidad;
use App\Http\Requests\StoreEntidadRequest;
use App\Http\Requests\UpdateEntidadRequest;

class EntidadController extends Controller
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
     * @param  \App\Http\Requests\StoreEntidadRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEntidadRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Entidad  $entidad
     * @return \Illuminate\Http\Response
     */
    public function show(Entidad $entidad)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Entidad  $entidad
     * @return \Illuminate\Http\Response
     */
    public function edit(Entidad $entidad)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEntidadRequest  $request
     * @param  \App\Models\Entidad  $entidad
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEntidadRequest $request, Entidad $entidad)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Entidad  $entidad
     * @return \Illuminate\Http\Response
     */
    public function destroy(Entidad $entidad)
    {
        //
    }
}
