{{-- Part of phoenix project. --}}

<div class="modal fade" id="batch-modal" tabindex="-1" role="dialog" aria-labelledby="batch-modal-title">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="batch-modal-title">
                    {{ \Windwalker\Core\Language\Translator::translate('phoenix.batch.modal.title') }}
                </h4>
            </div>
            <div class="modal-body">
                <p>
                    {{ \Windwalker\Core\Language\Translator::translate('phoenix.batch.modal.desc') }}
                </p>
                <hr />
                <div class="form-horizontal">
                    {{ \Windwalker\Core\Frontend\Bootstrap::renderFields($batchForm->getFields()) }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    {{ \Windwalker\Core\Language\Translator::translate('phoenix.close') }}
                </button>
                <button type="button" class="btn btn-info" onclick="Phoenix.validateChecked();Phoenix.patch()">
                    {{ \Windwalker\Core\Language\Translator::translate('phoenix.move') }}
                </button>
                <button type="button" class="btn btn-primary" onclick="Phoenix.validateChecked();Phoenix.post()">
                    {{ \Windwalker\Core\Language\Translator::translate('phoenix.copy') }}
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
