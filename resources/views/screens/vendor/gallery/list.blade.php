@extends('layouts.vendor.app')
@section('title', 'Gallery')

@push('styles')
<style>
    :root { --gallery-card-radius: 12px; }

    .toolbar .form-select,
    .toolbar .form-control {
        min-width: 160px;
    }

    /* ========== GRID ========== */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 16px;
    }
    @media (max-width: 1400px) { .gallery-grid { grid-template-columns: repeat(4, 1fr); } }
    @media (max-width: 992px)  { .gallery-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 768px)  { .gallery-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px)  { .gallery-grid { grid-template-columns: 1fr; } }

    /* ========== CARD ========== */
    .gallery-card {
        border: 1px solid #e5e7eb;
        border-radius: var(--gallery-card-radius);
        overflow: hidden;
        background: #fff;
        box-shadow: 0 1px 2px rgba(0,0,0,.04);
        position: relative;
        transition: transform .12s ease, box-shadow .12s ease;
        cursor: pointer;
    }
    .gallery-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,.08);
    }
    .gallery-card.selected {
        outline: 3px solid #2563eb;
    }

    /* ========== THUMBNAIL ========== */
    .gallery-thumb-w {
        position: relative;
        aspect-ratio: 4 / 3; /* zyada visible / rectangular */
        background: #f6f7f9;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: visible !important; /* in case theme hides inside */
    }
    .gallery-thumb {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
        width: 100%;
        height: 100%;
        border-bottom: 1px solid #e5e7eb;
    }

    /* ========== INFO AREA ========== */
    .gallery-info {
        padding: 10px 12px;
        border-top: 1px solid #f0f1f3;
        display: flex;
        flex-direction: column;
        gap: 6px;
        background: #ffffff;
        /* force visible – some themes hide on non-hover */
        opacity: 1 !important;
        visibility: visible !important;
        position: static !important;
        transform: none !important;
        height: auto !important;
    }

    .gallery-info .small {
        font-size: 12px;
        color: #374151;
    }

    .actions-row .btn {
        min-width: 140px;
    }

    .load-more-wrap {
        display: flex;
        justify-content: center;
        margin-top: 16px;
    }

    /* ========== BUTTON COLORS ========== */
    .copy-url-btn {
        background: #295568 !important;   /* Dark theme-primary color */
        border: none !important;
        color: #fff !important;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .copy-url-btn:hover {
        background: #1f4453 !important;
        color: #fff !important;
    }

    .delete-btn {
        background: #dc3545 !important; /* Red */
        border: none !important;
        color: #fff !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .delete-btn:hover {
        background: #bb2d3b !important;
    }

    /* ========== HARD OVERRIDES (no hover show/hide) ========== */
    .gallery-card .gallery-info,
    .gallery-card .gallery-info .btn,
    .gallery-card .gallery-info button,
    .gallery-card .gallery-info .d-flex {
        opacity: 1 !important;
        visibility: visible !important;
        display: flex !important;
    }

    /* So buttons row stays horizontal flex but inner buttons stay inline-flex */
    .gallery-card .gallery-info .d-flex .btn {
        display: inline-flex !important;
    }

    /* In case theme has hover-based rules, override them too */
    .gallery-card:hover .gallery-info,
    .gallery-card:hover .gallery-info .btn,
    .gallery-card:hover .gallery-info button {
        opacity: 1 !important;
        visibility: visible !important;
    }
</style>
@endpush


@section('section')
    <section class="main-content-area">

        <div class="profile-info-wrapper">
            <div class="row">
                <div class="col-12">
                    <div class="profile-card shadow-sm p-3">

                        {{-- Header --}}
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <h5 class="Pro-info-hd mb-0">
                                <strong>Image Gallery</strong>
                            </h5>
                            <a class="btn btn-primary" href="{{ route('vendor.gallery.create') }}">
                                <i class="fa-solid fa-upload"></i> &nbsp; Upload New
                            </a>
                        </div>

                        {{-- Search & Sort --}}
                        <div class="row g-3 align-items-end toolbar mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Search</label>
                                <input id="search" type="text" class="form-control" placeholder="Search by filename…">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Sort</label>
                                <select id="sortBy" class="form-select">
                                    <option value="newest">Newest First</option>
                                    <option value="oldest">Oldest First</option>
                                    <option value="az">Name A–Z</option>
                                    <option value="za">Name Z–A</option>
                                </select>
                            </div>
                            <div class="col-md-3 text-md-end">
                                <div class="text-muted small" id="countInfo"></div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-between align-items-center my-3 actions-row flex-wrap gap-2">
                            <div class="d-flex gap-2">

                                <button id="selectAll" type="button"
                                    class="btn"
                                    style="background:#295568; color:white; border:1px solid #ccc; font-weight:600;">
                                    Select All
                                </button>

                                <button id="bulkDelete" type="button"
                                    class="btn d-none"
                                    style="background:light-gray; color:#b30000; border:1px solid #ffcccc; font-weight:600;">
                                    Delete Selected
                                </button>

                            </div>
                        </div>


                        {{-- GRID VIEW --}}
                        <div id="gridView" class="gallery-grid"></div>

                        {{-- LOAD MORE --}}
                        <div class="load-more-wrap">
                            <button id="loadMore" type="button" class="btn btn-outline-secondary d-none">
                                Load more
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
(function($){
  // Blade se seed data (controller me $galleryData prepare hoga)
  let RAW = @json($galleryData ?? []);

  if (!Array.isArray(RAW)) {
      RAW = [];
  }

  let LIST = [];
  let SELECTED = new Set();
  let PAGE = 1, PER_PAGE = 30;

  function toast(msg, icon='success') {
    Swal.fire({ toast:true, position:'top-end', showConfirmButton:false, timer:1500, icon, title: msg });
  }

  function buildCard(row){
    const url = row.url || '';
    const name = row.image || '';
    const selected = SELECTED.has(row.id) ? 'selected' : '';

    return `
      <div class="gallery-card ${selected}"
           data-id="${row.id}"
           data-url="${url}"
           aria-selected="${SELECTED.has(row.id)}">

        <div class="gallery-thumb-w">
          <img loading="lazy"
               src="${url}"
               alt="${name}"
               class="gallery-thumb">
        </div>

        <div class="gallery-info">

          <div class="small text-truncate" title="${name}">
            ${name}
          </div>

          <div class="d-flex gap-2">
            <button type="button"
                    class="btn btn-dark btn-sm flex-grow-1 copy-url"
                    data-url="${url}">
              Copy URL
            </button>

            <button type="button"
                    class="btn btn-dark btn-sm flex-grow-0 single-delete"
                    data-id="${row.id}">
              <i class="fa-solid fa-trash"></i>
            </button>
          </div>

        </div>
      </div>`;
  }

  function applyFilters(){
    const q = $('#search').val().trim().toLowerCase();

    LIST = RAW.filter(r=>{
      const hay = (r.image||'') + ' ' + (r.url||'');
      return !q || hay.toLowerCase().includes(q);
    });

    const sort = $('#sortBy').val();
    LIST.sort((a,b)=>{
      if(sort === 'newest'){ return (new Date(b.created_at||0)) - (new Date(a.created_at||0)); }
      if(sort === 'oldest'){ return (new Date(a.created_at||0)) - (new Date(b.created_at||0)); }
      if(sort === 'az'){ return (a.image||'').localeCompare(b.image||''); }
      if(sort === 'za'){ return (b.image||'').localeCompare(a.image||''); }
      return 0;
    });

    PAGE = 1;
    render();
  }

  function visibleSlice(){ return LIST.slice(0, PAGE * PER_PAGE); }

  function updateActionButtons(){
    const hasSelection = SELECTED.size > 0;
    $('#bulkDelete').toggleClass('d-none', !hasSelection);

    const vis = visibleSlice();
    const allVisibleSelected = vis.length > 0 && vis.every(r => SELECTED.has(r.id));
    $('#selectAll').text(allVisibleSelected ? 'Deselect All' : 'Select All');

    $('#countInfo').text(`${LIST.length} item(s)${hasSelection ? ' • ' + SELECTED.size + ' selected' : ''}`);
  }

  function render(){
    const slice = visibleSlice();
    $('#gridView').html(slice.map(buildCard).join(''));
    $('#loadMore').toggleClass('d-none', slice.length >= LIST.length);
    updateActionButtons();
  }

  $('#search').on('input', applyFilters);
  $('#sortBy').on('change', applyFilters);
  $('#loadMore').on('click', function(){ PAGE++; render(); });

  $(document).on('click', '.gallery-card', function(e){
    if ($(e.target).closest('.copy-url, .single-delete').length) return;
    const id = $(this).data('id');
    if(SELECTED.has(id)) SELECTED.delete(id); else SELECTED.add(id);
    render();
  });

  $('#selectAll').on('click', function(){
    const vis = visibleSlice();
    const allVisibleSelected = vis.length > 0 && vis.every(r => SELECTED.has(r.id));
    if(allVisibleSelected){ vis.forEach(r => SELECTED.delete(r.id)); }
    else{ vis.forEach(r => SELECTED.add(r.id)); }
    render();
  });

  // Single delete button
  $(document).on('click', '.single-delete', function(e){
    e.stopPropagation();
    const id = $(this).data('id');
    if (!id) return;

    Swal.fire({
      title: 'Delete this image?',
      text: 'It will be permanently removed.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete',
    }).then((res)=>{
      if(!res.isConfirmed) return;
      deleteGalleryItems([id]);
    });
  });

  // Bulk delete
  $('#bulkDelete').on('click', function(){
    if(SELECTED.size === 0) return;

    Swal.fire({
      title: `Delete ${SELECTED.size} selected image(s)?`,
      text: 'They will be permanently removed.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete',
    }).then((res)=>{
      if(!res.isConfirmed) return;
      deleteGalleryItems(Array.from(SELECTED));
    });
  });

  function deleteGalleryItems(ids){
    if (!ids.length) return;

    $.LoadingOverlay("show");

    let ok = 0, fail = 0;
    const baseUrl = `{{ url('vendor/gallery') }}`;

    const promises = ids.map(id => {
      return $.ajax({
        url: baseUrl + '/' + id,
        type: 'POST',
        data: {
          _method: 'DELETE',
          _token: `{{ csrf_token() }}`,
        }
      }).then(function(resp){
        if (resp.success) {
          ok++;
          RAW = RAW.filter(x => x.id !== id);
          SELECTED.delete(id);
        } else {
          fail++;
        }
      }).catch(function(){
        fail++;
      });
    });

    Promise.all(promises).then(()=>{
      $.LoadingOverlay("hide");
      toast(`Deleted: ${ok}${fail ? ', Failed: ' + fail : ''}`, fail ? 'warning' : 'success');
      applyFilters();
    });
  }

  // Initial render
  $(document).ready(function(){
    applyFilters();
  });

})(jQuery);
</script>

<script>
// Copy URL handler
$(document).on('click', '.copy-url', async function(e){
  e.stopPropagation();
  const $btn = $(this);
  const url  = ($btn.data('url') || '').toString().trim();
  if (!url) {
    Swal.fire({toast:true, position:'top-end', showConfirmButton:false, timer:1500, icon:'error', title:'No URL to copy'});
    return;
  }

  const blink = () => {
    const original = $btn.html();
    $btn.prop('disabled', true)
        .removeClass('btn-outline-secondary').addClass('btn-success')
        .text('Copied!');
    setTimeout(()=>{
      $btn.prop('disabled', false)
          .removeClass('btn-success').addClass('btn-outline-secondary')
          .html(original);
    }, 900);
  };

  try {
    if (window.isSecureContext && navigator.clipboard?.writeText) {
      await navigator.clipboard.writeText(url);
      blink();
      Swal.fire({toast:true, position:'top-end', showConfirmButton:false, timer:1500, icon:'success', title:'URL copied'});
      return;
    }
  } catch {}

  try {
    const ta = document.createElement('textarea');
    ta.value = url; ta.setAttribute('readonly','');
    ta.style.position = 'fixed'; ta.style.left = '-9999px';
    document.body.appendChild(ta);
    ta.focus(); ta.select();
    const ok = document.execCommand('copy');
    document.body.removeChild(ta);
    if (ok) {
      blink();
      Swal.fire({toast:true, position:'top-end', showConfirmButton:false, timer:1500, icon:'success', title:'URL copied'});
      return;
    }
  } catch {}

  Swal.fire({title:'Press Ctrl+C (or ⌘+C) to copy', text:url, icon:'info', confirmButtonText:'Done'});
});
</script>
@endpush
