(() => {
  const selector = 'input[data-jds-checkbox-input]';
  const nativeErrors = new WeakSet();
  const initialized = new WeakSet();

  function sync(input) {
    const content = input.parentElement;
    const field = content?.parentElement;
    if (!field?.classList.contains('jds-checkbox')) return;

    const invalid = input.hasAttribute('aria-invalid') && !['false', ''].includes(input.getAttribute('aria-invalid'));

    for (const element of [field, content]) {
      for (const [name, value] of Object.entries({
        selected: input.checked,
        indeterminate: input.indeterminate,
        disabled: input.disabled,
        required: input.required,
        readonly: input.getAttribute('aria-readonly') === 'true',
        invalid,
      })) {
        if (value) element.dataset[name] = 'true';
        else delete element.dataset[name];
      }
    }

    const error = field.querySelector(':scope > [data-slot="field-error"]');
    if (error?.id) {
      const descriptions = new Set(input.getAttribute('aria-describedby')?.split(/\s+/).filter(Boolean));
      if (invalid) descriptions.add(error.id);
      else descriptions.delete(error.id);
      if (descriptions.size) input.setAttribute('aria-describedby', [...descriptions].join(' '));
      else input.removeAttribute('aria-describedby');
    }
  }

  function initialize(input) {
    initialized.add(input);
    input.indeterminate = input.dataset.jdsIndeterminate === 'true';
    sync(input);
  }

  function visit(node) {
    if (!(node instanceof Element)) return;
    if (node.matches(selector) && !initialized.has(node)) initialize(node);
    node.querySelectorAll(selector).forEach(input => {
      if (!initialized.has(input)) initialize(input);
    });
  }

  document.addEventListener('click', event => {
    if (event.target.matches?.(`${selector}[aria-readonly="true"]`)) event.preventDefault();
  }, true);

  document.addEventListener('invalid', event => {
    const input = event.target;
    if (!input.matches?.(selector)) return;
    if (!input.hasAttribute('aria-invalid')) {
      nativeErrors.add(input);
      input.setAttribute('aria-invalid', 'true');
    }
    sync(input);
  }, true);

  function handleChange(event) {
    const input = event.target;
    if (!input.matches?.(selector)) return;
    if (nativeErrors.has(input) && input.validity.valid) {
      input.removeAttribute('aria-invalid');
      nativeErrors.delete(input);
    }
    sync(input);
  }

  document.addEventListener('input', handleChange, true);
  document.addEventListener('change', handleChange, true);

  document.addEventListener('reset', event => {
    if (!(event.target instanceof HTMLFormElement)) return;
    queueMicrotask(() => {
      if (event.defaultPrevented) return;
      for (const input of event.target.elements) {
        if (!input.matches(selector)) continue;
        if (nativeErrors.delete(input)) input.removeAttribute('aria-invalid');
        initialize(input);
      }
    });
  });

  visit(document.documentElement);
  new MutationObserver(records => {
    for (const record of records) {
      if (record.type === 'attributes') {
        if (record.target.matches(selector)) initialize(record.target);
      } else {
        record.addedNodes.forEach(visit);
      }
    }
  }).observe(document.documentElement, {
    childList: true,
    subtree: true,
    attributes: true,
    attributeFilter: ['data-jds-indeterminate'],
  });
})();
