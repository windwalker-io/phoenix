/**
 * Part of afb project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

// Custom file input
$(() => {
  // Polyfill sweetalert
  const swal = window.swal || function swal(title, message = null) {
    alert(title + ' / ' + message);
  };

  Phoenix.plugin('dragFile', class {
    constructor($element, $options) {
      $element.on('change', e => {
          const $self = $(e.currentTarget);

          //get the file name
          const files = $self[0].files;
          const limit = $self.attr('data-max-files');
          const sizeLimit = $self.attr('data-max-size');
          let placeholder = $self.attr('placeholder');
          const accepted = ($self.attr('accept') || $self.attr('data-accepted') || '')
            .split(',')
            .map(v => v.trim())
            .filter(v => v.length > 0)
            .map(v => {
              if (v.indexOf('/') === -1 && v[0] === '.') {
                return v.substr(1);
              }

              return v;
            });

          let text;

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
          let fileSize = 0;
          Array.prototype.forEach.call(files, file => {
            this.checkFileType(accepted, file);

            fileSize += file.size;
          });

          if (sizeLimit && (fileSize / 1024 / 1024) > sizeLimit) {
            swal(
              Phoenix.__(
                'phoenix.form.field.drag.file.message.max.size',
                sizeLimit < 1 ? (sizeLimit * 1024) + 'KB' : sizeLimit + 'MB'
              ),
              '',
              'warning'
            );
            e.preventDefault();
            return;
          }

          if (files.length > 1) {
            text = `<span class="fa fa-files fa-copy"></span> 
                    ${Phoenix.__('phoenix.form.field.drag.file.selected', files.length)}`;
          } else if (files.length === 1) {
            text = `<span class="fa fa-file"></span> ${files[0].name}`;
          } else {
            text = `<span class="fa fa-upload"></span> ${placeholder}`;
          }

          //replace the "Choose a file" label
          $self.next('.custom-file-label').find('span').html(text);
        })
        .on('dragover', e => {
          $(e.currentTarget).addClass('hover');
        })
        .on('dragleave', e => {
          $(e.currentTarget).removeClass('hover');
        })
        .on('drop', e => {
          $(e.currentTarget).removeClass('hover');
        })
        .trigger('change');
    }

    checkFileType(accepted, file) {
      const fileExt = file.name.split('.').pop();

      if (accepted.length) {
        let allow = false;

        accepted.forEach((type) => {
          if (allow) {
            return;
          }

          if (type.indexOf('/') !== -1) {
            if (this.compareMimeType(type, file.type)) {
              allow = true;
            }
          } else {
            if (type === fileExt) {
              allow = true;
            }
          }
        });

        if (!allow) {
          swal(
            Phoenix.__('phoenix.form.field.drag.file.message.unaccepted.files'),
            Phoenix.__('phoenix.form.field.drag.file.message.unaccepted.files.desc', accepted.join(', ')),
            'warning'
          );
          throw new Error('Not accepted file ext');
        }
      }
    }

    compareMimeType(accepted, mime) {
      const accepted2 = accepted.split('/');
      const mime2 = mime.split('/');

      if (accepted2[1] === '*') {
        return accepted2[0] === mime2[0];
      }

      return accepted === mime;
    }
  });
});
