<?php

namespace App\Http\Controllers;

use App\Models\Locacion;
use App\Http\Requests\StoreLocacionRequest;
use App\Http\Requests\UpdateLocacionRequest;

class LocacionController extends Controller
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
     * @param  \App\Http\Requests\StoreLocacionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLocacionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Locacion  $locacion
     * @return \Illuminate\Http\Response
     */
    public function show(Locacion $locacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Locacion  $locacion
     * @return \Illuminate\Http\Response
     */
    public function edit(Locacion $locacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLocacionRequest  $request
     * @param  \App\Models\Locacion  $locacion
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLocacionRequest $request, Locacion $locacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Locacion  $locacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Locacion $locacion)
    {
        //
    }
}
