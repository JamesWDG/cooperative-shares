
    <h3 class="dashboard-hd fs-24 font-w600 mb-3" style="line-height:1.5;">

        <span style="color:#000000;">You’re updating the</span>

        {{-- SECTION NAME FIRST --}}
        <strong style="color:#A91125;">
            {{ ucfirst(str_replace(['_', '-'], ' ', $sectionType ?? 'Section')) }}
        </strong>

        <span style="color:#000000;">section of the</span>

        {{-- PAGE NAME SECOND --}}
        <strong style="color:#295568;">
            {{ $page ? ($page->title ?? ucfirst(str_replace('-', ' ', $page->page_key))) : 'CMS Page' }}
        </strong>

        <span style="color:#000000;">page</span>
    </h3>
