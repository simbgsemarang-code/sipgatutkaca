/*
 * Legenda peta yang dapat disembunyikan / ditampilkan (dipakai Spasial, Analisa Kerusakan, Cagar Budaya).
 *
 *   var lg = legendToggle({ kunci: 'spasial' });   // kunci = nama peta, untuk mengingat pilihan pengguna
 *   lg.body.innerHTML = '...isi legenda...';        // isi legenda ditulis ke .body (boleh diubah kapan saja)
 *   return lg.el;                                   // elemen kontrol Leaflet (di dalam L.control.onAdd)
 *
 * Pilihan buka/tutup diingat per peta di localStorage (bila tersedia). Tanpa pilihan tersimpan,
 * legenda terbuka di layar lebar dan tertutup di layar sempit (<= 560px) supaya peta tidak tertutup.
 */
(function () {
  if (window.legendToggle) return;

  var CSS =
    '.legend.legend-toggle{padding:0;max-height:none;max-width:none;overflow:hidden;line-height:1.45;min-width:0;background:rgba(255,255,255,.9);-webkit-backdrop-filter:blur(12px) saturate(1.2);backdrop-filter:blur(12px) saturate(1.2);border:1px solid rgba(165,126,44,.36);border-radius:14px;box-shadow:0 10px 28px rgba(8,24,38,.22)}' +
    '.legend-toggle .legend-head{display:flex;align-items:center;gap:8px;width:100%;background:linear-gradient(135deg,rgba(201,162,75,.2),rgba(228,200,123,.08));border:0;cursor:pointer;padding:10px 12px 10px 14px;' +
    'font:600 .7rem/1.2 "Plus Jakarta Sans",system-ui,sans-serif;letter-spacing:.14em;text-transform:uppercase;color:#8a6a1c;text-align:left}' +
    '.legend-toggle .legend-head:hover{background:linear-gradient(135deg,rgba(201,162,75,.3),rgba(228,200,123,.14))}' +
    '.legend-toggle .legend-head:focus-visible{outline:2px solid #A57E2C;outline-offset:-2px}' +
    '.legend-toggle .lg-ikon{flex:0 0 auto}' +
    '.legend-toggle .lg-chev{margin-left:auto;flex:0 0 auto;transition:transform .2s}' +
    '.legend-toggle.tutup .lg-chev{transform:rotate(180deg)}' +
    '.legend-toggle .legend-body{padding:8px 14px 12px;border-top:1px solid rgba(165,126,44,.22);max-height:280px;overflow-y:auto;scrollbar-width:thin}' +
    '.legend-toggle.tutup .legend-body{display:none}' +
    '@media(max-width:560px){.legend-toggle .legend-body{max-height:190px}}';

  function suntikCss() {
    if (document.getElementById('legend-toggle-css')) return;
    var s = document.createElement('style');
    s.id = 'legend-toggle-css';
    s.appendChild(document.createTextNode(CSS));
    document.head.appendChild(s);
  }

  function baca(kunci) { try { return window.localStorage.getItem('legend:' + kunci); } catch (e) { return null; } }
  function simpan(kunci, nilai) { try { window.localStorage.setItem('legend:' + kunci, nilai); } catch (e) { /* abaikan */ } }

  window.legendToggle = function (opt) {
    opt = opt || {};
    var kunci = opt.kunci || location.pathname;
    suntikCss();

    var el = document.createElement('div');
    el.className = 'legend legend-toggle';

    var head = document.createElement('button');
    head.type = 'button';
    head.className = 'legend-head';
    head.innerHTML =
      '<svg class="lg-ikon" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">' +
      '<rect x="2" y="3" width="3" height="3" rx=".6" stroke="currentColor" stroke-width="1.3"/><rect x="2" y="10" width="3" height="3" rx=".6" stroke="currentColor" stroke-width="1.3"/>' +
      '<path d="M7.5 4.5h6M7.5 11.5h6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>' +
      '<span>' + (opt.judul || 'Legenda') + '</span>' +
      '<svg class="lg-chev" width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2.5 4.5L6 8l3.5-3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';

    var body = document.createElement('div');
    body.className = 'legend-body';
    body.id = 'legend-body-' + String(kunci).replace(/[^a-z0-9_-]/gi, '');
    head.setAttribute('aria-controls', body.id);

    el.appendChild(head);
    el.appendChild(body);

    function atur(tutup, ingat) {
      el.classList.toggle('tutup', !!tutup);
      head.setAttribute('aria-expanded', tutup ? 'false' : 'true');
      head.title = tutup ? 'Tampilkan legenda' : 'Sembunyikan legenda';
      if (ingat) simpan(kunci, tutup ? 'tutup' : 'buka');
    }
    var tersimpan = baca(kunci);
    var awalTutup = tersimpan !== null ? (tersimpan === 'tutup') : (window.matchMedia && window.matchMedia('(max-width:560px)').matches);
    atur(awalTutup, false);

    head.addEventListener('click', function () { atur(!el.classList.contains('tutup'), true); });

    // jangan teruskan klik/gulir ke peta
    if (window.L && L.DomEvent) {
      L.DomEvent.disableClickPropagation(el);
      L.DomEvent.disableScrollPropagation(el);
    }

    return { el: el, body: body, buka: function () { atur(false, true); }, tutup: function () { atur(true, true); } };
  };
})();
