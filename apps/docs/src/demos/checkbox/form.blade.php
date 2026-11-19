<form id="preferences" style="display: grid; gap: .75rem;">
    <x-jds::checkbox name="notifications">Enable notifications</x-jds::checkbox>
    <x-jds::checkbox name="newsletter" checked>Subscribe to newsletter</x-jds::checkbox>
    <x-jds::checkbox name="marketing">Receive marketing updates</x-jds::checkbox>
    <x-jds::button size="sm" type="submit" style="margin-top: 1rem;">Submit</x-jds::button>
    <output aria-live="polite"></output>
</form>

<script>
    const form = document.getElementById('preferences');

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        const choices = [...new FormData(form)].map(([name, value]) => `${name}: ${value}`);
        form.querySelector('output').textContent = choices.join(', ') || 'No preferences selected';
    });
</script>
