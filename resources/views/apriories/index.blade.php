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

                    <form action="{{ url('proses-apriori') }}" method="get">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date-input" class="form-control-label">Date</label>
                                    <select name="date" id="date-input" class="form-control select2" style="width: 100%" required>
                                        <option value="">Pilih Tahun</option>
                                        @foreach($years as $year)
                                            <option value="{{ $year }}" {{ request('date') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="supportInput" class="form-control-label">Min Support</label>
                                    <input class="form-control" name="min_support" type="number" id="supportInput" value="{{ request('min_support') ? request('min_support') : null }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="supportConfidence" class="form-control-label">Min Confidence</label>
                                    <input class="form-control" name="min_confidence" type="number" id="supportConfidence" value="{{ request('min_confidence') ? request('min_confidence') : null }}" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn bg-gradient-warning w-100 mt-3">Proses</button>
                    </form>

                    @if (!is_null($products) && $products->count() > 0)
                        <div class="card mb-4">
                            <div class="card-header pb-0">
                                <div class="card-body px-0 pt-0 pb-2">
                                    <div class="table-responsive p-0">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Produk</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Transaksi</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Hasil Support</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($satuSetItem as $satuItem)
                                                <tr>
                                                    <td class="align-middle">
                                                        <p class="text-xs font-weight-bold mb-0">{{ $satuItem['product_name'] }}</p>
                                                    </td>
                                                    <td class="align-middle">
                                                        <p class="text-xs font-weight-bold mb-0">{{ $satuItem['total_transaksi'] }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">{{ $satuItem['persentase'] }} %</p>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
