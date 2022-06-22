<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Laporan;
use App\Models\User;
use App\Models\Category;
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
        // $laporans = Laporan::with('voters')->get();

        $laporans = Laporan::all();

        return ResponseFormatter::success(
            [
                'laporans' => $laporans,
            ],
            'Data Laporan berhasil diambil'
        );
    }

    public function getByUser()
    {
        // $laporans = Laporan::all();
        try {
            if (Auth::id()==1) {
                $laporan = Laporan::all();
            } else {
                $host = User::firstWhere('id', Auth::id());
                if ($host == null)
                    throw new ModelNotFoundException('Host with User ID ' . $this->id . ' Not Found', 0);

                $laporan = Laporan::where('users_id', $host->id)->get();
                // dd($laporan);
            }
         
            // return $laporan;
        
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'code' => 404,
                'message' => 'Not Found',
                'description' => $e->getMessage(),
            ]);
        }
        return ResponseFormatter::success(
            [
                'laporans' => $laporan,
            ],
            'Data Laporan berhasil diambil'
        );
    }

    public function getByCategory(string $request)
    {
        // $laporans = Laporan::all();
        // $request->validate([
        //     'nama' => ['string', 'max:64'],
        // ]);
        try {
                $category = Category::firstWhere('nama', $request);
                // dd($category->id);
                if ($category == null)
                    throw new ModelNotFoundException('Category ' . $request . ' Not Found', 0);

                $laporan = Laporan::where('categories_id', $category->id)->get();
            
            // return $laporan;
        
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'code' => 404,
                'message' => 'Not Found',
                'description' => $e->getMessage(),
            ]);
        }
        return ResponseFormatter::success(
            [
                'laporans' => $laporan,
            ],
            'Data Laporan berhasil diambil'
        );
    }

    public function getCategoryCount()
    {
        // $laporans = Laporan::all();
        // $request->validate([
        //     'nama' => ['string', 'max:64'],
        // ]);
        $graph = [];
        $categories = Category::all();
        foreach($categories as $arr ){
            $laporans = count(Laporan::all());
            $laporan = Laporan::where('categories_id',$arr->id)->get();
            $count = count($laporan);
            $presentase = ($count / $laporans) * 100;
            // $category = array
            array_push($graph, $arr->nama. " = ".$presentase."%");
            // $graph = array();
        }
        // dd($graph);
        // $category = 
        // try {
        //         $category = Category::firstWhere('nama', $request);
        //         // dd($category->id);
        //         if ($category == null)
        //             throw new ModelNotFoundException('Category ' . $request . ' Not Found', 0);

        //         $laporan = Laporan::where('categories_id', $category->id)->get();
            
        //     // return $laporan;
        
        // } catch (ModelNotFoundException $e) {
        //     return response()->json([
        //         'code' => 404,
        //         'message' => 'Not Found',
        //         'description' => $e->getMessage(),
        //     ]);
        // }
        return ResponseFormatter::success(
            [
                'laporans' => $graph,
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
        // Validasi Request
        $request->validate([
            'subjek' => ['required', 'string', 'max:64'],
            'unit' => ['required', 'string', 'max:64'],
            'uraian' => ['required', 'string', 'max:4000'],
            'solusi' => ['string', 'max:4000'],
            'gambar' => ['mimes:jpg,jpeg,png', 'max:1024'],
            'categories_id' => ['required', 'exists:App\Models\Category,id'],
        ]);       

        // Create Laporan
        try {
            $laporan = Laporan::create([     
                'subjek' => $request->subjek,     
                'unit' => $request->unit,
                'uraian' => $request->uraian,
                'status' => 'Active',
                'categories_id' => $request->categories_id,
                'users_id' => Auth::id()
            ]);

            // Jika ada file gambar
            if($request->gambar != null){
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

                // update ke database
                $laporan->gambar = $fileName;
                $laporan->save();
            }

            // jika ada solusi disimpan
            if($request->solusi != null){
                $laporan->solusi = $request->solusi;
                $laporan->save();
            }

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
        $laporan = Laporan::with(['category','user'])->find($id);

        if($laporan)
        {
            return ResponseFormatter::success(
                [
                    'laporan' => $laporan
                ],
                'Get laporan data success'
            );
        }else{
            return ResponseFormatter::error(
                null,
                'Laporan not found',
                404
            );
        }
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
    public function giveResponse(Request $request, $id)
    {
        // Validasi Request
        $request->validate([
            'tanggapan' => ['required', 'string', 'max:4000'],
        ]);
        
        // Cek apakah laporan ada
        $laporan = Laporan::find($id);

        if(!$laporan)
        {
            return ResponseFormatter::error(
                null,
                'Laporan not found',
                404
            );
        }        
        // Update Laporan
        try {
            $laporan->update([
                'tanggapan' => $request->tanggapan,                
            ]);

            $laporan = Laporan::find($id);

            return ResponseFormatter::success(
                [
                    'laporan' => $laporan
                ],
                'Laporan updated successfully'
            );
                    
        } catch (\Exception $e) {
            return ResponseFormatter::error(
                [
                    'error' => $e,
                ],
                'Update laporan failed!'
            );
        }
    }

    public function update(Request $request, $id)
    {
        // Validasi Request
        $request->validate([
            'subjek' => ['required', 'string', 'max:64'],
            'unit' => ['required', 'string', 'max:64'],
            'uraian' => ['required', 'string', 'max:4000'],
            'solusi' => ['string', 'max:4000'],
            'gambar' => ['mimes:jpg,jpeg,png', 'max:1024'],
            'categories_id' => ['required', 'exists:App\Models\Category,id'],
        ]);
        
        // Cek apakah laporan ada
        $laporan = Laporan::find($id);

        if(!$laporan)
        {
            return ResponseFormatter::error(
                null,
                'Laporan not found',
                404
            );
        }        

        if($request->gambar != null){
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

            // update ke database
            $laporan->gambar = $fileName;
            $laporan->save();
        }

        // jika ada solusi disimpan
        if($request->solusi != null){
            $laporan->solusi = $request->solusi;
            $laporan->save();
        }        

        // Update Laporan
        try {
            $laporan->update([
                'subjek' => $request->subjek,                
                'unit' => $request->unit,
                'uraian' => $request->uraian,                
                'categories_id' => $request->categories_id,
            ]);

            $laporan = Laporan::find($id);

            return ResponseFormatter::success(
                [
                    'laporan' => $laporan
                ],
                'Laporan updated successfully'
            );
                    
        } catch (\Exception $e) {
            return ResponseFormatter::error(
                [
                    'error' => $e,
                ],
                'Update laporan failed!'
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $laporan = Laporan::find($id);
        $laporan->delete();
        return ResponseFormatter::success(
            null,
            'Laporan deleted successfully'
        );
    }

    public function getImage($file_name){
        // Direktori file
        $path = "private/images/{$file_name}";

        // Kembalikan File
        if(Storage::exists($path)){
            return Storage::download($path);
        }else{
            return ResponseFormatter::error(
                null,
                'Image not found',
                404
            );
        }
    }
}
