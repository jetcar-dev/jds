<x-jds::button id="press-demo" data-custom="bar">Press me</x-jds::button>

<script>
    const action = document.getElementById('press-demo');
    new MutationObserver(() => {
        action.dataset.custom = action.dataset.pressed === 'true' ? 'pressed' : 'bar';
    }).observe(action, {attributes: true, attributeFilter: ['data-pressed']});
</script>
