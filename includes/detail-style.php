<style>
    body.detail-page { font-family: 'Inter', sans-serif; background: radial-gradient(circle at top left, rgba(245,176,65,.08), transparent 34rem), #080a0b; color: #f6f2e9; }
    .rd-muted { color: rgba(255,255,255,.68); }
    .rd-icon { color: #F5B041; width: 20px; text-align: center; }
    .rd-hero { position: relative; min-height: 520px; display: flex; align-items: end; background-size: cover; background-position: center; border-bottom: 1px solid rgba(245,176,65,.24); overflow: hidden; }
    .rd-hero::before { content: ''; position: absolute; inset: 0; background: linear-gradient(90deg, rgba(8,10,11,.98), rgba(8,10,11,.58), rgba(8,10,11,.86)), linear-gradient(180deg, rgba(8,10,11,.1), #080a0b 96%); }
    .rd-hero .container { position: relative; z-index: 2; }
    .rd-back, .rd-chip, .rd-status, .rd-soft-link { display: inline-flex; align-items: center; gap: .45rem; min-height: 34px; padding: 7px 12px; color: #fff; background: rgba(7,8,8,.72); border: 1px solid rgba(245,176,65,.35); border-radius: 8px; font-size: .86rem; font-weight: 800; text-decoration: none; }
    .rd-back, .rd-soft-link { color: #F5B041; }
    .rd-back:hover, .rd-soft-link:hover { color: #111; background: #F5B041; }
    .rd-status.open { color: #43e68b; border-color: rgba(67,230,139,.35); }
    .rd-title { color: #fff; font-size: clamp(2.5rem, 6vw, 5rem); font-weight: 800; line-height: 1.02; margin: .85rem 0 .65rem; }
    .rd-kicker, .rd-meta-row, .rd-action-row { display: flex; flex-wrap: wrap; align-items: center; gap: .85rem; }
    .rd-kicker { color: rgba(255,255,255,.84); font-size: 1.02rem; margin-bottom: 1rem; }
    .rd-action-row { margin-top: 1.35rem; }
    .rd-action-row form { margin: 0; }
    .rd-btn, .rd-outline-btn { min-height: 44px; display: inline-flex; align-items: center; justify-content: center; gap: .55rem; border-radius: 8px; padding: 10px 22px; font-weight: 800; text-decoration: none; border: 1px solid transparent; }
    .rd-btn { color: #111; background: linear-gradient(95deg,#F5B041,#E67E22); }
    .rd-outline-btn { color: #fff; background: rgba(0,0,0,.35); border-color: rgba(245,176,65,.45); }
    .rd-outline-btn:hover, .rd-btn:hover { color: #111; background: #F5B041; }
    .rd-thumbs { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: .75rem; }
    .rd-thumb { min-height: 96px; border: 1px solid rgba(245,176,65,.36); border-radius: 8px; background-size: cover; background-position: center; box-shadow: 0 16px 32px rgba(0,0,0,.4); }
    .rd-main { padding: 1.25rem 0 3rem; }
    .rd-panel { background: rgba(17,18,16,.92); border: 1px solid rgba(245,176,65,.22); border-radius: 8px; box-shadow: 0 22px 42px rgba(0,0,0,.26); padding: 1.25rem; }
    .rd-panel + .rd-panel { margin-top: 1rem; }
    .rd-panel-title { color: #fff; font-size: 1.25rem; font-weight: 800; margin-bottom: 1rem; }
    .rd-about-grid { display: grid; grid-template-columns: minmax(142px,180px) 1fr; gap: 1.25rem; align-items: center; }
    .rd-logo-box { min-height: 132px; display: grid; place-items: center; text-align: center; border: 1px solid rgba(245,176,65,.28); border-radius: 8px; background: rgba(255,255,255,.055); }
    .rd-logo-box i { color: #F5B041; font-size: 2.3rem; margin-bottom: .55rem; }
    .rd-logo-box strong { display: block; color: #fff; font-size: 1rem; line-height: 1.15; }
    .rd-amenities { display: flex; flex-wrap: wrap; gap: .85rem 1rem; margin-top: 1rem; }
    .rd-amenities span { color: rgba(255,255,255,.77); font-size: .9rem; }
    .rd-tabs { display: flex; flex-wrap: wrap; gap: .55rem; margin-bottom: 1rem; }
    .rd-tabs a, .rd-tabs span { border-radius: 8px; padding: 9px 17px; color: rgba(255,255,255,.78); background: rgba(255,255,255,.09); font-size: .86rem; font-weight: 800; text-decoration: none; }
    .rd-tabs .active { color: #111; background: #F5B041; }
    .rd-menu-grid { display: grid; grid-template-columns: repeat(4,minmax(0,1fr)); gap: 1rem; }
    .rd-menu-card { overflow: hidden; border: 1px solid rgba(245,176,65,.18); border-radius: 8px; background: rgba(255,255,255,.055); }
    .rd-menu-image { position: relative; min-height: 130px; background-size: cover; background-position: center; }
    .rd-menu-tag { position: absolute; top: 9px; left: 9px; color: #111; background: #F5B041; border-radius: 6px; padding: 4px 7px; font-size: .66rem; font-weight: 900; text-transform: uppercase; }
    .rd-menu-body { padding: .85rem; }
    .rd-menu-body h4 { color: #fff; font-size: 1rem; font-weight: 800; margin-bottom: .35rem; }
    .rd-menu-body p { min-height: 42px; color: rgba(255,255,255,.68); font-size: .82rem; margin-bottom: .85rem; }
    .rd-price { color: #F5B041; font-weight: 900; }
    .rd-gallery { display: grid; grid-template-columns: repeat(5,minmax(0,1fr)); gap: .75rem; }
    .rd-gallery-tile { min-height: 98px; border-radius: 8px; border: 1px solid rgba(255,255,255,.1); background-size: cover; background-position: center; }
    .rd-similar-grid { display: grid; grid-template-columns: repeat(4,minmax(0,1fr)); gap: .75rem; }
    .rd-similar-item { display: grid; grid-template-columns: 72px 1fr; gap: .75rem; color: inherit; text-decoration: none; border: 1px solid rgba(245,176,65,.16); border-radius: 8px; padding: .55rem; background: rgba(255,255,255,.055); }
    .rd-similar-image { min-height: 72px; border-radius: 7px; background-size: cover; background-position: center; }
    .rd-similar-item h4 { color: #fff; font-size: .92rem; font-weight: 800; margin-bottom: .25rem; }
    .rd-info-list, .rd-review-list { display: grid; gap: 1rem; }
    .rd-info-line { display: grid; grid-template-columns: 26px 1fr; gap: .65rem; }
    .rd-info-line strong { display: block; color: #F5B041; font-size: .9rem; }
    .rd-info-line span { color: rgba(255,255,255,.78); font-size: .92rem; }
    .rd-review-line { border-top: 1px solid rgba(255,255,255,.1); padding-top: 1rem; }
    .rd-review-line:first-child { border-top: 0; padding-top: 0; }
    .rd-review-head { display: flex; justify-content: space-between; gap: .75rem; align-items: center; margin-bottom: .6rem; }
    .rd-review-user { display: flex; align-items: center; gap: .7rem; }
    .rd-avatar { width: 38px; height: 38px; display: grid; place-items: center; color: #111; background: linear-gradient(135deg,#f7d37c,#f5a941); border-radius: 50%; font-weight: 900; }
    .rd-map { min-height: 190px; position: relative; display: grid; place-items: center; overflow: hidden; border-radius: 8px; border: 1px solid rgba(255,255,255,.12); background: linear-gradient(35deg, rgba(111,168,220,.55), transparent 34%), linear-gradient(135deg,#dceee7 0%,#edf2e7 48%,#cbdff1 100%); }
    .rd-map::before, .rd-map::after { content: ''; position: absolute; width: 140%; height: 4px; background: rgba(255,255,255,.86); transform: rotate(-18deg); }
    .rd-map::after { transform: rotate(28deg); background: rgba(245,176,65,.62); }
    .rd-map-card { position: relative; z-index: 2; max-width: 230px; color: #111; background: #fff; border-radius: 8px; padding: .75rem .9rem; box-shadow: 0 12px 26px rgba(0,0,0,.24); font-size: .82rem; text-align: left; }
    .rd-map-card i { color: #e4473f; }
    @media (max-width: 1199px) { .rd-menu-grid, .rd-similar-grid { grid-template-columns: repeat(2,minmax(0,1fr)); } }
    @media (max-width: 991.98px) { .rd-thumbs { margin-top: 1.25rem; } .rd-about-grid { grid-template-columns: 1fr; } }
    @media (max-width: 767.98px) { .rd-hero { min-height: auto; padding: 3.5rem 0 2.5rem; } .rd-menu-grid, .rd-gallery, .rd-similar-grid { grid-template-columns: 1fr; } .rd-thumbs { grid-template-columns: repeat(2,minmax(0,1fr)); } .rd-action-row .rd-btn, .rd-action-row .rd-outline-btn, .rd-action-row form { width: 100%; } }
</style>