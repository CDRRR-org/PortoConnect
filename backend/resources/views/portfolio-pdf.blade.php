<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Portfolio - {{ $mahasiswa->user->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 16px;
            line-height: 1.4;
            color: #000000;
            background: #ffffff;
        }
        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: #ffffff;
            position: relative;
            padding: 0;
        }
        .header {
            padding: 20mm 20mm 12mm 20mm;
            border-bottom: 1px solid #cccccc;
            position: relative;
        }
        .header-content {
            display: table;
            width: 100%;
        }
        .name-section {
            display: table-cell;
            vertical-align: middle;
            width: 65%;
        }
        .name {
            font-size: 48px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 4px;
            letter-spacing: 0.5px;
        }
        .title {
            font-size: 21px;
            color: #333333;
            font-weight: normal;
        }
        .contact-section {
            display: table-cell;
            vertical-align: top;
            width: 35%;
            text-align: right;
            font-size: 15px;
            color: #333333;
            line-height: 1.8;
            padding-top: 5mm;
        }
        .contact-item {
            margin-bottom: 2mm;
        }
        .gradient-strip {
            position: absolute;
            right: 0;
            top: 0;
            width: 12mm;
            height: 100%;
            background: linear-gradient(to bottom, #4c1d95 0%, #6b21a8 50%, #9333ea 100%);
        }
        .container {
            padding: 10mm 20mm 15mm 20mm;
            position: relative;
        }
        .section {
            margin-bottom: 8mm;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 23px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 5mm;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .experience-item {
            margin-bottom: 6mm;
        }
        .item-title {
            font-weight: bold;
            font-size: 17px;
            color: #000000;
            margin-bottom: 1mm;
        }
        .item-subtitle {
            font-size: 16px;
            color: #666666;
            margin-bottom: 2mm;
        }
        .item-description {
            font-size: 17px;
            color: #333333;
            line-height: 1.5;
            margin-left: 4mm;
        }
        .item-description ul {
            list-style-type: disc;
            margin-left: 6mm;
            padding-left: 0;
        }
        .item-description li {
            margin-bottom: 1mm;
        }
        .two-column {
            display: table;
            width: 100%;
            margin-top: 3mm;
        }
        .column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 8mm;
        }
        .column:last-child {
            padding-right: 0;
        }
        .certificate-item, .skill-item, .language-item, .education-item-simple {
            font-size: 15px;
            color: #333333;
            margin-bottom: 2mm;
            line-height: 1.5;
        }
        .footer {
            position: absolute;
            bottom: 8mm;
            left: 20mm;
            right: 20mm;
            text-align: center;
            font-size: 10px;
            color: #666666;
            border-top: 1px solid #cccccc;
            padding-top: 3mm;
        }
        @media print {
            .section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="gradient-strip"></div>
            <div class="header-content">
                <div class="name-section">
                    <div class="name">{{ $mahasiswa->user->name }}</div>
                    @if($portofolio && $portofolio->bidang)
                        <div class="title">{{ strtoupper($portofolio->bidang) }}</div>
                    @elseif($portofolio && $portofolio->nama)
                        <div class="title">{{ $portofolio->nama }}</div>
                    @endif
                </div>
                <div class="contact-section">
                    @if($mahasiswa->no_telp)
                        <div class="contact-item">+{{ $mahasiswa->no_telp }}</div>
                    @endif
                    @if($mahasiswa->user->email)
                        <div class="contact-item">{{ $mahasiswa->user->email }}</div>
                    @endif
                    @if($mahasiswa->alamat)
                        <div class="contact-item">{{ $mahasiswa->alamat }}</div>
                    @endif
                    @if($mahasiswa->github)
                        <div class="contact-item">{{ $mahasiswa->github }}</div>
                    @endif
                    @if($mahasiswa->linkedin)
                        <div class="contact-item">{{ $mahasiswa->linkedin }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="container">
            <!-- EXPERIENCE Section -->
            @if($portofolio && $portofolio->experiences && count($portofolio->experiences) > 0)
            <div class="section">
                <div class="section-title">EXPERIENCE</div>
                @foreach($portofolio->experiences as $experience)
                <div class="experience-item">
                    <div class="item-title">{{ $experience->judul }}</div>
                    @if($experience->perusahaan)
                        <div class="item-subtitle">{{ $experience->perusahaan }}</div>
                    @endif
                    @if($experience->tanggal_mulai)
                        <div class="item-subtitle">
                            {{ date('M Y', strtotime($experience->tanggal_mulai)) }}
                            @if($experience->masih_berlangsung)
                                - Present
                            @elseif($experience->tanggal_selesai)
                                - {{ date('M Y', strtotime($experience->tanggal_selesai)) }}
                            @endif
                            @if($experience->tipe)
                                • {{ ucfirst($experience->tipe) }}
                            @endif
                        </div>
                    @endif
                    @if($experience->deskripsi)
                        <div class="item-description">
                            @php
                                $desc = $experience->deskripsi;
                                $lines = explode("\n", $desc);
                                echo '<ul>';
                                foreach($lines as $line) {
                                    $line = trim($line);
                                    if ($line) {
                                        echo '<li>' . $line . '</li>';
                                    }
                                }
                                echo '</ul>';
                            @endphp
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
            @elseif($mahasiswa->experiences && count($mahasiswa->experiences) > 0)
            <div class="section">
                <div class="section-title">EXPERIENCE</div>
                @foreach($mahasiswa->experiences as $experience)
                <div class="experience-item">
                    <div class="item-title">{{ $experience->judul }}</div>
                    @if($experience->perusahaan)
                        <div class="item-subtitle">{{ $experience->perusahaan }}</div>
                    @endif
                    @if($experience->tanggal_mulai)
                        <div class="item-subtitle">
                            {{ date('M Y', strtotime($experience->tanggal_mulai)) }}
                            @if($experience->masih_berlangsung)
                                - Present
                            @elseif($experience->tanggal_selesai)
                                - {{ date('M Y', strtotime($experience->tanggal_selesai)) }}
                            @endif
                            @if($experience->tipe)
                                • {{ ucfirst($experience->tipe) }}
                            @endif
                        </div>
                    @endif
                    @if($experience->deskripsi)
                        <div class="item-description">
                            @php
                                $desc = $experience->deskripsi;
                                $lines = explode("\n", $desc);
                                echo '<ul>';
                                foreach($lines as $line) {
                                    $line = trim($line);
                                    if ($line) {
                                        echo '<li>' . $line . '</li>';
                                    }
                                }
                                echo '</ul>';
                            @endphp
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif

            <!-- EDUCATION Section -->
            <div class="section">
                <div class="section-title">EDUCATION</div>
                <div class="two-column">
                    <div class="column">
                        @php
                            $educationParts = [];
                            if ($mahasiswa->universitas) {
                                $educationParts[] = $mahasiswa->universitas;
                            }
                            if ($mahasiswa->fakultas) {
                                $educationParts[] = $mahasiswa->fakultas;
                            }
                            if ($mahasiswa->jurusan) {
                                $educationParts[] = $mahasiswa->jurusan;
                            }
                            if (count($educationParts) > 0) {
                                echo '<div class="education-item-simple">' . implode(' - ', $educationParts) . '</div>';
                            }
                        @endphp
                        @if($portofolio && $portofolio->education)
                            @php
                                $education = is_string($portofolio->education) ? json_decode($portofolio->education, true) : $portofolio->education;
                                if (is_array($education)) {
                                    $count = 0;
                                    foreach($education as $edu) {
                                        if ($count >= 2) break;
                                        if (is_string($edu)) {
                                            echo '<div class="education-item-simple">' . $edu . '</div>';
                                        } else {
                                            $eduParts = [];
                                            if (!empty($edu['instansi'])) $eduParts[] = $edu['instansi'];
                                            if (!empty($edu['jurusan'])) $eduParts[] = $edu['jurusan'];
                                            $eduText = implode(' - ', $eduParts);
                                            if (!empty($edu['tahun'])) {
                                                $eduText .= ' (' . $edu['tahun'] . ')';
                                            }
                                            echo '<div class="education-item-simple">' . $eduText . '</div>';
                                        }
                                        $count++;
                                    }
                                } else {
                                    echo '<div class="education-item-simple">' . $portofolio->education . '</div>';
                                }
                            @endphp
                        @endif
                    </div>
                    <div class="column">
                        @if($portofolio && $portofolio->education)
                            @php
                                $education = is_string($portofolio->education) ? json_decode($portofolio->education, true) : $portofolio->education;
                                if (is_array($education) && count($education) > 2) {
                                    $count = 0;
                                    foreach($education as $edu) {
                                        if ($count < 2) {
                                            $count++;
                                            continue;
                                        }
                                        if ($count >= 4) break;
                                        if (is_string($edu)) {
                                            echo '<div class="education-item-simple">' . $edu . '</div>';
                                        } else {
                                            $eduParts = [];
                                            if (!empty($edu['instansi'])) $eduParts[] = $edu['instansi'];
                                            if (!empty($edu['jurusan'])) $eduParts[] = $edu['jurusan'];
                                            $eduText = implode(' - ', $eduParts);
                                            if (!empty($edu['tahun'])) {
                                                $eduText .= ' (' . $edu['tahun'] . ')';
                                            }
                                            echo '<div class="education-item-simple">' . $eduText . '</div>';
                                        }
                                        $count++;
                                    }
                                }
                            @endphp
                        @endif
                    </div>
                </div>
            </div>

            <!-- CERTIFICATES Section -->
            @if($portofolio && $portofolio->certificates && count($portofolio->certificates) > 0)
            <div class="section">
                <div class="section-title">CERTIFICATES</div>
                @foreach($portofolio->certificates as $certificate)
                <div class="certificate-item">
                    <strong>{{ $certificate->nama }}</strong>
                    @if($certificate->penerbit)
                        • {{ $certificate->penerbit }}
                    @endif
                    @if($certificate->tanggal_terbit)
                        • {{ date('M Y', strtotime($certificate->tanggal_terbit)) }}
                    @endif
                </div>
                @endforeach
            </div>
            @elseif($mahasiswa->certificates && count($mahasiswa->certificates) > 0)
            <div class="section">
                <div class="section-title">CERTIFICATES</div>
                @foreach($mahasiswa->certificates as $certificate)
                <div class="certificate-item">
                    <strong>{{ $certificate->nama }}</strong>
                    @if($certificate->penerbit)
                        • {{ $certificate->penerbit }}
                    @endif
                    @if($certificate->tanggal_terbit)
                        • {{ date('M Y', strtotime($certificate->tanggal_terbit)) }}
                    @endif
                </div>
                @endforeach
            </div>
            @endif

            <!-- SKILLS Section -->
            @if($portofolio && $portofolio->skills && count($portofolio->skills) > 0)
            <div class="section">
                <div class="section-title">SKILLS</div>
                <div class="two-column">
                    <div class="column">
                        @foreach(array_slice($portofolio->skills->toArray(), 0, ceil(count($portofolio->skills) / 2)) as $skill)
                        <div class="skill-item"><strong>{{ $skill['nama'] }}</strong> ({{ ucfirst($skill['level']) }})</div>
                        @endforeach
                    </div>
                    <div class="column">
                        @foreach(array_slice($portofolio->skills->toArray(), ceil(count($portofolio->skills) / 2)) as $skill)
                        <div class="skill-item"><strong>{{ $skill['nama'] }}</strong> ({{ ucfirst($skill['level']) }})</div>
                        @endforeach
                    </div>
                </div>
            </div>
            @elseif($mahasiswa->skills && count($mahasiswa->skills) > 0)
            <div class="section">
                <div class="section-title">SKILLS</div>
                <div class="two-column">
                    <div class="column">
                        @foreach(array_slice($mahasiswa->skills->toArray(), 0, ceil(count($mahasiswa->skills) / 2)) as $skill)
                        <div class="skill-item"><strong>{{ $skill['nama'] }}</strong> ({{ ucfirst($skill['level']) }})</div>
                        @endforeach
                    </div>
                    <div class="column">
                        @foreach(array_slice($mahasiswa->skills->toArray(), ceil(count($mahasiswa->skills) / 2)) as $skill)
                        <div class="skill-item"><strong>{{ $skill['nama'] }}</strong> ({{ ucfirst($skill['level']) }})</div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- LANGUAGES Section -->
            @if($portofolio && $portofolio->language)
            <div class="section">
                <div class="section-title">LANGUAGES</div>
                @php
                    $languages = is_string($portofolio->language) ? json_decode($portofolio->language, true) : $portofolio->language;
                    if (is_array($languages)) {
                        foreach($languages as $lang) {
                            if (is_string($lang)) {
                                echo '<div class="language-item">' . $lang . '</div>';
                            } else {
                                $langText = ($lang['nama'] ?? '') . ($lang['level'] ? ' ' . ucfirst($lang['level']) : '');
                                echo '<div class="language-item">' . $langText . '</div>';
                            }
                        }
                    } else {
                        echo '<div class="language-item">' . $portofolio->language . '</div>';
                    }
                @endphp
            </div>
            @endif
        </div>

        <div class="footer">
            <p>Generated via Porto Connect - {{ date('F Y') }}</p>
        </div>
    </div>
</body>
</html>
