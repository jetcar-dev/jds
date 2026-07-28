@props(['doc'])

<div class="jds-docs-reference">
    <div class="jds-docs-reference-block">
        <div class="jds-docs-reference-heading">구성 요소</div>
        <div class="jds-docs-component-index">
            @foreach($doc['components'] as $component)
                <code>&lt;x-{{ $component['name'] }}&gt;</code>
            @endforeach
        </div>
    </div>

    @foreach($doc['components'] as $component)
        <section class="jds-docs-api-component" aria-labelledby="api-{{ $component['name'] }}">
            <div class="jds-docs-api-title" id="api-{{ $component['name'] }}">
                <code>&lt;x-{{ $component['name'] }}&gt;</code>
            </div>

            @if(count($component['props']))
                <div class="jds-docs-table-scroll">
                    <table class="jds-docs-table">
                        <thead>
                        <tr>
                            <th>속성</th>
                            <th>타입</th>
                            <th>기본값</th>
                            <th>설명</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($component['props'] as $prop)
                            <tr>
                                <td><code>{{ $prop['name'] }}</code></td>
                                <td><code>{{ $prop['type'] }}</code></td>
                                <td><code>{{ $prop['default'] }}</code></td>
                                <td>{{ $prop['description'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="jds-docs-reference-copy">전용 속성이 없습니다. <code>class</code>, <code>id</code>, <code>aria-*</code> 등 일반 HTML 속성은 그대로 전달할 수 있습니다.</p>
            @endif

            <div class="jds-docs-slot-list">
                <span class="jds-docs-slot-label">슬롯</span>
                @forelse($component['slots'] as $slot)
                    <div><code>{{ $slot['name'] }}</code><span>{{ $slot['description'] }}</span></div>
                @empty
                    <span>기본 슬롯 없이 단독으로 사용하는 컴포넌트입니다.</span>
                @endforelse
            </div>
        </section>
    @endforeach
</div>
