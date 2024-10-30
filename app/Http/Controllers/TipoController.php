<?php

namespace App\Http\Controllers;

use App\Models\tipo;
use App\Http\Requests\StoretipoRequest;
use App\Http\Requests\UpdatetipoRequest;

class TipoController extends Controller
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
     * @param  \App\Http\Requests\StoretipoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoretipoRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\tipo  $tipo
     * @return \Illuminate\Http\Response
     */
    public function show(tipo $tipo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\tipo  $tipo
     * @return \Illuminate\Http\Response
     */
    public function edit(tipo $tipo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatetipoRequest  $request
     * @param  \App\Models\tipo  $tipo
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatetipoRequest $request, tipo $tipo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\tipo  $tipo
     * @return \Illuminate\Http\Response
     */
    public function destroy(tipo $tipo)
    {
        //
    }
}
