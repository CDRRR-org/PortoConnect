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
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            background: #ffffff;
            color: #4c1d95;
            padding: 30px;
            text-align: center;
            border-bottom: 3px solid #9333ea;
        }
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            color: #4c1d95;
            font-weight: bold;
        }
        .header p {
            font-size: 14px;
            color: #666;
        }
        .container {
            padding: 30px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #4c1d95;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #9333ea;
        }
        .profile-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            color: #666;
            margin-bottom: 3px;
        }
        .info-value {
            color: #333;
        }
        .description {
            background: #f9fafb;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #9333ea;
        }
        .skills-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .skill-badge {
            background: #e9d5ff;
            color: #4c1d95;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: bold;
        }
        .project-item, .certificate-item, .experience-item {
            background: #f9fafb;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            border-left: 3px solid #9333ea;
        }
        .project-title, .certificate-title, .experience-title {
            font-weight: bold;
            font-size: 14px;
            color: #4c1d95;
            margin-bottom: 8px;
        }
        .project-description, .certificate-description, .experience-description {
            color: #666;
            margin-bottom: 8px;
        }
        .project-link {
            color: #9333ea;
            text-decoration: none;
            font-size: 11px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #999;
            font-size: 10px;
            border-top: 1px solid #eee;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $mahasiswa->user->name }}</h1>
        @if($portofolio && $portofolio->nama)
            <p>{{ $portofolio->nama }}</p>
        @endif
        @if($portofolio && $portofolio->bidang)
            <p style="margin-top: 5px; font-size: 12px;">{{ strtoupper($portofolio->bidang) }}</p>
        @endif
    </div>

    <div class="container">
        <!-- Profile Information -->
        <div class="section">
            <div class="section-title">Informasi Profil</div>
            <div class="profile-info">
                @if($mahasiswa->nim)
                <div class="info-item">
                    <div class="info-label">NIM</div>
                    <div class="info-value">{{ $mahasiswa->nim }}</div>
                </div>
                @endif
                @if($mahasiswa->jurusan)
                <div class="info-item">
                    <div class="info-label">Jurusan</div>
                    <div class="info-value">{{ $mahasiswa->jurusan }}</div>
                </div>
                @endif
                @if($mahasiswa->fakultas)
                <div class="info-item">
                    <div class="info-label">Fakultas</div>
                    <div class="info-value">{{ $mahasiswa->fakultas }}</div>
                </div>
                @endif
                @if($mahasiswa->universitas)
                <div class="info-item">
                    <div class="info-label">Universitas</div>
                    <div class="info-value">{{ $mahasiswa->universitas }}</div>
                </div>
                @endif
                @if($mahasiswa->no_telp)
                <div class="info-item">
                    <div class="info-label">No. Telepon</div>
                    <div class="info-value">{{ $mahasiswa->no_telp }}</div>
                </div>
                @endif
                @if($mahasiswa->email)
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $mahasiswa->user->email }}</div>
                </div>
                @endif
                @if($mahasiswa->linkedin)
                <div class="info-item">
                    <div class="info-label">LinkedIn</div>
                    <div class="info-value">{{ $mahasiswa->linkedin }}</div>
                </div>
                @endif
                @if($mahasiswa->github)
                <div class="info-item">
                    <div class="info-label">GitHub</div>
                    <div class="info-value">{{ $mahasiswa->github }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Description -->
        @if(($portofolio && $portofolio->deskripsi) || $mahasiswa->deskripsi_diri)
        <div class="section">
            <div class="section-title">Deskripsi</div>
            <div class="description">
                {{ $portofolio && $portofolio->deskripsi ? $portofolio->deskripsi : $mahasiswa->deskripsi_diri }}
            </div>
        </div>
        @endif

        <!-- Skills -->
        @if($portofolio && $portofolio->skills && count($portofolio->skills) > 0)
        <div class="section">
            <div class="section-title">Skills</div>
            <div class="skills-grid">
                @foreach($portofolio->skills as $skill)
                <span class="skill-badge">{{ $skill->nama }} ({{ ucfirst($skill->level) }})</span>
                @endforeach
            </div>
        </div>
        @elseif($mahasiswa->skills && count($mahasiswa->skills) > 0)
        <div class="section">
            <div class="section-title">Skills</div>
            <div class="skills-grid">
                @foreach($mahasiswa->skills as $skill)
                <span class="skill-badge">{{ $skill->nama }} ({{ ucfirst($skill->level) }})</span>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Projects -->
        @if($portofolio && $portofolio->projects && count($portofolio->projects) > 0)
        <div class="section">
            <div class="section-title">Proyek</div>
            @foreach($portofolio->projects as $project)
            <div class="project-item">
                <div class="project-title">{{ $project->judul }}</div>
                @if($project->deskripsi)
                <div class="project-description">{{ $project->deskripsi }}</div>
                @endif
                @if($project->teknologi)
                <div class="info-value" style="margin-top: 5px; font-size: 11px; color: #666;">Teknologi: {{ $project->teknologi }}</div>
                @endif
                @if($project->link)
                <div style="margin-top: 5px;"><a href="{{ $project->link }}" class="project-link">{{ $project->link }}</a></div>
                @endif
            </div>
            @endforeach
        </div>
        @elseif($mahasiswa->projects && count($mahasiswa->projects) > 0)
        <div class="section">
            <div class="section-title">Proyek</div>
            @foreach($mahasiswa->projects as $project)
            <div class="project-item">
                <div class="project-title">{{ $project->judul }}</div>
                @if($project->deskripsi)
                <div class="project-description">{{ $project->deskripsi }}</div>
                @endif
                @if($project->teknologi)
                <div class="info-value" style="margin-top: 5px; font-size: 11px; color: #666;">Teknologi: {{ $project->teknologi }}</div>
                @endif
                @if($project->link)
                <div style="margin-top: 5px;"><a href="{{ $project->link }}" class="project-link">{{ $project->link }}</a></div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <!-- Certificates -->
        @if($portofolio && $portofolio->certificates && count($portofolio->certificates) > 0)
        <div class="section">
            <div class="section-title">Sertifikat</div>
            @foreach($portofolio->certificates as $certificate)
            <div class="certificate-item">
                <div class="certificate-title">{{ $certificate->nama }}</div>
                @if($certificate->deskripsi)
                <div class="certificate-description">{{ $certificate->deskripsi }}</div>
                @endif
            </div>
            @endforeach
        </div>
        @elseif($mahasiswa->certificates && count($mahasiswa->certificates) > 0)
        <div class="section">
            <div class="section-title">Sertifikat</div>
            @foreach($mahasiswa->certificates as $certificate)
            <div class="certificate-item">
                <div class="certificate-title">{{ $certificate->nama }}</div>
                @if($certificate->deskripsi)
                <div class="certificate-description">{{ $certificate->deskripsi }}</div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <!-- Experiences -->
        @if($portofolio && $portofolio->experiences && count($portofolio->experiences) > 0)
        <div class="section">
            <div class="section-title">Pengalaman</div>
            @foreach($portofolio->experiences as $experience)
            <div class="experience-item">
                <div class="experience-title">{{ $experience->judul }}</div>
                @if($experience->deskripsi)
                <div class="experience-description">{{ $experience->deskripsi }}</div>
                @endif
            </div>
            @endforeach
        </div>
        @elseif($mahasiswa->experiences && count($mahasiswa->experiences) > 0)
        <div class="section">
            <div class="section-title">Pengalaman</div>
            @foreach($mahasiswa->experiences as $experience)
            <div class="experience-item">
                <div class="experience-title">{{ $experience->judul }}</div>
                @if($experience->deskripsi)
                <div class="experience-description">{{ $experience->deskripsi }}</div>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <div class="footer">
        <p>Dibuat melalui Porto Connect - {{ date('d F Y') }}</p>
    </div>
</body>
</html>


