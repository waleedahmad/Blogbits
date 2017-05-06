
const ReactDOM = require('react-dom');
import React from "react";
import Routes from './components/Routes';
import boostrap from 'bootstrap';


toastr.options = {
    "debug": false,
    "positionClass": "toast-bottom-right",
    "onclick": null,
    "fadeIn": 300,
    "fadeOut": 1000,
    "timeOut": 5000,
    "extendedTimeOut": 1000,
    "newestOnTop" : false
};

ReactDOM.render(
    <Routes />,
    document.getElementById('root')
);