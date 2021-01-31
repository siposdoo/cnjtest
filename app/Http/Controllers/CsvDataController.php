<?php

namespace App\Http\Controllers;

use App\Models\CsvData;
use Illuminate\Http\Request;
use DateTime;

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
        
        return response()->json(['success' => "Data returned successfully", 'result' => $result]);

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
          // put all data to array if > 0 
           if(count($data)>1){
                     
            // Write to the database 
            if(!empty($data)){

                array_shift($data); //remove first row with header colums
                $res=$this->loadDataToArr($data);
               
                // var_dump($res);
                
                $result['houses_sold']=$this->infoArrByKey($res,"houses_sold");
                $result['average_price']=$this->infoArrByKey($res,"average_price");

                $result['no_of_crimes_in_2011']=$this->infoArrByKeyAndYear($res,"no_of_crimes",2011);
                $result['avgByYearsInLondon']=$this->groupedArrByYearsArea($res,"E09000001","average_price");
                
                //if saveToMysql checked, split large insert query and store to DB
                if($request->saveToMysql){
                    foreach(array_chunk($res, 2000) as $key => $smallerArray) {
                        foreach ($smallerArray as $index => $value) {
                            $temp[$index] = $value;
                        }
                        CsvData::insert($temp);
                    }
                }
            }
        }
    }
    
    if( $validate == true )
    {
        return response()->json(['success' => "Data parsed successfully", 'result' => $result]);
    }
    else
    {
        return response()->json(['error' => 'Your .CSV headers are not correct. Must be: date,area,average_price,code,houses_sold,no_of_crimes,borough_flag']);
    }
    }
    
    function groupedArrByYearsArea( $arr, $code, $key){
        
        $sum = 0;
        $i = 0;
        $filtered=array();
        $years=array();
        
        foreach ($arr as $item) {
            
            if($item['code']==$code){
               array_push($filtered,$item);
               $years[DateTime::createFromFormat("Y-m-d", $item['date'])->format("Y")]=0; 
           
            }
        }
        
        foreach ($filtered as $item){
            $temp['date']=$item['date'];
            $temp[$key]=$item[$key];
            
            $years[DateTime::createFromFormat("Y-m-d", $item['date'])->format("Y")] += $item[$key];
        }
        
        ksort($years, 1);
        
        return $years;
    }
    
    function infoArrByKeyAndYear( $arr,$key,$year )
    {
        $sum = 0;
        $i = 0;
    
        foreach ($arr as $item) {
            if(DateTime::createFromFormat("Y-m-d", $item['date'])->format("Y") == $year){
                $sum += $item[$key];
                $i++;
            }
        }
        
         $res['items']=$i;
         $res['sum']=$sum;
         $res['key']=$key;
         $res['year']=$year;
         $res['avr']=round($sum/$i,2);
         
         return $res;
    
    }

    function infoArrByKey( $arr,$key )
    {
        $sum = 0;
        $i = 0;
   
        foreach ($arr as $item) {
            $sum += $item[$key];
            $i++;
        }
    
        $res['items']=$i;
        $res['sum']=$sum;
        $res['key']=$key;
        $res['avr']=round($sum/$i,2);
     
        return $res;
    }
 
    // load CSV data to array
    public function loadDataToArr($data)
    {
        
        foreach ($data as $key => $val) {
            
            $array[] = [
             'date' => $val[0],
             'area' => $val[1],
             'average_price' => ($val[2]?$val[2]:0),
             'code' => $val[3],
             'houses_sold' => ($val[4]?$val[4]:0),
             'no_of_crimes' => ($val[5]?$val[5]:'0.00'),
             'borough_flag' =>  ($val[6]?$val[6]:0)];
            
            }
       
            return $array;
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
