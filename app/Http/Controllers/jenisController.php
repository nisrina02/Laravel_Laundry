<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\jenis;
use Validator;
use Auth;

class jenisController extends Controller
{
  public function store(Request $req){
    if(Auth::user()->level=="admin"){
      $validator=Validator::make($req->all(), [
        'jenis_cuci' => 'required',
        'harga' => 'required'
      ]);
      if($validator->fails()){
        return response()->json($validator->errors());
      }

      $insert=jenis::insert([
        'jenis_cuci' => $req->jenis_cuci,
        'harga' => $req->harga
      ]);

      $status="1";
      $messege="Berhasil menambahkan data jenis cuci";

      return response()->json(compact('status', 'messege'));

    } else {
      echo "Hanya admin yang bisa mengakses!";
    }
  }

  public function update($id, Request $req){
    if(Auth::user()->level=="admin"){
      $validator=Validator::make($req->all(), [
        'jenis_cuci' => 'required',
        'harga' => 'required'
      ]);

      if($validator->fails()){
        return response()->json($validator->errors());
      }

      $update=jenis::where('id', $id)->update([
        'jenis_cuci' => $req->jenis_cuci,
        'harga' => $req->harga
      ]);

      $status="1";
      $messege="Berhasil mengupdate data jenis cuci";

      return response()->json(compact('status', 'messege'));

    } else {
      echo "Hanya admin yang dapat mengakses!";
    }
  }

  public function destroy($id){
    if(Auth::user()->level=="admin"){
      $delete=jenis::where('id', $id)->delete();

      $status="1";
      $messege="Berhasil menghapus data jenis cuci";

      return response()->json(compact('status', 'messege'));
    } else {
      echo "Hanya admin yang dapat mengakses!";
    }
  }
}
