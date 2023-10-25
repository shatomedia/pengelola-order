@extends('layouts.master')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0">
        <h6>Data Penjualan</h6>
        <div class="d-flex mb-2 float-end">
          <a class="btn bg-gradient-warning " href="/order/create"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;Tambah</a>
        </div>
      </div>
      <div class="card-body pt-0">
        <div class="row">
          <div class="col-5">
            <form action="/template-penjualan" method="GET">
              @csrf
              <button type="submit" class="btn btn-outline-info mb-0">
                <i class="fas fa-download"></i>&nbsp;&nbsp;Unduh Template Excel
              </button>
          </form>
          </div>
          <div class="col-7">
            <form action="/order-import" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="input-group">
                <input name="template" type="file" required class="form-control" placeholder="Upload File" aria-label="Upload" aria-describedby="button-addon4">
                <button class="btn bg-gradient-info mb-0" type="submit">Upload</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="card-body px-0 pt-0 pb-2">
        <div class="table-responsive p-0">
          <table class="table align-items-center mb-0">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Pembeli</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Alamat</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">No HP</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Produk</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Order Via</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">TGL Order</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">TGL Kirim</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Title</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Background</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Request</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Keterangan</th>
                <th class="text-secondary opacity-7"></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($orders as $order)
              <tr>
                <td>
                  <span class="badge badge-sm bg-gradient-success">{{ $order->status }}</span>
                </td>
                <td>
                  <h6 class="mb-0 text-sm">{{ $order->nama_pembeli }}</h6>
                </td>
                <td class="align-middle text-center">
                  <p class="text-xs font-weight-bold mb-0">{{ $order->alamat }}</p>
                </td>
                
                <td class="align-middle text-center">
                  <span class="text-secondary text-xs font-weight-bold">{{ $order->no_hp }}</span>
                </td>
                <td class="align-middle text-center">
                  <p class="text-xs font-weight-bold mb-0">{{ optional($order->product)->nama }}</p>
                </td>
                <td>
                  <p class="text-xs font-weight-bold mb-0">{{ $order->order_via }}</p>
                </td>
                <td>
                  <p class="text-xs font-weight-bold mb-0">{{ $order->tgl_order }}</p>
                </td>
                <td>
                  <p class="text-xs font-weight-bold mb-0">{{ $order->tgl_kirim }}</p>
                </td>
                <td>
                  <p class="text-xs font-weight-bold mb-0">{{ $order->title }}</p>
                </td>
                <td>
                  <p class="text-xs font-weight-bold mb-0">{{ $order->background }}</p>
                </td>
                <td>
                  <p class="text-xs font-weight-bold mb-0">{{ $order->request }}</p>
                </td>
                <td>
                  <p class="text-xs font-weight-bold mb-0">{{ $order->keterangan }}</p>
                </td>
                <td>
                  <p class="text-xs font-weight-bold mb-0">-</p>
                </td>
                <td class="align-middle">
                  <a href="/order/{{ $order->id }}/edit" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
                    Edit
                  </a>
                </td>
              </tr>
                @endforeach
              
            </tbody>
          </table>
        </div>
        <div class="card-footer pagination float-end">
          {{ $orders->withQueryString()->links() }}
      </div>
      </div>
    </div>
  </div>
</div>
@endsection