<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMASTER — Sistem Informasi Manajemen Surat Terpadu</title>
    <meta name="description" content="SIMASTER mencatat, mendisposisikan, menyetujui, dan mengarsipkan surat SMK Negeri 7 dalam satu alur resmi.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        :root{
            --bs-primary:#178754;
            --primary-dark:#0F5C39;
            --primary-light:#E6F5EC;
            --ink:#1C1D22;
            --ink-muted:#75778A;
            --surface:#F6F8F5;
            --paper:#FFFFFF;
            --border:#E7ECE8;
            --stamp:#A63446;
            --stamp-light:#FBEDEE;
            --gold:#B8862E;
            --gold-light:#F8F0E1;
            --mint:#7FE3B4;
            --ease:cubic-bezier(.16,.84,.44,1);
        }
        *{box-sizing:border-box}
        html{scroll-behavior:smooth}
        body{
            font-family:'Poppins',system-ui,-apple-system,sans-serif;
            color:var(--ink);
            background:var(--surface);
            font-size:.95rem;
            overflow-x:hidden;
            position:relative;
        }
        /* subtle film-grain, keeps flat colour fields from feeling cheap/flat */
        body::after{
            content:'';position:fixed;inset:0;pointer-events:none;z-index:9999;opacity:.035;mix-blend-mode:multiply;
            background-image:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='140' height='140'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2' stitchTiles='stitch'/></filter><rect width='100%25' height='100%25' filter='url(%23n)'/></svg>");
        }
        .display{font-family:'Poppins',sans-serif;font-weight:700;letter-spacing:-.02em}
        .mono{font-family:'IBM Plex Mono',monospace;letter-spacing:.02em}
        a{text-decoration:none}
        :focus-visible{outline:2px solid var(--bs-primary);outline-offset:3px;border-radius:4px}
        .btn-primary{background:var(--bs-primary);border-color:var(--bs-primary);transition:transform .18s var(--ease),box-shadow .18s var(--ease),background .18s}
        .btn-primary:hover{background:var(--primary-dark);border-color:var(--primary-dark);transform:translateY(-2px);box-shadow:0 10px 22px -10px rgba(15,92,57,.55)}
        .btn-outline-primary{color:var(--bs-primary);border-color:var(--bs-primary);transition:transform .18s var(--ease),background .18s}
        .btn-outline-primary:hover{background:var(--primary-light);color:var(--primary-dark);border-color:var(--bs-primary);transform:translateY(-2px)}

        /* ===== Reveal-on-scroll ===== */
        .reveal{opacity:0;transform:translateY(22px);transition:opacity .65s var(--ease),transform .65s var(--ease)}
        .reveal.is-visible{opacity:1;transform:translateY(0)}
        .reveal-delay-1{transition-delay:.08s}
        .reveal-delay-2{transition-delay:.16s}
        .reveal-delay-3{transition-delay:.24s}
        .reveal-delay-4{transition-delay:.32s}
        .reveal-delay-5{transition-delay:.4s}
        @media (prefers-reduced-motion: reduce){
            .reveal{opacity:1!important;transform:none!important;transition:none!important}
            *{animation-duration:.001s!important;animation-iteration-count:1!important}
        }

        /* ===== Brand / logo badge (shared nav + footer) ===== */
        .brand-badge{
            width:42px;height:42px;border-radius:12px;background:#fff;flex-shrink:0;
            display:flex;align-items:center;justify-content:center;overflow:hidden;padding:5px;
            border:1px solid var(--border);box-shadow:0 3px 10px -4px rgba(15,92,57,.25);
        }
        .brand-badge img{width:100%;height:100%;object-fit:contain}
        .brand-badge span{color:var(--bs-primary);font-weight:800;font-size:.8rem}

        /* ===== Navbar ===== */
        .nav-landing{
            position:sticky;top:0;z-index:1040;background:rgba(246,248,245,.86);
            backdrop-filter:blur(10px);border-bottom:1px solid var(--border);
            transition:box-shadow .25s ease;
        }
        .nav-landing.is-scrolled{box-shadow:0 6px 22px -14px rgba(30,31,38,.22)}
        .nav-landing .brand{display:flex;align-items:center;gap:.65rem}
        .nav-landing .brand-title{font-weight:800;font-size:.92rem;line-height:1.1;margin:0}
        .nav-landing .brand-sub{font-size:.68rem;color:var(--ink-muted);margin:0}
        .nav-link-top{
            color:#4B4D59;font-weight:600;font-size:.85rem;padding:.5rem .2rem;position:relative;
        }
        .nav-link-top::after{
            content:'';position:absolute;left:0;bottom:.1rem;width:0;height:2px;background:var(--bs-primary);
            transition:width .22s var(--ease);
        }
        .nav-link-top:hover{color:var(--ink)}
        .nav-link-top:hover::after{width:100%}
        .navbar-toggler{border:1px solid var(--border);padding:.4rem .55rem}
        .navbar-toggler:focus{box-shadow:0 0 0 3px var(--primary-light)}

        /* ===== Hero ===== */
        .hero{position:relative;padding:5rem 0 6rem;overflow:hidden}
        .hero::before{
            content:'';position:absolute;top:-200px;right:-160px;width:560px;height:560px;border-radius:50%;
            background:radial-gradient(circle,rgba(23,135,84,.12),transparent 70%);pointer-events:none;
        }
        .hero::after{
            content:'';position:absolute;bottom:-160px;left:-140px;width:420px;height:420px;border-radius:50%;
            background:radial-gradient(circle,rgba(166,52,70,.07),transparent 72%);pointer-events:none;
        }
        .hero-eyebrow{
            display:inline-flex;align-items:center;gap:.5rem;
            background:var(--primary-light);color:var(--primary-dark);border:1px solid rgba(23,135,84,.14);
            padding:.35rem .75rem;border-radius:99px;font-size:.72rem;font-weight:600;
            animation:eyebrow-in .5s var(--ease) both;
        }
        @keyframes eyebrow-in{from{opacity:0;transform:translateY(-6px)}to{opacity:1;transform:translateY(0)}}
        .hero-eyebrow .mono{font-size:.7rem}
        .hero h1{font-size:2.75rem;line-height:1.12;margin:1.1rem 0 1.1rem;
            animation:hero-in .6s var(--ease) .08s both;}
        .hero p.lead-text{color:var(--ink-muted);font-size:1.02rem;max-width:34rem;
            animation:hero-in .6s var(--ease) .16s both;}
        .hero .hero-cta{animation:hero-in .6s var(--ease) .24s both;}
        @keyframes hero-in{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
        @media (max-width:767.98px){ .hero h1{font-size:2.1rem} }

        .hero-trust{display:flex;flex-wrap:wrap;gap:1.6rem;margin-top:2.2rem;padding-top:1.6rem;border-top:1px dashed var(--border)}
        .hero-trust .item{display:flex;align-items:center;gap:.5rem;font-size:.78rem;color:var(--ink-muted);font-weight:600}
        .hero-trust .item i{color:var(--bs-primary);font-size:1rem}

        /* ---- Document stack illustration ---- */
        .doc-stack{position:relative;height:420px}
        .doc-card{
            position:absolute;background:var(--paper);border:1px solid var(--border);
            border-radius:14px;box-shadow:0 18px 40px -12px rgba(23,135,84,.18);
            padding:1.4rem 1.5rem;
        }
        .doc-card .line{height:8px;border-radius:99px;background:#EFF1EE;margin-bottom:.55rem}
        .doc-card-1{width:78%;top:8%;left:2%;transform:rotate(-4deg);z-index:1;
            animation:card-float-1 7s ease-in-out infinite;}
        .doc-card-2{width:82%;top:22%;left:14%;transform:rotate(3deg);z-index:2;padding-top:1.6rem;
            animation:card-float-2 8s ease-in-out infinite;}
        @keyframes card-float-1{0%,100%{transform:rotate(-4deg) translateY(0)}50%{transform:rotate(-5deg) translateY(-8px)}}
        @keyframes card-float-2{0%,100%{transform:rotate(3deg) translateY(0)}50%{transform:rotate(4deg) translateY(-6px)}}
        .doc-card-2 .doc-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem}
        .doc-card-2 .doc-head .mono{font-size:.65rem;color:var(--ink-muted)}
        .doc-card-2 .badge-terbit{background:var(--primary-light);color:var(--primary-dark);font-size:.62rem;font-weight:700;padding:.25rem .55rem;border-radius:99px}

        .stamp{
            position:absolute;bottom:6%;right:6%;width:150px;height:150px;z-index:3;
            transform:rotate(-14deg);filter:drop-shadow(0 8px 18px rgba(166,52,70,.28));
            animation:stamp-in .6s cubic-bezier(.2,.9,.3,1.3) .3s both;
        }
        @keyframes stamp-in{
            0%{transform:rotate(-14deg) scale(1.6);opacity:0}
            100%{transform:rotate(-14deg) scale(1);opacity:1}
        }
        @media (prefers-reduced-motion: reduce){ .stamp,.doc-card-1,.doc-card-2{animation:none} }

        /* ===== Unit-kerja marquee (replaces the old stat counters) ===== */
        .unit-marquee{background:var(--primary-dark);position:relative;padding:1.15rem 0;overflow:hidden}
        .unit-marquee .container{display:flex;align-items:center;gap:1.4rem}
        .unit-marquee-label{
            flex-shrink:0;display:flex;align-items:center;gap:.5rem;color:#fff;font-weight:700;
            font-size:.72rem;text-transform:uppercase;letter-spacing:.07em;white-space:nowrap;
            padding-right:1.4rem;border-right:1px solid rgba(255,255,255,.16);
        }
        .unit-marquee-label i{color:var(--mint)}
        .unit-marquee-viewport{
            flex:1;overflow:hidden;
            mask-image:linear-gradient(90deg,transparent,#000 6%,#000 94%,transparent);
            -webkit-mask-image:linear-gradient(90deg,transparent,#000 6%,#000 94%,transparent);
        }
        .unit-track{display:flex;align-items:center;gap:2.1rem;width:max-content;animation:unit-scroll 34s linear infinite}
        .unit-marquee:hover .unit-track{animation-play-state:paused}
        @keyframes unit-scroll{from{transform:translateX(0)}to{transform:translateX(-50%)}}
        .unit-chip{display:inline-flex;align-items:center;gap:.5rem;color:#fff;font-weight:600;font-size:.82rem;white-space:nowrap;opacity:.92}
        .unit-chip i{color:var(--mint);font-size:.9rem}
        .unit-dot{color:rgba(255,255,255,.28);font-size:.7rem}
        @media (prefers-reduced-motion: reduce){ .unit-track{animation:none;flex-wrap:wrap} .unit-marquee-viewport{overflow:visible;mask-image:none;-webkit-mask-image:none} }
        @media (max-width:767.98px){ .unit-marquee-label{display:none} }

        /* ===== Section shell ===== */
        .section{padding:4.5rem 0}
        .section-eyebrow{
            font-size:.72rem;text-transform:uppercase;letter-spacing:.09em;
            color:var(--bs-primary);font-weight:700;margin-bottom:.5rem;
            display:inline-flex;align-items:center;gap:.4rem;
        }
        .section-eyebrow::before{content:'';width:16px;height:2px;background:var(--bs-primary);display:inline-block}
        .section-title{font-size:1.9rem;margin-bottom:.6rem}
        .section-desc{color:var(--ink-muted);max-width:38rem}

        /* ===== Feature cards (bento: two lead cards + four support cards) ===== */
        .feat-card{
            background:var(--paper);border:1px solid var(--border);border-radius:16px;
            padding:1.6rem 1.5rem;height:100%;position:relative;overflow:hidden;
            transition:transform .22s var(--ease),box-shadow .22s var(--ease),border-color .22s;
        }
        .feat-card::before{
            content:'';position:absolute;top:0;left:0;right:0;height:3px;background:var(--bs-primary);
            transform:scaleX(0);transform-origin:left;transition:transform .28s var(--ease);
        }
        .feat-card:hover{transform:translateY(-5px);box-shadow:0 18px 32px -18px rgba(30,31,38,.22);border-color:transparent}
        .feat-card:hover::before{transform:scaleX(1)}
        .feat-icon{
            width:44px;height:44px;border-radius:11px;background:var(--primary-light);
            color:var(--bs-primary);display:flex;align-items:center;justify-content:center;
            font-size:1.2rem;margin-bottom:1rem;transition:transform .3s var(--ease),background .22s,color .22s;
        }
        .feat-card:hover .feat-icon{background:var(--bs-primary);color:#fff;transform:rotate(-8deg) scale(1.06)}
        .feat-card h3{font-size:1rem;font-weight:700;margin-bottom:.4rem}
        .feat-card p{font-size:.85rem;color:var(--ink-muted);margin:0}
        .feat-ref{position:absolute;top:1.1rem;right:1.3rem;font-size:.65rem;color:#C7CAD1}

        .feat-lead{padding:1.9rem 1.9rem 1.7rem}
        .feat-lead .feat-icon{width:50px;height:50px;font-size:1.35rem}
        .feat-lead h3{font-size:1.12rem}
        .feat-lead p{font-size:.88rem;max-width:26rem}
        .feat-flow{display:flex;align-items:center;flex-wrap:wrap;gap:.5rem;margin-top:1.25rem}
        .feat-flow .fpill{
            display:inline-flex;align-items:center;gap:.4rem;background:var(--surface);border:1px solid var(--border);
            border-radius:99px;padding:.32rem .7rem;font-size:.72rem;font-weight:600;color:var(--ink);
        }
        .feat-flow .fpill.on{background:var(--primary-light);border-color:transparent;color:var(--primary-dark)}
        .feat-flow i{color:#C7CAD1;font-size:.75rem}

        /* ===== Roles (untuk siapa) — ID badge style ===== */
        .role-card{
            background:var(--paper);border:1px solid var(--border);border-radius:16px;
            padding:1.6rem 1.4rem 1.4rem;height:100%;text-align:center;position:relative;
            transition:transform .22s var(--ease),box-shadow .22s var(--ease);
        }
        .role-card:hover{transform:translateY(-4px);box-shadow:0 16px 30px -18px rgba(30,31,38,.2)}
        .role-card .role-ring{
            width:64px;height:64px;border-radius:50%;margin:0 auto .9rem;display:flex;align-items:center;justify-content:center;
            background:conic-gradient(var(--bs-primary) 0deg,var(--primary-light) 0deg);
            position:relative;
        }
        .role-card .role-ring-inner{
            width:52px;height:52px;border-radius:50%;background:var(--paper);display:flex;align-items:center;justify-content:center;
            color:var(--bs-primary);font-size:1.3rem;
        }
        .role-card h4{font-size:.92rem;font-weight:700;margin:0 0 .3rem}
        .role-card p{font-size:.78rem;color:var(--ink-muted);margin:0}
        .role-card .role-tag{
            display:inline-block;margin-top:.7rem;font-size:.64rem;font-weight:700;letter-spacing:.05em;
            text-transform:uppercase;color:var(--primary-dark);background:var(--primary-light);
            padding:.22rem .55rem;border-radius:99px;
        }

        /* ===== Workflow tracks ===== */
        .track-card{background:var(--paper);border:1px solid var(--border);border-radius:18px;padding:2rem;height:100%}
        .track-head{display:flex;align-items:center;gap:.7rem;margin-bottom:1.6rem}
        .track-head .track-icon{
            width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;
            color:#fff;font-size:1rem;flex-shrink:0;
        }
        .track-steps{position:relative;padding-left:2.6rem}
        .track-steps::before{
            content:'';position:absolute;left:14px;top:6px;bottom:6px;width:2px;
            background:var(--border);
        }
        .track-step{position:relative;padding-bottom:1.6rem}
        .track-step:last-child{padding-bottom:0}
        .track-step .num{
            position:absolute;left:-2.6rem;top:0;width:30px;height:30px;border-radius:50%;
            background:var(--primary-light);color:var(--primary-dark);font-weight:800;font-size:.78rem;
            display:flex;align-items:center;justify-content:center;transition:background .25s,color .25s,transform .25s;
        }
        .track-step:hover .num{background:var(--bs-primary);color:#fff;transform:scale(1.08)}
        .track-step h4{font-size:.9rem;font-weight:700;margin:0 0 .2rem}
        .track-step p{font-size:.8rem;color:var(--ink-muted);margin:0}

        /* ===== Testimonial — memo style ===== */
        .memo-card{
            background:var(--paper);border:1px solid var(--border);border-radius:18px;
            padding:2.4rem 2.4rem;position:relative;max-width:44rem;margin:0 auto;
        }
        .memo-card::before{
            content:'\201C';position:absolute;top:.4rem;left:1.6rem;font-family:'Poppins',sans-serif;font-weight:800;
            font-size:4.6rem;color:var(--primary-light);line-height:1;
        }
        .memo-card .memo-quote{font-family:'Poppins',sans-serif;font-weight:500;font-size:1.28rem;line-height:1.55;color:var(--ink);position:relative;z-index:1}
        .memo-sign{display:flex;align-items:center;gap:.8rem;margin-top:1.6rem}
        .memo-sign .memo-avatar{
            width:44px;height:44px;border-radius:50%;background:var(--primary-light);color:var(--primary-dark);
            display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.85rem;flex-shrink:0;
        }
        .memo-sign .memo-name{font-weight:700;font-size:.86rem;margin:0}
        .memo-sign .memo-role{font-size:.75rem;color:var(--ink-muted);margin:0}
        .memo-stamp-mini{
            position:absolute;bottom:1.6rem;right:1.8rem;width:64px;height:64px;opacity:.9;transform:rotate(-10deg);
        }

        /* ===== FAQ accordion ===== */
        .faq-accordion .accordion-item{
            border:1px solid var(--border);border-radius:14px!important;margin-bottom:.85rem;overflow:hidden;
        }
        .faq-accordion .accordion-button{
            font-weight:700;font-size:.9rem;color:var(--ink);padding:1.15rem 1.35rem;background:var(--paper);
        }
        .faq-accordion .accordion-button:not(.collapsed){color:var(--primary-dark);background:var(--primary-light);box-shadow:none}
        .faq-accordion .accordion-button:focus{box-shadow:0 0 0 3px var(--primary-light)}
        .faq-accordion .accordion-button::after{
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23178754'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
        }
        .faq-accordion .accordion-body{font-size:.85rem;color:var(--ink-muted);padding:0 1.35rem 1.25rem;line-height:1.65}

        /* ===== CTA banner ===== */
        .cta-banner{
            border-radius:22px;background:linear-gradient(135deg,var(--primary-dark),var(--bs-primary) 65%,#22A67C);
            color:#fff;padding:3.2rem 2.5rem;position:relative;overflow:hidden;
        }
        .cta-banner::after{
            content:'';position:absolute;right:-60px;top:-60px;width:260px;height:260px;
            border:2px dashed rgba(255,255,255,.18);border-radius:50%;
        }
        .cta-banner .cta-envelope{
            position:absolute;font-size:1.4rem;color:rgba(255,255,255,.22);
            animation:cta-drift 9s ease-in-out infinite;
        }
        .cta-banner .e1{top:14%;left:8%;animation-delay:0s}
        .cta-banner .e2{bottom:18%;left:22%;animation-delay:1.4s;font-size:1rem}
        .cta-banner .e3{top:22%;left:38%;animation-delay:2.6s;font-size:.85rem}
        @keyframes cta-drift{0%,100%{transform:translateY(0) rotate(0deg)}50%{transform:translateY(-14px) rotate(6deg)}}
        .cta-banner h2{font-size:1.7rem;margin-bottom:.5rem}
        .cta-banner p{color:rgba(255,255,255,.85);max-width:30rem}
        .cta-banner .btn-light{color:var(--primary-dark);font-weight:700;transition:transform .18s var(--ease)}
        .cta-banner .btn-light:hover{transform:translateY(-2px)}

        /* ===== Footer ===== */
        .footer-landing{background:#12251C;color:rgba(255,255,255,.72);padding-top:3.2rem;font-size:.85rem}
        .footer-landing .f-brand{display:flex;align-items:center;gap:.65rem;margin-bottom:.9rem}
        .footer-landing .f-brand .brand-badge{border-color:rgba(255,255,255,.12)}
        .footer-landing .f-brand p{margin:0;color:#fff;font-weight:800;font-size:.92rem}
        .footer-landing p.f-desc{color:rgba(255,255,255,.55);max-width:22rem;font-size:.82rem;line-height:1.6}
        .footer-landing h6{color:#fff;font-size:.78rem;text-transform:uppercase;letter-spacing:.08em;font-weight:700;margin-bottom:1.1rem}
        .footer-landing a.f-link{color:rgba(255,255,255,.62);display:block;margin-bottom:.6rem;font-size:.83rem;transition:color .18s,transform .18s}
        .footer-landing a.f-link:hover{color:#fff;transform:translateX(3px)}
        .footer-landing .f-contact{display:flex;gap:.6rem;margin-bottom:.7rem;font-size:.82rem;color:rgba(255,255,255,.62)}
        .footer-landing .f-contact i{color:var(--bs-primary);margin-top:.15rem}
        .footer-bottom{border-top:1px solid rgba(255,255,255,.1);margin-top:2.6rem;padding:1.3rem 0;font-size:.78rem;color:rgba(255,255,255,.5)}
        .footer-social{
            width:34px;height:34px;border-radius:50%;border:1px solid rgba(255,255,255,.18);
            display:inline-flex;align-items:center;justify-content:center;color:rgba(255,255,255,.75);
            transition:background .2s,border-color .2s,transform .2s;
        }
        .footer-social:hover{background:var(--bs-primary);border-color:var(--bs-primary);color:#fff;transform:translateY(-2px)}

        /* ===== Back to top ===== */
        .back-to-top{
            position:fixed;right:1.4rem;bottom:1.4rem;width:46px;height:46px;border-radius:50%;
            background:var(--bs-primary);color:#fff;display:flex;align-items:center;justify-content:center;
            box-shadow:0 10px 22px -10px rgba(15,92,57,.6);opacity:0;visibility:hidden;
            transform:translateY(10px);transition:opacity .25s,visibility .25s,transform .25s,background .2s;
            z-index:1050;border:none;
        }
        .back-to-top.show{opacity:1;visibility:visible;transform:translateY(0)}
        .back-to-top:hover{background:var(--primary-dark)}
    </style>
</head>
<body>

@php
    $logoCandidates = ['images/logo-smkn7.jpeg.jpg', 'images/logo-smkn7.jpeg.jpeg', 'images/logo-smkn7.webp'];
    $logoPath = collect($logoCandidates)->first(fn($path) => file_exists(public_path($path)));
@endphp

<nav class="nav-landing" id="mainNav">
    <div class="container d-flex align-items-center justify-content-between" style="height:68px">
        <div class="brand">
            <span class="brand-badge">
                @if($logoPath)
                    <img src="{{ asset($logoPath) }}" alt="Logo SMK Negeri 7">
                @else
                    <span>S7</span>
                @endif
            </span>
            <div>
                <p class="brand-title">SIMASTER</p>
                <p class="brand-sub">SMK Negeri 7</p>
            </div>
        </div>

        <button class="navbar-toggler d-lg-none rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Buka menu">
            <i class="bi bi-list fs-4"></i>
        </button>

        <div class="d-none d-lg-flex align-items-center gap-4">
            <a href="#fitur" class="nav-link-top">Fitur</a>
            <a href="#peran" class="nav-link-top">Untuk Siapa</a>
            <a href="#alur" class="nav-link-top">Alur Surat</a>
            <a href="#faq" class="nav-link-top">FAQ</a>
        </div>
        {{-- <a href="{{ route('login') }}" class="btn btn-primary btn-sm px-3 text-white d-none d-lg-inline-flex">Masuk</a> --}}
    </div>

    <div class="collapse d-lg-none border-top" id="navMenu">
        <div class="container py-3 d-flex flex-column gap-1">
            <a href="#fitur" class="nav-link-top py-2">Fitur</a>
            <a href="#peran" class="nav-link-top py-2">Untuk Siapa</a>
            <a href="#alur" class="nav-link-top py-2">Alur Surat</a>
            <a href="#faq" class="nav-link-top py-2">FAQ</a>
            <a href="{{ route('login') }}" class="btn btn-primary text-white mt-2">Masuk ke Sistem</a>
        </div>
    </div>
</nav>

<header class="hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="hero-eyebrow">
                    <i class="bi bi-patch-check-fill"></i>
                    <span class="mono">No. 421/SI-TU/SMKN7/2026</span>
                </span>
                <h1 class="display">Satu alur resmi untuk setiap surat yang keluar &amp; masuk.</h1>
                <p class="lead-text">SIMASTER mencatat, mendisposisikan, menyetujui, dan mengarsipkan surat sekolah dalam satu sistem — tanpa map bolak-balik, tanpa status yang hilang.</p>
                <div class="d-flex flex-wrap gap-3 mt-4 hero-cta">
                    <a href="{{ route('login') }}" class="btn btn-primary text-white px-4 py-2">
                        Masuk ke Sistem <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                    <a href="#alur" class="btn btn-outline-primary px-4 py-2">Lihat Alur Kerja</a>
                </div>

                <div class="hero-trust">
                    <span class="item"><i class="bi bi-shield-check"></i> Setiap tindakan tercatat di log</span>
                    <span class="item"><i class="bi bi-diagram-3"></i> Terhubung ke semua unit kerja</span>
                    <span class="item"><i class="bi bi-file-earmark-pdf"></i> Cetak PDF siap tanda tangan</span>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="doc-stack">
                    <div class="doc-card doc-card-1">
                        <div class="line" style="width:40%"></div>
                        <div class="line" style="width:85%"></div>
                        <div class="line" style="width:70%"></div>
                        <div class="line" style="width:60%"></div>
                    </div>
                    <div class="doc-card doc-card-2">
                        <div class="doc-head">
                            <span class="mono">SK/074/DISP/2026</span>
                            <span class="badge-terbit">Disposisi Selesai</span>
                        </div>
                        <div class="line" style="width:90%"></div>
                        <div class="line" style="width:75%"></div>
                        <div class="line" style="width:50%"></div>
                    </div>
                    <svg class="stamp" viewBox="0 0 160 160" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <path id="stampCircle" d="M 80,80 m -62,0 a 62,62 0 1,1 124,0 a 62,62 0 1,1 -124,0"/>
                        </defs>
                        <circle cx="80" cy="80" r="70" fill="none" stroke="var(--stamp)" stroke-width="2"/>
                        <circle cx="80" cy="80" r="60" fill="none" stroke="var(--stamp)" stroke-width="2" stroke-dasharray="3 4"/>
                        <text font-family="IBM Plex Mono, monospace" font-size="10.5" font-weight="600" fill="var(--stamp)" letter-spacing="2">
                            <textPath href="#stampCircle" startOffset="2%">SIMASTER • SMK NEGERI 7 • TERVERIFIKASI •</textPath>
                        </text>
                        <text x="80" y="76" text-anchor="middle" font-family="Poppins, sans-serif" font-size="16" font-weight="800" fill="var(--stamp)">DISETUJUI</text>
                        <text x="80" y="94" text-anchor="middle" font-family="IBM Plex Mono, monospace" font-size="8.5" fill="var(--stamp)">Kepala Sekolah</text>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</header>

<section class="unit-marquee" aria-label="Unit kerja yang terhubung dengan SIMASTER">
    <div class="container">
        <div class="unit-marquee-label"><i class="bi bi-diagram-3-fill"></i> Terhubung dengan</div>
        <div class="unit-marquee-viewport">
            <div class="unit-track">
                @php
                    $units = [
                        ['bi-person-badge','Kepala Sekolah'],
                        ['bi-folder2-open','Tata Usaha'],
                        ['bi-journal-bookmark','Wakasek Kurikulum'],
                        ['bi-people','Wakasek Kesiswaan'],
                        ['bi-tools','Wakasek Sarpras'],
                        ['bi-briefcase','Hubungan Industri'],
                        ['bi-chat-heart','Bimbingan Konseling'],
                        ['bi-book','Perpustakaan'],
                        ['bi-person-workspace','Wali Kelas'],
                        ['bi-door-open','Guru Piket'],
                    ];
                @endphp
                @for($r = 0; $r < 2; $r++)
                    @foreach($units as $u)
                        <span class="unit-chip"><i class="bi {{ $u[0] }}"></i>{{ $u[1] }}</span>
                        <span class="unit-dot">●</span>
                    @endforeach
                @endfor
            </div>
        </div>
    </div>
</section>

<section class="section" id="fitur">
    <div class="container">
        <p class="section-eyebrow reveal">Fitur Utama</p>
        <h2 class="section-title display reveal reveal-delay-1">Semua urusan persuratan, di satu tempat.</h2>
        <p class="section-desc mb-5 reveal reveal-delay-2">Dari surat masuk sampai arsip, setiap langkah tercatat dan bisa ditelusuri kembali.</p>

        <div class="row g-3">
            <div class="col-lg-6 reveal">
                <div class="feat-card feat-lead">
                    <span class="feat-ref mono">01</span>
                    <div class="feat-icon"><i class="bi bi-envelope-arrow-down"></i></div>
                    <h3>Surat Masuk</h3>
                    <p>Catat surat masuk dan teruskan langsung ke unit kerja yang berwenang menindaklanjuti.</p>
                    <div class="feat-flow">
                        <span class="fpill on"><i class="bi bi-circle-fill" style="font-size:.4rem"></i>Diterima</span>
                        <i class="bi bi-arrow-right"></i>
                        <span class="fpill">Didisposisikan</span>
                        <i class="bi bi-arrow-right"></i>
                        <span class="fpill">Ditindaklanjuti</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 reveal reveal-delay-1">
                <div class="feat-card feat-lead">
                    <span class="feat-ref mono">02</span>
                    <div class="feat-icon"><i class="bi bi-send"></i></div>
                    <h3>Surat Keluar</h3>
                    <p>Susun surat dari template resmi sekolah, ajukan, dan cetak dalam format PDF siap tanda tangan.</p>
                    <div class="feat-flow">
                        <span class="fpill">Draf</span>
                        <i class="bi bi-arrow-right"></i>
                        <span class="fpill">Disetujui</span>
                        <i class="bi bi-arrow-right"></i>
                        <span class="fpill on"><i class="bi bi-check-circle-fill" style="font-size:.65rem"></i>Terbit PDF</span>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3 reveal">
                <div class="feat-card">
                    <span class="feat-ref mono">03</span>
                    <div class="feat-icon"><i class="bi bi-patch-check"></i></div>
                    <h3>Approval Berjenjang</h3>
                    <p>Kepala sekolah menyetujui atau menolak surat keluar langsung dari sistem, lengkap dengan catatan.</p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 reveal reveal-delay-1">
                <div class="feat-card">
                    <span class="feat-ref mono">04</span>
                    <div class="feat-icon"><i class="bi bi-diagram-3"></i></div>
                    <h3>Disposisi</h3>
                    <p>Delegasikan tindak lanjut surat masuk ke staf terkait, lalu pantau sampai tuntas.</p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 reveal reveal-delay-2">
                <div class="feat-card">
                    <span class="feat-ref mono">05</span>
                    <div class="feat-icon"><i class="bi bi-archive"></i></div>
                    <h3>Arsip Digital</h3>
                    <p>Setiap surat yang selesai diproses tersimpan rapi dan mudah dicari berdasarkan klasifikasi.</p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 reveal reveal-delay-3">
                <div class="feat-card">
                    <span class="feat-ref mono">06</span>
                    <div class="feat-icon"><i class="bi bi-clock-history"></i></div>
                    <h3>Log Aktivitas</h3>
                    <p>Riwayat setiap tindakan pengguna tercatat otomatis untuk keperluan audit internal.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" id="peran" style="background:var(--paper);border-top:1px solid var(--border);border-bottom:1px solid var(--border)">
    <div class="container">
        <p class="section-eyebrow reveal">Hak Akses</p>
        <h2 class="section-title display reveal reveal-delay-1">Satu sistem, peran yang jelas untuk tiap orang.</h2>
        <p class="section-desc mb-5 reveal reveal-delay-2">Setiap pengguna hanya melihat menu dan surat yang relevan dengan tugasnya.</p>

        <div class="row g-3">
            <div class="col-6 col-lg-3 reveal">
                <div class="role-card">
                    <div class="role-ring"><div class="role-ring-inner"><i class="bi bi-person-badge"></i></div></div>
                    <h4>Admin TU</h4>
                    <p>Mencatat surat masuk &amp; keluar, mengelola arsip dan master data.</p>
                    <span class="role-tag">Akses Penuh</span>
                </div>
            </div>
            <div class="col-6 col-lg-3 reveal reveal-delay-1">
                <div class="role-card">
                    <div class="role-ring"><div class="role-ring-inner"><i class="bi bi-award"></i></div></div>
                    <h4>Kepala Sekolah</h4>
                    <p>Meninjau dan menyetujui surat keluar sebelum diterbitkan.</p>
                    <span class="role-tag">Approval</span>
                </div>
            </div>
            <div class="col-6 col-lg-3 reveal reveal-delay-2">
                <div class="role-card">
                    <div class="role-ring"><div class="role-ring-inner"><i class="bi bi-person-workspace"></i></div></div>
                    <h4>Staf &amp; Guru</h4>
                    <p>Menindaklanjuti surat yang didisposisikan ke unit kerjanya.</p>
                    <span class="role-tag">Disposisi</span>
                </div>
            </div>
            <div class="col-6 col-lg-3 reveal reveal-delay-3">
                <div class="role-card">
                    <div class="role-ring"><div class="role-ring-inner"><i class="bi bi-people"></i></div></div>
                    <h4>Wali Kelas</h4>
                    <p>Memantau status surat terkait siswa dan kelas binaannya.</p>
                    <span class="role-tag">Pemantauan</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" id="alur">
    <div class="container">
        <p class="section-eyebrow reveal">Alur Kerja</p>
        <h2 class="section-title display reveal reveal-delay-1">Dua alur, satu standar yang sama.</h2>
        <p class="section-desc mb-5 reveal reveal-delay-2">Baik surat yang datang maupun yang dikirim, prosesnya jelas dari awal sampai diarsipkan.</p>

        <div class="row g-4">
            <div class="col-lg-6 reveal">
                <div class="track-card">
                    <div class="track-head">
                        <span class="track-icon" style="background:var(--bs-primary)"><i class="bi bi-envelope-arrow-down"></i></span>
                        <div>
                            <h3 class="mb-0" style="font-size:1.05rem;font-weight:700">Surat Masuk</h3>
                            <p class="mb-0" style="font-size:.78rem;color:var(--ink-muted)">Dari diterima sampai selesai ditindaklanjuti</p>
                        </div>
                    </div>
                    <div class="track-steps">
                        <div class="track-step">
                            <span class="num">1</span>
                            <h4>Terima &amp; Catat</h4>
                            <p>Admin TU mencatat surat masuk beserta kategori dan pengirimnya.</p>
                        </div>
                        <div class="track-step">
                            <span class="num">2</span>
                            <h4>Disposisi</h4>
                            <p>Surat didisposisikan ke unit kerja atau pegawai yang relevan.</p>
                        </div>
                        <div class="track-step">
                            <span class="num">3</span>
                            <h4>Tindak Lanjut</h4>
                            <p>Penerima disposisi memproses dan memperbarui statusnya.</p>
                        </div>
                        <div class="track-step">
                            <span class="num">4</span>
                            <h4>Selesai &amp; Arsip</h4>
                            <p>Surat yang tuntas otomatis tersimpan ke arsip digital.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 reveal reveal-delay-1">
                <div class="track-card">
                    <div class="track-head">
                        <span class="track-icon" style="background:var(--stamp)"><i class="bi bi-send"></i></span>
                        <div>
                            <h3 class="mb-0" style="font-size:1.05rem;font-weight:700">Surat Keluar</h3>
                            <p class="mb-0" style="font-size:.78rem;color:var(--ink-muted)">Dari draf sampai terbit resmi</p>
                        </div>
                    </div>
                    <div class="track-steps">
                        <div class="track-step">
                            <span class="num">1</span>
                            <h4>Draf &amp; Ajukan</h4>
                            <p>Surat disusun dari template resmi, lalu diajukan untuk persetujuan.</p>
                        </div>
                        <div class="track-step">
                            <span class="num">2</span>
                            <h4>Approval</h4>
                            <p>Kepala sekolah atau admin berwenang meninjau dan menyetujui.</p>
                        </div>
                        <div class="track-step">
                            <span class="num">3</span>
                            <h4>Terbit &amp; Cetak</h4>
                            <p>Surat yang disetujui siap dicetak dalam format PDF resmi.</p>
                        </div>
                        <div class="track-step">
                            <span class="num">4</span>
                            <h4>Arsip</h4>
                            <p>Salinan surat yang telah terbit tersimpan otomatis ke arsip.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" style="background:var(--paper);border-top:1px solid var(--border);border-bottom:1px solid var(--border)">
    <div class="container">
        <p class="section-eyebrow reveal text-center d-block">Kata Mereka</p>
        <div class="memo-card reveal reveal-delay-1">
            <p class="memo-quote">Sejak pakai SIMASTER, saya tidak perlu lagi menunggu map fisik naik-turun ruangan. Semua surat yang butuh tanda tangan saya bisa saya tinjau dan setujui langsung dari sistem, kapan pun saya sempat.</p>
            <div class="memo-sign">
                <div class="memo-avatar">KS</div>
                <div>
                    <p class="memo-name">Kepala Sekolah</p>
                    <p class="memo-role">SMK Negeri 7</p>
                </div>
            </div>
            <svg class="memo-stamp-mini" viewBox="0 0 160 160" xmlns="http://www.w3.org/2000/svg">
                <circle cx="80" cy="80" r="70" fill="none" stroke="var(--stamp)" stroke-width="3"/>
                <circle cx="80" cy="80" r="58" fill="none" stroke="var(--stamp)" stroke-width="2" stroke-dasharray="3 4"/>
                <text x="80" y="86" text-anchor="middle" font-family="Poppins, sans-serif" font-size="20" font-weight="800" fill="var(--stamp)">OK</text>
            </svg>
        </div>
    </div>
</section>

<section class="section" id="faq">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4 reveal">
                <p class="section-eyebrow">Pertanyaan Umum</p>
                <h2 class="section-title display">Masih ada yang ingin ditanyakan?</h2>
                <p class="section-desc">Berikut beberapa hal yang paling sering ditanyakan seputar penggunaan SIMASTER.</p>
            </div>
            <div class="col-lg-8 reveal reveal-delay-1">
                <div class="accordion faq-accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Siapa saja yang bisa memiliki akun SIMASTER?
                            </button>
                        </h3>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">Akun dibuat oleh Admin TU untuk seluruh pegawai yang membutuhkan akses, mulai dari kepala sekolah, staf tata usaha, guru, hingga wali kelas — sesuai peran masing-masing.</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Bagaimana status surat bisa dipantau?
                            </button>
                        </h3>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">Setiap surat memiliki status berjalan (diterima, didisposisikan, ditindaklanjuti, disetujui, diarsipkan) yang bisa dilihat langsung oleh pihak terkait tanpa perlu bertanya ke TU.</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Apakah surat yang sudah diarsipkan bisa dicetak ulang?
                            </button>
                        </h3>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">Bisa. Semua surat yang telah terbit tersimpan sebagai arsip digital dan dapat dicetak ulang dalam format PDF kapan pun dibutuhkan.</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                Apakah setiap tindakan pengguna tercatat?
                            </button>
                        </h3>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">Ya, seluruh aktivitas — mulai dari input surat, disposisi, hingga approval — tercatat otomatis pada log aktivitas untuk keperluan audit internal sekolah.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="cta-banner reveal">
            <i class="bi bi-envelope-fill cta-envelope e1"></i>
            <i class="bi bi-file-earmark-text-fill cta-envelope e2"></i>
            <i class="bi bi-patch-check-fill cta-envelope e3"></i>
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <h2 class="display">Masuk ke akun SIMASTER Anda</h2>
                    <p>Gunakan akun yang diberikan admin sekolah untuk mulai mengelola surat masuk, surat keluar, dan arsip.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('login') }}" class="btn btn-light px-4 py-2">
                        Masuk ke Sistem <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="footer-landing">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="f-brand">
                    <span class="brand-badge">
                        @if($logoPath)
                            <img src="{{ asset($logoPath) }}" alt="Logo SMK Negeri 7">
                        @else
                            <span>S7</span>
                        @endif
                    </span>
                    <p>SIMASTER</p>
                </div>
                <p class="f-desc">Sistem Informasi Manajemen Surat Terpadu — mencatat, mendisposisikan, dan mengarsipkan seluruh surat SMK Negeri 7 dalam satu alur resmi.</p>
                <div class="d-flex gap-2 mt-3">
                    <a href="#" class="footer-social"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="footer-social"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="footer-social"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <h6>Navigasi</h6>
                <a href="#fitur" class="f-link">Fitur</a>
                <a href="#peran" class="f-link">Untuk Siapa</a>
                <a href="#alur" class="f-link">Alur Surat</a>
                <a href="#faq" class="f-link">FAQ</a>
            </div>
            <div class="col-6 col-lg-2">
                <h6>Akses</h6>
                <a href="{{ route('login') }}" class="f-link">Masuk ke Sistem</a>
                <a href="#" class="f-link">Panduan Pengguna</a>
            </div>
            <div class="col-lg-4">
                <h6>Kontak Tata Usaha</h6>
                <div class="f-contact"><i class="bi bi-geo-alt"></i><span>Kantor TU SMK Negeri 7</span></div>
                <div class="f-contact"><i class="bi bi-envelope"></i><span>tu@smkn7.sch.id</span></div>
                <div class="f-contact"><i class="bi bi-clock"></i><span>Senin–Jumat, 07.00–15.00 WIB</span></div>
            </div>
        </div>

        <div class="footer-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
            <span>© {{ date('Y') }} SMK Negeri 7 — SIMASTER, Sistem Informasi Manajemen Surat Terpadu.</span>
            <a href="{{ route('login') }}" class="f-link mb-0">Masuk sebagai pengguna terdaftar →</a>
        </div>
    </div>
</footer>

<button class="back-to-top" id="backToTop" aria-label="Kembali ke atas" type="button">
    <i class="bi bi-arrow-up"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sticky navbar shadow on scroll
    const nav = document.getElementById('mainNav');
    const backToTop = document.getElementById('backToTop');
    window.addEventListener('scroll', () => {
        const scrolled = window.scrollY > 12;
        nav.classList.toggle('is-scrolled', scrolled);
        backToTop.classList.toggle('show', window.scrollY > 480);
    }, { passive: true });
    backToTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

    // Close mobile menu after clicking a link
    document.querySelectorAll('#navMenu a').forEach(link => {
        link.addEventListener('click', () => {
            const menu = bootstrap.Collapse.getOrCreateInstance(document.getElementById('navMenu'));
            menu.hide();
        });
    });

    // Reveal-on-scroll
    const revealEls = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });
        revealEls.forEach(el => io.observe(el));
    } else {
        revealEls.forEach(el => el.classList.add('is-visible'));
    }
</script>
</body>
</html>
