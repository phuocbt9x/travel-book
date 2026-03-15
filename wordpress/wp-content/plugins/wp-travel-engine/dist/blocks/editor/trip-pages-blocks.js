(()=>{var e={138:(e,t,n)=>{"use strict";n.r(t),n.d(t,{edit:()=>$a,metadata:()=>Ua});var r=n(1609),o=n.n(r),i=n(7723),a=n(4715),l=n(6427);const c=window.wp.data,s=window.ReactDOM,d="undefined"!=typeof window&&void 0!==window.document&&void 0!==window.document.createElement;function u(e){const t=Object.prototype.toString.call(e);return"[object Window]"===t||"[object global]"===t}function p(e){return"nodeType"in e}function f(e){var t,n;return e?u(e)?e:p(e)&&null!=(t=null==(n=e.ownerDocument)?void 0:n.defaultView)?t:window:window}function h(e){const{Document:t}=f(e);return e instanceof t}function g(e){return!u(e)&&e instanceof f(e).HTMLElement}function m(e){return e instanceof f(e).SVGElement}function v(e){return e?u(e)?e.document:p(e)?h(e)?e:g(e)||m(e)?e.ownerDocument:document:document:document}const b=d?r.useLayoutEffect:r.useEffect;function w(e){const t=(0,r.useRef)(e);return b((()=>{t.current=e})),(0,r.useCallback)((function(){for(var e=arguments.length,n=new Array(e),r=0;r<e;r++)n[r]=arguments[r];return null==t.current?void 0:t.current(...n)}),[])}function x(e,t){void 0===t&&(t=[e]);const n=(0,r.useRef)(e);return b((()=>{n.current!==e&&(n.current=e)}),t),n}function y(e,t){const n=(0,r.useRef)();return(0,r.useMemo)((()=>{const t=e(n.current);return n.current=t,t}),[...t])}function C(e){const t=w(e),n=(0,r.useRef)(null),o=(0,r.useCallback)((e=>{e!==n.current&&(null==t||t(e,n.current)),n.current=e}),[]);return[n,o]}function _(e){const t=(0,r.useRef)();return(0,r.useEffect)((()=>{t.current=e}),[e]),t.current}let k={};function E(e,t){return(0,r.useMemo)((()=>{if(t)return t;const n=null==k[e]?0:k[e]+1;return k[e]=n,e+"-"+n}),[e,t])}function O(e){return function(t){for(var n=arguments.length,r=new Array(n>1?n-1:0),o=1;o<n;o++)r[o-1]=arguments[o];return r.reduce(((t,n)=>{const r=Object.entries(n);for(const[n,o]of r){const r=t[n];null!=r&&(t[n]=r+e*o)}return t}),{...t})}}const M=O(1),S=O(-1);function A(e){if(!e)return!1;const{KeyboardEvent:t}=f(e.target);return t&&e instanceof t}function D(e){if(function(e){if(!e)return!1;const{TouchEvent:t}=f(e.target);return t&&e instanceof t}(e)){if(e.touches&&e.touches.length){const{clientX:t,clientY:n}=e.touches[0];return{x:t,y:n}}if(e.changedTouches&&e.changedTouches.length){const{clientX:t,clientY:n}=e.changedTouches[0];return{x:t,y:n}}}return function(e){return"clientX"in e&&"clientY"in e}(e)?{x:e.clientX,y:e.clientY}:null}const L=Object.freeze({Translate:{toString(e){if(!e)return;const{x:t,y:n}=e;return"translate3d("+(t?Math.round(t):0)+"px, "+(n?Math.round(n):0)+"px, 0)"}},Scale:{toString(e){if(!e)return;const{scaleX:t,scaleY:n}=e;return"scaleX("+t+") scaleY("+n+")"}},Transform:{toString(e){if(e)return[L.Translate.toString(e),L.Scale.toString(e)].join(" ")}},Transition:{toString(e){let{property:t,duration:n,easing:r}=e;return t+" "+n+"ms "+r}}}),R="a,frame,iframe,input:not([type=hidden]):not(:disabled),select:not(:disabled),textarea:not(:disabled),button:not(:disabled),*[tabindex]";function T(e){return e.matches(R)?e:e.querySelector(R)}const N={display:"none"};function P(e){let{id:t,value:n}=e;return o().createElement("div",{id:t,style:N},n)}function B(e){let{id:t,announcement:n,ariaLiveType:r="assertive"}=e;return o().createElement("div",{id:t,style:{position:"fixed",top:0,left:0,width:1,height:1,margin:-1,border:0,padding:0,overflow:"hidden",clip:"rect(0 0 0 0)",clipPath:"inset(100%)",whiteSpace:"nowrap"},role:"status","aria-live":r,"aria-atomic":!0},n)}const j=(0,r.createContext)(null),V={draggable:"\n    To pick up a draggable item, press the space bar.\n    While dragging, use the arrow keys to move the item.\n    Press space again to drop the item in its new position, or press escape to cancel.\n  "},H={onDragStart(e){let{active:t}=e;return"Picked up draggable item "+t.id+"."},onDragOver(e){let{active:t,over:n}=e;return n?"Draggable item "+t.id+" was moved over droppable area "+n.id+".":"Draggable item "+t.id+" is no longer over a droppable area."},onDragEnd(e){let{active:t,over:n}=e;return n?"Draggable item "+t.id+" was dropped over droppable area "+n.id:"Draggable item "+t.id+" was dropped."},onDragCancel(e){let{active:t}=e;return"Dragging was cancelled. Draggable item "+t.id+" was dropped."}};function I(e){let{announcements:t=H,container:n,hiddenTextDescribedById:i,screenReaderInstructions:a=V}=e;const{announce:l,announcement:c}=function(){const[e,t]=(0,r.useState)("");return{announce:(0,r.useCallback)((e=>{null!=e&&t(e)}),[]),announcement:e}}(),d=E("DndLiveRegion"),[u,p]=(0,r.useState)(!1);if((0,r.useEffect)((()=>{p(!0)}),[]),function(e){const t=(0,r.useContext)(j);(0,r.useEffect)((()=>{if(!t)throw new Error("useDndMonitor must be used within a children of <DndContext>");return t(e)}),[e,t])}((0,r.useMemo)((()=>({onDragStart(e){let{active:n}=e;l(t.onDragStart({active:n}))},onDragMove(e){let{active:n,over:r}=e;t.onDragMove&&l(t.onDragMove({active:n,over:r}))},onDragOver(e){let{active:n,over:r}=e;l(t.onDragOver({active:n,over:r}))},onDragEnd(e){let{active:n,over:r}=e;l(t.onDragEnd({active:n,over:r}))},onDragCancel(e){let{active:n,over:r}=e;l(t.onDragCancel({active:n,over:r}))}})),[l,t])),!u)return null;const f=o().createElement(o().Fragment,null,o().createElement(P,{id:i,value:a.draggable}),o().createElement(B,{id:d,announcement:c}));return n?(0,s.createPortal)(f,n):f}var z;function W(){}function Z(e,t){return(0,r.useMemo)((()=>({sensor:e,options:null!=t?t:{}})),[e,t])}!function(e){e.DragStart="dragStart",e.DragMove="dragMove",e.DragEnd="dragEnd",e.DragCancel="dragCancel",e.DragOver="dragOver",e.RegisterDroppable="registerDroppable",e.SetDroppableDisabled="setDroppableDisabled",e.UnregisterDroppable="unregisterDroppable"}(z||(z={}));const F=Object.freeze({x:0,y:0});function $(e,t){return Math.sqrt(Math.pow(e.x-t.x,2)+Math.pow(e.y-t.y,2))}function U(e,t){let{data:{value:n}}=e,{data:{value:r}}=t;return n-r}function q(e,t){let{data:{value:n}}=e,{data:{value:r}}=t;return r-n}function Y(e){let{left:t,top:n,height:r,width:o}=e;return[{x:t,y:n},{x:t+o,y:n},{x:t,y:n+r},{x:t+o,y:n+r}]}function X(e,t){if(!e||0===e.length)return null;const[n]=e;return t?n[t]:n}function K(e,t,n){return void 0===t&&(t=e.left),void 0===n&&(n=e.top),{x:t+.5*e.width,y:n+.5*e.height}}const G=e=>{let{collisionRect:t,droppableRects:n,droppableContainers:r}=e;const o=K(t,t.left,t.top),i=[];for(const e of r){const{id:t}=e,r=n.get(t);if(r){const n=$(K(r),o);i.push({id:t,data:{droppableContainer:e,value:n}})}}return i.sort(U)};function J(e,t){const n=Math.max(t.top,e.top),r=Math.max(t.left,e.left),o=Math.min(t.left+t.width,e.left+e.width),i=Math.min(t.top+t.height,e.top+e.height),a=o-r,l=i-n;if(r<o&&n<i){const n=t.width*t.height,r=e.width*e.height,o=a*l;return Number((o/(n+r-o)).toFixed(4))}return 0}const Q=e=>{let{collisionRect:t,droppableRects:n,droppableContainers:r}=e;const o=[];for(const e of r){const{id:r}=e,i=n.get(r);if(i){const n=J(i,t);n>0&&o.push({id:r,data:{droppableContainer:e,value:n}})}}return o.sort(q)};function ee(e,t){return e&&t?{x:e.left-t.left,y:e.top-t.top}:F}function te(e){return function(t){for(var n=arguments.length,r=new Array(n>1?n-1:0),o=1;o<n;o++)r[o-1]=arguments[o];return r.reduce(((t,n)=>({...t,top:t.top+e*n.y,bottom:t.bottom+e*n.y,left:t.left+e*n.x,right:t.right+e*n.x})),{...t})}}const ne=te(1);const re={ignoreTransform:!1};function oe(e,t){void 0===t&&(t=re);let n=e.getBoundingClientRect();if(t.ignoreTransform){const{transform:t,transformOrigin:r}=f(e).getComputedStyle(e);t&&(n=function(e,t,n){const r=function(e){if(e.startsWith("matrix3d(")){const t=e.slice(9,-1).split(/, /);return{x:+t[12],y:+t[13],scaleX:+t[0],scaleY:+t[5]}}if(e.startsWith("matrix(")){const t=e.slice(7,-1).split(/, /);return{x:+t[4],y:+t[5],scaleX:+t[0],scaleY:+t[3]}}return null}(t);if(!r)return e;const{scaleX:o,scaleY:i,x:a,y:l}=r,c=e.left-a-(1-o)*parseFloat(n),s=e.top-l-(1-i)*parseFloat(n.slice(n.indexOf(" ")+1)),d=o?e.width/o:e.width,u=i?e.height/i:e.height;return{width:d,height:u,top:s,right:c+d,bottom:s+u,left:c}}(n,t,r))}const{top:r,left:o,width:i,height:a,bottom:l,right:c}=n;return{top:r,left:o,width:i,height:a,bottom:l,right:c}}function ie(e){return oe(e,{ignoreTransform:!0})}function ae(e,t){const n=[];return e?function r(o){if(null!=t&&n.length>=t)return n;if(!o)return n;if(h(o)&&null!=o.scrollingElement&&!n.includes(o.scrollingElement))return n.push(o.scrollingElement),n;if(!g(o)||m(o))return n;if(n.includes(o))return n;const i=f(e).getComputedStyle(o);return o!==e&&function(e,t){void 0===t&&(t=f(e).getComputedStyle(e));const n=/(auto|scroll|overlay)/;return["overflow","overflowX","overflowY"].some((e=>{const r=t[e];return"string"==typeof r&&n.test(r)}))}(o,i)&&n.push(o),function(e,t){return void 0===t&&(t=f(e).getComputedStyle(e)),"fixed"===t.position}(o,i)?n:r(o.parentNode)}(e):n}function le(e){const[t]=ae(e,1);return null!=t?t:null}function ce(e){return d&&e?u(e)?e:p(e)?h(e)||e===v(e).scrollingElement?window:g(e)?e:null:null:null}function se(e){return u(e)?e.scrollX:e.scrollLeft}function de(e){return u(e)?e.scrollY:e.scrollTop}function ue(e){return{x:se(e),y:de(e)}}var pe;function fe(e){return!(!d||!e)&&e===document.scrollingElement}function he(e){const t={x:0,y:0},n=fe(e)?{height:window.innerHeight,width:window.innerWidth}:{height:e.clientHeight,width:e.clientWidth},r={x:e.scrollWidth-n.width,y:e.scrollHeight-n.height};return{isTop:e.scrollTop<=t.y,isLeft:e.scrollLeft<=t.x,isBottom:e.scrollTop>=r.y,isRight:e.scrollLeft>=r.x,maxScroll:r,minScroll:t}}!function(e){e[e.Forward=1]="Forward",e[e.Backward=-1]="Backward"}(pe||(pe={}));const ge={x:.2,y:.2};function me(e,t,n,r,o){let{top:i,left:a,right:l,bottom:c}=n;void 0===r&&(r=10),void 0===o&&(o=ge);const{isTop:s,isBottom:d,isLeft:u,isRight:p}=he(e),f={x:0,y:0},h={x:0,y:0},g=t.height*o.y,m=t.width*o.x;return!s&&i<=t.top+g?(f.y=pe.Backward,h.y=r*Math.abs((t.top+g-i)/g)):!d&&c>=t.bottom-g&&(f.y=pe.Forward,h.y=r*Math.abs((t.bottom-g-c)/g)),!p&&l>=t.right-m?(f.x=pe.Forward,h.x=r*Math.abs((t.right-m-l)/m)):!u&&a<=t.left+m&&(f.x=pe.Backward,h.x=r*Math.abs((t.left+m-a)/m)),{direction:f,speed:h}}function ve(e){if(e===document.scrollingElement){const{innerWidth:e,innerHeight:t}=window;return{top:0,left:0,right:e,bottom:t,width:e,height:t}}const{top:t,left:n,right:r,bottom:o}=e.getBoundingClientRect();return{top:t,left:n,right:r,bottom:o,width:e.clientWidth,height:e.clientHeight}}function be(e){return e.reduce(((e,t)=>M(e,ue(t))),F)}const we=[["x",["left","right"],function(e){return e.reduce(((e,t)=>e+se(t)),0)}],["y",["top","bottom"],function(e){return e.reduce(((e,t)=>e+de(t)),0)}]];class xe{constructor(e,t){this.rect=void 0,this.width=void 0,this.height=void 0,this.top=void 0,this.bottom=void 0,this.right=void 0,this.left=void 0;const n=ae(t),r=be(n);this.rect={...e},this.width=e.width,this.height=e.height;for(const[e,t,o]of we)for(const i of t)Object.defineProperty(this,i,{get:()=>{const t=o(n),a=r[e]-t;return this.rect[i]+a},enumerable:!0});Object.defineProperty(this,"rect",{enumerable:!1})}}class ye{constructor(e){this.target=void 0,this.listeners=[],this.removeAll=()=>{this.listeners.forEach((e=>{var t;return null==(t=this.target)?void 0:t.removeEventListener(...e)}))},this.target=e}add(e,t,n){var r;null==(r=this.target)||r.addEventListener(e,t,n),this.listeners.push([e,t,n])}}function Ce(e,t){const n=Math.abs(e.x),r=Math.abs(e.y);return"number"==typeof t?Math.sqrt(n**2+r**2)>t:"x"in t&&"y"in t?n>t.x&&r>t.y:"x"in t?n>t.x:"y"in t&&r>t.y}var _e,ke;function Ee(e){e.preventDefault()}function Oe(e){e.stopPropagation()}!function(e){e.Click="click",e.DragStart="dragstart",e.Keydown="keydown",e.ContextMenu="contextmenu",e.Resize="resize",e.SelectionChange="selectionchange",e.VisibilityChange="visibilitychange"}(_e||(_e={})),function(e){e.Space="Space",e.Down="ArrowDown",e.Right="ArrowRight",e.Left="ArrowLeft",e.Up="ArrowUp",e.Esc="Escape",e.Enter="Enter",e.Tab="Tab"}(ke||(ke={}));const Me={start:[ke.Space,ke.Enter],cancel:[ke.Esc],end:[ke.Space,ke.Enter,ke.Tab]},Se=(e,t)=>{let{currentCoordinates:n}=t;switch(e.code){case ke.Right:return{...n,x:n.x+25};case ke.Left:return{...n,x:n.x-25};case ke.Down:return{...n,y:n.y+25};case ke.Up:return{...n,y:n.y-25}}};class Ae{constructor(e){this.props=void 0,this.autoScrollEnabled=!1,this.referenceCoordinates=void 0,this.listeners=void 0,this.windowListeners=void 0,this.props=e;const{event:{target:t}}=e;this.props=e,this.listeners=new ye(v(t)),this.windowListeners=new ye(f(t)),this.handleKeyDown=this.handleKeyDown.bind(this),this.handleCancel=this.handleCancel.bind(this),this.attach()}attach(){this.handleStart(),this.windowListeners.add(_e.Resize,this.handleCancel),this.windowListeners.add(_e.VisibilityChange,this.handleCancel),setTimeout((()=>this.listeners.add(_e.Keydown,this.handleKeyDown)))}handleStart(){const{activeNode:e,onStart:t}=this.props,n=e.node.current;n&&function(e,t){if(void 0===t&&(t=oe),!e)return;const{top:n,left:r,bottom:o,right:i}=t(e);le(e)&&(o<=0||i<=0||n>=window.innerHeight||r>=window.innerWidth)&&e.scrollIntoView({block:"center",inline:"center"})}(n),t(F)}handleKeyDown(e){if(A(e)){const{active:t,context:n,options:r}=this.props,{keyboardCodes:o=Me,coordinateGetter:i=Se,scrollBehavior:a="smooth"}=r,{code:l}=e;if(o.end.includes(l))return void this.handleEnd(e);if(o.cancel.includes(l))return void this.handleCancel(e);const{collisionRect:c}=n.current,s=c?{x:c.left,y:c.top}:F;this.referenceCoordinates||(this.referenceCoordinates=s);const d=i(e,{active:t,context:n.current,currentCoordinates:s});if(d){const t=S(d,s),r={x:0,y:0},{scrollableAncestors:o}=n.current;for(const n of o){const o=e.code,{isTop:i,isRight:l,isLeft:c,isBottom:s,maxScroll:u,minScroll:p}=he(n),f=ve(n),h={x:Math.min(o===ke.Right?f.right-f.width/2:f.right,Math.max(o===ke.Right?f.left:f.left+f.width/2,d.x)),y:Math.min(o===ke.Down?f.bottom-f.height/2:f.bottom,Math.max(o===ke.Down?f.top:f.top+f.height/2,d.y))},g=o===ke.Right&&!l||o===ke.Left&&!c,m=o===ke.Down&&!s||o===ke.Up&&!i;if(g&&h.x!==d.x){const e=n.scrollLeft+t.x,i=o===ke.Right&&e<=u.x||o===ke.Left&&e>=p.x;if(i&&!t.y)return void n.scrollTo({left:e,behavior:a});r.x=i?n.scrollLeft-e:o===ke.Right?n.scrollLeft-u.x:n.scrollLeft-p.x,r.x&&n.scrollBy({left:-r.x,behavior:a});break}if(m&&h.y!==d.y){const e=n.scrollTop+t.y,i=o===ke.Down&&e<=u.y||o===ke.Up&&e>=p.y;if(i&&!t.x)return void n.scrollTo({top:e,behavior:a});r.y=i?n.scrollTop-e:o===ke.Down?n.scrollTop-u.y:n.scrollTop-p.y,r.y&&n.scrollBy({top:-r.y,behavior:a});break}}this.handleMove(e,M(S(d,this.referenceCoordinates),r))}}}handleMove(e,t){const{onMove:n}=this.props;e.preventDefault(),n(t)}handleEnd(e){const{onEnd:t}=this.props;e.preventDefault(),this.detach(),t()}handleCancel(e){const{onCancel:t}=this.props;e.preventDefault(),this.detach(),t()}detach(){this.listeners.removeAll(),this.windowListeners.removeAll()}}function De(e){return Boolean(e&&"distance"in e)}function Le(e){return Boolean(e&&"delay"in e)}Ae.activators=[{eventName:"onKeyDown",handler:(e,t,n)=>{let{keyboardCodes:r=Me,onActivation:o}=t,{active:i}=n;const{code:a}=e.nativeEvent;if(r.start.includes(a)){const t=i.activatorNode.current;return!(t&&e.target!==t||(e.preventDefault(),null==o||o({event:e.nativeEvent}),0))}return!1}}];class Re{constructor(e,t,n){var r;void 0===n&&(n=function(e){const{EventTarget:t}=f(e);return e instanceof t?e:v(e)}(e.event.target)),this.props=void 0,this.events=void 0,this.autoScrollEnabled=!0,this.document=void 0,this.activated=!1,this.initialCoordinates=void 0,this.timeoutId=null,this.listeners=void 0,this.documentListeners=void 0,this.windowListeners=void 0,this.props=e,this.events=t;const{event:o}=e,{target:i}=o;this.props=e,this.events=t,this.document=v(i),this.documentListeners=new ye(this.document),this.listeners=new ye(n),this.windowListeners=new ye(f(i)),this.initialCoordinates=null!=(r=D(o))?r:F,this.handleStart=this.handleStart.bind(this),this.handleMove=this.handleMove.bind(this),this.handleEnd=this.handleEnd.bind(this),this.handleCancel=this.handleCancel.bind(this),this.handleKeydown=this.handleKeydown.bind(this),this.removeTextSelection=this.removeTextSelection.bind(this),this.attach()}attach(){const{events:e,props:{options:{activationConstraint:t,bypassActivationConstraint:n}}}=this;if(this.listeners.add(e.move.name,this.handleMove,{passive:!1}),this.listeners.add(e.end.name,this.handleEnd),e.cancel&&this.listeners.add(e.cancel.name,this.handleCancel),this.windowListeners.add(_e.Resize,this.handleCancel),this.windowListeners.add(_e.DragStart,Ee),this.windowListeners.add(_e.VisibilityChange,this.handleCancel),this.windowListeners.add(_e.ContextMenu,Ee),this.documentListeners.add(_e.Keydown,this.handleKeydown),t){if(null!=n&&n({event:this.props.event,activeNode:this.props.activeNode,options:this.props.options}))return this.handleStart();if(Le(t))return this.timeoutId=setTimeout(this.handleStart,t.delay),void this.handlePending(t);if(De(t))return void this.handlePending(t)}this.handleStart()}detach(){this.listeners.removeAll(),this.windowListeners.removeAll(),setTimeout(this.documentListeners.removeAll,50),null!==this.timeoutId&&(clearTimeout(this.timeoutId),this.timeoutId=null)}handlePending(e,t){const{active:n,onPending:r}=this.props;r(n,e,this.initialCoordinates,t)}handleStart(){const{initialCoordinates:e}=this,{onStart:t}=this.props;e&&(this.activated=!0,this.documentListeners.add(_e.Click,Oe,{capture:!0}),this.removeTextSelection(),this.documentListeners.add(_e.SelectionChange,this.removeTextSelection),t(e))}handleMove(e){var t;const{activated:n,initialCoordinates:r,props:o}=this,{onMove:i,options:{activationConstraint:a}}=o;if(!r)return;const l=null!=(t=D(e))?t:F,c=S(r,l);if(!n&&a){if(De(a)){if(null!=a.tolerance&&Ce(c,a.tolerance))return this.handleCancel();if(Ce(c,a.distance))return this.handleStart()}return Le(a)&&Ce(c,a.tolerance)?this.handleCancel():void this.handlePending(a,c)}e.cancelable&&e.preventDefault(),i(l)}handleEnd(){const{onAbort:e,onEnd:t}=this.props;this.detach(),this.activated||e(this.props.active),t()}handleCancel(){const{onAbort:e,onCancel:t}=this.props;this.detach(),this.activated||e(this.props.active),t()}handleKeydown(e){e.code===ke.Esc&&this.handleCancel()}removeTextSelection(){var e;null==(e=this.document.getSelection())||e.removeAllRanges()}}const Te={cancel:{name:"pointercancel"},move:{name:"pointermove"},end:{name:"pointerup"}};class Ne extends Re{constructor(e){const{event:t}=e,n=v(t.target);super(e,Te,n)}}Ne.activators=[{eventName:"onPointerDown",handler:(e,t)=>{let{nativeEvent:n}=e,{onActivation:r}=t;return!(!n.isPrimary||0!==n.button||(null==r||r({event:n}),0))}}];const Pe={move:{name:"mousemove"},end:{name:"mouseup"}};var Be;!function(e){e[e.RightClick=2]="RightClick"}(Be||(Be={})),class extends Re{constructor(e){super(e,Pe,v(e.event.target))}}.activators=[{eventName:"onMouseDown",handler:(e,t)=>{let{nativeEvent:n}=e,{onActivation:r}=t;return n.button!==Be.RightClick&&(null==r||r({event:n}),!0)}}];const je={cancel:{name:"touchcancel"},move:{name:"touchmove"},end:{name:"touchend"}};var Ve,He;(class extends Re{constructor(e){super(e,je)}static setup(){return window.addEventListener(je.move.name,e,{capture:!1,passive:!1}),function(){window.removeEventListener(je.move.name,e)};function e(){}}}).activators=[{eventName:"onTouchStart",handler:(e,t)=>{let{nativeEvent:n}=e,{onActivation:r}=t;const{touches:o}=n;return!(o.length>1||(null==r||r({event:n}),0))}}],function(e){e[e.Pointer=0]="Pointer",e[e.DraggableRect=1]="DraggableRect"}(Ve||(Ve={})),function(e){e[e.TreeOrder=0]="TreeOrder",e[e.ReversedTreeOrder=1]="ReversedTreeOrder"}(He||(He={}));const Ie={x:{[pe.Backward]:!1,[pe.Forward]:!1},y:{[pe.Backward]:!1,[pe.Forward]:!1}};var ze,We;!function(e){e[e.Always=0]="Always",e[e.BeforeDragging=1]="BeforeDragging",e[e.WhileDragging=2]="WhileDragging"}(ze||(ze={})),function(e){e.Optimized="optimized"}(We||(We={}));const Ze=new Map;function Fe(e,t){return y((n=>e?n||("function"==typeof t?t(e):e):null),[t,e])}function $e(e){let{callback:t,disabled:n}=e;const o=w(t),i=(0,r.useMemo)((()=>{if(n||"undefined"==typeof window||void 0===window.ResizeObserver)return;const{ResizeObserver:e}=window;return new e(o)}),[n]);return(0,r.useEffect)((()=>()=>null==i?void 0:i.disconnect()),[i]),i}function Ue(e){return new xe(oe(e),e)}function qe(e,t,n){void 0===t&&(t=Ue);const[o,i]=(0,r.useState)(null);function a(){i((r=>{if(!e)return null;var o;if(!1===e.isConnected)return null!=(o=null!=r?r:n)?o:null;const i=t(e);return JSON.stringify(r)===JSON.stringify(i)?r:i}))}const l=function(e){let{callback:t,disabled:n}=e;const o=w(t),i=(0,r.useMemo)((()=>{if(n||"undefined"==typeof window||void 0===window.MutationObserver)return;const{MutationObserver:e}=window;return new e(o)}),[o,n]);return(0,r.useEffect)((()=>()=>null==i?void 0:i.disconnect()),[i]),i}({callback(t){if(e)for(const n of t){const{type:t,target:r}=n;if("childList"===t&&r instanceof HTMLElement&&r.contains(e)){a();break}}}}),c=$e({callback:a});return b((()=>{a(),e?(null==c||c.observe(e),null==l||l.observe(document.body,{childList:!0,subtree:!0})):(null==c||c.disconnect(),null==l||l.disconnect())}),[e]),o}const Ye=[];function Xe(e,t){void 0===t&&(t=[]);const n=(0,r.useRef)(null);return(0,r.useEffect)((()=>{n.current=null}),t),(0,r.useEffect)((()=>{const t=e!==F;t&&!n.current&&(n.current=e),!t&&n.current&&(n.current=null)}),[e]),n.current?S(e,n.current):F}function Ke(e){return(0,r.useMemo)((()=>e?function(e){const t=e.innerWidth,n=e.innerHeight;return{top:0,left:0,right:t,bottom:n,width:t,height:n}}(e):null),[e])}const Ge=[];const Je=[{sensor:Ne,options:{}},{sensor:Ae,options:{}}],Qe={current:{}},et={draggable:{measure:ie},droppable:{measure:ie,strategy:ze.WhileDragging,frequency:We.Optimized},dragOverlay:{measure:oe}};class tt extends Map{get(e){var t;return null!=e&&null!=(t=super.get(e))?t:void 0}toArray(){return Array.from(this.values())}getEnabled(){return this.toArray().filter((e=>{let{disabled:t}=e;return!t}))}getNodeFor(e){var t,n;return null!=(t=null==(n=this.get(e))?void 0:n.node.current)?t:void 0}}const nt={activatorEvent:null,active:null,activeNode:null,activeNodeRect:null,collisions:null,containerNodeRect:null,draggableNodes:new Map,droppableRects:new Map,droppableContainers:new tt,over:null,dragOverlay:{nodeRef:{current:null},rect:null,setRef:W},scrollableAncestors:[],scrollableAncestorRects:[],measuringConfiguration:et,measureDroppableContainers:W,windowRect:null,measuringScheduled:!1},rt={activatorEvent:null,activators:[],active:null,activeNodeRect:null,ariaDescribedById:{draggable:""},dispatch:W,draggableNodes:new Map,over:null,measureDroppableContainers:W},ot=(0,r.createContext)(rt),it=(0,r.createContext)(nt);function at(){return{draggable:{active:null,initialCoordinates:{x:0,y:0},nodes:new Map,translate:{x:0,y:0}},droppable:{containers:new tt}}}function lt(e,t){switch(t.type){case z.DragStart:return{...e,draggable:{...e.draggable,initialCoordinates:t.initialCoordinates,active:t.active}};case z.DragMove:return null==e.draggable.active?e:{...e,draggable:{...e.draggable,translate:{x:t.coordinates.x-e.draggable.initialCoordinates.x,y:t.coordinates.y-e.draggable.initialCoordinates.y}}};case z.DragEnd:case z.DragCancel:return{...e,draggable:{...e.draggable,active:null,initialCoordinates:{x:0,y:0},translate:{x:0,y:0}}};case z.RegisterDroppable:{const{element:n}=t,{id:r}=n,o=new tt(e.droppable.containers);return o.set(r,n),{...e,droppable:{...e.droppable,containers:o}}}case z.SetDroppableDisabled:{const{id:n,key:r,disabled:o}=t,i=e.droppable.containers.get(n);if(!i||r!==i.key)return e;const a=new tt(e.droppable.containers);return a.set(n,{...i,disabled:o}),{...e,droppable:{...e.droppable,containers:a}}}case z.UnregisterDroppable:{const{id:n,key:r}=t,o=e.droppable.containers.get(n);if(!o||r!==o.key)return e;const i=new tt(e.droppable.containers);return i.delete(n),{...e,droppable:{...e.droppable,containers:i}}}default:return e}}function ct(e){let{disabled:t}=e;const{active:n,activatorEvent:o,draggableNodes:i}=(0,r.useContext)(ot),a=_(o),l=_(null==n?void 0:n.id);return(0,r.useEffect)((()=>{if(!t&&!o&&a&&null!=l){if(!A(a))return;if(document.activeElement===a.target)return;const e=i.get(l);if(!e)return;const{activatorNode:t,node:n}=e;if(!t.current&&!n.current)return;requestAnimationFrame((()=>{for(const e of[t.current,n.current]){if(!e)continue;const t=T(e);if(t){t.focus();break}}}))}}),[o,t,i,l,a]),null}const st=(0,r.createContext)({...F,scaleX:1,scaleY:1});var dt;!function(e){e[e.Uninitialized=0]="Uninitialized",e[e.Initializing=1]="Initializing",e[e.Initialized=2]="Initialized"}(dt||(dt={}));const ut=(0,r.memo)((function(e){var t,n,i,a;let{id:l,accessibility:c,autoScroll:u=!0,children:p,sensors:h=Je,collisionDetection:m=Q,measuring:v,modifiers:w,...k}=e;const O=(0,r.useReducer)(lt,void 0,at),[S,A]=O,[L,R]=function(){const[e]=(0,r.useState)((()=>new Set)),t=(0,r.useCallback)((t=>(e.add(t),()=>e.delete(t))),[e]);return[(0,r.useCallback)((t=>{let{type:n,event:r}=t;e.forEach((e=>{var t;return null==(t=e[n])?void 0:t.call(e,r)}))}),[e]),t]}(),[T,N]=(0,r.useState)(dt.Uninitialized),P=T===dt.Initialized,{draggable:{active:B,nodes:V,translate:H},droppable:{containers:W}}=S,Z=null!=B?V.get(B):null,$=(0,r.useRef)({initial:null,translated:null}),U=(0,r.useMemo)((()=>{var e;return null!=B?{id:B,data:null!=(e=null==Z?void 0:Z.data)?e:Qe,rect:$}:null}),[B,Z]),q=(0,r.useRef)(null),[Y,K]=(0,r.useState)(null),[G,J]=(0,r.useState)(null),te=x(k,Object.values(k)),re=E("DndDescribedBy",l),ie=(0,r.useMemo)((()=>W.getEnabled()),[W]),se=(de=v,(0,r.useMemo)((()=>({draggable:{...et.draggable,...null==de?void 0:de.draggable},droppable:{...et.droppable,...null==de?void 0:de.droppable},dragOverlay:{...et.dragOverlay,...null==de?void 0:de.dragOverlay}})),[null==de?void 0:de.draggable,null==de?void 0:de.droppable,null==de?void 0:de.dragOverlay]));var de;const{droppableRects:he,measureDroppableContainers:ge,measuringScheduled:ve}=function(e,t){let{dragging:n,dependencies:o,config:i}=t;const[a,l]=(0,r.useState)(null),{frequency:c,measure:s,strategy:d}=i,u=(0,r.useRef)(e),p=function(){switch(d){case ze.Always:return!1;case ze.BeforeDragging:return n;default:return!n}}(),f=x(p),h=(0,r.useCallback)((function(e){void 0===e&&(e=[]),f.current||l((t=>null===t?e:t.concat(e.filter((e=>!t.includes(e))))))}),[f]),g=(0,r.useRef)(null),m=y((t=>{if(p&&!n)return Ze;if(!t||t===Ze||u.current!==e||null!=a){const t=new Map;for(let n of e){if(!n)continue;if(a&&a.length>0&&!a.includes(n.id)&&n.rect.current){t.set(n.id,n.rect.current);continue}const e=n.node.current,r=e?new xe(s(e),e):null;n.rect.current=r,r&&t.set(n.id,r)}return t}return t}),[e,a,n,p,s]);return(0,r.useEffect)((()=>{u.current=e}),[e]),(0,r.useEffect)((()=>{p||h()}),[n,p]),(0,r.useEffect)((()=>{a&&a.length>0&&l(null)}),[JSON.stringify(a)]),(0,r.useEffect)((()=>{p||"number"!=typeof c||null!==g.current||(g.current=setTimeout((()=>{h(),g.current=null}),c))}),[c,p,h,...o]),{droppableRects:m,measureDroppableContainers:h,measuringScheduled:null!=a}}(ie,{dragging:P,dependencies:[H.x,H.y],config:se.droppable}),we=function(e,t){const n=null!=t?e.get(t):void 0,r=n?n.node.current:null;return y((e=>{var n;return null==t?null:null!=(n=null!=r?r:e)?n:null}),[r,t])}(V,B),ye=(0,r.useMemo)((()=>G?D(G):null),[G]),Ce=function(){const e=!1===(null==Y?void 0:Y.autoScrollEnabled),t="object"==typeof u?!1===u.enabled:!1===u,n=P&&!e&&!t;return"object"==typeof u?{...u,enabled:n}:{enabled:n}}(),_e=function(e,t){return Fe(e,t)}(we,se.draggable.measure);!function(e){let{activeNode:t,measure:n,initialRect:o,config:i=!0}=e;const a=(0,r.useRef)(!1),{x:l,y:c}="boolean"==typeof i?{x:i,y:i}:i;b((()=>{if(!l&&!c||!t)return void(a.current=!1);if(a.current||!o)return;const e=null==t?void 0:t.node.current;if(!e||!1===e.isConnected)return;const r=ee(n(e),o);if(l||(r.x=0),c||(r.y=0),a.current=!0,Math.abs(r.x)>0||Math.abs(r.y)>0){const t=le(e);t&&t.scrollBy({top:r.y,left:r.x})}}),[t,l,c,o,n])}({activeNode:null!=B?V.get(B):null,config:Ce.layoutShiftCompensation,initialRect:_e,measure:se.draggable.measure});const ke=qe(we,se.draggable.measure,_e),Ee=qe(we?we.parentElement:null),Oe=(0,r.useRef)({activatorEvent:null,active:null,activeNode:we,collisionRect:null,collisions:null,droppableRects:he,draggableNodes:V,draggingNode:null,draggingNodeRect:null,droppableContainers:W,over:null,scrollableAncestors:[],scrollAdjustedTranslate:null}),Me=W.getNodeFor(null==(t=Oe.current.over)?void 0:t.id),Se=function(e){let{measure:t}=e;const[n,o]=(0,r.useState)(null),i=$e({callback:(0,r.useCallback)((e=>{for(const{target:n}of e)if(g(n)){o((e=>{const r=t(n);return e?{...e,width:r.width,height:r.height}:r}));break}}),[t])}),a=(0,r.useCallback)((e=>{const n=function(e){if(!e)return null;if(e.children.length>1)return e;const t=e.children[0];return g(t)?t:e}(e);null==i||i.disconnect(),n&&(null==i||i.observe(n)),o(n?t(n):null)}),[t,i]),[l,c]=C(a);return(0,r.useMemo)((()=>({nodeRef:l,rect:n,setRef:c})),[n,l,c])}({measure:se.dragOverlay.measure}),Ae=null!=(n=Se.nodeRef.current)?n:we,De=P?null!=(i=Se.rect)?i:ke:null,Le=Boolean(Se.nodeRef.current&&Se.rect),Re=ee(Te=Le?null:ke,Fe(Te));var Te;const Ne=Ke(Ae?f(Ae):null),Pe=function(e){const t=(0,r.useRef)(e),n=y((n=>e?n&&n!==Ye&&e&&t.current&&e.parentNode===t.current.parentNode?n:ae(e):Ye),[e]);return(0,r.useEffect)((()=>{t.current=e}),[e]),n}(P?null!=Me?Me:we:null),Be=function(e,t){void 0===t&&(t=oe);const[n]=e,o=Ke(n?f(n):null),[i,a]=(0,r.useState)(Ge);function l(){a((()=>e.length?e.map((e=>fe(e)?o:new xe(t(e),e))):Ge))}const c=$e({callback:l});return b((()=>{null==c||c.disconnect(),l(),e.forEach((e=>null==c?void 0:c.observe(e)))}),[e]),i}(Pe),je=function(e,t){let{transform:n,...r}=t;return null!=e&&e.length?e.reduce(((e,t)=>t({transform:e,...r})),n):n}(w,{transform:{x:H.x-Re.x,y:H.y-Re.y,scaleX:1,scaleY:1},activatorEvent:G,active:U,activeNodeRect:ke,containerNodeRect:Ee,draggingNodeRect:De,over:Oe.current.over,overlayNodeRect:Se.rect,scrollableAncestors:Pe,scrollableAncestorRects:Be,windowRect:Ne}),We=ye?M(ye,H):null,Ue=function(e){const[t,n]=(0,r.useState)(null),o=(0,r.useRef)(e),i=(0,r.useCallback)((e=>{const t=ce(e.target);t&&n((e=>e?(e.set(t,ue(t)),new Map(e)):null))}),[]);return(0,r.useEffect)((()=>{const t=o.current;if(e!==t){r(t);const a=e.map((e=>{const t=ce(e);return t?(t.addEventListener("scroll",i,{passive:!0}),[t,ue(t)]):null})).filter((e=>null!=e));n(a.length?new Map(a):null),o.current=e}return()=>{r(e),r(t)};function r(e){e.forEach((e=>{const t=ce(e);null==t||t.removeEventListener("scroll",i)}))}}),[i,e]),(0,r.useMemo)((()=>e.length?t?Array.from(t.values()).reduce(((e,t)=>M(e,t)),F):be(e):F),[e,t])}(Pe),tt=Xe(Ue),nt=Xe(Ue,[ke]),rt=M(je,tt),ut=De?ne(De,je):null,pt=U&&ut?m({active:U,collisionRect:ut,droppableRects:he,droppableContainers:ie,pointerCoordinates:We}):null,ft=X(pt,"id"),[ht,gt]=(0,r.useState)(null),mt=function(e,t,n){return{...e,scaleX:t&&n?t.width/n.width:1,scaleY:t&&n?t.height/n.height:1}}(Le?je:M(je,nt),null!=(a=null==ht?void 0:ht.rect)?a:null,ke),vt=(0,r.useRef)(null),bt=(0,r.useCallback)(((e,t)=>{let{sensor:n,options:r}=t;if(null==q.current)return;const o=V.get(q.current);if(!o)return;const i=e.nativeEvent,a=new n({active:q.current,activeNode:o,event:i,options:r,context:Oe,onAbort(e){if(!V.get(e))return;const{onDragAbort:t}=te.current,n={id:e};null==t||t(n),L({type:"onDragAbort",event:n})},onPending(e,t,n,r){if(!V.get(e))return;const{onDragPending:o}=te.current,i={id:e,constraint:t,initialCoordinates:n,offset:r};null==o||o(i),L({type:"onDragPending",event:i})},onStart(e){const t=q.current;if(null==t)return;const n=V.get(t);if(!n)return;const{onDragStart:r}=te.current,o={activatorEvent:i,active:{id:t,data:n.data,rect:$}};(0,s.unstable_batchedUpdates)((()=>{null==r||r(o),N(dt.Initializing),A({type:z.DragStart,initialCoordinates:e,active:t}),L({type:"onDragStart",event:o}),K(vt.current),J(i)}))},onMove(e){A({type:z.DragMove,coordinates:e})},onEnd:l(z.DragEnd),onCancel:l(z.DragCancel)});function l(e){return async function(){const{active:t,collisions:n,over:r,scrollAdjustedTranslate:o}=Oe.current;let a=null;if(t&&o){const{cancelDrop:l}=te.current;a={activatorEvent:i,active:t,collisions:n,delta:o,over:r},e===z.DragEnd&&"function"==typeof l&&await Promise.resolve(l(a))&&(e=z.DragCancel)}q.current=null,(0,s.unstable_batchedUpdates)((()=>{A({type:e}),N(dt.Uninitialized),gt(null),K(null),J(null),vt.current=null;const t=e===z.DragEnd?"onDragEnd":"onDragCancel";if(a){const e=te.current[t];null==e||e(a),L({type:t,event:a})}}))}}vt.current=a}),[V]),wt=(0,r.useCallback)(((e,t)=>(n,r)=>{const o=n.nativeEvent,i=V.get(r);if(null!==q.current||!i||o.dndKit||o.defaultPrevented)return;const a={active:i};!0===e(n,t.options,a)&&(o.dndKit={capturedBy:t.sensor},q.current=r,bt(n,t))}),[V,bt]),xt=function(e,t){return(0,r.useMemo)((()=>e.reduce(((e,n)=>{const{sensor:r}=n;return[...e,...r.activators.map((e=>({eventName:e.eventName,handler:t(e.handler,n)})))]}),[])),[e,t])}(h,wt);!function(e){(0,r.useEffect)((()=>{if(!d)return;const t=e.map((e=>{let{sensor:t}=e;return null==t.setup?void 0:t.setup()}));return()=>{for(const e of t)null==e||e()}}),e.map((e=>{let{sensor:t}=e;return t})))}(h),b((()=>{ke&&T===dt.Initializing&&N(dt.Initialized)}),[ke,T]),(0,r.useEffect)((()=>{const{onDragMove:e}=te.current,{active:t,activatorEvent:n,collisions:r,over:o}=Oe.current;if(!t||!n)return;const i={active:t,activatorEvent:n,collisions:r,delta:{x:rt.x,y:rt.y},over:o};(0,s.unstable_batchedUpdates)((()=>{null==e||e(i),L({type:"onDragMove",event:i})}))}),[rt.x,rt.y]),(0,r.useEffect)((()=>{const{active:e,activatorEvent:t,collisions:n,droppableContainers:r,scrollAdjustedTranslate:o}=Oe.current;if(!e||null==q.current||!t||!o)return;const{onDragOver:i}=te.current,a=r.get(ft),l=a&&a.rect.current?{id:a.id,rect:a.rect.current,data:a.data,disabled:a.disabled}:null,c={active:e,activatorEvent:t,collisions:n,delta:{x:o.x,y:o.y},over:l};(0,s.unstable_batchedUpdates)((()=>{gt(l),null==i||i(c),L({type:"onDragOver",event:c})}))}),[ft]),b((()=>{Oe.current={activatorEvent:G,active:U,activeNode:we,collisionRect:ut,collisions:pt,droppableRects:he,draggableNodes:V,draggingNode:Ae,draggingNodeRect:De,droppableContainers:W,over:ht,scrollableAncestors:Pe,scrollAdjustedTranslate:rt},$.current={initial:De,translated:ut}}),[U,we,pt,ut,V,Ae,De,he,W,ht,Pe,rt]),function(e){let{acceleration:t,activator:n=Ve.Pointer,canScroll:o,draggingRect:i,enabled:a,interval:l=5,order:c=He.TreeOrder,pointerCoordinates:s,scrollableAncestors:d,scrollableAncestorRects:u,delta:p,threshold:f}=e;const h=function(e){let{delta:t,disabled:n}=e;const r=_(t);return y((e=>{if(n||!r||!e)return Ie;const o=Math.sign(t.x-r.x),i=Math.sign(t.y-r.y);return{x:{[pe.Backward]:e.x[pe.Backward]||-1===o,[pe.Forward]:e.x[pe.Forward]||1===o},y:{[pe.Backward]:e.y[pe.Backward]||-1===i,[pe.Forward]:e.y[pe.Forward]||1===i}}}),[n,t,r])}({delta:p,disabled:!a}),[g,m]=function(){const e=(0,r.useRef)(null);return[(0,r.useCallback)(((t,n)=>{e.current=setInterval(t,n)}),[]),(0,r.useCallback)((()=>{null!==e.current&&(clearInterval(e.current),e.current=null)}),[])]}(),v=(0,r.useRef)({x:0,y:0}),b=(0,r.useRef)({x:0,y:0}),w=(0,r.useMemo)((()=>{switch(n){case Ve.Pointer:return s?{top:s.y,bottom:s.y,left:s.x,right:s.x}:null;case Ve.DraggableRect:return i}}),[n,i,s]),x=(0,r.useRef)(null),C=(0,r.useCallback)((()=>{const e=x.current;if(!e)return;const t=v.current.x*b.current.x,n=v.current.y*b.current.y;e.scrollBy(t,n)}),[]),k=(0,r.useMemo)((()=>c===He.TreeOrder?[...d].reverse():d),[c,d]);(0,r.useEffect)((()=>{if(a&&d.length&&w){for(const e of k){if(!1===(null==o?void 0:o(e)))continue;const n=d.indexOf(e),r=u[n];if(!r)continue;const{direction:i,speed:a}=me(e,r,w,t,f);for(const e of["x","y"])h[e][i[e]]||(a[e]=0,i[e]=0);if(a.x>0||a.y>0)return m(),x.current=e,g(C,l),v.current=a,void(b.current=i)}v.current={x:0,y:0},b.current={x:0,y:0},m()}else m()}),[t,C,o,m,a,l,JSON.stringify(w),JSON.stringify(h),g,d,k,u,JSON.stringify(f)])}({...Ce,delta:H,draggingRect:ut,pointerCoordinates:We,scrollableAncestors:Pe,scrollableAncestorRects:Be});const yt=(0,r.useMemo)((()=>({active:U,activeNode:we,activeNodeRect:ke,activatorEvent:G,collisions:pt,containerNodeRect:Ee,dragOverlay:Se,draggableNodes:V,droppableContainers:W,droppableRects:he,over:ht,measureDroppableContainers:ge,scrollableAncestors:Pe,scrollableAncestorRects:Be,measuringConfiguration:se,measuringScheduled:ve,windowRect:Ne})),[U,we,ke,G,pt,Ee,Se,V,W,he,ht,ge,Pe,Be,se,ve,Ne]),Ct=(0,r.useMemo)((()=>({activatorEvent:G,activators:xt,active:U,activeNodeRect:ke,ariaDescribedById:{draggable:re},dispatch:A,draggableNodes:V,over:ht,measureDroppableContainers:ge})),[G,xt,U,ke,A,re,V,ht,ge]);return o().createElement(j.Provider,{value:R},o().createElement(ot.Provider,{value:Ct},o().createElement(it.Provider,{value:yt},o().createElement(st.Provider,{value:mt},p)),o().createElement(ct,{disabled:!1===(null==c?void 0:c.restoreFocus)})),o().createElement(I,{...c,hiddenTextDescribedById:re}))})),pt=(0,r.createContext)(null),ft="button";const ht={timeout:25};function gt(e,t,n){const r=e.slice();return r.splice(n<0?r.length+n:n,0,r.splice(t,1)[0]),r}function mt(e,t){return e.reduce(((e,n,r)=>{const o=t.get(n);return o&&(e[r]=o),e}),Array(e.length))}function vt(e){return null!==e&&e>=0}const bt=e=>{let{rects:t,activeIndex:n,overIndex:r,index:o}=e;const i=gt(t,r,n),a=t[o],l=i[o];return l&&a?{x:l.left-a.left,y:l.top-a.top,scaleX:l.width/a.width,scaleY:l.height/a.height}:null},wt="Sortable",xt=o().createContext({activeIndex:-1,containerId:wt,disableTransforms:!1,items:[],overIndex:-1,useDragOverlay:!1,sortedRects:[],strategy:bt,disabled:{draggable:!1,droppable:!1}});function yt(e){let{children:t,id:n,items:i,strategy:a=bt,disabled:l=!1}=e;const{active:c,dragOverlay:s,droppableRects:d,over:u,measureDroppableContainers:p}=(0,r.useContext)(it),f=E(wt,n),h=Boolean(null!==s.rect),g=(0,r.useMemo)((()=>i.map((e=>"object"==typeof e&&"id"in e?e.id:e))),[i]),m=null!=c,v=c?g.indexOf(c.id):-1,w=u?g.indexOf(u.id):-1,x=(0,r.useRef)(g),y=!function(e,t){if(e===t)return!0;if(e.length!==t.length)return!1;for(let n=0;n<e.length;n++)if(e[n]!==t[n])return!1;return!0}(g,x.current),C=-1!==w&&-1===v||y,_=function(e){return"boolean"==typeof e?{draggable:e,droppable:e}:e}(l);b((()=>{y&&m&&p(g)}),[y,g,m,p]),(0,r.useEffect)((()=>{x.current=g}),[g]);const k=(0,r.useMemo)((()=>({activeIndex:v,containerId:f,disabled:_,disableTransforms:C,items:g,overIndex:w,useDragOverlay:h,sortedRects:mt(g,d),strategy:a})),[v,f,_.draggable,_.droppable,C,g,w,d,h,a]);return o().createElement(xt.Provider,{value:k},t)}const Ct=e=>{let{id:t,items:n,activeIndex:r,overIndex:o}=e;return gt(n,r,o).indexOf(t)},_t=e=>{let{containerId:t,isSorting:n,wasDragging:r,index:o,items:i,newIndex:a,previousItems:l,previousContainerId:c,transition:s}=e;return!(!s||!r||l!==i&&o===a||!n&&(a===o||t!==c))},kt={duration:200,easing:"ease"},Et="transform",Ot=L.Transition.toString({property:Et,duration:0,easing:"linear"}),Mt={roleDescription:"sortable"};function St(e){let{animateLayoutChanges:t=_t,attributes:n,disabled:o,data:i,getNewIndex:a=Ct,id:l,strategy:c,resizeObserverConfig:s,transition:d=kt}=e;const{items:u,containerId:p,activeIndex:f,disabled:h,disableTransforms:g,sortedRects:m,overIndex:v,useDragOverlay:w,strategy:y}=(0,r.useContext)(xt),_=function(e,t){var n,r;return"boolean"==typeof e?{draggable:e,droppable:!1}:{draggable:null!=(n=null==e?void 0:e.draggable)?n:t.draggable,droppable:null!=(r=null==e?void 0:e.droppable)?r:t.droppable}}(o,h),k=u.indexOf(l),O=(0,r.useMemo)((()=>({sortable:{containerId:p,index:k,items:u},...i})),[p,i,k,u]),M=(0,r.useMemo)((()=>u.slice(u.indexOf(l))),[u,l]),{rect:S,node:D,isOver:R,setNodeRef:T}=function(e){let{data:t,disabled:n=!1,id:o,resizeObserverConfig:i}=e;const a=E("Droppable"),{active:l,dispatch:c,over:s,measureDroppableContainers:d}=(0,r.useContext)(ot),u=(0,r.useRef)({disabled:n}),p=(0,r.useRef)(!1),f=(0,r.useRef)(null),h=(0,r.useRef)(null),{disabled:g,updateMeasurementsFor:m,timeout:v}={...ht,...i},b=x(null!=m?m:o),w=$e({callback:(0,r.useCallback)((()=>{p.current?(null!=h.current&&clearTimeout(h.current),h.current=setTimeout((()=>{d(Array.isArray(b.current)?b.current:[b.current]),h.current=null}),v)):p.current=!0}),[v]),disabled:g||!l}),y=(0,r.useCallback)(((e,t)=>{w&&(t&&(w.unobserve(t),p.current=!1),e&&w.observe(e))}),[w]),[_,k]=C(y),O=x(t);return(0,r.useEffect)((()=>{w&&_.current&&(w.disconnect(),p.current=!1,w.observe(_.current))}),[_,w]),(0,r.useEffect)((()=>(c({type:z.RegisterDroppable,element:{id:o,key:a,disabled:n,node:_,rect:f,data:O}}),()=>c({type:z.UnregisterDroppable,key:a,id:o}))),[o]),(0,r.useEffect)((()=>{n!==u.current.disabled&&(c({type:z.SetDroppableDisabled,id:o,key:a,disabled:n}),u.current.disabled=n)}),[o,a,n,c]),{active:l,rect:f,isOver:(null==s?void 0:s.id)===o,node:_,over:s,setNodeRef:k}}({id:l,data:O,disabled:_.droppable,resizeObserverConfig:{updateMeasurementsFor:M,...s}}),{active:N,activatorEvent:P,activeNodeRect:B,attributes:j,setNodeRef:V,listeners:H,isDragging:I,over:W,setActivatorNodeRef:Z,transform:F}=function(e){let{id:t,data:n,disabled:o=!1,attributes:i}=e;const a=E("Draggable"),{activators:l,activatorEvent:c,active:s,activeNodeRect:d,ariaDescribedById:u,draggableNodes:p,over:f}=(0,r.useContext)(ot),{role:h=ft,roleDescription:g="draggable",tabIndex:m=0}=null!=i?i:{},v=(null==s?void 0:s.id)===t,w=(0,r.useContext)(v?st:pt),[y,_]=C(),[k,O]=C(),M=function(e,t){return(0,r.useMemo)((()=>e.reduce(((e,n)=>{let{eventName:r,handler:o}=n;return e[r]=e=>{o(e,t)},e}),{})),[e,t])}(l,t),S=x(n);return b((()=>(p.set(t,{id:t,key:a,node:y,activatorNode:k,data:S}),()=>{const e=p.get(t);e&&e.key===a&&p.delete(t)})),[p,t]),{active:s,activatorEvent:c,activeNodeRect:d,attributes:(0,r.useMemo)((()=>({role:h,tabIndex:m,"aria-disabled":o,"aria-pressed":!(!v||h!==ft)||void 0,"aria-roledescription":g,"aria-describedby":u.draggable})),[o,h,m,v,g,u.draggable]),isDragging:v,listeners:o?void 0:M,node:y,over:f,setNodeRef:_,setActivatorNodeRef:O,transform:w}}({id:l,data:O,attributes:{...Mt,...n},disabled:_.draggable}),$=function(){for(var e=arguments.length,t=new Array(e),n=0;n<e;n++)t[n]=arguments[n];return(0,r.useMemo)((()=>e=>{t.forEach((t=>t(e)))}),t)}(T,V),U=Boolean(N),q=U&&!g&&vt(f)&&vt(v),Y=!w&&I,X=Y&&q?F:null,K=q?null!=X?X:(null!=c?c:y)({rects:m,activeNodeRect:B,activeIndex:f,overIndex:v,index:k}):null,G=vt(f)&&vt(v)?a({id:l,items:u,activeIndex:f,overIndex:v}):k,J=null==N?void 0:N.id,Q=(0,r.useRef)({activeId:J,items:u,newIndex:G,containerId:p}),ee=u!==Q.current.items,te=t({active:N,containerId:p,isDragging:I,isSorting:U,id:l,index:k,items:u,newIndex:Q.current.newIndex,previousItems:Q.current.items,previousContainerId:Q.current.containerId,transition:d,wasDragging:null!=Q.current.activeId}),ne=function(e){let{disabled:t,index:n,node:o,rect:i}=e;const[a,l]=(0,r.useState)(null),c=(0,r.useRef)(n);return b((()=>{if(!t&&n!==c.current&&o.current){const e=i.current;if(e){const t=oe(o.current,{ignoreTransform:!0}),n={x:e.left-t.left,y:e.top-t.top,scaleX:e.width/t.width,scaleY:e.height/t.height};(n.x||n.y)&&l(n)}}n!==c.current&&(c.current=n)}),[t,n,o,i]),(0,r.useEffect)((()=>{a&&l(null)}),[a]),a}({disabled:!te,index:k,node:D,rect:S});return(0,r.useEffect)((()=>{U&&Q.current.newIndex!==G&&(Q.current.newIndex=G),p!==Q.current.containerId&&(Q.current.containerId=p),u!==Q.current.items&&(Q.current.items=u)}),[U,G,p,u]),(0,r.useEffect)((()=>{if(J===Q.current.activeId)return;if(J&&!Q.current.activeId)return void(Q.current.activeId=J);const e=setTimeout((()=>{Q.current.activeId=J}),50);return()=>clearTimeout(e)}),[J]),{active:N,activeIndex:f,attributes:j,data:O,rect:S,index:k,newIndex:G,items:u,isOver:R,isSorting:U,isDragging:I,listeners:H,node:D,overIndex:v,over:W,setNodeRef:$,setActivatorNodeRef:Z,setDroppableNodeRef:T,setDraggableNodeRef:V,transform:null!=ne?ne:K,transition:ne||ee&&Q.current.newIndex===k?Ot:Y&&!A(P)||!d?void 0:U||te?L.Transition.toString({...d,property:Et}):void 0}}function At(e){if(!e)return!1;const t=e.data.current;return!!(t&&"sortable"in t&&"object"==typeof t.sortable&&"containerId"in t.sortable&&"items"in t.sortable&&"index"in t.sortable)}const Dt=[ke.Down,ke.Right,ke.Up,ke.Left],Lt=(e,t)=>{let{context:{active:n,collisionRect:r,droppableRects:o,droppableContainers:i,over:a,scrollableAncestors:l}}=t;if(Dt.includes(e.code)){if(e.preventDefault(),!n||!r)return;const t=[];i.getEnabled().forEach((n=>{if(!n||null!=n&&n.disabled)return;const i=o.get(n.id);if(i)switch(e.code){case ke.Down:r.top<i.top&&t.push(n);break;case ke.Up:r.top>i.top&&t.push(n);break;case ke.Left:r.left>i.left&&t.push(n);break;case ke.Right:r.left<i.left&&t.push(n)}}));const d=(e=>{let{collisionRect:t,droppableRects:n,droppableContainers:r}=e;const o=Y(t),i=[];for(const e of r){const{id:t}=e,r=n.get(t);if(r){const n=Y(r),a=o.reduce(((e,t,r)=>e+$(n[r],t)),0),l=Number((a/4).toFixed(4));i.push({id:t,data:{droppableContainer:e,value:l}})}}return i.sort(U)})({active:n,collisionRect:r,droppableRects:o,droppableContainers:t,pointerCoordinates:null});let u=X(d,"id");if(u===(null==a?void 0:a.id)&&d.length>1&&(u=d[1].id),null!=u){const e=i.get(n.id),t=i.get(u),a=t?o.get(t.id):null,d=null==t?void 0:t.node.current;if(d&&a&&e&&t){const n=ae(d).some(((e,t)=>l[t]!==e)),o=Rt(e,t),i=(s=t,!(!At(c=e)||!At(s))&&!!Rt(c,s)&&c.data.current.sortable.index<s.data.current.sortable.index),u=n||!o?{x:0,y:0}:{x:i?r.width-a.width:0,y:i?r.height-a.height:0},p={x:a.left,y:a.top};return u.x&&u.y?p:S(p,u)}}}var c,s};function Rt(e,t){return!(!At(e)||!At(t))&&e.data.current.sortable.containerId===t.data.current.sortable.containerId}function Tt(){return Tt=Object.assign?Object.assign.bind():function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)({}).hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e},Tt.apply(null,arguments)}var Nt=function(){function e(e){var t=this;this._insertTag=function(e){var n;n=0===t.tags.length?t.insertionPoint?t.insertionPoint.nextSibling:t.prepend?t.container.firstChild:t.before:t.tags[t.tags.length-1].nextSibling,t.container.insertBefore(e,n),t.tags.push(e)},this.isSpeedy=void 0===e.speedy||e.speedy,this.tags=[],this.ctr=0,this.nonce=e.nonce,this.key=e.key,this.container=e.container,this.prepend=e.prepend,this.insertionPoint=e.insertionPoint,this.before=null}var t=e.prototype;return t.hydrate=function(e){e.forEach(this._insertTag)},t.insert=function(e){this.ctr%(this.isSpeedy?65e3:1)==0&&this._insertTag(function(e){var t=document.createElement("style");return t.setAttribute("data-emotion",e.key),void 0!==e.nonce&&t.setAttribute("nonce",e.nonce),t.appendChild(document.createTextNode("")),t.setAttribute("data-s",""),t}(this));var t=this.tags[this.tags.length-1];if(this.isSpeedy){var n=function(e){if(e.sheet)return e.sheet;for(var t=0;t<document.styleSheets.length;t++)if(document.styleSheets[t].ownerNode===e)return document.styleSheets[t]}(t);try{n.insertRule(e,n.cssRules.length)}catch(e){}}else t.appendChild(document.createTextNode(e));this.ctr++},t.flush=function(){this.tags.forEach((function(e){var t;return null==(t=e.parentNode)?void 0:t.removeChild(e)})),this.tags=[],this.ctr=0},e}(),Pt=Math.abs,Bt=String.fromCharCode,jt=Object.assign;function Vt(e){return e.trim()}function Ht(e,t,n){return e.replace(t,n)}function It(e,t){return e.indexOf(t)}function zt(e,t){return 0|e.charCodeAt(t)}function Wt(e,t,n){return e.slice(t,n)}function Zt(e){return e.length}function Ft(e){return e.length}function $t(e,t){return t.push(e),e}var Ut=1,qt=1,Yt=0,Xt=0,Kt=0,Gt="";function Jt(e,t,n,r,o,i,a){return{value:e,root:t,parent:n,type:r,props:o,children:i,line:Ut,column:qt,length:a,return:""}}function Qt(e,t){return jt(Jt("",null,null,"",null,null,0),e,{length:-e.length},t)}function en(){return Kt=Xt>0?zt(Gt,--Xt):0,qt--,10===Kt&&(qt=1,Ut--),Kt}function tn(){return Kt=Xt<Yt?zt(Gt,Xt++):0,qt++,10===Kt&&(qt=1,Ut++),Kt}function nn(){return zt(Gt,Xt)}function rn(){return Xt}function on(e,t){return Wt(Gt,e,t)}function an(e){switch(e){case 0:case 9:case 10:case 13:case 32:return 5;case 33:case 43:case 44:case 47:case 62:case 64:case 126:case 59:case 123:case 125:return 4;case 58:return 3;case 34:case 39:case 40:case 91:return 2;case 41:case 93:return 1}return 0}function ln(e){return Ut=qt=1,Yt=Zt(Gt=e),Xt=0,[]}function cn(e){return Gt="",e}function sn(e){return Vt(on(Xt-1,pn(91===e?e+2:40===e?e+1:e)))}function dn(e){for(;(Kt=nn())&&Kt<33;)tn();return an(e)>2||an(Kt)>3?"":" "}function un(e,t){for(;--t&&tn()&&!(Kt<48||Kt>102||Kt>57&&Kt<65||Kt>70&&Kt<97););return on(e,rn()+(t<6&&32==nn()&&32==tn()))}function pn(e){for(;tn();)switch(Kt){case e:return Xt;case 34:case 39:34!==e&&39!==e&&pn(Kt);break;case 40:41===e&&pn(e);break;case 92:tn()}return Xt}function fn(e,t){for(;tn()&&e+Kt!==57&&(e+Kt!==84||47!==nn()););return"/*"+on(t,Xt-1)+"*"+Bt(47===e?e:tn())}function hn(e){for(;!an(nn());)tn();return on(e,Xt)}var gn="-ms-",mn="-moz-",vn="-webkit-",bn="comm",wn="rule",xn="decl",yn="@keyframes";function Cn(e,t){for(var n="",r=Ft(e),o=0;o<r;o++)n+=t(e[o],o,e,t)||"";return n}function _n(e,t,n,r){switch(e.type){case"@layer":if(e.children.length)break;case"@import":case xn:return e.return=e.return||e.value;case bn:return"";case yn:return e.return=e.value+"{"+Cn(e.children,r)+"}";case wn:e.value=e.props.join(",")}return Zt(n=Cn(e.children,r))?e.return=e.value+"{"+n+"}":""}function kn(e){return cn(En("",null,null,null,[""],e=ln(e),0,[0],e))}function En(e,t,n,r,o,i,a,l,c){for(var s=0,d=0,u=a,p=0,f=0,h=0,g=1,m=1,v=1,b=0,w="",x=o,y=i,C=r,_=w;m;)switch(h=b,b=tn()){case 40:if(108!=h&&58==zt(_,u-1)){-1!=It(_+=Ht(sn(b),"&","&\f"),"&\f")&&(v=-1);break}case 34:case 39:case 91:_+=sn(b);break;case 9:case 10:case 13:case 32:_+=dn(h);break;case 92:_+=un(rn()-1,7);continue;case 47:switch(nn()){case 42:case 47:$t(Mn(fn(tn(),rn()),t,n),c);break;default:_+="/"}break;case 123*g:l[s++]=Zt(_)*v;case 125*g:case 59:case 0:switch(b){case 0:case 125:m=0;case 59+d:-1==v&&(_=Ht(_,/\f/g,"")),f>0&&Zt(_)-u&&$t(f>32?Sn(_+";",r,n,u-1):Sn(Ht(_," ","")+";",r,n,u-2),c);break;case 59:_+=";";default:if($t(C=On(_,t,n,s,d,o,l,w,x=[],y=[],u),i),123===b)if(0===d)En(_,t,C,C,x,i,u,l,y);else switch(99===p&&110===zt(_,3)?100:p){case 100:case 108:case 109:case 115:En(e,C,C,r&&$t(On(e,C,C,0,0,o,l,w,o,x=[],u),y),o,y,u,l,r?x:y);break;default:En(_,C,C,C,[""],y,0,l,y)}}s=d=f=0,g=v=1,w=_="",u=a;break;case 58:u=1+Zt(_),f=h;default:if(g<1)if(123==b)--g;else if(125==b&&0==g++&&125==en())continue;switch(_+=Bt(b),b*g){case 38:v=d>0?1:(_+="\f",-1);break;case 44:l[s++]=(Zt(_)-1)*v,v=1;break;case 64:45===nn()&&(_+=sn(tn())),p=nn(),d=u=Zt(w=_+=hn(rn())),b++;break;case 45:45===h&&2==Zt(_)&&(g=0)}}return i}function On(e,t,n,r,o,i,a,l,c,s,d){for(var u=o-1,p=0===o?i:[""],f=Ft(p),h=0,g=0,m=0;h<r;++h)for(var v=0,b=Wt(e,u+1,u=Pt(g=a[h])),w=e;v<f;++v)(w=Vt(g>0?p[v]+" "+b:Ht(b,/&\f/g,p[v])))&&(c[m++]=w);return Jt(e,t,n,0===o?wn:l,c,s,d)}function Mn(e,t,n){return Jt(e,t,n,bn,Bt(Kt),Wt(e,2,-2),0)}function Sn(e,t,n,r){return Jt(e,t,n,xn,Wt(e,0,r),Wt(e,r+1,-1),r)}var An=function(e,t,n){for(var r=0,o=0;r=o,o=nn(),38===r&&12===o&&(t[n]=1),!an(o);)tn();return on(e,Xt)},Dn=new WeakMap,Ln=function(e){if("rule"===e.type&&e.parent&&!(e.length<1)){for(var t=e.value,n=e.parent,r=e.column===n.column&&e.line===n.line;"rule"!==n.type;)if(!(n=n.parent))return;if((1!==e.props.length||58===t.charCodeAt(0)||Dn.get(n))&&!r){Dn.set(e,!0);for(var o=[],i=function(e,t){return cn(function(e,t){var n=-1,r=44;do{switch(an(r)){case 0:38===r&&12===nn()&&(t[n]=1),e[n]+=An(Xt-1,t,n);break;case 2:e[n]+=sn(r);break;case 4:if(44===r){e[++n]=58===nn()?"&\f":"",t[n]=e[n].length;break}default:e[n]+=Bt(r)}}while(r=tn());return e}(ln(e),t))}(t,o),a=n.props,l=0,c=0;l<i.length;l++)for(var s=0;s<a.length;s++,c++)e.props[c]=o[l]?i[l].replace(/&\f/g,a[s]):a[s]+" "+i[l]}}},Rn=function(e){if("decl"===e.type){var t=e.value;108===t.charCodeAt(0)&&98===t.charCodeAt(2)&&(e.return="",e.value="")}};function Tn(e,t){switch(function(e,t){return 45^zt(e,0)?(((t<<2^zt(e,0))<<2^zt(e,1))<<2^zt(e,2))<<2^zt(e,3):0}(e,t)){case 5103:return vn+"print-"+e+e;case 5737:case 4201:case 3177:case 3433:case 1641:case 4457:case 2921:case 5572:case 6356:case 5844:case 3191:case 6645:case 3005:case 6391:case 5879:case 5623:case 6135:case 4599:case 4855:case 4215:case 6389:case 5109:case 5365:case 5621:case 3829:return vn+e+e;case 5349:case 4246:case 4810:case 6968:case 2756:return vn+e+mn+e+gn+e+e;case 6828:case 4268:return vn+e+gn+e+e;case 6165:return vn+e+gn+"flex-"+e+e;case 5187:return vn+e+Ht(e,/(\w+).+(:[^]+)/,vn+"box-$1$2"+gn+"flex-$1$2")+e;case 5443:return vn+e+gn+"flex-item-"+Ht(e,/flex-|-self/,"")+e;case 4675:return vn+e+gn+"flex-line-pack"+Ht(e,/align-content|flex-|-self/,"")+e;case 5548:return vn+e+gn+Ht(e,"shrink","negative")+e;case 5292:return vn+e+gn+Ht(e,"basis","preferred-size")+e;case 6060:return vn+"box-"+Ht(e,"-grow","")+vn+e+gn+Ht(e,"grow","positive")+e;case 4554:return vn+Ht(e,/([^-])(transform)/g,"$1"+vn+"$2")+e;case 6187:return Ht(Ht(Ht(e,/(zoom-|grab)/,vn+"$1"),/(image-set)/,vn+"$1"),e,"")+e;case 5495:case 3959:return Ht(e,/(image-set\([^]*)/,vn+"$1$`$1");case 4968:return Ht(Ht(e,/(.+:)(flex-)?(.*)/,vn+"box-pack:$3"+gn+"flex-pack:$3"),/s.+-b[^;]+/,"justify")+vn+e+e;case 4095:case 3583:case 4068:case 2532:return Ht(e,/(.+)-inline(.+)/,vn+"$1$2")+e;case 8116:case 7059:case 5753:case 5535:case 5445:case 5701:case 4933:case 4677:case 5533:case 5789:case 5021:case 4765:if(Zt(e)-1-t>6)switch(zt(e,t+1)){case 109:if(45!==zt(e,t+4))break;case 102:return Ht(e,/(.+:)(.+)-([^]+)/,"$1"+vn+"$2-$3$1"+mn+(108==zt(e,t+3)?"$3":"$2-$3"))+e;case 115:return~It(e,"stretch")?Tn(Ht(e,"stretch","fill-available"),t)+e:e}break;case 4949:if(115!==zt(e,t+1))break;case 6444:switch(zt(e,Zt(e)-3-(~It(e,"!important")&&10))){case 107:return Ht(e,":",":"+vn)+e;case 101:return Ht(e,/(.+:)([^;!]+)(;|!.+)?/,"$1"+vn+(45===zt(e,14)?"inline-":"")+"box$3$1"+vn+"$2$3$1"+gn+"$2box$3")+e}break;case 5936:switch(zt(e,t+11)){case 114:return vn+e+gn+Ht(e,/[svh]\w+-[tblr]{2}/,"tb")+e;case 108:return vn+e+gn+Ht(e,/[svh]\w+-[tblr]{2}/,"tb-rl")+e;case 45:return vn+e+gn+Ht(e,/[svh]\w+-[tblr]{2}/,"lr")+e}return vn+e+gn+e+e}return e}var Nn=[function(e,t,n,r){if(e.length>-1&&!e.return)switch(e.type){case xn:e.return=Tn(e.value,e.length);break;case yn:return Cn([Qt(e,{value:Ht(e.value,"@","@"+vn)})],r);case wn:if(e.length)return function(e,t){return e.map(t).join("")}(e.props,(function(t){switch(function(e){return(e=/(::plac\w+|:read-\w+)/.exec(e))?e[0]:e}(t)){case":read-only":case":read-write":return Cn([Qt(e,{props:[Ht(t,/:(read-\w+)/,":-moz-$1")]})],r);case"::placeholder":return Cn([Qt(e,{props:[Ht(t,/:(plac\w+)/,":"+vn+"input-$1")]}),Qt(e,{props:[Ht(t,/:(plac\w+)/,":-moz-$1")]}),Qt(e,{props:[Ht(t,/:(plac\w+)/,gn+"input-$1")]})],r)}return""}))}}],Pn=function(e){var t=e.key;if("css"===t){var n=document.querySelectorAll("style[data-emotion]:not([data-s])");Array.prototype.forEach.call(n,(function(e){-1!==e.getAttribute("data-emotion").indexOf(" ")&&(document.head.appendChild(e),e.setAttribute("data-s",""))}))}var r,o,i=e.stylisPlugins||Nn,a={},l=[];r=e.container||document.head,Array.prototype.forEach.call(document.querySelectorAll('style[data-emotion^="'+t+' "]'),(function(e){for(var t=e.getAttribute("data-emotion").split(" "),n=1;n<t.length;n++)a[t[n]]=!0;l.push(e)}));var c,s,d,u,p=[_n,(u=function(e){c.insert(e)},function(e){e.root||(e=e.return)&&u(e)})],f=(s=[Ln,Rn].concat(i,p),d=Ft(s),function(e,t,n,r){for(var o="",i=0;i<d;i++)o+=s[i](e,t,n,r)||"";return o});o=function(e,t,n,r){c=n,Cn(kn(e?e+"{"+t.styles+"}":t.styles),f),r&&(h.inserted[t.name]=!0)};var h={key:t,sheet:new Nt({key:t,container:r,nonce:e.nonce,speedy:e.speedy,prepend:e.prepend,insertionPoint:e.insertionPoint}),nonce:e.nonce,inserted:a,registered:{},insert:o};return h.sheet.hydrate(l),h},Bn={animationIterationCount:1,aspectRatio:1,borderImageOutset:1,borderImageSlice:1,borderImageWidth:1,boxFlex:1,boxFlexGroup:1,boxOrdinalGroup:1,columnCount:1,columns:1,flex:1,flexGrow:1,flexPositive:1,flexShrink:1,flexNegative:1,flexOrder:1,gridRow:1,gridRowEnd:1,gridRowSpan:1,gridRowStart:1,gridColumn:1,gridColumnEnd:1,gridColumnSpan:1,gridColumnStart:1,msGridRow:1,msGridRowSpan:1,msGridColumn:1,msGridColumnSpan:1,fontWeight:1,lineHeight:1,opacity:1,order:1,orphans:1,scale:1,tabSize:1,widows:1,zIndex:1,zoom:1,WebkitLineClamp:1,fillOpacity:1,floodOpacity:1,stopOpacity:1,strokeDasharray:1,strokeDashoffset:1,strokeMiterlimit:1,strokeOpacity:1,strokeWidth:1};function jn(e){var t=Object.create(null);return function(n){return void 0===t[n]&&(t[n]=e(n)),t[n]}}var Vn=/[A-Z]|^ms/g,Hn=/_EMO_([^_]+?)_([^]*?)_EMO_/g,In=function(e){return 45===e.charCodeAt(1)},zn=function(e){return null!=e&&"boolean"!=typeof e},Wn=jn((function(e){return In(e)?e:e.replace(Vn,"-$&").toLowerCase()})),Zn=function(e,t){switch(e){case"animation":case"animationName":if("string"==typeof t)return t.replace(Hn,(function(e,t,n){return $n={name:t,styles:n,next:$n},t}))}return 1===Bn[e]||In(e)||"number"!=typeof t||0===t?t:t+"px"};function Fn(e,t,n){if(null==n)return"";var r=n;if(void 0!==r.__emotion_styles)return r;switch(typeof n){case"boolean":return"";case"object":var o=n;if(1===o.anim)return $n={name:o.name,styles:o.styles,next:$n},o.name;var i=n;if(void 0!==i.styles){var a=i.next;if(void 0!==a)for(;void 0!==a;)$n={name:a.name,styles:a.styles,next:$n},a=a.next;return i.styles+";"}return function(e,t,n){var r="";if(Array.isArray(n))for(var o=0;o<n.length;o++)r+=Fn(e,t,n[o])+";";else for(var i in n){var a=n[i];if("object"!=typeof a){var l=a;null!=t&&void 0!==t[l]?r+=i+"{"+t[l]+"}":zn(l)&&(r+=Wn(i)+":"+Zn(i,l)+";")}else if(!Array.isArray(a)||"string"!=typeof a[0]||null!=t&&void 0!==t[a[0]]){var c=Fn(e,t,a);switch(i){case"animation":case"animationName":r+=Wn(i)+":"+c+";";break;default:r+=i+"{"+c+"}"}}else for(var s=0;s<a.length;s++)zn(a[s])&&(r+=Wn(i)+":"+Zn(i,a[s])+";")}return r}(e,t,n);case"function":if(void 0!==e){var l=$n,c=n(e);return $n=l,Fn(e,t,c)}}var s=n;if(null==t)return s;var d=t[s];return void 0!==d?d:s}var $n,Un=/label:\s*([^\s;{]+)\s*(;|$)/g,qn=!!r.useInsertionEffect&&r.useInsertionEffect,Yn=qn||function(e){return e()},Xn=(qn||r.useLayoutEffect,r.createContext("undefined"!=typeof HTMLElement?Pn({key:"css"}):null)),Kn=(Xn.Provider,function(e){return(0,r.forwardRef)((function(t,n){var o=(0,r.useContext)(Xn);return e(t,o,n)}))}),Gn=r.createContext({}),Jn=function(e,t,n){var r=e.key+"-"+t.name;!1===n&&void 0===e.registered[r]&&(e.registered[r]=t.styles)},Qn=/^((children|dangerouslySetInnerHTML|key|ref|autoFocus|defaultValue|defaultChecked|innerHTML|suppressContentEditableWarning|suppressHydrationWarning|valueLink|abbr|accept|acceptCharset|accessKey|action|allow|allowUserMedia|allowPaymentRequest|allowFullScreen|allowTransparency|alt|async|autoComplete|autoPlay|capture|cellPadding|cellSpacing|challenge|charSet|checked|cite|classID|className|cols|colSpan|content|contentEditable|contextMenu|controls|controlsList|coords|crossOrigin|data|dateTime|decoding|default|defer|dir|disabled|disablePictureInPicture|disableRemotePlayback|download|draggable|encType|enterKeyHint|fetchpriority|fetchPriority|form|formAction|formEncType|formMethod|formNoValidate|formTarget|frameBorder|headers|height|hidden|high|href|hrefLang|htmlFor|httpEquiv|id|inputMode|integrity|is|keyParams|keyType|kind|label|lang|list|loading|loop|low|marginHeight|marginWidth|max|maxLength|media|mediaGroup|method|min|minLength|multiple|muted|name|nonce|noValidate|open|optimum|pattern|placeholder|playsInline|poster|preload|profile|radioGroup|readOnly|referrerPolicy|rel|required|reversed|role|rows|rowSpan|sandbox|scope|scoped|scrolling|seamless|selected|shape|size|sizes|slot|span|spellCheck|src|srcDoc|srcLang|srcSet|start|step|style|summary|tabIndex|target|title|translate|type|useMap|value|width|wmode|wrap|about|datatype|inlist|prefix|property|resource|typeof|vocab|autoCapitalize|autoCorrect|autoSave|color|incremental|fallback|inert|itemProp|itemScope|itemType|itemID|itemRef|on|option|results|security|unselectable|accentHeight|accumulate|additive|alignmentBaseline|allowReorder|alphabetic|amplitude|arabicForm|ascent|attributeName|attributeType|autoReverse|azimuth|baseFrequency|baselineShift|baseProfile|bbox|begin|bias|by|calcMode|capHeight|clip|clipPathUnits|clipPath|clipRule|colorInterpolation|colorInterpolationFilters|colorProfile|colorRendering|contentScriptType|contentStyleType|cursor|cx|cy|d|decelerate|descent|diffuseConstant|direction|display|divisor|dominantBaseline|dur|dx|dy|edgeMode|elevation|enableBackground|end|exponent|externalResourcesRequired|fill|fillOpacity|fillRule|filter|filterRes|filterUnits|floodColor|floodOpacity|focusable|fontFamily|fontSize|fontSizeAdjust|fontStretch|fontStyle|fontVariant|fontWeight|format|from|fr|fx|fy|g1|g2|glyphName|glyphOrientationHorizontal|glyphOrientationVertical|glyphRef|gradientTransform|gradientUnits|hanging|horizAdvX|horizOriginX|ideographic|imageRendering|in|in2|intercept|k|k1|k2|k3|k4|kernelMatrix|kernelUnitLength|kerning|keyPoints|keySplines|keyTimes|lengthAdjust|letterSpacing|lightingColor|limitingConeAngle|local|markerEnd|markerMid|markerStart|markerHeight|markerUnits|markerWidth|mask|maskContentUnits|maskUnits|mathematical|mode|numOctaves|offset|opacity|operator|order|orient|orientation|origin|overflow|overlinePosition|overlineThickness|panose1|paintOrder|pathLength|patternContentUnits|patternTransform|patternUnits|pointerEvents|points|pointsAtX|pointsAtY|pointsAtZ|preserveAlpha|preserveAspectRatio|primitiveUnits|r|radius|refX|refY|renderingIntent|repeatCount|repeatDur|requiredExtensions|requiredFeatures|restart|result|rotate|rx|ry|scale|seed|shapeRendering|slope|spacing|specularConstant|specularExponent|speed|spreadMethod|startOffset|stdDeviation|stemh|stemv|stitchTiles|stopColor|stopOpacity|strikethroughPosition|strikethroughThickness|string|stroke|strokeDasharray|strokeDashoffset|strokeLinecap|strokeLinejoin|strokeMiterlimit|strokeOpacity|strokeWidth|surfaceScale|systemLanguage|tableValues|targetX|targetY|textAnchor|textDecoration|textRendering|textLength|to|transform|u1|u2|underlinePosition|underlineThickness|unicode|unicodeBidi|unicodeRange|unitsPerEm|vAlphabetic|vHanging|vIdeographic|vMathematical|values|vectorEffect|version|vertAdvY|vertOriginX|vertOriginY|viewBox|viewTarget|visibility|widths|wordSpacing|writingMode|x|xHeight|x1|x2|xChannelSelector|xlinkActuate|xlinkArcrole|xlinkHref|xlinkRole|xlinkShow|xlinkTitle|xlinkType|xmlBase|xmlns|xmlnsXlink|xmlLang|xmlSpace|y|y1|y2|yChannelSelector|z|zoomAndPan|for|class|autofocus)|(([Dd][Aa][Tt][Aa]|[Aa][Rr][Ii][Aa]|x)-.*))$/,er=jn((function(e){return Qn.test(e)||111===e.charCodeAt(0)&&110===e.charCodeAt(1)&&e.charCodeAt(2)<91})),tr=function(e){return"theme"!==e},nr=function(e){return"string"==typeof e&&e.charCodeAt(0)>96?er:tr},rr=function(e,t,n){var r;if(t){var o=t.shouldForwardProp;r=e.__emotion_forwardProp&&o?function(t){return e.__emotion_forwardProp(t)&&o(t)}:o}return"function"!=typeof r&&n&&(r=e.__emotion_forwardProp),r},or=function(e){var t=e.cache,n=e.serialized,r=e.isStringTag;return Jn(t,n,r),Yn((function(){return function(e,t,n){Jn(e,t,n);var r=e.key+"-"+t.name;if(void 0===e.inserted[t.name]){var o=t;do{e.insert(t===o?"."+r:"",o,e.sheet,!0),o=o.next}while(void 0!==o)}}(t,n,r)})),null},ir=function e(t,n){var o,i,a=t.__emotion_real===t,l=a&&t.__emotion_base||t;void 0!==n&&(o=n.label,i=n.target);var c=rr(t,n,a),s=c||nr(l),d=!s("as");return function(){var u=arguments,p=a&&void 0!==t.__emotion_styles?t.__emotion_styles.slice(0):[];if(void 0!==o&&p.push("label:"+o+";"),null==u[0]||void 0===u[0].raw)p.push.apply(p,u);else{var f=u[0];p.push(f[0]);for(var h=u.length,g=1;g<h;g++)p.push(u[g],f[g])}var m=Kn((function(e,t,n){var o,a,u,f,h=d&&e.as||l,g="",m=[],v=e;if(null==e.theme){for(var b in v={},e)v[b]=e[b];v.theme=r.useContext(Gn)}"string"==typeof e.className?(o=t.registered,a=m,u=e.className,f="",u.split(" ").forEach((function(e){void 0!==o[e]?a.push(o[e]+";"):e&&(f+=e+" ")})),g=f):null!=e.className&&(g=e.className+" ");var w=function(e,t,n){if(1===e.length&&"object"==typeof e[0]&&null!==e[0]&&void 0!==e[0].styles)return e[0];var r=!0,o="";$n=void 0;var i=e[0];null==i||void 0===i.raw?(r=!1,o+=Fn(n,t,i)):o+=i[0];for(var a=1;a<e.length;a++)o+=Fn(n,t,e[a]),r&&(o+=i[a]);Un.lastIndex=0;for(var l,c="";null!==(l=Un.exec(o));)c+="-"+l[1];var s=function(e){for(var t,n=0,r=0,o=e.length;o>=4;++r,o-=4)t=1540483477*(65535&(t=255&e.charCodeAt(r)|(255&e.charCodeAt(++r))<<8|(255&e.charCodeAt(++r))<<16|(255&e.charCodeAt(++r))<<24))+(59797*(t>>>16)<<16),n=1540483477*(65535&(t^=t>>>24))+(59797*(t>>>16)<<16)^1540483477*(65535&n)+(59797*(n>>>16)<<16);switch(o){case 3:n^=(255&e.charCodeAt(r+2))<<16;case 2:n^=(255&e.charCodeAt(r+1))<<8;case 1:n=1540483477*(65535&(n^=255&e.charCodeAt(r)))+(59797*(n>>>16)<<16)}return(((n=1540483477*(65535&(n^=n>>>13))+(59797*(n>>>16)<<16))^n>>>15)>>>0).toString(36)}(o)+c;return{name:s,styles:o,next:$n}}(p.concat(m),t.registered,v);g+=t.key+"-"+w.name,void 0!==i&&(g+=" "+i);var x=d&&void 0===c?nr(h):s,y={};for(var C in e)d&&"as"===C||x(C)&&(y[C]=e[C]);return y.className=g,n&&(y.ref=n),r.createElement(r.Fragment,null,r.createElement(or,{cache:t,serialized:w,isStringTag:"string"==typeof h}),r.createElement(h,y))}));return m.displayName=void 0!==o?o:"Styled("+("string"==typeof l?l:l.displayName||l.name||"Component")+")",m.defaultProps=t.defaultProps,m.__emotion_real=m,m.__emotion_base=l,m.__emotion_styles=p,m.__emotion_forwardProp=c,Object.defineProperty(m,"toString",{value:function(){return"."+i}}),m.withComponent=function(t,r){return e(t,Tt({},n,r,{shouldForwardProp:rr(m,r,!0)})).apply(void 0,p)},m}}.bind(null);function ar(e){if(null==e)return window;if("[object Window]"!==e.toString()){var t=e.ownerDocument;return t&&t.defaultView||window}return e}function lr(e){return e instanceof ar(e).Element||e instanceof Element}function cr(e){return e instanceof ar(e).HTMLElement||e instanceof HTMLElement}function sr(e){return"undefined"!=typeof ShadowRoot&&(e instanceof ar(e).ShadowRoot||e instanceof ShadowRoot)}["a","abbr","address","area","article","aside","audio","b","base","bdi","bdo","big","blockquote","body","br","button","canvas","caption","cite","code","col","colgroup","data","datalist","dd","del","details","dfn","dialog","div","dl","dt","em","embed","fieldset","figcaption","figure","footer","form","h1","h2","h3","h4","h5","h6","head","header","hgroup","hr","html","i","iframe","img","input","ins","kbd","keygen","label","legend","li","link","main","map","mark","marquee","menu","menuitem","meta","meter","nav","noscript","object","ol","optgroup","option","output","p","param","picture","pre","progress","q","rp","rt","ruby","s","samp","script","section","select","small","source","span","strong","style","sub","summary","sup","table","tbody","td","textarea","tfoot","th","thead","time","title","tr","track","u","ul","var","video","wbr","circle","clipPath","defs","ellipse","foreignObject","g","image","line","linearGradient","mask","path","pattern","polygon","polyline","radialGradient","rect","stop","svg","text","tspan"].forEach((function(e){ir[e]=ir(e)}));var dr=Math.max,ur=Math.min,pr=Math.round;function fr(){var e=navigator.userAgentData;return null!=e&&e.brands&&Array.isArray(e.brands)?e.brands.map((function(e){return e.brand+"/"+e.version})).join(" "):navigator.userAgent}function hr(){return!/^((?!chrome|android).)*safari/i.test(fr())}function gr(e,t,n){void 0===t&&(t=!1),void 0===n&&(n=!1);var r=e.getBoundingClientRect(),o=1,i=1;t&&cr(e)&&(o=e.offsetWidth>0&&pr(r.width)/e.offsetWidth||1,i=e.offsetHeight>0&&pr(r.height)/e.offsetHeight||1);var a=(lr(e)?ar(e):window).visualViewport,l=!hr()&&n,c=(r.left+(l&&a?a.offsetLeft:0))/o,s=(r.top+(l&&a?a.offsetTop:0))/i,d=r.width/o,u=r.height/i;return{width:d,height:u,top:s,right:c+d,bottom:s+u,left:c,x:c,y:s}}function mr(e){var t=ar(e);return{scrollLeft:t.pageXOffset,scrollTop:t.pageYOffset}}function vr(e){return e?(e.nodeName||"").toLowerCase():null}function br(e){return((lr(e)?e.ownerDocument:e.document)||window.document).documentElement}function wr(e){return gr(br(e)).left+mr(e).scrollLeft}function xr(e){return ar(e).getComputedStyle(e)}function yr(e){var t=xr(e),n=t.overflow,r=t.overflowX,o=t.overflowY;return/auto|scroll|overlay|hidden/.test(n+o+r)}function Cr(e,t,n){void 0===n&&(n=!1);var r=cr(t),o=cr(t)&&function(e){var t=e.getBoundingClientRect(),n=pr(t.width)/e.offsetWidth||1,r=pr(t.height)/e.offsetHeight||1;return 1!==n||1!==r}(t),i=br(t),a=gr(e,o,n),l={scrollLeft:0,scrollTop:0},c={x:0,y:0};return(r||!r&&!n)&&(("body"!==vr(t)||yr(i))&&(l=function(e){return e!==ar(e)&&cr(e)?{scrollLeft:(t=e).scrollLeft,scrollTop:t.scrollTop}:mr(e);var t}(t)),cr(t)?((c=gr(t,!0)).x+=t.clientLeft,c.y+=t.clientTop):i&&(c.x=wr(i))),{x:a.left+l.scrollLeft-c.x,y:a.top+l.scrollTop-c.y,width:a.width,height:a.height}}function _r(e){var t=gr(e),n=e.offsetWidth,r=e.offsetHeight;return Math.abs(t.width-n)<=1&&(n=t.width),Math.abs(t.height-r)<=1&&(r=t.height),{x:e.offsetLeft,y:e.offsetTop,width:n,height:r}}function kr(e){return"html"===vr(e)?e:e.assignedSlot||e.parentNode||(sr(e)?e.host:null)||br(e)}function Er(e){return["html","body","#document"].indexOf(vr(e))>=0?e.ownerDocument.body:cr(e)&&yr(e)?e:Er(kr(e))}function Or(e,t){var n;void 0===t&&(t=[]);var r=Er(e),o=r===(null==(n=e.ownerDocument)?void 0:n.body),i=ar(r),a=o?[i].concat(i.visualViewport||[],yr(r)?r:[]):r,l=t.concat(a);return o?l:l.concat(Or(kr(a)))}function Mr(e){return["table","td","th"].indexOf(vr(e))>=0}function Sr(e){return cr(e)&&"fixed"!==xr(e).position?e.offsetParent:null}function Ar(e){for(var t=ar(e),n=Sr(e);n&&Mr(n)&&"static"===xr(n).position;)n=Sr(n);return n&&("html"===vr(n)||"body"===vr(n)&&"static"===xr(n).position)?t:n||function(e){var t=/firefox/i.test(fr());if(/Trident/i.test(fr())&&cr(e)&&"fixed"===xr(e).position)return null;var n=kr(e);for(sr(n)&&(n=n.host);cr(n)&&["html","body"].indexOf(vr(n))<0;){var r=xr(n);if("none"!==r.transform||"none"!==r.perspective||"paint"===r.contain||-1!==["transform","perspective"].indexOf(r.willChange)||t&&"filter"===r.willChange||t&&r.filter&&"none"!==r.filter)return n;n=n.parentNode}return null}(e)||t}var Dr="top",Lr="bottom",Rr="right",Tr="left",Nr="auto",Pr=[Dr,Lr,Rr,Tr],Br="start",jr="end",Vr="viewport",Hr="popper",Ir=Pr.reduce((function(e,t){return e.concat([t+"-"+Br,t+"-"+jr])}),[]),zr=[].concat(Pr,[Nr]).reduce((function(e,t){return e.concat([t,t+"-"+Br,t+"-"+jr])}),[]),Wr=["beforeRead","read","afterRead","beforeMain","main","afterMain","beforeWrite","write","afterWrite"];function Zr(e){var t=new Map,n=new Set,r=[];function o(e){n.add(e.name),[].concat(e.requires||[],e.requiresIfExists||[]).forEach((function(e){if(!n.has(e)){var r=t.get(e);r&&o(r)}})),r.push(e)}return e.forEach((function(e){t.set(e.name,e)})),e.forEach((function(e){n.has(e.name)||o(e)})),r}var Fr={placement:"bottom",modifiers:[],strategy:"absolute"};function $r(){for(var e=arguments.length,t=new Array(e),n=0;n<e;n++)t[n]=arguments[n];return!t.some((function(e){return!(e&&"function"==typeof e.getBoundingClientRect)}))}function Ur(e){void 0===e&&(e={});var t=e,n=t.defaultModifiers,r=void 0===n?[]:n,o=t.defaultOptions,i=void 0===o?Fr:o;return function(e,t,n){void 0===n&&(n=i);var o,a,l={placement:"bottom",orderedModifiers:[],options:Object.assign({},Fr,i),modifiersData:{},elements:{reference:e,popper:t},attributes:{},styles:{}},c=[],s=!1,d={state:l,setOptions:function(n){var o="function"==typeof n?n(l.options):n;u(),l.options=Object.assign({},i,l.options,o),l.scrollParents={reference:lr(e)?Or(e):e.contextElement?Or(e.contextElement):[],popper:Or(t)};var a,s,p=function(e){var t=Zr(e);return Wr.reduce((function(e,n){return e.concat(t.filter((function(e){return e.phase===n})))}),[])}((a=[].concat(r,l.options.modifiers),s=a.reduce((function(e,t){var n=e[t.name];return e[t.name]=n?Object.assign({},n,t,{options:Object.assign({},n.options,t.options),data:Object.assign({},n.data,t.data)}):t,e}),{}),Object.keys(s).map((function(e){return s[e]}))));return l.orderedModifiers=p.filter((function(e){return e.enabled})),l.orderedModifiers.forEach((function(e){var t=e.name,n=e.options,r=void 0===n?{}:n,o=e.effect;if("function"==typeof o){var i=o({state:l,name:t,instance:d,options:r});c.push(i||function(){})}})),d.update()},forceUpdate:function(){if(!s){var e=l.elements,t=e.reference,n=e.popper;if($r(t,n)){l.rects={reference:Cr(t,Ar(n),"fixed"===l.options.strategy),popper:_r(n)},l.reset=!1,l.placement=l.options.placement,l.orderedModifiers.forEach((function(e){return l.modifiersData[e.name]=Object.assign({},e.data)}));for(var r=0;r<l.orderedModifiers.length;r++)if(!0!==l.reset){var o=l.orderedModifiers[r],i=o.fn,a=o.options,c=void 0===a?{}:a,u=o.name;"function"==typeof i&&(l=i({state:l,options:c,name:u,instance:d})||l)}else l.reset=!1,r=-1}}},update:(o=function(){return new Promise((function(e){d.forceUpdate(),e(l)}))},function(){return a||(a=new Promise((function(e){Promise.resolve().then((function(){a=void 0,e(o())}))}))),a}),destroy:function(){u(),s=!0}};if(!$r(e,t))return d;function u(){c.forEach((function(e){return e()})),c=[]}return d.setOptions(n).then((function(e){!s&&n.onFirstUpdate&&n.onFirstUpdate(e)})),d}}var qr={passive:!0};function Yr(e){return e.split("-")[0]}function Xr(e){return e.split("-")[1]}function Kr(e){return["top","bottom"].indexOf(e)>=0?"x":"y"}function Gr(e){var t,n=e.reference,r=e.element,o=e.placement,i=o?Yr(o):null,a=o?Xr(o):null,l=n.x+n.width/2-r.width/2,c=n.y+n.height/2-r.height/2;switch(i){case Dr:t={x:l,y:n.y-r.height};break;case Lr:t={x:l,y:n.y+n.height};break;case Rr:t={x:n.x+n.width,y:c};break;case Tr:t={x:n.x-r.width,y:c};break;default:t={x:n.x,y:n.y}}var s=i?Kr(i):null;if(null!=s){var d="y"===s?"height":"width";switch(a){case Br:t[s]=t[s]-(n[d]/2-r[d]/2);break;case jr:t[s]=t[s]+(n[d]/2-r[d]/2)}}return t}var Jr={top:"auto",right:"auto",bottom:"auto",left:"auto"};function Qr(e){var t,n=e.popper,r=e.popperRect,o=e.placement,i=e.variation,a=e.offsets,l=e.position,c=e.gpuAcceleration,s=e.adaptive,d=e.roundOffsets,u=e.isFixed,p=a.x,f=void 0===p?0:p,h=a.y,g=void 0===h?0:h,m="function"==typeof d?d({x:f,y:g}):{x:f,y:g};f=m.x,g=m.y;var v=a.hasOwnProperty("x"),b=a.hasOwnProperty("y"),w=Tr,x=Dr,y=window;if(s){var C=Ar(n),_="clientHeight",k="clientWidth";C===ar(n)&&"static"!==xr(C=br(n)).position&&"absolute"===l&&(_="scrollHeight",k="scrollWidth"),(o===Dr||(o===Tr||o===Rr)&&i===jr)&&(x=Lr,g-=(u&&C===y&&y.visualViewport?y.visualViewport.height:C[_])-r.height,g*=c?1:-1),o!==Tr&&(o!==Dr&&o!==Lr||i!==jr)||(w=Rr,f-=(u&&C===y&&y.visualViewport?y.visualViewport.width:C[k])-r.width,f*=c?1:-1)}var E,O=Object.assign({position:l},s&&Jr),M=!0===d?function(e,t){var n=e.x,r=e.y,o=t.devicePixelRatio||1;return{x:pr(n*o)/o||0,y:pr(r*o)/o||0}}({x:f,y:g},ar(n)):{x:f,y:g};return f=M.x,g=M.y,c?Object.assign({},O,((E={})[x]=b?"0":"",E[w]=v?"0":"",E.transform=(y.devicePixelRatio||1)<=1?"translate("+f+"px, "+g+"px)":"translate3d("+f+"px, "+g+"px, 0)",E)):Object.assign({},O,((t={})[x]=b?g+"px":"",t[w]=v?f+"px":"",t.transform="",t))}const eo={name:"applyStyles",enabled:!0,phase:"write",fn:function(e){var t=e.state;Object.keys(t.elements).forEach((function(e){var n=t.styles[e]||{},r=t.attributes[e]||{},o=t.elements[e];cr(o)&&vr(o)&&(Object.assign(o.style,n),Object.keys(r).forEach((function(e){var t=r[e];!1===t?o.removeAttribute(e):o.setAttribute(e,!0===t?"":t)})))}))},effect:function(e){var t=e.state,n={popper:{position:t.options.strategy,left:"0",top:"0",margin:"0"},arrow:{position:"absolute"},reference:{}};return Object.assign(t.elements.popper.style,n.popper),t.styles=n,t.elements.arrow&&Object.assign(t.elements.arrow.style,n.arrow),function(){Object.keys(t.elements).forEach((function(e){var r=t.elements[e],o=t.attributes[e]||{},i=Object.keys(t.styles.hasOwnProperty(e)?t.styles[e]:n[e]).reduce((function(e,t){return e[t]="",e}),{});cr(r)&&vr(r)&&(Object.assign(r.style,i),Object.keys(o).forEach((function(e){r.removeAttribute(e)})))}))}},requires:["computeStyles"]};var to={left:"right",right:"left",bottom:"top",top:"bottom"};function no(e){return e.replace(/left|right|bottom|top/g,(function(e){return to[e]}))}var ro={start:"end",end:"start"};function oo(e){return e.replace(/start|end/g,(function(e){return ro[e]}))}function io(e,t){var n=t.getRootNode&&t.getRootNode();if(e.contains(t))return!0;if(n&&sr(n)){var r=t;do{if(r&&e.isSameNode(r))return!0;r=r.parentNode||r.host}while(r)}return!1}function ao(e){return Object.assign({},e,{left:e.x,top:e.y,right:e.x+e.width,bottom:e.y+e.height})}function lo(e,t,n){return t===Vr?ao(function(e,t){var n=ar(e),r=br(e),o=n.visualViewport,i=r.clientWidth,a=r.clientHeight,l=0,c=0;if(o){i=o.width,a=o.height;var s=hr();(s||!s&&"fixed"===t)&&(l=o.offsetLeft,c=o.offsetTop)}return{width:i,height:a,x:l+wr(e),y:c}}(e,n)):lr(t)?function(e,t){var n=gr(e,!1,"fixed"===t);return n.top=n.top+e.clientTop,n.left=n.left+e.clientLeft,n.bottom=n.top+e.clientHeight,n.right=n.left+e.clientWidth,n.width=e.clientWidth,n.height=e.clientHeight,n.x=n.left,n.y=n.top,n}(t,n):ao(function(e){var t,n=br(e),r=mr(e),o=null==(t=e.ownerDocument)?void 0:t.body,i=dr(n.scrollWidth,n.clientWidth,o?o.scrollWidth:0,o?o.clientWidth:0),a=dr(n.scrollHeight,n.clientHeight,o?o.scrollHeight:0,o?o.clientHeight:0),l=-r.scrollLeft+wr(e),c=-r.scrollTop;return"rtl"===xr(o||n).direction&&(l+=dr(n.clientWidth,o?o.clientWidth:0)-i),{width:i,height:a,x:l,y:c}}(br(e)))}function co(e){return Object.assign({},{top:0,right:0,bottom:0,left:0},e)}function so(e,t){return t.reduce((function(t,n){return t[n]=e,t}),{})}function uo(e,t){void 0===t&&(t={});var n=t,r=n.placement,o=void 0===r?e.placement:r,i=n.strategy,a=void 0===i?e.strategy:i,l=n.boundary,c=void 0===l?"clippingParents":l,s=n.rootBoundary,d=void 0===s?Vr:s,u=n.elementContext,p=void 0===u?Hr:u,f=n.altBoundary,h=void 0!==f&&f,g=n.padding,m=void 0===g?0:g,v=co("number"!=typeof m?m:so(m,Pr)),b=p===Hr?"reference":Hr,w=e.rects.popper,x=e.elements[h?b:p],y=function(e,t,n,r){var o="clippingParents"===t?function(e){var t=Or(kr(e)),n=["absolute","fixed"].indexOf(xr(e).position)>=0&&cr(e)?Ar(e):e;return lr(n)?t.filter((function(e){return lr(e)&&io(e,n)&&"body"!==vr(e)})):[]}(e):[].concat(t),i=[].concat(o,[n]),a=i[0],l=i.reduce((function(t,n){var o=lo(e,n,r);return t.top=dr(o.top,t.top),t.right=ur(o.right,t.right),t.bottom=ur(o.bottom,t.bottom),t.left=dr(o.left,t.left),t}),lo(e,a,r));return l.width=l.right-l.left,l.height=l.bottom-l.top,l.x=l.left,l.y=l.top,l}(lr(x)?x:x.contextElement||br(e.elements.popper),c,d,a),C=gr(e.elements.reference),_=Gr({reference:C,element:w,strategy:"absolute",placement:o}),k=ao(Object.assign({},w,_)),E=p===Hr?k:C,O={top:y.top-E.top+v.top,bottom:E.bottom-y.bottom+v.bottom,left:y.left-E.left+v.left,right:E.right-y.right+v.right},M=e.modifiersData.offset;if(p===Hr&&M){var S=M[o];Object.keys(O).forEach((function(e){var t=[Rr,Lr].indexOf(e)>=0?1:-1,n=[Dr,Lr].indexOf(e)>=0?"y":"x";O[e]+=S[n]*t}))}return O}function po(e,t,n){return dr(e,ur(t,n))}function fo(e,t,n){return void 0===n&&(n={x:0,y:0}),{top:e.top-t.height-n.y,right:e.right-t.width+n.x,bottom:e.bottom-t.height+n.y,left:e.left-t.width-n.x}}function ho(e){return[Dr,Rr,Lr,Tr].some((function(t){return e[t]>=0}))}var go=Ur({defaultModifiers:[{name:"eventListeners",enabled:!0,phase:"write",fn:function(){},effect:function(e){var t=e.state,n=e.instance,r=e.options,o=r.scroll,i=void 0===o||o,a=r.resize,l=void 0===a||a,c=ar(t.elements.popper),s=[].concat(t.scrollParents.reference,t.scrollParents.popper);return i&&s.forEach((function(e){e.addEventListener("scroll",n.update,qr)})),l&&c.addEventListener("resize",n.update,qr),function(){i&&s.forEach((function(e){e.removeEventListener("scroll",n.update,qr)})),l&&c.removeEventListener("resize",n.update,qr)}},data:{}},{name:"popperOffsets",enabled:!0,phase:"read",fn:function(e){var t=e.state,n=e.name;t.modifiersData[n]=Gr({reference:t.rects.reference,element:t.rects.popper,strategy:"absolute",placement:t.placement})},data:{}},{name:"computeStyles",enabled:!0,phase:"beforeWrite",fn:function(e){var t=e.state,n=e.options,r=n.gpuAcceleration,o=void 0===r||r,i=n.adaptive,a=void 0===i||i,l=n.roundOffsets,c=void 0===l||l,s={placement:Yr(t.placement),variation:Xr(t.placement),popper:t.elements.popper,popperRect:t.rects.popper,gpuAcceleration:o,isFixed:"fixed"===t.options.strategy};null!=t.modifiersData.popperOffsets&&(t.styles.popper=Object.assign({},t.styles.popper,Qr(Object.assign({},s,{offsets:t.modifiersData.popperOffsets,position:t.options.strategy,adaptive:a,roundOffsets:c})))),null!=t.modifiersData.arrow&&(t.styles.arrow=Object.assign({},t.styles.arrow,Qr(Object.assign({},s,{offsets:t.modifiersData.arrow,position:"absolute",adaptive:!1,roundOffsets:c})))),t.attributes.popper=Object.assign({},t.attributes.popper,{"data-popper-placement":t.placement})},data:{}},eo,{name:"offset",enabled:!0,phase:"main",requires:["popperOffsets"],fn:function(e){var t=e.state,n=e.options,r=e.name,o=n.offset,i=void 0===o?[0,0]:o,a=zr.reduce((function(e,n){return e[n]=function(e,t,n){var r=Yr(e),o=[Tr,Dr].indexOf(r)>=0?-1:1,i="function"==typeof n?n(Object.assign({},t,{placement:e})):n,a=i[0],l=i[1];return a=a||0,l=(l||0)*o,[Tr,Rr].indexOf(r)>=0?{x:l,y:a}:{x:a,y:l}}(n,t.rects,i),e}),{}),l=a[t.placement],c=l.x,s=l.y;null!=t.modifiersData.popperOffsets&&(t.modifiersData.popperOffsets.x+=c,t.modifiersData.popperOffsets.y+=s),t.modifiersData[r]=a}},{name:"flip",enabled:!0,phase:"main",fn:function(e){var t=e.state,n=e.options,r=e.name;if(!t.modifiersData[r]._skip){for(var o=n.mainAxis,i=void 0===o||o,a=n.altAxis,l=void 0===a||a,c=n.fallbackPlacements,s=n.padding,d=n.boundary,u=n.rootBoundary,p=n.altBoundary,f=n.flipVariations,h=void 0===f||f,g=n.allowedAutoPlacements,m=t.options.placement,v=Yr(m),b=c||(v!==m&&h?function(e){if(Yr(e)===Nr)return[];var t=no(e);return[oo(e),t,oo(t)]}(m):[no(m)]),w=[m].concat(b).reduce((function(e,n){return e.concat(Yr(n)===Nr?function(e,t){void 0===t&&(t={});var n=t,r=n.placement,o=n.boundary,i=n.rootBoundary,a=n.padding,l=n.flipVariations,c=n.allowedAutoPlacements,s=void 0===c?zr:c,d=Xr(r),u=d?l?Ir:Ir.filter((function(e){return Xr(e)===d})):Pr,p=u.filter((function(e){return s.indexOf(e)>=0}));0===p.length&&(p=u);var f=p.reduce((function(t,n){return t[n]=uo(e,{placement:n,boundary:o,rootBoundary:i,padding:a})[Yr(n)],t}),{});return Object.keys(f).sort((function(e,t){return f[e]-f[t]}))}(t,{placement:n,boundary:d,rootBoundary:u,padding:s,flipVariations:h,allowedAutoPlacements:g}):n)}),[]),x=t.rects.reference,y=t.rects.popper,C=new Map,_=!0,k=w[0],E=0;E<w.length;E++){var O=w[E],M=Yr(O),S=Xr(O)===Br,A=[Dr,Lr].indexOf(M)>=0,D=A?"width":"height",L=uo(t,{placement:O,boundary:d,rootBoundary:u,altBoundary:p,padding:s}),R=A?S?Rr:Tr:S?Lr:Dr;x[D]>y[D]&&(R=no(R));var T=no(R),N=[];if(i&&N.push(L[M]<=0),l&&N.push(L[R]<=0,L[T]<=0),N.every((function(e){return e}))){k=O,_=!1;break}C.set(O,N)}if(_)for(var P=function(e){var t=w.find((function(t){var n=C.get(t);if(n)return n.slice(0,e).every((function(e){return e}))}));if(t)return k=t,"break"},B=h?3:1;B>0&&"break"!==P(B);B--);t.placement!==k&&(t.modifiersData[r]._skip=!0,t.placement=k,t.reset=!0)}},requiresIfExists:["offset"],data:{_skip:!1}},{name:"preventOverflow",enabled:!0,phase:"main",fn:function(e){var t=e.state,n=e.options,r=e.name,o=n.mainAxis,i=void 0===o||o,a=n.altAxis,l=void 0!==a&&a,c=n.boundary,s=n.rootBoundary,d=n.altBoundary,u=n.padding,p=n.tether,f=void 0===p||p,h=n.tetherOffset,g=void 0===h?0:h,m=uo(t,{boundary:c,rootBoundary:s,padding:u,altBoundary:d}),v=Yr(t.placement),b=Xr(t.placement),w=!b,x=Kr(v),y="x"===x?"y":"x",C=t.modifiersData.popperOffsets,_=t.rects.reference,k=t.rects.popper,E="function"==typeof g?g(Object.assign({},t.rects,{placement:t.placement})):g,O="number"==typeof E?{mainAxis:E,altAxis:E}:Object.assign({mainAxis:0,altAxis:0},E),M=t.modifiersData.offset?t.modifiersData.offset[t.placement]:null,S={x:0,y:0};if(C){if(i){var A,D="y"===x?Dr:Tr,L="y"===x?Lr:Rr,R="y"===x?"height":"width",T=C[x],N=T+m[D],P=T-m[L],B=f?-k[R]/2:0,j=b===Br?_[R]:k[R],V=b===Br?-k[R]:-_[R],H=t.elements.arrow,I=f&&H?_r(H):{width:0,height:0},z=t.modifiersData["arrow#persistent"]?t.modifiersData["arrow#persistent"].padding:{top:0,right:0,bottom:0,left:0},W=z[D],Z=z[L],F=po(0,_[R],I[R]),$=w?_[R]/2-B-F-W-O.mainAxis:j-F-W-O.mainAxis,U=w?-_[R]/2+B+F+Z+O.mainAxis:V+F+Z+O.mainAxis,q=t.elements.arrow&&Ar(t.elements.arrow),Y=q?"y"===x?q.clientTop||0:q.clientLeft||0:0,X=null!=(A=null==M?void 0:M[x])?A:0,K=T+U-X,G=po(f?ur(N,T+$-X-Y):N,T,f?dr(P,K):P);C[x]=G,S[x]=G-T}if(l){var J,Q="x"===x?Dr:Tr,ee="x"===x?Lr:Rr,te=C[y],ne="y"===y?"height":"width",re=te+m[Q],oe=te-m[ee],ie=-1!==[Dr,Tr].indexOf(v),ae=null!=(J=null==M?void 0:M[y])?J:0,le=ie?re:te-_[ne]-k[ne]-ae+O.altAxis,ce=ie?te+_[ne]+k[ne]-ae-O.altAxis:oe,se=f&&ie?function(e,t,n){var r=po(e,t,n);return r>n?n:r}(le,te,ce):po(f?le:re,te,f?ce:oe);C[y]=se,S[y]=se-te}t.modifiersData[r]=S}},requiresIfExists:["offset"]},{name:"arrow",enabled:!0,phase:"main",fn:function(e){var t,n=e.state,r=e.name,o=e.options,i=n.elements.arrow,a=n.modifiersData.popperOffsets,l=Yr(n.placement),c=Kr(l),s=[Tr,Rr].indexOf(l)>=0?"height":"width";if(i&&a){var d=function(e,t){return co("number"!=typeof(e="function"==typeof e?e(Object.assign({},t.rects,{placement:t.placement})):e)?e:so(e,Pr))}(o.padding,n),u=_r(i),p="y"===c?Dr:Tr,f="y"===c?Lr:Rr,h=n.rects.reference[s]+n.rects.reference[c]-a[c]-n.rects.popper[s],g=a[c]-n.rects.reference[c],m=Ar(i),v=m?"y"===c?m.clientHeight||0:m.clientWidth||0:0,b=h/2-g/2,w=d[p],x=v-u[s]-d[f],y=v/2-u[s]/2+b,C=po(w,y,x),_=c;n.modifiersData[r]=((t={})[_]=C,t.centerOffset=C-y,t)}},effect:function(e){var t=e.state,n=e.options.element,r=void 0===n?"[data-popper-arrow]":n;null!=r&&("string"!=typeof r||(r=t.elements.popper.querySelector(r)))&&io(t.elements.popper,r)&&(t.elements.arrow=r)},requires:["popperOffsets"],requiresIfExists:["preventOverflow"]},{name:"hide",enabled:!0,phase:"main",requiresIfExists:["preventOverflow"],fn:function(e){var t=e.state,n=e.name,r=t.rects.reference,o=t.rects.popper,i=t.modifiersData.preventOverflow,a=uo(t,{elementContext:"reference"}),l=uo(t,{altBoundary:!0}),c=fo(a,r),s=fo(l,o,i),d=ho(c),u=ho(s);t.modifiersData[n]={referenceClippingOffsets:c,popperEscapeOffsets:s,isReferenceHidden:d,hasPopperEscaped:u},t.attributes.popper=Object.assign({},t.attributes.popper,{"data-popper-reference-hidden":d,"data-popper-escaped":u})}}]}),mo="tippy-content",vo="tippy-arrow",bo="tippy-svg-arrow",wo={passive:!0,capture:!0},xo=function(){return document.body};function yo(e,t,n){if(Array.isArray(e)){var r=e[t];return null==r?Array.isArray(n)?n[t]:n:r}return e}function Co(e,t){var n={}.toString.call(e);return 0===n.indexOf("[object")&&n.indexOf(t+"]")>-1}function _o(e,t){return"function"==typeof e?e.apply(void 0,t):e}function ko(e,t){return 0===t?e:function(r){clearTimeout(n),n=setTimeout((function(){e(r)}),t)};var n}function Eo(e){return[].concat(e)}function Oo(e,t){-1===e.indexOf(t)&&e.push(t)}function Mo(e){return[].slice.call(e)}function So(e){return Object.keys(e).reduce((function(t,n){return void 0!==e[n]&&(t[n]=e[n]),t}),{})}function Ao(){return document.createElement("div")}function Do(e){return["Element","Fragment"].some((function(t){return Co(e,t)}))}function Lo(e,t){e.forEach((function(e){e&&(e.style.transitionDuration=t+"ms")}))}function Ro(e,t){e.forEach((function(e){e&&e.setAttribute("data-state",t)}))}function To(e,t,n){var r=t+"EventListener";["transitionend","webkitTransitionEnd"].forEach((function(t){e[r](t,n)}))}function No(e,t){for(var n=t;n;){var r;if(e.contains(n))return!0;n=null==n.getRootNode||null==(r=n.getRootNode())?void 0:r.host}return!1}var Po={isTouch:!1},Bo=0;function jo(){Po.isTouch||(Po.isTouch=!0,window.performance&&document.addEventListener("mousemove",Vo))}function Vo(){var e=performance.now();e-Bo<20&&(Po.isTouch=!1,document.removeEventListener("mousemove",Vo)),Bo=e}function Ho(){var e,t=document.activeElement;if((e=t)&&e._tippy&&e._tippy.reference===e){var n=t._tippy;t.blur&&!n.state.isVisible&&t.blur()}}var Io=!("undefined"==typeof window||"undefined"==typeof document||!window.msCrypto),zo=Object.assign({appendTo:xo,aria:{content:"auto",expanded:"auto"},delay:0,duration:[300,250],getReferenceClientRect:null,hideOnClick:!0,ignoreAttributes:!1,interactive:!1,interactiveBorder:2,interactiveDebounce:0,moveTransition:"",offset:[0,10],onAfterUpdate:function(){},onBeforeUpdate:function(){},onCreate:function(){},onDestroy:function(){},onHidden:function(){},onHide:function(){},onMount:function(){},onShow:function(){},onShown:function(){},onTrigger:function(){},onUntrigger:function(){},onClickOutside:function(){},placement:"top",plugins:[],popperOptions:{},render:null,showOnCreate:!1,touch:!0,trigger:"mouseenter focus",triggerTarget:null},{animateFill:!1,followCursor:!1,inlinePositioning:!1,sticky:!1},{allowHTML:!1,animation:"fade",arrow:!0,content:"",inertia:!1,maxWidth:350,role:"tooltip",theme:"",zIndex:9999}),Wo=Object.keys(zo);function Zo(e){var t=(e.plugins||[]).reduce((function(t,n){var r,o=n.name,i=n.defaultValue;return o&&(t[o]=void 0!==e[o]?e[o]:null!=(r=zo[o])?r:i),t}),{});return Object.assign({},e,t)}function Fo(e,t){var n=Object.assign({},t,{content:_o(t.content,[e])},t.ignoreAttributes?{}:function(e,t){return(t?Object.keys(Zo(Object.assign({},zo,{plugins:t}))):Wo).reduce((function(t,n){var r=(e.getAttribute("data-tippy-"+n)||"").trim();if(!r)return t;if("content"===n)t[n]=r;else try{t[n]=JSON.parse(r)}catch(e){t[n]=r}return t}),{})}(e,t.plugins));return n.aria=Object.assign({},zo.aria,n.aria),n.aria={expanded:"auto"===n.aria.expanded?t.interactive:n.aria.expanded,content:"auto"===n.aria.content?t.interactive?null:"describedby":n.aria.content},n}function $o(e,t){e.innerHTML=t}function Uo(e){var t=Ao();return!0===e?t.className=vo:(t.className=bo,Do(e)?t.appendChild(e):$o(t,e)),t}function qo(e,t){Do(t.content)?($o(e,""),e.appendChild(t.content)):"function"!=typeof t.content&&(t.allowHTML?$o(e,t.content):e.textContent=t.content)}function Yo(e){var t=e.firstElementChild,n=Mo(t.children);return{box:t,content:n.find((function(e){return e.classList.contains(mo)})),arrow:n.find((function(e){return e.classList.contains(vo)||e.classList.contains(bo)})),backdrop:n.find((function(e){return e.classList.contains("tippy-backdrop")}))}}function Xo(e){var t=Ao(),n=Ao();n.className="tippy-box",n.setAttribute("data-state","hidden"),n.setAttribute("tabindex","-1");var r=Ao();function o(n,r){var o=Yo(t),i=o.box,a=o.content,l=o.arrow;r.theme?i.setAttribute("data-theme",r.theme):i.removeAttribute("data-theme"),"string"==typeof r.animation?i.setAttribute("data-animation",r.animation):i.removeAttribute("data-animation"),r.inertia?i.setAttribute("data-inertia",""):i.removeAttribute("data-inertia"),i.style.maxWidth="number"==typeof r.maxWidth?r.maxWidth+"px":r.maxWidth,r.role?i.setAttribute("role",r.role):i.removeAttribute("role"),n.content===r.content&&n.allowHTML===r.allowHTML||qo(a,e.props),r.arrow?l?n.arrow!==r.arrow&&(i.removeChild(l),i.appendChild(Uo(r.arrow))):i.appendChild(Uo(r.arrow)):l&&i.removeChild(l)}return r.className=mo,r.setAttribute("data-state","hidden"),qo(r,e.props),t.appendChild(n),n.appendChild(r),o(e.props,e.props),{popper:t,onUpdate:o}}Xo.$$tippy=!0;var Ko=1,Go=[],Jo=[];function Qo(e,t){var n,r,o,i,a,l,c,s,d=Fo(e,Object.assign({},zo,Zo(So(t)))),u=!1,p=!1,f=!1,h=!1,g=[],m=ko(q,d.interactiveDebounce),v=Ko++,b=(s=d.plugins).filter((function(e,t){return s.indexOf(e)===t})),w={id:v,reference:e,popper:Ao(),popperInstance:null,props:d,state:{isEnabled:!0,isVisible:!1,isDestroyed:!1,isMounted:!1,isShown:!1},plugins:b,clearDelayTimeouts:function(){clearTimeout(n),clearTimeout(r),cancelAnimationFrame(o)},setProps:function(t){if(!w.state.isDestroyed){T("onBeforeUpdate",[w,t]),$();var n=w.props,r=Fo(e,Object.assign({},n,So(t),{ignoreAttributes:!0}));w.props=r,F(),n.interactiveDebounce!==r.interactiveDebounce&&(B(),m=ko(q,r.interactiveDebounce)),n.triggerTarget&&!r.triggerTarget?Eo(n.triggerTarget).forEach((function(e){e.removeAttribute("aria-expanded")})):r.triggerTarget&&e.removeAttribute("aria-expanded"),P(),R(),C&&C(n,r),w.popperInstance&&(G(),Q().forEach((function(e){requestAnimationFrame(e._tippy.popperInstance.forceUpdate)}))),T("onAfterUpdate",[w,t])}},setContent:function(e){w.setProps({content:e})},show:function(){var e=w.state.isVisible,t=w.state.isDestroyed,n=!w.state.isEnabled,r=Po.isTouch&&!w.props.touch,o=yo(w.props.duration,0,zo.duration);if(!(e||t||n||r||S().hasAttribute("disabled")||(T("onShow",[w],!1),!1===w.props.onShow(w)))){if(w.state.isVisible=!0,M()&&(y.style.visibility="visible"),R(),I(),w.state.isMounted||(y.style.transition="none"),M()){var i=D();Lo([i.box,i.content],0)}l=function(){var e;if(w.state.isVisible&&!h){if(h=!0,y.offsetHeight,y.style.transition=w.props.moveTransition,M()&&w.props.animation){var t=D(),n=t.box,r=t.content;Lo([n,r],o),Ro([n,r],"visible")}N(),P(),Oo(Jo,w),null==(e=w.popperInstance)||e.forceUpdate(),T("onMount",[w]),w.props.animation&&M()&&function(e){W(e,(function(){w.state.isShown=!0,T("onShown",[w])}))}(o)}},function(){var e,t=w.props.appendTo,n=S();(e=w.props.interactive&&t===xo||"parent"===t?n.parentNode:_o(t,[n])).contains(y)||e.appendChild(y),w.state.isMounted=!0,G()}()}},hide:function(){var e=!w.state.isVisible,t=w.state.isDestroyed,n=!w.state.isEnabled,r=yo(w.props.duration,1,zo.duration);if(!(e||t||n)&&(T("onHide",[w],!1),!1!==w.props.onHide(w))){if(w.state.isVisible=!1,w.state.isShown=!1,h=!1,u=!1,M()&&(y.style.visibility="hidden"),B(),z(),R(!0),M()){var o=D(),i=o.box,a=o.content;w.props.animation&&(Lo([i,a],r),Ro([i,a],"hidden"))}N(),P(),w.props.animation?M()&&function(e,t){W(e,(function(){!w.state.isVisible&&y.parentNode&&y.parentNode.contains(y)&&t()}))}(r,w.unmount):w.unmount()}},hideWithInteractivity:function(e){A().addEventListener("mousemove",m),Oo(Go,m),m(e)},enable:function(){w.state.isEnabled=!0},disable:function(){w.hide(),w.state.isEnabled=!1},unmount:function(){w.state.isVisible&&w.hide(),w.state.isMounted&&(J(),Q().forEach((function(e){e._tippy.unmount()})),y.parentNode&&y.parentNode.removeChild(y),Jo=Jo.filter((function(e){return e!==w})),w.state.isMounted=!1,T("onHidden",[w]))},destroy:function(){w.state.isDestroyed||(w.clearDelayTimeouts(),w.unmount(),$(),delete e._tippy,w.state.isDestroyed=!0,T("onDestroy",[w]))}};if(!d.render)return w;var x=d.render(w),y=x.popper,C=x.onUpdate;y.setAttribute("data-tippy-root",""),y.id="tippy-"+w.id,w.popper=y,e._tippy=w,y._tippy=w;var _=b.map((function(e){return e.fn(w)})),k=e.hasAttribute("aria-expanded");return F(),P(),R(),T("onCreate",[w]),d.showOnCreate&&ee(),y.addEventListener("mouseenter",(function(){w.props.interactive&&w.state.isVisible&&w.clearDelayTimeouts()})),y.addEventListener("mouseleave",(function(){w.props.interactive&&w.props.trigger.indexOf("mouseenter")>=0&&A().addEventListener("mousemove",m)})),w;function E(){var e=w.props.touch;return Array.isArray(e)?e:[e,0]}function O(){return"hold"===E()[0]}function M(){var e;return!(null==(e=w.props.render)||!e.$$tippy)}function S(){return c||e}function A(){var e,t,n=S().parentNode;return n?null!=(t=Eo(n)[0])&&null!=(e=t.ownerDocument)&&e.body?t.ownerDocument:document:document}function D(){return Yo(y)}function L(e){return w.state.isMounted&&!w.state.isVisible||Po.isTouch||i&&"focus"===i.type?0:yo(w.props.delay,e?0:1,zo.delay)}function R(e){void 0===e&&(e=!1),y.style.pointerEvents=w.props.interactive&&!e?"":"none",y.style.zIndex=""+w.props.zIndex}function T(e,t,n){var r;void 0===n&&(n=!0),_.forEach((function(n){n[e]&&n[e].apply(n,t)})),n&&(r=w.props)[e].apply(r,t)}function N(){var t=w.props.aria;if(t.content){var n="aria-"+t.content,r=y.id;Eo(w.props.triggerTarget||e).forEach((function(e){var t=e.getAttribute(n);if(w.state.isVisible)e.setAttribute(n,t?t+" "+r:r);else{var o=t&&t.replace(r,"").trim();o?e.setAttribute(n,o):e.removeAttribute(n)}}))}}function P(){!k&&w.props.aria.expanded&&Eo(w.props.triggerTarget||e).forEach((function(e){w.props.interactive?e.setAttribute("aria-expanded",w.state.isVisible&&e===S()?"true":"false"):e.removeAttribute("aria-expanded")}))}function B(){A().removeEventListener("mousemove",m),Go=Go.filter((function(e){return e!==m}))}function j(t){if(!Po.isTouch||!f&&"mousedown"!==t.type){var n=t.composedPath&&t.composedPath()[0]||t.target;if(!w.props.interactive||!No(y,n)){if(Eo(w.props.triggerTarget||e).some((function(e){return No(e,n)}))){if(Po.isTouch)return;if(w.state.isVisible&&w.props.trigger.indexOf("click")>=0)return}else T("onClickOutside",[w,t]);!0===w.props.hideOnClick&&(w.clearDelayTimeouts(),w.hide(),p=!0,setTimeout((function(){p=!1})),w.state.isMounted||z())}}}function V(){f=!0}function H(){f=!1}function I(){var e=A();e.addEventListener("mousedown",j,!0),e.addEventListener("touchend",j,wo),e.addEventListener("touchstart",H,wo),e.addEventListener("touchmove",V,wo)}function z(){var e=A();e.removeEventListener("mousedown",j,!0),e.removeEventListener("touchend",j,wo),e.removeEventListener("touchstart",H,wo),e.removeEventListener("touchmove",V,wo)}function W(e,t){var n=D().box;function r(e){e.target===n&&(To(n,"remove",r),t())}if(0===e)return t();To(n,"remove",a),To(n,"add",r),a=r}function Z(t,n,r){void 0===r&&(r=!1),Eo(w.props.triggerTarget||e).forEach((function(e){e.addEventListener(t,n,r),g.push({node:e,eventType:t,handler:n,options:r})}))}function F(){var e;O()&&(Z("touchstart",U,{passive:!0}),Z("touchend",Y,{passive:!0})),(e=w.props.trigger,e.split(/\s+/).filter(Boolean)).forEach((function(e){if("manual"!==e)switch(Z(e,U),e){case"mouseenter":Z("mouseleave",Y);break;case"focus":Z(Io?"focusout":"blur",X);break;case"focusin":Z("focusout",X)}}))}function $(){g.forEach((function(e){var t=e.node,n=e.eventType,r=e.handler,o=e.options;t.removeEventListener(n,r,o)})),g=[]}function U(e){var t,n=!1;if(w.state.isEnabled&&!K(e)&&!p){var r="focus"===(null==(t=i)?void 0:t.type);i=e,c=e.currentTarget,P(),!w.state.isVisible&&Co(e,"MouseEvent")&&Go.forEach((function(t){return t(e)})),"click"===e.type&&(w.props.trigger.indexOf("mouseenter")<0||u)&&!1!==w.props.hideOnClick&&w.state.isVisible?n=!0:ee(e),"click"===e.type&&(u=!n),n&&!r&&te(e)}}function q(e){var t=e.target,n=S().contains(t)||y.contains(t);if("mousemove"!==e.type||!n){var r=Q().concat(y).map((function(e){var t,n=null==(t=e._tippy.popperInstance)?void 0:t.state;return n?{popperRect:e.getBoundingClientRect(),popperState:n,props:d}:null})).filter(Boolean);(function(e,t){var n=t.clientX,r=t.clientY;return e.every((function(e){var t=e.popperRect,o=e.popperState,i=e.props.interactiveBorder,a=o.placement.split("-")[0],l=o.modifiersData.offset;if(!l)return!0;var c="bottom"===a?l.top.y:0,s="top"===a?l.bottom.y:0,d="right"===a?l.left.x:0,u="left"===a?l.right.x:0,p=t.top-r+c>i,f=r-t.bottom-s>i,h=t.left-n+d>i,g=n-t.right-u>i;return p||f||h||g}))})(r,e)&&(B(),te(e))}}function Y(e){K(e)||w.props.trigger.indexOf("click")>=0&&u||(w.props.interactive?w.hideWithInteractivity(e):te(e))}function X(e){w.props.trigger.indexOf("focusin")<0&&e.target!==S()||w.props.interactive&&e.relatedTarget&&y.contains(e.relatedTarget)||te(e)}function K(e){return!!Po.isTouch&&O()!==e.type.indexOf("touch")>=0}function G(){J();var t=w.props,n=t.popperOptions,r=t.placement,o=t.offset,i=t.getReferenceClientRect,a=t.moveTransition,c=M()?Yo(y).arrow:null,s=i?{getBoundingClientRect:i,contextElement:i.contextElement||S()}:e,d=[{name:"offset",options:{offset:o}},{name:"preventOverflow",options:{padding:{top:2,bottom:2,left:5,right:5}}},{name:"flip",options:{padding:5}},{name:"computeStyles",options:{adaptive:!a}},{name:"$$tippy",enabled:!0,phase:"beforeWrite",requires:["computeStyles"],fn:function(e){var t=e.state;if(M()){var n=D().box;["placement","reference-hidden","escaped"].forEach((function(e){"placement"===e?n.setAttribute("data-placement",t.placement):t.attributes.popper["data-popper-"+e]?n.setAttribute("data-"+e,""):n.removeAttribute("data-"+e)})),t.attributes.popper={}}}}];M()&&c&&d.push({name:"arrow",options:{element:c,padding:3}}),d.push.apply(d,(null==n?void 0:n.modifiers)||[]),w.popperInstance=go(s,y,Object.assign({},n,{placement:r,onFirstUpdate:l,modifiers:d}))}function J(){w.popperInstance&&(w.popperInstance.destroy(),w.popperInstance=null)}function Q(){return Mo(y.querySelectorAll("[data-tippy-root]"))}function ee(e){w.clearDelayTimeouts(),e&&T("onTrigger",[w,e]),I();var t=L(!0),r=E(),o=r[0],i=r[1];Po.isTouch&&"hold"===o&&i&&(t=i),t?n=setTimeout((function(){w.show()}),t):w.show()}function te(e){if(w.clearDelayTimeouts(),T("onUntrigger",[w,e]),w.state.isVisible){if(!(w.props.trigger.indexOf("mouseenter")>=0&&w.props.trigger.indexOf("click")>=0&&["mouseleave","mousemove"].indexOf(e.type)>=0&&u)){var t=L(!1);t?r=setTimeout((function(){w.state.isVisible&&w.hide()}),t):o=requestAnimationFrame((function(){w.hide()}))}}else z()}}function ei(e,t){void 0===t&&(t={});var n=zo.plugins.concat(t.plugins||[]);document.addEventListener("touchstart",jo,wo),window.addEventListener("blur",Ho);var r,o=Object.assign({},t,{plugins:n}),i=(r=e,Do(r)?[r]:function(e){return Co(e,"NodeList")}(r)?Mo(r):Array.isArray(r)?r:Mo(document.querySelectorAll(r))).reduce((function(e,t){var n=t&&Qo(t,o);return n&&e.push(n),e}),[]);return Do(e)?i[0]:i}ei.defaultProps=zo,ei.setDefaultProps=function(e){Object.keys(e).forEach((function(t){zo[t]=e[t]}))},ei.currentInput=Po,Object.assign({},eo,{effect:function(e){var t=e.state,n={popper:{position:t.options.strategy,left:"0",top:"0",margin:"0"},arrow:{position:"absolute"},reference:{}};Object.assign(t.elements.popper.style,n.popper),t.styles=n,t.elements.arrow&&Object.assign(t.elements.arrow.style,n.arrow)}}),ei.setDefaultProps({render:Xo});const ti=ei;function ni(e,t){if(null==e)return{};var n,r,o={},i=Object.keys(e);for(r=0;r<i.length;r++)n=i[r],t.indexOf(n)>=0||(o[n]=e[n]);return o}var ri="undefined"!=typeof window&&"undefined"!=typeof document;function oi(e,t){e&&("function"==typeof e&&e(t),{}.hasOwnProperty.call(e,"current")&&(e.current=t))}function ii(){return ri&&document.createElement("div")}function ai(e,t){if(e===t)return!0;if("object"==typeof e&&null!=e&&"object"==typeof t&&null!=t){if(Object.keys(e).length!==Object.keys(t).length)return!1;for(var n in e){if(!t.hasOwnProperty(n))return!1;if(!ai(e[n],t[n]))return!1}return!0}return!1}function li(e){var t=[];return e.forEach((function(e){t.find((function(t){return ai(e,t)}))||t.push(e)})),t}var ci=ri?r.useLayoutEffect:r.useEffect;function si(e,t,n){n.split(/\s+/).forEach((function(n){n&&e.classList[t](n)}))}var di={name:"className",defaultValue:"",fn:function(e){var t=e.popper.firstElementChild,n=function(){var t;return!!(null==(t=e.props.render)?void 0:t.$$tippy)};function r(){e.props.className&&!n()||si(t,"add",e.props.className)}return{onCreate:r,onBeforeUpdate:function(){n()&&si(t,"remove",e.props.className)},onAfterUpdate:r}}};function ui(e){return function(t){var n,i,a=t.children,l=t.content,c=t.visible,d=t.singleton,u=t.render,p=t.reference,f=t.disabled,h=void 0!==f&&f,g=t.ignoreAttributes,m=void 0===g||g,v=(t.__source,t.__self,ni(t,["children","content","visible","singleton","render","reference","disabled","ignoreAttributes","__source","__self"])),b=void 0!==c,w=void 0!==d,x=(0,r.useState)(!1),y=x[0],C=x[1],_=(0,r.useState)({}),k=_[0],E=_[1],O=(0,r.useState)(),M=O[0],S=O[1],A=(n=function(){return{container:ii(),renders:1}},(i=(0,r.useRef)()).current||(i.current="function"==typeof n?n():n),i.current),D=Object.assign({ignoreAttributes:m},v,{content:A.container});b&&(D.trigger="manual",D.hideOnClick=!1),w&&(h=!0);var L=D,R=D.plugins||[];u&&(L=Object.assign({},D,{plugins:w&&null!=d.data?[].concat(R,[{fn:function(){return{onTrigger:function(e,t){var n=d.data.children.find((function(e){return e.instance.reference===t.currentTarget}));e.state.$$activeSingletonInstance=n.instance,S(n.content)}}}}]):R,render:function(){return{popper:A.container}}}));var T=[p].concat(a?[a.type]:[]);return ci((function(){var t=p;p&&p.hasOwnProperty("current")&&(t=p.current);var n=e(t||A.ref||ii(),Object.assign({},L,{plugins:[di].concat(D.plugins||[])}));return A.instance=n,h&&n.disable(),c&&n.show(),w&&d.hook({instance:n,content:l,props:L,setSingletonContent:S}),C(!0),function(){n.destroy(),null==d||d.cleanup(n)}}),T),ci((function(){var e,t,n,r,o;if(1!==A.renders){var i=A.instance;i.setProps((t=i.props,n=L,Object.assign({},n,{popperOptions:Object.assign({},t.popperOptions,n.popperOptions,{modifiers:li([].concat((null==(r=t.popperOptions)?void 0:r.modifiers)||[],(null==(o=n.popperOptions)?void 0:o.modifiers)||[]))})}))),null==(e=i.popperInstance)||e.forceUpdate(),h?i.disable():i.enable(),b&&(c?i.show():i.hide()),w&&d.hook({instance:i,content:l,props:L,setSingletonContent:S})}else A.renders++})),ci((function(){var e;if(u){var t=A.instance;t.setProps({popperOptions:Object.assign({},t.props.popperOptions,{modifiers:[].concat(((null==(e=t.props.popperOptions)?void 0:e.modifiers)||[]).filter((function(e){return"$$tippyReact"!==e.name})),[{name:"$$tippyReact",enabled:!0,phase:"beforeWrite",requires:["computeStyles"],fn:function(e){var t,n=e.state,r=null==(t=n.modifiersData)?void 0:t.hide;k.placement===n.placement&&k.referenceHidden===(null==r?void 0:r.isReferenceHidden)&&k.escaped===(null==r?void 0:r.hasPopperEscaped)||E({placement:n.placement,referenceHidden:null==r?void 0:r.isReferenceHidden,escaped:null==r?void 0:r.hasPopperEscaped}),n.attributes.popper={}}}])})})}}),[k.placement,k.referenceHidden,k.escaped].concat(T)),o().createElement(o().Fragment,null,a?(0,r.cloneElement)(a,{ref:function(e){A.ref=e,oi(a.ref,e)}}):null,y&&(0,s.createPortal)(u?u(function(e){var t={"data-placement":e.placement};return e.referenceHidden&&(t["data-reference-hidden"]=""),e.escaped&&(t["data-escaped"]=""),t}(k),M,A.instance):l,A.container))}}var pi=function(e,t){return(0,r.forwardRef)((function(n,i){var a=n.children,l=ni(n,["children"]);return o().createElement(e,Object.assign({},t,l),a?(0,r.cloneElement)(a,{ref:function(e){oi(i,e),oi(a.ref,e)}}):null)}))};const fi=pi(ui(ti)),hi=window.wp.element,gi=ir.div`
  display: inline-flex;
  cursor: pointer;
  &:hover {
    color: var(--cw__secondary-color);
  }
  .wc__tooltip {
    display: block !important;
  }
`,mi=({children:e,title:t,...n})=>(0,r.createElement)(gi,null,(0,r.createElement)(fi,{className:"wc__tooltip",content:t,disabled:!t,animation:"shift-away",arrow:!0,...n},e)),vi=({content:e,children:t,className:n,interactive:o,...i})=>{const a=`cw_popover ${n}`;return(0,r.createElement)(fi,{content:e,className:a,trigger:"click",theme:"light",disabled:!e,animation:"shift-away",interactive:!0,allowHTML:!0,arrow:!0,...o?{}:{appendTo:document.body},...i},(0,r.createElement)("div",null,t))},bi=ir.div`
  display: inline-block;
  position: relative;
  > div,
  button {
    height: 100%;
  }
  button {
    min-width: 40px;
    border: none;
    border-radius: var(--cw__border-radius);
    background-color: var(--cw__background-color);
    cursor: pointer;
    min-height: 36px;
    &:hover {
      color: var(--cw__secondary-color);
    }
    &:focus {
      outline: 1px dotted;
    }
  }
  .cw__unit-picker-options {
    max-width: 72px;
    width: 72px;
    border-radius: var(--cw__border-radius);
    background-color: var(--cw__background-color);
    display: flex;
    flex-wrap: wrap;
    position: absolute;
    margin-bottom: 10px;
    bottom: 100%;
    left: -17.5px;
    right: -17.5px;
    animation: fadeInUp 0.1s ease;
    border: 1px solid var(--cw__border-color);
    z-index: 1;
    &::before,
    &::after {
      content: "";
      border: 6px solid transparent;
      border-top-color: var(--cw__background-color);
      position: absolute;
      left: 50%;
      top: 100%;
      transform: translateX(-50%);
    }
    &::before {
      margin-top: 1px;
      border-top-color: #dcdcdc;
    }
    span {
      min-width: 35px;
      flex-basis: 0;
      flex-grow: 1;
      display: inline-block;
      padding: 0.5rem 0.25rem;
      text-align: center;
      font-size: 12px;
      cursor: pointer;
      border-top: 1px solid #dcdcdc;
      &:nth-of-type(2n + 1) {
        border-right: 1px solid #dcdcdc;
      }
      &:nth-of-type(-n + 2) {
        border-top: 0;
      }
      &:last-child {
        border-right: 0;
      }
      &:hover {
        background-color: #ffffff;
      }
    }
  }
`,wi=ir.div`
  max-width: 72px;
  width: 72px;
  display: flex;
  flex-wrap: wrap;
  span {
    min-width: 35px;
    flex-basis: 0;
    flex-grow: 1;
    display: inline-block;
    padding: 0.5rem 0.25rem;
    text-align: center;
    font-size: 12px;
    cursor: pointer;
    border-top: 1px solid #dcdcdc;
    &:nth-of-type(2n + 1) {
      border-right: 1px solid #dcdcdc;
    }
    &:nth-of-type(-n + 2) {
      border-top: 0;
    }
    &:last-child {
      border-right: 0;
    }
    &:hover {
      background-color: var(--cw__background-color);
    }
  }
`,xi=({value:e,onChange:t,units:n})=>{const[o,i]=(0,hi.useState)(!1),a=e=>n=>{("click"===n.type||"keydown"===n.type&&"Enter"===n.key)&&(t(e),i(!1))};return(0,r.createElement)(bi,{className:"cw__unit-picker-wrapper"},(0,r.createElement)(vi,{className:"cw__unit-picker-popover",content:n&&(0,r.createElement)(wi,null,n?.map(((e,t)=>(0,r.createElement)("span",{key:t,tabIndex:0,onClick:a(e),onKeyDown:a(e)},e)))),disabled:(n||[])?.length<=1,interactive:!0},(0,r.createElement)("button",{tabIndex:0,type:"button",onClick:()=>i(!o),onKeyDown:e=>{"keydown"===e.type&&"Enter"===e.key&&i(o)}},e||"-")))},yi={desktop:(0,r.createElement)("svg",{width:"24",height:"24",viewBox:"0 0 24 24",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("path",{d:"M20 3H4C2.89543 3 2 3.89543 2 5V15C2 16.1046 2.89543 17 4 17H20C21.1046 17 22 16.1046 22 15V5C22 3.89543 21.1046 3 20 3Z",stroke:"currentColor",strokeWidth:"2",strokeLinecap:"round",strokeLinejoin:"round"}),(0,r.createElement)("path",{d:"M8 21H16",stroke:"currentColor",strokeWidth:"2",strokeLinecap:"round",strokeLinejoin:"round"}),(0,r.createElement)("path",{d:"M12 17V21",stroke:"currentColor",strokeWidth:"2",strokeLinecap:"round",strokeLinejoin:"round"})),tablet:(0,r.createElement)("svg",{width:"24",height:"24",viewBox:"0 0 24 24",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("path",{d:"M18 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V4C20 2.89543 19.1046 2 18 2Z",stroke:"currentColor",strokeWidth:"2",strokeLinecap:"round",strokeLinejoin:"round"}),(0,r.createElement)("path",{d:"M12 18H12.01",stroke:"currentColor",strokeWidth:"2",strokeLinecap:"round",strokeLinejoin:"round"})),mobile:(0,r.createElement)("svg",{width:"24",height:"24",viewBox:"0 0 24 24",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("path",{d:"M17 2H7C5.89543 2 5 2.89543 5 4V20C5 21.1046 5.89543 22 7 22H17C18.1046 22 19 21.1046 19 20V4C19 2.89543 18.1046 2 17 2Z",stroke:"currentColor",strokeWidth:"2",strokeLinecap:"round",strokeLinejoin:"round"}),(0,r.createElement)("path",{d:"M12 18H12.01",stroke:"currentColor",strokeWidth:"2",strokeLinecap:"round",strokeLinejoin:"round"})),help:(0,r.createElement)("svg",{width:"14",height:"13",viewBox:"0 0 14 13",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("path",{d:"M7.7677 9.75C7.7677 9.89833 7.72371 10.0433 7.6413 10.1667C7.55889 10.29 7.44176 10.3861 7.30471 10.4429C7.16767 10.4997 7.01687 10.5145 6.87138 10.4856C6.7259 10.4566 6.59226 10.3852 6.48737 10.2803C6.38248 10.1754 6.31105 10.0418 6.28211 9.89632C6.25317 9.75083 6.26803 9.60003 6.32479 9.46299C6.38156 9.32594 6.47769 9.20881 6.60102 9.1264C6.72436 9.04398 6.86937 9 7.0177 9C7.21661 9 7.40738 9.07902 7.54803 9.21967C7.68868 9.36032 7.7677 9.55109 7.7677 9.75ZM7.0177 3C5.63895 3 4.5177 4.00937 4.5177 5.25V5.5C4.5177 5.63261 4.57038 5.75978 4.66415 5.85355C4.75792 5.94732 4.88509 6 5.0177 6C5.15031 6 5.27749 5.94732 5.37126 5.85355C5.46502 5.75978 5.5177 5.63261 5.5177 5.5V5.25C5.5177 4.5625 6.19083 4 7.0177 4C7.84458 4 8.5177 4.5625 8.5177 5.25C8.5177 5.9375 7.84458 6.5 7.0177 6.5C6.88509 6.5 6.75792 6.55268 6.66415 6.64644C6.57038 6.74021 6.5177 6.86739 6.5177 7V7.5C6.5177 7.63261 6.57038 7.75978 6.66415 7.85355C6.75792 7.94732 6.88509 8 7.0177 8C7.15031 8 7.27749 7.94732 7.37126 7.85355C7.46502 7.75978 7.5177 7.63261 7.5177 7.5V7.455C8.6577 7.24562 9.5177 6.33625 9.5177 5.25C9.5177 4.00937 8.39645 3 7.0177 3ZM13.5177 6.5C13.5177 7.78558 13.1365 9.04228 12.4223 10.1112C11.708 11.1801 10.6929 12.0132 9.50514 12.5052C8.31742 12.9972 7.01049 13.1259 5.74961 12.8751C4.48874 12.6243 3.33055 12.0052 2.42151 11.0962C1.51247 10.1872 0.893403 9.02896 0.642599 7.76809C0.391795 6.50721 0.520517 5.20028 1.01249 4.01256C1.50446 2.82484 2.33758 1.80968 3.4065 1.09545C4.47542 0.381218 5.73212 0 7.0177 0C8.74105 0.00181989 10.3933 0.687223 11.6119 1.90582C12.8305 3.12441 13.5159 4.77665 13.5177 6.5ZM12.5177 6.5C12.5177 5.4122 12.1951 4.34883 11.5908 3.44436C10.9864 2.53989 10.1275 1.83494 9.12246 1.41866C8.11747 1.00238 7.0116 0.893462 5.94471 1.10568C4.87781 1.3179 3.8978 1.84172 3.12862 2.61091C2.35943 3.3801 1.8356 4.36011 1.62338 5.427C1.41117 6.4939 1.52008 7.59976 1.93637 8.60476C2.35265 9.60975 3.0576 10.4687 3.96207 11.0731C4.86654 11.6774 5.9299 12 7.0177 12C8.47588 11.9983 9.87387 11.4183 10.905 10.3873C11.9361 9.35617 12.516 7.95818 12.5177 6.5Z",fill:"currentColor"})),link:(0,r.createElement)("svg",{width:"15",height:"15",viewBox:"0 0 15 15",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("path",{d:"M6.5354 7.99995C7.5054 9.36695 9.5464 9.12695 10.5464 7.99995L12.5354 5.99995C13.6594 4.77195 13.6994 3.18595 12.5354 1.99995C11.3994 0.842952 9.6714 0.842952 8.5354 1.99995L6.5354 3.99995",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"}),(0,r.createElement)("path",{d:"M8.53543 7.06999C7.56543 5.70299 5.53543 5.87299 4.53543 6.99999L2.53543 8.97499C1.41143 10.203 1.37143 11.814 2.53543 13C3.67143 14.157 5.39943 14.157 6.53543 13L8.53543 11",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"})),upload:(0,r.createElement)("svg",{width:"25",height:"23",viewBox:"0 0 25 23",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("path",{d:"M8.1176 15.8L12.5176 11.4M12.5176 11.4L16.9176 15.8M12.5176 11.4V21.3001M21.3176 16.6172C22.6613 15.5075 23.5176 13.8288 23.5176 11.95C23.5176 8.6087 20.809 5.90001 17.4676 5.90001C17.2273 5.90001 17.0024 5.77461 16.8804 5.56752C15.4459 3.13332 12.7975 1.5 9.7676 1.5C5.21124 1.5 1.51758 5.19366 1.51758 9.75002C1.51758 12.0227 2.43657 14.0808 3.92323 15.5729",stroke:"currentColor",strokeWidth:"1.46667",strokeLinecap:"round",strokeLinejoin:"round"})),minus:(0,r.createElement)("svg",{width:"11",height:"2",viewBox:"0 0 11 2",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("path",{d:"M1.35103 1.16675C1.13002 1.16675 0.918058 1.11407 0.761778 1.0203C0.605498 0.926533 0.5177 0.799356 0.5177 0.666748C0.5177 0.53414 0.605498 0.406963 0.761778 0.313195C0.918058 0.219427 1.13002 0.166748 1.35103 0.166748H9.68437C9.90538 0.166748 10.1173 0.219427 10.2736 0.313195C10.4299 0.406963 10.5177 0.53414 10.5177 0.666748C10.5177 0.799356 10.4299 0.926533 10.2736 1.0203C10.1173 1.11407 9.90538 1.16675 9.68437 1.16675H1.35103Z",fill:"currentColor"})),plus:(0,r.createElement)("svg",{width:"12",height:"12",viewBox:"0 0 12 12",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("path",{d:"M5.79272 1.27478V11.2748M0.792725 6.27478H10.7927",stroke:"currentColor",strokeLinecap:"round",strokeLinejoin:"round"})),leftAlignment:(0,r.createElement)("svg",{width:"25",height:"14",viewBox:"0 0 25 14",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("path",{d:"M1.2677 0.75H23.7677M1.2677 7H16.2677M1.2677 13.25H6.2677",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"})),centerAlignment:(0,r.createElement)("svg",{width:"23",height:"18",viewBox:"0 0 23 18",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("path",{d:"M1.23206 1.28571H21.8035M6.37491 8.99999H16.6606M3.80348 16.7143H19.2321",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"})),rightAlignment:(0,r.createElement)("svg",{width:"25",height:"14",viewBox:"0 0 25 14",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("path",{d:"M23.7677 0.75H1.2677M23.7677 7H8.7677M23.7677 13.25H18.7677",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"})),top:(0,r.createElement)("svg",{width:"16",height:"15",viewBox:"0 0 16 15",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("path",{d:"M9.08916 15H6.94631C6.35457 15 5.87488 14.5203 5.87488 13.9286V3.21429C5.87488 2.62255 6.35457 2.14286 6.94631 2.14286H9.08916C9.6809 2.14286 10.1606 2.62255 10.1606 3.21429V13.9286C10.1606 14.5203 9.6809 15 9.08916 15Z",fill:"currentColor"}),(0,r.createElement)("path",{d:"M1.05341 1.07143C0.911334 1.07143 0.775073 1.01499 0.674607 0.914522C0.574141 0.814056 0.5177 0.677795 0.5177 0.535714C0.5177 0.393634 0.574141 0.257373 0.674607 0.156907C0.775073 0.0564411 0.911334 0 1.05341 0V1.07143ZM14.982 0C15.1241 0 15.2603 0.0564411 15.3608 0.156907C15.4613 0.257373 15.5177 0.393634 15.5177 0.535714C15.5177 0.677795 15.4613 0.814056 15.3608 0.914522C15.2603 1.01499 15.1241 1.07143 14.982 1.07143V0ZM1.05341 0H14.982V1.07143H1.05341V0Z",fill:"currentColor"})),middle:(0,r.createElement)("svg",{width:"13",height:"15",viewBox:"0 0 13 15",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("path",{d:"M6.51768 0C6.65976 0 6.79602 0.0564411 6.89649 0.156907C6.99696 0.257373 7.0534 0.393634 7.0534 0.535714V5.35714H5.98197V0.535714C5.98197 0.393634 6.03841 0.257373 6.13888 0.156907C6.23934 0.0564411 6.3756 0 6.51768 0ZM6.51768 15C6.3756 15 6.23934 14.9436 6.13888 14.8431C6.03841 14.7426 5.98197 14.6064 5.98197 14.4643V9.64286H7.0534V14.4643C7.0534 14.6064 6.99696 14.7426 6.89649 14.8431C6.79602 14.9436 6.65976 15 6.51768 15ZM0.0891113 6.42857C0.0891113 6.14441 0.201994 5.87189 0.402925 5.67096C0.603857 5.47003 0.876379 5.35714 1.16054 5.35714H11.8748C12.159 5.35714 12.4315 5.47003 12.6324 5.67096C12.8334 5.87189 12.9463 6.14441 12.9463 6.42857V8.57143C12.9463 8.85559 12.8334 9.12811 12.6324 9.32904C12.4315 9.52997 12.159 9.64286 11.8748 9.64286H1.16054C0.876379 9.64286 0.603857 9.52997 0.402925 9.32904C0.201994 9.12811 0.0891113 8.85559 0.0891113 8.57143V6.42857Z",fill:"currentColor"})),bottom:(0,r.createElement)("svg",{width:"16",height:"15",viewBox:"0 0 16 15",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("path",{d:"M9.08916 0H6.94631C6.35457 0 5.87488 0.479695 5.87488 1.07143V11.7857C5.87488 12.3774 6.35457 12.8571 6.94631 12.8571H9.08916C9.6809 12.8571 10.1606 12.3774 10.1606 11.7857V1.07143C10.1606 0.479695 9.6809 0 9.08916 0Z",fill:"currentColor"}),(0,r.createElement)("path",{d:"M1.05341 13.9286C0.911334 13.9286 0.775073 13.985 0.674607 14.0855C0.574141 14.186 0.5177 14.3222 0.5177 14.4643C0.5177 14.6064 0.574141 14.7426 0.674607 14.8431C0.775073 14.9436 0.911334 15 1.05341 15V13.9286ZM14.982 15C15.1241 15 15.2603 14.9436 15.3608 14.8431C15.4613 14.7426 15.5177 14.6064 15.5177 14.4643C15.5177 14.3222 15.4613 14.186 15.3608 14.0855C15.2603 13.985 15.1241 13.9286 14.982 13.9286V15ZM1.05341 15H14.982V13.9286H1.05341V15Z",fill:"currentColor"})),pen:(0,r.createElement)("svg",{width:"25",height:"24",viewBox:"0 0 25 24",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("path",{d:"M5.51758 15.36V19H9.17618L19.5176 8.65405L15.8651 5L5.51758 15.36Z",stroke:"currentColor",strokeWidth:"1.5",strokeLinejoin:"round"}),(0,r.createElement)("path",{d:"M12.5176 8L16.5176 12",stroke:"currentColor",strokeWidth:"1.5"})),none:(0,r.createElement)("svg",{width:"20",height:"20",viewBox:"0 0 20 20",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("path",{d:"M4.10829 4.10829L15.8916 15.8916M18.3333 9.99996C18.3333 14.6023 14.6023 18.3333 9.99996 18.3333C5.39759 18.3333 1.66663 14.6023 1.66663 9.99996C1.66663 5.39759 5.39759 1.66663 9.99996 1.66663C14.6023 1.66663 18.3333 5.39759 18.3333 9.99996Z",stroke:"currentColor",strokeWidth:"1.67",strokeLinecap:"round",strokeLinejoin:"round"})),dashed:(0,r.createElement)("svg",{width:"20",height:"20",viewBox:"0 0 20 20",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("path",{d:"M2.91675 10.8334C2.56953 10.8334 2.27439 10.7118 2.03133 10.4688C1.78828 10.2257 1.66675 9.9306 1.66675 9.58337C1.66675 9.23615 1.78828 8.94101 2.03133 8.69796C2.27439 8.4549 2.56953 8.33337 2.91675 8.33337H7.91675C8.26397 8.33337 8.55911 8.4549 8.80216 8.69796C9.04522 8.94101 9.16675 9.23615 9.16675 9.58337C9.16675 9.9306 9.04522 10.2257 8.80216 10.4688C8.55911 10.7118 8.26397 10.8334 7.91675 10.8334H2.91675ZM12.0834 10.8334C11.7362 10.8334 11.4411 10.7118 11.198 10.4688C10.9549 10.2257 10.8334 9.9306 10.8334 9.58337C10.8334 9.23615 10.9549 8.94101 11.198 8.69796C11.4411 8.4549 11.7362 8.33337 12.0834 8.33337H17.0834C17.4306 8.33337 17.7258 8.4549 17.9688 8.69796C18.2119 8.94101 18.3334 9.23615 18.3334 9.58337C18.3334 9.9306 18.2119 10.2257 17.9688 10.4688C17.7258 10.7118 17.4306 10.8334 17.0834 10.8334H12.0834Z",fill:"currentColor"})),menu:(0,r.createElement)("svg",{width:"20",height:"20",viewBox:"0 0 20 20",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("path",{d:"M2.5 7.08337H17.5M2.5 12.9167H17.5",stroke:"currentColor",strokeWidth:"1.67",strokeLinecap:"round",strokeLinejoin:"round"})),ellipsis:(0,r.createElement)("svg",{width:"20",height:"20",viewBox:"0 0 20 20",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("path",{d:"M10 10.8334C10.4603 10.8334 10.8334 10.4603 10.8334 10.0001C10.8334 9.53984 10.4603 9.16675 10 9.16675C9.5398 9.16675 9.16671 9.53984 9.16671 10.0001C9.16671 10.4603 9.5398 10.8334 10 10.8334Z",stroke:"currentColor",strokeWidth:"1.67",strokeLinecap:"round",strokeLinejoin:"round"}),(0,r.createElement)("path",{d:"M15.8334 10.8334C16.2936 10.8334 16.6667 10.4603 16.6667 10.0001C16.6667 9.53984 16.2936 9.16675 15.8334 9.16675C15.3731 9.16675 15 9.53984 15 10.0001C15 10.4603 15.3731 10.8334 15.8334 10.8334Z",stroke:"currentColor",strokeWidth:"1.67",strokeLinecap:"round",strokeLinejoin:"round"}),(0,r.createElement)("path",{d:"M4.16671 10.8334C4.62694 10.8334 5.00004 10.4603 5.00004 10.0001C5.00004 9.53984 4.62694 9.16675 4.16671 9.16675C3.70647 9.16675 3.33337 9.53984 3.33337 10.0001C3.33337 10.4603 3.70647 10.8334 4.16671 10.8334Z",stroke:"currentColor",strokeWidth:"1.67",strokeLinecap:"round",strokeLinejoin:"round"})),chevronDown:(0,r.createElement)("svg",{width:"13",height:"9",viewBox:"0 0 13 9",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("g",{clipPath:"url(#clip0_336_894)"},(0,r.createElement)("path",{d:"M1.01758 2L6.01758 7L11.0176 2",stroke:"currentColor",strokeWidth:"2",strokeLinecap:"round",strokeLinejoin:"round"})),(0,r.createElement)("defs",null,(0,r.createElement)("clipPath",{id:"clip0_336_894"},(0,r.createElement)("rect",{width:"12",height:"8",fill:"white",transform:"translate(0.0175781 0.5)"})))),move:(0,r.createElement)("svg",{width:"12",height:"20",viewBox:"0 0 12 20",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("g",{clipPath:"url(#clip0_724_134)"},(0,r.createElement)("path",{d:"M0.75 0.25H3.75V3.25H0.75V0.25ZM8.25 0.25H11.25V3.25H8.25V0.25ZM0.75 5.75H3.75V8.75H0.75V5.75ZM8.25 5.75H11.25V8.75H8.25V5.75ZM0.75 11.25H3.75V14.25H0.75V11.25ZM8.25 11.25H11.25V14.25H8.25V11.25ZM0.75 16.75H3.75V19.75H0.75V16.75ZM8.25 16.75H11.25V19.75H8.25V16.75Z",fill:"currentColor"})),(0,r.createElement)("defs",null,(0,r.createElement)("clipPath",{id:"clip0_724_134"},(0,r.createElement)("rect",{width:"12",height:"20",fill:"white"})))),dot:(0,r.createElement)("svg",{width:"8",height:"8",viewBox:"0 0 8 8",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("g",{clipPath:"url(#clip0_724_5659)"},(0,r.createElement)("path",{d:"M3.86535 0.538818C2.94729 0.538818 2.06683 0.903516 1.41767 1.55268C0.768506 2.20184 0.403809 3.0823 0.403809 4.00036C0.403809 4.91841 0.768506 5.79887 1.41767 6.44803C2.06683 7.0972 2.94729 7.4619 3.86535 7.4619C5.7865 7.4619 7.32689 5.92151 7.32689 4.00036C7.32689 3.0823 6.96219 2.20184 6.31302 1.55268C5.66386 0.903516 4.7834 0.538818 3.86535 0.538818Z",fill:"currentColor"})),(0,r.createElement)("defs",null,(0,r.createElement)("clipPath",{id:"clip0_724_5659"},(0,r.createElement)("rect",{width:"6.92308",height:"6.92308",fill:"white",transform:"translate(0.403809 0.538818)"})))),pipe:(0,r.createElement)("svg",{width:"4",height:"14",viewBox:"0 0 4 14",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("g",{clipPath:"url(#clip0_724_5665)"},(0,r.createElement)("path",{d:"M1.86536 12.7689V1.23047",stroke:"currentColor",strokeWidth:"1.38462",strokeLinecap:"round",strokeLinejoin:"round"})),(0,r.createElement)("defs",null,(0,r.createElement)("clipPath",{id:"clip0_724_5665"},(0,r.createElement)("rect",{width:"2.30769",height:"13.8462",fill:"white",transform:"translate(0.711548 0.0769043)"})))),slash:(0,r.createElement)("svg",{width:"11",height:"14",viewBox:"0 0 11 14",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("g",{clipPath:"url(#clip0_724_5668)"},(0,r.createElement)("path",{d:"M9.6923 0.942139L1.03845 13.0575",stroke:"currentColor",strokeWidth:"1.38462",strokeLinecap:"round",strokeLinejoin:"round"})),(0,r.createElement)("defs",null,(0,r.createElement)("clipPath",{id:"clip0_724_5668"},(0,r.createElement)("rect",{width:"10.3846",height:"13.8462",fill:"white",transform:"translate(0.173096 0.0769043)"})))),brush:(0,r.createElement)("svg",{width:"25",height:"24",viewBox:"0 0 25 24",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("g",{mask:"url(#mask0_2471_2065)"},(0,r.createElement)("path",{d:"M6.5177 21C5.7677 21 5.02603 20.8167 4.2927 20.45C3.55937 20.0833 2.9677 19.6 2.5177 19C2.95103 19 3.3927 18.8292 3.8427 18.4875C4.2927 18.1458 4.5177 17.65 4.5177 17C4.5177 16.1667 4.80937 15.4583 5.3927 14.875C5.97603 14.2917 6.68437 14 7.5177 14C8.35103 14 9.05937 14.2917 9.6427 14.875C10.226 15.4583 10.5177 16.1667 10.5177 17C10.5177 18.1 10.126 19.0417 9.3427 19.825C8.55937 20.6083 7.6177 21 6.5177 21ZM12.2677 15L9.5177 12.25L18.4677 3.29999C18.651 3.11666 18.8802 3.02083 19.1552 3.01249C19.4302 3.00416 19.6677 3.09999 19.8677 3.29999L21.2177 4.64999C21.4177 4.84999 21.5177 5.08333 21.5177 5.34999C21.5177 5.61666 21.4177 5.84999 21.2177 6.04999L12.2677 15Z",fill:"currentColor"}))),gradient:(0,r.createElement)("svg",{width:"25",height:"24",viewBox:"0 0 25 24",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("g",{mask:"url(#mask0_2471_2070)"},(0,r.createElement)("path",{d:"M3.5177 3V21H21.5177V3H3.5177ZM10.1844 19.6667H9.85103V4.33333H10.1844V19.6667ZM12.1844 19.6667H11.5177V4.33333H12.1844V19.6667ZM14.1844 19.6667H13.1844V4.33333H14.1844V19.6667ZM16.1844 19.6667H14.851V4.33333H16.1844V19.6667ZM20.1844 19.6667H16.5177V4.33333H20.1844V19.6667Z",fill:"currentColor"}))),"no-repeat":(0,r.createElement)("svg",{width:"24",height:"24",viewBox:"0 0 24 24",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("path",{d:"M14 11.5C14 12.8807 12.8807 14 11.5 14C10.1193 14 9 12.8807 9 11.5C9 10.1193 10.1193 9 11.5 9C12.8807 9 14 10.1193 14 11.5Z",fill:"currentColor"})),"repeat-x":(0,r.createElement)("svg",{width:"24",height:"24",viewBox:"0 0 24 24",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("circle",{cx:"4.5",cy:"11.5",r:"2.5",fill:"currentColor"}),(0,r.createElement)("circle",{cx:"11.5",cy:"11.5",r:"2.5",fill:"currentColor"}),(0,r.createElement)("circle",{cx:"18.5",cy:"11.5",r:"2.5",fill:"currentColor"})),"repeat-y":(0,r.createElement)("svg",{width:"24",height:"24",viewBox:"0 0 24 24",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("circle",{cx:"11.5",cy:"4.5",r:"2.5",transform:"rotate(90 11.5 4.5)",fill:"currentColor"}),(0,r.createElement)("circle",{cx:"11.5",cy:"11.5",r:"2.5",transform:"rotate(90 11.5 11.5)",fill:"currentColor"}),(0,r.createElement)("circle",{cx:"11.5",cy:"18.5",r:"2.5",transform:"rotate(90 11.5 18.5)",fill:"currentColor"})),repeat:(0,r.createElement)("svg",{width:"24",height:"24",viewBox:"0 0 24 24",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("circle",{cx:"4.5",cy:"11.5",r:"2.5",fill:"currentColor"}),(0,r.createElement)("circle",{cx:"11.5",cy:"11.5",r:"2.5",fill:"currentColor"}),(0,r.createElement)("circle",{cx:"18.5",cy:"11.5",r:"2.5",fill:"currentColor"}),(0,r.createElement)("circle",{cx:"4.5",cy:"18.5",r:"2.5",fill:"currentColor"}),(0,r.createElement)("circle",{cx:"11.5",cy:"18.5",r:"2.5",fill:"currentColor"}),(0,r.createElement)("circle",{cx:"18.5",cy:"18.5",r:"2.5",fill:"currentColor"}),(0,r.createElement)("circle",{cx:"4.5",cy:"4.5",r:"2.5",fill:"currentColor"}),(0,r.createElement)("circle",{cx:"11.5",cy:"4.5",r:"2.5",fill:"currentColor"}),(0,r.createElement)("circle",{cx:"18.5",cy:"4.5",r:"2.5",fill:"currentColor"}))},Ci=ir.button`
  padding: 4px;
  // border: 1px solid var(--cw__border-color);
  border: none;
  border-radius: var(--cw__border-radius);
  cursor: pointer;
  background: none;
  box-shadow: 0 0 0 1px var(--cw__border-color);
  &:hover,
  &.changed {
    color: var(--cw__secondary-color);
    box-shadow: 0 0 0 1px var(--cw__secondary-color);
  }
  svg{
    vertical-align: top;
  }
  &+button{
    margin-left: 8px;
  }
`,_i=({title:e,changed:t,children:n,...o})=>(0,r.createElement)(mi,{title:e},(0,r.createElement)(Ci,{type:"button",className:1===t?"changed":"",...o},n||yi.pen)),ki=(ir.div`
    padding: 8px 16px;
    font-size: 12px;
    color: #717578;
    background-color: #F6F6F6;
`,ir.div`
    color: var(--cw__primary-color);
    padding: 16px 0;
    width: 100%;

    * {
        box-sizing: border-box;
    }

    .cw__control-item {
        padding: 0;
        width: unset;
    }

    &[data-divider*="top"] {
        border-top: 1px solid var(--cw__background-color);
        padding-top: 16px;
    }

    &[data-divider*="bottom"] {
        border-bottom: 1px solid var(--cw__background-color);
        padding-bottom: 16px;
    }

    > header {
        &:not(:empty) {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            flex: 1;
        }

        label {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
            position: relative;
            display: inline-flex;
            align-items: center;
            color: #2b3034;
        }

        .cw__action-buttons {
            display: flex;
            align-items: center;
            gap: 8px;
        }
    }

    &:not(.horizontal) {
        > header {
            margin: 0 0 16px;
        }
    }

    .cw__control-description {
        flex: 0 0 100%;
        margin: 0 0 16px;
        font-size: 13px;
        line-height: 1.5;
    }

    header + .cw__control-description{
        margin-top: 12px;
    }

    .cw__reset-button {
        display: inline-block;
        padding: 0;
        width: 16px;
        height: 16px;
        border: none;
        background: none;
        background-image: url("data:image/svg+xml,%3Csvg width='13' height='13' viewBox='0 0 13 13' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.93963 2.09581C2.49505 1.53695 3.15568 1.09348 3.88342 0.790986C4.61115 0.488489 5.3916 0.332942 6.17978 0.333314C9.49685 0.333314 12.176 3.01831 12.176 6.33331C12.176 9.64831 9.49685 12.3333 6.17978 12.3333C3.38053 12.3333 1.04657 10.4208 0.378653 7.83331H1.93963C2.24877 8.71045 2.82267 9.4701 3.58215 10.0074C4.34162 10.5448 5.24924 10.8333 6.17978 10.8333C8.66383 10.8333 10.6826 8.81581 10.6826 6.33331C10.6826 3.85081 8.66383 1.83331 6.17978 1.83331C4.934 1.83331 3.82331 2.35081 3.0128 3.16831L5.42931 5.58331H0.176025V0.333314L1.93963 2.09581Z' fill='%2393999F'/%3E%3C/svg%3E%0A");
        background-repeat: no-repeat;
        background-position: center;
        background-size: 100%;
        font-size: 0;
        cursor: pointer;
        transition: var(--cw__transition);

        &:hover {
            transform: rotate(-30deg);
        }
    }

    .cw__visibility-button {
        display: inline-block;
        padding: 0;
        width: 16px;
        height: 16px;
        border: none;
        background: none;
        background-image: url("data:image/svg+xml,%3Csvg width='19' height='14' viewBox='0 0 19 14' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M9.16667 10.75C10.2083 10.75 11.0938 10.3854 11.8229 9.65625C12.5521 8.92708 12.9167 8.04167 12.9167 7C12.9167 5.95833 12.5521 5.07292 11.8229 4.34375C11.0938 3.61458 10.2083 3.25 9.16667 3.25C8.125 3.25 7.23958 3.61458 6.51042 4.34375C5.78125 5.07292 5.41667 5.95833 5.41667 7C5.41667 8.04167 5.78125 8.92708 6.51042 9.65625C7.23958 10.3854 8.125 10.75 9.16667 10.75ZM9.16667 9.25C8.54167 9.25 8.01042 9.03125 7.57292 8.59375C7.13542 8.15625 6.91667 7.625 6.91667 7C6.91667 6.375 7.13542 5.84375 7.57292 5.40625C8.01042 4.96875 8.54167 4.75 9.16667 4.75C9.79167 4.75 10.3229 4.96875 10.7604 5.40625C11.1979 5.84375 11.4167 6.375 11.4167 7C11.4167 7.625 11.1979 8.15625 10.7604 8.59375C10.3229 9.03125 9.79167 9.25 9.16667 9.25ZM9.16667 13.25C7.13889 13.25 5.29167 12.684 3.625 11.5521C1.95833 10.4201 0.75 8.90278 0 7C0.75 5.09722 1.95833 3.57986 3.625 2.44792C5.29167 1.31597 7.13889 0.75 9.16667 0.75C11.1944 0.75 13.0417 1.31597 14.7083 2.44792C16.375 3.57986 17.5833 5.09722 18.3333 7C17.5833 8.90278 16.375 10.4201 14.7083 11.5521C13.0417 12.684 11.1944 13.25 9.16667 13.25Z' fill='%2342474B'/%3E%3C/svg%3E%0A");
        background-repeat: no-repeat;
        background-position: center;
        background-size: 100%;
        font-size: 0;
        cursor: pointer;
        transition: var(--cw__transition);
    }

    .cw__reset-button + .cw__responsive-buttons {
        position: relative;
        padding-left: 10px;

        &::before {
            content: "";
            width: 0;
            height: 14px;
            border-left: 2px solid var(--cw__border-color);
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
        }
    }

    &.horizontal {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        column-gap: 8px;
        // > section {
        //   max-width: 150px;
        // }

        .cw__custom-select {
            .cw__select-dropdown {
                left: auto;
                right: 0;
            }
        }

        .cw__color-picker-popover {
            right: 0;
        }

        > header > .cw__action-buttons {
            padding-right: 10px;
            position: relative;

            &::after {
                content: "";
                width: 0;
                height: 14px;
                border-right: 2px solid var(--cw__border-color);
                position: absolute;
                top: 50%;
                right: 0;
                transform: translateY(-50%);
            }
        }
    }
`),Ei=ir.div`
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;

    .cw__responsive-button {
        font-size: 15px;
        cursor: pointer;
        color: var(--cw__inactive-color);
        transition: var(--cw__transition);
        padding: 0;
        border: none;
        background: none;

        svg {
            width: 1em;
            height: 1em;
            vertical-align: -0.12em;
        }

        &:hover,
        &.active {
            color: var(--cw__secondary-color);
        }
    }
`,Oi=ir.i`
    margin: 0 8px;
`,Mi=({device:e,onChange:t})=>(0,r.createElement)(Ei,{className:"cw__responsive-buttons"},(0,r.createElement)("button",{className:"cw__responsive-button"+("desktop"===e?" active":""),onClick:()=>t("desktop"),title:"Desktop"},yi.desktop),(0,r.createElement)("button",{className:"cw__responsive-button"+("tablet"===e?" active":""),onClick:()=>t("tablet"),title:"Tablet"},yi.tablet),(0,r.createElement)("button",{className:"cw__responsive-button"+("mobile"===e?" active":""),onClick:()=>t("mobile"),title:"Mobile"},yi.mobile)),Si=e=>({direction:t,className:n,label:o,divider:i,description:a,value:l,defaultValue:c,onChange:s,responsive:d,isChildren:u,visibility:p,setVisibility:f,help:h,children:g,hideResetButton:m=!0,containerStyle:v,...b})=>{let w=(0,hi.useRef)(null);null==w.current&&(w.current=l);const[x,y]=(0,hi.useState)("desktop"),C=JSON.stringify(c||w.current),_=JSON.stringify(l);return(0,r.createElement)(ki,{className:`cw__control-item ${t||""} ${n||""}`,"data-visibility":!!p&&"hidden","data-divider":i},o&&(0,r.createElement)("header",null,(0,r.createElement)("label",null,o,h&&(0,r.createElement)(mi,{title:h},(0,r.createElement)(Oi,null,yi.help))),(p||!m&&!u&&C!==_||d)&&(0,r.createElement)("div",{className:"cw__action-buttons"},!m&&(0,r.createElement)(r.Fragment,null,!u&&C!==_&&(0,r.createElement)("button",{tabIndex:0,className:"cw__reset-button",onClick:()=>s(w.current)},"Reset")),d&&(0,r.createElement)(Mi,{onChange:y,device:x}),p&&(0,r.createElement)("button",{className:"cw__visibility-button",onClick:()=>{f(!p)}},"Visibility"))),a&&"horizontal"!==t&&(0,r.createElement)("div",{className:"cw__control-description"},a),(0,r.createElement)("section",{className:n||"",style:v},(0,r.createElement)(e,{changed:C!==_?1:0,value:d?l[x]:l,onChange:e=>{return t=e,void s(d?{...l,[x]:t}:t);var t},...b}),g),a&&"horizontal"===t&&(0,r.createElement)("div",{className:"cw__control-description",style:{margin:"16px 0 0"}},a))},Ai={close:(0,r.createElement)("svg",{width:"9",height:"10",viewBox:"0 0 9 10",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)("path",{d:"M8.12428 1.46449L1.05321 8.53556M1.05321 1.46449L8.12428 8.53556",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"}))},Di=ir.div`
	position: relative;
	font-size: 14px;
	min-width: 136px;
	[data-tippy-root]{
		width: 100%;
	}
	.tippy-box{
		background: none;
	}
	.tippy-content{
		padding: 6px !important;
		background-color: #ffffff;
		border-radius: var(--cw__border-radius);
		box-shadow:
		  0px 4px 6px -2px #10182808,
		  0px 12px 16px -4px #10182814;
		border: 1px solid var(--cw__border-color);
		padding-top: 0.5rem;
		min-width: 100%;
	}
  .cw__custom-select__input-wrapper{
    padding-right: 32px !important;
    position: relative;
    &::after {
        content: "";
        width: 1rem;
        height: 1rem;
        background-color: var(--cw__inactive-color);
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        transition: var(--cw__transition);
        mask: url("data:image/svg+xml,%3Csvg width='15' height='8' viewBox='0 0 15 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.5177 1L7.5177 7L13.5177 1' stroke='%2393999F' stroke-width='2' strokeLinecap='round' strokeLinejoin='round'/%3E%3C/svg%3E%0A");
        mask-size: 100%;
        mask-position: center;
        mask-repeat: no-repeat;
    }
    ${e=>e.disabled&&"\n      cursor: not-allowed !important;\n      opacity: .5;\n    "}
  }
  .open {
    .cw__custom-select__input-wrapper{
      &::after {
        transform: translateY(-50%) rotate(180deg);
      }
    }
  }
  .cw__select-input {
    padding-right: 2rem;
    cursor: default;
  }
  .cw__select-dropdown {
    input[type="search"] {
      margin: 0 0 8px;
      border: 1px solid #D8E6FC;
      font-size: 14px;
      min-height: 32px;
    }
    .cw__404-text {
      display: block;
      text-align: center;
      color: #ff0e0e;
      font-weight: 600;
      padding: 6px;
    }
  }
  .cw__select-options {
    padding: 0;
    margin: 0;
    list-style: none;
    max-height: 202px;
    overflow-y: auto;
    li {
      padding: 10.5px 8px;
      cursor: default;
      border-radius: var(--cw__border-radius);
      color: #2b3034;
      cursor: pointer;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 8px;
	  margin-bottom: 0.25rem;
      &:last-child {
		margin-bottom: 0;
      }
      &:hover {
        color: var(--cw__secondary-color);
        background-color: #F6F6F6;
      }
      input[type="checkbox"] {
        margin: 0;
        &:checked{
          border-color: var(--cw__secondary-color);
          background-color: #EFF5FF;
        }
      }
      &.selected {
        font-weight: 600;
        color: var(--cw__secondary-color);
        background-color: var(--cw__background-color);
        padding-right: 40px;
        ${e=>!e.hasCheckbox&&"\n          background-image: url(\"data:image/svg+xml,%3Csvg width='21' height='20' viewBox='0 0 21 20' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M16.7021 5L7.53544 14.1667L3.36877 10' stroke='%23216BDB' stroke-width='1.66667' strokeLinecap='round' strokeLinejoin='round'/%3E%3C/svg%3E%0A\");\n          background-size: 20px 20px;\n          background-repeat: no-repeat;\n          background-position: center right 10px;  \n        "}
      }
      .icon {
        display: inline-flex;
        font-size: 20px;
        svg {
          width: 1em;
          height: 1em;
        }
      }
      .icon + .text {
        margin-left: 8px;
      }
    }
  }
  &.solid {
    .cw__custom-select__input-wrapper {
      border-color: transparent;
      background-color: var(--cw__background-color);
    }
  }
  .cw__custom-select__input-wrapper {
    min-width: 100px;
    color: #2b3034;
    border: 1px solid var(--cw__border-color);
    border-radius: var(--cw__border-radius);
    min-height: 44px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    padding: 10px;
    gap: 8px;
    cursor: pointer;
    input.cw__custom-select__input {
      min-height: unset;
      padding: 0;
      width: 1px;
      min-width: unset;
      border: none;
    }
    &:focus {
      border-color: var(--cw__secondary-color);
    }
    .cw__custom-select__input-value {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .placeholder {
      color: var(--cw__inactive-color);
    }
    > .cw__badge-container{
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }
  }
  &:not(.is-multiple) {
    .cw__custom-select__input-wrapper {
      padding-right: 32px;
    }
  }
`,Li=ir.div`
  display: inline-flex;
  gap: 2px;
  align-items: center;
  color: #2b3034;
  padding: 6px;
  background-color: #e5f0ff;
  border-radius: var(--cw__border-radius);
  transition: var(--cw__transition);
  > span{
    max-width: 90px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .cw__cancel {
    border: none;
    background: none;
    padding: 0;
    cursor: pointer;
    flex: 0 0 20px;
    height: 20px;
    width: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--cw__transition);
    border-radius: var(--cw__border-radius);
    &:hover{
      background-color: #ff0e0e;
      color: #ffffff;
    }
  }
`,Ri=({value:e,options:t=[],isSearchable:n,onSelect:o,onSearch:a,isMultiple:l,checkbox:c})=>(0,r.createElement)("div",{className:"cw__select-dropdown"},n&&(0,r.createElement)("input",{type:"search",placeholder:(0,i.__)("Search...","Rishi"),onChange:a}),t.length<=0&&(0,r.createElement)("span",{className:"cw__404-text"},"There are no options!"),(0,r.createElement)("ul",{className:"cw__select-options"},t?.map((({value:t,label:n,icon:i},a)=>{const s=l?e.includes(t):e==t;return(0,r.createElement)("li",{key:a,tabIndex:0,className:s?"selected":"",onClick:o(t),onKeyDown:o(t)},c&&(0,r.createElement)("input",{type:"checkbox",checked:s,style:{margin:"0px"}}),i&&(0,r.createElement)("i",{className:"icon"},i),(0,r.createElement)("span",{className:"text"},n))})))),Ti=({onChange:e,onCancelClick:t,options:n,value:o,isMultiple:i,isSearchable:a,isSortable:l=!1,placeholder:c,variant:s,style:d,disabled:u=!1,checkbox:p=!1})=>{const[f,h]=(0,hi.useState)(!1),g=(0,hi.useRef)(null),m=n?.find((e=>e.value==o));let v=f||n;v=i?v.filter((e=>o.includes(e.value))).concat(v.filter((e=>!o.includes(e.value)))):v;const b=l?Bi:"div";return(0,r.createElement)(Di,{className:`${i?" is-multiple":""} ${s||""}`,disabled:u,hasCheckbox:p},(0,r.createElement)(fi,{content:(0,r.createElement)(Ri,{value:o,isSearchable:a,options:v,onSelect:t=>n=>{("click"===n.type||"keydown"===n.type&&"Enter"===n.key)&&(e(i?o.includes(t)?o.filter((e=>e!=t)):[...o,t]:t),g.current.focus())},onSearch:e=>{const t=e.target.value.toLowerCase();h(i?n.filter((e=>e.label.toLowerCase().match(t))):n.filter((e=>e.value.toLowerCase().split("-").join(" ").match(t))))},checkbox:p,isMultiple:i}),animation:"shift-away",trigger:"click",arrow:!1,interactive:!0,disabled:u},(0,r.createElement)("div",{className:"cw__custom-select "+(u?"disabled":"")},(0,r.createElement)("div",{tabIndex:0,className:"cw__custom-select__input-wrapper",ref:g,style:d},i&&!p&&(0,r.createElement)(b,{className:l?"":"cw__badge-container",style:{padding:"0px"},items:o,setItems:e},o?.map(((i,a)=>{const l=n?.find((e=>e.value===i))?.label;return(0,r.createElement)(Pi,{key:i,id:i,text:l,onCancel:()=>{t?t(i):e(o?.filter((e=>e!==i)))}})}))),!i&&(0,r.createElement)("span",{className:"cw__custom-select__input-value"},m?.icon,m?.label),c&&(i&&p||o?.length<=0)&&(0,r.createElement)("span",{className:"placeholder"},c||"Select")))))},Ni=e=>Si(Ti)(e),Pi=e=>{const{attributes:t,listeners:n,setNodeRef:o,transform:i,transition:a}=St({id:e.id}),{children:l}=e,c={transform:L.Transform.toString(i),transition:a};return(0,r.createElement)(Li,{style:c,ref:o,...t},(0,r.createElement)("span",{title:e?.text,className:"cw__selected-badge",...n},e?.text),(0,r.createElement)("button",{type:"button","aria-label":"cancel",className:"cw__cancel",onClick:e?.onCancel},Ai.close))},Bi=({children:e,items:t,setItems:n})=>{const o=function(){for(var e=arguments.length,t=new Array(e),n=0;n<e;n++)t[n]=arguments[n];return(0,r.useMemo)((()=>[...t].filter((e=>null!=e))),[...t])}(Z(Ne),Z(Ae,{coordinateGetter:Lt}));return(0,r.createElement)(ut,{sensors:o,collisionDetection:G,onDragEnd:e=>{const{active:t,over:r}=e;t.id!==r.id&&n((e=>{const n=e.indexOf(t.id),o=e.indexOf(r.id);return gt(e,n,o)}))}},(0,r.createElement)(yt,{items:t},e))},ji=({onChange:e,...t})=>(0,r.createElement)("input",{type:"text",onChange:t=>e(t.target.value),...t}),Vi=e=>Si(ji)(e),Hi=({onChange:e,...t})=>(0,r.createElement)("textarea",{onChange:t=>e(t.target.value),...t}),Ii=e=>Si(Hi)(e),zi=ir.div`
    width: 40px;
    height: 22px;
    border-radius: 45px;
    background-color: #d1d1d1;
    position: relative;
    box-shadow: var(--cw__box-shadow);
    transition: var(--cw__transition);
    cursor: pointer;
    span{
        content: "";
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background-color: #ffffff;
        position: absolute;
        top: 2px;
        left: 2px;
        transition: var(--cw__transition);
        box-shadow: 2px 0px 4px rgba(0,0,0, .1)
    }
    &.checked{
        background-color: var(--cw__secondary-color);
        span{
            left: 20px;
            box-shadow: -2px 0px 4px rgba(0,0,0, .1)
        }
    }
`,Wi=({onChange:e,value:t})=>(0,r.createElement)(zi,{tabIndex:0,className:"cw__switch"+(t?" checked":""),onClick:()=>e(!t),onKeyDown:n=>{"keydown"===n.type&&"Enter"===n.key&&e(!t)}},(0,r.createElement)("span",null)),Zi=e=>Si(Wi)({...e,hideResetButton:!0,direction:"horizontal"}),Fi=ir.label`
  display: inline-flex;
  align-items: center;
  justify-content: center;
  position: relative;
  margin: 0;
  padding: 10px;
  border-radius: var(--cw__border-radius);
  background-color: var(--cw__background-color);
  color: var(--cw__inactive-color);
  cursor: pointer;
  text-align: center;
  font-size: 14px;
  font-weight: 600;
  transition: var(--cw__transition);
  .cw__select-button {
    position: absolute;
    inset-block-start: 0;
    inset-inline-start: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
  }
  .cw__icon {
    display: flex;
    svg {
      height: 1em;
      vertical-align: -0.12em;
    }
  }
  .cw__icon + span {
    margin-left: 0.25rem;
  }
  .cw__select-button-input {
    width: 0;
    height: 0;
    opacity: 0;
    pointer-events: none;
  }
  &.cw__select-button-wrapper-checked {
    background-color: var(--cw__secondary-color);
    color: #ffffff;
  }
`,$i=ir.div`
  padding: 6px;
  border-radius: var(--cw__border-radius);
  background-color: var(--cw__background-color);
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 0.5rem;
  &.sm {
    padding: 4px;
  }
  > * {
    flex: 1;
    gap: 6px;
  }
  .cw__select-button {
    width: 100%;
    &:hover{
      background-color: #ffffff;
    }
    &.cw__select-button-checked {
      background-color: #ffffff;
      color: var(--cw__secondary-color);
      box-shadow: var(--cw__box-shadow);
    }
  }
  &.cw__separate {
    padding: 0;
    background: none;
    border-radius: 0;
    gap: 15px;
    .cw__select-button {
      border: 1px solid var(--cw__border-color);
      background: none;
      &.cw__select-button-checked {
        border-color: var(--cw__secondary-color);
        box-shadow: none;
      }
    }
  }
`,Ui=ir.ul`
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-wrap: wrap;
    flex-direction: column;
    gap: 8px;
    column-gap: 12px;

    input {
        margin: 0;
    }

    label {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 4px;
        font-size: 14px;
    }

    ${e=>e.inline&&"\n    flex-direction: row;\n  "}
`,qi={sm:"14px",md:"16px",lg:"18px",xl:"20px"},Yi=({value:e,label:t,checked:n,icon:o,onChange:i,title:a,size:l,...c})=>{const{style:s,...d}={...c};return(0,r.createElement)(mi,{title:a},(0,r.createElement)(Fi,{tabIndex:0,className:"cw__select-button"+(n?" cw__select-button-checked":""),onKeyDown:t=>{"keydown"===t.type&&"Enter"===t.key&&i(e)},style:{fontSize:qi[l],...s}},(0,r.createElement)("span",{className:"cw__select-button"},(0,r.createElement)("input",{tabIndex:-1,className:"cw__select-button-input",type:"checkbox",value:e,checked:n,onChange:i,...d})),o&&(0,r.createElement)("span",{className:"cw__icon"},o),t&&(0,r.createElement)("span",null,t)))},Xi=({options:e,className:t,onChange:n,value:o,separate:i,isMultiple:a,size:l,type:c,inline:s,...d})=>{const u=e=>()=>{n(a?o.includes(e)?o.filter((t=>t!=e)):[...o,e]:e)};return"radio"==c?(0,r.createElement)(Ui,{inline:s},e?.map((({value:e,label:t,id:i,...a},l)=>(0,r.createElement)("li",{key:l},(0,r.createElement)("label",{htmlFor:i},(0,r.createElement)("input",{type:"radio",checked:o===e,id:i,...a,onChange:()=>n(e)}),t))))):(0,r.createElement)($i,{className:`cw__select-button-group ${t||""} ${i?"cw__separate":""} ${l||""}`},e.map((({value:e,...t},n)=>{const i=a?o.includes(e):o===e,{...c}={...d,...t};return(0,r.createElement)(Yi,{key:n,size:l,value:e,checked:i,onChange:u(e),...c})})))},Ki=e=>Si(Xi)({...e,direction:!1}),Gi=window.lodash,Ji=e=>{var t;return(0,Gi.isString)(e)?null!==(t=e?.match(/(\px|em|pt|vw|vh|rem|%|cm|mm|Q|in|pc)/g))&&void 0!==t?t:[]:e},Qi=new RegExp(/([-+]?([0-9]*\.[0-9]+|[0-9]+))(px|em|pt|vw|vh|rem|%|cm|mm|Q|in|pc)?/),ea=ir.div`
    display: flex;

    > .components-base-control {
        flex: 1;
        margin-bottom: 0;

        .components-base-control__field {
            margin-bottom: 0;

            .components-input-control__input {
                border: none;
                background-color: var(--cw__background-color);
                padding-left: 5px;
                padding-right: 5px;
                text-align: center;
                padding-top: 0;
                padding-bottom: 0;
                min-height: 40px;
                -moz-appearance: textfield;
                &::-webkit-outer-spin-button,
                &::-webkit-inner-spin-button {
                    -webkit-appearance: none;
                }
            }
        }
    }

    &.cw__has-unit {
        .components-input-control__container {
            max-width: 40px;
        }

        .components-input-control__input {
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }
    }

    .cw__unit-picker-wrapper {
        position: relative;

        &::before {
            content: "";
            width: 0;
            height: 14px;
            border-left: 1px solid var(--cw__inactive-color);
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
        }

        button {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            color: var(--cw__inactive-color);
        }
    }
`,ta=[{unit:"px",min:0,max:1e3},{unit:"em",min:0,max:20},{unit:"rem",min:0,max:20},{unit:"%",min:0,max:100},{unit:"pt",min:0,max:100}],na=({units:e,value:t,min:n,max:o,onChange:i,className:a,...c})=>{var s;const[,d="",,u=""]=null!==(s=t?.match(Qi))&&void 0!==s?s:[];let p=ta.find((e=>e.unit==t))?"":d,f=ta.find((e=>e.unit==t))?t:u;e=(0,Gi.isBoolean)(e)&&(e?ta:e)||e,n=!(0,Gi.isBoolean)(e)&&e?.find((e=>e.unit===f))?.min||n||0,o=!(0,Gi.isBoolean)(e)&&e?.find((e=>e.unit===f))?.max||o||1e3,e=!(0,Gi.isBoolean)(e)&&e?.map((e=>e?.unit))||e;const h=(e,t)=>{e>=o?e=o:e<=n&&(e=n),i(e+t)};return(0,r.createElement)(ea,{className:f||e?"cw__has-unit":""},(0,r.createElement)(l.RangeControl,{value:Number(p)||"",onChange:e=>h(e,f),min:n,max:o,...c}),(f||e)&&(0,r.createElement)(xi,{units:e,value:f,onChange:e=>h(p,e)}))},ra=e=>Si(na)(e),oa=ir.div`
  display: flex;
  align-items: center;
  gap: 8px;
  [aria-expanded] {
    display: flex;
  }
  .cw__color-picker-color-block {
    display: inline-block;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    &:hover, &:focus {
      outline: 1px solid #dfe1eb;
      outline-offset: 2px;
      outline-color: var(--cw__secondary-color);
    }
  }

    ${e=>e.color?`\n  .cw__color-picker-color-block{\n      border: 1px solid #efefef;\n      background-color: ${e.color}\n    }\n    `:"\n    .cw__color-picker-color-block{\n      background: #fff linear-gradient(-45deg,transparent 48%,#ddd 0,#ddd 52%,transparent 0);\n      box-shadow: inset 0 0 0 1px #dddddd;\n    }"}
  .cw__color-picker-popover {
    position: absolute;
    z-index: 11;
  }
  &:focus {
    .cw__color-picker-color-block {
      outline: 1px solid #dfe1eb;
      outline-offset: 2px;
    }
  }
`,ia=ir.div`
  max-width: 24px;
  background-color: #e5e5f7;
  opacity: 1;
  background-image:  repeating-linear-gradient(45deg, #c1c1c1 25%, transparent 25%, transparent 75%, #c1c1c1 75%, #c1c1c1), repeating-linear-gradient(45deg, #c1c1c1 25%, #e5e5f7 25%, #e5e5f7 75%, #c1c1c1 75%, #c1c1c1);
  background-position: 0 0, 6px 6px;
  background-size: 12px 12px;
  border-radius: 50%;
`,aa=({color:e,title:t,children:n,interactive:o,placement:i="left",enableReset:a=!1,onChange:l})=>(0,r.createElement)(oa,{className:"cw__color-picker-trigger",color:e},(0,r.createElement)(ia,null,(0,r.createElement)(vi,{content:n,interactive:o,placement:i},(0,r.createElement)(mi,{title:t},(0,r.createElement)("span",{tabIndex:0,className:"cw__color-picker-color-block"},(0,r.createElement)("span",{className:"cw__color-picker-color-block-inner"}))))),a&&e&&(0,r.createElement)("span",{role:"button",className:"cw__color-picker-reset-button",onClick:()=>l("")},"Reset")),la=ir.header`
  padding: 5px;
  border: 1px solid var(--cw__border-color);
  border-radius: var(--cw__border-radius);
  margin: 0 -4px 13px;
  .components-circular-option-picker__swatches{
    gap: 3px;
    .components-circular-option-picker__option-wrapper, .components-button{
      width: 26px;
      height: 26px;
    }
    .components-circular-option-picker__option-wrapper{
      &:hover{
        transform: scale(1.1);
      }
    }
  }
`,ca=({colorPalette:e,value:t,title:n,interactive:o,onChange:i,placement:a,enableReset:c,...s})=>{let d=t;if(t?.includes("var(")){const e=t.replace(/var[()]/g,"").replace(/[)]/g,"");d=getComputedStyle(document.body).getPropertyValue(e)}return(0,r.createElement)(aa,{onChange:i,color:t,title:n,interactive:o,placement:a,enableReset:c},e&&(0,r.createElement)(la,null,(0,r.createElement)(l.ColorPalette,{colors:e,value:d,clearable:!1,disableCustomColors:!0,onChange:t=>{const n=e.find((e=>e.color===t))?.slug;i(`var(${"--wp--preset--color--"+n})`)},...s})),(0,r.createElement)(l.ColorPicker,{color:d,enableAlpha:!0,defaultValue:"#000",onChange:i,...s}))},sa=e=>Si(ca)(e),da=ir.div`
  display: flex;
  flex-wrap: wrap;
  gap: 4px;
`,ua=({colors:e=[],value:t,onChange:n,...o})=>(0,r.createElement)(da,null,e.map((({title:e,name:i,colorPalette:a},l)=>(0,r.createElement)(sa,{key:l,value:t[i],colorPalette:a,onChange:e=>n({...t,[i]:e}),...o,title:e})))),pa=e=>Si(ua)(e),fa=(ir.div`
  padding: 10px;
  border: 1px solid var(--cw__border-color);
  border-radius: var(--cw__border-radius);
  display: flex;
  align-items: center;
  padding-right: 24px;
  position: relative;
  cursor: pointer;
  .cw__color-palette-swatches-inner {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 4px;
    .cw__control-item {
      margin: 0 !important;
    }
  }
  .cw__color-palette-swatch,
  .cw__color-picker-trigger .cw__color-picker-color-block {
    width: 25px;
    height: 25px;
    border: 1px solid var(--cw__border-color);
    border-radius: 50%;
  }
  .cw__dropdown-button-wrapper {
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
  }
  .dropdown-button {
    padding: 0;
    background: none;
    border: none;
    width: 12px;
    height: 12px;
    cursor: pointer;
    color: #a3b1bf;
  }
  &.selected {
    &::after {
      content: "";
      width: 14px;
      height: 14px;
      background-image: url("data:image/svg+xml,%3Csvg width='14' height='15' viewBox='0 0 14 15' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='7' cy='7.5' r='6.74' fill='%23216BDB' stroke='%23216BDB' stroke-width='0.52'/%3E%3Cg clip-path='url(%23clip0_336_1961)'%3E%3Cpath d='M5.40589 11.2598L2.44189 8.29584L3.18289 7.55484L5.40589 9.77784L10.1769 5.00684L10.9179 5.74784L5.40589 11.2598Z' fill='white'/%3E%3C/g%3E%3Cdefs%3E%3CclipPath id='clip0_336_1961'%3E%3Crect width='9.36' height='6.76' fill='white' transform='translate(2 4.5)'/%3E%3C/clipPath%3E%3C/defs%3E%3C/svg%3E%0A");
      background-size: 14px 14px;
      background-repeat: no-repeat;
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
    }
  }
  &.has-dropdown {
    cursor: default;
  }
`,ir.div`
  .cw__palette-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    margin: 0 0 8px;
  }
  .cw__color-palette-option {
    &:not(:last-child) {
      margin-bottom: 13px;
    }
    .cw__color-palette-swatches-inner {
      gap: 2px;
    }
  }
`,ir.label`
  text-align: center;
  flex: 1;
  input {
    text-align: center;
    padding-left: 0.25rem;
    padding-right: 0.25rem;
    -moz-appearance: textfield;
    &::-webkit-outer-spin-button,
    &::-webkit-inner-spin-button {
      -webkit-appearance: none;
    }
    &:read-only{
      background-color: #efefef;
      color: #999999;
      pointer-events: none;
    }
  }
  .label {
    display: inline-block;
    font-size: 10px;
    margin-top: 0.25rem;
    text-transform: uppercase;
  }
`),ha=ir.div`
  display: flex;
  width: 100%;
  align-items: flex-start;
  gap: 0.5rem;
  .cw__spacing-button-wrapper {
    background-color: var(--cw__background-color);
    border-radius: var(--cw__border-radius);
    display: flex;
    height: 45px;
    flex: 1;
    button {
      background: none;
      border: none;
      cursor: pointer;
      color: var(--cw__inactive-color);
      padding: 0.5rem;
      font-size: 13px;
      border-radius: var(--cw__border-radius);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      &:hover,
      &.active {
        color: var(--cw__secondary-color);
      }
      &:focus {
        outline: 1px dotted;
      }
      &.cw__spacing-button-link-button {
        flex: 1;
      }
    }
    .cw__unit-picker-wrapper {
      position: relative;
      &::before {
        content: "";
        width: 0;
        height: 14px;
        border-left: 1px solid var(--cw__inactive-color);
        position: absolute;
        top: 50%;
        left: 0;
        transform: translateY(-50%);
      }
    }
  }
`,ga=[{name:"top",label:"Top"},{name:"right",label:"Right"},{name:"bottom",label:"Bottom"},{name:"left",label:"Left"}],ma=(e,t)=>{let n={};return ga.map((({name:r})=>{const o="auto"!==t[r];n={...n,[r]:o?e:t[r]}})),n},va=[{unit:"px",min:0,max:1e3},{unit:"em",min:0,max:20},{unit:"rem",min:0,max:20},{unit:"%",min:0,max:100},{unit:"pt",min:0,max:100}],ba=({label:e,value:t,...n})=>{const o="auto"!=t;return(0,r.createElement)(fa,{className:"cw__spacing-input-wrapper"},(0,r.createElement)("span",{className:"cw__spacing-input"},(0,r.createElement)("input",{readOnly:!o,type:o?"number":"text",value:t,...n})),e&&(0,r.createElement)("span",{className:"label"},e))},wa=({onChange:e,value:t,units:n,...o})=>{const[i,a]=(0,hi.useState)(!0);n=(0,Gi.isBoolean)(n)?va:n;const l=Object.values(t).find((e=>""!==e&&"auto"!==e))||"",[c="px"]=Ji(l),s=n?.find((e=>e.unit===c)),d=s?.max||1e3,u=s?.min||0,p=Object.values(t).find((e=>""!==e&&"auto"!==e))||0+c,f=n=>{const r=n.target.value,o=n.target.name;if("number"===n.target.type){const n=""!==r?r>=d?d:r<=u?u:r:r;e(i?{...t,...ma(n+c,t)}:{...t,[o]:n+c})}};return n=n?.map((e=>e.unit)),(0,r.createElement)(ha,{className:"cw__spacing-group"},ga.map((({name:e,label:n})=>{let i=t[e];if(Ji(t[e]).length>0){var a;const[,n]=(null!==(a=t[e])&&void 0!==a?a:"")?.match(Qi)||[];i=n}return(0,r.createElement)(ba,{key:e,label:n,name:e,onChange:f,value:i,...o})})),(0,r.createElement)("div",{className:"cw__spacing-button-wrapper"},(0,r.createElement)("button",{type:"button",className:"cw__spacing-button-link-button"+(i?" active":""),onClick:()=>(a(!i),void e({...t,...ma(p,t)}))},yi.link),(0,r.createElement)(xi,{units:n,value:c,onChange:n=>(n=>{let r={};Object.entries(t).map((([e,t="0px"])=>{var o;const[,i]=null!==(o=t?.match(/(\d+)/))&&void 0!==o?o:[];""!==t&&(r={...r,[e]:i+n})})),e({...t,...r})})(n)})))},xa=e=>Si(wa)(e),ya=(ir.div`
    .components-button {
        min-height: 43px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        font-size: 14px;
        line-height: 18.6px;
        padding: 10px 16px;
        border: none;
        background-color: var(--cw__background-color);
        color: var(--cw__secondary-color);
        gap: 8px;
        cursor: pointer;
        border-radius: var(--cw__border-radius);
        transition: var(--cw__transition);
        background-image: none;
        svg {
            font-size: 24px;
            width: 1em;
            height: 1em;
            fill: none;
        }
        &:hover {
            background-color: var(--cw__secondary-color);
            color: #ffffff;
        }
    }
    .cw__media-preview {
        text-align: center;
        border-radius: var(--cw__border-radius);
        border: 2px dashed var(--cw__secondary-color);
        position: relative;
        padding: 16px;
        img {
            max-width: 100%;
            border-radius: var(--cw__border-radius);
            margin: 0 auto;
            max-height: 142px;
        }
        .cw__media-remove-button {
            display: flex;
            border-radius: 50%;
            color: #ff3e60;
            background: #ffffff;
            border: none;
            padding: 0;
            cursor: pointer;
            position: absolute;
            right: 0;
            top: 0;
            transform: translate(50%, -50%);
            z-index: 1;
            svg {
                width: 16px;
                height: 16px;
            }
            &:hover {
                outline: 1px solid #ff3e60;
                outline-offset: 2px;
            }
        }
        .cw__media-replace-button {
            border-radius: var(--cw__border-radius);
            color: var(--cw__secondary-color);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            border: none;
            cursor: pointer;
            visibility: hidden;
            opacity: 0;
            transition: var(--cw__transition);
            display: flex;
            justify-content: center;
            align-items: center;
            svg {
                width: 14px;
                height: 15px;
            }
        }
        &:hover {
            .cw__media-replace-button {
                visibility: visible;
                opacity: 1;
            }
        }
    }
`,n(6154),ir.div`
    display: inline-flex;
    background-color: var(--cw__background-color);
    border-radius: var(--cw__border-radius);
    input[type=number]{
        padding: 4px !important;
        border: none !important;
        background: none !important;
        text-align: center;
        width: 40px !important;
        -moz-appearance: textfield;
        -moz-appearance: textfield;
        &::-webkit-outer-spin-button, &::-webkit-inner-spin-button{
            -webkit-appearance: none;
        }
    }
    button{
        border: none;
        background: none;
        padding: 10px;
        cursor: pointer;
        display: flex;
        align-items: center;
        &:hover{
            color: var(--cw__secondary-color);
        }
        &:disabled{
            cursor: not-allowed;
            pointer-event: none;
            color: var(--cw__inactive-color);
            opacity: .5;
        }
    }
`),Ca=({value:e,min:t,max:n,onChange:o,step:i,...a})=>{const l=i||1;return(0,r.createElement)(ya,{className:"cw__input-number-wrapper"},(0,r.createElement)("button",{disabled:t>=e,type:"button",onClick:()=>{o(+e-l)}},yi.minus),(0,r.createElement)("input",{type:"number",value:+e,onChange:e=>o(e.target.value),min:t,max:n,...a}),(0,r.createElement)("button",{disabled:n<=e,type:"button",onClick:()=>{o(+e+l)}},yi.plus))},_a=e=>Si(Ca)({...e,direction:"horizontal"}),ka=ir.div`
    display: inline-flex;
    align-items: center;
    gap: 8px;
`,Ea=({value:e,onChange:t})=>{const{width:n,style:o}=e;return(0,r.createElement)(r.Fragment,null,(0,r.createElement)(_a,{label:"Border Width",direction:"horizontal",value:n,onChange:n=>t({...e,width:n}),min:0,isChildren:!0}),(0,r.createElement)(Ni,{label:"Border Style",options:[{value:"none",label:"None",icon:yi.none},{value:"solid",label:"Solid",icon:yi.minus},{value:"dashed",label:"Dash",icon:yi.dashed},{value:"double",label:"Double",icon:yi.menu},{value:"dotted",label:"Dot",icon:yi.ellipsis}],value:o,onChange:n=>t({...e,style:n}),isChildren:!0}))},Oa=({colorPalette:e,changed:t,value:n,onChange:o,...i})=>{const{color:a}=n;return(0,r.createElement)(ka,null,(0,r.createElement)(sa,{colorPalette:e,title:"Border Color",value:a,onChange:e=>o({...n,color:e}),...i}),(0,r.createElement)(vi,{content:(0,r.createElement)(Ea,{value:n,onChange:o,...i})},(0,r.createElement)(_i,{changed:t})))},Ma=e=>Si(Oa)({...e,direction:"horizontal"}),Sa=ir.div`
  .components-range-control__wrapper {
    position: relative;
    &::after {
      content: "";
      width: 100%;
      height: 7px;
      position: absolute;
      left: 0;
      right: 0;
      bottom: -7px;
      background-image: url("data:image/svg+xml,%3Csvg width='6' height='1' viewBox='0 0 6 1' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cg clip-path='url(%23clip0_330_2020)'%3E%3Cpath d='M0.9198 0.9375C0.803768 0.9375 0.692488 0.891406 0.610441 0.809359C0.528394 0.727312 0.4823 0.616032 0.4823 0.5C0.4823 0.383968 0.528394 0.272688 0.610441 0.190641C0.692488 0.108594 0.803768 0.0625 0.9198 0.0625H5.2948C5.41083 0.0625 5.52211 0.108594 5.60416 0.190641C5.68621 0.272688 5.7323 0.383968 5.7323 0.5C5.7323 0.616032 5.68621 0.727312 5.60416 0.809359C5.52211 0.891406 5.41083 0.9375 5.2948 0.9375H0.9198Z' fill='%2342474B'/%3E%3C/g%3E%3Cdefs%3E%3CclipPath id='clip0_330_2020'%3E%3Crect width='5.25' height='0.875' fill='white' transform='translate(0.4823 0.0625)'/%3E%3C/clipPath%3E%3C/defs%3E%3C/svg%3E%0A"),
        url("data:image/svg+xml,%3Csvg width='2' height='7' viewBox='0 0 2 7' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cg clip-path='url(%23clip0_330_2022)'%3E%3Cpath d='M0.6073 6.5625V0.4375V6.5625Z' fill='%23D9D9D9'/%3E%3Cpath d='M0.6073 6.5625V0.4375' stroke='%2342474B' stroke-width='0.875' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/g%3E%3Cdefs%3E%3CclipPath id='clip0_330_2022'%3E%3Crect width='0.875' height='7' fill='white' transform='translate(0.1698)'/%3E%3C/clipPath%3E%3C/defs%3E%3C/svg%3E%0A"),
        url("data:image/svg+xml,%3Csvg width='8' height='7' viewBox='0 0 8 7' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cg clip-path='url(%23clip0_330_2024)'%3E%3Cpath d='M3.98232 0.743652V6.25615M1.22607 3.4999H6.73857' stroke='%2342474B' stroke-width='0.875' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/g%3E%3Cdefs%3E%3CclipPath id='clip0_330_2024'%3E%3Crect width='7' height='7' fill='white' transform='translate(0.4823)'/%3E%3C/clipPath%3E%3C/defs%3E%3C/svg%3E%0A");
      background-position:
        left center,
        center center,
        right center;
      background-repeat: no-repeat;
    }
  }
  .cw__control-item.cw__box-shadow-blur{
		.components-range-control__wrapper{
			&::after{
				background-image: url("data:image/svg+xml,%3Csvg width='2' height='7' viewBox='0 0 2 7' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cg clipPath='url(%23clip0_330_2022)'%3E%3Cpath d='M0.6073 6.5625V0.4375V6.5625Z' fill='%23D9D9D9'/%3E%3Cpath d='M0.6073 6.5625V0.4375' stroke='%2342474B' stroke-width='0.875' strokeLinecap='round' strokeLinejoin='round'/%3E%3C/g%3E%3Cdefs%3E%3CclipPath id='clip0_330_2022'%3E%3Crect width='0.875' height='7' fill='white' transform='translate(0.1698)'/%3E%3C/clipPath%3E%3C/defs%3E%3C/svg%3E%0A"),
				url("data:image/svg+xml,%3Csvg width='8' height='7' viewBox='0 0 8 7' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cg clipPath='url(%23clip0_330_2024)'%3E%3Cpath d='M3.98232 0.743652V6.25615M1.22607 3.4999H6.73857' stroke='%2342474B' stroke-width='0.875' strokeLinecap='round' strokeLinejoin='round'/%3E%3C/g%3E%3Cdefs%3E%3CclipPath id='clip0_330_2024'%3E%3Crect width='7' height='7' fill='white' transform='translate(0.4823)'/%3E%3C/clipPath%3E%3C/defs%3E%3C/svg%3E%0A");
				background-position: left center, right center;
			}
		}
	}
`,Aa=ir.div`
  display: inline-flex;
  align-items: center;
  gap: 8px;
`,Da=({value:e,onChange:t})=>{const{enable:n,xOffset:o,yOffset:i,blur:a,spread:l,position:c}=e;return(0,r.createElement)(Sa,null,(0,r.createElement)(Zi,{label:"Enable",direction:"horizontal",value:n,onChange:n=>t({...e,enable:n}),isChildren:!0}),(0,r.createElement)(ra,{label:"Horizontal",value:o||"0px",onChange:n=>t({...e,xOffset:n}),min:-100,max:100,isChildren:!0}),(0,r.createElement)(ra,{label:"Vertical",value:i||"0px",onChange:n=>t({...e,yOffset:n}),min:-100,max:100,isChildren:!0}),(0,r.createElement)(ra,{label:"Blur",value:a||"0px",onChange:n=>t({...e,blur:n}),min:0,max:100,className:"cw__box-shadow-blur",isChildren:!0}),(0,r.createElement)(ra,{label:"Spread",value:l||"0px",onChange:n=>t({...e,spread:n}),min:-100,max:100,isChildren:!0}),(0,r.createElement)(Ki,{label:"Position",options:[{value:"outline",label:"Outline"},{value:"inset",label:"Inset"}],value:c,onChange:n=>t({...e,position:n}),isChildren:!0}))},La=({colorPalette:e,value:t,onChange:n,changed:o,...i})=>{const{color:a}=t;return(0,r.createElement)(Aa,null,t?.enable&&(0,r.createElement)(sa,{colorPalette:e,value:a,onChange:e=>n({...t,color:e}),isChildren:!0}),(0,r.createElement)(vi,{content:(0,r.createElement)(Da,{value:t,onChange:n})},(0,r.createElement)(_i,{changed:o})))},Ra=e=>Si(La)({...e,direction:"horizontal"}),Ta=({value:e,onChange:t,responsiveState:n,setResponsiveState:o,fontFamilies:i,fontWeights:a})=>{const{family:l="default",size:c={desktop:"px",tablet:"px",mobile:"px"},"line-height":s={desktop:"",tablet:"",mobile:""},"letter-spacing":d={desktop:"0px",tablet:"0px",mobile:"0px"},weight:u="400",style:p="inherit",transform:f="inherit",decoration:h="inherit"}=e,g=(n,r)=>{t({...e,[n]:r})};return(0,r.createElement)(r.Fragment,null,(0,r.createElement)(Ni,{label:"Font Family",direction:"horizontal",value:l||"default",onChange:e=>g("family",e),options:i||[{label:"Default",value:"default"}],placeholder:"Select Font Family",variant:"solid",isChildren:!0,isSearchable:!0,style:{width:"136px"},disabled:!0}),(0,r.createElement)(ra,{label:"Size",value:c,onChange:e=>g("size",e),isChildren:!0,responsive:!0,units:[{unit:"px",min:0,max:100},{unit:"em",min:0,max:20},{unit:"rem",min:0,max:20}]}),(0,r.createElement)(ra,{label:"Line Height",value:s,onChange:e=>g("line-height",e),isChildren:!0,responsive:!0,step:.1,min:0,max:10,units:[{unit:"px",min:0,max:100},{unit:"em",min:0,max:20}]}),(0,r.createElement)(ra,{label:"Letter Spacing",value:d,onChange:e=>g("letter-spacing",e),isChildren:!0,responsive:!0,units:[{unit:"px",min:0,max:100},{unit:"em",min:0,max:20}],step:.1}),a&&(0,r.createElement)(Ni,{label:"Font Weight",direction:"horizontal",value:u,options:a||[],onChange:e=>g("weight",e),variant:"solid",isChildren:!0,style:{width:"136px"}}),(0,r.createElement)(Ni,{label:"Style",direction:"horizontal",options:[{value:"inherit",label:"Inherit"},{value:"italic",label:"Italic"},{value:"oblique",label:"Oblique"},{value:"normal",label:"Normal"}],value:p,onChange:e=>g("style",e),variant:"solid",isChildren:!0,style:{width:"136px"}}),(0,r.createElement)(Ni,{label:"Transform",direction:"horizontal",options:[{value:"inherit",label:"Inherit"},{value:"uppercase",label:"Uppercase"},{value:"lowercase",label:"Lowercase"},{value:"capitalize",label:"Capitalize"},{value:"none",label:"None"}],value:f,onChange:e=>g("transform",e),variant:"solid",isChildren:!0,style:{width:"136px"}}),(0,r.createElement)(Ni,{label:"Decoration",direction:"horizontal",options:[{value:"inherit",label:"Inherit"},{value:"underline",label:"Underline"},{value:"overline",label:"Overline"},{value:"line-through",label:"Line Through"},{value:"none",label:"None"}],value:h,onChange:e=>g("decoration",e),variant:"solid",isChildren:!0,style:{width:"136px"}}))},Na=({changed:e,...t})=>(0,r.createElement)(vi,{content:(0,r.createElement)(Ta,{...t}),placement:"left"},(0,r.createElement)(_i,{changed:e})),Pa=e=>Si(Na)({...e,direction:"horizontal"}),Ba=(ir.div`
  .cw__control-item {
    &.cw__divider-top {
      margin-top: 12px;
      padding-top: 12px;
    }
  }
`,ir.div`
    display: flex;
    align-items: center;
    gap: 8px;
    .cw__control-item{
        margin: 0 !important;
        padding: 0 !important;
    }
`,ir.div`
    padding: 10.5px 10px;
    border: 1px solid var(--cw__border-color);
    border-radius: var(--cw__border-radius);
    color: #2B3034;
    font-size: 14px;
    &:focus{
        border-color: var(--cw__secondary-color);
    }
    .cw__ratio-input{
        span{
            &:not(:last-of-type){
                border-right: 1px solid var(--cw__border-color);
                padding-right: 6px;
                margin-right: 6px;
            }
        }
    }
`,ir.div`
    display: flex;
    align-items: center;
    gap: 8px;
    .cw__control-item{
        margin: 0 !important;
        padding: 0 !important;
    }
`,ir.div`
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  padding: 16px 0;
`,ir.div`
    width: 100%;
    position: relative;
    .wc__sort-button{
        padding: 0;
        background-color: transparent;
        font-size: 0;
        border: none;
        width: 12px;
        height: 20px;
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        cursor: move;
        color: #42474B;
        opacity: .5;
        svg{
            vertical-align: top;
            width: 100%;
            height: 100%;
        }
        &:hover{
            color: var(--cw__secondary-color);
            opacity: 1;
        }
    }
    > .cw__control-item{
        border: 1px solid var(--cw__border-color);
        border-radius: var(--cw__border-radius);
        padding: 12px;
        padding-left: 34px;
        background-color: #ffffff;
    }
`,ir.div`
    display: inline-flex;
    gap: 8px;
`,ir.div`
    border: 2px dashed var(--cw__secondary-color);
    border-radius: var(--cw__border-radius);
    background-color: #F6F6F6;
    width: 100%;
    min-height: 100px;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    transition: all .3s ease;
    &:hover{
        background-color: var(--cw__background-color);
    }
    >button{
        padding: 0;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #ffffff;
        font-size: 24px;
        border: none;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: all .3s ease;
        svg{
            width: 1em;
            height: 1em;
        }
        &:hover{
            background-color: var(--cw__secondary-color);
            color: #ffffff;
        }
    }
    input[type="file"]{
        visibility: hidden;
        position: absolute;
        top: -9999999px;
        width: 0;
        height: 0;
    }
`,ir.button`
    border: 1px solid #bb2124;
    color: #bb2124;
    padding: 2px 16px;
    text-align: center;
    border-radius: 4px;
    font-size: 14px;
    margin-top: 6px;
    cursor: pointer;
    width: 100%;
    &:hover{
        background-color: #bb2124;
        color: #ffffff;
    }
`,ir.div`
    > div, canvas{
        max-width: 100%;
    }
    #gradient-bar{
        div{
            max-width: 100%;
        }
    }
    #rbgcp-wrapper{
        > div{
            gap: 8px;
        }
    }
`,ir.div`
    .components-form-token-field__label{
        visibility: hidden;
        width: 0;
        height: 0;
        overflow: hidden;
        position: absolute;
        top: -99999999px;
        z-index: -1;
    }
    .components-form-token-field__help{
        font-size: 12px;
        margin-bottom: 0;
    }
    .components-form-token-field__input-container{
        border: 1px solid var(--cw__border-color);
        border-radius: var(--cw__border-radius);
        transition: var(--cw__transition);
        min-height: 44px;
        padding: 10px;
        display: flex;
        align-items: center;
        position: relative;
        &.is-active{
            border-color: var(--cw__secondary-color);
        }
        input.components-form-token-field__input{
            all: unset;
            width: 100%;
            min-width: 50px;
            max-width: 100%;
            display: inline-block;
            flex: 1;
            outline: none !important;
        }
        > .components-flex{
            padding: 0;
            gap: 8px;
        }
        .components-form-token-field__suggestions-list{
            position: absolute;
            max-height: 202px;
            border: 1px solid var(--cw__border-color);
            border-radius: var(--cw__border-radius);
            padding: 6px;
            list-style: none;
            margin: 0;
            width: 100%;
            top: 100%;
            margin-top: 10px;
            box-shadow: 0px 4px 6px -2px #10182808, 0px 12px 16px -4px #10182814;
            left: 0;
            background: #ffffff;
            li{
                font-size: 14px;
                color: #2b3034;
                padding: 10.5px 8px;
                cursor: default;
                &:hover{
                    color: var(--cw__secondary-color);
                }
            }
        }
        .components-form-token-field__token{
            display: inline-flex;
            align-items: center;
            color: #2b3034;
            padding: 6px 12px;
            background-color: #e5f0ff;
            border-radius: var(--cw__border-radius);
            gap: 4px;
            .components-form-token-field__remove-token{
                flex: 0 0 24px;
                height: 24px;
                width: 24px;
                border: none;
                padding: 0;
                background: none;
                transition: var(--cw__transition);
                cursor: pointer;
                border-radius: var(--cw__border-radius);
                svg{
                    fill: currentColor;
                }
                &:hover{
                    background-color: #ff0e0e;
                    color: #ffffff;
                }
            }
        }
    }
`),ja=e=>(0,r.createElement)(Ba,null,(0,r.createElement)(l.FormTokenField,{...e})),Va=e=>Si(ja)(e),Ha=(ir.div`
    margin-bottom: 16px;
    label.cw__group-label{
        display: block;
        margin: 0 0 16px;
        font-size: 14px;
        font-weight: 600;
        color: #2b3034;
    }
`,ir.div`
    padding: 12px;
    border: 1px solid var(--cw__border-color);
    border-radius: var(--cw__border-radius);
    > .cw__control-description{
        margin: 12px 0 0 !important;
        font-size: 13px;
        font-weight: 500;
        line-height: 1.5;
        color: #2b3034;
        padding: 4px 8px;
        border-radius: var(--cw__border-radius);
        background-color: var(--cw__background-color);
    }
    > .cw__control-item{
        padding-top: 8px !important;
        padding-bottom: 8px !important;
        &:not(.horizontal){
            > header{
                margin-bottom: 8px;
            }
        }
        > .cw__control-description{
            margin: 8px 0;
        }
        &:first-of-type{
            padding-top: 0 !important;
            border-top: 0 !important;
        }
        &:last-of-type{
            padding-bottom: 0 !important;
            border-bottom: 0 !important;
        }
    }
`,[{label:"Thin",value:"100"},{label:"Extra Light",value:"200"},{label:"Light",value:"300"},{label:"Normal",value:"400"},{label:"Medium",value:"500"},{label:"Semi Bold",value:"600"},{label:"Bold",value:"700"},{label:"Extra Bold",value:"800"},{label:"Black",value:"900"}]);function Ia({header:e,title:t,...n}){const o=(0,c.useSelect)((e=>e("core/block-editor").getSettings().colors)),{initialOpen:i,controls:a,children:s,attributes:d,setAttributes:u}=n;return(0,r.createElement)(l.Panel,{header:e||null},(0,r.createElement)(l.PanelBody,{title:t,initialOpen:i},a.map((([e,t],n)=>{const{type:i,default:l,label:c,control:{labels:s,type:p,style:f,settings:h,controlAttribute:g,...m}}=t;switch(p){case"background":case"color":const t=Object.entries(s||{[e]:c}).map((([e,t])=>({name:e,title:(0,Gi.isString)(t)?t:t.label,colorPalette:o}))),p=(0,Gi.isString)(d[e])?{[e]:d[e]}:d[e];return(0,r.createElement)(pa,{key:n,label:c,direction:g?.direction||"horizontal",colors:t,defaultValue:l,onChange:t=>{u("object"===i?{[e]:t}:t)},value:p,divider:e!==a[0][0]?"top":""});case"border":return(0,r.createElement)(Ma,{key:n,label:c,defaultValue:l,colorPalette:o,onChange:t=>{u({[e]:t})},value:d[e],divider:e!==a[0][0]?"top":"",...m});case"box-shadow":return(0,r.createElement)(Ra,{key:n,label:c,defaultValue:l,onChange:t=>{u({[e]:t})},value:d[e],divider:e!==a[0][0]?"top":"",...m});case"border-radius":case"spacing":return(0,r.createElement)(xa,{key:n,label:c,onChange:t=>{u({[e]:t})},value:d[e],divider:e!==a[0][0]?"top":"",...g});case"typography":return(0,r.createElement)(Pa,{key:n,label:c,value:d[e],onChange:t=>u({[e]:t}),divider:e!==a[0][0]?"top":"",fontWeights:Ha,...g});case"alignment":return(0,r.createElement)(Ki,{key:n,label:c,value:d[e],onChange:t=>u({[e]:t}),options:[{value:g?.flex?"flex-start":"left",icon:yi.leftAlignment,title:"Left"},{value:"center",icon:yi.centerAlignment,title:"Center"},{value:g?.flex?"flex-end":"right",icon:yi.rightAlignment,title:"Right"}],divider:e!==a[0][0]?"top":"",...g});case"range":return(0,r.createElement)(ra,{key:n,label:c,onChange:t=>u({[e]:t}),value:d[e],divider:e!==a[0][0]?"top":"",...g});case"select":return(0,r.createElement)(Ni,{key:n,label:c,onChange:t=>u({[e]:t}),value:d[e],divider:e!==a[0][0]?"top":"",...g});case"text":return(0,r.createElement)(Vi,{key:n,label:c,onChange:t=>u({[e]:t}),value:d[e],divider:e!==a[0][0]?"top":"",...g});case"textarea":return(0,r.createElement)(Ii,{key:n,label:c,onChange:t=>u({[e]:t}),value:d[e],divider:e!==a[0][0]?"top":"",...g});case"switch":return(0,r.createElement)(Zi,{key:n,label:c,onChange:t=>u({[e]:t}),value:d[e],divider:e!==a[0][0]?"top":"",...g});case"select-button":return(0,r.createElement)(Ki,{key:n,label:c,value:d[e],onChange:t=>u({[e]:t}),divider:e!==a[0][0]?"top":"",...g});case"input-number":return(0,r.createElement)(_a,{key:n,label:c,value:d[e],onChange:t=>u({[e]:t}),divider:e!==a[0][0]?"top":"",...g});case"token-field":return(0,r.createElement)(Va,{key:n,label:c,value:d[e],onChange:t=>u({[e]:t}),divider:e!==a[0][0]?"top":"",...g})}}))),s)}const{Fill:za,Slot:Wa}=(0,l.createSlotFill)("WPTravelEngineTripBlocksInspectorControls"),Za={color:{title:(0,i.__)("Colors"),icon:"admin-appearance"},border:{title:(0,i.__)("Border"),icon:"admin-appearance"},dimensions:{title:(0,i.__)("Dimensions"),icon:"admin-appearance"}},Fa=({children:e,controls:t,...n})=>{const{attributes:o,setAttributes:i}=n,{name:l}=(0,a.useBlockEditContext)(),{getBlockType:s}=(0,c.useSelect)((e=>e("core/blocks")),[]),{attributes:d,supports:{wptravelenginetripblocks:u}}=s(l);let p={default:{},styles:{}};Object.entries(d).forEach((([e,t])=>{const n=t.panel||t.control?.type;n&&(n.match(/^(-[\w-]+-)$/)?p.default={...p.default,[n]:[...p.default?.[n]||[],[e,t]]}:p.styles={...p.styles,[n]:[...p.styles?.[n]||[],[e,t]]})}));const f={...Za,...u?.panels||{}},h=Object.entries(p.default),g=Object.entries(p.styles);return(0,r.createElement)(r.Fragment,null,(0,r.createElement)(a.InspectorControls,{group:"styles"},g.map((([e,t])=>(0,r.createElement)(Ia,{initialOpen:e===g[0][0],title:f[e]?.title||null,key:e,panel:e,controls:t,attributes:o,setAttributes:i})))),(0,r.createElement)(a.InspectorControls,{group:"default"},h.map((([e,t])=>(0,r.createElement)(Ia,{initialOpen:e===h[0][0],title:f[e]?.title||null,key:e,panel:e,controls:t,attributes:o,setAttributes:i})))),e)},$a=e=>{const t=(0,a.useBlockProps)();return(0,r.createElement)("div",{...t},(0,r.createElement)("div",null,(0,r.createElement)("p",null,(0,i.__)("Trip Taxonomy - Server Block"))),(0,r.createElement)(Fa,{group:"styles",...e}))},Ua=JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":3,"name":"wptravelenginepagesblocks/taxonomy","version":"1.0.0","title":"Trip Taxonomy","category":"widgets","description":"Taxonomy Page.","attributes":{"taxonomyType":{"type":"string","label":"Taxonomy Type","default":"destination","control":{"type":"select","controlAttribute":{"options":[{"value":"destination","label":"Destination"},{"value":"activities","label":"Activities "},{"value":"trip_types","label":"Trip Types "}]}},"panel":"-taxonomy-"}},"supports":{"align":false,"wptravelenginetripblocks":{"panels":{"-taxonomy-":{"title":"Taxonomy"}}}},"textdomain":"wptravelengine","render":"file:./block.php"}')},1609:e=>{"use strict";e.exports=window.React},4114:(e,t,n)=>{"use strict";n.r(t),n.d(t,{edit:()=>a,metadata:()=>l});var r=n(1609),o=n(7723),i=n(4715);const a=()=>{const e=(0,i.useBlockProps)();return(0,r.createElement)("div",{...e},(0,r.createElement)("p",null,(0,o.__)("Trip Archive Page - Server Block")))},l=JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":3,"name":"wptravelenginepagesblocks/archive-trip","version":"1.0.0","title":"Trip Archive","category":"widgets","description":"Trip Archive Page.","attributes":{},"supports":{"align":false},"textdomain":"wptravelengine","render":"file:./block.php"}')},4715:e=>{"use strict";e.exports=window.wp.blockEditor},6154:e=>{"use strict";e.exports=window.moment},6427:e=>{"use strict";e.exports=window.wp.components},7112:(e,t,n)=>{"use strict";n.r(t),n.d(t,{edit:()=>a,metadata:()=>l});var r=n(1609),o=n(7723),i=n(4715);const a=()=>{const e=(0,i.useBlockProps)();return(0,r.createElement)("div",{...e},(0,r.createElement)("p",null,(0,o.__)("Trip Checkout - Server Block")))},l=JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":3,"name":"wptravelenginepagesblocks/trip-checkout","version":"1.0.0","title":"Trip Checkout","category":"widgets","description":"Trip Checkout Page.","attributes":{},"textdomain":"wptravelengine","render":"file:./block.php"}')},7723:e=>{"use strict";e.exports=window.wp.i18n},9820:(e,t,n)=>{"use strict";n.r(t),n.d(t,{edit:()=>a,metadata:()=>l});var r=n(1609),o=n(7723),i=n(4715);const a=()=>{const e=(0,i.useBlockProps)();return(0,r.createElement)("div",{...e},(0,r.createElement)("p",null,(0,o.__)("Trip Search Result - Server Block")))},l=JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":3,"name":"wptravelenginepagesblocks/trip-search","version":"1.0.0","title":"Trip Search Result","category":"widgets","description":"Trip Search Result Page.","attributes":{},"textdomain":"wptravelengine","render":"file:./block.php"}')},9987:(e,t,n)=>{var r={"./archive-trip/block.js":4114,"./checkout/block.js":7112,"./search/block.js":9820,"./taxonomy/block.js":138};function o(e){var t=i(e);return n(t)}function i(e){if(!n.o(r,e)){var t=new Error("Cannot find module '"+e+"'");throw t.code="MODULE_NOT_FOUND",t}return r[e]}o.keys=function(){return Object.keys(r)},o.resolve=i,e.exports=o,o.id=9987}},t={};function n(r){var o=t[r];if(void 0!==o)return o.exports;var i=t[r]={exports:{}};return e[r](i,i.exports,n),i.exports}n.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return n.d(t,{a:t}),t},n.d=(e,t)=>{for(var r in t)n.o(t,r)&&!n.o(e,r)&&Object.defineProperty(e,r,{enumerable:!0,get:t[r]})},n.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),n.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},(()=>{"use strict";var e=n(7723);const t=window.wp.blocks;var r=n(1609),o=n(6427);const i=(0,r.createElement)(o.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24",width:"24",height:"24"},(0,r.createElement)(o.Path,{d:"M17.3,11.6A3.7,3.7,0,1,0,21,15.29,3.7,3.7,0,0,0,17.3,11.6Zm0,6.28a2.59,2.59,0,1,1,2.59-2.59A2.6,2.6,0,0,1,17.3,17.88Z"}),(0,r.createElement)(o.Path,{d:"M6.7,11.6a3.7,3.7,0,1,0,3.69,3.69A3.7,3.7,0,0,0,6.7,11.6Zm0,6.28a2.59,2.59,0,1,1,2.59-2.59A2.6,2.6,0,0,1,6.7,17.88Z"}),(0,r.createElement)(o.Path,{d:"M12.37,11.38l-1-.8,2-1L15.08,11a.67.67,0,0,0,.43.16l1.63.09a.76.76,0,0,0,.8-.64c.08-.43-.21-.7-.63-.77l-1.67-.25s-1.22-1.68-1.49-2c-.82-.85-1.32-1.34-2.26-.87L9,8.15a1.9,1.9,0,0,0-.9,2.47,2.71,2.71,0,0,0,1.12,1l1.67,1,.19,3.19a.85.85,0,0,0,1.69-.06l0-3.71A.88.88,0,0,0,12.37,11.38Z"}),(0,r.createElement)(o.Path,{d:"M14.86,8.05a1.83,1.83,0,0,0,.61.25h0a1.66,1.66,0,0,0,1.94-2,1.66,1.66,0,0,0-1-1.19,1.64,1.64,0,0,0-1.56.15,1.67,1.67,0,0,0,0,2.76Z"})),a=(0,r.createElement)(o.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24",width:"24",height:"24"},(0,r.createElement)(o.Circle,{fill:"#010002",cx:"13.88",cy:"5.8",r:"1.8"}),(0,r.createElement)(o.Path,{fill:"#010002",d:"M18.37,10.19C16.76,10,15.21,8.28,14.81,8A1.88,1.88,0,0,0,14,7.71a1.54,1.54,0,0,0-.4,0,1.57,1.57,0,0,0-.91.35,3.59,3.59,0,0,0-.31.29,13.72,13.72,0,0,0-2.73,4,.67.67,0,0,0,.14.73L9,14.62l-1.21-.68a.29.29,0,0,0-.41.11L5,18.1a.31.31,0,0,0,.11.41l.92.51a.68.68,0,0,0,.11.82.69.69,0,0,0,1,0,.7.7,0,0,0,.06-.92l1.25-2.19.88-1.56,1.08-1.88a.69.69,0,0,0,.54-.44,9.49,9.49,0,0,1,1.26-2.15l.21,2.51a1.29,1.29,0,0,0,.33.74c-.56,1-2.07,3.81-2.62,4.75a.9.9,0,0,0,.33,1.22A.82.82,0,0,0,11,20a.87.87,0,0,0,.71-.44c.51-.88,1.84-3.56,2.42-4.57a40.93,40.93,0,0,1,2.27,4.49.91.91,0,0,0,.82.53.94.94,0,0,0,.36-.08A.88.88,0,0,0,18,18.8a40.16,40.16,0,0,0-2.55-5,1.27,1.27,0,0,0,.27-.9l-.2-2.37a4.87,4.87,0,0,0,2.71,1h.07a.7.7,0,0,0,.07-1.39ZM6.66,19.63a.25.25,0,1,1,.25-.25A.26.26,0,0,1,6.66,19.63Z"}),(0,r.createElement)(o.Rect,{fill:"none",width:"24",height:"24"})),l=(0,r.createElement)(o.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24",width:"24",height:"24"},(0,r.createElement)(o.Path,{d:"M12.82,13.34h0a.62.62,0,0,0,.54.61c2.8.38,4.45,1.39,4.45,2.15,0,1-2.58,2.31-6.78,2.31S4.25,17.07,4.25,16.1c0-.77,1.68-1.79,4.52-2.15a.63.63,0,0,0,.54-.62h0a.62.62,0,0,0-.7-.62C5.6,13.11,3,14.27,3,16.1c0,2.31,4.14,3.56,8,3.56s8-1.25,8-3.56c0-1.81-2.56-3-5.54-3.38A.62.62,0,0,0,12.82,13.34Z"}),(0,r.createElement)(o.Path,{d:"M19.72,4.52a.56.56,0,0,0-.31-.09.58.58,0,0,0-.26.06l-1,.5a3.43,3.43,0,0,1-1.53.35A3.51,3.51,0,0,1,15,5a3.47,3.47,0,0,0-1.6-.39A3.51,3.51,0,0,0,11.7,5V4.6A.63.63,0,0,0,11.07,4a.63.63,0,0,0-.63.63V15.9a.63.63,0,0,0,.63.63.63.63,0,0,0,.63-.63V10.13a3.51,3.51,0,0,1,1.67-.42,3.47,3.47,0,0,1,1.6.39,3.51,3.51,0,0,0,1.61.39,3.43,3.43,0,0,0,1.53-.35l1.56-.75A.6.6,0,0,0,20,8.86V5A.59.59,0,0,0,19.72,4.52Z"})),c=(0,r.createElement)(o.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24",width:"24",height:"24"},(0,r.createElement)(o.Path,{d:"M6.3,18.93H17.7V7.08H15.16V6a1.68,1.68,0,0,0-1.68-1.68h-3A1.68,1.68,0,0,0,8.84,6V7.08H6.3V18.93ZM13.41,15l-.69.53.06.87a.47.47,0,0,1-.24.44.46.46,0,0,1-.23.06.5.5,0,0,1-.26-.08l-.72-.49-.81.32a.46.46,0,0,1-.49-.08.48.48,0,0,1-.13-.48l.23-.83-.55-.67a.47.47,0,0,1,.34-.77l.87,0,.47-.74a.46.46,0,0,1,.44-.21.47.47,0,0,1,.39.3l.3.82.84.22a.44.44,0,0,1,.34.35A.46.46,0,0,1,13.41,15Zm2.25-6.47c.83,0,1.36.32,1.36.81s-.53.82-1.36.82-1.37-.32-1.37-.82S14.83,8.54,15.66,8.54ZM9.77,6a.76.76,0,0,1,.75-.75h3a.76.76,0,0,1,.75.75V7.08H9.77ZM7.43,11.36l1.31-.72a.43.43,0,0,1,.35,0,.44.44,0,0,1,.28.22l.73,1.3a.48.48,0,0,1-.18.64l-1.31.72a.46.46,0,0,1-.22.06.27.27,0,0,1-.13,0A.44.44,0,0,1,8,13.3L7.25,12A.48.48,0,0,1,7.43,11.36ZM20.92,8.29v9.43a1.21,1.21,0,0,1-1.21,1.21H18.64V7.08h1.07A1.21,1.21,0,0,1,20.92,8.29ZM5.36,18.93H4.29a1.21,1.21,0,0,1-1.21-1.21V8.29A1.21,1.21,0,0,1,4.29,7.08H5.36V18.93Z"})),s=(0,r.createElement)(o.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24",width:"24",height:"24"},(0,r.createElement)(o.Path,{d:"M15.67,19.36l2-2a.32.32,0,0,0-.23-.55h-2a.32.32,0,0,0-.33.32v2A.33.33,0,0,0,15.67,19.36Z"}),(0,r.createElement)(o.Path,{d:"M18.31,15.35V4.47a.5.5,0,0,0-.5-.5H6.19a.5.5,0,0,0-.5.5v15a.5.5,0,0,0,.5.5h7.48a.5.5,0,0,0,.5-.5V16.36a.5.5,0,0,1,.5-.5h3.14A.5.5,0,0,0,18.31,15.35Zm-7.84-7.7h3.06a.47.47,0,0,1,.47.47h0a.47.47,0,0,1-.47.47H10.47A.47.47,0,0,1,10,8.12h0A.47.47,0,0,1,10.47,7.65ZM8.63,10h6.74a.47.47,0,0,1,.47.47h0a.47.47,0,0,1-.47.47H8.63a.47.47,0,0,1-.47-.47h0A.47.47,0,0,1,8.63,10Zm0,2h6.74a.47.47,0,0,1,.47.47h0a.47.47,0,0,1-.47.46H8.63a.47.47,0,0,1-.47-.46h0A.47.47,0,0,1,8.63,12Z"})),d=(0,r.createElement)(o.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24",width:"24",height:"24"},(0,r.createElement)(o.Path,{d:"M19.71,18.29l-4-4a6.52,6.52,0,1,0-1.41,1.41l4,4a1,1,0,0,0,1.42,0A1,1,0,0,0,19.71,18.29Zm-8.84-3.46a.49.49,0,0,1-.74,0C9.81,14.48,7,11.3,7,9.5a3.5,3.5,0,0,1,7,0C14,11.3,11.19,14.48,10.87,14.83Z"}),(0,r.createElement)(o.Circle,{cx:"10.5",cy:"9.5",r:"1.5"})),u=n(9987),p=()=>null;(0,t.registerBlockCollection)("wptravelenginepagesblocks",{title:(0,e.__)("WP Travel Engine - Trip Pages Blocks","wp-travel-engine")}),u.keys().forEach((e=>{if(!e.split("/").includes("block.js"))return;const{edit:n,metadata:r,save:o}=u(e);r.name&&function(e){if(!e)return;const{metadata:n,settings:r,name:o}=e,u={"wptravelengine/activities":i,"wptravelengine/destinations":l,"wptravelengine/trip-types":c,"wptravelengine/trip-search":d,"wptravelengine/terms":s,"wptravelengine/trips":a}[o];u&&(r.icon=u),(0,t.registerBlockType)({name:o,...n},r)}({name:r.name,metadata:r,settings:{edit:n,save:null!=o?o:p}})}))})()})();