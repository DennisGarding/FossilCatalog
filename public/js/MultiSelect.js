(()=>{var H=globalThis,R=H.ShadowRoot&&(H.ShadyCSS===void 0||H.ShadyCSS.nativeShadow)&&"adoptedStyleSheets"in Document.prototype&&"replace"in CSSStyleSheet.prototype,F=Symbol(),Y=new WeakMap,E=class{constructor(t,e,s){if(this._$cssResult$=!0,s!==F)throw Error("CSSResult is not constructable. Use `unsafeCSS` or `css` instead.");this.cssText=t,this.t=e}get styleSheet(){let t=this.o,e=this.t;if(R&&t===void 0){let s=e!==void 0&&e.length===1;s&&(t=Y.get(e)),t===void 0&&((this.o=t=new CSSStyleSheet).replaceSync(this.cssText),s&&Y.set(e,t))}return t}toString(){return this.cssText}},G=r=>new E(typeof r=="string"?r:r+"",void 0,F),j=(r,...t)=>{let e=r.length===1?r[0]:t.reduce((s,i,o)=>s+(n=>{if(n._$cssResult$===!0)return n.cssText;if(typeof n=="number")return n;throw Error("Value passed to 'css' function must be a 'css' function result: "+n+". Use 'unsafeCSS' to pass non-literal values, but take care to ensure page security.")})(i)+r[o+1],r[0]);return new E(e,r,F)},D=(r,t)=>{if(R)r.adoptedStyleSheets=t.map(e=>e instanceof CSSStyleSheet?e:e.styleSheet);else for(let e of t){let s=document.createElement("style"),i=H.litNonce;i!==void 0&&s.setAttribute("nonce",i),s.textContent=e.cssText,r.appendChild(s)}},N=R?r=>r:r=>r instanceof CSSStyleSheet?(t=>{let e="";for(let s of t.cssRules)e+=s.cssText;return G(e)})(r):r;var{is:$t,defineProperty:ft,getOwnPropertyDescriptor:mt,getOwnPropertyNames:gt,getOwnPropertySymbols:_t,getPrototypeOf:vt}=Object,M=globalThis,Q=M.trustedTypes,yt=Q?Q.emptyScript:"",At=M.reactiveElementPolyfillSupport,x=(r,t)=>r,I={toAttribute(r,t){switch(t){case Boolean:r=r?yt:null;break;case Object:case Array:r=r==null?r:JSON.stringify(r)}return r},fromAttribute(r,t){let e=r;switch(t){case Boolean:e=r!==null;break;case Number:e=r===null?null:Number(r);break;case Object:case Array:try{e=JSON.parse(r)}catch{e=null}}return e}},tt=(r,t)=>!$t(r,t),X={attribute:!0,type:String,converter:I,reflect:!1,hasChanged:tt};Symbol.metadata??=Symbol("metadata"),M.litPropertyMetadata??=new WeakMap;var $=class extends HTMLElement{static addInitializer(t){this._$Ei(),(this.l??=[]).push(t)}static get observedAttributes(){return this.finalize(),this._$Eh&&[...this._$Eh.keys()]}static createProperty(t,e=X){if(e.state&&(e.attribute=!1),this._$Ei(),this.elementProperties.set(t,e),!e.noAccessor){let s=Symbol(),i=this.getPropertyDescriptor(t,s,e);i!==void 0&&ft(this.prototype,t,i)}}static getPropertyDescriptor(t,e,s){let{get:i,set:o}=mt(this.prototype,t)??{get(){return this[e]},set(n){this[e]=n}};return{get(){return i?.call(this)},set(n){let p=i?.call(this);o.call(this,n),this.requestUpdate(t,p,s)},configurable:!0,enumerable:!0}}static getPropertyOptions(t){return this.elementProperties.get(t)??X}static _$Ei(){if(this.hasOwnProperty(x("elementProperties")))return;let t=vt(this);t.finalize(),t.l!==void 0&&(this.l=[...t.l]),this.elementProperties=new Map(t.elementProperties)}static finalize(){if(this.hasOwnProperty(x("finalized")))return;if(this.finalized=!0,this._$Ei(),this.hasOwnProperty(x("properties"))){let e=this.properties,s=[...gt(e),..._t(e)];for(let i of s)this.createProperty(i,e[i])}let t=this[Symbol.metadata];if(t!==null){let e=litPropertyMetadata.get(t);if(e!==void 0)for(let[s,i]of e)this.elementProperties.set(s,i)}this._$Eh=new Map;for(let[e,s]of this.elementProperties){let i=this._$Eu(e,s);i!==void 0&&this._$Eh.set(i,e)}this.elementStyles=this.finalizeStyles(this.styles)}static finalizeStyles(t){let e=[];if(Array.isArray(t)){let s=new Set(t.flat(1/0).reverse());for(let i of s)e.unshift(N(i))}else t!==void 0&&e.push(N(t));return e}static _$Eu(t,e){let s=e.attribute;return s===!1?void 0:typeof s=="string"?s:typeof t=="string"?t.toLowerCase():void 0}constructor(){super(),this._$Ep=void 0,this.isUpdatePending=!1,this.hasUpdated=!1,this._$Em=null,this._$Ev()}_$Ev(){this._$ES=new Promise(t=>this.enableUpdating=t),this._$AL=new Map,this._$E_(),this.requestUpdate(),this.constructor.l?.forEach(t=>t(this))}addController(t){(this._$EO??=new Set).add(t),this.renderRoot!==void 0&&this.isConnected&&t.hostConnected?.()}removeController(t){this._$EO?.delete(t)}_$E_(){let t=new Map,e=this.constructor.elementProperties;for(let s of e.keys())this.hasOwnProperty(s)&&(t.set(s,this[s]),delete this[s]);t.size>0&&(this._$Ep=t)}createRenderRoot(){let t=this.shadowRoot??this.attachShadow(this.constructor.shadowRootOptions);return D(t,this.constructor.elementStyles),t}connectedCallback(){this.renderRoot??=this.createRenderRoot(),this.enableUpdating(!0),this._$EO?.forEach(t=>t.hostConnected?.())}enableUpdating(t){}disconnectedCallback(){this._$EO?.forEach(t=>t.hostDisconnected?.())}attributeChangedCallback(t,e,s){this._$AK(t,s)}_$EC(t,e){let s=this.constructor.elementProperties.get(t),i=this.constructor._$Eu(t,s);if(i!==void 0&&s.reflect===!0){let o=(s.converter?.toAttribute!==void 0?s.converter:I).toAttribute(e,s.type);this._$Em=t,o==null?this.removeAttribute(i):this.setAttribute(i,o),this._$Em=null}}_$AK(t,e){let s=this.constructor,i=s._$Eh.get(t);if(i!==void 0&&this._$Em!==i){let o=s.getPropertyOptions(i),n=typeof o.converter=="function"?{fromAttribute:o.converter}:o.converter?.fromAttribute!==void 0?o.converter:I;this._$Em=i,this[i]=n.fromAttribute(e,o.type),this._$Em=null}}requestUpdate(t,e,s){if(t!==void 0){if(s??=this.constructor.getPropertyOptions(t),!(s.hasChanged??tt)(this[t],e))return;this.P(t,e,s)}this.isUpdatePending===!1&&(this._$ES=this._$ET())}P(t,e,s){this._$AL.has(t)||this._$AL.set(t,e),s.reflect===!0&&this._$Em!==t&&(this._$Ej??=new Set).add(t)}async _$ET(){this.isUpdatePending=!0;try{await this._$ES}catch(e){Promise.reject(e)}let t=this.scheduleUpdate();return t!=null&&await t,!this.isUpdatePending}scheduleUpdate(){return this.performUpdate()}performUpdate(){if(!this.isUpdatePending)return;if(!this.hasUpdated){if(this.renderRoot??=this.createRenderRoot(),this._$Ep){for(let[i,o]of this._$Ep)this[i]=o;this._$Ep=void 0}let s=this.constructor.elementProperties;if(s.size>0)for(let[i,o]of s)o.wrapped!==!0||this._$AL.has(i)||this[i]===void 0||this.P(i,this[i],o)}let t=!1,e=this._$AL;try{t=this.shouldUpdate(e),t?(this.willUpdate(e),this._$EO?.forEach(s=>s.hostUpdate?.()),this.update(e)):this._$EU()}catch(s){throw t=!1,this._$EU(),s}t&&this._$AE(e)}willUpdate(t){}_$AE(t){this._$EO?.forEach(e=>e.hostUpdated?.()),this.hasUpdated||(this.hasUpdated=!0,this.firstUpdated(t)),this.updated(t)}_$EU(){this._$AL=new Map,this.isUpdatePending=!1}get updateComplete(){return this.getUpdateComplete()}getUpdateComplete(){return this._$ES}shouldUpdate(t){return!0}update(t){this._$Ej&&=this._$Ej.forEach(e=>this._$EC(e,this[e])),this._$EU()}updated(t){}firstUpdated(t){}};$.elementStyles=[],$.shadowRootOptions={mode:"open"},$[x("elementProperties")]=new Map,$[x("finalized")]=new Map,At?.({ReactiveElement:$}),(M.reactiveElementVersions??=[]).push("2.0.4");var Z=globalThis,O=Z.trustedTypes,et=O?O.createPolicy("lit-html",{createHTML:r=>r}):void 0,lt="$lit$",m=`lit$${Math.random().toFixed(9).slice(2)}$`,ht="?"+m,St=`<${ht}>`,y=document,C=()=>y.createComment(""),P=r=>r===null||typeof r!="object"&&typeof r!="function",at=Array.isArray,bt=r=>at(r)||typeof r?.[Symbol.iterator]=="function",B=`[ 	
\f\r]`,w=/<(?:(!--|\/[^a-zA-Z])|(\/?[a-zA-Z][^>\s]*)|(\/?$))/g,st=/-->/g,it=/>/g,_=RegExp(`>|${B}(?:([^\\s"'>=/]+)(${B}*=${B}*(?:[^ 	
\f\r"'\`<>=]|("|')|))|$)`,"g"),rt=/'/g,ot=/"/g,ct=/^(?:script|style|textarea|title)$/i,dt=r=>(t,...e)=>({_$litType$:r,strings:t,values:e}),k=dt(1),Lt=dt(2),A=Symbol.for("lit-noChange"),c=Symbol.for("lit-nothing"),nt=new WeakMap,v=y.createTreeWalker(y,129);function pt(r,t){if(!Array.isArray(r)||!r.hasOwnProperty("raw"))throw Error("invalid template strings array");return et!==void 0?et.createHTML(t):t}var Et=(r,t)=>{let e=r.length-1,s=[],i,o=t===2?"<svg>":"",n=w;for(let p=0;p<e;p++){let l=r[p],a,d,h=-1,u=0;for(;u<l.length&&(n.lastIndex=u,d=n.exec(l),d!==null);)u=n.lastIndex,n===w?d[1]==="!--"?n=st:d[1]!==void 0?n=it:d[2]!==void 0?(ct.test(d[2])&&(i=RegExp("</"+d[2],"g")),n=_):d[3]!==void 0&&(n=_):n===_?d[0]===">"?(n=i??w,h=-1):d[1]===void 0?h=-2:(h=n.lastIndex-d[2].length,a=d[1],n=d[3]===void 0?_:d[3]==='"'?ot:rt):n===ot||n===rt?n=_:n===st||n===it?n=w:(n=_,i=void 0);let f=n===_&&r[p+1].startsWith("/>")?" ":"";o+=n===w?l+St:h>=0?(s.push(a),l.slice(0,h)+lt+l.slice(h)+m+f):l+m+(h===-2?p:f)}return[pt(r,o+(r[e]||"<?>")+(t===2?"</svg>":"")),s]},T=class r{constructor({strings:t,_$litType$:e},s){let i;this.parts=[];let o=0,n=0,p=t.length-1,l=this.parts,[a,d]=Et(t,e);if(this.el=r.createElement(a,s),v.currentNode=this.el.content,e===2){let h=this.el.content.firstChild;h.replaceWith(...h.childNodes)}for(;(i=v.nextNode())!==null&&l.length<p;){if(i.nodeType===1){if(i.hasAttributes())for(let h of i.getAttributeNames())if(h.endsWith(lt)){let u=d[n++],f=i.getAttribute(h).split(m),L=/([.?@])?(.*)/.exec(u);l.push({type:1,index:o,name:L[2],strings:f,ctor:L[1]==="."?W:L[1]==="?"?q:L[1]==="@"?K:b}),i.removeAttribute(h)}else h.startsWith(m)&&(l.push({type:6,index:o}),i.removeAttribute(h));if(ct.test(i.tagName)){let h=i.textContent.split(m),u=h.length-1;if(u>0){i.textContent=O?O.emptyScript:"";for(let f=0;f<u;f++)i.append(h[f],C()),v.nextNode(),l.push({type:2,index:++o});i.append(h[u],C())}}}else if(i.nodeType===8)if(i.data===ht)l.push({type:2,index:o});else{let h=-1;for(;(h=i.data.indexOf(m,h+1))!==-1;)l.push({type:7,index:o}),h+=m.length-1}o++}}static createElement(t,e){let s=y.createElement("template");return s.innerHTML=t,s}};function S(r,t,e=r,s){if(t===A)return t;let i=s!==void 0?e._$Co?.[s]:e._$Cl,o=P(t)?void 0:t._$litDirective$;return i?.constructor!==o&&(i?._$AO?.(!1),o===void 0?i=void 0:(i=new o(r),i._$AT(r,e,s)),s!==void 0?(e._$Co??=[])[s]=i:e._$Cl=i),i!==void 0&&(t=S(r,i._$AS(r,t.values),i,s)),t}var V=class{constructor(t,e){this._$AV=[],this._$AN=void 0,this._$AD=t,this._$AM=e}get parentNode(){return this._$AM.parentNode}get _$AU(){return this._$AM._$AU}u(t){let{el:{content:e},parts:s}=this._$AD,i=(t?.creationScope??y).importNode(e,!0);v.currentNode=i;let o=v.nextNode(),n=0,p=0,l=s[0];for(;l!==void 0;){if(n===l.index){let a;l.type===2?a=new U(o,o.nextSibling,this,t):l.type===1?a=new l.ctor(o,l.name,l.strings,this,t):l.type===6&&(a=new J(o,this,t)),this._$AV.push(a),l=s[++p]}n!==l?.index&&(o=v.nextNode(),n++)}return v.currentNode=y,i}p(t){let e=0;for(let s of this._$AV)s!==void 0&&(s.strings!==void 0?(s._$AI(t,s,e),e+=s.strings.length-2):s._$AI(t[e])),e++}},U=class r{get _$AU(){return this._$AM?._$AU??this._$Cv}constructor(t,e,s,i){this.type=2,this._$AH=c,this._$AN=void 0,this._$AA=t,this._$AB=e,this._$AM=s,this.options=i,this._$Cv=i?.isConnected??!0}get parentNode(){let t=this._$AA.parentNode,e=this._$AM;return e!==void 0&&t?.nodeType===11&&(t=e.parentNode),t}get startNode(){return this._$AA}get endNode(){return this._$AB}_$AI(t,e=this){t=S(this,t,e),P(t)?t===c||t==null||t===""?(this._$AH!==c&&this._$AR(),this._$AH=c):t!==this._$AH&&t!==A&&this._(t):t._$litType$!==void 0?this.$(t):t.nodeType!==void 0?this.T(t):bt(t)?this.k(t):this._(t)}S(t){return this._$AA.parentNode.insertBefore(t,this._$AB)}T(t){this._$AH!==t&&(this._$AR(),this._$AH=this.S(t))}_(t){this._$AH!==c&&P(this._$AH)?this._$AA.nextSibling.data=t:this.T(y.createTextNode(t)),this._$AH=t}$(t){let{values:e,_$litType$:s}=t,i=typeof s=="number"?this._$AC(t):(s.el===void 0&&(s.el=T.createElement(pt(s.h,s.h[0]),this.options)),s);if(this._$AH?._$AD===i)this._$AH.p(e);else{let o=new V(i,this),n=o.u(this.options);o.p(e),this.T(n),this._$AH=o}}_$AC(t){let e=nt.get(t.strings);return e===void 0&&nt.set(t.strings,e=new T(t)),e}k(t){at(this._$AH)||(this._$AH=[],this._$AR());let e=this._$AH,s,i=0;for(let o of t)i===e.length?e.push(s=new r(this.S(C()),this.S(C()),this,this.options)):s=e[i],s._$AI(o),i++;i<e.length&&(this._$AR(s&&s._$AB.nextSibling,i),e.length=i)}_$AR(t=this._$AA.nextSibling,e){for(this._$AP?.(!1,!0,e);t&&t!==this._$AB;){let s=t.nextSibling;t.remove(),t=s}}setConnected(t){this._$AM===void 0&&(this._$Cv=t,this._$AP?.(t))}},b=class{get tagName(){return this.element.tagName}get _$AU(){return this._$AM._$AU}constructor(t,e,s,i,o){this.type=1,this._$AH=c,this._$AN=void 0,this.element=t,this.name=e,this._$AM=i,this.options=o,s.length>2||s[0]!==""||s[1]!==""?(this._$AH=Array(s.length-1).fill(new String),this.strings=s):this._$AH=c}_$AI(t,e=this,s,i){let o=this.strings,n=!1;if(o===void 0)t=S(this,t,e,0),n=!P(t)||t!==this._$AH&&t!==A,n&&(this._$AH=t);else{let p=t,l,a;for(t=o[0],l=0;l<o.length-1;l++)a=S(this,p[s+l],e,l),a===A&&(a=this._$AH[l]),n||=!P(a)||a!==this._$AH[l],a===c?t=c:t!==c&&(t+=(a??"")+o[l+1]),this._$AH[l]=a}n&&!i&&this.j(t)}j(t){t===c?this.element.removeAttribute(this.name):this.element.setAttribute(this.name,t??"")}},W=class extends b{constructor(){super(...arguments),this.type=3}j(t){this.element[this.name]=t===c?void 0:t}},q=class extends b{constructor(){super(...arguments),this.type=4}j(t){this.element.toggleAttribute(this.name,!!t&&t!==c)}},K=class extends b{constructor(t,e,s,i,o){super(t,e,s,i,o),this.type=5}_$AI(t,e=this){if((t=S(this,t,e,0)??c)===A)return;let s=this._$AH,i=t===c&&s!==c||t.capture!==s.capture||t.once!==s.once||t.passive!==s.passive,o=t!==c&&(s===c||i);i&&this.element.removeEventListener(this.name,this,s),o&&this.element.addEventListener(this.name,this,t),this._$AH=t}handleEvent(t){typeof this._$AH=="function"?this._$AH.call(this.options?.host??this.element,t):this._$AH.handleEvent(t)}},J=class{constructor(t,e,s){this.element=t,this.type=6,this._$AN=void 0,this._$AM=e,this.options=s}get _$AU(){return this._$AM._$AU}_$AI(t){S(this,t)}};var xt=Z.litHtmlPolyfillSupport;xt?.(T,U),(Z.litHtmlVersions??=[]).push("3.1.3");var ut=(r,t,e)=>{let s=e?.renderBefore??t,i=s._$litPart$;if(i===void 0){let o=e?.renderBefore??null;s._$litPart$=i=new U(t.insertBefore(C(),o),o,void 0,e??{})}return i._$AI(r),i};var g=class extends ${constructor(){super(...arguments),this.renderOptions={host:this},this._$Do=void 0}createRenderRoot(){let t=super.createRenderRoot();return this.renderOptions.renderBefore??=t.firstChild,t}update(t){let e=this.render();this.hasUpdated||(this.renderOptions.isConnected=this.isConnected),super.update(t),this._$Do=ut(e,this.renderRoot,this.renderOptions)}connectedCallback(){super.connectedCallback(),this._$Do?.setConnected(!0)}disconnectedCallback(){super.disconnectedCallback(),this._$Do?.setConnected(!1)}render(){return A}};g._$litElement$=!0,g.finalized=!0,globalThis.litElementHydrateSupport?.({LitElement:g});var wt=globalThis.litElementPolyfillSupport;wt?.({LitElement:g});(globalThis.litElementVersions??=[]).push("4.0.5");var z=class extends g{hiddenClass="hidden";timeoutLength=300;list=null;searchField=null;closeTimeout=null;constructor(){super()}static properties={jsonData:{type:Array,attribute:"json-data"},displayField:{type:String,attribute:"display-field"},valueField:{type:String,attribute:"value-field"},value:{type:Array},open:{type:Boolean}};onChangeValue(t){this.open&&this.getSearchField().focus();let e=parseInt(t.currentTarget.dataset.value);this.value.includes(e)?this.value.splice(this.value.indexOf(e),1):this.value.push(e),this.setAttribute("value",JSON.stringify(this.value))}onChevronClick(){this.getSearchField().focus()}onInput(t){let e=t.currentTarget.value.toLowerCase();this.getList().querySelectorAll(".multi-select-select-list-item").forEach(s=>{s.innerHTML.toLowerCase().indexOf(e)>-1?s.classList.remove(this.hiddenClass):s.classList.add(this.hiddenClass)})}onFocusInput(){clearTimeout(this.closeTimeout),this.showList()}onBlur(){this.closeTimeout=setTimeout(()=>{this.hideList()},this.timeoutLength)}onMouseEnter(t){t.currentTarget.addEventListener("wheel",this.onScroll.bind(this,t.currentTarget),{passive:!0})}onMouseLeave(t){t.currentTarget.removeEventListener("wheel",this.onScroll.bind(this,t.currentTarget))}onScroll(t,e){t.scrollLeft+=e.deltaY/10}isSelected(t){return this.value.includes(t)}getListItem(t){let e;return this.jsonData.some(s=>{if(s[this.valueField]===t)return e=s,!0}),e}showList(){this.setAttribute("open","true"),this.adjustSize()}adjustSize(){this.getList().style=`width: ${this.getSearchField().clientWidth}px;`}hideList(){this.removeAttribute("open")}getList(){return this.list===null&&(this.list=this.renderRoot.querySelector(".multi-select-select-list")),this.list}getSearchField(){return this.searchField===null&&(this.searchField=this.renderRoot.querySelector(".multi-select-search-field-input")),this.searchField}render(){return k`
            <div class="multi-select-container">
                <div class="multi-select-wrapper">
                    <div class="multi-select-selected-container" @mouseenter="${this.onMouseEnter}"
                         @mouseleave="${this.onMouseLeave}">
                        ${this.value.map(t=>{let e=this.getListItem(t);return k`
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
                        ${this.jsonData.map(t=>{let e=this.isSelected(t[this.valueField])?" selected":"";return k`
                                        <div class="multi-select-select-list-item${e}"
                                             @click=${this.onChangeValue}
                                             data-value="${t[this.valueField]}">${t[this.displayField]}
                                        </div>`})}
                    </div>
                </div>
            </div>`}static styles=j`
        .hidden {
            display: none;
            height: 0;
        }

        .multi-select-container {
            font-family: sans-serif;
            width: 100%;
        }

        .multi-select-wrapper {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 5px;
        }

        .multi-select-select-list {
            position: absolute;
            max-height: 350px;
            overflow-y: scroll;
            overflow-x: hidden;
            padding: 0;
            border-radius: 0;
            z-index: 999;
            width: 100%;
            border-left: 1px solid lightgray;
            border-right: 1px solid lightgray;
            border-bottom: 1px solid lightgray;
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
            border: 1px solid #dee2e6;
            border-radius: 5px;
            color: #212529;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;

            &:focus {
                border-color: #86b7fe;
                outline: 0;
                box-shadow: 0 0 0 .25rem rgba(13, 110, 253, .25);
            }
        }

        .multi-select-select-list-item {
            background: #ffffff;
            padding: 5px 5px;
            cursor: pointer;

            &:hover {
                background: #dee2e6;
            }

            &.selected {
                background: #86b7fe;

                &:hover {
                    background: #dee2e6;
                }
            }
        }

        .multi-select-selected-container {
            overflow-x: hidden;
            display: flex;
            height: 38px;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            padding: 3px;
        }

        .multi-select-select-badge {
            display: inline-block;
            text-wrap: nowrap;
            margin: 0 5px 3px 0;
            background: #fdfdfd;
            vertical-align: center;
            padding: .175rem .5rem .175rem .75rem;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            color: #212529;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
        }

        .cross {
            cursor: pointer;
            color: #212529;
            
            &:hover {
                color: #86b7fe;
            }
        }
    `};console.log("Register MultiSelect");window.customElements.define("multi-select",z);})();
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
