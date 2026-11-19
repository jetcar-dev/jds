<div style="display: grid; gap: .75rem; width: min(100%, 16rem);">
    <x-jds::input mask="aa-999" full-width aria-label="영문과 숫자" placeholder="AB-123" />
    <x-jds::input mask="kkk-999" full-width aria-label="한글과 숫자" placeholder="서울점-123" />
    <x-jds::input mask="k+-999" full-width aria-label="길이가 가변인 한글과 숫자" placeholder="강남지점-123" />
    <x-jds::input mask="\k\k\k-999" full-width aria-label="고정 접두어와 숫자" placeholder="kkk-123" />
</div>
