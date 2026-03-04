@if(auth()->check() && auth()->user()->perusahaan)
<div style="display:flex; align-items:center;">
    <a href="/" style="display:flex; align-items:center; gap:12px;">
        <img
            src="{{ auth()->user()->perusahaan->logo == null ? asset('storage/perusahaan/default_logo.png') : Storage::url(auth()->user()->perusahaan->logo) }}"
            alt="Logo"
            style="width:32px; height:32px; object-fit:contain;"
        >
        <span style="font-size:1.125rem; font-weight:700;">
            {{ auth()->user()->perusahaan->nama_perusahaan }}
        </span>
    </a>
</div>
@else
<div style="display:flex; align-items:center;">
    <span style="font-size:1.125rem; font-weight:700;">Attendance Sistem</span>
</div>
@endif