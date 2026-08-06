@extends('layouts.main')

@section('title', 'METAR TAF SPECI - Sentani')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Penerbangan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Metar & TAF</li>
@endsection

@section('page_title', 'Metar Taf Speci Sentani')

@section('page_description')
    Informasi METAR, TAF, dan SPECI Bandara Internasional Sentani (WAJJ).
@endsection

@section('content')
<script src="https://cdn.tailwindcss.com"></script>

<div class="font-sans text-slate-800 antialiased my-4">
    <div class="bg-gradient-to-r from-blue-800 to-indigo-900 text-white p-6 rounded-t-xl shadow-md flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-4">
            <div class="bg-white/10 p-3 rounded-lg border border-white/20">
                <i class="fa-solid fa-plane-departure text-3xl text-amber-400"></i>
            </div>
            <div>
                <h2 class="text-xl md:text-2xl font-black tracking-wide uppercase">Bandara Internasional Sentani</h2>
                <p class="text-xs md:text-sm text-blue-200">ICAO Code: <span class="font-bold text-white bg-blue-700/50 px-2 py-0.5 rounded">WAJJ</span> | IATA: DJJ</p>
            </div>
        </div>
        <div class="text-left md:text-right">
            <span class="inline-flex items-center gap-1.5 bg-emerald-500/20 text-emerald-300 text-xs px-3 py-1 rounded-full border border-emerald-500/30 font-medium">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Live Update BMKG
            </span>
        </div>
    </div>

    <div class="bg-white border border-slate-200 border-t-0 rounded-b-xl p-6 shadow-sm space-y-6">
        
        {{-- METAR --}}
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 shadow-sm transition hover:shadow-md">
            <div class="flex items-center gap-2 border-b border-slate-200 pb-3 mb-4">
                <span class="bg-blue-600 text-white font-black text-xs px-2.5 py-1 rounded shadow-sm">METAR</span>
                <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Meteorological Aerodrome Report</h4>
            </div>
            <div class="bg-slate-900 text-emerald-400 font-mono text-base md:text-lg p-4 rounded-lg border border-slate-950 shadow-inner select-all leading-relaxed break-words">
                {{ $metar ?? '-' }}
            </div>
            <p class="text-[11px] text-slate-500 mt-2 italic">Laporan kondisi cuaca terkini di bandara yang diperbarui secara berkala (tiap 30/60 menit).</p>
        </div>

        {{-- TAF --}}
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 shadow-sm transition hover:shadow-md">
            <div class="flex items-center gap-2 border-b border-slate-200 pb-3 mb-4">
                <span class="bg-amber-600 text-white font-black text-xs px-2.5 py-1 rounded shadow-sm">TAF</span>
                <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Terminal Aerodrome Forecast</h4>
            </div>
            <div class="bg-slate-900 text-amber-400 font-mono text-base md:text-lg p-4 rounded-lg border border-slate-950 shadow-inner select-all leading-relaxed break-words">
                {{ $taf ?? '-' }}
            </div>
            <p class="text-[11px] text-slate-500 mt-2 italic">Prakiraan cuaca kawasan bandar udara untuk periode 24–30 jam ke depan.</p>
        </div>

        {{-- SPECI --}}
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 shadow-sm transition hover:shadow-md">
            <div class="flex items-center gap-2 border-b border-slate-200 pb-3 mb-4">
                <span class="bg-red-600 text-white font-black text-xs px-2.5 py-1 rounded shadow-sm">SPECI</span>
                <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Aviation Special Weather Report</h4>
            </div>
            <div class="bg-slate-900 text-rose-400 font-mono text-base md:text-lg p-4 rounded-lg border border-slate-950 shadow-inner select-all leading-relaxed break-words">
                {{ $speci ?? '-' }}
            </div>
            <p class="text-[11px] text-slate-500 mt-2 italic">Laporan cuaca khusus saat terjadi perubahan cuaca signifikan secara mendadak.</p>
        </div>

    </div>
</div>
@endsection