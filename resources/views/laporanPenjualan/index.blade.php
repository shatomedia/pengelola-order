@extends('layouts.master')

@section('content')
  <div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-3">Laporan Penjualan</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        No
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Nama
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Email
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        role
                                    </th>
                                    
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                              
                              <tr>
                                  <td class="ps-4">
                                      <p class="text-xs font-weight-bold mb-0">1</p>
                                  </td>
                                  
                                  <td class="text-center">
                                      <p class="text-xs font-weight-bold mb-0">name</p>
                                  </td>
                                  <td class="text-center">
                                      <p class="text-xs font-weight-bold mb-0">email</p>
                                  </td>
                                  <td class="text-center">
                                      <p class="text-xs font-weight-bold mb-0">user</p>
                                  </td>
                                  <td class="text-center">
                                    {{-- <button class="btn btn-link btn-tooltip text-dark text-sm mb-0 px-0 ms-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Tooltip on top"><i class="fas fa-file-pdf text-lg me-1" aria-hidden="true"></i> PDF</button> --}}
                                    <button class="btn btn-link btn-tooltip text-dark text-sm mb-0 px-0 ms-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Cetak" data-container="body" data-animation="true"><i class="fas fa-file-pdf text-lg me-1" aria-hidden="true"></i> PDF</button>
                                  </td>
                              </tr>  

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
@endsection