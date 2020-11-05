<?php

?>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Báo cáo kho</h4>
            <div class="row w-50">
                <div class="col-md-6">
                    <select title="Sản phẩm"
                            data-actions-box="true"
                            data-live-search="true"
                            name="sale" class="selectpicker mr-3"
                            multiple data-selected-text-format="count"
                            data-style="btn-light">

                    </select>
                </div>
                <div class="col-md-6">
                    <select title="Loại sản phẩm"
                            data-actions-box="true"
                            data-live-search="true"
                            name="sale" class="selectpicker"
                            multiple data-selected-text-format="count"
                            data-style="btn-light">

                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="result" class="table table-borderless">
                <thead>
                <tr>
                    <th>Đối tác</th>
                    <th>Sản phẩm</th>
                    <th>Loại sản phẩm</th>
                    <th>Tình trạng</th>
                    <th>Số lượng</th>
                </tr>
                </thead>
                <tbody id="warehouse-result">
                <tr>
                    <td colspan="5" class="text-center">
                        <div class="spinner-grow text-danger" role="status"></div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script id="warehouse-template" type="text/x-handlebars-template">
        {{#each this}}
        <tr>
            {{#each this}}
            <td>{{this}}</td>
            {{/each}}
        </tr>
        {{/each}}
    </script>
<?php
$js = <<<JS
    $(document).ready(function() {
        let html = $("#warehouse-template").html();
        let template = Handlebars.compile(html);
      getWarehouse("$partner").then(res => {
          let result = template(res);
          if(!res){
              result = "Dữ liệu rỗng!"
          }
          $("#warehouse-result").html(result);
          initDataTable('#result');
      }).catch(error =>{ 
          $("#warehouse-result").html('<tr><td class="text-center" colspan="5">Dữ liệu trống!</td></tr>');
      })
    });
JS;
$this->registerJs($js);