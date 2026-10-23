</div></main></div><script>
(function(){
  var actions=document.querySelector('.brand-actions'), name=actions&&actions.querySelector('strong');
  if(!actions||!name)return;
  var wrap=document.createElement('div'); wrap.className='user-menu';
  wrap.innerHTML='<button class="user-menu-btn" id="userMenuBtn" type="button" aria-expanded="false" aria-controls="userMenuPanel"></button><div class="user-menu-panel" id="userMenuPanel" role="menu"><a href="<?= base_url('pengaturan') ?>" role="menuitem">⚙ Pengaturan</a><a href="<?= base_url('login/keluar') ?>" class="logout" role="menuitem">⇥ Logout</a></div>';
  var button=wrap.querySelector('button'); button.textContent=name.textContent.replace('⌄','').trim(); button.insertAdjacentHTML('beforeend','<svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2.5 4.5L6 8l3.5-3.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>');
  name.replaceWith(wrap); var panel=wrap.querySelector('.user-menu-panel');
  button.addEventListener('click',function(e){e.stopPropagation();var open=panel.classList.toggle('open');button.setAttribute('aria-expanded',open?'true':'false')});
  document.addEventListener('click',function(e){if(!wrap.contains(e.target)){panel.classList.remove('open');button.setAttribute('aria-expanded','false')}});
  document.addEventListener('keydown',function(e){if(e.key==='Escape'){panel.classList.remove('open');button.setAttribute('aria-expanded','false')}});
})();
</script></body></html>
