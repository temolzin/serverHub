@extends('layouts/contentNavbarLayout')

@section('title', 'Owners')

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Owners</h5>
      <button class="btn btn-primary">
        <i class="icon-base bx bx-plus me-1"></i> Add Owner
      </button>
    </div>
    <div class="table-responsive text-nowrap">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Owner</th>
            <th>Email</th>
            <th>Phone</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          <tr>
            <td>1</td>
            <td>
              <div class="d-flex align-items-center">
                <div class="avatar avatar-sm me-3">
                  <span class="avatar-initial rounded-circle bg-label-primary">
                    J
                  </span>
                </div>
                <div>
                  <span class="fw-medium">Juan Pérez</span>
                </div>
              </div>
            </td>
            <td>juan.perez@email.com</td>
            <td>5512345678</td>
            <td class="text-end">
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="icon-base bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                  <a class="dropdown-item" href="#">
                    <i class="icon-base bx bx-edit-alt me-1"></i> Edit
                  </a>
                  <a class="dropdown-item text-danger" href="#">
                    <i class="icon-base bx bx-trash me-1"></i> Delete
                  </a>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <td>2</td>
            <td>
              <div class="d-flex align-items-center">
                <div class="avatar avatar-sm me-3">
                  <span class="avatar-initial rounded-circle bg-label-info">
                    A
                  </span>
                </div>
                <div>
                  <span class="fw-medium">Ana López</span>
                </div>
              </div>
            </td>
            <td>ana.lopez@email.com</td>
            <td>5587654321</td>
            <td class="text-end">
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="icon-base bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                  <a class="dropdown-item" href="#">
                    <i class="icon-base bx bx-edit-alt me-1"></i> Edit
                  </a>
                  <a class="dropdown-item text-danger" href="#">
                    <i class="icon-base bx bx-trash me-1"></i> Delete
                  </a>
                </div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
@endsection
