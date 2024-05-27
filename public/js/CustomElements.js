(()=>{var N=globalThis,O=N.ShadowRoot&&(N.ShadyCSS===void 0||N.ShadyCSS.nativeShadow)&&"adoptedStyleSheets"in Document.prototype&&"replace"in CSSStyleSheet.prototype,j=Symbol(),Q=new WeakMap,k=class{constructor(t,e,i){if(this._$cssResult$=!0,i!==j)throw Error("CSSResult is not constructable. Use `unsafeCSS` or `css` instead.");this.cssText=t,this.t=e}get styleSheet(){let t=this.o,e=this.t;if(O&&t===void 0){let i=e!==void 0&&e.length===1;i&&(t=Q.get(e)),t===void 0&&((this.o=t=new CSSStyleSheet).replaceSync(this.cssText),i&&Q.set(e,t))}return t}toString(){return this.cssText}},tt=r=>new k(typeof r=="string"?r:r+"",void 0,j),h=(r,...t)=>{let e=r.length===1?r[0]:t.reduce((i,s,o)=>i+(n=>{if(n._$cssResult$===!0)return n.cssText;if(typeof n=="number")return n;throw Error("Value passed to 'css' function must be a 'css' function result: "+n+". Use 'unsafeCSS' to pass non-literal values, but take care to ensure page security.")})(s)+r[o+1],r[0]);return new k(e,r,j)},V=(r,t)=>{if(O)r.adoptedStyleSheets=t.map(e=>e instanceof CSSStyleSheet?e:e.styleSheet);else for(let e of t){let i=document.createElement("style"),s=N.litNonce;s!==void 0&&i.setAttribute("nonce",s),i.textContent=e.cssText,r.appendChild(i)}},R=O?r=>r:r=>r instanceof CSSStyleSheet?(t=>{let e="";for(let i of t.cssRules)e+=i.cssText;return tt(e)})(r):r;var{is:yt,defineProperty:_t,getOwnPropertyDescriptor:At,getOwnPropertyNames:xt,getOwnPropertySymbols:St,getPrototypeOf:Ct}=Object,M=globalThis,et=M.trustedTypes,wt=et?et.emptyScript:"",kt=M.reactiveElementPolyfillSupport,E=(r,t)=>r,q={toAttribute(r,t){switch(t){case Boolean:r=r?wt:null;break;case Object:case Array:r=r==null?r:JSON.stringify(r)}return r},fromAttribute(r,t){let e=r;switch(t){case Boolean:e=r!==null;break;case Number:e=r===null?null:Number(r);break;case Object:case Array:try{e=JSON.parse(r)}catch{e=null}}return e}},st=(r,t)=>!yt(r,t),it={attribute:!0,type:String,converter:q,reflect:!1,hasChanged:st};Symbol.metadata??=Symbol("metadata"),M.litPropertyMetadata??=new WeakMap;var b=class extends HTMLElement{static addInitializer(t){this._$Ei(),(this.l??=[]).push(t)}static get observedAttributes(){return this.finalize(),this._$Eh&&[...this._$Eh.keys()]}static createProperty(t,e=it){if(e.state&&(e.attribute=!1),this._$Ei(),this.elementProperties.set(t,e),!e.noAccessor){let i=Symbol(),s=this.getPropertyDescriptor(t,i,e);s!==void 0&&_t(this.prototype,t,s)}}static getPropertyDescriptor(t,e,i){let{get:s,set:o}=At(this.prototype,t)??{get(){return this[e]},set(n){this[e]=n}};return{get(){return s?.call(this)},set(n){let f=s?.call(this);o.call(this,n),this.requestUpdate(t,f,i)},configurable:!0,enumerable:!0}}static getPropertyOptions(t){return this.elementProperties.get(t)??it}static _$Ei(){if(this.hasOwnProperty(E("elementProperties")))return;let t=Ct(this);t.finalize(),t.l!==void 0&&(this.l=[...t.l]),this.elementProperties=new Map(t.elementProperties)}static finalize(){if(this.hasOwnProperty(E("finalized")))return;if(this.finalized=!0,this._$Ei(),this.hasOwnProperty(E("properties"))){let e=this.properties,i=[...xt(e),...St(e)];for(let s of i)this.createProperty(s,e[s])}let t=this[Symbol.metadata];if(t!==null){let e=litPropertyMetadata.get(t);if(e!==void 0)for(let[i,s]of e)this.elementProperties.set(i,s)}this._$Eh=new Map;for(let[e,i]of this.elementProperties){let s=this._$Eu(e,i);s!==void 0&&this._$Eh.set(s,e)}this.elementStyles=this.finalizeStyles(this.styles)}static finalizeStyles(t){let e=[];if(Array.isArray(t)){let i=new Set(t.flat(1/0).reverse());for(let s of i)e.unshift(R(s))}else t!==void 0&&e.push(R(t));return e}static _$Eu(t,e){let i=e.attribute;return i===!1?void 0:typeof i=="string"?i:typeof t=="string"?t.toLowerCase():void 0}constructor(){super(),this._$Ep=void 0,this.isUpdatePending=!1,this.hasUpdated=!1,this._$Em=null,this._$Ev()}_$Ev(){this._$ES=new Promise(t=>this.enableUpdating=t),this._$AL=new Map,this._$E_(),this.requestUpdate(),this.constructor.l?.forEach(t=>t(this))}addController(t){(this._$EO??=new Set).add(t),this.renderRoot!==void 0&&this.isConnected&&t.hostConnected?.()}removeController(t){this._$EO?.delete(t)}_$E_(){let t=new Map,e=this.constructor.elementProperties;for(let i of e.keys())this.hasOwnProperty(i)&&(t.set(i,this[i]),delete this[i]);t.size>0&&(this._$Ep=t)}createRenderRoot(){let t=this.shadowRoot??this.attachShadow(this.constructor.shadowRootOptions);return V(t,this.constructor.elementStyles),t}connectedCallback(){this.renderRoot??=this.createRenderRoot(),this.enableUpdating(!0),this._$EO?.forEach(t=>t.hostConnected?.())}enableUpdating(t){}disconnectedCallback(){this._$EO?.forEach(t=>t.hostDisconnected?.())}attributeChangedCallback(t,e,i){this._$AK(t,i)}_$EC(t,e){let i=this.constructor.elementProperties.get(t),s=this.constructor._$Eu(t,i);if(s!==void 0&&i.reflect===!0){let o=(i.converter?.toAttribute!==void 0?i.converter:q).toAttribute(e,i.type);this._$Em=t,o==null?this.removeAttribute(s):this.setAttribute(s,o),this._$Em=null}}_$AK(t,e){let i=this.constructor,s=i._$Eh.get(t);if(s!==void 0&&this._$Em!==s){let o=i.getPropertyOptions(s),n=typeof o.converter=="function"?{fromAttribute:o.converter}:o.converter?.fromAttribute!==void 0?o.converter:q;this._$Em=s,this[s]=n.fromAttribute(e,o.type),this._$Em=null}}requestUpdate(t,e,i){if(t!==void 0){if(i??=this.constructor.getPropertyOptions(t),!(i.hasChanged??st)(this[t],e))return;this.P(t,e,i)}this.isUpdatePending===!1&&(this._$ES=this._$ET())}P(t,e,i){this._$AL.has(t)||this._$AL.set(t,e),i.reflect===!0&&this._$Em!==t&&(this._$Ej??=new Set).add(t)}async _$ET(){this.isUpdatePending=!0;try{await this._$ES}catch(e){Promise.reject(e)}let t=this.scheduleUpdate();return t!=null&&await t,!this.isUpdatePending}scheduleUpdate(){return this.performUpdate()}performUpdate(){if(!this.isUpdatePending)return;if(!this.hasUpdated){if(this.renderRoot??=this.createRenderRoot(),this._$Ep){for(let[s,o]of this._$Ep)this[s]=o;this._$Ep=void 0}let i=this.constructor.elementProperties;if(i.size>0)for(let[s,o]of i)o.wrapped!==!0||this._$AL.has(s)||this[s]===void 0||this.P(s,this[s],o)}let t=!1,e=this._$AL;try{t=this.shouldUpdate(e),t?(this.willUpdate(e),this._$EO?.forEach(i=>i.hostUpdate?.()),this.update(e)):this._$EU()}catch(i){throw t=!1,this._$EU(),i}t&&this._$AE(e)}willUpdate(t){}_$AE(t){this._$EO?.forEach(e=>e.hostUpdated?.()),this.hasUpdated||(this.hasUpdated=!0,this.firstUpdated(t)),this.updated(t)}_$EU(){this._$AL=new Map,this.isUpdatePending=!1}get updateComplete(){return this.getUpdateComplete()}getUpdateComplete(){return this._$ES}shouldUpdate(t){return!0}update(t){this._$Ej&&=this._$Ej.forEach(e=>this._$EC(e,this[e])),this._$EU()}updated(t){}firstUpdated(t){}};b.elementStyles=[],b.shadowRootOptions={mode:"open"},b[E("elementProperties")]=new Map,b[E("finalized")]=new Map,kt?.({ReactiveElement:b}),(M.reactiveElementVersions??=[]).push("2.0.4");var X=globalThis,F=X.trustedTypes,rt=F?F.createPolicy("lit-html",{createHTML:r=>r}):void 0,ht="$lit$",y=`lit$${Math.random().toFixed(9).slice(2)}$`,dt="?"+y,Et=`<${dt}>`,x=document,L=()=>x.createComment(""),P=r=>r===null||typeof r!="object"&&typeof r!="function",ut=Array.isArray,Tt=r=>ut(r)||typeof r?.[Symbol.iterator]=="function",W=`[ 	
\f\r]`,T=/<(?:(!--|\/[^a-zA-Z])|(\/?[a-zA-Z][^>\s]*)|(\/?$))/g,ot=/-->/g,nt=/>/g,_=RegExp(`>|${W}(?:([^\\s"'>=/]+)(${W}*=${W}*(?:[^ 	
\f\r"'\`<>=]|("|')|))|$)`,"g"),at=/'/g,lt=/"/g,pt=/^(?:script|style|textarea|title)$/i,mt=r=>(t,...e)=>({_$litType$:r,strings:t,values:e}),m=mt(1),Rt=mt(2),S=Symbol.for("lit-noChange"),u=Symbol.for("lit-nothing"),ct=new WeakMap,A=x.createTreeWalker(x,129);function ft(r,t){if(!Array.isArray(r)||!r.hasOwnProperty("raw"))throw Error("invalid template strings array");return rt!==void 0?rt.createHTML(t):t}var Lt=(r,t)=>{let e=r.length-1,i=[],s,o=t===2?"<svg>":"",n=T;for(let f=0;f<e;f++){let l=r[f],d,p,c=-1,$=0;for(;$<l.length&&(n.lastIndex=$,p=n.exec(l),p!==null);)$=n.lastIndex,n===T?p[1]==="!--"?n=ot:p[1]!==void 0?n=nt:p[2]!==void 0?(pt.test(p[2])&&(s=RegExp("</"+p[2],"g")),n=_):p[3]!==void 0&&(n=_):n===_?p[0]===">"?(n=s??T,c=-1):p[1]===void 0?c=-2:(c=n.lastIndex-p[2].length,d=p[1],n=p[3]===void 0?_:p[3]==='"'?lt:at):n===lt||n===at?n=_:n===ot||n===nt?n=T:(n=_,s=void 0);let v=n===_&&r[f+1].startsWith("/>")?" ":"";o+=n===T?l+Et:c>=0?(i.push(d),l.slice(0,c)+ht+l.slice(c)+y+v):l+y+(c===-2?f:v)}return[ft(r,o+(r[e]||"<?>")+(t===2?"</svg>":"")),i]},U=class r{constructor({strings:t,_$litType$:e},i){let s;this.parts=[];let o=0,n=0,f=t.length-1,l=this.parts,[d,p]=Lt(t,e);if(this.el=r.createElement(d,i),A.currentNode=this.el.content,e===2){let c=this.el.content.firstChild;c.replaceWith(...c.childNodes)}for(;(s=A.nextNode())!==null&&l.length<f;){if(s.nodeType===1){if(s.hasAttributes())for(let c of s.getAttributeNames())if(c.endsWith(ht)){let $=p[n++],v=s.getAttribute(c).split(y),H=/([.?@])?(.*)/.exec($);l.push({type:1,index:o,name:H[2],strings:v,ctor:H[1]==="."?K:H[1]==="?"?Y:H[1]==="@"?Z:w}),s.removeAttribute(c)}else c.startsWith(y)&&(l.push({type:6,index:o}),s.removeAttribute(c));if(pt.test(s.tagName)){let c=s.textContent.split(y),$=c.length-1;if($>0){s.textContent=F?F.emptyScript:"";for(let v=0;v<$;v++)s.append(c[v],L()),A.nextNode(),l.push({type:2,index:++o});s.append(c[$],L())}}}else if(s.nodeType===8)if(s.data===dt)l.push({type:2,index:o});else{let c=-1;for(;(c=s.data.indexOf(y,c+1))!==-1;)l.push({type:7,index:o}),c+=y.length-1}o++}}static createElement(t,e){let i=x.createElement("template");return i.innerHTML=t,i}};function C(r,t,e=r,i){if(t===S)return t;let s=i!==void 0?e._$Co?.[i]:e._$Cl,o=P(t)?void 0:t._$litDirective$;return s?.constructor!==o&&(s?._$AO?.(!1),o===void 0?s=void 0:(s=new o(r),s._$AT(r,e,i)),i!==void 0?(e._$Co??=[])[i]=s:e._$Cl=s),s!==void 0&&(t=C(r,s._$AS(r,t.values),s,i)),t}var J=class{constructor(t,e){this._$AV=[],this._$AN=void 0,this._$AD=t,this._$AM=e}get parentNode(){return this._$AM.parentNode}get _$AU(){return this._$AM._$AU}u(t){let{el:{content:e},parts:i}=this._$AD,s=(t?.creationScope??x).importNode(e,!0);A.currentNode=s;let o=A.nextNode(),n=0,f=0,l=i[0];for(;l!==void 0;){if(n===l.index){let d;l.type===2?d=new B(o,o.nextSibling,this,t):l.type===1?d=new l.ctor(o,l.name,l.strings,this,t):l.type===6&&(d=new G(o,this,t)),this._$AV.push(d),l=i[++f]}n!==l?.index&&(o=A.nextNode(),n++)}return A.currentNode=x,s}p(t){let e=0;for(let i of this._$AV)i!==void 0&&(i.strings!==void 0?(i._$AI(t,i,e),e+=i.strings.length-2):i._$AI(t[e])),e++}},B=class r{get _$AU(){return this._$AM?._$AU??this._$Cv}constructor(t,e,i,s){this.type=2,this._$AH=u,this._$AN=void 0,this._$AA=t,this._$AB=e,this._$AM=i,this.options=s,this._$Cv=s?.isConnected??!0}get parentNode(){let t=this._$AA.parentNode,e=this._$AM;return e!==void 0&&t?.nodeType===11&&(t=e.parentNode),t}get startNode(){return this._$AA}get endNode(){return this._$AB}_$AI(t,e=this){t=C(this,t,e),P(t)?t===u||t==null||t===""?(this._$AH!==u&&this._$AR(),this._$AH=u):t!==this._$AH&&t!==S&&this._(t):t._$litType$!==void 0?this.$(t):t.nodeType!==void 0?this.T(t):Tt(t)?this.k(t):this._(t)}S(t){return this._$AA.parentNode.insertBefore(t,this._$AB)}T(t){this._$AH!==t&&(this._$AR(),this._$AH=this.S(t))}_(t){this._$AH!==u&&P(this._$AH)?this._$AA.nextSibling.data=t:this.T(x.createTextNode(t)),this._$AH=t}$(t){let{values:e,_$litType$:i}=t,s=typeof i=="number"?this._$AC(t):(i.el===void 0&&(i.el=U.createElement(ft(i.h,i.h[0]),this.options)),i);if(this._$AH?._$AD===s)this._$AH.p(e);else{let o=new J(s,this),n=o.u(this.options);o.p(e),this.T(n),this._$AH=o}}_$AC(t){let e=ct.get(t.strings);return e===void 0&&ct.set(t.strings,e=new U(t)),e}k(t){ut(this._$AH)||(this._$AH=[],this._$AR());let e=this._$AH,i,s=0;for(let o of t)s===e.length?e.push(i=new r(this.S(L()),this.S(L()),this,this.options)):i=e[s],i._$AI(o),s++;s<e.length&&(this._$AR(i&&i._$AB.nextSibling,s),e.length=s)}_$AR(t=this._$AA.nextSibling,e){for(this._$AP?.(!1,!0,e);t&&t!==this._$AB;){let i=t.nextSibling;t.remove(),t=i}}setConnected(t){this._$AM===void 0&&(this._$Cv=t,this._$AP?.(t))}},w=class{get tagName(){return this.element.tagName}get _$AU(){return this._$AM._$AU}constructor(t,e,i,s,o){this.type=1,this._$AH=u,this._$AN=void 0,this.element=t,this.name=e,this._$AM=s,this.options=o,i.length>2||i[0]!==""||i[1]!==""?(this._$AH=Array(i.length-1).fill(new String),this.strings=i):this._$AH=u}_$AI(t,e=this,i,s){let o=this.strings,n=!1;if(o===void 0)t=C(this,t,e,0),n=!P(t)||t!==this._$AH&&t!==S,n&&(this._$AH=t);else{let f=t,l,d;for(t=o[0],l=0;l<o.length-1;l++)d=C(this,f[i+l],e,l),d===S&&(d=this._$AH[l]),n||=!P(d)||d!==this._$AH[l],d===u?t=u:t!==u&&(t+=(d??"")+o[l+1]),this._$AH[l]=d}n&&!s&&this.j(t)}j(t){t===u?this.element.removeAttribute(this.name):this.element.setAttribute(this.name,t??"")}},K=class extends w{constructor(){super(...arguments),this.type=3}j(t){this.element[this.name]=t===u?void 0:t}},Y=class extends w{constructor(){super(...arguments),this.type=4}j(t){this.element.toggleAttribute(this.name,!!t&&t!==u)}},Z=class extends w{constructor(t,e,i,s,o){super(t,e,i,s,o),this.type=5}_$AI(t,e=this){if((t=C(this,t,e,0)??u)===S)return;let i=this._$AH,s=t===u&&i!==u||t.capture!==i.capture||t.once!==i.once||t.passive!==i.passive,o=t!==u&&(i===u||s);s&&this.element.removeEventListener(this.name,this,i),o&&this.element.addEventListener(this.name,this,t),this._$AH=t}handleEvent(t){typeof this._$AH=="function"?this._$AH.call(this.options?.host??this.element,t):this._$AH.handleEvent(t)}},G=class{constructor(t,e,i){this.element=t,this.type=6,this._$AN=void 0,this._$AM=e,this.options=i}get _$AU(){return this._$AM._$AU}_$AI(t){C(this,t)}};var Pt=X.litHtmlPolyfillSupport;Pt?.(U,B),(X.litHtmlVersions??=[]).push("3.1.3");var gt=(r,t,e)=>{let i=e?.renderBefore??t,s=i._$litPart$;if(s===void 0){let o=e?.renderBefore??null;i._$litPart$=s=new B(t.insertBefore(L(),o),o,void 0,e??{})}return s._$AI(r),s};var g=class extends b{constructor(){super(...arguments),this.renderOptions={host:this},this._$Do=void 0}createRenderRoot(){let t=super.createRenderRoot();return this.renderOptions.renderBefore??=t.firstChild,t}update(t){let e=this.render();this.hasUpdated||(this.renderOptions.isConnected=this.isConnected),super.update(t),this._$Do=gt(e,this.renderRoot,this.renderOptions)}connectedCallback(){super.connectedCallback(),this._$Do?.setConnected(!0)}disconnectedCallback(){super.disconnectedCallback(),this._$Do?.setConnected(!1)}render(){return S}};g._$litElement$=!0,g.finalized=!0,globalThis.litElementHydrateSupport?.({LitElement:g});var Ut=globalThis.litElementPolyfillSupport;Ut?.({LitElement:g});(globalThis.litElementVersions??=[]).push("4.0.5");var a={primary:h`#0d6efd`,primaryLight:h`#86b7fe`,primaryHover:h`#0b5ed7`,secondary:h`#6c757d`,secondaryHover:h`#5c636a`,primaryBackground:h`#ffffff`,overlayBackground:h`rgba(0, 0, 0, 0.9)`,disabledBackground:h`rgba(200, 200, 200, 0.5)`,border:h`#dee2e6`,fontColorLight:h`#ffffff`,fontColorDark:h`#212429`,linkColor:h`#212429`,focusBoxShadow:h`rgba(13, 110, 253, .25)`};var $t=h`
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: sans-serif;
    }

    .hidden {
        display: none;
        height: 0;
    }

    .multi-select-container {
        font-family: sans-serif;
        width: 100%;
    }

    .multi-select-wrapper {
        border: 1px solid ${a.border};
        border-radius: 5px;
        padding: .5rem;
    }

    .multi-select-select-list {
        position: absolute;
        max-height: 15rem;
        overflow-y: scroll;
        overflow-x: hidden;
        padding: 0;
        border-radius: 0;
        z-index: 999;
        width: 100%;
        border-left: 1px solid ${a.border};
        border-right: 1px solid ${a.border};
        border-bottom: 1px solid ${a.border};
    }

    .multi-select-search-field-input-container {
        position: relative;
        width: 100%;
    }

    .multi-select-chevron-down {
        cursor: pointer;
        position: absolute;
        right: 10px;
        top: 3px;
        font-size: 22px;
    }

    .multi-select-search-field-input {
        width: 100%;
        box-sizing: border-box;
        padding: .375rem 2.25rem .375rem .75rem;
        border: 1px solid ${a.border};
        border-radius: 5px;
        color: ${a.linkColor};
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;

        &:focus {
            border-color: ${a.primaryLight};
            outline: 0;
            box-shadow: 0 0 0 .25rem ${a.focusBoxShadow};
        }
    }

    .multi-select-select-list-item {
        background: ${a.primaryBackground};
        padding: .5rem;
        cursor: pointer;

        &:hover {
            background: ${a.border};
        }

        &.selected {
            background: ${a.primaryLight};

            &:hover {
                background: ${a.border};
            }
        }
    }

    .multi-select-selected-container {
        overflow-x: hidden;
        display: flex;
        height: 2.4rem;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        padding: 0 0 .5rem 0;
    }

    .multi-select-select-badge {
        display: inline-block;
        text-wrap: nowrap;
        margin: 0 .5rem 0 0;
        background: ${a.border};
        vertical-align: center;
        padding: .175rem .5rem .175rem .75rem;
        border: 1px solid ${a.border};
        border-radius: 5px;
        color: ${a.linkColor};
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
    }

    .cross {
        cursor: pointer;
        color: ${a.linkColor};

        &:hover {
            color: ${a.primaryLight};
        }
    }
`;var z=class extends g{static styles=$t;static properties={jsonData:{type:Array,attribute:"json-data"},displayField:{type:String,attribute:"display-field"},valueField:{type:String,attribute:"value-field"},value:{type:Array},name:{type:String},open:{type:Boolean}};timeoutLength=300;hiddenClass="hidden";list=null;searchField=null;closeTimeout=null;#t;static formAssociated=!0;constructor(){super(),this.#t=this.attachInternals()}connectedCallback(){if(super.connectedCallback(),!(this.jsonData instanceof Array))throw new Error('No "json-data" provided');if(!this.displayField)throw new Error('No "display-field" provided');if(!this.valueField)throw new Error('No "value-field" provided');this.value instanceof Array||(this.value=[],this.setAttribute("value",JSON.stringify(this.value))),this.setFormValue()}setFormValue(){let t=new FormData;this.value.forEach(e=>{t.append(`${this.name}[]`,e)}),this.#t.setFormValue(t)}onChangeValue(t){this.open&&this.getSearchField().focus();let e=parseInt(t.currentTarget.dataset.value),i=-1;this.value.some((s,o)=>{parseInt(s)===e&&(i=o)}),i>-1?this.value.splice(i,1):this.value.push(e),this.setAttribute("value",JSON.stringify(this.value)),this.setFormValue()}onChevronClick(){this.getSearchField().focus()}onInput(t){let e=t.currentTarget.value.toLowerCase();this.getList().querySelectorAll(".multi-select-select-list-item").forEach(i=>{i.innerHTML.toLowerCase().indexOf(e)>-1?i.classList.remove(this.hiddenClass):i.classList.add(this.hiddenClass)})}onFocusInput(){clearTimeout(this.closeTimeout),this.showList()}onBlur(){this.closeTimeout=setTimeout(()=>{this.hideList()},this.timeoutLength)}onMouseEnter(t){t.currentTarget.addEventListener("wheel",this.onScroll.bind(this,t.currentTarget))}onMouseLeave(t){t.currentTarget.removeEventListener("wheel",this.onScroll.bind(this,t.currentTarget))}onScroll(t,e){e.preventDefault(),t.scrollLeft+=e.deltaY/10}isSelected(t){let e=!1;return this.value.some(i=>{if(parseInt(i)===t||String(i)===t)return e=!0,!0}),e}getListItem(t){let e;return this.jsonData.some(i=>{if(this.compareAsInteger(i,t)||this.compareAsString(i,t))return e=i,!0}),e}compareAsString(t,e){return String(t[this.valueField])===e}compareAsInteger(t,e){return parseInt(t[this.valueField])===e}showList(){this.setAttribute("open","true"),this.adjustSize()}adjustSize(){this.getList().style=`width: ${this.getSearchField().clientWidth}px;`}hideList(){this.removeAttribute("open")}getList(){return this.list===null&&(this.list=this.renderRoot.querySelector(".multi-select-select-list")),this.list}getSearchField(){return this.searchField===null&&(this.searchField=this.renderRoot.querySelector(".multi-select-search-field-input")),this.searchField}render(){return m`
            <div class="multi-select-container">
                <div class="multi-select-wrapper">
                    <div class="multi-select-selected-container" @mouseenter="${this.onMouseEnter}"
                         @mouseleave="${this.onMouseLeave}">
                        ${this.value.map(t=>{let e=this.getListItem(t);if(e)return m`
                                        <span class="multi-select-select-badge"
                                              data-value="${e[this.valueField]}">
                                            ${e[this.displayField]}&nbsp;
                                            <span class="cross"
                                                  data-value="${e[this.valueField]}"
                                                  @click="${this.onChangeValue}">&#10799;</span>
                                        </span>`})}
                    </div>
                    <div class="multi-select-search-field-input-container">
                        <span class="multi-select-chevron-down" @click="${this.onChevronClick}">&#8964;</span>
                        <input type="text" class="multi-select-search-field-input"
                               @input="${this.onInput}"
                               @focus="${this.onFocusInput}"
                               @blur="${this.onBlur}"/>
                    </div>
                    <div class="multi-select-select-list${this.open?"":` ${this.hiddenClass}`}">
                        ${this.jsonData.map(t=>{let e=this.isSelected(t[this.valueField])?" selected":"";return m`
                                        <div class="multi-select-select-list-item${e}"
                                             @click=${this.onChangeValue}
                                             data-value="${t[this.valueField]}">${t[this.displayField]}
                                        </div>`})}
                    </div>
                </div>
            </div>`}};var bt=h`
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: sans-serif;
    }

    .hidden {
        display: none;
        height: 0;
        width: 0;
        visibility: hidden;
    }

    .overlay {
        z-index: 1100;
        background-color: ${a.overlayBackground};
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        overflow: hidden;
        display: flex;
    }

    .inner-overlay {
        z-index: 1101;
        width: 100%;
        align-self: flex-end;
        min-height: 5rem;

        .container {
            max-width: 1600px;
            min-height: 5rem;
            background: ${a.primaryBackground};
            margin: 1.5rem auto;
            padding: 1.5rem;

            .text-container {
                padding: 1rem;

                .title {
                    font-weight: bolder;
                    margin-bottom: 1rem;
                }

                .text {
                    line-height: 1.5rem;
                }
            }

            .checkbox-container {
                padding: 1rem;

                .checkboxLabel {
                    display: block;
                    line-height: 1.5rem;

                    .checkbox {
                        border-radius: 3px;
                        width: 1rem;
                        height: 1rem;
                        border: 1px solid ${a.border};
                    }
                }
            }

            .button-container {
                padding: 1rem;

                .button {
                    cursor: pointer;
                    line-height: 1.5rem;
                    border-radius: 5px;
                    margin-bottom: 1rem;
                    padding: .4rem .8rem;
                    width: 100%;
                    max-width: 380px;
                    text-align: center;
                    color: ${a.fontColorLight};
                }

                .default-button-container {
                    .default-button {
                        background: ${a.secondary};

                        &:hover {
                            background: ${a.secondaryHover};
                        }
                    }
                }

                .accept-button-container {
                    .accept-all-button {
                        background: ${a.primary};

                        &:hover {
                            background: ${a.primaryHover};
                        }
                    }
                }
            }

            .link-container {
                text-align: center;
                color: ${a.fontColorDark};

                a {
                    color: ${a.linkColor};

                    :visited {
                        color: ${a.linkColor};
                    }
                }
            }
        }
    }

    @media only screen and (min-width: 450px) {
        .inner-overlay {
            .container {
                .button-container {
                    .button {
                        margin: 0 auto 1rem auto;
                    }
                }
            }
        }
    }

    @media only screen and (min-width: 768px) {
        .inner-overlay {
            .container {
                .button-container {
                    .button {

                    }
                }
            }
        }
    }

    @media only screen and (min-width: 1024px) {
        .inner-overlay {
            .container {
                display: flex;
                flex-wrap: wrap;

                .text-container {
                    flex: 2.25;
                }

                .checkbox-container {
                    flex: 1;
                }

                .button-container {
                    flex: 1.25;
                }

                .link-container {
                    flex: unset;
                    width: 100%;
                }
            }
        }
    }
`;var D=class extends g{static styles=bt;static properties={consentTitle:{type:String,attribute:"consent-title"},consentText:{type:String,attribute:"consent-text"},checkboxConfig:{type:Array,attribute:"checkbox-config"},linkConfig:{type:Array,attribute:"link-config"},acceptAllButtonText:{type:String,attribute:"accept-all-button-text"},acceptSelectedButtonText:{type:String,attribute:"accept-selected-button-text"},leavePageButtonText:{type:String,attribute:"leave-page-button-text"}};hiddenClass="hidden";oneYear=31536e6;value=null;constructor(){super()}connectedCallback(){if(super.connectedCallback(),this.checkboxConfig instanceof Array||(this.checkboxConfig=[]),this.linkConfig instanceof Array||(this.linkConfig=[]),!this.consentTitle)throw new Error("CookieConsent - consent-title required");if(!this.consentText)throw new Error("CookieConsent - consent-text required");if(!this.acceptAllButtonText)throw new Error("CookieConsent - accept-all-button-text required");let t=this.getCookie();t!==null&&(this.value=t),this.dispatchEvent(new CustomEvent("loaded",{detail:{accepted:this.value}}))}onAcceptAllButtonClick(){let t=this.checkboxConfig.map(e=>e.name);this.setCookie(t),this.close(t)}onAcceptSelectedButtonClick(){let e=[...this.getOverlay().querySelectorAll("boolean-switch[checked]")].map(i=>i.getAttribute("name"));this.setCookie(e),this.close(e)}onLeavePageButtonClick(){window.history.back()}getCookie(){let e=`; ${document.cookie}`.split("; consent=");return e.length===2?JSON.parse(e.pop().split(";").shift()):null}setCookie(t){document.cookie=`consent=${JSON.stringify(t)}; path=/; expires=${this.getCookieExpireTime()};`}getCookieExpireTime(){return new Date(new Date().getTime()+this.oneYear).toGMTString()}close(t){this.getOverlay().classList.add(this.hiddenClass),this.value=t,this.dispatchEvent(new CustomEvent("close",{detail:{accepted:t}}))}getOverlay(){return this.renderRoot.querySelector(".overlay")}render(){return m`
            <div class="overlay${this.getCookie()!==null?" "+this.hiddenClass:""}">
                <div class="inner-overlay">
                    <div class="container">
                        <div class="text-container">
                            <div class="title">${this.consentTitle}</div>
                            <div class="text">${this.consentText}</div>
                        </div>
                        ${this.renderCheckboxContainer()}
                        <div class="button-container">
                            <div class="accept-button-container">
                                <div class="button accept-all-button" @click="${this.onAcceptAllButtonClick}">
                                    ${this.acceptAllButtonText}&nbsp;&#10004;
                                </div>
                            </div>
                            ${this.renderButton(this.acceptSelectedButtonText,this.onAcceptSelectedButtonClick)}
                            ${this.renderButton(this.leavePageButtonText,this.onLeavePageButtonClick)}
                        </div>
                        ${this.renderLinkContainer()}
                    </div>
                </div>
            </div>`}renderButton(t,e){return t?m`
            <div class="default-button-container">
                <div class="button default-button" @click="${e}">
                    ${t}
                </div>
            </div>
        `:""}renderCheckbox(t){return t.required?m`
                <boolean-switch name="${t.name}" label="${t.label}" checked="checked"
                                disabled="disabled"></boolean-switch>`:m`
            <boolean-switch name="${t.name}" label="${t.label}"></boolean-switch>`}renderCheckboxContainer(){return this.checkboxConfig.length?m`
            <div class="checkbox-container">
                ${this.checkboxConfig.map(t=>this.renderCheckbox(t))}
            </div>
        `:""}renderLinkContainer(){return this.linkConfig.length?m`
            <div class="link-container">
                ${this.linkConfig.map((t,e)=>e+1>=this.linkConfig.length?m`<a href="${t.href}">${t.label}</a>`:m`<a href="${t.href}">${t.label}</a> | `)}
            </div>
        `:""}};var vt=h`
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: sans-serif;
    }

    .boolean-switch-container {
        display: block;
        
        label {
            position: absolute;
            line-height: 1.5rem;
        }

        .boolean-switch {
            position: relative;
            display: inline-block;
            width: 35px;
            height: 20px;
            border: 1px solid ${a.border};
            border-radius: 30px;
            cursor: pointer;
            
            .disabled {
                position: absolute;
                border-radius: 30px;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                background: ${a.disabledBackground};
                
                &.hidden {
                    visibility: hidden;
                    position: relative;
                    width: 0;
                    height: 0;
                }
            }

            .boolean-switch-slider {
                position: absolute;
                top: 1px;
                left: 1px;
                height: 16px;
                width: 16px;
                border-radius: 50%;
                background: ${a.secondary};
                -webkit-transition: .3s;
                transition: .3s;
                
                &.active {
                    background: ${a.primary};
                    transform: translateX(15px);
                }
            }
        }
    }
}
`;var I=class extends g{static styles=vt;static properties={name:{type:String},label:{type:String},checked:{type:Boolean},disabled:{type:Boolean}};#t;static formAssociated=!0;constructor(){super(),this.#t=this.attachInternals()}connectedCallback(){super.connectedCallback(),this.setFormValue()}render(){return m`
            <div class="boolean-switch-container">
                <div class="boolean-switch" @click="${this.onSliderClick}">
                    <div class="boolean-switch-slider${this.checked?" active":""}"></div>
                    <div class="disabled${this.disabled?"":" hidden"}"></div>
                </div>
                <label for="${this.name}" @click="${this.onSliderClick}">&nbsp;${this.label}</label>
            </div>
        `}onSliderClick(){if(this.disabled)return;let t=this.getSlider().classList;if(t.contains("active")){t.remove("active"),this.removeAttribute("checked");return}t.add("active"),this.setAttribute("checked","checked")}getSlider(){return this.renderRoot.querySelector(".boolean-switch-slider")}setFormValue(){let t=new FormData;t.append(`${this.name}`,this.checked),this.#t.setFormValue(t)}};window.customElements.define("multi-select",z);window.customElements.define("cookie-consent",D);window.customElements.define("boolean-switch",I);})();
/*! Bundled license information:

@lit/reactive-element/css-tag.js:
  (**
   * @license
   * Copyright 2019 Google LLC
   * SPDX-License-Identifier: BSD-3-Clause
   *)

@lit/reactive-element/reactive-element.js:
  (**
   * @license
   * Copyright 2017 Google LLC
   * SPDX-License-Identifier: BSD-3-Clause
   *)

lit-html/lit-html.js:
  (**
   * @license
   * Copyright 2017 Google LLC
   * SPDX-License-Identifier: BSD-3-Clause
   *)

lit-element/lit-element.js:
  (**
   * @license
   * Copyright 2017 Google LLC
   * SPDX-License-Identifier: BSD-3-Clause
   *)

lit-html/is-server.js:
  (**
   * @license
   * Copyright 2022 Google LLC
   * SPDX-License-Identifier: BSD-3-Clause
   *)
*/
