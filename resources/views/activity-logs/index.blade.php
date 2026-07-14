@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Log Aktivitas</h6>
                </div>
                <div class="card-body px-0 pt-3 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table table-bordered mb-0 table-mobile-cards">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Waktu</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">User</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Aksi</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Data</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Perubahan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $log)
                                    <tr>
                                        <td data-label="Waktu"><p class="mb-0 text-sm">{{ $log->created_at->format('d-m-Y H:i') }}</p></td>
                                        <td data-label="User"><p class="mb-0 text-sm">{{ optional($log->user)->name ?? 'System' }}</p></td>
                                        <td data-label="Aksi">
                                            <span class="badge badge-sm {{ $log->action == 'created' ? 'bg-gradient-success' : ($log->action == 'updated' ? 'bg-gradient-warning' : 'bg-gradient-danger') }}">{{ $log->action }}</span>
                                        </td>
                                        <td data-label="Data"><p class="mb-0 text-sm">{{ class_basename($log->model_type) }} #{{ $log->model_id }}</p></td>
                                        <td data-label="Perubahan">
                                            <p class="mb-0 text-xs">{{ Str::limit(json_encode($log->changes), 150) }}</p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer pagination float-end">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
