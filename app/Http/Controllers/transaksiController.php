<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\transaksi;
use Validator;
use DB;
use Auth;

class transaksiController extends Controller
{
  public function get_transaksi(Request $r){
    if(Auth::user()->level=="petugas"){
      $transaksi = DB::table('transaksi')
      ->join('pelanggan', 'pelanggan.id', '=', 'transaksi.id_pelanggan')
      ->join('petugas', 'petugas.id', '=', 'transaksi.id_petugas')
      ->where('tgl_transaksi', '>=', $r->tgl_awal)
      ->where('tgl_transaksi', '<=', $r->tgl_akhir)
      ->select('transaksi.tgl_transaksi', 'pelanggan.nama_pelanggan', 'pelanggan.alamat', 'pelanggan.telp',
              'transaksi.tgl_selesai', 'transaksi.id')
      ->get();

      $hasil = array();

      foreach ($transaksi as $t){
        $grand = DB::table('detail')
        ->where('id_transaksi', '=', $t->id)
        ->groupBy('id_transaksi')
        ->select(DB::raw('sum(subtotal) as grandtotal'))
        ->first();

        $detail = DB::table('detail')
        ->join('jenis_cuci', 'jenis_cuci.id', '=', 'detail.id_jenis')
        ->where('id_transaksi', '=', $t->id)
        ->select('detail.*', 'jenis_cuci.*')
        ->get();

        $hasil2 = array();

        foreach ($detail as $d){
          $hasil2[] = array(
            'id transaksi' => $d->id_transaksi,
            'jenis cuci' => $d->jenis_cuci,
            'qty' => $d->qty,
            'harga' => $d->harga,
            'subtotal' => $d->subtotal
          );
        }

        $hasil[] = array(
          'tgl transaksi' => $t->tgl_transaksi,
          'nama pelanggan' => $t->nama_pelanggan,
          'alamat' => $t->alamat,
          'telp' => $t->telp,
          'tgl selesai' => $t->tgl_selesai,
          'total transaksi' => $grand->grandtotal,
          'detail transaksi' => $hasil2,
        );
      }

      return response()->json(compact('hasil'));

    } else {
      echo "Hanya petugas yang bisa mengakses!";
    }
  }

  public function store(Request $req){
    if(Auth::user()->level=="petugas"){
      $validator=Validator::make($req->all(), [
        'id_pelanggan' => 'required',
        'id_petugas' => 'required',
        'tgl_transaksi' => 'required',
        'tgl_selesai' => 'required'
      ]);
      if($validator->fails()){
        return response()->json($validator->errors());
      }

      $insert=transaksi::insert([
        'id_pelanggan' => $req->id_pelanggan,
        'id_petugas' => $req->id_petugas,
        'tgl_transaksi' => $req->tgl_transaksi,
        'tgl_selesai' => $req->tgl_selesai
      ]);

      $status="1";
      $messege="Berhasil menambahkan data transaksi";

      return response()->json(compact('status', 'messege'));

    } else {
      echo "Hanya petugas yang bisa mengakses!";
    }
  }

  public function update($id, Request $req){
    if(Auth::user()->level=="petugas"){
      $validator=Validator::make($req->all(), [
        'id_pelanggan' => 'required',
        'id_petugas' => 'required',
        'tgl_transaksi' => 'required',
        'tgl_selesai' => 'required'
      ]);

      if($validator->fails()){
        return response()->json($validator->errors());
      }

      $update=transaksi::where('id', $id)->update([
        'id_pelanggan' => $req->id_pelanggan,
        'id_petugas' => $req->id_petugas,
        'tgl_transaksi' => $req->tgl_transaksi,
        'tgl_selesai' => $req->tgl_selesai
      ]);

      $status="1";
      $messege="Berhasil mengupdate data transaksi";

      return response()->json(compact('status', 'messege'));

    } else {
      echo "Hanya petugas yang dapat mengakses!";
    }
  }

  public function destroy($id){
    if(Auth::user()->level=="petugas"){
      $delete=transaksi::where('id', $id)->delete();

      $status="1";
      $messege="Berhasil menghapus data transaksi";

      return response()->json(compact('status', 'messege'));
    } else {
      echo "hanya petugas yang dapat mengakses";
    }
  }
}
