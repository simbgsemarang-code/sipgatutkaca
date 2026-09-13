<div class="itr-auth-modal" id="itrAuthModal" hidden>
  <div class="itr-auth-backdrop" data-itr-close></div>
  <section class="itr-auth-dialog" role="dialog" aria-modal="true" aria-labelledby="itrAuthTitle">
    <div class="itr-auth-heading"><div><p class="eyebrow">Portal Pemohon ITR</p><h2 id="itrAuthTitle">Masuk ke Akun</h2></div><button type="button" class="itr-auth-close" data-itr-close aria-label="Tutup modal">×</button></div>
    <nav class="itr-auth-tabs" aria-label="Pilihan akun"><button type="button" data-itr-mode="login">Masuk</button><button type="button" data-itr-mode="daftar">Mendaftar</button><button type="button" data-itr-mode="lupa">Lupa / Ganti Password</button></nav>
    <iframe id="itrAuthFrame" title="Formulir akun pemohon ITR" src="about:blank"></iframe>
    <?php if (ENVIRONMENT === 'development'): ?>
    <div class="itr-demo" id="itrDemoAccounts"><p class="note">Akun dummy pemohon ITR — hanya untuk uji coba:</p>
      <?php foreach (array(array('Budi Santoso','budi.santoso.itr@sipgatutkaca.local','ItrDemo#2026A'),array('Dewi Anggraini','dewi.anggraini.itr@sipgatutkaca.local','ItrDemo#2026B')) as $demo): ?>
      <div class="itr-demo-account"><div><strong><?= htmlspecialchars($demo[0]) ?></strong><small><?= htmlspecialchars($demo[1]) ?> / <?= htmlspecialchars($demo[2]) ?></small></div><button type="button" data-itr-email="<?= htmlspecialchars($demo[1],ENT_QUOTES,'UTF-8') ?>" data-itr-password="<?= htmlspecialchars($demo[2],ENT_QUOTES,'UTF-8') ?>">Gunakan Akun</button></div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </section>
</div>
<style>
.itr-auth-modal[hidden]{display:none}.itr-auth-modal{position:fixed;inset:0;z-index:300;display:flex;align-items:center;justify-content:center;padding:20px}.itr-auth-backdrop{position:absolute;inset:0;background:rgba(15,35,51,.65);backdrop-filter:blur(5px)}section.itr-auth-dialog{position:relative;width:min(620px,100%);max-height:92vh;overflow:auto;margin:0;padding:24px!important;background:var(--bg,#fff);border:1px solid var(--line);border-radius:20px;box-shadow:0 24px 80px #0004}.itr-auth-heading{display:flex;align-items:flex-start;justify-content:space-between;gap:16px}.itr-auth-heading h2{font-size:28px;margin:5px 0 20px}.itr-auth-heading .eyebrow{font-size:11px}.itr-auth-close{border:0;background:transparent;color:var(--text);font-size:28px;cursor:pointer}.itr-auth-tabs{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px}.itr-auth-tabs button,.itr-demo-account button{border:1px solid var(--line);border-radius:10px;padding:10px 13px;background:var(--surface,#fff);color:var(--text);font:500 12px var(--body);cursor:pointer}.itr-auth-tabs button[aria-current=true]{background:var(--gold-500,#a57e2c);color:#fff}.itr-auth-dialog iframe{display:block;width:100%;height:430px;border:0;background:transparent}.itr-demo{border-top:1px solid var(--line);margin-top:16px;padding-top:12px}.itr-demo-account{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:12px 0;border-bottom:1px solid var(--line)}.itr-demo-account strong{font-size:14px;color:var(--text)}.itr-demo-account small{display:block;font-size:12px;color:var(--muted);overflow-wrap:anywhere}.itr-auth-dialog button:focus-visible{outline:2px solid var(--gold-500,#a57e2c);outline-offset:3px}@media(max-width:600px){.itr-auth-modal{padding:10px}section.itr-auth-dialog{padding:18px!important}.itr-demo-account{align-items:flex-start;flex-direction:column}}
</style>
<script>
(function(){
  var modal=document.getElementById('itrAuthModal'),frame=document.getElementById('itrAuthFrame'),apply=document.getElementById('itrApplyButton'),previousFocus,previousOverflow;
  var urls=<?= json_encode(array('login'=>base_url('login?from=itr'),'daftar'=>base_url('daftar'),'lupa'=>base_url('login/lupa-password')),JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) ?>;
  var dashboard=<?= json_encode(base_url('pemohon'),JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) ?>;
  function setMode(mode,navigate){modal.querySelectorAll('[data-itr-mode]').forEach(function(button){button.setAttribute('aria-current',button.dataset.itrMode===mode?'true':'false');});document.getElementById('itrAuthTitle').textContent=mode==='daftar'?'Daftar Akun Pemohon':mode==='lupa'?'Atur Ulang Password':'Masuk ke Akun';var demo=document.getElementById('itrDemoAccounts');if(demo)demo.hidden=mode!=='login';if(navigate)frame.src=urls[mode];}
  function close(){modal.hidden=true;document.body.style.overflow=previousOverflow;if(previousFocus)previousFocus.focus();}
  apply.addEventListener('click',function(){previousFocus=document.activeElement;previousOverflow=document.body.style.overflow;modal.hidden=false;document.body.style.overflow='hidden';setMode('login',true);modal.querySelector('.itr-auth-close').focus();});
  modal.querySelectorAll('[data-itr-close]').forEach(function(button){button.addEventListener('click',close);});
  modal.querySelectorAll('[data-itr-mode]').forEach(function(button){button.addEventListener('click',function(){setMode(button.dataset.itrMode,true);});});
  frame.addEventListener('load',function(){
    try {var doc=frame.contentDocument,path=new URL(frame.contentWindow.location.href).pathname;if(path.indexOf('/pemohon')!==-1){window.location.assign(dashboard);return;}if(path.indexOf('/login')===-1&&path.indexOf('/daftar')===-1)return;
      var mode=path.indexOf('/daftar')!==-1?'daftar':path.indexOf('lupa')!==-1||path.indexOf('atur-ulang')!==-1?'lupa':'login';setMode(mode,false);
      var style=doc.createElement('style');style.textContent='header,footer,.theme-toggle,.theme-panel,.page-breadcrumb{display:none!important}body{background:transparent!important;margin:0!important}main,section,.wrap{padding:0!important;margin:0!important;width:100%!important;max-width:none!important}.form-card,.login-card{padding:20px!important;box-shadow:none!important}h1,h2,.eyebrow{display:none!important}';doc.head.appendChild(style);
      doc.querySelectorAll('form').forEach(function(form){if(mode==='daftar'||mode==='login'){var origin=form.querySelector("input[name=from]");if(!origin){origin=doc.createElement('input');origin.type='hidden';origin.name='from';form.appendChild(origin);}origin.value='itr';}});
      frame.style.height=Math.min(580,Math.max(300,doc.documentElement.scrollHeight))+'px';
    } catch(error){/* Authentication remains usable in the frame if enhancement fails. */}
  });
  modal.querySelectorAll('[data-itr-email]').forEach(function(button){button.addEventListener('click',function(){var doc=frame.contentDocument,email=doc&&doc.querySelector('input[name=email]'),password=doc&&doc.querySelector('input[name=password]');if(email&&password){email.value=button.dataset.itrEmail;password.value=button.dataset.itrPassword;password.focus();}});});
  document.addEventListener('keydown',function(event){if(modal.hidden)return;if(event.key==='Escape'){close();return;}if(event.key==='Tab'){var controls=Array.prototype.filter.call(modal.querySelectorAll('button,iframe'),function(el){return el.getClientRects().length>0;}),first=controls[0],last=controls[controls.length-1];if(event.shiftKey&&document.activeElement===first){event.preventDefault();last.focus();}else if(!event.shiftKey&&document.activeElement===last){event.preventDefault();first.focus();}}});
})();
</script>
