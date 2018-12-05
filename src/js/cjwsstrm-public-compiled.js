"use strict";

var data = [1, 2, 3, 4, 5];
var testBabel = data.map(function (n) {
  return n * 3;
});
var data2 = ['1', '2', '3', '4', '5'];
var testBabel2 = data.filter(function (n) {
  return parseInt(n) * 10;
});