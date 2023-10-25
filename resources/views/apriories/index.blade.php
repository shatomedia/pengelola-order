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
        
        @if (!is_null($products) && $products->count() > 0)
        <div class="card mb-4">
          <div class="card-header pb-0">    
          <div class="card-body px-0 pt-0 pb-2">
            <div class="table-responsive p-0">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Produk</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Hasil Support</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($products as $product)
                  <tr>
                    <td class="align-middle">
                      <p class="text-xs font-weight-bold mb-0">{{ $product->nama }}</p>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0">{{ $product->orders_count }}</p>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0">{{ $jumlahMinSupport[$product->id] }} %</p>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection