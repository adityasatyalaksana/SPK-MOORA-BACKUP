@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Matriks Keputusan (Penilaian)</h3>
        <button class="btn btn-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg me-1"></i> Tambah Penilaian
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap">
                    <thead class="bg-dark text-white text-center">
                        <tr>
                            <th class="px-3">Mountain and Trail</th>
                            <th>Bus Fleet and Route</th>
                            <th>Travel Estimate</th>
                            <th>Round Trip Price</th>
                            <th>Simaksi and Climbing Estimate</th>
                            <th>Difficulty Level</th>
                            @foreach($kriterias as $k)
                                <th class="bg-primary text-white">{{ $k->kode_kriteria }}</th>
                            @endforeach
                            <th class="bg-secondary text-white">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grouped = $penilaians->groupBy(fn($i) => $i->jalur_id . '-' . $i->biaya_id); @endphp
                        @foreach($grouped as $key => $items)
                        @php 
                            $first = $items->first(); 
                            $skorC4 = $items->where('kriteria_id', $kriterias->where('kode_kriteria', 'C4')->first()->id ?? 0)->first()->nilai ?? 0;
                            $modalId = "modalEdit" . $first->jalur_id . "_" . $first->biaya_id;
                        @endphp
                            
                            <tr>
                            {{-- Mountain and Trail --}}
                            <td class="ps-3">
                                <strong>{{ $first->jalur->gunung->nama_gunung ?? '-' }}</strong><br>
                                <small class="text-muted">{{ $first->jalur->nama_jalur ?? '-' }}</small>
                            </td>
                            
                            {{-- Bus Fleet and Route --}}
                            <td>
                                <strong>{{ $first->biaya->nama_armada ?? '-' }}</strong><br>
                                <small class="text-muted">
                                    {{ $first->biaya->start_terminal->nama_terminal ?? '-' }} &rarr; 
                                    {{ $first->biaya->end_terminal->nama_terminal ?? '-' }}
                                </small>
                            </td>

                            {{-- Travel Estimate --}}
                            <td class="text-center fw-bold">
                                {{ $first->biaya->estimasi_perjalanan ?? '-' }} Jam
                            </td>

                            {{-- Round Trip Price --}}
                            <td class="text-end fw-bold text-dark">
                                Rp {{ number_format($first->biaya->harga_pp ?? 0, 0, ',', '.') }}
                            </td>

                            {{-- Simaksi and Climbing Estimate --}}
                            <td class="text-center">
                                <span class="text-success fw-bold">Rp {{ number_format($first->jalur->biaya_simaksi ?? 0, 0, ',', '.') }}</span><br>
                                <small class="text-info">{{ $first->jalur->estimasi_jam ?? '-' }} Jam Mendaki</small>
                            </td>

                            {{-- Difficulty Level --}}
                            <td class="text-center">
                                @if($skorC4 == 5) 
                                    <span class="badge bg-info px-3 py-2">MUDAH</span>
                                @elseif($skorC4 == 3) 
                                    <span class="badge bg-success px-3 py-2">SEDANG</span>
                                @elseif($skorC4 == 1) 
                                    <span class="badge bg-danger px-3 py-2">SULIT</span>
                                @endif
                            </td>

                            {{-- Scores C1 - C6 --}}
                            @foreach($kriterias as $k)
                                <td class="text-center fw-bold text-primary">
                                    {{ $items->where('kriteria_id', $k->id)->first()->nilai ?? '-' }}
                                </td>
                            @endforeach

                            {{-- Aksi --}}
                            <td class="text-center pe-3">
                                <div class="btn-group shadow-sm">
                                    <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">Edit</button>
                                    <form action="{{ route('admin.penilaian.destroy', ['jalur' => $first->jalur_id, 'biaya' => $first->biaya_id]) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus baris ini?')">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- MODAL EDIT --}}
                        <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-warning">
                                        <h5 class="modal-title fw-bold">Edit Penilaian: {{ $first->jalur->gunung->nama_gunung ?? '' }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.penilaian.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="jalur_id" value="{{ $first->jalur_id }}">
                                        <input type="hidden" name="biaya_id" value="{{ $first->biaya_id }}">
                                        <div class="modal-body p-4">
                                            <div class="row g-3">
                                                @foreach($kriterias as $k)
                                                @php $nilaiLama = $items->where('kriteria_id', $k->id)->first()->nilai ?? ''; @endphp
                                                <div class="col-md-4">
                                                    <label class="small fw-bold">{{ $k->nama_kriteria }}</label>
                                                    <select name="nilai[{{ $k->id }}]" class="form-select" required>
                                                        @foreach($k->subKriterias as $sub)
                                                            <option value="{{ $sub->bobot }}" {{ $nilaiLama == $sub->bobot ? 'selected' : '' }}>
                                                                {{ $sub->nama_sub }} (Skor: {{ $sub->bobot }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-warning w-100 fw-bold">UPDATE DATA</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Input Penilaian Matriks</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.penilaian.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jalur Gunung</label>
                            <select name="jalur_id" class="form-select border-primary" required>
                                <option value="">-- Pilih --</option>
                                @foreach($jalurs as $j) 
                                    <option value="{{ $j->id }}">
                                        Gn. {{ $j->gunung->nama_gunung }} - {{ $j->nama_jalur }} ({{ $j->gunung->ketinggian }} Mdpl | {{ $j->nama_jalur == 'Apuy' ? 'Mudah' : ($j->nama_jalur == 'Cibodas' ? 'Sedang' : 'Sulit') }} | {{ $j->estimasi_jam }} Jam)
                                    </option> 
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Armada Bus</label>
                            <select name="biaya_id" class="form-select border-primary" required>
                                <option value="">-- Pilih --</option>
                                @foreach($biayas as $b) 
                                    <option value="{{ $b->id }}">
                                        {{ $b->nama_armada }} ({{ $b->start_terminal->short_name ?? 'Poris' }} ➔ {{ $b->end_terminal->nama_terminal ?? 'Gunung' }}) | {{ $b->estimasi_perjalanan }}J | Rp{{ number_format($b->harga_pp, 0, ',', '.') }}
                                    </option> 
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row g-3">
                        @foreach($kriterias as $k)
                        <div class="col-md-4">
                            <label class="small fw-bold">{{ $k->nama_kriteria }}</label>
                            <select name="nilai[{{ $k->id }}]" class="form-select" required>
                                <option value="">-- Pilih Skor --</option>
                                @foreach($k->subKriterias as $sub)
                                    <option value="{{ $sub->bobot }}">{{ $sub->nama_sub }} (Skor: {{ $sub->bobot }})</option>
                                @endforeach
                            </select>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">SIMPAN DATA</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection