<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Portfolio - {{ $mahasiswa->user->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', 'Helvetica', Arial, sans-serif;
            font-size: 15px;
            line-height: 1.55;
            color: #111;
            padding: 28px 36px 32px;
        }
        .page { width: 100%; }
        .header {
            display: table;
            width: 100%;
            table-layout: fixed;
            margin-bottom: 18px;
        }
        .header-left {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }
        .header-right {
            display: table-cell;
            width: 40%;
            vertical-align: top;
            text-align: right;
        }
        .name {
            font-size: 29px;
            font-weight: 800;
            letter-spacing: 0.5px;
        }
        .role {
            margin-top: 6px;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 1.1px;
        }
        .contacts {
            text-align: left;
            font-size: 14px;
            line-height: 1.6;
            /* Kontak di sisi kanan header, tapi teks rata kiri */
            display: inline-block;
            width: 100%;
        }
        .contact-item {
            margin-bottom: 4px;
        }
        .contact-label {
            font-weight: 600;
            margin-right: 6px;
        }
        .contact-value {
            font-weight: 400;
        }
        .divider {
            border-top: 1px solid #000;
            margin: 10px 0 26px;
        }
        .section {
            margin-bottom: 36px;
        }
        .section-title {
            font-size: 21px; /* +2pt for section labels */
            font-weight: 800;
            letter-spacing: 0.8px;
            margin-bottom: 12px;
        }
        .block {
            margin-bottom: 14px;
        }
        .block-title {
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 2px;
        }
        .meta {
            font-size: 14px;
            color: #333;
            margin-bottom: 6px;
        }
        .bullet {
            margin-left: 14px;
            font-size: 15px;
        }
        .grid-two {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px 28px;
        }
        .flex-two {
            display: table;
            width: 100%;
            table-layout: fixed;
            margin-bottom: 36px;
        }
        .side-section {
            margin-bottom: 36px;
        }
        .cert-col, .skill-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 14px;
        }
        .skill-col {
            padding-right: 0;
            padding-left: 14px;
        }
        .label {
            font-weight: 700;
            font-size: 15px;
        }
        .small {
            font-size: 14px;
            color: #333;
        }
        .inline-skill { font-weight: 700; }
        .nowrap { white-space: nowrap; }
    </style>
</head>
<body>
@php
    $portfolio = $portofolio;
    $experienceList = $portfolio?->experiences?->sortBy('urutan') ?? $mahasiswa->experiences ?? collect();
    $projectList = $portfolio?->projects?->sortBy('urutan') ?? $mahasiswa->projects ?? collect();
    $certificateList = $portfolio?->certificates?->sortBy('urutan') ?? $mahasiswa->certificates ?? collect();
    $skillList = $portfolio?->skills?->sortBy('urutan') ?? $mahasiswa->skills ?? collect();
    $roleTitle = $portfolio?->nama ?: ($portfolio?->bidang ? strtoupper($portfolio->bidang) : 'PORTFOLIO');
    $address = $mahasiswa->alamat ?? '';
    // language stored as text or json; normalize to collection
    $languageRaw = $portfolio?->language ?? null;
    if (is_string($languageRaw)) {
        $decoded = json_decode($languageRaw, true);
        $languageList = collect($decoded && is_array($decoded) ? $decoded : array_filter(array_map('trim', explode(',', $languageRaw))));
    } elseif (is_array($languageRaw)) {
        $languageList = collect($languageRaw);
    } else {
        $languageList = collect();
    }
@endphp

<div class="page">
    <header class="header">
        <div class="header-left">
            <div class="name">{{ $mahasiswa->user->name }}</div>
            <div class="role">{{ $roleTitle }}</div>
        </div>
        <div class="header-right">
            <div class="contacts">
                @if($mahasiswa->no_telp)
                    <div class="contact-item">
                        <span class="contact-label">Phone:</span>
                        <span class="contact-value">{{ $mahasiswa->no_telp }}</span>
                    </div>
                @endif
                @if($mahasiswa->user->email)
                    <div class="contact-item">
                        <span class="contact-label">Email:</span>
                        <span class="contact-value">{{ $mahasiswa->user->email }}</span>
                    </div>
                @endif
                @if($address)
                    <div class="contact-item">
                        <span class="contact-label">Address:</span>
                        <span class="contact-value">{{ $address }}</span>
                    </div>
                @endif
                @if($mahasiswa->github)
                    <div class="contact-item">
                        <span class="contact-label">Website:</span>
                        <span class="contact-value">{{ $mahasiswa->github }}</span>
                    </div>
                @endif
                @if($mahasiswa->linkedin)
                    <div class="contact-item">
                        <span class="contact-label">LinkedIn:</span>
                        <span class="contact-value">{{ $mahasiswa->linkedin }}</span>
                    </div>
                @endif
            </div>
        </div>
    </header>

    <div class="divider"></div>

    {{-- EXPERIENCE --}}
    @if($experienceList->count())
    <section class="section">
        <div class="section-title">EXPERIENCE</div>
        @foreach($experienceList as $exp)
            @php
                $start = $exp->tanggal_mulai ? \Carbon\Carbon::parse($exp->tanggal_mulai)->format('M Y') : null;
                $end = $exp->masih_berlangsung ? 'Present' : ($exp->tanggal_selesai ? \Carbon\Carbon::parse($exp->tanggal_selesai)->format('M Y') : null);
            @endphp
            <div class="block">
                <div class="block-title">{{ $exp->judul }}</div>
                <div class="meta">
                    {{ $exp->perusahaan ?? '' }}
                    @if($start || $end)
                        · {{ trim($start.' - '.$end, ' -') }}
                    @endif
                    @if($exp->tipe)
                        · {{ ucfirst($exp->tipe) }}
                    @endif
                </div>
                @if($exp->deskripsi)
                    <div class="bullet">• {{ $exp->deskripsi }}</div>
                @endif
            </div>
        @endforeach
    </section>
    @endif

    {{-- EDUCATION --}}
    @if($mahasiswa->universitas || $mahasiswa->fakultas || $mahasiswa->jurusan)
    <section class="section">
        <div class="section-title">EDUCATION</div>
        <div class="block">
            <div class="block-title">{{ $mahasiswa->universitas ?? '' }}</div>
            <div class="meta">
                {{ $mahasiswa->fakultas ? $mahasiswa->fakultas.' - ' : '' }}{{ $mahasiswa->jurusan ?? '' }}
            </div>
        </div>
    </section>
    @endif

    {{-- LANGUAGES --}}
    @if($languageList->count())
    <section class="section">
        <div class="section-title">LANGUAGES</div>
        <div class="grid-two">
            @foreach($languageList as $lang)
                <div class="small">{{ $lang }}</div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- CERTIFICATES & SKILLS SIDE BY SIDE (separate sections) --}}
    @if($certificateList->count() || $skillList->count())
    <div class="flex-two">
        {{-- CERTIFICATES (Left) --}}
        <div class="cert-col">
            @if($certificateList->count())
            <div class="section-title">CERTIFICATES</div>
            @foreach($certificateList as $cert)
                <div class="block">
                    <div class="block-title">{{ $cert->nama }}</div>
                    <div class="meta">
                        {{ $cert->penerbit ?? '' }}
                        @if($cert->tanggal_terbit)
                            • {{ \Carbon\Carbon::parse($cert->tanggal_terbit)->format('M Y') }}
                        @endif
                    </div>
                </div>
            @endforeach
            @endif
        </div>

        {{-- SKILLS (Right) --}}
        <div class="skill-col">
            @if($skillList->count())
            <div class="section-title">SKILLS</div>
            @foreach($skillList as $skill)
                <div class="small" style="margin-bottom: 8px;"><span class="inline-skill">{{ $skill->nama }}</span> ({{ ucfirst($skill->level) }})</div>
            @endforeach
            @endif
        </div>
    </div>
    @endif
</div>
</body>
</html>


