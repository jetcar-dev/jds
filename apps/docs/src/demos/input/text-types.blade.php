<div class="text-input-types">
    <label>텍스트 <x-jds::input type="text" placeholder="이름" full-width /></label>
    <label>검색 <x-jds::input type="search" placeholder="검색어" full-width /></label>
    <label>전화번호 <x-jds::input type="tel" placeholder="010-1234-5678" full-width /></label>
    <label>웹 주소 <x-jds::input type="url" placeholder="https://example.com" full-width /></label>
</div>

<style>
    .text-input-types {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(100%, 10rem), 1fr));
        gap: .75rem;
        width: min(100%, 23rem);
    }

    .text-input-types label { display: grid; gap: .25rem; font-size: .875rem; line-height: 1.25rem; }
</style>
