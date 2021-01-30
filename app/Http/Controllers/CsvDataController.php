<?php

namespace App\Http\Controllers;

use App\Models\CsvData;
use Illuminate\Http\Request;

class CsvDataController extends Controller
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
    public function getInfo()
    {
        $last= CsvData::latest('id')->first();
        
        $result['lastID']=$last->id;
        $result['count']=CsvData::count();
        
        return response()->json(['success' => "Data returned successfully",
        'result' => $result]);

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response()->json(['success' => "Route test"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CsvData  $csvData
     * @return \Illuminate\Http\Response
     */
    public function show(CsvData $csvData)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CsvData  $csvData
     * @return \Illuminate\Http\Response
     */
    public function edit(CsvData $csvData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CsvData  $csvData
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CsvData $csvData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CsvData  $csvData
     * @return \Illuminate\Http\Response
     */
    public function destroy(CsvData $csvData)
    {
        //
    }
}
