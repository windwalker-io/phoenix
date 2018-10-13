'use strict';

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/**
 * Part of afb project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

// Custom file input
$(function () {
  // Polyfill sweetalert
  var swal = window.swal || function swal(title) {
    var message = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;

    alert(title + ' / ' + message);
  };

  Phoenix.plugin('dragFile', function () {
    function _class($element, $options) {
      _classCallCheck(this, _class);

      $element.on('change', function (e) {
        var $self = $(e.currentTarget);

        //get the file name
        var files = $self[0].files;
        var limit = $self.attr('data-max-files');
        var sizeLimit = $self.attr('data-max-size');
        var placeholder = $self.attr('placeholder');
        var acceptExtensions = ($self.attr('data-accepted') || '').split(',').map(function (v) {
          return v.trim();
        }).filter(function (v) {
          return v.length > 0;
        });
        var text = void 0;

        if (!placeholder) {
          if ($self.attr('multiple')) {
            placeholder = Phoenix.__('phoenix.form.field.drag.file.placeholder.multiple');
          } else {
            placeholder = Phoenix.__('phoenix.form.field.drag.file.placeholder.single');
          }
        }

        // Files limit
        if (limit && files.length > limit) {
          swal(Phoenix.__('phoenix.form.field.drag.file.message.max.files', limit), '', 'warning');
          e.preventDefault();
          return;
        }

        // Files size
        var fileSize = 0;
        Array.prototype.forEach.call(files, function (file) {
          if (acceptExtensions.length && acceptExtensions.indexOf(file.name.split('.').pop().toLowerCase()) === -1) {
            swal(Phoenix.__('phoenix.form.field.drag.file.message.unaccepted.files'), Phoenix.__('phoenix.form.field.drag.file.message.unaccepted.files.desc', acceptExtensions.join(', ')), 'warning');
            throw new Error('Not accepted file ext');
          }

          fileSize += file.size;
        });

        if (sizeLimit && fileSize / 1024 / 1024 > sizeLimit) {
          swal(Phoenix.__('phoenix.form.field.drag.file.message.max.size', sizeLimit < 1 ? sizeLimit * 1024 + 'KB' : sizeLimit + 'MB'), '', 'warning');
          e.preventDefault();
          return;
        }

        if (files.length > 1) {
          text = '<span class="fa fa-files fa-copy"></span> \n                    ' + Phoenix.__('phoenix.form.field.drag.file.selected', files.length);
        } else if (files.length === 1) {
          text = '<span class="fa fa-file"></span> ' + files[0].name;
        } else {
          text = '<span class="fa fa-upload"></span> ' + placeholder;
        }

        //replace the "Choose a file" label
        $self.next('.custom-file-label').find('span').html(text);
      }).on('dragover', function (e) {
        $(e.currentTarget).addClass('hover');
      }).on('dragleave', function (e) {
        $(e.currentTarget).removeClass('hover');
      }).on('drop', function (e) {
        $(e.currentTarget).removeClass('hover');
      }).trigger('change');
    }

    return _class;
  }());
});
//# sourceMappingURL=drag-file.js.map
