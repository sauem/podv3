<?php

use backend\models\ContactsModel;
use kartik\form\ActiveForm;

?>
    <div class="modal fade" id="modalViewFormInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Danh sách mẫu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th>loại sản phẩm</th>
                            <th>Doanh thu</th>
                            <th width="35%">Nội dung</th>
                            <th>Sản phẩm</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody id="resultApplyInfo">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script type="text/x-handlebars-template" id="template-view-info">
        {{#each this}}
        <tr>

            <td>{{category}}</td>
            <td>{{money revenue}}</td>
            <td>{{content}}</td>
            <td>
                {{#each skus}}
                <small>{{this}}</small><br>
                {{/each}}
            </td>
            <td>
                <button data-key="{{@index}}" type="button" class="applyInfo btn btn-sm btn-info">Áp dụng</button>
            </td>
        </tr>
        {{/each }}
    </script>
<?php
