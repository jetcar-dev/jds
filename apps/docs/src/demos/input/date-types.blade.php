<div class="date-input-types">
    <label>날짜 <x-jds::input type="date" value="2026-11-19" full-width /></label>
    <label>월 <x-jds::input type="month" value="2026-11" full-width /></label>
    <label>주 <x-jds::input type="week" value="2026-W47" full-width /></label>
    <label>시간 <x-jds::input type="time" value="09:30" full-width /></label>
    <label>날짜와 시간 <x-jds::input type="datetime-local" value="2026-11-19T09:30" full-width /></label>
</div>

<style>
    .date-input-types {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(100%, 12rem), 1fr));
        gap: .75rem;
        width: min(100%, 27rem);
    }

    .date-input-types label { display: grid; gap: .25rem; min-width: 0; font-size: .875rem; line-height: 1.25rem; }
</style>
