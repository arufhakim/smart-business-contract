@extends('layouts.master-dashboard')
@section('page-title', 'Tambah Data Kontrak')
@section('active-contract', 'active')
@section('dashboard')
    <div>
        <div class="card">
            <div class="card-header card-forestgreen">
                <h6 class="card-title">Tambah Data Kontrak</h6>
                <!-- tool -->
                <div class="card-tools">
                    <button type="button" class="btn btn-tool btn-xs pr-0" data-card-widget="maximize"><i
                            class="fas fa-expand fa-xs icon-border-default"></i>
                    </button>
                    <button type="button" class="btn btn-tool btn-xs" data-card-widget="collapse"><i
                            class="fas fa-minus fa-xs icon-border-yellow"></i>
                    </button>
                </div>
                <!-- /tool -->
            </div>
            <div class="card-body">
                <form
                    action="{{ route('vendor.contract-update', ['contract' => $contract->pivot->contract_id, 'vendor' => $contract->pivot->vendor_id]) }}"
                    method="POST">
                    @csrf
                    @method('put')
                    <div class="form-group">
                        <label class="col-form-label col-form-label-xs" for="number">Nomor Kontrak<span
                                class="required">*</span></label>
                        <input type="text" class="form-control form-control-sm @error('number') is-invalid @enderror"
                            value="{{ $contract->pivot->number ?? old('number') }}" id="number" name="number">
                        @error('number')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="col-form-label col-form-label-xs" for="prosentase">Prosentase<span
                                class="required">*</span></label>
                        <input type="number" class="form-control form-control-sm @error('prosentase') is-invalid @enderror"
                            value="{{ $contract->pivot->prosentase ?? old('prosentase') }}" id="prosentase"
                            name="prosentase">
                        @error('prosentase')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="col-form-label col-form-label-xs" for="nilai_kontrak">Nilai Kontrak<span
                                class="required">*</span></label>
                        <input type="number"
                            class="form-control form-control-sm @error('nilai_kontrak') is-invalid @enderror"
                            value="{{ $contract->pivot->nilai_kontrak ?? old('nilai_kontrak') }}" id="nilai_kontrak"
                            name="nilai_kontrak">
                        @error('nilai_kontrak')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="col-form-label col-form-label-xs" for="director">Direktur<span
                                class="required">*</span></label>
                        <input type="text" class="form-control form-control-sm @error('director') is-invalid @enderror"
                            value="{{ $contract->pivot->director ?? old('director') }}" id="director" name="director">
                        @error('director')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="col-form-label col-form-label-xs" for="phone">Kontak<span
                                class="required">*</span></label>
                        <input type="text" class="form-control form-control-sm @error('phone') is-invalid @enderror"
                            value="{{ $contract->pivot->phone ?? old('phone') }}" id="phone" name="phone">
                        @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="col-form-label col-form-label-xs" for="address">Alamat<span
                                class="required">*</span></label>
                        <input type="text" class="form-control form-control-sm @error('address') is-invalid @enderror"
                            value="{{ $contract->pivot->address ?? old('address') }}" id="address" name="address">
                        @error('address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="row justify-content-end mr-0">
                        <button type="submit" class="btn btn-success btn-xs text-right" data-toggle="confirmation"
                            data-placement="left">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
