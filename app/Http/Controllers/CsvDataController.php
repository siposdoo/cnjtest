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
        // Validate request 
      $request->validate(['csvfile' => 'required']);
      //get path of csvfile
      $path = $request->file('csvfile')->getRealPath();
      //map to array data from file
      $data = array_map('str_getcsv', file($path));
     
      $header = $data[0]; //Get data from header row 1
      $validate = $this->validateHeader($header); // Filter data of header row through our custom filter 
      $result=array();
      // If we have csv file with our header colums
      if( $validate == true )
      {

      }
      
      if( $validate == true )
      {
          return response()->json(['success' => "Data parsed successfully",
          'result' => $result]);
        }
        else
        {
            return response()->json(['error' => 'Your .CSV headers are not correct. Must be: date,area,average_price,code,houses_sold,no_of_crimes,borough_flag']);
        }
    }
    //Filter header row to check if contains our columns
    public function validateHeader($h)
    {
        $validate = false;

        if( $h[0] == 'date'
        && $h[1] == 'area'
        && $h[2] == 'average_price'
        && $h[3] == 'code'
        && $h[4] == 'houses_sold'
        && $h[5] == 'no_of_crimes'
        && $h[6] == 'borough_flag')
        {
            $validate = true;
        }
        return $validate;
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
