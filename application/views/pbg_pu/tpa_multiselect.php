<style>
.tpa-picker{position:relative;min-width:0}.tpa-picker-native{position:absolute!important;width:1px!important;height:1px!important;min-height:0!important;padding:0!important;opacity:0;pointer-events:none}.tpa-picker+ .multi-hint{display:none}.tpa-picker-toggle{display:flex;align-items:center;justify-content:space-between;gap:12px;width:100%;min-height:50px;padding:12px 16px;border:1px solid var(--line);border-radius:12px;background:#fff;color:var(--text);font:400 12px var(--body);text-align:left;cursor:pointer}.tpa-picker-toggle:focus-visible{outline:2px solid var(--gold);outline-offset:3px}.tpa-picker-toggle:disabled{opacity:.55;cursor:not-allowed}.tpa-picker-label{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.tpa-picker-menu{position:absolute;z-index:30;top:calc(100% + 6px);left:0;right:0;padding:8px;background:#fff;border:1px solid var(--line);border-radius:12px;box-shadow:0 12px 30px #16394e26;max-height:280px;overflow:auto}.tpa-picker-menu[hidden]{display:none}.tpa-picker-menu label{display:flex!important;align-items:flex-start;gap:10px;padding:10px!important;margin:0!important;border-radius:6px;font:400 12px var(--body)!important;color:var(--text)!important;letter-spacing:0!important;text-transform:none!important;cursor:pointer}.tpa-picker-menu label:hover{background:#f1f6f7}.tpa-picker-menu input{flex:0 0 16px;width:16px!important;height:16px;margin:2px 0 0;padding:0!important;accent-color:var(--gold)}.tpa-picker-menu .unavailable{opacity:.5;cursor:not-allowed}.tpa-picker-all{border-bottom:1px solid var(--line);border-radius:0!important;margin-bottom:4px!important}.tpa-picker-option-text{min-width:0;overflow-wrap:anywhere}.tpa-picker-option-text small{display:block;margin-top:3px;color:var(--muted);font-size:10px}
</style>
<script>
(function(){
  var pickers=[];
  document.querySelectorAll('select.tpa-multiple').forEach(function(select,index){
    var wrapper=document.createElement('div');wrapper.className='tpa-picker';
    select.parentNode.insertBefore(wrapper,select);wrapper.appendChild(select);select.classList.add('tpa-picker-native');select.tabIndex=-1;
    var toggle=document.createElement('button');toggle.type='button';toggle.className='tpa-picker-toggle';toggle.disabled=select.disabled;toggle.setAttribute('aria-expanded','false');
    var title=select.parentNode.parentNode.querySelector('label');toggle.setAttribute('aria-label',title?title.textContent:'Pilih TPA');
    var caption=document.createElement('span');caption.className='tpa-picker-label';toggle.appendChild(caption);
    var arrow=document.createElement('span');arrow.textContent='⌄';arrow.setAttribute('aria-hidden','true');toggle.appendChild(arrow);wrapper.appendChild(toggle);
    var menu=document.createElement('div');menu.className='tpa-picker-menu';menu.id='tpa-picker-menu-'+index;menu.hidden=true;toggle.setAttribute('aria-controls',menu.id);wrapper.appendChild(menu);
    function checkbox(text,disabled){var label=document.createElement('label'),input=document.createElement('input'),span=document.createElement('span');input.type='checkbox';input.disabled=disabled;span.className='tpa-picker-option-text';span.textContent=text;label.appendChild(input);label.appendChild(span);if(disabled)label.className='unavailable';menu.appendChild(label);return input;}
    var all=checkbox('Pilih semua',false);all.parentNode.classList.add('tpa-picker-all');var items=[];
    Array.prototype.forEach.call(select.options,function(option){var input=checkbox(option.textContent,option.disabled);items.push({input:input,option:option});input.addEventListener('change',function(){option.selected=input.checked;select.dispatchEvent(new Event('change',{bubbles:true}));});});
    function refresh(){var enabled=items.filter(function(item){return !item.option.disabled;}),chosen=enabled.filter(function(item){return item.option.selected;});items.forEach(function(item){item.input.checked=item.option.selected;});all.checked=enabled.length>0&&chosen.length===enabled.length;all.indeterminate=chosen.length>0&&chosen.length<enabled.length;all.disabled=!enabled.length;caption.textContent=chosen.length?chosen.length+' TPA dipilih':'— Pilih TPA —';toggle.title=chosen.map(function(item){return item.option.textContent;}).join(', ');}
    function close(){menu.hidden=true;toggle.setAttribute('aria-expanded','false');}
    function open(){pickers.forEach(function(picker){picker.close();});menu.hidden=false;toggle.setAttribute('aria-expanded','true');}
    toggle.addEventListener('click',function(){if(menu.hidden)open();else close();});
    toggle.addEventListener('keydown',function(event){if(event.key==='ArrowDown'){event.preventDefault();open();var first=menu.querySelector('input:not(:disabled)');if(first)first.focus();}});
    wrapper.addEventListener('keydown',function(event){if(event.key==='Escape'){close();toggle.focus();}});
    wrapper.addEventListener('focusout',function(event){if(!wrapper.contains(event.relatedTarget))close();});
    all.addEventListener('change',function(){items.forEach(function(item){if(!item.option.disabled)item.option.selected=all.checked;});select.dispatchEvent(new Event('change',{bubbles:true}));});
    select.addEventListener('change',refresh);select.addEventListener('invalid',function(event){event.preventDefault();open();toggle.focus();caption.textContent='Pilih minimal satu TPA';});
    pickers.push({wrapper:wrapper,close:close});refresh();
  });
  document.addEventListener('click',function(event){pickers.forEach(function(picker){if(!picker.wrapper.contains(event.target))picker.close();});});
})();
</script>
