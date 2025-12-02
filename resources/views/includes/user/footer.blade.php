<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('assets/user/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/user/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/user/js/slick.min.js') }}"></script>
<script src="{{ asset('assets/user/js/main.js') }}"></script>

{{-- Sweet Alert & Loader --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

{{-- Validation Scripts Start --}}
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
{{-- Validation Scripts End --}}

@stack('scripts')
    </div>
    </main>
</body>
</html>
