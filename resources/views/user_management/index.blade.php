@extends('layouts.master')

@section('content')
<div>
  <div class="row">
      <div class="col-12">
          <div class="card mb-4 mx-4">
              <div class="card-header pb-0">
                  <div class="d-flex flex-row justify-content-between">
                      <div>
                          <h5 class="mb-0">Data Pengguna</h5>
                      </div>
                      <a href="#" class="btn bg-gradient-warning btn-sm mb-0" type="button">+&nbsp; Tambah User</a>
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
                            @foreach ($users as $nomor=> $user )
                            <tr>
                                <td class="ps-4">
                                    <p class="text-xs font-weight-bold mb-0">{{ $nomor+1 }}</p>
                                </td>
                                
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $user->name }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $user->email }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ Str::title($user->modelHasRole->role->name); }}</p>
                                </td>
                                <td class="text-center">
                                    <a href="#" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Edit user">
                                        <i class="fas fa-user-edit text-secondary"></i>
                                    </a>

                                    @if(auth()->user()->can('user-management-delete') && auth()->user()->id != $user->id)
                                    <a href="#" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Hapus user">
                                        <i class="cursor-pointer fas fa-trash text-secondary"></i>
                                    </a>
                                    @endif  
                                </td>
                            </tr>  
                            @endforeach
                              
                              
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>
@endsection