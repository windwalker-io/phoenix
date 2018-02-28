// {$package.name.cap$} package CSS

// This section is for default template, you can delete it if you use custom templates.
// ------------------------------------------------------------------------------------

body {
  margin: 0;
  padding: 0;

  &.phoenix-admin {
    background: #f8f8f8;
  }
}

// Main Layout
.main-body {
  padding-right: 0;
  padding-left: 0;
  background-color: white;
  border-left: 1px solid #e7e7e7;
}

// Header
.navbar-fixed-top {
  position: relative;
  margin-bottom: 0;
}

.navbar-brand img {
  height: 35px;
  margin-top: -8px;
}

// Banner
.jumbotron {
  h1 {
    margin: 0;
  }
}

.container .jumbotron,
.container-fluid .jumbotron {
  border-radius: 0;
  padding-left: 0;
}

.admin-header.jumbotron {
  padding-top: 24px;
  padding-bottom: 24px;
  margin-bottom: 0;

  h1 {
    font-size: 48px;
  }
}

// Admin Layout
#admin-area {
  padding-top: 30px;
  padding-left: 15px;
  padding-right: 15px;
}

#admin-toolbar {
  position: sticky;
  width: 100%;
  top: 0;
  z-index: 100;
  box-shadow: 0 3px 8px rgba(0, 0, 0, .1);
  background-color: #f6f6f6;
  padding: 7px 15px;

  .toolbar-toggle-button,
  .admin-toolbar-buttons hr {
    display: none;
  }

  .btn-wide {
    min-width: 175px;
  }
}

// Sidebar
.main-sidebar {
  ul.nav {
    margin-left: -15px;
    margin-right: -15px;

    > li {
      margin: 0;
      background-color: #fff;

      > a {
        border-radius: 0;
        position: relative;
        display: block;
        padding: 10px 15px;
        border-bottom: 1px solid #ddd;
      }

      &:first-child > a {
        border-top: 1px solid #ddd;
      }
    }
  }
}

body.sidebar-hide {
  .main-sidebar {
    display: none;
  }

  .main-body {
    width: 100%;
    flex: 0 0 100%;
    max-width: 100%;
  }
}

// Copyright
#copyright {
  padding-bottom: 20px;
}

// Mobile
@media (max-width: 767px) {
  #admin-toolbar {
    button, a {
      &.btn {
        display: block;
        width: 100%;
        margin-bottom: 5px;
      }
    }

    .admin-toolbar-buttons {
      height: 0;
      overflow: hidden;

      &.collapse.in {
        height: auto;
      }

      &.collapse.show {
        height: auto;
      }
    }

    .toolbar-toggle-button,
    .admin-toolbar-buttons hr {
      display: block;
    }
  }

  .admin-toolbar-fixed {
    position: relative;
    box-shadow: none;
  }

  #batch-modal .modal-footer .btn {
    width: 100%;
    margin-bottom: 5px;
    margin-left: 0;
  }
}

// ------------------------------------------------------------------------------------
// End deletable section

// Global
.clearfix {
  clear: both;
}

// Alert
.alert p:last-child {
  margin-bottom: 0;
}

// Grid
.search-container {
  margin-bottom: 15px;
}

.ordering-control {
  input {
    min-width: 40px;
  }

  max-width: 120px;
}

// Input
.input-xs {
  height: 22px;
  padding: 0 5px;
}

.sr-only {
  padding: 0;
}

// Modal
#phoenix-iframe-modal {

  .modal-body iframe {
    height: 500px;
  }
}

.modal-xs {
  max-width: 900px;
}

@media (max-width: 767px) {
  .filter-buttons-group {

    .filter-toggle-button {
      width: 70%;
    }

    .search-clear-button {
      width: 30%;
    }
  }

  .search-container > * {
    margin-bottom: 5px;
  }

  .search-container::after {
    content: "";
    display: block;
    clear: both;
  }
}

@media (max-width: 1000px) {
  #phoenix-iframe-modal {
    .modal-dialog {
      min-width: 90%;
    }

    .modal-body iframe {
      height: 400px;
    }
  }
}

// Chosen
.chosen-container {
  cursor: pointer;
  max-width: 100%;
}

.chosen-container-multi .chosen-choices {
  .search-choice {
    background: #eee;

    padding: 4px 25px 4px 14px;
    background-image: none;

    .search-choice-close {
      top: 7px;
      right: 6px;
    }
  }
}

// Chosen in modal will be 0px, force it's width
.modal .chosen-container,
*[class*="col-"] > .chosen-container {
  min-width: 100%;
}

// Tooltips
.tooltip .tooltip-inner {
  padding: 8px;
  background-color: rgba(0, 0, 0, .8);
  font-size: 13px;
  min-width: 100px;
}
