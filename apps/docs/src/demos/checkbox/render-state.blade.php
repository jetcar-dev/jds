<x-jds::checkbox id="terms">
    <span id="terms-label">Accept terms</span>
    <x-slot:description id="terms-description">Please read and accept the terms</x-slot:description>
</x-jds::checkbox>

<script>
    const terms = document.getElementById('terms');

    terms.addEventListener('input', () => {
        document.getElementById('terms-label').textContent = terms.checked ? 'Terms accepted' : 'Accept terms';
        document.getElementById('terms-description').textContent = terms.checked
            ? 'Thank you for accepting'
            : 'Please read and accept the terms';
    });
</script>
