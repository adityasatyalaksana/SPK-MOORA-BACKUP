@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4" style="background: #f8f9fc;">
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="fw-bold text-dark">Proses Perhitungan MOORA</h4>
            <p class="text-muted small">Halaman kalkulasi matriks keputusan hingga hasil rekomendasi akhir berdasarkan kombinasi kriteria keuntungan (Benefit) dan kriteria biaya (Cost).</p>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-secondary text-white py-3">
            <h6 class="m-0 font-weight-bold"><i class="bi bi-table me-2"></i>1. Matriks Keputusan (Nilai Awal)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle mb-0">
                    <thead>
                        <tr class="text-center bg-light text-dark">
                            <th class="py-2" style="width: 20%;">Nama Gunung</th>
                            <th class="py-2" style="width: 20%;">Jalur Pendakian</th>
                            @foreach($kriterias as $k) 
                                <th class="py-2">{{ $k->nama_kriteria }} ({{ ucfirst($k->tipe) }})</th> 
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($matriks as $keyAlternatif => $kcriteriaNilai)
                        @php
                            // Memecah "Nama Gunung (Nama Jalur)" menjadi array
                            preg_match('/^(.*?)\s*\((.*?)\)$/', $keyAlternatif, $matches);
                            $namaGunung = $matches[1] ?? $keyAlternatif;
                            $namaJalur  = $matches[2] ?? '-';
                        @endphp
                        <tr class="text-center">
                            <td class="fw-bold py-2 text-dark" style="background: #fdfdfd;">{{ $namaGunung }}</td>
                            <td class="text-muted py-2">{{ $namaJalur }}</td>
                            @foreach($kcriteriaNilai as $nilai) 
                                <td class="py-2">{{ $nilai }}</td> 
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-info text-white py-3">
            <h6 class="m-0 font-weight-bold"><i class="bi bi-layers me-2"></i>2. Matriks Ternormalisasi Terbobot</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle mb-0">
                    <thead>
                        <tr class="text-center bg-light text-dark">
                            <th class="py-2" style="width: 20%;">Nama Gunung</th>
                            <th class="py-2" style="width: 20%;">Jalur Pendakian</th>
                            @foreach($kriterias as $k) 
                                <th class="py-2">{{ $k->nama_kriteria }}</th> 
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($terbobot as $keyAlternatif => $kcriteriaNilai)
                        @php
                            preg_match('/^(.*?)\s*\((.*?)\)$/', $keyAlternatif, $matches);
                            $namaGunung = $matches[1] ?? $keyAlternatif;
                            $namaJalur  = $matches[2] ?? '-';
                        @endphp
                        <tr class="text-center">
                            <td class="fw-bold py-2 text-dark" style="background: #fdfdfd;">{{ $namaGunung }}</td>
                            <td class="text-muted py-2">{{ $namaJalur }}</td>
                            @foreach($kcriteriaNilai as $nilai) 
                                <td class="text-secondary py-2">{{ number_format($nilai, 4) }}</td> 
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white py-3">
            <h6 class="m-0 font-weight-bold"><i class="bi bi-trophy me-2"></i>3. Hasil Akhir & Perangkingan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover border align-middle mb-0">
                    <thead class="table-dark text-center">
                        <tr>
                            <th class="py-3" style="width: 10%;">Peringkat</th>
                            <th class="py-3" style="width: 25%;">Nama Gunung</th>
                            <th class="py-3" style="width: 25%;">Jalur Pendakian</th>
                            <th class="py-3" style="width: 15%;">Total Benefit ($\sum \text{Max}$)</th>
                            <th class="py-3" style="width: 15%;">Total Cost ($\sum \text{Min}$)</th>
                            <th class="py-3" style="width: 10%;">Skor Akhir ($Y_i$)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hasil as $index => $data)
                        @php
                            preg_match('/^(.*?)\s*\((.*?)\)$/', $data['jalur'], $matches);
                            $namaGunung = $matches[1] ?? $data['jalur'];
                            $namaJalur  = $matches[2] ?? '-';
                        @endphp
                        <tr class="text-center">
                            <td class="py-3">
                                @if($index == 0)
                                    <span class="badge bg-warning text-dark px-3 py-2 fw-bold shadow-sm">🏆 Peringkat 1</span>
                                @else
                                    <strong class="text-muted">{{ $index + 1 }}</strong>
                                @endif
                            </td>
                            <td class="text-center fw-bold py-3 text-dark">{{ $namaGunung }}</td>
                            <td class="text-center text-muted py-3">{{ $namaJalur }}</td>
                            
                            <td class="text-secondary py-3 fw-semibold">{{ number_format($data['max'], 4) }}</td>
                            
                            <td class="text-secondary py-3 fw-semibold">{{ number_format($data['min'], 4) }}</td>
                            
                            <td class="py-3">
                                <span class="fw-bold fs-5 {{ $data['skor'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($data['skor'], 4) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3 p-3 bg-light rounded border-start border-4 border-success">
                <p class="mb-0 small text-muted">
                    <i class="bi bi-info-circle me-1 text-success"></i> 
                    <strong>Catatan Akademis:</strong> Nilai Akhir Preferensi ($Y_i$) diperoleh dari hasil pengurangan kolom <strong>Total Benefit ($\sum \text{Max}$)</strong> dengan <strong>Total Cost ($\sum \text{Min}$)</strong>. Alternatif rute pendakian dengan skor tertinggi ditempatkan pada peringkat teratas sebagai rekomendasi keputusan yang paling optimal.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection