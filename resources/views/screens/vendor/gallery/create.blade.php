@extends('layouts.vendor.app')
@section('title', 'Upload Gallery Images')

@push('styles')
<style>
  .preview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 12px;
    max-height: 520px;
    overflow: auto;
    padding-right: 4px;
  }
  .preview-card {
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 8px;
    background: #fff;
    display: flex;
    flex-direction: column;
    gap: 8px;
    align-items: center;
    width: 100%;
    box-sizing: border-box;
  }
  .preview-img {
    width: 100%;
    aspect-ratio: 1 / 1;
    object-fit: cover;
    border: 1px solid #ddd;
    border-radius: 10px;
  }
  .preview-name {
    width: 100%;
    max-width: 100%;
    font-size: 12px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    text-align: center;
  }
  @media (max-width: 480px){
    .preview-grid { gap: 10px; }
  }
</style>
@endpush

@section('section')
    <section class="main-content-area">

        <div class="profile-info-wrapper">
            <div class="row">
                <div class="col-12">
                    {{-- Same card structure as profile page --}}
                    <div class="profile-card shadow-sm p-3">

                        {{-- Header row --}}
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <h5 class="Pro-info-hd mb-0">
                                <strong>Upload Gallery Images</strong>
                            </h5>

                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('vendor.gallery') }}" class="btn btn-dark">
                                    Back to Gallery
                                </a>
                                <button class="btn btn-dark create-btn" type="button">
                                    Upload
                                </button>
                            </div>
                        </div>

                        {{-- Form --}}
                        <form action="{{ route('vendor.gallery.store') }}"
                              class="form-submit"
                              method="POST"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <div class="row g-4 align-items-start">
                                    {{-- Left: file input --}}
                                    <div class="col-md-6 col-12">
                                        <div class="form-group mb-2">
                                            <label>
                                                Choose Images
                                                <small class="text-muted">
                                                    (You can upload multiple images)
                                                </small>
                                            </label>
                                            <div>:</div>
                                            <input type="file"
                                                   name="images[]"
                                                   id="images"
                                                   accept="image/*"
                                                   class="input-area form-control"
                                                   multiple>
                                            <span class="text-danger"></span>
                                        </div>

                                        <div class="mt-2 text-muted small">
                                            <span id="fileCount">No files selected.</span>
                                        </div>
                                        <div class="mt-1 text-muted small">
                                            Max size per image: <strong>5MB</strong>
                                        </div>
                                    </div>

                                    {{-- Right: previews --}}
                                    <div class="col-md-6 col-12">
                                        <div id="previewGrid" class="preview-grid"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Submit button bottom (optional second CTAs) --}}
                            <!-- <div class="mt-3">
                                <button type="button" class="btn-dark create-btn">
                                    Upload Images
                                </button>
                            </div> -->
                        </form>

                    </div> {{-- /profile-card --}}
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
  const dt = new DataTransfer();

  function refreshFileInput(){
    const input = document.getElementById('images');
    input.files = dt.files;
    const count = dt.files.length;
    $('#fileCount').text(count ? count + ' file(s) selected.' : 'No files selected.');
  }

  function addPreview(file){
    const url = URL.createObjectURL(file);
    const $card = $(`
      <div class="preview-card" data-name="${file.name}" data-size="${file.size}">
        <img class="preview-img" src="${url}" alt="${file.name}">
        <div class="preview-name" title="${file.name}">${file.name}</div>
        <button type="button" class="btn btn-sm btn-danger remove-preview">Remove</button>
      </div>
    `);
    $('#previewGrid').append($card);
    $card.find('img')[0].onload = () => URL.revokeObjectURL(url);
  }

  function rebuildPreviews(){
    $('#previewGrid').empty();
    for (let i = 0; i < dt.files.length; i++) {
      addPreview(dt.files[i]);
    }
    refreshFileInput();
  }

  $('#images').on('change', function(e){
    const files = e.target.files;
    if (!files || !files.length) return;

    for (let i = 0; i < files.length; i++) {
      const f = files[i];
      if (f.size > 5 * 1024 * 1024) {
        Swal.fire('Too large', `${f.name} is over 5MB. Skipped.`, 'warning');
        continue;
      }
      dt.items.add(f);
    }
    this.value = '';
    rebuildPreviews();
  });

  $(document).on('click', '.remove-preview', function(){
    const $card = $(this).closest('.preview-card');
    const name = $card.data('name');
    const size = parseInt($card.data('size'), 10);

    for (let i = 0; i < dt.items.length; i++) {
      const f = dt.items[i].getAsFile();
      if (f.name === name && f.size === size) {
        dt.items.remove(i);
        break;
      }
    }
    $card.remove();
    refreshFileInput();
  });

  // Both "Upload" buttons share same class .create-btn
  $('.create-btn').on('click', function(e){
    e.preventDefault();
    const $form = $('.form-submit');

    $('.text-danger').text('');
    if (dt.files.length === 0) {
      Swal.fire('No files', 'Please choose at least one image.', 'info');
      return;
    }

    $.LoadingOverlay('show');

    const fd = new FormData();
    fd.append('_token', `{{ csrf_token() }}`);
    for (let i = 0; i < dt.files.length; i++) {
      fd.append('images[]', dt.files[i]);
    }

    $.ajax({
      url: $form.attr('action'),
      type: 'POST',
      data: fd,
      contentType: false,
      processData: false,
      success: function(resp){
        $.LoadingOverlay('hide');

        if (resp.success) {
          Swal.fire('Success', resp.message || 'Uploaded.', 'success').then(()=>{
            const redirect = resp.redirect_url || `{{ route('vendor.gallery') }}`;
            window.location.href = redirect;
          });
        } else {
          Swal.fire('Error', resp.message || 'Upload failed.', 'error');
        }
      },
      error: function(xhr){
        $.LoadingOverlay('hide');

        if(xhr?.responseJSON?.errors){
          const errs = xhr.responseJSON.errors;
          const msg  = errs['images'] || errs['images.*'] || 'Upload failed.';
          Swal.fire('Error', Array.isArray(msg) ? msg.join('<br>') : msg, 'error');
        }else{
          Swal.fire('Error', xhr?.responseJSON?.message || 'Upload failed.', 'error');
        }
      }
    });
  });

  $(document).on('input change', 'input, select, textarea', function(){
    $(this).next('span.text-danger').text('');
  });

})(jQuery);
</script>
@endpush
