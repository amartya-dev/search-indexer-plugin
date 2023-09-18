!function(){"use strict";var e={n:function(t){var a=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(a,{a:a}),a},d:function(t,a){for(var n in a)e.o(a,n)&&!e.o(t,n)&&Object.defineProperty(t,n,{enumerable:!0,get:a[n]})},o:function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r:function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})}},t={};e.r(t);var a=window.wp.element,n=window.wp.domReady,l=e.n(n);const i=({id:e,label:t,helperText:n,value:l,onChange:i,error:c=!1})=>(0,a.createElement)("div",null,(0,a.createElement)("label",{htmlFor:e,className:"text-input-label"},t),(0,a.createElement)("input",{id:e,type:"text",className:"text-input",placeholder:" ",value:l,onChange:i}),(0,a.createElement)("p",{className:"text-md "+(c?"text-red-600":"")},n));var c,s=window.React;function r(){return r=Object.assign?Object.assign.bind():function(e){for(var t=1;t<arguments.length;t++){var a=arguments[t];for(var n in a)Object.prototype.hasOwnProperty.call(a,n)&&(e[n]=a[n])}return e},r.apply(this,arguments)}var o,m=function(e){return s.createElement("svg",r({xmlns:"http://www.w3.org/2000/svg",width:24,height:24},e),c||(c=s.createElement("path",{fill:"currentColor",d:"M12 6C5.188 6 1 12 1 12s4.188 6 11 6 11-6 11-6-4.188-6-11-6zm0 10c-3.943 0-6.926-2.484-8.379-4 1.04-1.085 2.862-2.657 5.254-3.469A3.96 3.96 0 0 0 8 11a4 4 0 0 0 8 0 3.96 3.96 0 0 0-.875-2.469c2.393.812 4.216 2.385 5.254 3.469-1.455 1.518-4.437 4-8.379 4z"})))};function d(){return d=Object.assign?Object.assign.bind():function(e){for(var t=1;t<arguments.length;t++){var a=arguments[t];for(var n in a)Object.prototype.hasOwnProperty.call(a,n)&&(e[n]=a[n])}return e},d.apply(this,arguments)}var u=function(e){return s.createElement("svg",d({xmlns:"http://www.w3.org/2000/svg",width:24,height:24},e),o||(o=s.createElement("path",{fill:"currentColor",d:"M1 12s4.188-6 11-6c.947 0 1.839.121 2.678.322L8.36 12.64A3.96 3.96 0 0 1 8 11c0-.937.335-1.787.875-2.469-2.392.812-4.214 2.385-5.254 3.469a14.868 14.868 0 0 0 2.98 2.398l-1.453 1.453C2.497 14.13 1 12 1 12zm22 0s-4.188 6-11 6c-.946 0-1.836-.124-2.676-.323L5 22l-1.5-1.5 17-17L22 5l-3.147 3.147C21.501 9.869 23 12 23 12zm-2.615.006a14.83 14.83 0 0 0-2.987-2.403L16 11a4 4 0 0 1-4 4l-.947.947c.31.031.624.053.947.053 3.978 0 6.943-2.478 8.385-3.994z"})))};const p=({id:e,label:t,helperText:n,value:l,onChange:i,error:c=!1})=>{const[s,r]=(0,a.useState)(!1);return(0,a.createElement)("div",null,(0,a.createElement)("label",{htmlFor:e,className:"text-input-label"},t),(0,a.createElement)("div",{className:"relative"},(0,a.createElement)("input",{id:e,className:"text-input",placeholder:" ",value:l,onChange:i,type:s?"text":"password"}),(0,a.createElement)("button",{className:"text-black absolute right-2.5 bottom-[0.2rem] font-medium rounded-lg text-sm px-4 py-2",onClick:()=>{r(!s)}},s?(0,a.createElement)(u,null):(0,a.createElement)(m,null))),(0,a.createElement)("p",{className:"text-md "+(c?"text-red-600":"")},n))},g=({loading:e,children:t,onSubmit:n,id:l})=>e?(0,a.createElement)("button",{type:"button",id:l,className:"action-button-loading",disabled:!0},(0,a.createElement)("svg",{className:"w-5 h-5 mr-3 -ml-1 text-white animate-spin",xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24"},(0,a.createElement)("circle",{className:"opacity-25",cx:"12",cy:"12",r:"10",stroke:"currentColor",strokeWidth:"4"}),(0,a.createElement)("path",{className:"opacity-75",fill:"currentColor",d:"M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"})),t):(0,a.createElement)("button",{id:l,className:"action-button",onClick:n},t),x=async({apiCallFunc:e,apiCallParams:t})=>{try{return await e(t)}catch(e){return{error:e,failed:!0}}};var h=window.wp.apiFetch,E=e.n(h);const y="/search-indexer-plugin/v1".concat("/index"),v=()=>({index:{getAllIndexes:async e=>await E()({path:y.concat(`/list/${e}`),method:"GET"}),getIndexSettings:async e=>await E()({path:y.concat(`/settings/${e}`),method:"GET"}),saveIndexSettings:async e=>await E()({path:y.concat("/save"),method:"POST",data:e}),getDefaultIndex:async()=>await E()({path:y.concat("/default"),method:"GET"}),setDefaultIndex:async e=>await E()({path:y.concat("/default"),method:"POST",data:{index_name:e}}),reIndex:async e=>await E()({path:y.concat("/re-index"),method:"POST",data:{index_name:e}})}}),w="typesense",b=()=>{const[e,t]=(0,a.useState)({host:"",port:"",protocol:"",apiKey:""}),[n,l]=(0,a.useState)(""),[c,s]=(0,a.useState)(!1),[r,o]=(0,a.useState)(!1),[m,d]=(0,a.useState)(!1),[u,h]=(0,a.useState)(!1),[E,y]=(0,a.useState)([]),b=async()=>{const e=await x({apiCallFunc:v().index.getDefaultIndex});h(e.default&&e.default===w)};return(0,a.useEffect)((()=>{(async()=>{const e=await x({apiCallFunc:v().index.getIndexSettings,apiCallParams:w});e.failed||0===Object.keys(e.settings).length||(t(e.settings.connection),l(e.settings.index_name))})(),b(),(async()=>{const e=await x({apiCallFunc:v().index.getAllIndexes,apiCallParams:w});e.failed||y(e.indexes)})()}),[]),(0,a.createElement)("div",{className:"flex justify-center items-center mt-8"},(0,a.createElement)("div",{className:"w-5/6"},(0,a.createElement)("h2",{className:"text-2xl mb-4"},"Connection Settings"),(0,a.createElement)("div",{className:"grid gap-6 mb-4 grid-cols-3"},(0,a.createElement)(i,{id:"typesense-host-input",label:"Host",value:e.host,onChange:a=>{t({...e,host:a.target.value})}}),(0,a.createElement)(i,{id:"typesense-port-input",label:"Port",value:e.port,onChange:a=>{t({...e,port:a.target.value})}}),(0,a.createElement)(i,{id:"typesense-protocol-input",label:"Protocol",value:e.protocol,onChange:a=>{t({...e,protocol:a.target.value})}})),(0,a.createElement)("div",{className:"mb-6"},(0,a.createElement)(p,{id:"typesense-api-key-input",label:"Api Key",value:e.apiKey,onChange:a=>{t({...e,apiKey:a.target.value})}})),(0,a.createElement)("h2",{className:"text-2xl mb-4"},"Index specific settings"),(0,a.createElement)(i,{id:"typesense-index-input",label:"Index Name",value:n,onChange:e=>{l(e.target.value)}}),E.length>=0&&(0,a.createElement)(a.Fragment,null,(0,a.createElement)("h2",{className:"text-2xl mt-4"},"Available Indices"),(0,a.createElement)("ul",{className:"list-disc ml-4"},E.map((e=>(0,a.createElement)("li",{className:"text-md",key:e.name},"Name: ".concat(e.name," Documents: ",e.num_documents)))))),(0,a.createElement)("div",{className:"mt-8"},(0,a.createElement)(g,{loading:c,onSubmit:async()=>{s(!0),await x({apiCallFunc:v().index.saveIndexSettings,apiCallParams:{index_name:w,settings:{index_name:n,connection:e}}}),s(!1),window.location.reload()}},"Save"),(0,a.createElement)(g,{loading:m,onSubmit:async()=>{d(!0),await x({apiCallFunc:v().index.reIndex,apiCallParams:w}),d(!1),window.location.reload()}},"Re-Index"),!u&&(0,a.createElement)(g,{loading:r,onSubmit:async()=>{o(!0),await x({apiCallFunc:v().index.setDefaultIndex,apiCallParams:w}),o(!1),await b(),window.location.reload()}},"Make Default"))))},f="meilisearch",C=()=>{const[e,t]=(0,a.useState)({host:"",apiKey:""}),[n,l]=(0,a.useState)(""),[c,s]=(0,a.useState)(!1),[r,o]=(0,a.useState)(!1),[m,d]=(0,a.useState)(!1),[u,h]=(0,a.useState)(!1),[E,y]=(0,a.useState)([]),w=async()=>{const e=await x({apiCallFunc:v().index.getDefaultIndex});h(e.default&&e.default===f)};return(0,a.useEffect)((()=>{(async()=>{const e=await x({apiCallFunc:v().index.getIndexSettings,apiCallParams:f});e.failed||0===Object.keys(e.settings).length||(t(e.settings.connection),l(e.settings.index_name))})(),w(),(async()=>{const e=await x({apiCallFunc:v().index.getAllIndexes,apiCallParams:f});e.failed||y(e.indexes)})()}),[]),(0,a.createElement)("div",{className:"flex justify-center items-center mt-8"},(0,a.createElement)("div",{className:"w-5/6"},(0,a.createElement)("h2",{className:"text-2xl mb-4"},"Connection Settings"),(0,a.createElement)("div",{className:"mb-6"},(0,a.createElement)(i,{id:"typesense-host-input",label:"Host",value:e.host,onChange:a=>{t({...e,host:a.target.value})}})),(0,a.createElement)("div",{className:"mb-6"},(0,a.createElement)(p,{id:"typesense-api-key-input",label:"Api Key",value:e.apiKey,onChange:a=>{t({...e,apiKey:a.target.value})}})),(0,a.createElement)("h2",{className:"text-2xl mb-4"},"Index specific settings"),(0,a.createElement)(i,{id:"typesense-index-input",label:"Index Name",value:n,onChange:e=>{l(e.target.value)}}),E.length>=0&&(0,a.createElement)(a.Fragment,null,(0,a.createElement)("h2",{className:"text-2xl mt-4"},"Available Indices"),(0,a.createElement)("ul",{className:"list-disc ml-4"},E.map((e=>(0,a.createElement)("li",{className:"text-md",key:e.uid},"Name: ".concat(e.uid," Documents: ",e.num_documents)))))),(0,a.createElement)("div",{className:"mt-8"},(0,a.createElement)(g,{loading:c,onSubmit:async()=>{s(!0),await x({apiCallFunc:v().index.saveIndexSettings,apiCallParams:{index_name:f,settings:{index_name:n,connection:e}}}),s(!1),window.location.reload()}},"Save"),(0,a.createElement)(g,{loading:m,onSubmit:async()=>{d(!0),await x({apiCallFunc:v().index.reIndex,apiCallParams:f}),d(!1),window.location.reload()}},"Re-Index"),!u&&(0,a.createElement)(g,{loading:r,onSubmit:async()=>{o(!0),await x({apiCallFunc:v().index.setDefaultIndex,apiCallParams:f}),o(!1),await w(),window.location.reload()}},"Make Default"))))},S=()=>{const e={typesense:{name:"Type Sense",component:(0,a.createElement)(b,null)},meili:{name:"Meili Search",component:(0,a.createElement)(C,null)},algolia:{name:"Algolia",component:(0,a.createElement)(a.Fragment,null)}},[t,n]=(0,a.useState)("typesense");return(0,a.createElement)(a.Fragment,null,(0,a.createElement)("ul",{className:"tab-container"},Object.keys(e).map((l=>(0,a.createElement)("li",{key:l,className:l===t?"tab-item-active":"tab-item"},(0,a.createElement)("button",{onClick:()=>{n(l)}},e[l].name))))),e[t].component)},N=()=>(0,a.createElement)("div",{className:"m-2"},(0,a.createElement)("h2",{className:"text-3xl pt-8 ml-3 mb-4"},"Configure Indexes"),(0,a.createElement)(S,null));var I=function(){return(0,a.createElement)(N,null)};l()((()=>{const e=document.getElementById("sip-app");null!==e&&(void 0!==a.createRoot?(0,a.createRoot)(e).render((0,a.createElement)(I,null)):void 0!==a.render&&(0,a.render)((0,a.createElement)(I,null),e))})),(window.SiteIndexerPlugin=window.SiteIndexerPlugin||{})["search-indexer-plugin"]=t}();