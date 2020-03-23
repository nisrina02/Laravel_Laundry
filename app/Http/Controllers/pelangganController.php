<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\pelanggan;
use Validator;
use Auth;

class pelangganController extends Controller
{
    public function store(Request $req){
      if(Auth::user()->level=="admin"){
        $validator = Validator::make($req->all(), [
          'nama_pelanggan' => 'required',
          'telp' => 'required',
          'alamat' => 'required'
        ]);
        if($validator->fails()){
          return response()->json($validator->errors()->toJson(),400);
        }
        else {
          $insert=pelanggan::insert([
            'nama_pelanggan' => $req->nama_pelanggan,
            'telp' => $req->telp,
            'alamat' => $req->alamat
          ]);

          $status = "1";
          $messege = "Berhasil menambahkan data pelanggan";

          return response()->json(compact('status', 'messege'));

        }  
      } else {
        echo "Hanya admin yang bisa mengakses!";
      }
    }

    public function update($id, Request $req){
      if(Auth::user()->level=="admin"){
        $validator=Validator::make($req->all(), [
          'nama_pelanggan' => 'required',
          'telp' => 'required',
          'alamat' => 'required'
        ]);

        if($validator->fails()){
          return response()->json($validator->errors());
        }

        $ubah = pelanggan::where('id', $id)->update([
          'nama_pelanggan' => $req->nama_pelanggan,
          'telp' => $req->telp,
          'alamat' => $req->alamat
        ]);

        $status="1";
        $messege="Berhasil mengupdate data pelanggan";

        return response()->json(compact('status', 'messege'));

      } else {
        echo "Hanya admin yang bisa mengakses!";
      }
    }

    public function destroy($id){
      if(Auth::user()->level=="admin"){
        $hapus = pelanggan::where('id', $id)->delete();

        $status="1";
        $messege="Berhasil menghapus data pelanggan";

        return response()->json(compact('status', 'messege'));
      } else {
        echo "Hanya admin yang dapat mengakses!";
      }
    }
}
