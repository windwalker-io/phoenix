// Global
body {
	padding-bottom: 20px;
}
.nowrap {
	white-space: nowrap;
}

.clearfix {
	clear: both;
}

.jumbotron.admin-header {
	margin-bottom: 0;
}

#admin-area {
	margin-top: 30px;
}

#admin-toolbar {
	background-color: #f6f6f6;
	padding: 7px 25px;
}

// Layout
.navbar-fixed-top {
	position: relative;
	margin-bottom: 0;
}
.navbar-brand img {
	height: 35px;
	margin-top: -8px;
}
.jumbotron {
	h1 {
		margin: 0;
	}
}

.admin-header.jumbotron {
	padding-top: 24px;
	padding-bottom: 24px;

	h1 {
		font-size: 48px;
	}
}

.admin-toolbar-fixed {
	position: fixed;
	width: 100%;
	top: 0;
	z-index: 100;
	box-shadow: 0 3px 8px rgba(0,0,0,.1);
}

// Grid

.search-container {
	margin-bottom: 15px;
}

.ordering-control
{
	input {
		min-width: 40px;
	}

	max-width: 90px;
}

// Input
.input-xs {
	height: 22px;
	padding: 0 5px;
}

// Modal
#phoenix-iframe-modal {
	.modal-dialog {
		min-width: 900px;
	}

	.modal-body iframe {
		height: 500px;
	}
}

// Button
.btn-group .btn {
	margin-left: -1px;
}

// Chosen
.chosen-container {
	cursor: pointer;
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
