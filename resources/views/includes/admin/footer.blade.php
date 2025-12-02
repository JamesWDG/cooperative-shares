{{-- resources/views/includes/admin/footer.blade.php --}}

</div> {{-- .content-wrapper CLOSE --}}
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('assets/vendor/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/js/slick.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('assets/vendor/js/main.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

{{-- Sweet Alert & Loader --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

{{-- Validation Scripts Start --}}
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
{{-- Validation Scripts End --}}
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
    $('.sidebar-dropdown > a').on('click', function () {
        $(this).parent().toggleClass('active');
        $(this).next('.sidebar-submenu').slideToggle(200);
    });

    (function(){
    function uid(p='ed_'){ return p+Math.random().toString(36).slice(2)+Date.now().toString(36); }
    window._ck5 = window._ck5 || {};
    function initOne(el){
        if (!el.id) el.id = uid('editor_');
        if (el.dataset.ckInitialized) return;             // prevent double init
        el.dataset.ckInitialized = '1';
        ClassicEditor.create(el).then(ed => { window._ck5[el.id]=ed; });
    }
    function initAll(root){ (root.querySelectorAll?root:document).querySelectorAll('.editor').forEach(initOne); }
    document.addEventListener('DOMContentLoaded', initAll);

    // re-init for dynamically added nodes (MutationObserver)
    const mo = new MutationObserver((muts)=>{
        muts.forEach(m=>{
        m.addedNodes.forEach(n=>{
            if (!(n instanceof HTMLElement)) return;
            if (n.matches && n.matches('.editor')) initOne(n);
            if (n.querySelectorAll) n.querySelectorAll('.editor').forEach(initOne);
        });
        });
    });
    mo.observe(document.body, {childList:true,subtree:true});

    // before any form submit, sync editor data back into textareas
    document.addEventListener('submit', ()=>{
        for (const id in window._ck5){
        const ed = window._ck5[id], ta = document.getElementById(id);
        if (ed && ta) ta.value = ed.getData();
        }
    }, true);
    })();
    </script>
@stack('scripts')
</body>
</html>
