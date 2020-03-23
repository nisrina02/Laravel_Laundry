<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\detail;
use Validator;
use DB;
use App\jenis;
use Auth;

class detailController extends Controller
{
  public function store(Request $req){
    if(Auth::user()->level=="petugas"){
      $validator=Validator::make($req->all(), [
        'id_transaksi' => 'required',
        'id_jenis' => 'required',
        'qty' => 'required',
      ]);
      if($validator->fails()){
        return response()->json($validator->errors());
      }

      $harga = jenis::where('id', $req->id_jenis)->first();
      $subtotal = @$harga->harga * $req->qty;

      $insert=detail::insert([
        'id_transaksi' => $req->id_transaksi,
        'id_jenis' => $req->id_jenis,
        'qty' => $req->qty,
        'subtotal' => $subtotal
      ]);

      $status="1";
      $messege="Berhasil menambahkan data detail transaksi";

      return response()->json(compact('status', 'messege'));

    } else {
      echo "Hanya petugas yang bisa mengakses!";
    }
  }

  public function update($id, Request $req){
    if(Auth::user()->level=="petugas"){
      $validator=Validator::make($req->all(), [
        'id_transaksi' => 'required',
        'id_jenis' => 'required',
        'qty' => 'required',
      ]);

      if($validator->fails()){
        return response()->json($validator->errors());
      }

      $harga = jenis::where('id', $req->id_jenis)->first();
      $subtotal = @$harga->harga * $req->qty;

      $update=detail::where('id', $id)->update([
        'id_transaksi' => $req->id_transaksi,
        'id_jenis' => $req->id_jenis,
        'qty' => $req->qty,
        'subtotal' => $subtotal
      ]);

      $status="1";
      $messege="Berhasil mengupdate data detail transaksi";

      return response()->json(compact('status', 'messege'));

    } else {
      echo "Hanya petugas yang dapat mengakses!";
    }
  }

  public function destroy($id){
    if(Auth::user()->level=="petugas"){
      $delete=detail::where('id', $id)->delete();

      $status="1";
      $messege="Berhasil menghapus data detail transaksi";

      return response()->json(compact('status', 'messege'));
    } else {
      echo "Hanya petugas yang dapat mengakses!";
    }
  }
}
