(function (window, document) {
    'use strict'
    const AppUI = window.AppUI

    AppUI.register('interactive', '[data-ui-interactive]', root => { AppUI.interaction(root); return {focus: () => root.focus()} })

    AppUI.register('dismissible', '[data-dismissible="true"]', root => {
        root.querySelector('[data-dismiss]')?.addEventListener('click', () => { root.hidden = true; AppUI.emit(root, `${root.dataset.uiComponent}:close`) })
        return {close: () => { root.hidden = true }}
    })

    AppUI.register('image', '[data-slot="image-wrapper"]', root => {
        const image = root.querySelector('img')
        const fallback = root.querySelector('[data-image-fallback]')
        image?.addEventListener('load', () => { root.dataset.loaded = 'true'; if (fallback) fallback.hidden = true })
        image?.addEventListener('error', () => { root.dataset.loaded = 'false'; image.hidden = true; if (fallback) fallback.hidden = false })
        return {}
    })

    AppUI.register('snippet', '[data-slot="snippet"]', root => {
        const button = root.querySelector('[data-snippet-copy]')
        const value = () => root.querySelector('[data-snippet-value]')?.textContent || ''
        const copy = async () => { await navigator.clipboard.writeText(value()); root.dataset.copied = 'true'; AppUI.emit(root, 'snippet:copy', {value: value()}); setTimeout(() => root.dataset.copied = 'false', 1600) }
        button?.addEventListener('click', copy)
        return {copy, getValue: value}
    })

    AppUI.register('scroll-shadow', '[data-slot="scroll-shadow"]', root => {
        const sync = () => { root.dataset.top = root.scrollTop > 1 ? 'true' : 'false'; root.dataset.bottom = root.scrollTop + root.clientHeight < root.scrollHeight - 1 ? 'true' : 'false' }
        root.addEventListener('scroll', sync, {passive: true}); new ResizeObserver(sync).observe(root); sync(); return {sync}
    })

    AppUI.register('input', '[data-slot="input-wrapper"]', root => {
        const input = root.querySelector('input,textarea')
        const clear = root.querySelector('[data-input-clear]')
        AppUI.interaction(root)
        const sync = () => { root.dataset.filled = input?.value ? 'true' : 'false'; if (clear) clear.hidden = !input?.value }
        clear?.addEventListener('click', () => { input.value = ''; input.dispatchEvent(new Event('input',{bubbles:true})); input.dispatchEvent(new Event('change',{bubbles:true})); input.focus(); sync() })
        root.querySelector('[data-password-toggle]')?.addEventListener('click', event => { input.type = input.type === 'password' ? 'text' : 'password'; event.currentTarget.dataset.visible = input.type === 'text' ? 'true' : 'false'; input.focus() })
        input?.addEventListener('input', sync); sync()
        return {getValue: () => input?.value, setValue: value => { input.value = value ?? ''; input.dispatchEvent(new Event('input',{bubbles:true})); sync() }, focus: () => input?.focus()}
    })

    AppUI.register('number-input', '[data-slot="number-input"]', root => {
        const input = root.querySelector('input[type="number"]')
        const change = delta => { input.stepUp(delta > 0 ? 1 : -1); input.dispatchEvent(new Event('input',{bubbles:true})); input.dispatchEvent(new Event('change',{bubbles:true})) }
        root.querySelector('[data-step-up]')?.addEventListener('click', () => change(1)); root.querySelector('[data-step-down]')?.addEventListener('click', () => change(-1))
        return {getValue: () => input.valueAsNumber, setValue: value => { input.value = value; input.dispatchEvent(new Event('input',{bubbles:true})) }, focus: () => input.focus()}
    })

    AppUI.register('checkbox', '[data-slot="checkbox"]', root => {
        const input = root.querySelector('input[type="checkbox"]')
        input.indeterminate = root.dataset.indeterminate === 'true'
        const sync = notify => { const selected = input.checked; root.dataset.selected = String(selected); root.dataset.indeterminate = String(input.indeterminate); if (notify) AppUI.emit(root, 'checkbox:change', {selected, value: input.value}) }
        input.addEventListener('change', () => sync(true)); AppUI.interaction(root); sync(false)
        return {getValue: () => input.checked, setValue: value => { input.checked = !!value; input.indeterminate = false; sync(false) }, focus: () => input.focus()}
    })

    AppUI.register('radio-group', '[data-slot="radio-group"]', root => {
        const radios = () => [...root.querySelectorAll('[data-slot="radio"]')]
        const input = root.querySelector('[data-radio-input]')
        const setValue = (value, notify = false) => { radios().forEach(radio => { const selected = String(radio.dataset.value) === String(value); radio.dataset.selected = String(selected); radio.setAttribute('aria-checked', String(selected)); radio.tabIndex = selected ? 0 : -1 }); if (input) input.value = value ?? ''; if (notify) { input?.dispatchEvent(new Event('input',{bubbles:true})); input?.dispatchEvent(new Event('change',{bubbles:true})); AppUI.emit(root,'radio-group:change',{value}) } }
        radios().forEach(radio => { AppUI.interaction(radio); radio.addEventListener('click', () => { if (radio.dataset.disabled !== 'true') setValue(radio.dataset.value,true) }) })
        root.addEventListener('keydown', event => { if (!['ArrowLeft','ArrowUp','ArrowRight','ArrowDown'].includes(event.key)) return; event.preventDefault(); const enabled = radios().filter(r => r.dataset.disabled !== 'true'), current = enabled.indexOf(document.activeElement), next = enabled[(current + (event.key === 'ArrowRight' || event.key === 'ArrowDown' ? 1 : -1) + enabled.length) % enabled.length]; next.focus(); setValue(next.dataset.value,true) })
        setValue(input?.value || radios().find(r => r.dataset.selected === 'true')?.dataset.value || '')
        return {getValue: () => input?.value, setValue, focus: () => (radios().find(r => r.dataset.selected === 'true') || radios()[0])?.focus()}
    })

    AppUI.register('switch', '[data-slot="switch"]', root => {
        const input = root.querySelector('input[type="checkbox"]')
        const sync = notify => { root.dataset.selected = String(input.checked); if (notify) AppUI.emit(root,'switch:change',{selected:input.checked}) }
        input.addEventListener('change',()=>sync(true)); AppUI.interaction(root); sync(false)
        return {getValue: () => input.checked, setValue: value => { input.checked = !!value; sync(false) }, focus: () => input.focus()}
    })

    AppUI.register('slider', '[data-slot="slider"]', root => {
        const inputs = [...root.querySelectorAll('input[type="range"]')], fill = root.querySelector('[data-slider-fill]'), thumbs = [...root.querySelectorAll('[data-slider-thumb]')]
        const sync = notify => { const min = Number(inputs[0]?.min || 0), max = Number(inputs[0]?.max || 100), values = inputs.map(input => Number(input.value)), percentages = values.map(value => (value-min)/(max-min)*100); root.style.setProperty('--range-start',`${Math.min(...percentages)}%`); root.style.setProperty('--range-end',`${Math.max(...percentages)}%`); thumbs.forEach((thumb,index) => thumb.style.setProperty('--thumb-position',`${percentages[index]}%`)); root.querySelector('[data-slider-output]')?.replaceChildren(document.createTextNode(values.join(' – '))); if (notify) AppUI.emit(root,'slider:change',{value:values.length === 1 ? values[0] : values}) }
        inputs.forEach(input => input.addEventListener('input',() => sync(true))); sync(false)
        return {getValue: () => inputs.length === 1 ? Number(inputs[0].value) : inputs.map(input => Number(input.value)), setValue: value => { (Array.isArray(value)?value:[value]).forEach((item,index) => { if(inputs[index]) inputs[index].value=item }); sync(false) }}
    })

    AppUI.register('input-otp', '[data-slot="input-otp"]', root => {
        const slots = [...root.querySelectorAll('[data-otp-slot]')], input = root.querySelector('[data-otp-input]')
        const sync = notify => { input.value = slots.map(slot => slot.value).join(''); if (notify) { input.dispatchEvent(new Event('input',{bubbles:true})); input.dispatchEvent(new Event('change',{bubbles:true})); AppUI.emit(root,'input-otp:change',{value:input.value}) } }
        slots.forEach((slot,index) => { slot.addEventListener('input',() => { slot.value = slot.value.slice(-1); if(slot.value) slots[index+1]?.focus(); sync(true) }); slot.addEventListener('keydown',event => { if(event.key==='Backspace'&&!slot.value) slots[index-1]?.focus(); if(event.key==='ArrowLeft') slots[index-1]?.focus(); if(event.key==='ArrowRight') slots[index+1]?.focus() }); slot.addEventListener('paste',event => { event.preventDefault(); [...event.clipboardData.getData('text').slice(0,slots.length)].forEach((character,i)=>slots[i].value=character); sync(true); slots[Math.min(event.clipboardData.getData('text').length,slots.length)-1]?.focus() }) }); sync(false)
        return {getValue:()=>input.value,setValue:value=>{ [...String(value||'')].forEach((character,index)=>{if(slots[index])slots[index].value=character});sync(false)},focus:()=>slots[0]?.focus()}
    })

    AppUI.register('accordion', '[data-slot="accordion"]', root => {
        const items = () => [...root.querySelectorAll(':scope > [data-slot="accordion-item"]')], mode = root.dataset.selectionMode || 'single'
        const setOpen = (item, open, notify = true) => { if (mode === 'single' && open) items().forEach(other => { if(other!==item) setOpen(other,false,false) }); if(root.dataset.disallowEmptySelection==='true'&&!open&&items().filter(i=>i.dataset.open==='true').length===1)return; item.dataset.open=String(open); const button=item.querySelector('[data-slot="accordion-trigger"]'),content=item.querySelector('[data-slot="accordion-content"]'); button?.setAttribute('aria-expanded',String(open)); if(content){content.hidden=false;content.setAttribute('aria-hidden',String(!open));content.inert=!open} if(notify)AppUI.emit(root,'accordion:change',{keys:items().filter(i=>i.dataset.open==='true').map(i=>i.dataset.value)}) }
        items().forEach(item=>{const trigger=item.querySelector('[data-slot="accordion-trigger"]');AppUI.interaction(trigger);trigger?.addEventListener('click',()=>setOpen(item,item.dataset.open!=='true'));setOpen(item,item.dataset.open==='true',false)})
        root.addEventListener('keydown',event=>{const triggers=items().map(i=>i.querySelector('[data-slot="accordion-trigger"]')).filter(Boolean),index=triggers.indexOf(document.activeElement);if(index<0)return;if(event.key==='ArrowDown'){event.preventDefault();triggers[(index+1)%triggers.length].focus()}if(event.key==='ArrowUp'){event.preventDefault();triggers[(index-1+triggers.length)%triggers.length].focus()}if(event.key==='Home'){event.preventDefault();triggers[0].focus()}if(event.key==='End'){event.preventDefault();triggers.at(-1).focus()}})
        return {getValue:()=>items().filter(i=>i.dataset.open==='true').map(i=>i.dataset.value),setValue:values=>{const set=new Set(Array.isArray(values)?values.map(String):[String(values)]);items().forEach(i=>setOpen(i,set.has(String(i.dataset.value)),false))}}
    })

    AppUI.register('tabs', '[data-slot="tabs"]', root => {
        const tabs=()=>[...root.querySelectorAll('[role="tab"]')],panels=()=>[...root.querySelectorAll('[role="tabpanel"]')],input=root.querySelector('[data-tabs-input]')
        const setValue=(value,notify=false)=>{tabs().forEach(tab=>{const selected=String(tab.dataset.value)===String(value);tab.dataset.selected=String(selected);tab.setAttribute('aria-selected',String(selected));tab.tabIndex=selected?0:-1});panels().forEach(panel=>panel.hidden=String(panel.dataset.value)!==String(value));if(input)input.value=value;if(notify)AppUI.emit(root,'tabs:change',{value})}
        tabs().forEach(tab=>{AppUI.interaction(tab);tab.addEventListener('click',()=>setValue(tab.dataset.value,true))});root.addEventListener('keydown',event=>{if(!['ArrowLeft','ArrowRight','Home','End'].includes(event.key))return;const all=tabs().filter(t=>t.dataset.disabled!=='true'),current=all.indexOf(document.activeElement);if(current<0)return;event.preventDefault();const next=event.key==='Home'?all[0]:event.key==='End'?all.at(-1):all[(current+(event.key==='ArrowRight'?1:-1)+all.length)%all.length];next.focus();if(root.dataset.keyboardActivation!=='manual')setValue(next.dataset.value,true)});setValue(input?.value||tabs().find(t=>t.dataset.selected==='true')?.dataset.value||tabs()[0]?.dataset.value)
        return {getValue:()=>input?.value,setValue,focus:()=>tabs().find(t=>t.dataset.selected==='true')?.focus()}
    })

    AppUI.register('listbox', '[data-slot="listbox"]:not([data-collection-owned="true"])', root => {const base=root.closest('[data-slot="listbox-base"]'),name=base?.dataset.name,valuesBox=base?.querySelector('[data-listbox-form-values]'),proxy=base?.querySelector('[data-listbox-input]');const sync=selected=>{const values=selected.map(item=>item.dataset.value),value=root.dataset.selectionMode==='multiple'?values:(values[0]||'');if(valuesBox&&name){valuesBox.replaceChildren(...values.map(value=>{const input=document.createElement('input');input.type='hidden';input.name=name+(root.dataset.selectionMode==='multiple'?'[]':'');input.value=value;return input}))}if(proxy){proxy.value=Array.isArray(value)?JSON.stringify(value):value;proxy.dispatchEvent(new Event('input',{bubbles:true}));proxy.dispatchEvent(new Event('change',{bubbles:true}))}AppUI.emit(root,'listbox:change',{value,values})};return AppUI.collection(root,{selectionMode:root.dataset.selectionMode,onSelectionChange:sync})})

    AppUI.register('select', '[data-slot="select"]', root => {
        const trigger=root.querySelector('[data-slot="select-trigger"]'),popover=root.querySelector('[data-slot="select-popover"]'),listbox=popover?.querySelector('[data-slot="listbox"]'),input=root.querySelector('[data-select-input]'),valueBox=root.querySelector('[data-slot="select-value"]'),multiple=root.dataset.selectionMode==='multiple',formValues=root.querySelector('[data-select-form-values]'),name=formValues?.dataset.name
        if(!trigger||!popover||!listbox)return{};AppUI.interaction(root)
        let overlay
        const collection=AppUI.collection(listbox,{selectionMode:multiple?'multiple':'single',onSelectionChange:selected=>{const values=selected.map(item=>item.dataset.value);input.value=multiple?JSON.stringify(values):(values[0]||'');valueBox.textContent=selected.map(item=>item.dataset.textValue||item.textContent.trim()).join(', ')||valueBox.dataset.placeholderText;valueBox.dataset.placeholder=selected.length?'false':'true';if(multiple&&formValues&&name){formValues.replaceChildren(...values.map(value=>{const field=document.createElement('input');field.type='hidden';field.name=name+'[]';field.value=value;return field}))}input.dispatchEvent(new Event('input',{bubbles:true}));input.dispatchEvent(new Event('change',{bubbles:true}));AppUI.emit(root,'select:change',{value:multiple?values:(values[0]||''),values});if(!multiple)overlay.close(true)}})
        overlay=AppUI.overlay.create({root,trigger,panel:popover,placement:popover.dataset.placement||'bottom-start',matchWidth:true,onOpen:()=>{const selected=collection.selected()[0]||collection.items()[0];selected?.focus()}})
        collection.setValue(multiple?JSON.parse(input.value||'[]'):[input.value]);const initial=collection.selected();valueBox.textContent=initial.map(item=>item.dataset.textValue||item.textContent.trim()).join(', ')||valueBox.dataset.placeholderText;valueBox.dataset.placeholder=initial.length?'false':'true';root.dataset.uiComponent='select'
        return {getValue:()=>multiple?collection.getValue():(collection.getValue()[0]||''),setValue:value=>collection.setValue(value,true),open:overlay.open,close:overlay.close,focus:()=>trigger.focus(),destroy:overlay.destroy}
    })

    AppUI.register('autocomplete', '[data-slot="autocomplete"]', root => {
        const field=root.querySelector('[data-autocomplete-input]'),hidden=root.querySelector('[data-autocomplete-value]'),popover=root.querySelector('[data-slot="autocomplete-popover"]'),listbox=popover?.querySelector('[data-slot="listbox"]')
        if(!field||!popover||!listbox)return{};AppUI.interaction(root)
        let overlay
        const filter=()=>{const query=field.value.trim().toLocaleLowerCase();collection.items().forEach(item=>item.hidden=query&&!String(item.dataset.textValue||item.textContent).toLocaleLowerCase().includes(query));if(!overlay.isOpen())overlay.open(field)}
        const collection=AppUI.collection(listbox,{selectionMode:'single',onSelectionChange:selected=>{const item=selected[0];if(!item)return;field.value=item.dataset.textValue||item.textContent.trim();hidden.value=item.dataset.value;hidden.dispatchEvent(new Event('input',{bubbles:true}));hidden.dispatchEvent(new Event('change',{bubbles:true}));AppUI.emit(root,'autocomplete:change',{value:hidden.value,label:field.value});overlay.close(true)}})
        overlay=AppUI.overlay.create({root,trigger:field,panel:popover,placement:'bottom-start',matchWidth:true});field.addEventListener('input',filter);field.addEventListener('focus',()=>overlay.open(field));field.addEventListener('keydown',event=>{if(event.key==='ArrowDown'){event.preventDefault();overlay.open(field);collection.items().find(item=>!item.hidden)?.focus()}});root.dataset.uiComponent='autocomplete'
        const setValue=(value,notify=true)=>{hidden.value=value??'';collection.setValue([hidden.value]);const item=collection.items().find(i=>String(i.dataset.value)===String(hidden.value));field.value=item?.dataset.textValue||item?.textContent.trim()||'';if(notify){hidden.dispatchEvent(new Event('input',{bubbles:true}));hidden.dispatchEvent(new Event('change',{bubbles:true}));AppUI.emit(root,'autocomplete:change',{value:hidden.value,label:field.value})}};setValue(hidden.value,false)
        return {getValue:()=>hidden.value,setValue,open:overlay.open,close:overlay.close,focus:()=>field.focus(),destroy:overlay.destroy}
    })

    const registerMenuOverlay=(name,root)=>{const trigger=root.querySelector(`[data-slot="${name}-trigger"]`),panel=root.querySelector(`[data-slot="${name}-content"]`);if(!trigger||!panel)return{};const overlay=AppUI.overlay.create({root,trigger,panel,placement:panel.dataset.placement||'bottom-start',matchWidth:false});root.dataset.uiComponent=name;if(name==='dropdown'){const collection=AppUI.collection(panel,{selector:'[role^="menuitem"]',selectionMode:'none'});panel.addEventListener('click',event=>{const item=event.target.closest('[role^="menuitem"]');if(item&&item.dataset.closeOnSelect!=='false'){AppUI.emit(root,'dropdown:action',{key:item.dataset.key});overlay.close(true)}});return{...overlay,collection}}return overlay}
    AppUI.register('dropdown','[data-slot="dropdown"]',root=>registerMenuOverlay('dropdown',root))
    AppUI.register('popover','[data-slot="popover"]',root=>registerMenuOverlay('popover',root))

    AppUI.register('tooltip','[data-slot="tooltip-root"]',root=>{const trigger=root.querySelector('[data-slot="tooltip-trigger"]'),tip=root.querySelector('[data-slot="tooltip"]');let timer;const show=()=>{clearTimeout(timer);timer=setTimeout(()=>{document.body.appendChild(tip);tip.hidden=false;AppUI.overlay.position(tip,trigger,{placement:tip.dataset.placement||'top',matchWidth:false,offset:6})},Number(root.dataset.delay||500))};const hide=()=>{clearTimeout(timer);tip.hidden=true;root.appendChild(tip)};trigger.addEventListener('pointerenter',show);trigger.addEventListener('focusin',show);trigger.addEventListener('pointerleave',hide);trigger.addEventListener('focusout',hide);return{open:show,close:hide}})

    const registerModal=(name,root)=>{const trigger=root.querySelector(`[data-slot="${name}-trigger"]`),layer=root.querySelector('[data-overlay-layer]'),panel=layer?.querySelector(`[data-slot="${name}-content"]`),backdrop=layer?.querySelector('[data-overlay-backdrop]');if(!layer||!panel)return{};backdrop.dataset.backdrop=root.dataset.backdrop||'opaque';if(name==='drawer')panel.dataset.placement=root.dataset.placement||'right';const overlay=AppUI.overlay.create({root,trigger,panel:layer,modal:true,dismissable:true,keyboardDismiss:root.dataset.keyboardDismiss!=='false',onOpen:()=>{layer.hidden=false},onClose:()=>{layer.hidden=true}});const originalOpen=overlay.open,originalClose=overlay.close;let closing=false;overlay.open=source=>{if(closing)return;layer.hidden=false;originalOpen(source)};overlay.close=focus=>{if(closing)return;if(matchMedia('(prefers-reduced-motion: reduce)').matches){originalClose(focus);return}closing=true;layer.dataset.state='closing';const animations=[panel.animate([{opacity:1,transform:getComputedStyle(panel).transform},{opacity:0,transform:`${getComputedStyle(panel).transform} scale(.96)`}],{duration:160,easing:'ease-in'}),backdrop.animate([{opacity:1},{opacity:0}],{duration:140,easing:'linear'})];Promise.allSettled(animations.map(animation=>animation.finished)).then(()=>{delete layer.dataset.state;closing=false;originalClose(focus)})};if(root.dataset.dismissable==='true')backdrop?.addEventListener('pointerdown',()=>overlay.close(true));layer.addEventListener('click',event=>{if(event.target.closest('[data-overlay-close],[data-modal-close],[data-drawer-close]'))overlay.close(true)});root.dataset.uiComponent=name;return overlay}
    AppUI.register('modal','[data-slot="modal"]',root=>registerModal('modal',root))
    AppUI.register('drawer','[data-slot="drawer"]',root=>registerModal('drawer',root))
    document.addEventListener('click',event=>{const trigger=event.target.closest('[data-overlay-target]');if(!trigger)return;const target=document.getElementById(trigger.dataset.overlayTarget);target?.appUI?.open(trigger)})

    AppUI.register('navbar','[data-slot="navbar"]',root=>{const button=root.querySelector('[data-navbar-toggle]'),menu=root.querySelector('[data-navbar-menu]');const setOpen=open=>{root.dataset.open=String(open);button?.setAttribute('aria-expanded',String(open));if(menu)menu.hidden=!open};button?.addEventListener('click',()=>setOpen(root.dataset.open!=='true'));setOpen(false);return{open:()=>setOpen(true),close:()=>setOpen(false)}})
    AppUI.register('table','[data-slot="table"]',root=>{root.querySelectorAll('tbody tr').forEach(row=>{AppUI.interaction(row);if(row.dataset.selectable==='true')row.addEventListener('click',()=>{if(root.dataset.selectionMode==='single')root.querySelectorAll('tbody tr').forEach(item=>item.dataset.selected='false');row.dataset.selected=String(row.dataset.selected!=='true');AppUI.emit(root,'table:change',{keys:[...root.querySelectorAll('tbody tr[data-selected="true"]')].map(item=>item.dataset.key)})})});return{getValue:()=>[...root.querySelectorAll('tbody tr[data-selected="true"]')].map(item=>item.dataset.key)}})
    AppUI.register('pagination','[data-slot="pagination"]',root=>{let page=Number(root.querySelector('[aria-current="page"]')?.dataset.page||1);const setValue=(value,notify=false)=>{page=Number(value);root.querySelectorAll('[data-page]').forEach(item=>{const active=Number(item.dataset.page)===page;item.dataset.active=String(active);active?item.setAttribute('aria-current','page'):item.removeAttribute('aria-current')});if(notify)AppUI.emit(root,'pagination:change',{page})};root.addEventListener('click',event=>{const item=event.target.closest('[data-page]');if(item&&!item.disabled)setValue(item.dataset.page,true)});return{getValue:()=>page,setValue,focus:()=>root.querySelector('[aria-current="page"]')?.focus()}})

    const toastRegions=new Map()
    AppUI.toast=(message,options={})=>{const placement=options.placement||'top-right';let region=toastRegions.get(placement);if(!region){region=document.createElement('div');region.className='app-toast-region';region.dataset.placement=placement;region.setAttribute('aria-live','polite');document.body.appendChild(region);toastRegions.set(placement,region)}const toast=document.createElement('div');toast.className='app-toast app-color-'+(options.color||'default');toast.setAttribute('role',options.color==='danger'?'alert':'status');toast.innerHTML=`<span>${options.icon||''}</span><span><span class="app-toast-title"></span><span class="app-toast-description"></span></span><button type="button" data-toast-close aria-label="Close">×</button>`;toast.querySelector('.app-toast-title').textContent=options.title||message;toast.querySelector('.app-toast-description').textContent=options.description||(!options.title?message:'');const close=()=>toast.remove();toast.querySelector('[data-toast-close]').addEventListener('click',close);region.appendChild(toast);if((options.timeout??5000)>0)setTimeout(close,options.timeout);return{close,element:toast}}
    AppUI.register('toast','[data-slot="toast"]',root=>{const close=()=>{root.hidden=true;AppUI.emit(root,'toast:close')},timeout=Number(root.dataset.timeout||0);if(timeout>0)setTimeout(close,timeout);return{close}})
})(window, document)
