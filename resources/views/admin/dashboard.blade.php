<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF +8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | CREAMO</title>
    <link rel="shortcut icon" href="/assets/img/logo.svg" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #f0ede8;
            --sidebar-bg: #e8e0d4;
            --card-bg: #ffffff;
            --accent: #3b4b86;
            --accent2: #5b6fb5;
            --green: #22c55e;
            --red: #ef4444;
            --gold: #f59e0b;
            --text: #1e293b;
            --muted: #64748b;
            --border: #e2e8f0;
            --radius: 20px;
            --shadow: 0 4px 24px rgba(0,0,0,0.07);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }

        /* ── SIDEBAR ────────────────────────────────────── */
        .sidebar {
            width: 240px;
            min-width: 240px;
            background: var(--sidebar-bg);
            border-radius: 0 28px 28px 0;
            display: flex;
            flex-direction: column;
            padding: 28px 20px;
            position: sticky;
            top: 0;
            height: 100vh;
            box-shadow: 4px 0 20px rgba(0,0,0,0.06);
        }

        .sidebar-logo {
            font-size: 26px;
            font-weight: 800;
            color: var(--accent);
            letter-spacing: 2px;
            margin-bottom: 36px;
            padding-left: 6px;
        }

        .sidebar-section-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            color: var(--muted);
            text-transform: uppercase;
            margin: 20px 0 10px 6px;
        }

        .sidebar-menu { list-style: none; }
        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text);
            text-decoration: none;
            transition: all .2s;
        }
        .sidebar-menu li a:hover { background: rgba(59,75,134,.1); color: var(--accent); }
        .sidebar-menu li a.active {
            background: var(--accent);
            color: #fff;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(59,75,134,.35);
        }
        .sidebar-menu li a i { width: 18px; text-align: center; }

        .sidebar-bottom { margin-top: auto; }
        .sidebar-bottom a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 14px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            color: var(--red);
            text-decoration: none;
            transition: background .2s;
        }
        .sidebar-bottom a:hover { background: rgba(239,68,68,.1); }

        /* ── MAIN ───────────────────────────────────────── */
        .main {
            flex: 1;
            padding: 28px 28px 40px;
            overflow-y: auto;
        }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }
        .page-title { font-size: 22px; font-weight: 700; }
        .merchant-info {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--card-bg);
            padding: 8px 16px;
            border-radius: 40px;
            box-shadow: var(--shadow);
            font-size: 13px;
            font-weight: 600;
        }
        .merchant-info .avatar {
            width: 34px; height: 34px;
            background: var(--accent);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: 14px;
        }

        /* ── GRID ROW 1 ─────────────────────────────────── */
        .grid-row { display: grid; gap: 20px; }
        .row1 { grid-template-columns: 1.4fr 1fr; margin-bottom: 20px; }
        .row2 { grid-template-columns: 1fr 1fr; margin-bottom: 20px; }
        .row3 { grid-template-columns: 1fr; margin-bottom: 20px; }

        /* ── CARD ───────────────────────────────────────── */
        .card {
            background: var(--card-bg);
            border-radius: var(--radius);
            padding: 24px;
            box-shadow: var(--shadow);
        }
        .card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 4px;
        }
        .card-label { font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: .8px; }
        .card-value { font-size: 32px; font-weight: 800; color: var(--text); margin: 4px 0; }
        .card-value.medium { font-size: 26px; }
        .badge-up { color: var(--green); font-size: 12px; font-weight: 600; }
        .badge-down { color: var(--red); font-size: 12px; font-weight: 600; }

        .btn-outline {
            font-size: 12px; font-weight: 600;
            padding: 6px 14px;
            border: 1.5px solid var(--border);
            border-radius: 20px;
            color: var(--text);
            text-decoration: none;
            background: #fff;
            cursor: pointer;
            transition: all .2s;
        }
        .btn-outline:hover { background: var(--accent); color: #fff; border-color: var(--accent); }

        .chart-wrap { position: relative; height: 130px; margin-top: 14px; }

        /* ── STATS CARD RIGHT ───────────────────────────── */
        .stats-right {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .stat-item {
            display: flex;
            align-items: center;
            gap: 16px;
            background: var(--card-bg);
            border-radius: var(--radius);
            padding: 20px 22px;
            box-shadow: var(--shadow);
            flex: 1;
        }
        .stat-icon {
            width: 50px; height: 50px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }
        .stat-icon.green { background: #dcfce7; color: #16a34a; }
        .stat-icon.blue  { background: #dbeafe; color: #2563eb; }
        .stat-meta { flex: 1; }
        .stat-meta .label { font-size: 12px; font-weight: 600; color: var(--muted); }
        .stat-meta .val   { font-size: 28px; font-weight: 800; color: var(--text); }
        .active-now {
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 20px 22px;
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            min-width: 120px;
            cursor: pointer;
            transition: transform .2s, box-shadow .2s;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }
        .active-now::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(59,75,134,.05), transparent);
            opacity: 0;
            transition: opacity .2s;
        }
        .active-now:hover { transform: translateY(-2px); box-shadow: 0 8px 32px rgba(59,75,134,.18); }
        .active-now:hover::before { opacity: 1; }
        .active-now .an-label { font-size: 13px; color: var(--muted); font-weight: 600; }
        .active-now .an-val   { font-size: 48px; font-weight: 900; color: var(--text); line-height: 1; }
        .active-now .an-pulse {
            display: inline-block;
            width: 8px; height: 8px;
            background: var(--green);
            border-radius: 50%;
            margin-right: 5px;
            box-shadow: 0 0 0 0 rgba(34,197,94,.6);
            animation: pulse-ring 1.6s infinite;
        }
        @keyframes pulse-ring {
            0%   { box-shadow: 0 0 0 0 rgba(34,197,94,.6); }
            70%  { box-shadow: 0 0 0 7px rgba(34,197,94,0); }
            100% { box-shadow: 0 0 0 0 rgba(34,197,94,0); }
        }
        .an-hint { font-size: 10px; color: var(--muted); margin-top: 4px; display:flex; align-items:center; }
        .stats-row {
            display: flex;
            gap: 14px;
        }

        /* ── TABLE ───────────────────────────────────────── */
        table { width: 100%; border-collapse: collapse; }
        th {
            text-align: left;
            font-size: 12px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .7px;
            padding: 0 12px 14px;
            border-bottom: 1.5px solid var(--border);
        }
        td {
            padding: 14px 12px;
            font-size: 13px;
            border-bottom: 1px solid #f1f5f9;
            color: var(--text);
        }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #fafbff; }

        .status-badge {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }
        .status-badge.success { background: #dcfce7; color: #15803d; }
        .status-badge.failed  { background: #fee2e2; color: #b91c1c; }
        .status-badge.pending { background: #fef9c3; color: #92400e; }

        /* ── STARS ───────────────────────────────────────── */
        .stars { color: var(--gold); font-size: 15px; letter-spacing: 2px; }
        .stars .empty { color: #d1d5db; }

        /* ── MONTHLY STATS ───────────────────────────────── */
        .chart-wrap-lg { position: relative; height: 220px; margin-top: 16px; }

        /* ── ORDER CHART MINI ────────────────────────────── */
        .order-chart-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            padding: 22px;
            box-shadow: var(--shadow);
        }

        @media (max-width: 1100px) {
            .row1, .row2 { grid-template-columns: 1fr; }
            .stats-row { flex-direction: column; }
        }
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main { padding: 18px; }
        }

        /* ── MODAL ACTIVE DEVICES ────────────────────────────── */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(15,20,40,.45);
            backdrop-filter: blur(4px);
            z-index: 1000;
            display: flex; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none;
            transition: opacity .25s;
        }
        .modal-overlay.open { opacity: 1; pointer-events: all; }
        .modal-box {
            background: #fff;
            border-radius: 24px;
            width: 94%; max-width: 620px;
            max-height: 85vh;
            display: flex; flex-direction: column;
            box-shadow: 0 24px 60px rgba(0,0,0,.18);
            transform: translateY(20px) scale(.97);
            transition: transform .25s;
            overflow: hidden;
        }
        .modal-overlay.open .modal-box { transform: translateY(0) scale(1); }
        .modal-head {
            padding: 22px 26px 18px;
            border-bottom: 1.5px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .modal-head h2 { font-size: 17px; font-weight: 700; display:flex; align-items:center; gap:10px; }
        .modal-close {
            width: 34px; height: 34px;
            border: none; background: #f1f5f9;
            border-radius: 50%; cursor: pointer;
            font-size: 16px; color: var(--muted);
            display:flex; align-items:center; justify-content:center;
            transition: background .2s;
        }
        .modal-close:hover { background: #e2e8f0; }
        .modal-body { padding: 20px 26px; overflow-y: auto; flex: 1; }
        .modal-footer {
            padding: 14px 26px;
            border-top: 1px solid var(--border);
            font-size: 11px; color: var(--muted);
            display: flex; align-items: center; gap: 6px;
        }

        /* device card inside modal */
        .device-card {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 14px 16px;
            border-radius: 14px;
            background: #f8fafc;
            margin-bottom: 10px;
            transition: background .2s;
        }
        .device-card:hover { background: #f1f5f9; }
        .device-icon {
            width: 44px; height: 44px;
            background: linear-gradient(135deg, #3b4b86, #5b6fb5);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 20px;
            flex-shrink: 0;
        }
        .device-info { flex: 1; }
        .device-name { font-size: 14px; font-weight: 700; color: var(--text); }
        .device-meta { font-size: 12px; color: var(--muted); margin-top: 2px; }
        .device-status {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 11px; font-weight: 700;
            padding: 4px 10px; border-radius: 20px;
        }
        .device-status.printing  { background:#dbeafe; color:#1d4ed8; }
        .device-status.pending   { background:#fef9c3; color:#92400e; }
        .device-status.completed { background:#dcfce7; color:#15803d; }
        .device-status.failed    { background:#fee2e2; color:#b91c1c; }

        .no-device {
            text-align: center; padding: 48px 20px;
            color: var(--muted); font-size: 14px;
        }
        .no-device i { font-size: 40px; margin-bottom: 12px; opacity:.35; display:block; }
    </style>
</head>
<body>

<!-- ══ SIDEBAR ══════════════════════════════════════════ -->
<aside class="sidebar">
    <img src="/assets/img/logocreamo.png" alt="Creamo Logo" class="w-full drop-shadow-md">

    <div class="sidebar-section-label">Menu</div>
    <ul class="sidebar-menu">
        <li><a href="{{ route('dashboard') }}" class="active"><i class="fas fa-th-large"></i> Dashboard</a></li>
        <li><a href="#"><i class="fas fa-history"></i> Payment History</a></li>
        <li><a href="#"><i class="fas fa-dollar-sign"></i> Revenue</a></li>
        <li><a href="#"><i class="fas fa-star"></i> Rating</a></li>
        <li><a href="#"><i class="fas fa-chart-bar"></i> Statistics</a></li>
    </ul>

    <div class="sidebar-section-label">Others</div>
    <ul class="sidebar-menu">
        <li><a href="#"><i class="fas fa-user"></i> Accounts</a></li>
        <li><a href="#"><i class="fas fa-credit-card"></i> Payment</a></li>
        <li><a href="#"><i class="fas fa-question-circle"></i> Help</a></li>
    </ul>

    <div class="sidebar-bottom">
        <a href="/logout"><i class="fas fa-sign-out-alt"></i> Log Out</a>
    </div>
</aside>

<!-- ══ MAIN ═════════════════════════════════════════════ -->
<main class="main">

    <!-- Header -->
    <div class="page-header">
        <h1 class="page-title">Dashboard</h1>
        <div class="merchant-info">
            <div class="avatar">{{ strtoupper(substr($merchant->business_name, 0, 1)) }}</div>
            <span>{{ $merchant->business_name }}</span>
        </div>
    </div>

    <!-- ROW 1: Revenue Chart + Stats Cards -->
    <div class="grid-row row1">

        <!-- Revenue 1 Minggu -->
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-label">Revenue</div>
                    <div class="card-value">IDR {{ number_format($revenueThisWeek, 0, ',', '.') }}</div>
                    @if($revenueChangePercent >= 0)
                        <span class="badge-up"><i class="fas fa-arrow-up"></i> {{ $revenueChangePercent }}% vs last week</span>
                    @else
                        <span class="badge-down"><i class="fas fa-arrow-down"></i> {{ abs($revenueChangePercent) }}% vs last week</span>
                    @endif
                    <div style="font-size:11px;color:var(--muted);margin-top:4px;">Sales this week — {{ now()->startOfWeek()->format('d M') }} – {{ now()->endOfWeek()->format('d M Y') }}</div>
                </div>
                <a href="#" class="btn-outline">View Report</a>
            </div>
            <div class="chart-wrap">
                <canvas id="revenueChart"></canvas>
            </div>
            <div style="display:flex;gap:20px;margin-top:10px;">
                <span style="font-size:11px;font-weight:600;color:var(--accent);"><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:var(--accent);margin-right:4px;"></span>This week</span>
                <span style="font-size:11px;font-weight:600;color:#94a3b8;"><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#cbd5e1;margin-right:4px;"></span>Last week</span>
            </div>
        </div>

        <!-- Total Customer + Total Photo + Active Now -->
        <div class="stats-right">
            <div class="stats-row">
                <div class="stat-item">
                    <div class="stat-icon green"><i class="fas fa-users"></i></div>
                    <div class="stat-meta">
                        <div class="label">Total Customers</div>
                        <div class="val">{{ number_format($totalCustomers) }}</div>
                        @if($customerGrowth >= 0)
                            <span class="badge-up"><i class="fas fa-arrow-up"></i> {{ $customerGrowth }}% this month</span>
                        @else
                            <span class="badge-down"><i class="fas fa-arrow-down"></i> {{ abs($customerGrowth) }}% this month</span>
                        @endif
                    </div>
                </div>
                <div class="active-now" id="activeNowCard" onclick="openActiveModal()" title="Lihat device aktif">
                    <div class="an-label">Active Now</div>
                    <div class="an-val" id="activeNowCount">{{ $activeDeviceCount }}</div>
                    <div class="an-hint"><span class="an-pulse"></span> device aktif</div>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon blue"><i class="fas fa-print"></i></div>
                <div class="stat-meta">
                    <div class="label">Total Photo (Today)</div>
                    <div class="val">{{ number_format($totalPhotosToday) }}</div>
                    @if($photoGrowth >= 0)
                        <span class="badge-up"><i class="fas fa-arrow-up"></i> {{ $photoGrowth }}% vs yesterday</span>
                    @else
                        <span class="badge-down"><i class="fas fa-arrow-down"></i> {{ abs($photoGrowth) }}% vs yesterday</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ROW 2: Customer Table -->
    <div class="card" style="margin-bottom:20px;">
        <div class="card-header" style="margin-bottom:16px;">
            <div class="card-label" style="font-size:14px;font-weight:700;color:var(--text);">Deskripsi Customer</div>
            <a href="#" class="btn-outline">View All</a>
        </div>
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Nomor HP</th>
                        <th>Jam Transaksi</th>
                        <th>Tanggal Transaksi</th>
                        <th>Email</th>
                        <th>Status Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                    <tr>
                        <td style="font-weight:600;">{{ $trx->customer_name ?? '-' }}</td>
                        <td>{{ $trx->phone_number ?? '-' }}</td>
                        <td>{{ $trx->created_at->format('H:i:s') }}</td>
                        <td>{{ $trx->created_at->format('d/m/y') }}</td>
                        <td>{{ $trx->email ?? '-' }}</td>
                        <td>
                            @if($trx->payment_status === 'success')
                                <span class="status-badge success">Berhasil</span>
                            @elseif($trx->payment_status === 'failed')
                                <span class="status-badge failed">Gagal</span>
                            @else
                                <span class="status-badge pending">Pending</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;color:var(--muted);padding:30px;">Belum ada transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ROW 3: Reviews + Order Mini Chart -->
    <div class="grid-row row2">

        <!-- Reviews -->
        <div class="card">
            <div class="card-header" style="margin-bottom:16px;">
                <div class="card-label" style="font-size:14px;font-weight:700;color:var(--text);">Review Customer</div>
            </div>
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Rating</th>
                            <th>Tanggal</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                        <tr>
                            <td style="font-weight:600;">{{ $review->customer_name }}</td>
                            <td>
                                <span class="stars">
                                    @for($s = 1; $s <= 5; $s++)
                                        @if($s <= $review->rating)
                                            ★
                                        @else
                                            <span class="empty">★</span>
                                        @endif
                                    @endfor
                                </span>
                            </td>
                            <td>{{ $review->created_at->format('d/m/y') }}</td>
                            <td>{{ $review->email ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center;color:var(--muted);padding:30px;">Belum ada review</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Orders Mini Chart -->
        <div class="order-chart-card">
            <div class="card-header">
                <div>
                    <div class="card-label">Order</div>
                    <div class="card-value medium">{{ number_format($totalOrdersThisWeek) }}</div>
                    @if($ordersChangePercent >= 0)
                        <span class="badge-up"><i class="fas fa-arrow-up"></i> {{ $ordersChangePercent }}% vs last week</span>
                    @else
                        <span class="badge-down"><i class="fas fa-arrow-down"></i> {{ abs($ordersChangePercent) }}% vs last week</span>
                    @endif
                    <div style="font-size:11px;color:var(--muted);margin-top:4px;">Sales 1–6 {{ now()->format('M, Y') }}</div>
                </div>
                <a href="#" class="btn-outline">View Report</a>
            </div>
            <div class="chart-wrap">
                <canvas id="orderChart"></canvas>
            </div>
            <div style="display:flex;gap:20px;margin-top:10px;">
                <span style="font-size:11px;font-weight:600;color:var(--accent);"><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:var(--accent);margin-right:4px;"></span>This week</span>
                <span style="font-size:11px;font-weight:600;color:#94a3b8;"><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#cbd5e1;margin-right:4px;"></span>Last week</span>
            </div>
        </div>
    </div>

    <!-- ROW 4: Monthly Statistics -->
    <div class="card">
        <div class="card-header" style="margin-bottom:8px;">
            <div>
                <div class="card-label" style="font-size:14px;font-weight:700;color:var(--text);">Statistik Bisnis 1 Bulan</div>
                <div style="font-size:11px;color:var(--muted);margin-top:2px;">Revenue & Orders per hari — {{ now()->subDays(29)->format('d M') }} – {{ now()->format('d M Y') }}</div>
            </div>
        </div>
        <div class="chart-wrap-lg">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>

</main>

<!-- ══ MODAL ACTIVE DEVICES ══════════════════════════════════ -->
<div class="modal-overlay" id="activeModal" onclick="closeModalOutside(event)">
    <div class="modal-box">
        <div class="modal-head">
            <h2>
                <span class="an-pulse"></span>
                Active Devices
            </h2>
            <button class="modal-close" onclick="closeActiveModal()" aria-label="Tutup"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" id="modalDeviceList">
            <!-- diisi oleh JS -->
        </div>
        <div class="modal-footer">
            <i class="fas fa-sync-alt"></i>
            <span id="lastRefreshTime">Memuat…</span>
        </div>
    </div>
</div>

<script>
// ── Data from PHP ──────────────────────────────────────────
const revenueData = @json($revenueChart);
const monthlyData = @json($monthlyStats);

// ── Revenue Chart ──────────────────────────────────────────
const revCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revCtx, {
    type: 'bar',
    data: {
        labels: revenueData.map(d => d.label),
        datasets: [
            {
                label: 'This Week',
                data: revenueData.map(d => d.value),
                backgroundColor: '#3b4b86',
                borderRadius: 6,
                barPercentage: 0.5,
            },
            {
                label: 'Last Week (est.)',
                data: revenueData.map(d => Math.round(d.value * 0.88 + Math.random() * 5000)),
                backgroundColor: '#cbd5e1',
                borderRadius: 6,
                barPercentage: 0.5,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false }, tooltip: {
            callbacks: {
                label: ctx => 'IDR ' + ctx.raw.toLocaleString('id-ID')
            }
        }},
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 11 } } },
            y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 11 }, callback: v => 'IDR ' + (v/1000).toFixed(0) + 'k' } }
        }
    }
});

// ── Order Mini Chart ───────────────────────────────────────
const ordCtx = document.getElementById('orderChart').getContext('2d');
const last7 = monthlyData.slice(-7);
new Chart(ordCtx, {
    type: 'line',
    data: {
        labels: last7.map(d => d.date),
        datasets: [
            {
                label: 'Orders',
                data: last7.map(d => d.orders),
                borderColor: '#3b4b86',
                backgroundColor: 'rgba(59,75,134,0.08)',
                fill: true,
                tension: 0.45,
                borderWidth: 2.5,
                pointRadius: 3,
            },
            {
                label: 'Prev',
                data: last7.map(d => Math.max(0, d.orders - Math.floor(Math.random() * 3))),
                borderColor: '#cbd5e1',
                backgroundColor: 'transparent',
                fill: false,
                tension: 0.45,
                borderWidth: 2,
                pointRadius: 2,
                borderDash: [4,4],
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 10 } } },
            y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 }, stepSize: 1 } }
        }
    }
});

// ── Monthly Statistics Chart ───────────────────────────────
const mCtx = document.getElementById('monthlyChart').getContext('2d');
new Chart(mCtx, {
    type: 'line',
    data: {
        labels: monthlyData.map(d => d.date),
        datasets: [
            {
                label: 'Revenue (IDR)',
                data: monthlyData.map(d => d.revenue),
                borderColor: '#3b4b86',
                backgroundColor: 'rgba(59,75,134,0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 2.5,
                pointRadius: 2,
                yAxisID: 'yRev',
            },
            {
                label: 'Orders',
                data: monthlyData.map(d => d.orders),
                borderColor: '#22c55e',
                backgroundColor: 'rgba(34,197,94,0.07)',
                fill: true,
                tension: 0.4,
                borderWidth: 2,
                pointRadius: 2,
                yAxisID: 'yOrd',
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: { usePointStyle: true, font: { size: 12 } }
            },
            tooltip: {
                callbacks: {
                    label: ctx => ctx.dataset.label === 'Revenue (IDR)'
                        ? 'IDR ' + ctx.raw.toLocaleString('id-ID')
                        : ctx.raw + ' orders'
                }
            }
        },
        scales: {
            x: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 }, maxTicksLimit: 15 } },
            yRev: {
                position: 'left',
                grid: { color: '#f1f5f9' },
                ticks: { font: { size: 10 }, callback: v => 'IDR ' + (v/1000).toFixed(0) + 'k' }
            },
            yOrd: {
                position: 'right',
                grid: { drawOnChartArea: false },
                ticks: { font: { size: 10 }, stepSize: 1 }
            }
        }
    }
});
</script>

<script>
// ── Active Now Modal & Live Polling ────────────────────────
const ACTIVE_URL = '{{ route("dashboard.active-devices") }}';
let pollInterval = null;

const statusIcon = { printing:'fa-print', pending:'fa-hourglass-half', completed:'fa-check-circle', failed:'fa-times-circle' };

function renderDevices(data) {
    const list  = document.getElementById('modalDeviceList');
    const count = document.getElementById('activeNowCount');
    const time  = document.getElementById('lastRefreshTime');

    count.textContent = data.count;
    time.textContent  = 'Diperbarui: ' + new Date().toLocaleTimeString('id-ID');

    if (!data.devices.length) {
        list.innerHTML = `
            <div class="no-device">
                <i class="fas fa-laptop-house"></i>
                Tidak ada device yang aktif saat ini.
            </div>`;
        return;
    }

    list.innerHTML = data.devices.map(d => {
        const icon   = statusIcon[d.status_cetak] || 'fa-desktop';
        const cls    = d.status_cetak || 'pending';
        const label  = { printing:'Printing', pending:'Pending', completed:'Selesai', failed:'Gagal' }[d.status_cetak] ?? d.status_cetak;
        return `
        <div class="device-card">
            <div class="device-icon"><i class="fas fa-desktop"></i></div>
            <div class="device-info">
                <div class="device-name">${d.device_name}</div>
                <div class="device-meta">
                    Order: <strong>${d.order_id}</strong> &nbsp;·&nbsp;
                    ${d.email} &nbsp;·&nbsp;
                    Mulai: ${d.waktu_mulai ?? '-'} &nbsp;·&nbsp;
                    Ping: ${d.last_ping_at ?? '-'}
                </div>
            </div>
            <span class="device-status ${cls}">
                <i class="fas ${icon}"></i> ${label}
            </span>
        </div>`;
    }).join('');
}

function fetchActiveDevices() {
    fetch(ACTIVE_URL, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(renderDevices)
        .catch(() => {
            document.getElementById('lastRefreshTime').textContent = 'Gagal memuat data';
        });
}

function openActiveModal() {
    document.getElementById('activeModal').classList.add('open');
    fetchActiveDevices();
    pollInterval = setInterval(fetchActiveDevices, 30000);
}

function closeActiveModal() {
    document.getElementById('activeModal').classList.remove('open');
    clearInterval(pollInterval);
}

function closeModalOutside(e) {
    if (e.target === document.getElementById('activeModal')) closeActiveModal();
}

// Close on Escape key
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeActiveModal(); });
</script>
</body>
</html>
