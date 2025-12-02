<h3 class="dashboard-hd fs-24 font-w600 me-auto mb-3 pe-3" style="line-height:1.4;">
    <span style="color:#000000;">You’re updating the</span>
    <strong style="color:#295568;">
        {{ $title ?? 'CMS Page' }}
    </strong>
    <span style="color:#000000;">Page Sections</span>

    @if(!empty($subtitle))
        <div style="font-size: 14px; color:#777; margin-top:4px;">
            {{ $subtitle }}
        </div>
    @endif
</h3>
