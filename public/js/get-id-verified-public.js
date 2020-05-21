!function(){"use strict";function t(){}function e(t){return t()}function n(){return Object.create(null)}function r(t){t.forEach(e)}function o(t){return"function"==typeof t}function i(t,e){return t!=t?e==e:t!==e||t&&"object"==typeof t||"function"==typeof t}function a(t,e){t.appendChild(e)}function c(t,e,n){t.insertBefore(e,n||null)}function u(t){t.parentNode.removeChild(t)}function l(t){return document.createElement(t)}function s(){return t=" ",document.createTextNode(t);var t}function d(t,e,n,r){return t.addEventListener(e,n,r),()=>t.removeEventListener(e,n,r)}function f(t,e,n){null==n?t.removeAttribute(e):t.getAttribute(e)!==n&&t.setAttribute(e,n)}let p;function g(t){p=t}const m=[],v=[],h=[],$=[],b=Promise.resolve();let y=!1;function _(t){h.push(t)}let x=!1;const w=new Set;function E(){if(!x){x=!0;do{for(let t=0;t<m.length;t+=1){const e=m[t];g(e),A(e.$$)}for(m.length=0;v.length;)v.pop()();for(let t=0;t<h.length;t+=1){const e=h[t];w.has(e)||(w.add(e),e())}h.length=0}while(m.length);for(;$.length;)$.pop()();y=!1,x=!1,w.clear()}}function A(t){if(null!==t.fragment){t.update(),r(t.before_update);const e=t.dirty;t.dirty=[-1],t.fragment&&t.fragment.p(t.ctx,e),t.after_update.forEach(_)}}const j=new Set;function O(t,e){-1===t.$$.dirty[0]&&(m.push(t),y||(y=!0,b.then(E)),t.$$.dirty.fill(0)),t.$$.dirty[e/31|0]|=1<<e%31}function S(i,a,c,l,s,d,f=[-1]){const m=p;g(i);const v=a.props||{},h=i.$$={fragment:null,ctx:null,props:d,update:t,not_equal:s,bound:n(),on_mount:[],on_destroy:[],before_update:[],after_update:[],context:new Map(m?m.$$.context:[]),callbacks:n(),dirty:f};let $=!1;if(h.ctx=c?c(i,v,(t,e,...n)=>{const r=n.length?n[0]:e;return h.ctx&&s(h.ctx[t],h.ctx[t]=r)&&(h.bound[t]&&h.bound[t](r),$&&O(i,t)),e}):[],h.update(),$=!0,r(h.before_update),h.fragment=!!l&&l(h.ctx),a.target){if(a.hydrate){const t=function(t){return Array.from(t.childNodes)}(a.target);h.fragment&&h.fragment.l(t),t.forEach(u)}else h.fragment&&h.fragment.c();a.intro&&((b=i.$$.fragment)&&b.i&&(j.delete(b),b.i(y))),function(t,n,i){const{fragment:a,on_mount:c,on_destroy:u,after_update:l}=t.$$;a&&a.m(n,i),_(()=>{const n=c.map(e).filter(o);u?u.push(...n):r(n),t.$$.on_mount=[]}),l.forEach(_)}(i,a.target,a.anchor),E()}var b,y;g(m)}function L(t){let e,n,r,o,i,p;return{c(){e=l("div"),n=l("img"),o=s(),i=l("button"),i.textContent="Edit",f(n,"class","giv_preview-image svelte-1o1rx7r"),n.src!==(r=t[0])&&f(n,"src",r),f(n,"alt","Id Verification"),f(i,"class","giv_preview-edit svelte-1o1rx7r"),f(i,"type","button"),f(e,"class","giv_preview svelte-1o1rx7r")},m(r,u,l){c(r,e,u),a(e,n),a(e,o),a(e,i),l&&p(),p=d(i,"click",t[3])},p(t,e){1&e&&n.src!==(r=t[0])&&f(n,"src",r)},d(t){t&&u(e),p()}}}function N(t){let e,n,r,o,i=t[2]&&k();return{c(){e=l("div"),i&&i.c(),n=s(),r=l("input"),f(r,"type","file"),f(r,"accept","image/jpeg,image/png"),f(e,"class","giv_uploader svelte-1o1rx7r")},m(u,l,s){c(u,e,l),i&&i.m(e,null),a(e,n),a(e,r),s&&o(),o=d(r,"change",t[4])},p(t,r){t[2]?i||(i=k(),i.c(),i.m(e,n)):i&&(i.d(1),i=null)},d(t){t&&u(e),i&&i.d(),o()}}}function k(t){let e;return{c(){e=l("div"),f(e,"class","giv_spinner svelte-1o1rx7r")},m(t,n){c(t,e,n)},d(t){t&&u(e)}}}function C(e){let n,r,o;function i(t,e){return t[0]?L:N}let d=i(e),p=d(e);return{c(){n=l("div"),p.c(),r=s(),o=l("input"),f(o,"type","hidden"),f(o,"name","giv_upload_image_name"),o.value=e[1],f(n,"class","giv_container svelte-1o1rx7r")},m(t,e){c(t,n,e),p.m(n,null),a(n,r),a(n,o)},p(t,[e]){d===(d=i(t))&&p?p.p(t,e):(p.d(1),p=d(t),p&&(p.c(),p.m(n,r))),2&e&&(o.value=t[1])},i:t,o:t,d(t){t&&u(n),p.d()}}}function M(t,e,n){let{source:r=""}=e,{value:o=""}=e,{action:i=""}=e,{uploading:a=!1}=e;return t.$set=t=>{"source"in t&&n(0,r=t.source),"value"in t&&n(1,o=t.value),"action"in t&&n(5,i=t.action),"uploading"in t&&n(2,a=t.uploading)},[r,o,a,()=>{n(0,r=""),n(1,o="")},({target:t})=>{const e=new XMLHttpRequest;e.open("POST",i,!0);const c=t.files[0],u=(t=>{const e=t.closest("form");return e?e.querySelector('button[type="submit"]'):null})(t);if(c){if(c.size>15e6)return alert("Please upload a file less than 15MB.");if(!c.name.split(".").pop().match(/(jpg|jpeg|png)/))return alert("Only jpg or png images are allowed.");const t=new FormData;t.append("giv_upload_image",c),e.send(t),n(2,a=!0),u&&u.setAttribute("disabled","disabled")}else alert("Please select a file.");e.onreadystatechange=()=>{4==e.readyState&&200==e.status&&(n(1,o=JSON.parse(e.response)),n(0,r=`${i}/${o}?tmp=true`)),u&&u.removeAttribute("disabled"),n(2,a=!1)}},i]}class P extends class{$destroy(){!function(t,e){const n=t.$$;null!==n.fragment&&(r(n.on_destroy),n.fragment&&n.fragment.d(e),n.on_destroy=n.fragment=null,n.ctx=[])}(this,1),this.$destroy=t}$on(t,e){const n=this.$$.callbacks[t]||(this.$$.callbacks[t]=[]);return n.push(e),()=>{const t=n.indexOf(e);-1!==t&&n.splice(t,1)}}$set(){}}{constructor(t){super(),S(this,t,M,C,i,{source:0,value:1,action:5,uploading:2})}}window.addEventListener("DOMContentLoaded",()=>{let t=document.getElementsByTagName("giv-uploader");t&&t.length&&(t=t[0],new P({target:t,props:{source:t.getAttribute("source"),value:t.getAttribute("value"),action:t.getAttribute("action")}}))})}();
