<?php

namespace App\Http\Controllers;

use App\Models\cargo;
use App\Http\Requests\StorecargoRequest;
use App\Http\Requests\UpdatecargoRequest;

class CargoController extends Controller
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
     * @param  \App\Http\Requests\StorecargoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorecargoRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\cargo  $cargo
     * @return \Illuminate\Http\Response
     */
    public function show(cargo $cargo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\cargo  $cargo
     * @return \Illuminate\Http\Response
     */
    public function edit(cargo $cargo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatecargoRequest  $request
     * @param  \App\Models\cargo  $cargo
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatecargoRequest $request, cargo $cargo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cargo  $cargo
     * @return \Illuminate\Http\Response
     */
    public function destroy(cargo $cargo)
    {
        //
    }
}
