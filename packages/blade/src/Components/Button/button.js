const selector = 'button.jds-button';
const blocked = ':disabled, [aria-disabled="true"], [data-pending="true"]';
const pointerPresses = new Map();
const pendingStates = new WeakMap();
const pendingAttributes = ['data-pending', 'aria-disabled', 'aria-busy'];

function syncPending(button) {
  const previous = pendingStates.get(button);
  if (button.isPending === Boolean(previous)) return;

  if (previous) {
    for (const [name, value, pendingValue] of previous) {
      if (button.getAttribute(name) !== pendingValue) continue;
      if (value === null) button.removeAttribute(name);
      else button.setAttribute(name, value);
    }
    pendingStates.set(button, null);
    return;
  }

  const changes = pendingAttributes.map(name => [name, button.getAttribute(name), 'true']);
  if (button.type === 'submit' || button.type === 'reset') {
    changes.push(['type', button.getAttribute('type'), 'button']);
  }
  pendingStates.set(button, changes);
  for (const [name, , pendingValue] of changes) button.setAttribute(name, pendingValue);
}

function initializeButton(button) {
  if (pendingStates.has(button)) return;

  const requestedPending = button.isPending;
  const initialType = button.getAttribute('data-jds-pending-type');
  if (initialType === null) {
    pendingStates.set(button, null);
  } else {
    const previous = pendingAttributes.map(name => [name, null, 'true']);
    previous.push(['type', initialType, 'button']);
    pendingStates.set(button, previous);
    button.removeAttribute('data-jds-pending-type');
  }

  Object.defineProperty(button, 'isPending', {
    configurable: true,
    get: () => button.hasAttribute('is-pending'),
    set: value => {
      button.toggleAttribute('is-pending', Boolean(value));
      syncPending(button);
    },
  });

  // Apply assignments made before the script or the observer initialized this element.
  if (requestedPending !== undefined) button.isPending = requestedPending;
  else syncPending(button);
}

document.querySelectorAll(selector).forEach(initializeButton);
new MutationObserver(records => {
  for (const record of records) {
    if (record.type === 'attributes' && record.target.matches(selector)) {
      initializeButton(record.target);
      syncPending(record.target);
    }
    for (const node of record.addedNodes) {
      if (!(node instanceof Element)) continue;
      if (node.matches(selector)) initializeButton(node);
      node.querySelectorAll(selector).forEach(initializeButton);
    }
  }
}).observe(document.documentElement, {subtree: true, childList: true, attributeFilter: ['is-pending']});

function findButton(target) {
  return target instanceof Element ? target.closest(selector) : null;
}

function setState(button, state, enabled) {
  const attribute = `data-${state}`;
  if (enabled) button.setAttribute(attribute, 'true');
  else button.removeAttribute(attribute);
}

function updateFocus(button) {
  const focused = document.activeElement === button;
  setState(button, 'focused', focused);
  setState(button, 'focus-visible', focused && button.matches(':focus-visible'));
}

document.addEventListener('focusin', (event) => {
  const button = findButton(event.target);
  if (button) updateFocus(button);
});

document.addEventListener('focusout', (event) => {
  const button = findButton(event.target);
  if (!button) return;
  setState(button, 'focused', false);
  setState(button, 'focus-visible', false);
  setState(button, 'pressed', false);
});

document.addEventListener('pointerover', (event) => {
  const button = findButton(event.target);
  if (!button || button.matches(blocked)) return;
  if (event.pointerType !== 'touch') setState(button, 'hovered', true);
  if (pointerPresses.get(event.pointerId) === button) setState(button, 'pressed', true);
});

document.addEventListener('pointerout', (event) => {
  const button = findButton(event.target);
  if (!button || findButton(event.relatedTarget) === button) return;
  setState(button, 'hovered', false);
  setState(button, 'pressed', false);
});

document.addEventListener('pointerdown', (event) => {
  const button = findButton(event.target);
  if (!button || event.button !== 0 || button.matches(blocked)) return;
  pointerPresses.set(event.pointerId, button);
  setState(button, 'pressed', true);
});

function endPointerPress(event) {
  const button = pointerPresses.get(event.pointerId);
  if (!button) return;
  pointerPresses.delete(event.pointerId);
  setState(button, 'pressed', false);
}

document.addEventListener('pointerup', endPointerPress);
document.addEventListener('pointercancel', endPointerPress);
window.addEventListener('blur', () => {
  for (const button of pointerPresses.values()) setState(button, 'pressed', false);
  pointerPresses.clear();
  const focusedButton = findButton(document.activeElement);
  if (focusedButton) setState(focusedButton, 'pressed', false);
});

document.addEventListener('keydown', (event) => {
  const button = findButton(event.target);
  if (!button) return;
  requestAnimationFrame(() => updateFocus(button));
  if ((event.key === ' ' || event.key === 'Enter') && !button.matches(blocked)) {
    setState(button, 'pressed', true);
  }
});

document.addEventListener('keyup', (event) => {
  const button = findButton(event.target);
  if (button && (event.key === ' ' || event.key === 'Enter')) {
    setState(button, 'pressed', false);
  }
});

document.addEventListener('click', (event) => {
  const button = findButton(event.target);
  if (!button) return;
  initializeButton(button);
  syncPending(button);
  if (!button.matches(blocked)) return;
  event.preventDefault();
  event.stopImmediatePropagation();
}, true);

const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

document.addEventListener('click', (event) => {
  const button = findButton(event.target);
  if (button?.dataset.jdsRipple !== 'true' || reducedMotion.matches) return;

  const rect = button.getBoundingClientRect();
  const x = event.detail ? event.clientX - rect.left : rect.width / 2;
  const y = event.detail ? event.clientY - rect.top : rect.height / 2;
  const size = 2 * Math.hypot(Math.max(x, rect.width - x), Math.max(y, rect.height - y));
  const ripple = document.createElement('span');
  ripple.className = 'jds-button__ripple';
  ripple.setAttribute('aria-hidden', 'true');
  ripple.style.setProperty('--jds-ripple-x', x + 'px');
  ripple.style.setProperty('--jds-ripple-y', y + 'px');
  ripple.style.setProperty('--jds-ripple-size', size + 'px');
  const cleanup = () => ripple.remove();
  ripple.addEventListener('animationend', cleanup, {once: true});
  ripple.addEventListener('animationcancel', cleanup, {once: true});
  button.append(ripple);
});
