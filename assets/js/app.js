import '../css/app.css';
import $ from 'jquery';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-select/dist/js/bootstrap-select.js';
import 'bootstrap-select/dist/css/bootstrap-select.min.css';
import 'select2/dist/css/select2.min.css';
import 'select2/dist/js/select2.min';
import '@fortawesome/fontawesome-free/css/all.min.css';
import '@fortawesome/fontawesome-free/js/all.js';

$('select').select2({
    minimumResultsForSearch: 1
});