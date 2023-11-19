<?php use App\Framework\Facades\App;
use App\Framework\Http\View; ?>



<div class="modal" tabindex="-1" role="dialog" id="model-template">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@title</h5>
                <button type="button" class="close" aria-label="Close" onclick="Editor.instance.models['@id'].toggle()" id="model-close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @body
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="@actionCallback; Editor.instance.models['@id'].toggle()">@actionButton</button>
                <button type="button" class="btn btn-secondary" onclick="Editor.instance.models['@id'].toggle()">@cancelButton</button>
            </div>
        </div>
    </div>
</div>

<div class="col py-3" style="margin-top: 120px" id="page-container">
    <page size="A4" id="document">
        <?php echo View::Bag()["document"]["code"]?>
    </page>
</div>


<script src="<?php echo App::Env("AWS_CLOUD_FRONT")?>/static/javascript/Sway/Sway.js"></script>
<script src="<?php echo App::Env("AWS_CLOUD_FRONT")?>/static/javascript/Sway/UIComponent.js"></script>
<script src="<?php echo App::Env("AWS_CLOUD_FRONT")?>/static/javascript/VariableManager.js"></script>
<script src="<?php echo App::Env("AWS_CLOUD_FRONT")?>/static/javascript/Components.js"></script>
<script src="<?php echo App::Env("AWS_CLOUD_FRONT")?>/static/javascript/Editor.js"></script>
<script src="<?php echo App::Env("AWS_CLOUD_FRONT")?>/static/javascript/PropertyEditor.js"></script>