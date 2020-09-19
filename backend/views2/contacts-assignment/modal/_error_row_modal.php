<div class="modal fade in" id="errorRowModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Cảnh báo dữ liệu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="resultErrorRowImport">
                    <div class="text-center">
                        <div class="spinner-border text-success m-2" role="status"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<script id="error-template" type="text/x-handlebars-template">
    {{#each this}}
        <p>{{this}}</p>
    {{/each}}
</script>