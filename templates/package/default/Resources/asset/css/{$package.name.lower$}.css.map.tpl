{"version":3,"sources":["{$package.name.lower$}.scss"],"names":[],"mappings":"AAKA;EACE,UAAS;EACT,WAAU,EAKX;EAPD;IAKI,oBAAmB,EACpB;;AAIH;EACE,iBAAgB;EAChB,gBAAe;EACf,wBAAuB;EACvB,+BAA8B,EAC/B;;AAGD;EACE,mBAAkB;EAClB,iBAAgB,EACjB;;AAED;EACE,aAAY;EACZ,iBAAgB,EACjB;;AAGD;EAEI,UAAS,EACV;;AAGH;;EAEE,iBAAgB;EAChB,gBAAe,EAChB;;AAED;EACE,kBAAiB;EACjB,qBAAoB;EACpB,iBAAgB,EAKjB;EARD;IAMI,gBAAe,EAChB;;AAIH;EACE,kBAAiB;EACjB,mBAAkB;EAClB,oBAAmB,EACpB;;AAED;EACE,yBAAgB;EAAhB,iBAAgB;EAChB,YAAW;EACX,OAAM;EACN,aAAY;EACZ,iDAAuC;UAAvC,yCAAuC;EACvC,0BAAyB;EACzB,kBAAiB,EAUlB;EAjBD;;IAWI,cAAa,EACd;EAZH;IAeI,iBAAgB,EACjB;;AAIH;EAEI,mBAAkB;EAClB,oBAAmB,EAkBpB;EArBH;IAMM,UAAS;IACT,uBAAsB,EAavB;IApBL;MAUQ,iBAAgB;MAChB,mBAAkB;MAClB,eAAc;MACd,mBAAkB;MAClB,8BAA6B,EAC9B;IAfP;MAkBQ,2BAA0B,EAC3B;;AAKP;EAEI,cAAa,EACd;;AAHH;EAMI,YAAW;EACX,oBAAc;MAAd,mBAAc;UAAd,eAAc;EACd,gBAAe,EAChB;;AAIH;EACE,qBAAoB,EACrB;;AAGD;EACE;;IAGI,eAAc;IACd,YAAW;IACX,mBAAkB,EACnB;EANH;IASI,YAAW,EACZ;EAVH;IAaI,UAAS;IACT,iBAAgB,EASjB;IAvBH;MAiBM,aAAY,EACb;IAlBL;MAqBM,aAAY,EACb;EAtBL;;IA2BI,eAAc,EACf;EAGH;IACE,mBAAkB;IAClB,yBAAgB;YAAhB,iBAAgB,EACjB;EAED;IACE,YAAW;IACX,mBAAkB;IAClB,eAAc,EACf,EAAA;;AAOH;EACE,YAAW,EACZ;;AAGD;EACE,iBAAgB,EACjB;;AAGD;EACE,oBAAmB,EACpB;;AAED;EAKE,iBAAgB,EACjB;EAND;IAEI,gBAAe,EAChB;;AAMH;EACE,aAAY;EACZ,eAAc,EACf;;AAED;EACE,WAAU,EACX;;AAGD;EAGI,cAAa,EACd;;AAGH;EACE,iBAAgB,EACjB;;AAED;EACE;IAGI,WAAU,EACX;EAJH;IAOI,WAAU,EACX;EAGH;IACE,mBAAkB,EACnB;EAED;IACE,YAAW;IACX,eAAc;IACd,YAAW,EACZ,EAAA;;AAGH;EACE;IAEI,eAAc,EACf;EAHH;IAMI,cAAa,EACd,EAAA;;AAKL;EACE,gBAAe;EACf,gBAAe,EAChB;;AAED;EAEI,iBAAgB;EAEhB,2BAA0B;EAC1B,uBAAsB,EAMvB;EAXH;IAQM,SAAQ;IACR,WAAU,EACX;;AAKL;;EAEE,gBAAe,EAChB;;AAGD;EACE,aAAY;EACZ,qCAAmC;EACnC,gBAAe;EACf,iBAAgB,EACjB","file":"{$package.name.lower$}.css","sourcesContent":["// {$package.name.cap$} package CSS\r\n\r\n// This section is for default template, you can delete it if you use custom templates.\r\n// ------------------------------------------------------------------------------------\r\n\r\nbody {\r\n  margin: 0;\r\n  padding: 0;\r\n\r\n  &.phoenix-admin {\r\n    background: #f8f8f8;\r\n  }\r\n}\r\n\r\n// Main Layout\r\n.main-body {\r\n  padding-right: 0;\r\n  padding-left: 0;\r\n  background-color: white;\r\n  border-left: 1px solid #e7e7e7;\r\n}\r\n\r\n// Header\r\n.navbar-fixed-top {\r\n  position: relative;\r\n  margin-bottom: 0;\r\n}\r\n\r\n.navbar-brand img {\r\n  height: 35px;\r\n  margin-top: -8px;\r\n}\r\n\r\n// Banner\r\n.jumbotron {\r\n  h1 {\r\n    margin: 0;\r\n  }\r\n}\r\n\r\n.container .jumbotron,\r\n.container-fluid .jumbotron {\r\n  border-radius: 0;\r\n  padding-left: 0;\r\n}\r\n\r\n.admin-header.jumbotron {\r\n  padding-top: 24px;\r\n  padding-bottom: 24px;\r\n  margin-bottom: 0;\r\n\r\n  h1 {\r\n    font-size: 48px;\r\n  }\r\n}\r\n\r\n// Admin Layout\r\n#admin-area {\r\n  padding-top: 30px;\r\n  padding-left: 15px;\r\n  padding-right: 15px;\r\n}\r\n\r\n#admin-toolbar {\r\n  position: sticky;\r\n  width: 100%;\r\n  top: 0;\r\n  z-index: 100;\r\n  box-shadow: 0 3px 8px rgba(0, 0, 0, .1);\r\n  background-color: #f6f6f6;\r\n  padding: 7px 15px;\r\n\r\n  .toolbar-toggle-button,\r\n  .admin-toolbar-buttons hr {\r\n    display: none;\r\n  }\r\n\r\n  .btn-wide {\r\n    min-width: 175px;\r\n  }\r\n}\r\n\r\n// Sidebar\r\n.main-sidebar {\r\n  ul.nav {\r\n    margin-left: -15px;\r\n    margin-right: -15px;\r\n\r\n    > li {\r\n      margin: 0;\r\n      background-color: #fff;\r\n\r\n      > a {\r\n        border-radius: 0;\r\n        position: relative;\r\n        display: block;\r\n        padding: 10px 15px;\r\n        border-bottom: 1px solid #ddd;\r\n      }\r\n\r\n      &:first-child > a {\r\n        border-top: 1px solid #ddd;\r\n      }\r\n    }\r\n  }\r\n}\r\n\r\nbody.sidebar-hide {\r\n  .main-sidebar {\r\n    display: none;\r\n  }\r\n\r\n  .main-body {\r\n    width: 100%;\r\n    flex: 0 0 100%;\r\n    max-width: 100%;\r\n  }\r\n}\r\n\r\n// Copyright\r\n#copyright {\r\n  padding-bottom: 20px;\r\n}\r\n\r\n// Mobile\r\n@media (max-width: 767px) {\r\n  #admin-toolbar {\r\n    .btn,\r\n    .btn-group {\r\n      display: block;\r\n      width: 100%;\r\n      margin-bottom: 5px;\r\n    }\r\n\r\n    .dropdown-menu {\r\n      width: 100%;\r\n    }\r\n\r\n    .admin-toolbar-buttons {\r\n      height: 0;\r\n      overflow: hidden;\r\n\r\n      &.collapse.in {\r\n        height: auto;\r\n      }\r\n\r\n      &.collapse.show {\r\n        height: auto;\r\n      }\r\n    }\r\n\r\n    .toolbar-toggle-button,\r\n    .admin-toolbar-buttons hr {\r\n      display: block;\r\n    }\r\n  }\r\n\r\n  .admin-toolbar-fixed {\r\n    position: relative;\r\n    box-shadow: none;\r\n  }\r\n\r\n  #batch-modal .modal-footer .btn {\r\n    width: 100%;\r\n    margin-bottom: 5px;\r\n    margin-left: 0;\r\n  }\r\n}\r\n\r\n// ------------------------------------------------------------------------------------\r\n// End deletable section\r\n\r\n// Global\r\n.clearfix {\r\n  clear: both;\r\n}\r\n\r\n// Alert\r\n.alert p:last-child {\r\n  margin-bottom: 0;\r\n}\r\n\r\n// Grid\r\n.search-container {\r\n  margin-bottom: 15px;\r\n}\r\n\r\n.ordering-control {\r\n  input {\r\n    min-width: 40px;\r\n  }\r\n\r\n  max-width: 120px;\r\n}\r\n\r\n// Input\r\n.input-xs {\r\n  height: 22px;\r\n  padding: 0 5px;\r\n}\r\n\r\n.sr-only {\r\n  padding: 0;\r\n}\r\n\r\n// Modal\r\n#phoenix-iframe-modal {\r\n\r\n  .modal-body iframe {\r\n    height: 500px;\r\n  }\r\n}\r\n\r\n.modal-xs {\r\n  max-width: 900px;\r\n}\r\n\r\n@media (max-width: 767px) {\r\n  .filter-buttons-group {\r\n\r\n    .filter-toggle-button {\r\n      width: 70%;\r\n    }\r\n\r\n    .search-clear-button {\r\n      width: 30%;\r\n    }\r\n  }\r\n\r\n  .search-container > * {\r\n    margin-bottom: 5px;\r\n  }\r\n\r\n  .search-container::after {\r\n    content: \"\";\r\n    display: block;\r\n    clear: both;\r\n  }\r\n}\r\n\r\n@media (max-width: 1000px) {\r\n  #phoenix-iframe-modal {\r\n    .modal-dialog {\r\n      min-width: 90%;\r\n    }\r\n\r\n    .modal-body iframe {\r\n      height: 400px;\r\n    }\r\n  }\r\n}\r\n\r\n// Chosen\r\n.chosen-container {\r\n  cursor: pointer;\r\n  max-width: 100%;\r\n}\r\n\r\n.chosen-container-multi .chosen-choices {\r\n  .search-choice {\r\n    background: #eee;\r\n\r\n    padding: 4px 25px 4px 14px;\r\n    background-image: none;\r\n\r\n    .search-choice-close {\r\n      top: 7px;\r\n      right: 6px;\r\n    }\r\n  }\r\n}\r\n\r\n// Chosen in modal will be 0px, force it's width\r\n.modal .chosen-container,\r\n*[class*=\"col-\"] > .chosen-container {\r\n  min-width: 100%;\r\n}\r\n\r\n// Tooltips\r\n.tooltip .tooltip-inner {\r\n  padding: 8px;\r\n  background-color: rgba(0, 0, 0, .8);\r\n  font-size: 13px;\r\n  min-width: 100px;\r\n}\r\n"]}