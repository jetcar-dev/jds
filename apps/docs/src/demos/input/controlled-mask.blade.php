<div style="display: grid; justify-items: start; gap: .75rem; width: min(100%, 20rem);">
    <x-jds::input id="controlled-amount" mask="comma" aria-label="금액" placeholder="1,000,000" full-width />
    <button
        id="set-example-amount"
        type="button"
        style="padding: .5rem .75rem; border: 0; border-radius: var(--field-radius); background: var(--default); color: var(--foreground); font: inherit; cursor: pointer;"
    >예제 금액 입력</button>
    <output id="current-amount" for="controlled-amount">현재 값 없음</output>
</div>

<script>
    const amount = document.getElementById('controlled-amount');
    const currentAmount = document.getElementById('current-amount');
    const setExampleAmount = document.getElementById('set-example-amount');

    amount.addEventListener('input', () => {
        currentAmount.textContent = amount.value ? `현재 값 ${amount.value}` : '현재 값 없음';
    });

    setExampleAmount.addEventListener('click', () => {
        amount.value = '1000000';
        amount.dispatchEvent(new Event('input', { bubbles: true }));
    });
</script>
