<div class="native-input-types">
    <label>강조 색상 <x-jds::input type="color" value="#0485f7" /></label>
    <label>볼륨 <x-jds::input type="range" min="0" max="100" value="50" /></label>
    <label>알림 받기 <x-jds::input type="checkbox" checked /></label>
    <fieldset>
        <legend>배송 방법</legend>
        <label><x-jds::input type="radio" name="delivery" value="standard" checked /> 일반</label>
        <label><x-jds::input type="radio" name="delivery" value="express" /> 빠른 배송</label>
    </fieldset>
    <label class="file-input-type">첨부 파일 <x-jds::input type="file" full-width /></label>
</div>

<style>
    .native-input-types { display: grid; gap: .75rem; width: min(100%, 22rem); font-size: .875rem; }
    .native-input-types > label:not(.file-input-type) { display: flex; gap: .5rem; align-items: center; }
    .native-input-types fieldset { margin: 0; }
    .native-input-types .jds-input:is([type="color"], [type="checkbox"], [type="radio"], [type="range"]) { padding: 0; }
    .native-input-types .file-input-type { display: grid; gap: .25rem; }
</style>
