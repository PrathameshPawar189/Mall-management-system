/*!
* inputmask.regex.extensions.min.js
* https://github.com/RobinHerbots/jquery.inputmask
* Copyright (c) 2010 - 2016 Robin Herbots
* Licensed under the MIT license (http://www.opensource.org/licenses/mit-license.php)
* Version: 3.3.2-106
*/
/*
 Input Mask plugin extensions
 http://github.com/RobinHerbots/jquery.inputmask
 Copyright (c) 2010 -  Robin Herbots
 Licensed under the MIT license (http://www.opensource.org/licenses/mit-license.php)
 Version: 0.0.0-dev

 Regex extensions on the jquery.inputmask base
 Allows for using regular expressions as a mask
 */
!function(a){"function"==typeof define&&define.amd?define(["inputmask.dependencyLib","inputmask"],a):"object"==typeof exports?module.exports=a(require("./inputmask.dependencyLib.jquery"),require("./inputmask")):a(window.dependencyLib||jQuery,window.Inputmask)}(function(a,b){return b.extendAliases({// $(selector).inputmask("Regex", { regex: "[0-9]*"}
Regex:{mask:"r",greedy:!1,repeat:"*",regex:null,regexTokens:null,
//Thx to https://github.com/slevithan/regex-colorizer for the tokenizer regex
tokenizer:/\[\^?]?(?:[^\\\]]+|\\[\S\s]?)*]?|\\(?:0(?:[0-3][0-7]{0,2}|[4-7][0-7]?)?|[1-9][0-9]*|x[0-9A-Fa-f]{2}|u[0-9A-Fa-f]{4}|c[A-Za-z]|[\S\s]?)|\((?:\?[:=!]?)?|(?:[?*+]|\{[0-9]+(?:,[0-9]*)?\})\??|[^.?*+^${[()|\\]+|./g,quantifierFilter:/[0-9]+[^,]/,isComplete:function(a,b){return new RegExp(b.regex).test(a.join(""))},definitions:{r:{validator:function(b,c,d,e,f){function g(a,b){this.matches=[],this.isGroup=a||!1,this.isQuantifier=b||!1,this.quantifier={min:1,max:1},this.repeaterPart=void 0}function h(){var a,b,c=new g,d=[];
// The tokenizer regex does most of the tokenization grunt work
for(f.regexTokens=[];a=f.tokenizer.exec(f.regex);)switch(b=a[0],b.charAt(0)){case"(":// Group opening
d.push(new g((!0)));break;case")":// Group closing
k=d.pop(),d.length>0?d[d.length-1].matches.push(k):c.matches.push(k);break;case"{":case"+":case"*"://Quantifier
var e=new g((!1),(!0));b=b.replace(/[{}]/g,"");var h=b.split(","),i=isNaN(h[0])?h[0]:parseInt(h[0]),j=1===h.length?i:isNaN(h[1])?h[1]:parseInt(h[1]);if(e.quantifier={min:i,max:j},d.length>0){var l=d[d.length-1].matches;a=l.pop(),a.isGroup||(k=new g((!0)),k.matches.push(a),a=k),l.push(a),l.push(e)}else a=c.matches.pop(),a.isGroup||(k=new g((!0)),k.matches.push(a),a=k),c.matches.push(a),c.matches.push(e);break;default:d.length>0?d[d.length-1].matches.push(b):c.matches.push(b)}c.matches.length>0&&f.regexTokens.push(c)}function i(b,c){var d=!1;c&&(m+="(",o++);for(var e=0;e<b.matches.length;e++){var f=b.matches[e];if(f.isGroup===!0)d=i(f,!0);else if(f.isQuantifier===!0){var g=a.inArray(f,b.matches),h=b.matches[g-1],k=m;if(isNaN(f.quantifier.max)){for(;f.repeaterPart&&f.repeaterPart!==m&&f.repeaterPart.length>m.length&&!(d=i(h,!0)););d=d||i(h,!0),d&&(f.repeaterPart=m),m=k+f.quantifier.max}else{for(var l=0,n=f.quantifier.max-1;l<n&&!(d=i(h,!0));l++);m=k+"{"+f.quantifier.min+","+f.quantifier.max+"}"}}else if(void 0!==f.matches)for(var p=0;p<f.length&&!(d=i(f[p],c));p++);else{var q;if("["==f.charAt(0)){q=m,q+=f;for(var r=0;r<o;r++)q+=")";var s=new RegExp("^("+q+")$");d=s.test(j)}else for(var t=0,u=f.length;t<u;t++)if("\\"!==f.charAt(t)){q=m,q+=f.substr(0,t+1),q=q.replace(/\|$/,"");for(var r=0;r<o;r++)q+=")";var s=new RegExp("^("+q+")$");if(d=s.test(j))break}m+=f}if(d)break}return c&&(m+=")",o--),d}var j,k,l=c.buffer.slice(),m="",n=!1,o=0;null===f.regexTokens&&h(),l.splice(d,0,b),j=l.join("");for(var p=0;p<f.regexTokens.length;p++){var q=f.regexTokens[p];if(n=i(q,q.isGroup))break}return n},cardinality:1}}}}),b});