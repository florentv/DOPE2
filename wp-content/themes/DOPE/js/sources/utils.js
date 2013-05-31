var _keys = Object.keys || function(obj) {
      if (obj !== Object(obj)) throw new TypeError('Invalid object');
      var keys = [];
      for (var key in obj) if (obj.hasOwnProperty(key)) keys[keys.length] = key;
      return keys;
};
//
var _slice = [].slice;
//
function forEach(array, fn, context) {
    var i,
        len = array.length;
    for (i = 0; i < len; i += 1) { 
        if (fn.call(context, i, array[i]) === "break") {
            return;
        }; 
    }
}
//
function forIn(obj, fn) {
    var prop;
    for (prop in obj) {
        if (obj.hasOwnProperty(prop)) {
            fn(prop, obj[prop]);
        }
    }
}
//
function isArray(it) {
    return Object.prototype.toString.call(it) === "[object Array]";
}
//
function extend(parent, child) {
    var i;
    child = child || {};
    for (i in parent) {
        if (parent.hasOwnProperty(i)) {
            if (typeof parent[i] === 'object') {    
                child[i] = (isArray(parent[i])) ? [] : {};
                extend(parent[i], child[i]);    
            } else {
                child[i] = parent[i];
            }
        }
    }    
    return child;
}
//
function makeConstructor(o) {
    if (o.__init__) {
      var __init__ = o.__init__ ;      
      return function() { return __init__.apply(extend(o), arguments); };
    }
    return function() { return extend(o); };
}
//
function tagPosition(tag) {
  var top = 0 ;
	if (tag.getBoundingClientRect) {
		top = tag.getBoundingClientRect().top ;
	}
	return top + ( window.pageYOffset || document.documentElement.scrollTop ) ;	
}
//
function durationToString(seconds)
{
  var hours = "0",
      minutes = "0";
  hours += Math.floor(seconds/3600);
  minutes += Math.floor((seconds % 3600) / 60);
  seconds = "0" + Math.round(seconds % 60);
  return [hours.slice(-2), minutes.slice(-2), seconds.slice(-2)];
}

