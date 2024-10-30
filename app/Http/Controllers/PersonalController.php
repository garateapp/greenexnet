<?php

namespace App\Http\Controllers;

use App\Models\personal;
use App\Http\Requests\StorepersonalRequest;
use App\Http\Requests\UpdatepersonalRequest;

class PersonalController extends Controller
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
     * @param  \App\Http\Requests\StorepersonalRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorepersonalRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\personal  $personal
     * @return \Illuminate\Http\Response
     */
    public function show(personal $personal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\personal  $personal
     * @return \Illuminate\Http\Response
     */
    public function edit(personal $personal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatepersonalRequest  $request
     * @param  \App\Models\personal  $personal
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatepersonalRequest $request, personal $personal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\personal  $personal
     * @return \Illuminate\Http\Response
     */
    public function destroy(personal $personal)
    {
        //
    }
}
