if(!self.define){let n,i={};const e=(e,o)=>(e=new URL(e+".js",o).href,i[e]||new Promise((i=>{if("document"in self){const n=document.createElement("script");n.src=e,n.onload=i,document.head.appendChild(n)}else n=e,importScripts(e),i()})).then((()=>{let n=i[e];if(!n)throw new Error(`Module ${e} didn’t register its module`);return n})));self.define=(o,s)=>{const f=n||("document"in self?document.currentScript.src:"")||location.href;if(i[f])return;let r={};const l=n=>e(n,f),c={module:{uri:f},exports:r,require:l};i[f]=Promise.all(o.map((n=>c[n]||l(n)))).then((n=>(s(...n),r)))}}define(["./workbox-fbf980fb"],(function(n){"use strict";n.setCacheNameDetails({prefix:"consumofront"}),self.addEventListener("message",(n=>{n.data&&"SKIP_WAITING"===n.data.type&&self.skipWaiting()})),n.precacheAndRoute([{url:"css/269.9ff08940.css",revision:null},{url:"css/app.b85d1c62.css",revision:null},{url:"css/vendor.55fa71d0.css",revision:null},{url:"favicon.ico",revision:"f4facfeaed834544d622544acfbb7722"},{url:"fonts/KFOkCnqEu92Fr1MmgVxIIzQ.9391e6e2.woff",revision:null},{url:"fonts/KFOlCnqEu92Fr1MmEU9fBBc-.ddd11dab.woff",revision:null},{url:"fonts/KFOlCnqEu92Fr1MmSU5fBBc-.877b9231.woff",revision:null},{url:"fonts/KFOlCnqEu92Fr1MmWUlfBBc-.0344cc3c.woff",revision:null},{url:"fonts/KFOlCnqEu92Fr1MmYUtfBBc-.b555d228.woff",revision:null},{url:"fonts/KFOmCnqEu92Fr1Mu4mxM.9b78ea3b.woff",revision:null},{url:"fonts/flUhRq6tzZclQEJ-Vdg-IuiaDsNa.53e02ee0.woff",revision:null},{url:"fonts/flUhRq6tzZclQEJ-Vdg-IuiaDsNcIhQ8tQ.6a798765.woff2",revision:null},{url:"fonts/gok-H7zzDkdnRel8-DQ6KAXJ69wP1tGnf4ZGhUcY.8e6315a5.woff",revision:null},{url:"fonts/gok-H7zzDkdnRel8-DQ6KAXJ69wP1tGnf4ZGhUcel5euIg.3f63ea8f.woff2",revision:null},{url:"icons/apple-icon-120x120.png",revision:"d082235f6e6d2109e84e397f66fa868d"},{url:"icons/apple-icon-152x152.png",revision:"3c728ce3e709b7395be487becf76283a"},{url:"icons/apple-icon-167x167.png",revision:"3fec89672a18e4b402ede58646917c2d"},{url:"icons/apple-icon-180x180.png",revision:"aa47843bd47f34b7ca4b99f65dd25955"},{url:"icons/favicon-128x128.png",revision:"ab92df0270f054ca388127c9703a4911"},{url:"icons/favicon-16x16.png",revision:"e4b046d41e08e6fa06626d6410ab381d"},{url:"icons/favicon-32x32.png",revision:"410858b01fa6d3d66b7bf21447c5f1fc"},{url:"icons/favicon-96x96.png",revision:"db2bde7f824fb4057ffd1c42f6ed756e"},{url:"icons/icon-128x128.png",revision:"ab92df0270f054ca388127c9703a4911"},{url:"icons/icon-192x192.png",revision:"7659f0d3e9602e71811f8b7cf2ce0e8e"},{url:"icons/icon-256x256.png",revision:"cf5ad3498fb6fda43bdafd3c6ce9b824"},{url:"icons/icon-384x384.png",revision:"fdfc1b3612b6833a27a7b260c9990247"},{url:"icons/icon-512x512.png",revision:"2c2dc987945806196bd18cb6028d8bf4"},{url:"icons/ms-icon-144x144.png",revision:"8de1b0e67a62b881cd22d935f102a0e6"},{url:"icons/safari-pinned-tab.svg",revision:"3e4c3730b00c89591de9505efb73afd3"},{url:"img/logo.31fa2f46.png",revision:null},{url:"img/mountains.12fab592.jpg",revision:null},{url:"img/natividad.jpeg",revision:"317aa774f49f7fdb1aec804a3b616868"},{url:"img/natividad.png",revision:"c0b6ca212b012aa9d2d5727ef5b42035"},{url:"img/natividad2.jpeg",revision:"9f18f7ba411771fbc59327b30aaf5530"},{url:"img/resta.JPEG",revision:"212114e7d66e1d0bc25e0b87da4f20d1"},{url:"img/suma.JPEG",revision:"005084f6b2765ea78b97a6df8f08679d"},{url:"index.html",revision:"0386c07d90173d09c82c6b1d8f61a8e9"},{url:"js/193.a01bb124.js",revision:null},{url:"js/269.8cb8cc16.js",revision:null},{url:"js/app.da21887b.js",revision:null},{url:"js/vendor.4bfe8013.js",revision:null},{url:"manifest.json",revision:"0639b7c6701a21a16382385de9427c6a"}],{}),n.registerRoute(new n.NavigationRoute(n.createHandlerBoundToURL("index.html"),{denylist:[/service-worker\.js$/,/workbox-(.)*\.js$/]}))}));
