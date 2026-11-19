(() => {
  const selector = 'input.jds-input[data-jds-mask]';
  const supportedTypes = new Set(['text', 'tel', 'search', 'url', 'password']);
  const characterRules = {9: /^[0-9]$/, a: /^[A-Za-z]$/, k: /^\p{Script=Hangul}$/u};

  function findInput(element) {
    return element instanceof HTMLInputElement && element.matches(selector) && supportedTypes.has(element.type)
      ? element
      : null;
  }

  function parsePattern(mask) {
    const slots = [];
    let literal = '';

    for (const [, escaped, symbol, repeat, separator] of mask.matchAll(/\\(.)|([9ak])(\+)?|(.)/gu)) {
      if (symbol) {
        slots.push({accept: characterRules[symbol], repeat: Boolean(repeat), prefix: literal});
        literal = '';
      } else {
        literal += escaped ?? separator;
      }
    }

    return {slots, suffix: literal};
  }

  function formatPattern(value, {slots, suffix}, limit) {
    let text = '';
    let offset = 0;
    const characters = [];

    for (const [index, slot] of slots.entries()) {
      const next = slots[index + 1];
      let matched = false;
      if (value.startsWith(slot.prefix, offset)) offset += slot.prefix.length;

      while (offset < value.length) {
        const character = String.fromCodePoint(value.codePointAt(offset));
        const accepted = slot.accept.test(character);

        // A repeat ends at the next slot's separator or its first matching character.
        if (matched && ((next?.prefix && value.startsWith(next.prefix, offset))
          || (!accepted && next?.accept.test(character))
          || (!next && suffix && value.slice(offset) === suffix))) break;

        if (accepted) {
          const prefix = matched ? '' : slot.prefix;
          const start = text.length + prefix.length;
          const end = start + character.length;
          if (end > limit) return {text, characters};

          text += prefix + character;
          characters.push({source: offset, start, end});
          matched = true;
        }

        offset += character.length;
        if (matched && !slot.repeat) break;
      }

      if (!matched) return {text, characters};
    }

    if (characters.length && text.length + suffix.length <= limit) text += suffix;
    return {text, characters};
  }

  function formatComma(value, limit) {
    // Every complete group after the first uses one extra character for its comma.
    const digits = Array.from(value.matchAll(/[0-9]/g)).slice(0, Math.floor((3 * limit + 3) / 4));
    let text = '';
    const characters = [];

    for (const [index, digit] of digits.entries()) {
      if (index && (digits.length - index) % 3 === 0) text += ',';
      characters.push({source: digit.index, start: text.length, end: text.length + 1});
      text += digit[0];
    }

    return {text, characters};
  }

  function formatValue(input, value) {
    const limit = input.maxLength < 0 ? Infinity : input.maxLength;
    const mask = input.dataset.jdsMask.normalize('NFC');
    return mask === 'comma' ? formatComma(value, limit) : formatPattern(value, parsePattern(mask), limit);
  }

  function syncValue(input) {
    const {value, selectionStart, selectionEnd, selectionDirection} = input;
    const {text, characters} = formatValue(input, value.normalize('NFC'));
    if (text === value) return;

    input.value = text;
    if (input.getRootNode().activeElement !== input || selectionStart === null || selectionEnd === null) return;

    const findOffset = position => {
      const sourceOffset = value.slice(0, position).normalize('NFC').length;
      return characters.find(character => character.source >= sourceOffset)?.start ?? text.length;
    };

    input.setSelectionRange(findOffset(selectionStart), findOffset(selectionEnd), selectionDirection);
  }

  function initialize(input) {
    if (!input) return;

    const {text: initialValue} = formatValue(input, input.defaultValue.normalize('NFC'));
    if (initialValue !== input.defaultValue) input.defaultValue = initialValue;
    syncValue(input);
  }

  function initializeWithin(root) {
    initialize(findInput(root));
    for (const input of root.querySelectorAll(selector)) initialize(findInput(input));
  }

  function handleInput(event) {
    const input = findInput(event.target);
    if (input && !event.isComposing) syncValue(input);
  }

  document.addEventListener('input', handleInput, true);
  document.addEventListener('change', handleInput, true);
  document.addEventListener('compositionend', handleInput, true);

  document.addEventListener('beforeinput', event => {
    const input = findInput(event.target);
    if (!input || !event.cancelable || event.isComposing) return;

    const backward = event.inputType === 'deleteContentBackward';
    if (!backward && event.inputType !== 'deleteContentForward') return;

    const {value, selectionStart: caret, selectionEnd} = input;
    if (caret === null || caret !== selectionEnd) return;

    const {text, characters} = formatValue(input, value);
    if (text !== value) return;
    const adjacent = backward
      ? characters.findLast(character => character.start < caret)
      : characters.find(character => character.end > caret);
    if (!adjacent || (backward ? adjacent.end === caret : adjacent.start === caret)) return;

    // Remove the editable character next to a separator, which the mask would otherwise reinsert.
    event.preventDefault();
    input.setRangeText('', backward ? adjacent.start : caret, backward ? caret : adjacent.end, 'start');
    input.dispatchEvent(new InputEvent('input', {bubbles: true, inputType: event.inputType}));
  });

  initializeWithin(document);
  new MutationObserver(records => {
    for (const record of records) {
      if (record.type === 'attributes') initialize(findInput(record.target));
      for (const node of record.addedNodes) {
        if (node instanceof Element) initializeWithin(node);
      }
    }
  }).observe(document.documentElement, {
    childList: true,
    subtree: true,
    attributeFilter: ['data-jds-mask', 'type', 'value', 'maxlength'],
  });
})();
