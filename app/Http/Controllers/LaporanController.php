<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Laporan;
use App\Helpers\ResponseFormatter;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $laporans = Laporan::all();

        return ResponseFormatter::success(
            [
                'categories' => $laporans,
            ],
            'Data Laporan berhasil diambil'
        );
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
        $request->validate([
            'unit' => ['required', 'string', 'max:64'],
            'uraian' => ['required', 'string', 'max:4000'],
            'solusi' => ['required', 'string', 'max:4000'],
            'gambar' => ['required', 'mimes:jpg,jpeg,png', 'max:1024'],
            'categories_id' => ['required', 'exists:App\Models\Category,id'],
        ]);

        // Simpan Gambar Kedalam Storage
        $originalName =  $request->gambar->getClientOriginalName();
        $fileName = pathinfo($originalName, PATHINFO_FILENAME) . '_' .time() . '.' . pathinfo($originalName, PATHINFO_EXTENSION);

        // Simpan File ke dalam storage
        $path = "private/images";        
        $filePath = Storage::putFileAs(
            $path,
            $request->file('gambar'), 
            $fileName
        );

        try {
            $laporan = Laporan::create([                
                'unit' => $request->unit,
                'uraian' => $request->uraian,
                'solusi' => $request->solusi,
                'gambar' => $fileName,
                'categories_id' => $request->categories_id,
                'users_id' => Auth::id()
            ]);

            return ResponseFormatter::success(
                [
                    'laporan' => $laporan
                ],
                'Laporan created successfully'
            );
                    
        } catch (\Exception $e) {
            return ResponseFormatter::error(
                [
                    'error' => $e,
                ],
                'Laporan creation failed!'
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getImage($file_name){
        // Direktori file
        $path = "private/documents/{$file_name}";


    }
}
