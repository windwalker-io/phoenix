"use strict";

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

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

  Phoenix.plugin('dragFile', /*#__PURE__*/function () {
    function _class($element, $options) {
      var _this = this;

      _classCallCheck(this, _class);

      $element.on('change', function (e) {
        var $self = $(e.currentTarget); //get the file name

        var files = $self[0].files;
        var limit = $self.attr('data-max-files');
        var sizeLimit = $self.attr('data-max-size');
        var placeholder = $self.attr('placeholder');
        var accepted = ($self.attr('accept') || $self.attr('data-accepted') || '').split(',').map(function (v) {
          return v.trim();
        }).filter(function (v) {
          return v.length > 0;
        }).map(function (v) {
          if (v.indexOf('/') === -1 && v[0] === '.') {
            return v.substr(1);
          }

          return v;
        });
        var text;

        if (!placeholder) {
          if ($self.attr('multiple')) {
            placeholder = Phoenix.__('phoenix.form.field.drag.file.placeholder.multiple');
          } else {
            placeholder = Phoenix.__('phoenix.form.field.drag.file.placeholder.single');
          }
        } // Files limit


        if (limit && files.length > limit) {
          swal(Phoenix.__('phoenix.form.field.drag.file.message.max.files', limit), '', 'warning');
          e.preventDefault();
          return;
        } // Files size


        var fileSize = 0;
        Array.prototype.forEach.call(files, function (file) {
          _this.checkFileType(accepted, file);

          fileSize += file.size;
        });

        if (sizeLimit && fileSize / 1024 / 1024 > sizeLimit) {
          swal(Phoenix.__('phoenix.form.field.drag.file.message.max.size', sizeLimit < 1 ? sizeLimit * 1024 + 'KB' : sizeLimit + 'MB'), '', 'warning');
          e.preventDefault();
          return;
        }

        if (files.length > 1) {
          text = "<span class=\"fa fa-files fa-copy\"></span> \n                    ".concat(Phoenix.__('phoenix.form.field.drag.file.selected', files.length));
        } else if (files.length === 1) {
          text = "<span class=\"fa fa-file\"></span> ".concat(files[0].name);
        } else {
          text = "<span class=\"fa fa-upload\"></span> ".concat(placeholder);
        } //replace the "Choose a file" label


        $self.next('.custom-file-label').find('span').html(text);
      }).on('dragover', function (e) {
        $(e.currentTarget).addClass('hover');
      }).on('dragleave', function (e) {
        $(e.currentTarget).removeClass('hover');
      }).on('drop', function (e) {
        $(e.currentTarget).removeClass('hover');
      }).trigger('change');
    }

    _createClass(_class, [{
      key: "checkFileType",
      value: function checkFileType(accepted, file) {
        var _this2 = this;

        var fileExt = file.name.split('.').pop();

        if (accepted.length) {
          var allow = false;
          accepted.forEach(function (type) {
            if (allow) {
              return;
            }

            if (type.indexOf('/') !== -1) {
              if (_this2.compareMimeType(type, file.type)) {
                allow = true;
              }
            } else {
              if (type === fileExt) {
                allow = true;
              }
            }
          });

          if (!allow) {
            swal(Phoenix.__('phoenix.form.field.drag.file.message.unaccepted.files'), Phoenix.__('phoenix.form.field.drag.file.message.unaccepted.files.desc', accepted.join(', ')), 'warning');
            throw new Error('Not accepted file ext');
          }
        }
      }
    }, {
      key: "compareMimeType",
      value: function compareMimeType(accepted, mime) {
        var accepted2 = accepted.split('/');
        var mime2 = mime.split('/');

        if (accepted2[1] === '*') {
          return accepted2[0] === mime2[0];
        }

        return accepted === mime;
      }
    }]);

    return _class;
  }());
});
//# sourceMappingURL=drag-file.js.map
