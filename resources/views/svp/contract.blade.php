@extends('layouts.master-dashboard')
@section('page-title', 'Detail Kontrak')
@section('monitoring-contract', 'active')
@section('address')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Detail Kontrak</li>
</ol>
@endsection
@push('styles')
<style>
    .dataTables_scroll {
        margin-bottom: 10px;
    }
</style>
@endpush
@section('dashboard')
<div>
    <div class="card">
        <div class="card-header card-forestgreen">
            <h6 class="card-title pt-1">Detail Kontrak</h6>
            <div class="card-tools">
                <button type="button" class="btn btn-tool btn-xs pr-0" data-card-widget="maximize"><i class="fas fa-expand fa-xs icon-border-default"></i>
                </button>
                <button type="button" class="btn btn-tool btn-xs" data-card-widget="collapse"><i class="fas fa-minus fa-xs icon-border-yellow"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form>
                <div class="form-group row">
                    <label for="number" class="col-sm-2 col-form-label">Nomor Kontrak</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="number" value="{{ $contracts->pivot->number }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="number" class="col-sm-2 col-form-label">Nama Pekerjaan</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="number" value="{{ $contract->name }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="prosentase" class="col-sm-2 col-form-label">Prosentase</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="prosentase" value="{{ $contracts->pivot->prosentase }}%" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nilai_kontrak" class="col-sm-2 col-form-label">Nilai Kontrak</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nilai_kontrak" value="@currency($contracts->pivot->nilai_kontrak)" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="director" class="col-sm-2 col-form-label">Direktur</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="director" value="{{ $contracts->pivot->director }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="phone" class="col-sm-2 col-form-label">Kontak</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="phone" value="{{ $contracts->pivot->phone }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="address" class="col-sm-2 col-form-label">Alamat</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="address" value="{{ $contracts->pivot->address }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="status" class="col-sm-2 col-form-label">Posisi</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="status" @if ($contracts->pivot->status_id == 1) value="VENDOR"
                        @elseif ($contracts->pivot->status_id == 2)value="BUYER"
                        @elseif ($contracts->pivot->status_id == 3)value="HUKUM"
                        @elseif ($contracts->pivot->status_id == 4)value="APPROVE HUKUM"
                        @elseif ($contracts->pivot->status_id == 5)value="ASSISTANT VICE PRESIDENT"
                        @elseif ($contracts->pivot->status_id == 6)value="VICE PRESIDENT"
                        @elseif ($contracts->pivot->status_id == 7)value="SENIOR VICE PRESIDENT"
                        @elseif ($contracts->pivot->status_id == 8)value="DIREKTUR KEUNGAN DAN UMUM"
                        @elseif ($contracts->pivot->status_id == 9)value="APPROVED"
                        @elseif ($contracts->pivot->status_id == 10)value="VENDOR SIGNATURE"
                        @elseif ($contracts->pivot->status_id == 11)value="FINAL" @endif
                        readonly>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header card-forestgreen">
            <h6 class="card-title pt-1">Dokumen Kontrak</h6>
            <div class="card-tools">
                <button type="button" class="btn btn-tool btn-xs pr-0" data-card-widget="maximize"><i class="fas fa-expand fa-xs icon-border-default"></i>
                </button>
                <button type="button" class="btn btn-tool btn-xs" data-card-widget="collapse"><i class="fas fa-minus fa-xs icon-border-yellow"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                <li class="nav-item" role="presentation" style="width: 50%;">
                    <a class="nav-link text-center active" id="draft-tab" data-toggle="tab" data-target="#draft" href="#draft" role="tab" aria-controls="draft" aria-selected="true">DRAFT KONTRAK</a>
                </li>
                <li class="nav-item" role="presentation" style="width: 50%;">
                    <a class="nav-link text-center" id="vendor-tab" data-toggle="tab" data-target="#vendor" href="#vendor" role="tab" aria-controls="vendor" aria-selected="false">FINAL KONTRAK</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane show active" id="draft" role="tabpanel" aria-labelledby="draft-tab">
                    <embed src="{{ asset($contracts->pivot->filename) }}.pdf" width="100%" height="600px" type="application/pdf">
                </div>
                <div class="tab-pane" id="vendor" role="tabpanel" aria-labelledby="vendor-tab">
                    <embed src="{{ asset('file_upload/'.$contracts->pivot->final_vendor) }}" width="100%" height="600px" type="application/pdf">
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header card-forestgreen">
            <h6 class="card-title pt-1">Log History</h6>
            <div class="card-tools">
                <button type="button" class="btn btn-tool btn-xs pr-0" data-card-widget="maximize"><i class="fas fa-expand fa-xs icon-border-default"></i>
                </button>
                <button type="button" class="btn btn-tool btn-xs" data-card-widget="collapse"><i class="fas fa-minus fa-xs icon-border-yellow"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="pekerjaanTable" class="table table-sm table-hovered table-bordered table-hover table-striped datatable2">
                    <thead>
                        <tr>
                            <th class="text-center pr-0" style="vertical-align: middle; width: 5%;">No.</th>
                            <th class="text-center pr-0" style="vertical-align: middle; width: 20%;">Nama</th>
                            <th class="text-center pr-0" style="vertical-align: middle; width: 20%;">Posisi
                            </th>
                            <th class="text-center pr-0" style="vertical-align: middle; width: 45%;">Deskripsi
                            </th>
                            <th class="text-center pr-0" style="vertical-align: middle; width: 10%;">Tanggal Dibuat
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($approvals as $approval)
                        <tr>
                            <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                            <td style="vertical-align: middle;">{{ $approval->name }}</td>
                            <td class="text-center" style="vertical-align: middle;">
                                @if ($approval->status == 1)
                                <span class="badge badge-success">VENDOR</span>
                                @elseif ($approval->status == 2)
                                <span class="badge badge-success">BUYER</span>
                                @elseif ($approval->status == 3)
                                <span class="badge badge-success">HUKUM</span>
                                @elseif ($approval->status == 4)
                                <span class="badge badge-success">APPROVE HUKUM</span>
                                @elseif ($approval->status == 5)
                                <span class="badge badge-success">ASSISTANT VICE PRESIDENT</span>
                                @elseif ($approval->status == 6)
                                <span class="badge badge-success">VICE PRESIDENT</span>
                                @elseif ($approval->status == 7)
                                <span class="badge badge-success">SENIOR VICE PRESIDENT</span>
                                @elseif ($approval->status == 8)
                                <span class="badge badge-success">DIREKTUR KEUANGAN DAN UMUM</span>
                                @endif
                            </td>
                            <td style="vertical-align: middle;">{{ $approval->description }}</td>
                            <td class="text-center" style="vertical-align: middle;">
                                {{ date('d/m/Y', strtotime($approval->created_at)) }}
                            </td>
                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script type="text/javascript">
    // DataTable
    $(function() {
        $('#pekerjaanTable .second-row th').each(function() {
            var title = $(this).text();
            $(this).html('<input type="text"  class="form-control" placeholder="" />');
        });
        $(document).ready(function() {
            $('.datatable2').DataTable({
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    ['10', '25', '50', '100', 'All']
                ],
                ordering: false,
                scrollY: '500px',
                scrollCollapse: true,
                pageLength: 100,
                initComplete: function() {
                    this.api().columns([0, 1, 2, 3, 4, 5]).every(function() {
                        var that = this;

                        $('input', this.header()).on('keyup change clear',
                            function() {
                                if (that.search() !== this.value) {
                                    that
                                        .search(this.value)
                                        .draw();
                                }
                            });
                    });
                },
            });
        });
    });
</script>
@endpush