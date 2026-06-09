<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF +8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | CREAMO</title>
    <link rel="shortcut icon" href="/assets/img/logo.svg" type="image/x-icon">
    <link rel="preload" as="image" href="/assets/img/logocreamo.png">
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

        /* ══ FULL-PAGE MODAL PANELS ══════════════════════════════ */
        .panel-overlay {
            position: fixed; inset: 0;
            z-index: 2000;
            display: flex;
            pointer-events: none;
            opacity: 0;
            transition: opacity .3s;
        }
        .panel-overlay.open {
            opacity: 1;
            pointer-events: all;
        }
        .panel-backdrop {
            position: absolute; inset: 0;
            background: rgba(15,20,40,.5);
            backdrop-filter: blur(6px);
        }
        .panel-sheet {
            position: relative;
            margin: auto;
            width: 96%;
            max-width: 1000px;
            max-height: 90vh;
            background: #fff;
            border-radius: 28px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 32px 80px rgba(0,0,0,.22);
            transform: translateY(40px) scale(.97);
            transition: transform .32s cubic-bezier(.34,1.28,.64,1);
            overflow: hidden;
        }
        .panel-overlay.open .panel-sheet {
            transform: translateY(0) scale(1);
        }
        .panel-head {
            padding: 22px 28px 18px;
            border-bottom: 1.5px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }
        .panel-head-left { display: flex; align-items: center; gap: 14px; }
        .panel-head-icon {
            width: 42px; height: 42px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: #fff;
        }
        .panel-head h2 { font-size: 18px; font-weight: 800; color: var(--text); }
        .panel-head-sub { font-size: 12px; color: var(--muted); margin-top: 2px; }
        .panel-close {
            width: 36px; height: 36px;
            border: none; background: #f1f5f9;
            border-radius: 50%; cursor: pointer;
            font-size: 15px; color: var(--muted);
            display: flex; align-items: center; justify-content: center;
            transition: background .2s, color .2s;
        }
        .panel-close:hover { background: #e2e8f0; color: var(--text); }
        .panel-body {
            padding: 24px 28px;
            overflow-y: auto;
            flex: 1;
        }
        .panel-summary {
            display: flex; gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 22px;
        }
        .panel-stat {
            flex: 1; min-width: 130px;
            background: #f8fafc;
            border-radius: 16px;
            padding: 16px 20px;
            text-align: center;
        }
        .panel-stat .ps-val { font-size: 28px; font-weight: 800; color: var(--text); }
        .panel-stat .ps-lbl { font-size: 11px; color: var(--muted); font-weight: 600; margin-top: 2px; }
        .panel-stat .ps-badge { font-size: 11px; font-weight: 700; margin-top: 4px; }
        .panel-table { width: 100%; border-collapse: collapse; }
        .panel-table th {
            text-align: left; font-size: 11px; font-weight: 700;
            color: var(--muted); text-transform: uppercase; letter-spacing: .7px;
            padding: 0 14px 12px; border-bottom: 1.5px solid var(--border);
        }
        .panel-table td {
            padding: 13px 14px; font-size: 13px;
            border-bottom: 1px solid #f1f5f9; color: var(--text);
        }
        .panel-table tr:last-child td { border-bottom: none; }
        .panel-table tr:hover td { background: #fafbff; }
        .panel-chart-wrap { position: relative; height: 260px; margin-bottom: 20px; }
        .panel-filter-bar {
            display: flex; gap: 10px; flex-wrap: wrap;
            margin-bottom: 18px; align-items: center;
        }
        .filter-chip {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px; font-weight: 600;
            border: 1.5px solid var(--border);
            background: #fff; color: var(--muted);
            cursor: pointer; transition: all .2s;
        }
        .filter-chip.active, .filter-chip:hover {
            background: var(--accent); color: #fff; border-color: var(--accent);
        }
        .panel-empty {
            text-align: center; padding: 48px 20px;
            color: var(--muted); font-size: 14px;
        }
        .panel-empty i { font-size: 42px; margin-bottom: 14px; opacity: .3; display: block; }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        /* ── FORM INPUTS (panels) ───────────────────────── */
        .panel-form-group {
            margin-bottom: 18px;
        }
        .panel-form-group label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 6px;
        }
        .panel-input {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid var(--border);
            border-radius: 14px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: var(--text);
            background: #f8fafc;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }
        .panel-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59,75,134,.12);
            background: #fff;
        }
        .panel-input::placeholder {
            color: #94a3b8;
        }
        .panel-select {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid var(--border);
            border-radius: 14px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: var(--text);
            background: #f8fafc;
            cursor: pointer;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
            -webkit-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2364748b' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 40px;
        }
        .panel-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59,75,134,.12);
            background-color: #fff;
        }
        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 28px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            color: #fff;
            border: none;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: transform .15s, box-shadow .2s;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(59,75,134,.35);
        }
        .btn-primary:active {
            transform: translateY(0);
        }
        .btn-primary:disabled {
            opacity: .55;
            cursor: not-allowed;
            transform: none;
        }
        .panel-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        /* ── TOAST NOTIFICATION ─────────────────────────── */
        .toast-notification {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 9999;
            min-width: 320px;
            padding: 16px 22px;
            border-radius: 16px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            box-shadow: 0 12px 40px rgba(0,0,0,.18);
            display: flex;
            align-items: center;
            gap: 12px;
            transform: translateX(120%);
            transition: transform .35s cubic-bezier(.34,1.28,.64,1);
        }
        .toast-notification.show {
            transform: translateX(0);
        }
        .toast-notification.success {
            background: #dcfce7;
            color: #15803d;
            border: 1.5px solid #bbf7d0;
        }
        .toast-notification.error {
            background: #fee2e2;
            color: #b91c1c;
            border: 1.5px solid #fecaca;
        }
        .toast-notification i {
            font-size: 18px;
        }

        /* ── WITHDRAW STATUS BADGES ─────────────────────── */
        .wd-status {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }
        .wd-status.pending  { background: #fef9c3; color: #92400e; }
        .wd-status.approved { background: #dcfce7; color: #15803d; }
        .wd-status.rejected { background: #fee2e2; color: #b91c1c; }

        /* ── ABOUT PANEL ────────────────────────────────── */
        .about-hero {
            background: linear-gradient(135deg, #3b4b86 0%, #5b6fb5 100%);
            border-radius: 20px;
            padding: 36px 32px;
            color: #fff;
            margin-bottom: 24px;
        }
        .about-hero h3 {
            font-size: 26px;
            font-weight: 800;
            line-height: 1.3;
            margin-bottom: 16px;
        }
        .about-hero p {
            font-size: 14px;
            line-height: 1.8;
            opacity: .92;
        }
        .about-contact {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 14px;
        }
        .about-contact-item {
            background: #f8fafc;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
        }
        .about-contact-item i {
            font-size: 24px;
            color: var(--accent);
            margin-bottom: 10px;
            display: block;
        }
        .about-contact-item .aci-label {
            font-size: 11px;
            color: var(--muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        .about-contact-item .aci-val {
            font-size: 13px;
            font-weight: 700;
            color: var(--text);
            margin-top: 4px;
            word-break: break-all;
        }
    </style>
</head>
<body>

<!-- ══ SIDEBAR ══════════════════════════════════════════ -->
<aside class="sidebar">
    <img src="/assets/img/logocreamo.png" alt="Creamo Logo" class="w-full drop-shadow-md" fetchpriority="high" loading="eager">

    <div class="sidebar-section-label">Menu</div>
    <ul class="sidebar-menu">
        <li><a href="{{ route('dashboard') }}" class="active"><i class="fas fa-th-large"></i> Dashboard</a></li>
        <li><a href="#" onclick="openPanel('panelPayment');return false;"><i class="fas fa-history"></i> Payment History</a></li>
        <li><a href="#" onclick="openPanel('panelRevenue');return false;"><i class="fas fa-dollar-sign"></i> Revenue</a></li>
        <li><a href="#" onclick="openPanel('panelRating');return false;"><i class="fas fa-star"></i> Rating</a></li>
        <li><a href="#" onclick="openPanel('panelStatistics');return false;"><i class="fas fa-chart-bar"></i> Statistics</a></li>
        <li><a href="/photo"><i class="fas fa-camera"></i> Booth</a></li>
    </ul>

    <div class="sidebar-section-label">Others</div>
    <ul class="sidebar-menu">
        <li><a href="#" onclick="openPanel('panelAccount');return false;"><i class="fas fa-user"></i> Account</a></li>
        <li><a href="#" onclick="openPanel('panelWithdraw');return false;"><i class="fas fa-credit-card"></i> Payment</a></li>
        <li><a href="#" onclick="openPanel('panelAbout');return false;"><i class="fas fa-info-circle"></i> About</a></li>
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
                    <div class="card-value" id="val-revenue">
                        <span class="skeleton" style="display:inline-block;width:120px;height:24px;border-radius:6px;background:#e2e8f0;animation:pulse 1.5s infinite;"></span>
                    </div>
                    <span id="val-revenue-badge" class="badge-up">
                        <span class="skeleton" style="display:inline-block;width:80px;height:16px;border-radius:4px;background:#e2e8f0;animation:pulse 1.5s infinite;"></span>
                    </span>
                    <div style="font-size:11px;color:var(--muted);margin-top:4px;">Sales this week — {{ now()->startOfWeek()->format('d M') }} – {{ now()->endOfWeek()->format('d M Y') }}</div>
                </div>
                <a href="#" class="btn-outline" onclick="openPanel('panelRevenue');return false;">View Report</a>
            </div>
            <div class="chart-wrap" style="position:relative;">
                <!-- Skeleton bars -->
                <div id="skeleton-revenue-chart" style="display:flex;align-items:flex-end;gap:8px;height:130px;padding:10px 0;">
                    @foreach([60,80,45,90,70,55,85] as $h)
                    <div style="flex:1;height:{{$h}}%;background:#e2e8f0;border-radius:6px;animation:pulse 1.5s infinite;"></div>
                    @endforeach
                </div>
                <canvas id="revenueChart" style="display:none;"></canvas>
            </div>
            <div style="display:flex;gap:20px;margin-top:10px;">
                <span style="font-size:11px;font-weight:600;color:var(--accent);"><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:var(--accent);margin-right:4px;"></span>This week</span>
                <span style="font-size:11px;font-weight:600;color:#94a3b8;"><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#cbd5e1;margin-right:4px;"></span>Last week</span>
            </div>
        </div>

        <!-- Total Customer + Total Photo -->
        <div class="stats-right">
            <div class="stat-item">
                <div class="stat-icon green"><i class="fas fa-users"></i></div>
                <div class="stat-meta">
                    <div class="label">Total Customers</div>
                    <div class="val" id="val-customers">
                        <span style="display:inline-block;width:60px;height:28px;border-radius:6px;background:#e2e8f0;animation:pulse 1.5s infinite;"></span>
                    </div>
                    <span id="val-customer-badge" class="badge-up">
                        <span style="display:inline-block;width:80px;height:14px;border-radius:4px;background:#e2e8f0;animation:pulse 1.5s infinite;"></span>
                    </span>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon blue"><i class="fas fa-print"></i></div>
                <div class="stat-meta">
                    <div class="label">Total Photo (Today)</div>
                    <div class="val" id="val-photos">
                        <span style="display:inline-block;width:60px;height:28px;border-radius:6px;background:#e2e8f0;animation:pulse 1.5s infinite;"></span>
                    </div>
                    <span id="val-photo-badge" class="badge-up">
                        <span style="display:inline-block;width:80px;height:14px;border-radius:4px;background:#e2e8f0;animation:pulse 1.5s infinite;"></span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- ROW 2: Customer Table -->
    <div class="card" style="margin-bottom:20px;">
        <div class="card-header" style="margin-bottom:16px;">
            <div class="card-label" style="font-size:14px;font-weight:700;color:var(--text);">Deskripsi Customer</div>
            <a href="#" class="btn-outline" onclick="openPanel('panelPayment');return false;">View All</a>
        </div>
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Jam Transaksi</th>
                        <th>Tanggal Transaksi</th>
                        <th>Email</th>
                        <th>Status Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                    <tr>
                        <td style="font-weight:600;">{{ $trx->order_id ?? '-' }}</td>
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
                        <td colspan="5" style="text-align:center;color:var(--muted);padding:30px;">Belum ada transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ROW 3: Reviews + Statistik 1 Bulan -->
    <div class="grid-row row2">

        <!-- Reviews -->
        <div class="card">
            <div class="card-header" style="margin-bottom:16px;">
                <div class="card-label" style="font-size:14px;font-weight:700;color:var(--text);">Review Customer</div>
                <a href="#" class="btn-outline" onclick="openPanel('panelRating');return false;">View All</a>
            </div>
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Rating</th>
                            <th>Tanggal</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                        <tr>
                            <td style="font-weight:600;">{{ $loop->iteration }}</td>
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

        <!-- Statistik 1 Bulan -->
        <div class="card">
            <div class="card-header" style="margin-bottom:12px;">
                <div>
                    <div class="card-label" style="font-size:14px;font-weight:700;color:var(--text);">Statistik 1 Bulan</div>
                    <div style="font-size:11px;color:var(--muted);margin-top:2px;">Revenue &amp; Orders — {{ now()->subDays(29)->format('d M') }} – {{ now()->format('d M Y') }}</div>
                </div>
                <a href="#" class="btn-outline" onclick="openPanel('panelStatistics');return false;">View All</a>
            </div>
            <div class="chart-wrap-lg" style="position:relative;">
                <div id="skeleton-monthly-chart" style="display:flex;align-items:flex-end;gap:4px;height:220px;padding:10px 0;">
                    @foreach([40,60,35,80,55,70,45,90,65,75,50,85,40,60,70,55,80,45,65,90,35,75,60,50,85,40,70,55,80,65] as $h)
                    <div style="flex:1;height:{{$h}}%;background:#e2e8f0;border-radius:4px;animation:pulse 1.5s infinite;"></div>
                    @endforeach
                </div>
                <canvas id="monthlyChart" style="display:none;"></canvas>
            </div>
        </div>
    </div>



</main>

{{-- ══ PANEL: PAYMENT HISTORY ════════════════════════════════ --}}
<div class="panel-overlay" id="panelPayment">
    <div class="panel-backdrop" onclick="closePanel('panelPayment')"></div>
    <div class="panel-sheet">
        <div class="panel-head">
            <div class="panel-head-left">
                <div class="panel-head-icon" style="background:linear-gradient(135deg,#3b4b86,#5b6fb5);"><i class="fas fa-history"></i></div>
                <div>
                    <h2>Payment History</h2>
                    <div class="panel-head-sub">Seluruh riwayat transaksi customer</div>
                </div>
            </div>
            <button class="panel-close" onclick="closePanel('panelPayment')"><i class="fas fa-times"></i></button>
        </div>
        <div class="panel-body">
            {{-- Summary Stats --}}
            <div class="panel-summary">
                <div class="panel-stat">
                    <div class="ps-val" id="panel-total-customers">-</div>
                    <div class="ps-lbl">Total Customer</div>
                </div>
                <div class="panel-stat">
                    <div class="ps-val" style="color:var(--green);">{{ $transactions->where('payment_status','success')->count() }}</div>
                    <div class="ps-lbl">Berhasil</div>
                </div>
                <div class="panel-stat">
                    <div class="ps-val" style="color:var(--gold);">{{ $transactions->where('payment_status','pending')->count() }}</div>
                    <div class="ps-lbl">Pending</div>
                </div>
                <div class="panel-stat">
                    <div class="ps-val" style="color:var(--red);">{{ $transactions->where('payment_status','failed')->count() }}</div>
                    <div class="ps-lbl">Gagal</div>
                </div>
            </div>
            {{-- Filter Chips --}}
            <div class="panel-filter-bar">
                <span style="font-size:12px;font-weight:600;color:var(--muted);">Filter:</span>
                <button class="filter-chip active" onclick="filterTable('payTbl','all',this)">Semua</button>
                <button class="filter-chip" onclick="filterTable('payTbl','success',this)">Berhasil</button>
                <button class="filter-chip" onclick="filterTable('payTbl','pending',this)">Pending</button>
                <button class="filter-chip" onclick="filterTable('payTbl','failed',this)">Gagal</button>
            </div>
            {{-- Full Table --}}
            <div style="overflow-x:auto;">
                <table class="panel-table" id="payTbl">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Nomor HP</th>
                            <th>Email</th>
                            <th>Jam Transaksi</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $i => $trx)
                        <tr data-status="{{ $trx->payment_status }}">
                            <td style="color:var(--muted);font-weight:600;">{{ $i+1 }}</td>
                            <td style="font-weight:700;">{{ $trx->customer_name ?? '-' }}</td>
                            <td>{{ $trx->phone_number ?? '-' }}</td>
                            <td>{{ $trx->email ?? '-' }}</td>
                            <td>{{ $trx->created_at->format('H:i:s') }}</td>
                            <td>{{ $trx->created_at->format('d/m/Y') }}</td>
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
                        <tr><td colspan="7" class="panel-empty"><i class="fas fa-inbox"></i>Belum ada transaksi</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ══ PANEL: REVENUE ═════════════════════════════════════════ --}}
<div class="panel-overlay" id="panelRevenue">
    <div class="panel-backdrop" onclick="closePanel('panelRevenue')"></div>
    <div class="panel-sheet">
        <div class="panel-head">
            <div class="panel-head-left">
                <div class="panel-head-icon" style="background:linear-gradient(135deg,#16a34a,#22c55e);"><i class="fas fa-dollar-sign"></i></div>
                <div>
                    <h2>Revenue</h2>
                    <div class="panel-head-sub">Laporan pendapatan lengkap — {{ now()->startOfWeek()->format('d M') }} s/d {{ now()->format('d M Y') }}</div>
                </div>
            </div>
            <button class="panel-close" onclick="closePanel('panelRevenue')"><i class="fas fa-times"></i></button>
        </div>
        <div class="panel-body">
            <div class="panel-summary">
                <div class="panel-stat">
                    <div class="ps-val" style="font-size:22px;" id="panel-revenue-this-week">-</div>
                    <div class="ps-lbl">Revenue Minggu Ini</div>
                    <div class="ps-badge" id="panel-revenue-badge">-</div>
                </div>
                <div class="panel-stat">
                    <div class="ps-val" id="panel-orders-this-week">-</div>
                    <div class="ps-lbl">Order Minggu Ini</div>
                    <div class="ps-badge" id="panel-orders-badge">-</div>
                </div>
                <div class="panel-stat">
                    <div class="ps-val" id="panel-photos-today">-</div>
                    <div class="ps-lbl">Foto Hari Ini</div>
                </div>
            </div>
            <div style="font-size:13px;font-weight:700;color:var(--text);margin-bottom:10px;">Grafik Revenue & Order (30 Hari)</div>
            <div class="panel-chart-wrap">
                <canvas id="panelRevenueChart"></canvas>
            </div>
            <div style="font-size:13px;font-weight:700;color:var(--text);margin-bottom:10px;">Revenue per Hari (Minggu Ini)</div>
            <div style="overflow-x:auto;">
                <table class="panel-table">
                    <thead>
                        <tr><th>Hari</th><th style="text-align:right;">Revenue (IDR)</th></tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ══ PANEL: RATING / REVIEW ══════════════════════════════════ --}}
<div class="panel-overlay" id="panelRating">
    <div class="panel-backdrop" onclick="closePanel('panelRating')"></div>
    <div class="panel-sheet">
        <div class="panel-head">
            <div class="panel-head-left">
                <div class="panel-head-icon" style="background:linear-gradient(135deg,#f59e0b,#fbbf24);"><i class="fas fa-star"></i></div>
                <div>
                    <h2>Rating & Review Customer</h2>
                    <div class="panel-head-sub">Seluruh ulasan yang diberikan customer</div>
                </div>
            </div>
            <button class="panel-close" onclick="closePanel('panelRating')"><i class="fas fa-times"></i></button>
        </div>
        <div class="panel-body">
            <div class="panel-summary">
                <div class="panel-stat">
                    <div class="ps-val">{{ $reviews->count() }}</div>
                    <div class="ps-lbl">Total Review</div>
                </div>
                <div class="panel-stat">
                    <div class="ps-val" style="color:var(--gold);">{{ $reviews->count() > 0 ? number_format($reviews->avg('rating'),1) : '-' }}</div>
                    <div class="ps-lbl">Rata-rata Rating</div>
                </div>
                <div class="panel-stat">
                    <div class="ps-val" style="color:var(--green);">{{ $reviews->where('rating',5)->count() }}</div>
                    <div class="ps-lbl">Bintang 5 ⭐</div>
                </div>
                <div class="panel-stat">
                    <div class="ps-val" style="color:var(--red);">{{ $reviews->where('rating','<=',2)->count() }}</div>
                    <div class="ps-lbl">Rating Rendah</div>
                </div>
            </div>
            <div style="overflow-x:auto;">
                <table class="panel-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Email</th>
                            <th>Rating</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $i => $review)
                        <tr>
                            <td style="color:var(--muted);font-weight:600;">{{ $i+1 }}</td>
                            <td>{{ $review->email ?? '-' }}</td>
                            <td>
                                <span class="stars">
                                    @for($s = 1; $s <= 5; $s++)
                                        @if($s <= $review->rating) ★ @else <span class="empty">★</span> @endif
                                    @endfor
                                </span>
                                <span style="font-size:11px;color:var(--muted);margin-left:4px;">({{ $review->rating }}/5)</span>
                            </td>
                            <td>{{ $review->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5"><div class="panel-empty"><i class="fas fa-star"></i>Belum ada review</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ══ PANEL: STATISTICS ═══════════════════════════════════════ --}}
<div class="panel-overlay" id="panelStatistics">
    <div class="panel-backdrop" onclick="closePanel('panelStatistics')"></div>
    <div class="panel-sheet">
        <div class="panel-head">
            <div class="panel-head-left">
                <div class="panel-head-icon" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);"><i class="fas fa-chart-bar"></i></div>
                <div>
                    <h2>Statistik & Order</h2>
                    <div class="panel-head-sub">Data lengkap 30 hari terakhir — {{ now()->subDays(29)->format('d M') }} s/d {{ now()->format('d M Y') }}</div>
                </div>
            </div>
            <button class="panel-close" onclick="closePanel('panelStatistics')"><i class="fas fa-times"></i></button>
        </div>
        <div class="panel-body">
            <div class="panel-summary">
                <div class="panel-stat">
                    <div class="ps-val" id="panel-stat-orders">-</div>
                    <div class="ps-lbl">Order Minggu Ini</div>
                    <div class="ps-badge" id="panel-stat-orders-badge">-</div>
                </div>
                <div class="panel-stat">
                    <div class="ps-val" id="panel-stat-customers">-</div>
                    <div class="ps-lbl">Total Customer</div>
                </div>
                <div class="panel-stat">
                    <div class="ps-val" style="font-size:20px;" id="panel-stat-revenue">-</div>
                    <div class="ps-lbl">Revenue Minggu Ini</div>
                </div>
            </div>
            <div style="font-size:13px;font-weight:700;color:var(--text);margin-bottom:10px;">Grafik Statistik 30 Hari Terakhir</div>
            <div class="panel-chart-wrap">
                <canvas id="panelStatChart"></canvas>
            </div>
            <div style="font-size:13px;font-weight:700;color:var(--text);margin-bottom:10px;margin-top:8px;">Detail Per Hari</div>
            <div style="overflow-x:auto;">
                <table class="panel-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th style="text-align:right;">Revenue (IDR)</th>
                            <th style="text-align:right;">Orders</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>



{{-- ══ TOAST NOTIFICATION ═══════════════════════════════════════ --}}
<div class="toast-notification" id="toastNotif">
    <i class="fas fa-check-circle"></i>
    <span id="toastMsg">Success</span>
</div>

{{-- ══ PANEL: WITHDRAW (PAYMENT) ═══════════════════════════════ --}}
<div class="panel-overlay" id="panelWithdraw">
    <div class="panel-backdrop" onclick="closePanel('panelWithdraw')"></div>
    <div class="panel-sheet">
        <div class="panel-head">
            <div class="panel-head-left">
                <div class="panel-head-icon" style="background:linear-gradient(135deg,#0ea5e9,#38bdf8);"><i class="fas fa-credit-card"></i></div>
                <div>
                    <h2>Penarikan Dana</h2>
                    <div class="panel-head-sub">Withdraw saldo dari pembayaran customer</div>
                </div>
            </div>
            <button class="panel-close" onclick="closePanel('panelWithdraw')"><i class="fas fa-times"></i></button>
        </div>
        <div class="panel-body">
            {{-- Balance Summary --}}
            <div class="panel-summary">
                <div class="panel-stat">
                    <div class="ps-val" id="wd-total-revenue" style="font-size:20px;">-</div>
                    <div class="ps-lbl">Total Revenue</div>
                </div>
                <div class="panel-stat">
                    <div class="ps-val" id="wd-total-withdrawn" style="font-size:20px;color:var(--green);">-</div>
                    <div class="ps-lbl">Sudah Ditarik</div>
                </div>
                <div class="panel-stat">
                    <div class="ps-val" id="wd-total-pending" style="font-size:20px;color:var(--gold);">-</div>
                    <div class="ps-lbl">Menunggu Proses</div>
                </div>
                <div class="panel-stat">
                    <div class="ps-val" id="wd-available" style="font-size:20px;color:var(--accent);">-</div>
                    <div class="ps-lbl">Saldo Tersedia</div>
                </div>
            </div>

            {{-- Withdraw Form --}}
            <div style="font-size:14px;font-weight:700;color:var(--text);margin-bottom:14px;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-paper-plane" style="color:var(--accent);"></i> Request Penarikan
            </div>
            <form id="withdrawForm" onsubmit="submitWithdraw(event)">
                <div class="panel-row">
                    <div class="panel-form-group">
                        <label>Jumlah (IDR)</label>
                        <input type="number" class="panel-input" name="amount" id="wdAmount" placeholder="Min. 10.000" min="10000" required>
                    </div>
                    <div class="panel-form-group">
                        <label>Metode</label>
                        <select class="panel-select" name="method" id="wdMethod" required>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="ewallet">E-Wallet</option>
                        </select>
                    </div>
                </div>
                <div class="panel-row">
                    <div class="panel-form-group">
                        <label>Nama Bank / E-Wallet</label>
                        <input type="text" class="panel-input" name="bank_name" id="wdBankName" placeholder="cth: BCA, Mandiri, GoPay" required>
                    </div>
                    <div class="panel-form-group">
                        <label>Nomor Rekening / ID</label>
                        <input type="text" class="panel-input" name="account_number" id="wdAccNumber" placeholder="cth: 1234567890" required>
                    </div>
                </div>
                <div class="panel-form-group">
                    <label>Nama Pemilik Rekening</label>
                    <input type="text" class="panel-input" name="account_holder" id="wdAccHolder" placeholder="Nama sesuai rekening" required>
                </div>
                <div class="panel-form-group">
                    <label>Catatan (opsional)</label>
                    <input type="text" class="panel-input" name="notes" id="wdNotes" placeholder="Catatan tambahan...">
                </div>
                <button type="submit" class="btn-primary" id="wdSubmitBtn">
                    <i class="fas fa-paper-plane"></i> Kirim Request Withdraw
                </button>
            </form>

            {{-- Withdraw History --}}
            <div style="font-size:14px;font-weight:700;color:var(--text);margin:28px 0 14px;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-list" style="color:var(--accent);"></i> Riwayat Penarikan
            </div>
            <div style="overflow-x:auto;">
                <table class="panel-table" id="wdHistoryTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jumlah</th>
                            <th>Metode</th>
                            <th>Rekening</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="6" class="panel-empty"><i class="fas fa-spinner fa-spin"></i>Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ══ PANEL: ACCOUNT (CHANGE PASSWORD) ══════════════════════════ --}}
<div class="panel-overlay" id="panelAccount">
    <div class="panel-backdrop" onclick="closePanel('panelAccount')"></div>
    <div class="panel-sheet" style="max-width:560px;">
        <div class="panel-head">
            <div class="panel-head-left">
                <div class="panel-head-icon" style="background:linear-gradient(135deg,#8b5cf6,#a78bfa);"><i class="fas fa-user-cog"></i></div>
                <div>
                    <h2>Pengaturan Akun</h2>
                    <div class="panel-head-sub">Kelola keamanan akun Anda</div>
                </div>
            </div>
            <button class="panel-close" onclick="closePanel('panelAccount')"><i class="fas fa-times"></i></button>
        </div>
        <div class="panel-body">
            {{-- Account Info --}}
            <div style="display:flex;align-items:center;gap:16px;background:#f8fafc;border-radius:16px;padding:20px;margin-bottom:24px;">
                <div style="width:52px;height:52px;background:linear-gradient(135deg,var(--accent),var(--accent2));border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px;font-weight:800;">
                    {{ strtoupper(substr($merchant->business_name, 0, 1)) }}
                </div>
                <div>
                    <div style="font-size:16px;font-weight:700;color:var(--text);">{{ $merchant->business_name }}</div>
                    <div style="font-size:13px;color:var(--muted);">{{ $merchant->email }}</div>
                </div>
            </div>

            {{-- Change Password Form --}}
            @php
                $hasPassword = !empty($merchant->password) && str_starts_with($merchant->password, '$');
            @endphp
            <div style="font-size:14px;font-weight:700;color:var(--text);margin-bottom:14px;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-lock" style="color:var(--accent);"></i> {{ $hasPassword ? 'Ubah Password' : 'Set Password Baru' }}
            </div>
            @if(!$hasPassword)
                <div style="font-size:12px;color:var(--muted);background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:10px 14px;margin-bottom:16px;line-height:1.4;">
                    <i class="fas fa-info-circle" style="color:#16a34a;margin-right:6px;"></i>
                    Akun Anda terdaftar melalui Google/Apple. Silakan set password baru agar Anda dapat login langsung menggunakan email dan password.
                </div>
            @endif
            <form id="changePasswordForm" onsubmit="submitChangePassword(event)">
                @if($hasPassword)
                <div class="panel-form-group">
                    <label>Password Lama</label>
                    <input type="password" class="panel-input" name="old_password" id="cpOldPassword" placeholder="Masukkan password lama" required>
                </div>
                @endif
                <div class="panel-form-group">
                    <label>Password Baru</label>
                    <input type="password" class="panel-input" name="new_password" id="cpNewPassword" placeholder="Min. 8 karakter" minlength="8" required>
                </div>
                <div class="panel-form-group">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password" class="panel-input" name="new_password_confirmation" id="cpConfirmPassword" placeholder="Ulangi password baru" minlength="8" required>
                </div>
                <button type="submit" class="btn-primary" id="cpSubmitBtn">
                    <i class="fas fa-save"></i> {{ $hasPassword ? 'Simpan Password' : 'Set Password' }}
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ══ PANEL: ABOUT ══════════════════════════════════════════════ --}}
<div class="panel-overlay" id="panelAbout">
    <div class="panel-backdrop" onclick="closePanel('panelAbout')"></div>
    <div class="panel-sheet" style="max-width:720px;">
        <div class="panel-head">
            <div class="panel-head-left">
                <div class="panel-head-icon" style="background:linear-gradient(135deg,#3b4b86,#5b6fb5);"><i class="fas fa-info-circle"></i></div>
                <div>
                    <h2>Tentang CREAMO</h2>
                    <div class="panel-head-sub">Informasi tentang platform kami</div>
                </div>
            </div>
            <button class="panel-close" onclick="closePanel('panelAbout')"><i class="fas fa-times"></i></button>
        </div>
        <div class="panel-body">
            {{-- Hero About --}}
            <div class="about-hero">
                <div style="display:inline-block;background:rgba(255,255,255,.15);padding:6px 18px;border-radius:20px;font-size:12px;font-weight:600;margin-bottom:16px;">About Us</div>
                <h3>Photobooth Digital<br>untuk Setiap Momen<br>Berhargamu</h3>
                <p>
                    Creamo adalah platform Software as a Service (SaaS) inovatif yang membawa pengalaman photobooth ke level berikutnya. Kami menyediakan solusi dokumentasi digital yang praktis, interaktif, dan mudah diakses. Dengan Creamo, setiap perayaan mulai dari festival, bazaar, hingga acara kampus dapat menghadirkan keseruan berfoto tanpa perlu menyewa bilik atau mesin cetak tradisional yang memakan banyak tempat.
                </p>
                <br>
                <p>
                    Misi kami adalah memudahkan semua orang dalam mengabadikan kebahagiaan. Melalui integrasi teknologi yang seamless, mulai dari kemudahan transaksi hingga kustomisasi bingkai foto yang menarik, Creamo memastikan pengalaman menyimpan kenangan menjadi lebih cepat, modern, dan pastinya tak terlupakan.
                </p>
            </div>

            {{-- Feature Cards --}}
            <div style="font-size:14px;font-weight:700;color:var(--text);margin-bottom:14px;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-star" style="color:var(--gold);"></i> Keunggulan Kami
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;margin-bottom:24px;">
                <div style="background:#f8fafc;border-radius:16px;padding:20px;text-align:center;">
                    <div style="width:48px;height:48px;background:#dbeafe;border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:20px;color:#2563eb;">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div style="font-size:13px;font-weight:700;color:var(--text);margin-bottom:4px;">Easy Payment</div>
                    <div style="font-size:11px;color:var(--muted);line-height:1.5;">Kemudahan bertransaksi menggunakan QRIS dan berbagai E-Wallet.</div>
                </div>
                <div style="background:#f8fafc;border-radius:16px;padding:20px;text-align:center;">
                    <div style="width:48px;height:48px;background:#dcfce7;border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:20px;color:#16a34a;">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div style="font-size:13px;font-weight:700;color:var(--text);margin-bottom:4px;">Safe Transaction</div>
                    <div style="font-size:11px;color:var(--muted);line-height:1.5;">Sistem pembayaran terintegrasi dan terenkripsi.</div>
                </div>
                <div style="background:#f8fafc;border-radius:16px;padding:20px;text-align:center;">
                    <div style="width:48px;height:48px;background:#fef9c3;border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:20px;color:#ca8a04;">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <div style="font-size:13px;font-weight:700;color:var(--text);margin-bottom:4px;">Quick Transaction</div>
                    <div style="font-size:11px;color:var(--muted);line-height:1.5;">Verifikasi pembayaran instan dalam hitungan detik.</div>
                </div>
            </div>

            {{-- Contact Info --}}
            <div style="font-size:14px;font-weight:700;color:var(--text);margin-bottom:14px;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-map-marker-alt" style="color:var(--accent);"></i> Hubungi Kami
            </div>
            <div class="about-contact">
                <div class="about-contact-item">
                    <i class="fas fa-building"></i>
                    <div class="aci-label">Head Office</div>
                    <div class="aci-val">Jalan Pelita Blok D No. 9, Banjarbaru, Kalimantan Selatan</div>
                </div>
                <div class="about-contact-item">
                    <i class="fas fa-envelope"></i>
                    <div class="aci-label">Email</div>
                    <div class="aci-val">creamophotography@gmail.com</div>
                </div>
                <div class="about-contact-item">
                    <i class="fas fa-phone"></i>
                    <div class="aci-label">Telepon</div>
                    <div class="aci-val">0852-4871-3983</div>
                </div>
            </div>

            {{-- Footer --}}
            <div style="text-align:center;margin-top:28px;padding-top:18px;border-top:1.5px solid var(--border);font-size:12px;color:var(--muted);">
                &copy; 2026 Creamo, All rights reserved.
            </div>
        </div>
    </div>
</div>

<script>
let revenueData = [];
let monthlyData = [];

let revChart = null;
let monthlyChart = null;

fetch('/dashboard/stats')
    .then(r => r.json())
    .then(data => {
        const fmt = n => parseInt(n).toLocaleString('id-ID');

        // ── Card Revenue ──
        document.getElementById('val-revenue').innerHTML = 'IDR ' + fmt(data.revenueThisWeek);
        const revBadge = document.getElementById('val-revenue-badge');
        if (data.revenueChangePercent >= 0) {
            revBadge.className = 'badge-up';
            revBadge.innerHTML = `<i class="fas fa-arrow-up"></i> ${data.revenueChangePercent}% vs last week`;
        } else {
            revBadge.className = 'badge-down';
            revBadge.innerHTML = `<i class="fas fa-arrow-down"></i> ${Math.abs(data.revenueChangePercent)}% vs last week`;
        }

        // ── Card Customers ──
        document.getElementById('val-customers').textContent = fmt(data.totalCustomers);
        const custBadge = document.getElementById('val-customer-badge');
        if (data.customerGrowth >= 0) {
            custBadge.className = 'badge-up';
            custBadge.innerHTML = `<i class="fas fa-arrow-up"></i> ${data.customerGrowth}% this month`;
        } else {
            custBadge.className = 'badge-down';
            custBadge.innerHTML = `<i class="fas fa-arrow-down"></i> ${Math.abs(data.customerGrowth)}% this month`;
        }

        // ── Card Photos ──
        document.getElementById('val-photos').textContent = fmt(data.totalPhotosToday);
        const photoBadge = document.getElementById('val-photo-badge');
        if (data.photoGrowth >= 0) {
            photoBadge.className = 'badge-up';
            photoBadge.innerHTML = `<i class="fas fa-arrow-up"></i> ${data.photoGrowth}% vs yesterday`;
        } else {
            photoBadge.className = 'badge-down';
            photoBadge.innerHTML = `<i class="fas fa-arrow-down"></i> ${Math.abs(data.photoGrowth)}% vs yesterday`;
        }

        // ── Panel summaries ──
        document.getElementById('panel-total-customers').textContent   = fmt(data.totalCustomers);
        document.getElementById('panel-stat-customers').textContent    = fmt(data.totalCustomers);
        document.getElementById('panel-photos-today').textContent      = fmt(data.totalPhotosToday);
        document.getElementById('panel-orders-this-week').textContent  = fmt(data.totalOrdersThisWeek);
        document.getElementById('panel-stat-orders').textContent       = fmt(data.totalOrdersThisWeek);
        document.getElementById('panel-revenue-this-week').textContent = 'IDR ' + fmt(data.revenueThisWeek);
        document.getElementById('panel-stat-revenue').textContent      = 'IDR ' + fmt(data.revenueThisWeek);

        // Revenue badge panel
        const rp = document.getElementById('panel-revenue-badge');
        rp.style.color = data.revenueChangePercent >= 0 ? 'var(--green)' : 'var(--red)';
        rp.innerHTML = data.revenueChangePercent >= 0
            ? `<i class="fas fa-arrow-up"></i> ${data.revenueChangePercent}% vs lalu`
            : `<i class="fas fa-arrow-down"></i> ${Math.abs(data.revenueChangePercent)}% vs lalu`;

        // Orders badge panel
        const op = document.getElementById('panel-orders-badge');
        op.style.color = data.ordersChangePercent >= 0 ? 'var(--green)' : 'var(--red)';
        op.innerHTML = data.ordersChangePercent >= 0
            ? `<i class="fas fa-arrow-up"></i> ${data.ordersChangePercent}%`
            : `<i class="fas fa-arrow-down"></i> ${Math.abs(data.ordersChangePercent)}%`;

        // Stat orders badge panel
        const sop = document.getElementById('panel-stat-orders-badge');
        sop.style.color = data.ordersChangePercent >= 0 ? 'var(--green)' : 'var(--red)';
        sop.innerHTML = data.ordersChangePercent >= 0
            ? `<i class="fas fa-arrow-up"></i> ${data.ordersChangePercent}%`
            : `<i class="fas fa-arrow-down"></i> ${Math.abs(data.ordersChangePercent)}%`;
    });

fetch('/dashboard/chart-data')
    .then(r => r.json())
    .then(data => {
        revenueData = data.revenueChart;
        monthlyData = data.monthlyStats;
        renderRevenueChart();
        renderMonthlyChart();

        // ── Update tabel Revenue per Hari di panel ──
        const revTableBody = document.querySelector('#panelRevenue .panel-table tbody');
        if (revTableBody) {
            revTableBody.innerHTML = revenueData.map(rc => `
                <tr>
                    <td>${rc.label}</td>
                    <td style="text-align:right;font-weight:700;">${parseInt(rc.value).toLocaleString('id-ID')}</td>
                </tr>
            `).join('');
        }

        // ── Update tabel Statistik per Hari di panel ──
        const statTableBody = document.querySelector('#panelStatistics .panel-table tbody');
        if (statTableBody) {
            statTableBody.innerHTML = monthlyData.map(stat => `
                <tr>
                    <td>${stat.date}</td>
                    <td style="text-align:right;font-weight:700;">${parseInt(stat.revenue).toLocaleString('id-ID')}</td>
                    <td style="text-align:right;font-weight:700;">${stat.orders}</td>
                </tr>
            `).join('');
        }
    });

// ── Revenue Chart ──────────────────────────────────────────
function renderRevenueChart() {
    document.getElementById('skeleton-revenue-chart').style.display = 'none';
    document.getElementById('revenueChart').style.display = 'block';

    const revCtx = document.getElementById('revenueChart').getContext('2d');
    if (revChart) revChart.destroy();
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
                    label: 'Last Week',
                    data: revenueData.map(d => d.value),
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
}

// ── Monthly Statistics Chart ───────────────────────────────
function renderMonthlyChart() {
    document.getElementById('skeleton-monthly-chart').style.display = 'none';
    document.getElementById('monthlyChart').style.display = 'block';

    const mCtx = document.getElementById('monthlyChart').getContext('2d');
    if (monthlyChart) monthlyChart.destroy();
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
}
</script>

<script>
// ── Panel open / close ─────────────────────────────────────
function openPanel(id) {
    document.getElementById(id).classList.add('open');
    document.body.style.overflow = 'hidden';
    // Init charts lazily when panel opens
    if (id === 'panelRevenue' && !window._revPanelInited) {
        window._revPanelInited = true;
        new Chart(document.getElementById('panelRevenueChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: monthlyData.map(d => d.date),
                datasets: [
                    { label: 'Revenue (IDR)', data: monthlyData.map(d => d.revenue), borderColor: '#16a34a', backgroundColor: 'rgba(22,163,74,0.1)', fill: true, tension: 0.4, borderWidth: 2.5, pointRadius: 2, yAxisID: 'yRev' },
                    { label: 'Orders', data: monthlyData.map(d => d.orders), borderColor: '#3b4b86', backgroundColor: 'rgba(59,75,134,0.07)', fill: true, tension: 0.4, borderWidth: 2, pointRadius: 2, yAxisID: 'yOrd' }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { display: true, position: 'top', labels: { usePointStyle: true, font: { size: 12 } } } },
                scales: {
                    x: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 }, maxTicksLimit: 15 } },
                    yRev: { position: 'left', grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 }, callback: v => 'IDR ' + (v/1000).toFixed(0) + 'k' } },
                    yOrd: { position: 'right', grid: { drawOnChartArea: false }, ticks: { font: { size: 10 }, stepSize: 1 } }
                }
            }
        });
    }
    if (id === 'panelStatistics' && !window._statPanelInited) {
        window._statPanelInited = true;
        new Chart(document.getElementById('panelStatChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: monthlyData.map(d => d.date),
                datasets: [
                    { label: 'Revenue (IDR)', data: monthlyData.map(d => d.revenue), backgroundColor: 'rgba(99,102,241,0.75)', borderRadius: 5, yAxisID: 'yRev' },
                    { label: 'Orders', data: monthlyData.map(d => d.orders), backgroundColor: 'rgba(34,197,94,0.75)', borderRadius: 5, yAxisID: 'yOrd' }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { display: true, position: 'top', labels: { usePointStyle: true, font: { size: 12 } } } },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 10 }, maxTicksLimit: 15 } },
                    yRev: { position: 'left', grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 }, callback: v => 'IDR ' + (v/1000).toFixed(0) + 'k' } },
                    yOrd: { position: 'right', grid: { drawOnChartArea: false }, ticks: { font: { size: 10 }, stepSize: 1 } }
                }
            }
        });
    }
}

function closePanel(id) {
    document.getElementById(id).classList.remove('open');
    document.body.style.overflow = '';
}

// Close all panels on Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        ['panelPayment','panelRevenue','panelRating','panelStatistics','panelWithdraw','panelAccount','panelAbout'].forEach(closePanel);
    }
});

// Filter table rows by payment status
function filterTable(tableId, status, chip) {
    // toggle active chip
    chip.closest('.panel-filter-bar').querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
    chip.classList.add('active');
    // filter rows
    document.getElementById(tableId).querySelectorAll('tbody tr').forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>

<script>
// ── CSRF token ───────────────────────────────────────────────
const csrfToken = '{{ csrf_token() }}';

// ── Toast notification helper ───────────────────────────────
function showToast(message, type = 'success') {
    const toast = document.getElementById('toastNotif');
    const msg = document.getElementById('toastMsg');
    const icon = toast.querySelector('i');

    toast.className = 'toast-notification ' + type;
    msg.textContent = message;
    icon.className = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';

    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 4000);
}

// ── Withdraw: load data when panel opens ─────────────────────
const _origOpenPanel = openPanel;
openPanel = function(id) {
    _origOpenPanel(id);
    if (id === 'panelWithdraw' && !window._wdLoaded) {
        loadWithdrawData();
    }
};

function loadWithdrawData() {
    const fmt = n => 'IDR ' + parseInt(n).toLocaleString('id-ID');
    fetch('/dashboard/withdrawals')
        .then(r => r.json())
        .then(data => {
            window._wdLoaded = true;
            document.getElementById('wd-total-revenue').textContent = fmt(data.totalRevenue);
            document.getElementById('wd-total-withdrawn').textContent = fmt(data.totalWithdrawn);
            document.getElementById('wd-total-pending').textContent = fmt(data.totalPending);
            document.getElementById('wd-available').textContent = fmt(data.available);

            const tbody = document.querySelector('#wdHistoryTable tbody');
            if (data.withdrawals.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="panel-empty"><i class="fas fa-inbox"></i>Belum ada riwayat penarikan</td></tr>';
            } else {
                tbody.innerHTML = data.withdrawals.map((w, i) => `
                    <tr>
                        <td style="color:var(--muted);font-weight:600;">${i + 1}</td>
                        <td style="font-weight:700;">IDR ${parseInt(w.amount).toLocaleString('id-ID')}</td>
                        <td>${w.method === 'bank_transfer' ? 'Bank Transfer' : 'E-Wallet'}</td>
                        <td>${w.bank_name} - ${w.account_number}<br><span style="font-size:11px;color:var(--muted);">${w.account_holder}</span></td>
                        <td><span class="wd-status ${w.status}">${w.status.charAt(0).toUpperCase() + w.status.slice(1)}</span></td>
                        <td>${w.created_at}</td>
                    </tr>
                `).join('');
            }
        })
        .catch(() => {
            document.querySelector('#wdHistoryTable tbody').innerHTML =
                '<tr><td colspan="6" class="panel-empty"><i class="fas fa-exclamation-triangle"></i>Gagal memuat data</td></tr>';
        });
}

// ── Withdraw: submit form ───────────────────────────────────
function submitWithdraw(e) {
    e.preventDefault();
    const btn = document.getElementById('wdSubmitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

    const body = {
        amount: document.getElementById('wdAmount').value,
        method: document.getElementById('wdMethod').value,
        bank_name: document.getElementById('wdBankName').value,
        account_number: document.getElementById('wdAccNumber').value,
        account_holder: document.getElementById('wdAccHolder').value,
        notes: document.getElementById('wdNotes').value,
    };

    fetch('/dashboard/withdraw', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
        body: JSON.stringify(body),
    })
    .then(async r => {
        const data = await r.json();
        if (!r.ok) throw data;
        return data;
    })
    .then(data => {
        showToast(data.message, 'success');
        document.getElementById('withdrawForm').reset();
        window._wdLoaded = false;
        loadWithdrawData();
    })
    .catch(err => {
        const msg = err.message || err.errors ? Object.values(err.errors || {}).flat().join(', ') : 'Gagal mengirim request';
        showToast(err.message || msg, 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Request Withdraw';
    });
}

// ── Change Password: submit form ────────────────────────────
function submitChangePassword(e) {
    e.preventDefault();
    const newPw = document.getElementById('cpNewPassword').value;
    const confirmPw = document.getElementById('cpConfirmPassword').value;

    if (newPw !== confirmPw) {
        showToast('Password baru dan konfirmasi tidak cocok!', 'error');
        return;
    }

    const btn = document.getElementById('cpSubmitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    const oldPwEl = document.getElementById('cpOldPassword');
    const isSettingPassword = !oldPwEl;
    const endpoint = isSettingPassword ? '/dashboard/set-password' : '/dashboard/change-password';
    
    const bodyData = {
        new_password: newPw,
        new_password_confirmation: confirmPw,
    };
    if (oldPwEl) {
        bodyData.old_password = oldPwEl.value;
    }

    fetch(endpoint, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
        body: JSON.stringify(bodyData),
    })
    .then(async r => {
        const data = await r.json();
        if (!r.ok) throw data;
        return data;
    })
    .then(data => {
        showToast(data.message, 'success');
        document.getElementById('changePasswordForm').reset();
        setTimeout(() => location.reload(), 1500);
    })
    .catch(err => {
        const msg = err.message || (err.errors ? Object.values(err.errors).flat().join(', ') : 'Gagal memproses password');
        showToast(msg, 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> ' + (isSettingPassword ? 'Set Password' : 'Simpan Password');
    });
}
</script>

</body>
</html>
