<?php

namespace App\Http\Controllers;

use App\Models\TratoContratistas;
use App\Http\Requests\StoreTratoContratistasRequest;
use App\Http\Requests\UpdateTratoContratistasRequest;
use App\Models\Personal;

class TratoContratistasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $personal = Personal::whereIn('codigo',['cont1','cont2'])->get();
        $trato=TratoContratistas::all();
        return view('admin.tratocontratistas.tratocontratista',compact('personal'));
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
     * @param  \App\Http\Requests\StoreTratoContratistasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTratoContratistasRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TratoContratistas  $tratoContratistas
     * @return \Illuminate\Http\Response
     */
    public function show(TratoContratistas $tratoContratistas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TratoContratistas  $tratoContratistas
     * @return \Illuminate\Http\Response
     */
    public function edit(TratoContratistas $tratoContratistas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTratoContratistasRequest  $request
     * @param  \App\Models\TratoContratistas  $tratoContratistas
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTratoContratistasRequest $request, TratoContratistas $tratoContratistas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TratoContratistas  $tratoContratistas
     * @return \Illuminate\Http\Response
     */
    public function destroy(TratoContratistas $tratoContratistas)
    {
        //
    }
}
