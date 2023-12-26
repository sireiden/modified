(function(){
var ORIGIN = "";
if(!window.postMessage){
return;
}
var cfgFrame;
var addEvent = function(obj, evt, fn){
if(obj.addEventListener){
obj.addEventListener(evt, fn, false);
} else if(obj.attachEvent) {
obj.attachEvent(evt, fn);
}
};
(function(){
var iframe = document.getElementsByTagName('iframe');
for(var i = 0, len = iframe.length; i < len;i++){
if(iframe[i].getAttribute('data-seamless')){
cfgFrame = iframe[i];
cfgFrame.removeAttribute('data-seamless');
break;
}
}
})();
if(!cfgFrame){
if(window.console && console.log){
console.log('cfg frame not found abort');
}
return;
}
addEvent(window, 'message', (function(){
var init;
return function(e){
if(!ORIGIN || e.origin == ORIGIN){
var message;
try {
message = JSON.parse(e.data);
} catch(er){}
if(message.height && typeof message.height == 'number'
&& message.height > 100){
cfgFrame.style.height = message.height +'px';
cfgFrame.style.overflow = 'hidden';
cfgFrame.style.overflowX = 'hidden';
cfgFrame.style.overflowY = 'hidden';
}
if(message.requestConnection && e.source){
e.source.postMessage('seamlessconnected', '*');
}
}
};
})());
})();