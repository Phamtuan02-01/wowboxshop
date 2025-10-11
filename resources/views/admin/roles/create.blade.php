@extends('admin.layouts.app')

@section('title', 'Tạo vai trò mới')

@section('content')
<div class="admin-content">
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="content-title">Tạo vai trò mới</h1>
            </div>
            <div>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Quay lại</a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.roles.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label required">Tên vai trò</label>
                    <input type="text" name="ten_vai_tro" class="form-control @error('ten_vai_tro') is-invalid @enderror" value="{{ old('ten_vai_tro') }}" required>
                    @error('ten_vai_tro')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button class="btn btn-primary"><i class="fas fa-save"></i> Lưu</button>
            </form>
        </div>
    </div>
</div>
@endsection
