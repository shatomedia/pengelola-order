@extends('layouts.master')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-body p-3">

        @if($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif

        <div class="row">
          <div class="col-md-6 mb-md-0 mb-4">
            <form action="{{ url('proses-apriori') }}" method="get">
              <div class="form-group">
                <label for="example-date-input" class="form-control-label">Date</label>
                <input class="form-control daterange" name="date" type="text" value="{{ request('date') ? request('date') : null }}" id="example-date-input" autocomplete="off">
                <button type="submit" class="btn bg-gradient-info mt-3">Search</button>
              </div>
            </form>
          </div>
          <div class="col-md-6">
            <form action="{{ url('proses-apriori') }}" method="get">
              <input type="hidden" name="date" value="{{ request('date') ? request('date') : null }}">
              <div class="form-group">
                <label for="supportInput" class="form-control-label">Min Support</label>
                <input class="form-control" name="min_support" type="number" id="supportInput" value="{{ request('min_support') ? request('min_support') : null }}" required>
                <label for="supportConfidence" class="form-control-label">Min Confidence</label>
                <input class="form-control" name="min_confidence" type="number" id="supportConfidence" value="{{ request('min_confidence') ? request('min_confidence') : null }}" required>
                <button type="submit" class="btn bg-gradient-warning mt-3">Proses</button>
              </div>
            </form>
          </div>
        </div>
        
        @if ($orders && $orders->total() > 0)
        <div class="card mb-4">
          <div class="card-header pb-0">
            
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
                      <p class="text-xs font-weight-bold mb-0">{{ Carbon\Carbon::parse($order->tgl_order)->isoFormat('DD MMM YYYY') }}</p>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0">{{ Carbon\Carbon::parse($order->tgl_kirim)->isoFormat('DD MMM YYYY') }}</p>
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
          @endif
        
      </div>
      
    </div>
  </div>
</div>
@endsection