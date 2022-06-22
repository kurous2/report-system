<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Laporan;
use App\Helpers\ResponseFormatter;

class VoteController extends Controller
{
    public function upVote(Request $request, $id)
    {        
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
            // Tambah kolom vote
            $laporan->update([
                'vote' => $laporan->vote + 1,
            ]);

            // Simpan dalam pivot table
            $laporan = Laporan::find($id);
            $laporan->voters()->attach(Auth::id(), ['is_up_vote' => true]);

            return ResponseFormatter::success(
                [
                    'laporan' => $laporan
                ],
                'Vote Up Success'
            );
                    
        } catch (\Exception $e) {
            return ResponseFormatter::error(
                [
                    'error' => $e,
                ],
                'Vote Up failed!'
            );
        }
    }

    public function downVote(Request $request, $id)
    {        
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
            // Tambah kolom vote
            $laporan->update([
                'vote' => $laporan->vote - 1,
            ]);

            // Simpan dalam pivot table
            $laporan = Laporan::find($id);
            $laporan->voters()->attach(Auth::id(), ['is_up_vote' => false]);

            return ResponseFormatter::success(
                [
                    'laporan' => $laporan
                ],
                'Vote Down Success'
            );
                    
        } catch (\Exception $e) {
            return ResponseFormatter::error(
                [
                    'error' => $e,
                ],
                'Vote Down failed!'
            );
        }
    }
}
