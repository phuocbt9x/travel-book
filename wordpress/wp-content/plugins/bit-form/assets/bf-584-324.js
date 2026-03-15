import{bl as re,bi as ut,r as dt}from"./main-492.js";import{a as Xe}from"./bf-536-181.js";var fr={exports:{}};(function(M,b){ace.define("ace/mode/css_highlight_rules",["require","exports","module","ace/lib/oop","ace/lib/lang","ace/mode/text_highlight_rules"],function(n,p,A){var e=n("../lib/oop");n("../lib/lang");var a=n("./text_highlight_rules").TextHighlightRules,h=p.supportType="align-content|align-items|align-self|all|animation|animation-delay|animation-direction|animation-duration|animation-fill-mode|animation-iteration-count|animation-name|animation-play-state|animation-timing-function|backface-visibility|background|background-attachment|background-blend-mode|background-clip|background-color|background-image|background-origin|background-position|background-repeat|background-size|border|border-bottom|border-bottom-color|border-bottom-left-radius|border-bottom-right-radius|border-bottom-style|border-bottom-width|border-collapse|border-color|border-image|border-image-outset|border-image-repeat|border-image-slice|border-image-source|border-image-width|border-left|border-left-color|border-left-style|border-left-width|border-radius|border-right|border-right-color|border-right-style|border-right-width|border-spacing|border-style|border-top|border-top-color|border-top-left-radius|border-top-right-radius|border-top-style|border-top-width|border-width|bottom|box-shadow|box-sizing|caption-side|clear|clip|color|column-count|column-fill|column-gap|column-rule|column-rule-color|column-rule-style|column-rule-width|column-span|column-width|columns|content|counter-increment|counter-reset|cursor|direction|display|empty-cells|filter|flex|flex-basis|flex-direction|flex-flow|flex-grow|flex-shrink|flex-wrap|float|font|font-family|font-size|font-size-adjust|font-stretch|font-style|font-variant|font-weight|hanging-punctuation|height|justify-content|left|letter-spacing|line-height|list-style|list-style-image|list-style-position|list-style-type|margin|margin-bottom|margin-left|margin-right|margin-top|max-height|max-width|max-zoom|min-height|min-width|min-zoom|nav-down|nav-index|nav-left|nav-right|nav-up|opacity|order|outline|outline-color|outline-offset|outline-style|outline-width|overflow|overflow-x|overflow-y|padding|padding-bottom|padding-left|padding-right|padding-top|page-break-after|page-break-before|page-break-inside|perspective|perspective-origin|position|quotes|resize|right|tab-size|table-layout|text-align|text-align-last|text-decoration|text-decoration-color|text-decoration-line|text-decoration-style|text-indent|text-justify|text-overflow|text-shadow|text-transform|top|transform|transform-origin|transform-style|transition|transition-delay|transition-duration|transition-property|transition-timing-function|unicode-bidi|user-select|user-zoom|vertical-align|visibility|white-space|width|word-break|word-spacing|word-wrap|z-index",g=p.supportFunction="rgb|rgba|url|attr|counter|counters",l=p.supportConstant="absolute|after-edge|after|all-scroll|all|alphabetic|always|antialiased|armenian|auto|avoid-column|avoid-page|avoid|balance|baseline|before-edge|before|below|bidi-override|block-line-height|block|bold|bolder|border-box|both|bottom|box|break-all|break-word|capitalize|caps-height|caption|center|central|char|circle|cjk-ideographic|clone|close-quote|col-resize|collapse|column|consider-shifts|contain|content-box|cover|crosshair|cubic-bezier|dashed|decimal-leading-zero|decimal|default|disabled|disc|disregard-shifts|distribute-all-lines|distribute-letter|distribute-space|distribute|dotted|double|e-resize|ease-in|ease-in-out|ease-out|ease|ellipsis|end|exclude-ruby|flex-end|flex-start|fill|fixed|georgian|glyphs|grid-height|groove|hand|hanging|hebrew|help|hidden|hiragana-iroha|hiragana|horizontal|icon|ideograph-alpha|ideograph-numeric|ideograph-parenthesis|ideograph-space|ideographic|inactive|include-ruby|inherit|initial|inline-block|inline-box|inline-line-height|inline-table|inline|inset|inside|inter-ideograph|inter-word|invert|italic|justify|katakana-iroha|katakana|keep-all|last|left|lighter|line-edge|line-through|line|linear|list-item|local|loose|lower-alpha|lower-greek|lower-latin|lower-roman|lowercase|lr-tb|ltr|mathematical|max-height|max-size|medium|menu|message-box|middle|move|n-resize|ne-resize|newspaper|no-change|no-close-quote|no-drop|no-open-quote|no-repeat|none|normal|not-allowed|nowrap|nw-resize|oblique|open-quote|outset|outside|overline|padding-box|page|pointer|pre-line|pre-wrap|pre|preserve-3d|progress|relative|repeat-x|repeat-y|repeat|replaced|reset-size|ridge|right|round|row-resize|rtl|s-resize|scroll|se-resize|separate|slice|small-caps|small-caption|solid|space|square|start|static|status-bar|step-end|step-start|steps|stretch|strict|sub|super|sw-resize|table-caption|table-cell|table-column-group|table-column|table-footer-group|table-header-group|table-row-group|table-row|table|tb-rl|text-after-edge|text-before-edge|text-bottom|text-size|text-top|text|thick|thin|transparent|underline|upper-alpha|upper-latin|upper-roman|uppercase|use-script|vertical-ideographic|vertical-text|visible|w-resize|wait|whitespace|z-index|zero|zoom",s=p.supportConstantColor="aliceblue|antiquewhite|aqua|aquamarine|azure|beige|bisque|black|blanchedalmond|blue|blueviolet|brown|burlywood|cadetblue|chartreuse|chocolate|coral|cornflowerblue|cornsilk|crimson|cyan|darkblue|darkcyan|darkgoldenrod|darkgray|darkgreen|darkgrey|darkkhaki|darkmagenta|darkolivegreen|darkorange|darkorchid|darkred|darksalmon|darkseagreen|darkslateblue|darkslategray|darkslategrey|darkturquoise|darkviolet|deeppink|deepskyblue|dimgray|dimgrey|dodgerblue|firebrick|floralwhite|forestgreen|fuchsia|gainsboro|ghostwhite|gold|goldenrod|gray|green|greenyellow|grey|honeydew|hotpink|indianred|indigo|ivory|khaki|lavender|lavenderblush|lawngreen|lemonchiffon|lightblue|lightcoral|lightcyan|lightgoldenrodyellow|lightgray|lightgreen|lightgrey|lightpink|lightsalmon|lightseagreen|lightskyblue|lightslategray|lightslategrey|lightsteelblue|lightyellow|lime|limegreen|linen|magenta|maroon|mediumaquamarine|mediumblue|mediumorchid|mediumpurple|mediumseagreen|mediumslateblue|mediumspringgreen|mediumturquoise|mediumvioletred|midnightblue|mintcream|mistyrose|moccasin|navajowhite|navy|oldlace|olive|olivedrab|orange|orangered|orchid|palegoldenrod|palegreen|paleturquoise|palevioletred|papayawhip|peachpuff|peru|pink|plum|powderblue|purple|rebeccapurple|red|rosybrown|royalblue|saddlebrown|salmon|sandybrown|seagreen|seashell|sienna|silver|skyblue|slateblue|slategray|slategrey|snow|springgreen|steelblue|tan|teal|thistle|tomato|turquoise|violet|wheat|white|whitesmoke|yellow|yellowgreen",i=p.supportConstantFonts="arial|century|comic|courier|cursive|fantasy|garamond|georgia|helvetica|impact|lucida|symbol|system|tahoma|times|trebuchet|utopia|verdana|webdings|sans-serif|serif|monospace",d=p.numRe="\\-?(?:(?:[0-9]+(?:\\.[0-9]+)?)|(?:\\.[0-9]+))",f=p.pseudoElements="(\\:+)\\b(after|before|first-letter|first-line|moz-selection|selection)\\b",$=p.pseudoClasses="(:)\\b(active|checked|disabled|empty|enabled|first-child|first-of-type|focus|hover|indeterminate|invalid|last-child|last-of-type|link|not|nth-child|nth-last-child|nth-last-of-type|nth-of-type|only-child|only-of-type|required|root|target|valid|visited)\\b",S=function(){var m=this.createKeywordMapper({"support.function":g,"support.constant":l,"support.type":h,"support.constant.color":s,"support.constant.fonts":i},"text",!0);this.$rules={start:[{include:["strings","url","comments"]},{token:"paren.lparen",regex:"\\{",next:"ruleset"},{token:"paren.rparen",regex:"\\}"},{token:"string",regex:"@(?!viewport)",next:"media"},{token:"keyword",regex:"#[a-z0-9-_]+"},{token:"keyword",regex:"%"},{token:"variable",regex:"\\.[a-z0-9-_]+"},{token:"string",regex:":[a-z0-9-_]+"},{token:"constant.numeric",regex:d},{token:"constant",regex:"[a-z0-9-_]+"},{caseInsensitive:!0}],media:[{include:["strings","url","comments"]},{token:"paren.lparen",regex:"\\{",next:"start"},{token:"paren.rparen",regex:"\\}",next:"start"},{token:"string",regex:";",next:"start"},{token:"keyword",regex:"(?:media|supports|document|charset|import|namespace|media|supports|document|page|font|keyframes|viewport|counter-style|font-feature-values|swash|ornaments|annotation|stylistic|styleset|character-variant)"}],comments:[{token:"comment",regex:"\\/\\*",push:[{token:"comment",regex:"\\*\\/",next:"pop"},{defaultToken:"comment"}]}],ruleset:[{regex:"-(webkit|ms|moz|o)-",token:"text"},{token:"punctuation.operator",regex:"[:;]"},{token:"paren.rparen",regex:"\\}",next:"start"},{include:["strings","url","comments"]},{token:["constant.numeric","keyword"],regex:"("+d+")(ch|cm|deg|em|ex|fr|gd|grad|Hz|in|kHz|mm|ms|pc|pt|px|rad|rem|s|turn|vh|vmax|vmin|vm|vw|%)"},{token:"constant.numeric",regex:d},{token:"constant.numeric",regex:"#[a-f0-9]{6}"},{token:"constant.numeric",regex:"#[a-f0-9]{3}"},{token:["punctuation","entity.other.attribute-name.pseudo-element.css"],regex:f},{token:["punctuation","entity.other.attribute-name.pseudo-class.css"],regex:$},{include:"url"},{token:m,regex:"\\-?[a-zA-Z_][a-zA-Z0-9_\\-]*"},{token:"paren.lparen",regex:"\\{"},{caseInsensitive:!0}],url:[{token:"support.function",regex:"(?:url(:?-prefix)?|domain|regexp)\\(",push:[{token:"support.function",regex:"\\)",next:"pop"},{defaultToken:"string"}]}],strings:[{token:"string.start",regex:"'",push:[{token:"string.end",regex:"'|$",next:"pop"},{include:"escapes"},{token:"constant.language.escape",regex:/\\$/,consumeLineEnd:!0},{defaultToken:"string"}]},{token:"string.start",regex:'"',push:[{token:"string.end",regex:'"|$',next:"pop"},{include:"escapes"},{token:"constant.language.escape",regex:/\\$/,consumeLineEnd:!0},{defaultToken:"string"}]}],escapes:[{token:"constant.language.escape",regex:/\\([a-fA-F\d]{1,6}|[^a-fA-F\d])/}]},this.normalizeRules()};e.inherits(S,a),p.CssHighlightRules=S}),ace.define("ace/mode/matching_brace_outdent",["require","exports","module","ace/range"],function(n,p,A){var e=n("../range").Range,a=function(){};(function(){this.checkOutdent=function(h,g){return/^\s+$/.test(h)?/^\s*\}/.test(g):!1},this.autoOutdent=function(h,g){var l=h.getLine(g),s=l.match(/^(\s*\})/);if(!s)return 0;var i=s[1].length,d=h.findMatchingBracket({row:g,column:i});if(!d||d.row==g)return 0;var f=this.$getIndent(h.getLine(d.row));h.replace(new e(g,0,g,i-1),f)},this.$getIndent=function(h){return h.match(/^\s*/)[0]}}).call(a.prototype),p.MatchingBraceOutdent=a}),ace.define("ace/mode/css_completions",["require","exports","module"],function(n,p,A){var e={background:{"#$0":1},"background-color":{"#$0":1,transparent:1,fixed:1},"background-image":{"url('/$0')":1},"background-repeat":{repeat:1,"repeat-x":1,"repeat-y":1,"no-repeat":1,inherit:1},"background-position":{bottom:2,center:2,left:2,right:2,top:2,inherit:2},"background-attachment":{scroll:1,fixed:1},"background-size":{cover:1,contain:1},"background-clip":{"border-box":1,"padding-box":1,"content-box":1},"background-origin":{"border-box":1,"padding-box":1,"content-box":1},border:{"solid $0":1,"dashed $0":1,"dotted $0":1,"#$0":1},"border-color":{"#$0":1},"border-style":{solid:2,dashed:2,dotted:2,double:2,groove:2,hidden:2,inherit:2,inset:2,none:2,outset:2,ridged:2},"border-collapse":{collapse:1,separate:1},bottom:{px:1,em:1,"%":1},clear:{left:1,right:1,both:1,none:1},color:{"#$0":1,"rgb(#$00,0,0)":1},cursor:{default:1,pointer:1,move:1,text:1,wait:1,help:1,progress:1,"n-resize":1,"ne-resize":1,"e-resize":1,"se-resize":1,"s-resize":1,"sw-resize":1,"w-resize":1,"nw-resize":1},display:{none:1,block:1,inline:1,"inline-block":1,"table-cell":1},"empty-cells":{show:1,hide:1},float:{left:1,right:1,none:1},"font-family":{Arial:2,"Comic Sans MS":2,Consolas:2,"Courier New":2,Courier:2,Georgia:2,Monospace:2,"Sans-Serif":2,"Segoe UI":2,Tahoma:2,"Times New Roman":2,"Trebuchet MS":2,Verdana:1},"font-size":{px:1,em:1,"%":1},"font-weight":{bold:1,normal:1},"font-style":{italic:1,normal:1},"font-variant":{normal:1,"small-caps":1},height:{px:1,em:1,"%":1},left:{px:1,em:1,"%":1},"letter-spacing":{normal:1},"line-height":{normal:1},"list-style-type":{none:1,disc:1,circle:1,square:1,decimal:1,"decimal-leading-zero":1,"lower-roman":1,"upper-roman":1,"lower-greek":1,"lower-latin":1,"upper-latin":1,georgian:1,"lower-alpha":1,"upper-alpha":1},margin:{px:1,em:1,"%":1},"margin-right":{px:1,em:1,"%":1},"margin-left":{px:1,em:1,"%":1},"margin-top":{px:1,em:1,"%":1},"margin-bottom":{px:1,em:1,"%":1},"max-height":{px:1,em:1,"%":1},"max-width":{px:1,em:1,"%":1},"min-height":{px:1,em:1,"%":1},"min-width":{px:1,em:1,"%":1},overflow:{hidden:1,visible:1,auto:1,scroll:1},"overflow-x":{hidden:1,visible:1,auto:1,scroll:1},"overflow-y":{hidden:1,visible:1,auto:1,scroll:1},padding:{px:1,em:1,"%":1},"padding-top":{px:1,em:1,"%":1},"padding-right":{px:1,em:1,"%":1},"padding-bottom":{px:1,em:1,"%":1},"padding-left":{px:1,em:1,"%":1},"page-break-after":{auto:1,always:1,avoid:1,left:1,right:1},"page-break-before":{auto:1,always:1,avoid:1,left:1,right:1},position:{absolute:1,relative:1,fixed:1,static:1},right:{px:1,em:1,"%":1},"table-layout":{fixed:1,auto:1},"text-decoration":{none:1,underline:1,"line-through":1,blink:1},"text-align":{left:1,right:1,center:1,justify:1},"text-transform":{capitalize:1,uppercase:1,lowercase:1,none:1},top:{px:1,em:1,"%":1},"vertical-align":{top:1,bottom:1},visibility:{hidden:1,visible:1},"white-space":{nowrap:1,normal:1,pre:1,"pre-line":1,"pre-wrap":1},width:{px:1,em:1,"%":1},"word-spacing":{normal:1},filter:{"alpha(opacity=$0100)":1},"text-shadow":{"$02px 2px 2px #777":1},"text-overflow":{"ellipsis-word":1,clip:1,ellipsis:1},"-moz-border-radius":1,"-moz-border-radius-topright":1,"-moz-border-radius-bottomright":1,"-moz-border-radius-topleft":1,"-moz-border-radius-bottomleft":1,"-webkit-border-radius":1,"-webkit-border-top-right-radius":1,"-webkit-border-top-left-radius":1,"-webkit-border-bottom-right-radius":1,"-webkit-border-bottom-left-radius":1,"-moz-box-shadow":1,"-webkit-box-shadow":1,transform:{"rotate($00deg)":1,"skew($00deg)":1},"-moz-transform":{"rotate($00deg)":1,"skew($00deg)":1},"-webkit-transform":{"rotate($00deg)":1,"skew($00deg)":1}},a=function(){};(function(){this.completionsDefined=!1,this.defineCompletions=function(){if(document){var h=document.createElement("c").style;for(var g in h)if(typeof h[g]=="string"){var l=g.replace(/[A-Z]/g,function(s){return"-"+s.toLowerCase()});e.hasOwnProperty(l)||(e[l]=1)}}this.completionsDefined=!0},this.getCompletions=function(h,g,l,s){if(this.completionsDefined||this.defineCompletions(),h==="ruleset"||g.$mode.$id=="ace/mode/scss"){var i=g.getLine(l.row).substr(0,l.column),d=/\([^)]*$/.test(i);return d&&(i=i.substr(i.lastIndexOf("(")+1)),/:[^;]+$/.test(i)?this.getPropertyValueCompletions(h,g,l,s):this.getPropertyCompletions(h,g,l,s,d)}return[]},this.getPropertyCompletions=function(h,g,l,s,i){i=i||!1;var d=Object.keys(e);return d.map(function(f){return{caption:f,snippet:f+": $0"+(i?"":";"),meta:"property",score:1e6}})},this.getPropertyValueCompletions=function(h,g,l,s){var i=g.getLine(l.row).substr(0,l.column),d=(/([\w\-]+):[^:]*$/.exec(i)||{})[1];if(!d)return[];var f=[];return d in e&&typeof e[d]=="object"&&(f=Object.keys(e[d])),f.map(function($){return{caption:$,snippet:$,meta:"property value",score:1e6}})}}).call(a.prototype),p.CssCompletions=a}),ace.define("ace/mode/behaviour/css",["require","exports","module","ace/lib/oop","ace/mode/behaviour","ace/mode/behaviour/cstyle","ace/token_iterator"],function(n,p,A){var e=n("../../lib/oop");n("../behaviour").Behaviour;var a=n("./cstyle").CstyleBehaviour,h=n("../../token_iterator").TokenIterator,g=function(){this.inherit(a),this.add("colon","insertion",function(l,s,i,d,f){if(f===":"&&i.selection.isEmpty()){var $=i.getCursorPosition(),S=new h(d,$.row,$.column),m=S.getCurrentToken();if(m&&m.value.match(/\s+/)&&(m=S.stepBackward()),m&&m.type==="support.type"){var v=d.doc.getLine($.row),E=v.substring($.column,$.column+1);if(E===":")return{text:"",selection:[1,1]};if(/^(\s+[^;]|\s*$)/.test(v.substring($.column)))return{text:":;",selection:[1,1]}}}}),this.add("colon","deletion",function(l,s,i,d,f){var $=d.doc.getTextRange(f);if(!f.isMultiLine()&&$===":"){var S=i.getCursorPosition(),m=new h(d,S.row,S.column),v=m.getCurrentToken();if(v&&v.value.match(/\s+/)&&(v=m.stepBackward()),v&&v.type==="support.type"){var E=d.doc.getLine(f.start.row),P=E.substring(f.end.column,f.end.column+1);if(P===";")return f.end.column++,f}}}),this.add("semicolon","insertion",function(l,s,i,d,f){if(f===";"&&i.selection.isEmpty()){var $=i.getCursorPosition(),S=d.doc.getLine($.row),m=S.substring($.column,$.column+1);if(m===";")return{text:"",selection:[1,1]}}}),this.add("!important","insertion",function(l,s,i,d,f){if(f==="!"&&i.selection.isEmpty()){var $=i.getCursorPosition(),S=d.doc.getLine($.row);if(/^\s*(;|}|$)/.test(S.substring($.column)))return{text:"!important",selection:[10,10]}}})};e.inherits(g,a),p.CssBehaviour=g}),ace.define("ace/mode/folding/cstyle",["require","exports","module","ace/lib/oop","ace/range","ace/mode/folding/fold_mode"],function(n,p,A){var e=n("../../lib/oop"),a=n("../../range").Range,h=n("./fold_mode").FoldMode,g=p.FoldMode=function(l){l&&(this.foldingStartMarker=new RegExp(this.foldingStartMarker.source.replace(/\|[^|]*?$/,"|"+l.start)),this.foldingStopMarker=new RegExp(this.foldingStopMarker.source.replace(/\|[^|]*?$/,"|"+l.end)))};e.inherits(g,h),function(){this.foldingStartMarker=/([\{\[\(])[^\}\]\)]*$|^\s*(\/\*)/,this.foldingStopMarker=/^[^\[\{\(]*([\}\]\)])|^[\s\*]*(\*\/)/,this.singleLineBlockCommentRe=/^\s*(\/\*).*\*\/\s*$/,this.tripleStarBlockCommentRe=/^\s*(\/\*\*\*).*\*\/\s*$/,this.startRegionRe=/^\s*(\/\*|\/\/)#?region\b/,this._getFoldWidgetBase=this.getFoldWidget,this.getFoldWidget=function(l,s,i){var d=l.getLine(i);if(this.singleLineBlockCommentRe.test(d)&&!this.startRegionRe.test(d)&&!this.tripleStarBlockCommentRe.test(d))return"";var f=this._getFoldWidgetBase(l,s,i);return!f&&this.startRegionRe.test(d)?"start":f},this.getFoldWidgetRange=function(l,s,i,d){var f=l.getLine(i);if(this.startRegionRe.test(f))return this.getCommentRegionBlock(l,f,i);var m=f.match(this.foldingStartMarker);if(m){var $=m.index;if(m[1])return this.openingBracketBlock(l,m[1],i,$);var S=l.getCommentFoldRange(i,$+m[0].length,1);return S&&!S.isMultiLine()&&(d?S=this.getSectionRange(l,i):s!="all"&&(S=null)),S}if(s!=="markbegin"){var m=f.match(this.foldingStopMarker);if(m){var $=m.index+m[0].length;return m[1]?this.closingBracketBlock(l,m[1],i,$):l.getCommentFoldRange(i,$,-1)}}},this.getSectionRange=function(l,s){var i=l.getLine(s),d=i.search(/\S/),f=s,$=i.length;s+=1;for(var S=s,m=l.getLength();++s<m;){i=l.getLine(s);var v=i.search(/\S/);if(v!==-1){if(d>v)break;var E=this.getFoldWidgetRange(l,"all",s);if(E){if(E.start.row<=f)break;if(E.isMultiLine())s=E.end.row;else if(d==v)break}S=s}}return new a(f,$,S,l.getLine(S).length)},this.getCommentRegionBlock=function(l,s,i){for(var d=s.search(/\s*$/),f=l.getLength(),$=i,S=/^\s*(?:\/\*|\/\/|--)#?(end)?region\b/,m=1;++i<f;){s=l.getLine(i);var v=S.exec(s);if(v&&(v[1]?m--:m++,!m))break}var E=i;if(E>$)return new a($,d,E,s.length)}}.call(g.prototype)}),ace.define("ace/mode/css",["require","exports","module","ace/lib/oop","ace/mode/text","ace/mode/css_highlight_rules","ace/mode/matching_brace_outdent","ace/worker/worker_client","ace/mode/css_completions","ace/mode/behaviour/css","ace/mode/folding/cstyle"],function(n,p,A){var e=n("../lib/oop"),a=n("./text").Mode,h=n("./css_highlight_rules").CssHighlightRules,g=n("./matching_brace_outdent").MatchingBraceOutdent,l=n("../worker/worker_client").WorkerClient,s=n("./css_completions").CssCompletions,i=n("./behaviour/css").CssBehaviour,d=n("./folding/cstyle").FoldMode,f=function(){this.HighlightRules=h,this.$outdent=new g,this.$behaviour=new i,this.$completer=new s,this.foldingRules=new d};e.inherits(f,a),function(){this.foldingRules="cStyle",this.blockComment={start:"/*",end:"*/"},this.getNextLineIndent=function($,S,m){var v=this.$getIndent(S),E=this.getTokenizer().getLineTokens(S,$).tokens;if(E.length&&E[E.length-1].type=="comment")return v;var P=S.match(/^.*\{\s*$/);return P&&(v+=m),v},this.checkOutdent=function($,S,m){return this.$outdent.checkOutdent(S,m)},this.autoOutdent=function($,S,m){this.$outdent.autoOutdent(S,m)},this.getCompletions=function($,S,m,v){return this.$completer.getCompletions($,S,m,v)},this.createWorker=function($){var S=new l(["ace"],"ace/mode/css_worker","Worker");return S.attachToDocument($.getDocument()),S.on("annotate",function(m){$.setAnnotations(m.data)}),S.on("terminate",function(){$.clearAnnotations()}),S},this.$id="ace/mode/css",this.snippetFileId="ace/snippets/css"}.call(f.prototype),p.Mode=f}),function(){ace.require(["ace/mode/css"],function(n){M&&(M.exports=n)})}()})(fr);var mr={exports:{}};(function(M,b){ace.define("ace/mode/jsdoc_comment_highlight_rules",["require","exports","module","ace/lib/oop","ace/mode/text_highlight_rules"],function(n,p,A){var e=n("../lib/oop"),a=n("./text_highlight_rules").TextHighlightRules,h=function(){this.$rules={start:[{token:["comment.doc.tag","comment.doc.text","lparen.doc"],regex:"(@(?:param|member|typedef|property|namespace|var|const|callback))(\\s*)({)",push:[{token:"lparen.doc",regex:"{",push:[{include:"doc-syntax"},{token:"rparen.doc",regex:"}|(?=$)",next:"pop"}]},{token:["rparen.doc","text.doc","variable.parameter.doc","lparen.doc","variable.parameter.doc","rparen.doc"],regex:/(})(\s*)(?:([\w=:\/\.]+)|(?:(\[)([\w=:\/\.\-\'\" ]+)(\])))/,next:"pop"},{token:"rparen.doc",regex:"}|(?=$)",next:"pop"},{include:"doc-syntax"},{defaultToken:"text.doc"}]},{token:["comment.doc.tag","text.doc","lparen.doc"],regex:"(@(?:returns?|yields|type|this|suppress|public|protected|private|package|modifies|implements|external|exception|throws|enum|define|extends))(\\s*)({)",push:[{token:"lparen.doc",regex:"{",push:[{include:"doc-syntax"},{token:"rparen.doc",regex:"}|(?=$)",next:"pop"}]},{token:"rparen.doc",regex:"}|(?=$)",next:"pop"},{include:"doc-syntax"},{defaultToken:"text.doc"}]},{token:["comment.doc.tag","text.doc","variable.parameter.doc"],regex:'(@(?:alias|memberof|instance|module|name|lends|namespace|external|this|template|requires|param|implements|function|extends|typedef|mixes|constructor|var|memberof\\!|event|listens|exports|class|constructs|interface|emits|fires|throws|const|callback|borrows|augments))(\\s+)(\\w[\\w#.:/~"\\-]*)?'},{token:["comment.doc.tag","text.doc","variable.parameter.doc"],regex:"(@method)(\\s+)(\\w[\\w.\\(\\)]*)"},{token:"comment.doc.tag",regex:"@access\\s+(?:private|public|protected)"},{token:"comment.doc.tag",regex:"@kind\\s+(?:class|constant|event|external|file|function|member|mixin|module|namespace|typedef)"},{token:"comment.doc.tag",regex:"@\\w+(?=\\s|$)"},h.getTagRule(),{defaultToken:"comment.doc.body",caseInsensitive:!0}],"doc-syntax":[{token:"operator.doc",regex:/[|:]/},{token:"paren.doc",regex:/[\[\]]/}]},this.normalizeRules()};e.inherits(h,a),h.getTagRule=function(g){return{token:"comment.doc.tag.storage.type",regex:"\\b(?:TODO|FIXME|XXX|HACK)\\b"}},h.getStartRule=function(g){return{token:"comment.doc",regex:/\/\*\*(?!\/)/,next:g}},h.getEndRule=function(g){return{token:"comment.doc",regex:"\\*\\/",next:g}},p.JsDocCommentHighlightRules=h}),ace.define("ace/mode/javascript_highlight_rules",["require","exports","module","ace/lib/oop","ace/mode/jsdoc_comment_highlight_rules","ace/mode/text_highlight_rules"],function(n,p,A){function e(){var d=s.replace("\\d","\\d\\-"),f={onMatch:function(S,m,v){var E=S.charAt(1)=="/"?2:1;return E==1?(m!=this.nextState?v.unshift(this.next,this.nextState,0):v.unshift(this.next),v[2]++):E==2&&m==this.nextState&&(v[1]--,(!v[1]||v[1]<0)&&(v.shift(),v.shift())),[{type:"meta.tag.punctuation."+(E==1?"":"end-")+"tag-open.xml",value:S.slice(0,E)},{type:"meta.tag.tag-name.xml",value:S.substr(E)}]},regex:"</?(?:"+d+"|(?=>))",next:"jsxAttributes",nextState:"jsx"};this.$rules.start.unshift(f);var $={regex:"{",token:"paren.quasi.start",push:"start"};this.$rules.jsx=[$,f,{include:"reference"},{defaultToken:"string.xml"}],this.$rules.jsxAttributes=[{token:"meta.tag.punctuation.tag-close.xml",regex:"/?>",onMatch:function(S,m,v){return m==v[0]&&v.shift(),S.length==2&&(v[0]==this.nextState&&v[1]--,(!v[1]||v[1]<0)&&v.splice(0,2)),this.next=v[0]||"start",[{type:this.token,value:S}]},nextState:"jsx"},$,a("jsxAttributes"),{token:"entity.other.attribute-name.xml",regex:d},{token:"keyword.operator.attribute-equals.xml",regex:"="},{token:"text.tag-whitespace.xml",regex:"\\s+"},{token:"string.attribute-value.xml",regex:"'",stateName:"jsx_attr_q",push:[{token:"string.attribute-value.xml",regex:"'",next:"pop"},{include:"reference"},{defaultToken:"string.attribute-value.xml"}]},{token:"string.attribute-value.xml",regex:'"',stateName:"jsx_attr_qq",push:[{token:"string.attribute-value.xml",regex:'"',next:"pop"},{include:"reference"},{defaultToken:"string.attribute-value.xml"}]},f],this.$rules.reference=[{token:"constant.language.escape.reference.xml",regex:"(?:&#[0-9]+;)|(?:&#x[0-9a-fA-F]+;)|(?:&[a-zA-Z0-9_:\\.-]+;)"}]}function a(d){return[{token:"comment",regex:/\/\*/,next:[g.getTagRule(),{token:"comment",regex:"\\*\\/",next:d||"pop"},{defaultToken:"comment",caseInsensitive:!0}]},{token:"comment",regex:"\\/\\/",next:[g.getTagRule(),{token:"comment",regex:"$|^",next:d||"pop"},{defaultToken:"comment",caseInsensitive:!0}]}]}var h=n("../lib/oop"),g=n("./jsdoc_comment_highlight_rules").JsDocCommentHighlightRules,l=n("./text_highlight_rules").TextHighlightRules,s="[a-zA-Z\\$_¡-￿][a-zA-Z\\d\\$_¡-￿]*",i=function(d){var f={"variable.language":"Array|Boolean|Date|Function|Iterator|Number|Object|RegExp|String|Proxy|Symbol|Namespace|QName|XML|XMLList|ArrayBuffer|Float32Array|Float64Array|Int16Array|Int32Array|Int8Array|Uint16Array|Uint32Array|Uint8Array|Uint8ClampedArray|Error|EvalError|InternalError|RangeError|ReferenceError|StopIteration|SyntaxError|TypeError|URIError|decodeURI|decodeURIComponent|encodeURI|encodeURIComponent|eval|isFinite|isNaN|parseFloat|parseInt|JSON|Math|this|arguments|prototype|window|document",keyword:"const|yield|import|get|set|async|await|break|case|catch|continue|default|delete|do|else|finally|for|if|in|of|instanceof|new|return|switch|throw|try|typeof|let|var|while|with|debugger|__parent__|__count__|escape|unescape|with|__proto__|class|enum|extends|super|export|implements|private|public|interface|package|protected|static|constructor","storage.type":"const|let|var|function","constant.language":"null|Infinity|NaN|undefined","support.function":"alert","constant.language.boolean":"true|false"},$=this.createKeywordMapper(f,"identifier"),S="case|do|else|finally|in|instanceof|return|throw|try|typeof|yield|void",m="\\\\(?:x[0-9a-fA-F]{2}|u[0-9a-fA-F]{4}|u{[0-9a-fA-F]{1,6}}|[0-2][0-7]{0,2}|3[0-7][0-7]?|[4-7][0-7]?|.)",v="(function)(\\s*)(\\*?)",E={token:["identifier","text","paren.lparen"],regex:"(\\b(?!"+Object.values(f).join("|")+"\\b)"+s+")(\\s*)(\\()"};this.$rules={no_regex:[g.getStartRule("doc-start"),a("no_regex"),E,{token:"string",regex:"'(?=.)",next:"qstring"},{token:"string",regex:'"(?=.)',next:"qqstring"},{token:"constant.numeric",regex:/0(?:[xX][0-9a-fA-F]+|[oO][0-7]+|[bB][01]+)\b/},{token:"constant.numeric",regex:/(?:\d\d*(?:\.\d*)?|\.\d+)(?:[eE][+-]?\d+\b)?/},{token:["entity.name.function","text","keyword.operator","text","storage.type","text","storage.type","text","paren.lparen"],regex:"("+s+")(\\s*)(=)(\\s*)"+v+"(\\s*)(\\()",next:"function_arguments"},{token:["storage.type","text","storage.type","text","text","entity.name.function","text","paren.lparen"],regex:"(function)(?:(?:(\\s*)(\\*)(\\s*))|(\\s+))("+s+")(\\s*)(\\()",next:"function_arguments"},{token:["entity.name.function","text","punctuation.operator","text","storage.type","text","storage.type","text","paren.lparen"],regex:"("+s+")(\\s*)(:)(\\s*)"+v+"(\\s*)(\\()",next:"function_arguments"},{token:["text","text","storage.type","text","storage.type","text","paren.lparen"],regex:"(:)(\\s*)"+v+"(\\s*)(\\()",next:"function_arguments"},{token:"keyword",regex:`from(?=\\s*('|"))`},{token:"keyword",regex:"(?:"+S+")\\b",next:"start"},{token:"support.constant",regex:/that\b/},{token:["storage.type","punctuation.operator","support.function.firebug"],regex:/(console)(\.)(warn|info|log|error|debug|time|trace|timeEnd|assert)\b/},{token:$,regex:s},{token:"punctuation.operator",regex:/[.](?![.])/,next:"property"},{token:"storage.type",regex:/=>/,next:"start"},{token:"keyword.operator",regex:/--|\+\+|\.{3}|===|==|=|!=|!==|<+=?|>+=?|!|&&|\|\||\?:|[!$%&*+\-~\/^]=?/,next:"start"},{token:"punctuation.operator",regex:/[?:,;.]/,next:"start"},{token:"paren.lparen",regex:/[\[({]/,next:"start"},{token:"paren.rparen",regex:/[\])}]/},{token:"comment",regex:/^#!.*$/}],property:[{token:"text",regex:"\\s+"},{token:"keyword.operator",regex:/=/},{token:["storage.type","text","storage.type","text","paren.lparen"],regex:v+"(\\s*)(\\()",next:"function_arguments"},{token:["storage.type","text","storage.type","text","text","entity.name.function","text","paren.lparen"],regex:"(function)(?:(?:(\\s*)(\\*)(\\s*))|(\\s+))(\\w+)(\\s*)(\\()",next:"function_arguments"},{token:"punctuation.operator",regex:/[.](?![.])/},{token:"support.function",regex:"prototype"},{token:"support.function",regex:/(s(?:h(?:ift|ow(?:Mod(?:elessDialog|alDialog)|Help))|croll(?:X|By(?:Pages|Lines)?|Y|To)?|t(?:op|rike)|i(?:n|zeToContent|debar|gnText)|ort|u(?:p|b(?:str(?:ing)?)?)|pli(?:ce|t)|e(?:nd|t(?:Re(?:sizable|questHeader)|M(?:i(?:nutes|lliseconds)|onth)|Seconds|Ho(?:tKeys|urs)|Year|Cursor|Time(?:out)?|Interval|ZOptions|Date|UTC(?:M(?:i(?:nutes|lliseconds)|onth)|Seconds|Hours|Date|FullYear)|FullYear|Active)|arch)|qrt|lice|avePreferences|mall)|h(?:ome|andleEvent)|navigate|c(?:har(?:CodeAt|At)|o(?:s|n(?:cat|textual|firm)|mpile)|eil|lear(?:Timeout|Interval)?|a(?:ptureEvents|ll)|reate(?:StyleSheet|Popup|EventObject))|t(?:o(?:GMTString|S(?:tring|ource)|U(?:TCString|pperCase)|Lo(?:caleString|werCase))|est|a(?:n|int(?:Enabled)?))|i(?:s(?:NaN|Finite)|ndexOf|talics)|d(?:isableExternalCapture|ump|etachEvent)|u(?:n(?:shift|taint|escape|watch)|pdateCommands)|j(?:oin|avaEnabled)|p(?:o(?:p|w)|ush|lugins.refresh|a(?:ddings|rse(?:Int|Float)?)|r(?:int|ompt|eference))|e(?:scape|nableExternalCapture|val|lementFromPoint|x(?:p|ec(?:Script|Command)?))|valueOf|UTC|queryCommand(?:State|Indeterm|Enabled|Value)|f(?:i(?:nd|lter|le(?:ModifiedDate|Size|CreatedDate|UpdatedDate)|xed)|o(?:nt(?:size|color)|rward|rEach)|loor|romCharCode)|watch|l(?:ink|o(?:ad|g)|astIndexOf)|a(?:sin|nchor|cos|t(?:tachEvent|ob|an(?:2)?)|pply|lert|b(?:s|ort))|r(?:ou(?:nd|teEvents)|e(?:size(?:By|To)|calc|turnValue|place|verse|l(?:oad|ease(?:Capture|Events)))|andom)|g(?:o|et(?:ResponseHeader|M(?:i(?:nutes|lliseconds)|onth)|Se(?:conds|lection)|Hours|Year|Time(?:zoneOffset)?|Da(?:y|te)|UTC(?:M(?:i(?:nutes|lliseconds)|onth)|Seconds|Hours|Da(?:y|te)|FullYear)|FullYear|A(?:ttention|llResponseHeaders)))|m(?:in|ove(?:B(?:y|elow)|To(?:Absolute)?|Above)|ergeAttributes|a(?:tch|rgins|x))|b(?:toa|ig|o(?:ld|rderWidths)|link|ack))\b(?=\()/},{token:"support.function.dom",regex:/(s(?:ub(?:stringData|mit)|plitText|e(?:t(?:NamedItem|Attribute(?:Node)?)|lect))|has(?:ChildNodes|Feature)|namedItem|c(?:l(?:ick|o(?:se|neNode))|reate(?:C(?:omment|DATASection|aption)|T(?:Head|extNode|Foot)|DocumentFragment|ProcessingInstruction|E(?:ntityReference|lement)|Attribute))|tabIndex|i(?:nsert(?:Row|Before|Cell|Data)|tem)|open|delete(?:Row|C(?:ell|aption)|T(?:Head|Foot)|Data)|focus|write(?:ln)?|a(?:dd|ppend(?:Child|Data))|re(?:set|place(?:Child|Data)|move(?:NamedItem|Child|Attribute(?:Node)?)?)|get(?:NamedItem|Element(?:sBy(?:Name|TagName|ClassName)|ById)|Attribute(?:Node)?)|blur)\b(?=\()/},{token:"support.constant",regex:/(s(?:ystemLanguage|cr(?:ipts|ollbars|een(?:X|Y|Top|Left))|t(?:yle(?:Sheets)?|atus(?:Text|bar)?)|ibling(?:Below|Above)|ource|uffixes|e(?:curity(?:Policy)?|l(?:ection|f)))|h(?:istory|ost(?:name)?|as(?:h|Focus))|y|X(?:MLDocument|SLDocument)|n(?:ext|ame(?:space(?:s|URI)|Prop))|M(?:IN_VALUE|AX_VALUE)|c(?:haracterSet|o(?:n(?:structor|trollers)|okieEnabled|lorDepth|mp(?:onents|lete))|urrent|puClass|l(?:i(?:p(?:boardData)?|entInformation)|osed|asses)|alle(?:e|r)|rypto)|t(?:o(?:olbar|p)|ext(?:Transform|Indent|Decoration|Align)|ags)|SQRT(?:1_2|2)|i(?:n(?:ner(?:Height|Width)|put)|ds|gnoreCase)|zIndex|o(?:scpu|n(?:readystatechange|Line)|uter(?:Height|Width)|p(?:sProfile|ener)|ffscreenBuffering)|NEGATIVE_INFINITY|d(?:i(?:splay|alog(?:Height|Top|Width|Left|Arguments)|rectories)|e(?:scription|fault(?:Status|Ch(?:ecked|arset)|View)))|u(?:ser(?:Profile|Language|Agent)|n(?:iqueID|defined)|pdateInterval)|_content|p(?:ixelDepth|ort|ersonalbar|kcs11|l(?:ugins|atform)|a(?:thname|dding(?:Right|Bottom|Top|Left)|rent(?:Window|Layer)?|ge(?:X(?:Offset)?|Y(?:Offset)?))|r(?:o(?:to(?:col|type)|duct(?:Sub)?|mpter)|e(?:vious|fix)))|e(?:n(?:coding|abledPlugin)|x(?:ternal|pando)|mbeds)|v(?:isibility|endor(?:Sub)?|Linkcolor)|URLUnencoded|P(?:I|OSITIVE_INFINITY)|f(?:ilename|o(?:nt(?:Size|Family|Weight)|rmName)|rame(?:s|Element)|gColor)|E|whiteSpace|l(?:i(?:stStyleType|n(?:eHeight|kColor))|o(?:ca(?:tion(?:bar)?|lName)|wsrc)|e(?:ngth|ft(?:Context)?)|a(?:st(?:M(?:odified|atch)|Index|Paren)|yer(?:s|X)|nguage))|a(?:pp(?:MinorVersion|Name|Co(?:deName|re)|Version)|vail(?:Height|Top|Width|Left)|ll|r(?:ity|guments)|Linkcolor|bove)|r(?:ight(?:Context)?|e(?:sponse(?:XML|Text)|adyState))|global|x|m(?:imeTypes|ultiline|enubar|argin(?:Right|Bottom|Top|Left))|L(?:N(?:10|2)|OG(?:10E|2E))|b(?:o(?:ttom|rder(?:Width|RightWidth|BottomWidth|Style|Color|TopWidth|LeftWidth))|ufferDepth|elow|ackground(?:Color|Image)))\b/},{token:"identifier",regex:s},{regex:"",token:"empty",next:"no_regex"}],start:[g.getStartRule("doc-start"),a("start"),{token:"string.regexp",regex:"\\/",next:"regex"},{token:"text",regex:"\\s+|^$",next:"start"},{token:"empty",regex:"",next:"no_regex"}],regex:[{token:"regexp.keyword.operator",regex:"\\\\(?:u[\\da-fA-F]{4}|x[\\da-fA-F]{2}|.)"},{token:"string.regexp",regex:"/[sxngimy]*",next:"no_regex"},{token:"invalid",regex:/\{\d+\b,?\d*\}[+*]|[+*$^?][+*]|[$^][?]|\?{3,}/},{token:"constant.language.escape",regex:/\(\?[:=!]|\)|\{\d+\b,?\d*\}|[+*]\?|[()$^+*?.]/},{token:"constant.language.delimiter",regex:/\|/},{token:"constant.language.escape",regex:/\[\^?/,next:"regex_character_class"},{token:"empty",regex:"$",next:"no_regex"},{defaultToken:"string.regexp"}],regex_character_class:[{token:"regexp.charclass.keyword.operator",regex:"\\\\(?:u[\\da-fA-F]{4}|x[\\da-fA-F]{2}|.)"},{token:"constant.language.escape",regex:"]",next:"regex"},{token:"constant.language.escape",regex:"-"},{token:"empty",regex:"$",next:"no_regex"},{defaultToken:"string.regexp.charachterclass"}],default_parameter:[{token:"string",regex:"'(?=.)",push:[{token:"string",regex:"'|$",next:"pop"},{include:"qstring"}]},{token:"string",regex:'"(?=.)',push:[{token:"string",regex:'"|$',next:"pop"},{include:"qqstring"}]},{token:"constant.language",regex:"null|Infinity|NaN|undefined"},{token:"constant.numeric",regex:/0(?:[xX][0-9a-fA-F]+|[oO][0-7]+|[bB][01]+)\b/},{token:"constant.numeric",regex:/(?:\d\d*(?:\.\d*)?|\.\d+)(?:[eE][+-]?\d+\b)?/},{token:"punctuation.operator",regex:",",next:"function_arguments"},{token:"text",regex:"\\s+"},{token:"punctuation.operator",regex:"$"},{token:"empty",regex:"",next:"no_regex"}],function_arguments:[a("function_arguments"),{token:"variable.parameter",regex:s},{token:"punctuation.operator",regex:","},{token:"text",regex:"\\s+"},{token:"punctuation.operator",regex:"$"},{token:"empty",regex:"",next:"no_regex"}],qqstring:[{token:"constant.language.escape",regex:m},{token:"string",regex:"\\\\$",consumeLineEnd:!0},{token:"string",regex:'"|$',next:"no_regex"},{defaultToken:"string"}],qstring:[{token:"constant.language.escape",regex:m},{token:"string",regex:"\\\\$",consumeLineEnd:!0},{token:"string",regex:"'|$",next:"no_regex"},{defaultToken:"string"}]},(!d||!d.noES6)&&(this.$rules.no_regex.unshift({regex:"[{}]",onMatch:function(P,R,k){if(this.next=P=="{"?this.nextState:"",P=="{"&&k.length)k.unshift("start",R);else if(P=="}"&&k.length&&(k.shift(),this.next=k.shift(),this.next.indexOf("string")!=-1||this.next.indexOf("jsx")!=-1))return"paren.quasi.end";return P=="{"?"paren.lparen":"paren.rparen"},nextState:"start"},{token:"string.quasi.start",regex:/`/,push:[{token:"constant.language.escape",regex:m},{token:"paren.quasi.start",regex:/\${/,push:"start"},{token:"string.quasi.end",regex:/`/,next:"pop"},{defaultToken:"string.quasi"}]},{token:["variable.parameter","text"],regex:"("+s+")(\\s*)(?=\\=>)"},{token:"paren.lparen",regex:"(\\()(?=[^\\(]+\\s*=>)",next:"function_arguments"},{token:"variable.language",regex:"(?:(?:(?:Weak)?(?:Set|Map))|Promise)\\b"}),this.$rules.function_arguments.unshift({token:"keyword.operator",regex:"=",next:"default_parameter"},{token:"keyword.operator",regex:"\\.{3}"}),this.$rules.property.unshift({token:"support.function",regex:"(findIndex|repeat|startsWith|endsWith|includes|isSafeInteger|trunc|cbrt|log2|log10|sign|then|catch|finally|resolve|reject|race|any|all|allSettled|keys|entries|isInteger)\\b(?=\\()"},{token:"constant.language",regex:"(?:MAX_SAFE_INTEGER|MIN_SAFE_INTEGER|EPSILON)\\b"}),(!d||d.jsx!=0)&&e.call(this)),this.embedRules(g,"doc-",[g.getEndRule("no_regex")]),this.normalizeRules()};h.inherits(i,l),p.JavaScriptHighlightRules=i}),ace.define("ace/mode/matching_brace_outdent",["require","exports","module","ace/range"],function(n,p,A){var e=n("../range").Range,a=function(){};(function(){this.checkOutdent=function(h,g){return/^\s+$/.test(h)?/^\s*\}/.test(g):!1},this.autoOutdent=function(h,g){var l=h.getLine(g),s=l.match(/^(\s*\})/);if(!s)return 0;var i=s[1].length,d=h.findMatchingBracket({row:g,column:i});if(!d||d.row==g)return 0;var f=this.$getIndent(h.getLine(d.row));h.replace(new e(g,0,g,i-1),f)},this.$getIndent=function(h){return h.match(/^\s*/)[0]}}).call(a.prototype),p.MatchingBraceOutdent=a}),ace.define("ace/mode/behaviour/xml",["require","exports","module","ace/lib/oop","ace/mode/behaviour","ace/token_iterator"],function(n,p,A){function e(s,i){return s&&s.type.lastIndexOf(i+".xml")>-1}var a=n("../../lib/oop"),h=n("../behaviour").Behaviour,g=n("../../token_iterator").TokenIterator,l=function(){this.add("string_dquotes","insertion",function(s,i,d,f,$){if($=='"'||$=="'"){var S=$,m=f.doc.getTextRange(d.getSelectionRange());if(m!==""&&m!=="'"&&m!='"'&&d.getWrapBehavioursEnabled())return{text:S+m+S,selection:!1};var v=d.getCursorPosition(),E=f.doc.getLine(v.row),P=E.substring(v.column,v.column+1),R=new g(f,v.row,v.column),k=R.getCurrentToken();if(P==S&&(e(k,"attribute-value")||e(k,"string")))return{text:"",selection:[1,1]};if(k||(k=R.stepBackward()),!k)return;for(;e(k,"tag-whitespace")||e(k,"whitespace");)k=R.stepBackward();var t=!P||P.match(/\s/);if(e(k,"attribute-equals")&&(t||P==">")||e(k,"decl-attribute-equals")&&(t||P=="?"))return{text:S+S,selection:[1,1]}}}),this.add("string_dquotes","deletion",function(s,i,d,f,$){var S=f.doc.getTextRange($);if(!$.isMultiLine()&&(S=='"'||S=="'")){var m=f.doc.getLine($.start.row),v=m.substring($.start.column+1,$.start.column+2);if(v==S)return $.end.column++,$}}),this.add("autoclosing","insertion",function(s,i,d,f,$){if($==">"){var S=d.getSelectionRange().start,m=new g(f,S.row,S.column),v=m.getCurrentToken()||m.stepBackward();if(!v||!(e(v,"tag-name")||e(v,"tag-whitespace")||e(v,"attribute-name")||e(v,"attribute-equals")||e(v,"attribute-value"))||e(v,"reference.attribute-value"))return;if(e(v,"attribute-value")){var E=m.getCurrentTokenColumn()+v.value.length;if(S.column<E)return;if(S.column==E){var P=m.stepForward();if(P&&e(P,"attribute-value"))return;m.stepBackward()}}if(/^\s*>/.test(f.getLine(S.row).slice(S.column)))return;for(;!e(v,"tag-name");)if(v=m.stepBackward(),v.value=="<"){v=m.stepForward();break}var R=m.getCurrentTokenRow(),k=m.getCurrentTokenColumn();if(e(m.stepBackward(),"end-tag-open"))return;var t=v.value;return R==S.row&&(t=t.substring(0,S.column-k)),this.voidElements&&this.voidElements.hasOwnProperty(t.toLowerCase())?void 0:{text:"></"+t+">",selection:[1,1]}}}),this.add("autoindent","insertion",function(s,i,d,f,$){if($==`
`){var S=d.getCursorPosition(),m=f.getLine(S.row),v=new g(f,S.row,S.column),E=v.getCurrentToken();if(e(E,"")&&E.type.indexOf("tag-close")!==-1){if(E.value=="/>")return;for(;E&&E.type.indexOf("tag-name")===-1;)E=v.stepBackward();if(!E)return;var P=E.value,R=v.getCurrentTokenRow();if(E=v.stepBackward(),!E||E.type.indexOf("end-tag")!==-1)return;if(this.voidElements&&!this.voidElements[P]||!this.voidElements){var k=f.getTokenAt(S.row,S.column+1),m=f.getLine(R),t=this.$getIndent(m),r=t+f.getTabString();return k&&k.value==="</"?{text:`
`+r+`
`+t,selection:[1,r.length,1,r.length]}:{text:`
`+r}}}}})};a.inherits(l,h),p.XmlBehaviour=l}),ace.define("ace/mode/behaviour/javascript",["require","exports","module","ace/lib/oop","ace/token_iterator","ace/mode/behaviour/cstyle","ace/mode/behaviour/xml"],function(n,p,A){var e=n("../../lib/oop"),a=n("../../token_iterator").TokenIterator,h=n("../behaviour/cstyle").CstyleBehaviour,g=n("../behaviour/xml").XmlBehaviour,l=function(){var s=new g({closeCurlyBraces:!0}).getBehaviours();this.addBehaviours(s),this.inherit(h),this.add("autoclosing-fragment","insertion",function(i,d,f,$,S){if(S==">"){var m=f.getSelectionRange().start,v=new a($,m.row,m.column),E=v.getCurrentToken()||v.stepBackward();if(!E)return;if(E.value=="<")return{text:"></>",selection:[1,1]}}})};e.inherits(l,h),p.JavaScriptBehaviour=l}),ace.define("ace/mode/folding/xml",["require","exports","module","ace/lib/oop","ace/range","ace/mode/folding/fold_mode"],function(n,p,A){function e(i,d){return i&&i.type&&i.type.lastIndexOf(d+".xml")>-1}var a=n("../../lib/oop"),h=n("../../range").Range,g=n("./fold_mode").FoldMode,l=p.FoldMode=function(i,d){g.call(this),this.voidElements=i||{},this.optionalEndTags=a.mixin({},this.voidElements),d&&a.mixin(this.optionalEndTags,d)};a.inherits(l,g);var s=function(){this.tagName="",this.closing=!1,this.selfClosing=!1,this.start={row:0,column:0},this.end={row:0,column:0}};(function(){this.getFoldWidget=function(i,d,f){var $=this._getFirstTagInLine(i,f);return $?$.closing||!$.tagName&&$.selfClosing?d==="markbeginend"?"end":"":!$.tagName||$.selfClosing||this.voidElements.hasOwnProperty($.tagName.toLowerCase())||this._findEndTagInLine(i,f,$.tagName,$.end.column)?"":"start":this.getCommentFoldWidget(i,f)},this.getCommentFoldWidget=function(i,d){return/comment/.test(i.getState(d))&&/<!-/.test(i.getLine(d))?"start":""},this._getFirstTagInLine=function(i,d){for(var f=i.getTokens(d),$=new s,S=0;S<f.length;S++){var m=f[S];if(e(m,"tag-open")){if($.end.column=$.start.column+m.value.length,$.closing=e(m,"end-tag-open"),m=f[++S],!m)return null;if($.tagName=m.value,m.value===""){if(m=f[++S],!m)return null;$.tagName=m.value}for($.end.column+=m.value.length,S++;S<f.length;S++)if(m=f[S],$.end.column+=m.value.length,e(m,"tag-close")){$.selfClosing=m.value=="/>";break}return $}if(e(m,"tag-close"))return $.selfClosing=m.value=="/>",$;$.start.column+=m.value.length}return null},this._findEndTagInLine=function(i,d,f,$){for(var S=i.getTokens(d),m=0,v=0;v<S.length;v++){var E=S[v];if(m+=E.value.length,!(m<$-1)&&e(E,"end-tag-open")&&(E=S[v+1],e(E,"tag-name")&&E.value===""&&(E=S[v+2]),E&&E.value==f))return!0}return!1},this.getFoldWidgetRange=function(i,d,f){var $=this._getFirstTagInLine(i,f);if(!$)return this.getCommentFoldWidget(i,f)&&i.getCommentFoldRange(f,i.getLine(f).length);var S=i.getMatchingTags({row:f,column:0});if(S)return new h(S.openTag.end.row,S.openTag.end.column,S.closeTag.start.row,S.closeTag.start.column)}}).call(l.prototype)}),ace.define("ace/mode/folding/cstyle",["require","exports","module","ace/lib/oop","ace/range","ace/mode/folding/fold_mode"],function(n,p,A){var e=n("../../lib/oop"),a=n("../../range").Range,h=n("./fold_mode").FoldMode,g=p.FoldMode=function(l){l&&(this.foldingStartMarker=new RegExp(this.foldingStartMarker.source.replace(/\|[^|]*?$/,"|"+l.start)),this.foldingStopMarker=new RegExp(this.foldingStopMarker.source.replace(/\|[^|]*?$/,"|"+l.end)))};e.inherits(g,h),function(){this.foldingStartMarker=/([\{\[\(])[^\}\]\)]*$|^\s*(\/\*)/,this.foldingStopMarker=/^[^\[\{\(]*([\}\]\)])|^[\s\*]*(\*\/)/,this.singleLineBlockCommentRe=/^\s*(\/\*).*\*\/\s*$/,this.tripleStarBlockCommentRe=/^\s*(\/\*\*\*).*\*\/\s*$/,this.startRegionRe=/^\s*(\/\*|\/\/)#?region\b/,this._getFoldWidgetBase=this.getFoldWidget,this.getFoldWidget=function(l,s,i){var d=l.getLine(i);if(this.singleLineBlockCommentRe.test(d)&&!this.startRegionRe.test(d)&&!this.tripleStarBlockCommentRe.test(d))return"";var f=this._getFoldWidgetBase(l,s,i);return!f&&this.startRegionRe.test(d)?"start":f},this.getFoldWidgetRange=function(l,s,i,d){var f=l.getLine(i);if(this.startRegionRe.test(f))return this.getCommentRegionBlock(l,f,i);var m=f.match(this.foldingStartMarker);if(m){var $=m.index;if(m[1])return this.openingBracketBlock(l,m[1],i,$);var S=l.getCommentFoldRange(i,$+m[0].length,1);return S&&!S.isMultiLine()&&(d?S=this.getSectionRange(l,i):s!="all"&&(S=null)),S}if(s!=="markbegin"){var m=f.match(this.foldingStopMarker);if(m){var $=m.index+m[0].length;return m[1]?this.closingBracketBlock(l,m[1],i,$):l.getCommentFoldRange(i,$,-1)}}},this.getSectionRange=function(l,s){var i=l.getLine(s),d=i.search(/\S/),f=s,$=i.length;s+=1;for(var S=s,m=l.getLength();++s<m;){i=l.getLine(s);var v=i.search(/\S/);if(v!==-1){if(d>v)break;var E=this.getFoldWidgetRange(l,"all",s);if(E){if(E.start.row<=f)break;if(E.isMultiLine())s=E.end.row;else if(d==v)break}S=s}}return new a(f,$,S,l.getLine(S).length)},this.getCommentRegionBlock=function(l,s,i){for(var d=s.search(/\s*$/),f=l.getLength(),$=i,S=/^\s*(?:\/\*|\/\/|--)#?(end)?region\b/,m=1;++i<f;){s=l.getLine(i);var v=S.exec(s);if(v&&(v[1]?m--:m++,!m))break}var E=i;if(E>$)return new a($,d,E,s.length)}}.call(g.prototype)}),ace.define("ace/mode/folding/javascript",["require","exports","module","ace/lib/oop","ace/mode/folding/xml","ace/mode/folding/cstyle"],function(n,p,A){var e=n("../../lib/oop"),a=n("./xml").FoldMode,h=n("./cstyle").FoldMode,g=p.FoldMode=function(l){l&&(this.foldingStartMarker=new RegExp(this.foldingStartMarker.source.replace(/\|[^|]*?$/,"|"+l.start)),this.foldingStopMarker=new RegExp(this.foldingStopMarker.source.replace(/\|[^|]*?$/,"|"+l.end))),this.xmlFoldMode=new a};e.inherits(g,h),function(){this.getFoldWidgetRangeBase=this.getFoldWidgetRange,this.getFoldWidgetBase=this.getFoldWidget,this.getFoldWidget=function(l,s,i){var d=this.getFoldWidgetBase(l,s,i);return d||this.xmlFoldMode.getFoldWidget(l,s,i)},this.getFoldWidgetRange=function(l,s,i,d){var f=this.getFoldWidgetRangeBase(l,s,i,d);return f||this.xmlFoldMode.getFoldWidgetRange(l,s,i)}}.call(g.prototype)}),ace.define("ace/mode/javascript",["require","exports","module","ace/lib/oop","ace/mode/text","ace/mode/javascript_highlight_rules","ace/mode/matching_brace_outdent","ace/worker/worker_client","ace/mode/behaviour/javascript","ace/mode/folding/javascript"],function(n,p,A){var e=n("../lib/oop"),a=n("./text").Mode,h=n("./javascript_highlight_rules").JavaScriptHighlightRules,g=n("./matching_brace_outdent").MatchingBraceOutdent,l=n("../worker/worker_client").WorkerClient,s=n("./behaviour/javascript").JavaScriptBehaviour,i=n("./folding/javascript").FoldMode,d=function(){this.HighlightRules=h,this.$outdent=new g,this.$behaviour=new s,this.foldingRules=new i};e.inherits(d,a),function(){this.lineCommentStart="//",this.blockComment={start:"/*",end:"*/"},this.$quotes={'"':'"',"'":"'","`":"`"},this.$pairQuotesAfter={"`":/\w/},this.getNextLineIndent=function(f,$,S){var m=this.$getIndent($),v=this.getTokenizer().getLineTokens($,f),E=v.tokens,P=v.state;if(E.length&&E[E.length-1].type=="comment")return m;if(f=="start"||f=="no_regex"){var R=$.match(/^.*(?:\bcase\b.*:|[\{\(\[])\s*$/);R&&(m+=S)}else if(f=="doc-start"&&(P=="start"||P=="no_regex"))return"";return m},this.checkOutdent=function(f,$,S){return this.$outdent.checkOutdent($,S)},this.autoOutdent=function(f,$,S){this.$outdent.autoOutdent($,S)},this.createWorker=function(f){var $=new l(["ace"],"ace/mode/javascript_worker","JavaScriptWorker");return $.attachToDocument(f.getDocument()),$.on("annotate",function(S){f.setAnnotations(S.data)}),$.on("terminate",function(){f.clearAnnotations()}),$},this.$id="ace/mode/javascript",this.snippetFileId="ace/snippets/javascript"}.call(d.prototype),p.Mode=d}),function(){ace.require(["ace/mode/javascript"],function(n){M&&(M.exports=n)})}()})(mr);var br={exports:{}};(function(M,b){ace.define("ace/snippets/css.snippets",["require","exports","module"],function(n,p,A){A.exports=`snippet .
	\${1} {
		\${2}
	}
snippet !
	 !important
snippet bdi:m+
	-moz-border-image: url(\${1}) \${2:0} \${3:0} \${4:0} \${5:0} \${6:stretch} \${7:stretch};
snippet bdi:m
	-moz-border-image: \${1};
snippet bdrz:m
	-moz-border-radius: \${1};
snippet bxsh:m+
	-moz-box-shadow: \${1:0} \${2:0} \${3:0} #\${4:000};
snippet bxsh:m
	-moz-box-shadow: \${1};
snippet bdi:w+
	-webkit-border-image: url(\${1}) \${2:0} \${3:0} \${4:0} \${5:0} \${6:stretch} \${7:stretch};
snippet bdi:w
	-webkit-border-image: \${1};
snippet bdrz:w
	-webkit-border-radius: \${1};
snippet bxsh:w+
	-webkit-box-shadow: \${1:0} \${2:0} \${3:0} #\${4:000};
snippet bxsh:w
	-webkit-box-shadow: \${1};
snippet @f
	@font-face {
		font-family: \${1};
		src: url(\${2});
	}
snippet @i
	@import url(\${1});
snippet @m
	@media \${1:print} {
		\${2}
	}
snippet bg+
	background: #\${1:FFF} url(\${2}) \${3:0} \${4:0} \${5:no-repeat};
snippet bga
	background-attachment: \${1};
snippet bga:f
	background-attachment: fixed;
snippet bga:s
	background-attachment: scroll;
snippet bgbk
	background-break: \${1};
snippet bgbk:bb
	background-break: bounding-box;
snippet bgbk:c
	background-break: continuous;
snippet bgbk:eb
	background-break: each-box;
snippet bgcp
	background-clip: \${1};
snippet bgcp:bb
	background-clip: border-box;
snippet bgcp:cb
	background-clip: content-box;
snippet bgcp:nc
	background-clip: no-clip;
snippet bgcp:pb
	background-clip: padding-box;
snippet bgc
	background-color: #\${1:FFF};
snippet bgc:t
	background-color: transparent;
snippet bgi
	background-image: url(\${1});
snippet bgi:n
	background-image: none;
snippet bgo
	background-origin: \${1};
snippet bgo:bb
	background-origin: border-box;
snippet bgo:cb
	background-origin: content-box;
snippet bgo:pb
	background-origin: padding-box;
snippet bgpx
	background-position-x: \${1};
snippet bgpy
	background-position-y: \${1};
snippet bgp
	background-position: \${1:0} \${2:0};
snippet bgr
	background-repeat: \${1};
snippet bgr:n
	background-repeat: no-repeat;
snippet bgr:x
	background-repeat: repeat-x;
snippet bgr:y
	background-repeat: repeat-y;
snippet bgr:r
	background-repeat: repeat;
snippet bgz
	background-size: \${1};
snippet bgz:a
	background-size: auto;
snippet bgz:ct
	background-size: contain;
snippet bgz:cv
	background-size: cover;
snippet bg
	background: \${1};
snippet bg:ie
	filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='\${1}',sizingMethod='\${2:crop}');
snippet bg:n
	background: none;
snippet bd+
	border: \${1:1px} \${2:solid} #\${3:000};
snippet bdb+
	border-bottom: \${1:1px} \${2:solid} #\${3:000};
snippet bdbc
	border-bottom-color: #\${1:000};
snippet bdbi
	border-bottom-image: url(\${1});
snippet bdbi:n
	border-bottom-image: none;
snippet bdbli
	border-bottom-left-image: url(\${1});
snippet bdbli:c
	border-bottom-left-image: continue;
snippet bdbli:n
	border-bottom-left-image: none;
snippet bdblrz
	border-bottom-left-radius: \${1};
snippet bdbri
	border-bottom-right-image: url(\${1});
snippet bdbri:c
	border-bottom-right-image: continue;
snippet bdbri:n
	border-bottom-right-image: none;
snippet bdbrrz
	border-bottom-right-radius: \${1};
snippet bdbs
	border-bottom-style: \${1};
snippet bdbs:n
	border-bottom-style: none;
snippet bdbw
	border-bottom-width: \${1};
snippet bdb
	border-bottom: \${1};
snippet bdb:n
	border-bottom: none;
snippet bdbk
	border-break: \${1};
snippet bdbk:c
	border-break: close;
snippet bdcl
	border-collapse: \${1};
snippet bdcl:c
	border-collapse: collapse;
snippet bdcl:s
	border-collapse: separate;
snippet bdc
	border-color: #\${1:000};
snippet bdci
	border-corner-image: url(\${1});
snippet bdci:c
	border-corner-image: continue;
snippet bdci:n
	border-corner-image: none;
snippet bdf
	border-fit: \${1};
snippet bdf:c
	border-fit: clip;
snippet bdf:of
	border-fit: overwrite;
snippet bdf:ow
	border-fit: overwrite;
snippet bdf:r
	border-fit: repeat;
snippet bdf:sc
	border-fit: scale;
snippet bdf:sp
	border-fit: space;
snippet bdf:st
	border-fit: stretch;
snippet bdi
	border-image: url(\${1}) \${2:0} \${3:0} \${4:0} \${5:0} \${6:stretch} \${7:stretch};
snippet bdi:n
	border-image: none;
snippet bdl+
	border-left: \${1:1px} \${2:solid} #\${3:000};
snippet bdlc
	border-left-color: #\${1:000};
snippet bdli
	border-left-image: url(\${1});
snippet bdli:n
	border-left-image: none;
snippet bdls
	border-left-style: \${1};
snippet bdls:n
	border-left-style: none;
snippet bdlw
	border-left-width: \${1};
snippet bdl
	border-left: \${1};
snippet bdl:n
	border-left: none;
snippet bdlt
	border-length: \${1};
snippet bdlt:a
	border-length: auto;
snippet bdrz
	border-radius: \${1};
snippet bdr+
	border-right: \${1:1px} \${2:solid} #\${3:000};
snippet bdrc
	border-right-color: #\${1:000};
snippet bdri
	border-right-image: url(\${1});
snippet bdri:n
	border-right-image: none;
snippet bdrs
	border-right-style: \${1};
snippet bdrs:n
	border-right-style: none;
snippet bdrw
	border-right-width: \${1};
snippet bdr
	border-right: \${1};
snippet bdr:n
	border-right: none;
snippet bdsp
	border-spacing: \${1};
snippet bds
	border-style: \${1};
snippet bds:ds
	border-style: dashed;
snippet bds:dtds
	border-style: dot-dash;
snippet bds:dtdtds
	border-style: dot-dot-dash;
snippet bds:dt
	border-style: dotted;
snippet bds:db
	border-style: double;
snippet bds:g
	border-style: groove;
snippet bds:h
	border-style: hidden;
snippet bds:i
	border-style: inset;
snippet bds:n
	border-style: none;
snippet bds:o
	border-style: outset;
snippet bds:r
	border-style: ridge;
snippet bds:s
	border-style: solid;
snippet bds:w
	border-style: wave;
snippet bdt+
	border-top: \${1:1px} \${2:solid} #\${3:000};
snippet bdtc
	border-top-color: #\${1:000};
snippet bdti
	border-top-image: url(\${1});
snippet bdti:n
	border-top-image: none;
snippet bdtli
	border-top-left-image: url(\${1});
snippet bdtli:c
	border-corner-image: continue;
snippet bdtli:n
	border-corner-image: none;
snippet bdtlrz
	border-top-left-radius: \${1};
snippet bdtri
	border-top-right-image: url(\${1});
snippet bdtri:c
	border-top-right-image: continue;
snippet bdtri:n
	border-top-right-image: none;
snippet bdtrrz
	border-top-right-radius: \${1};
snippet bdts
	border-top-style: \${1};
snippet bdts:n
	border-top-style: none;
snippet bdtw
	border-top-width: \${1};
snippet bdt
	border-top: \${1};
snippet bdt:n
	border-top: none;
snippet bdw
	border-width: \${1};
snippet bd
	border: \${1};
snippet bd:n
	border: none;
snippet b
	bottom: \${1};
snippet b:a
	bottom: auto;
snippet bxsh+
	box-shadow: \${1:0} \${2:0} \${3:0} #\${4:000};
snippet bxsh
	box-shadow: \${1};
snippet bxsh:n
	box-shadow: none;
snippet bxz
	box-sizing: \${1};
snippet bxz:bb
	box-sizing: border-box;
snippet bxz:cb
	box-sizing: content-box;
snippet cps
	caption-side: \${1};
snippet cps:b
	caption-side: bottom;
snippet cps:t
	caption-side: top;
snippet cl
	clear: \${1};
snippet cl:b
	clear: both;
snippet cl:l
	clear: left;
snippet cl:n
	clear: none;
snippet cl:r
	clear: right;
snippet cp
	clip: \${1};
snippet cp:a
	clip: auto;
snippet cp:r
	clip: rect(\${1:0} \${2:0} \${3:0} \${4:0});
snippet c
	color: #\${1:000};
snippet ct
	content: \${1};
snippet ct:a
	content: attr(\${1});
snippet ct:cq
	content: close-quote;
snippet ct:c
	content: counter(\${1});
snippet ct:cs
	content: counters(\${1});
snippet ct:ncq
	content: no-close-quote;
snippet ct:noq
	content: no-open-quote;
snippet ct:n
	content: normal;
snippet ct:oq
	content: open-quote;
snippet coi
	counter-increment: \${1};
snippet cor
	counter-reset: \${1};
snippet cur
	cursor: \${1};
snippet cur:a
	cursor: auto;
snippet cur:c
	cursor: crosshair;
snippet cur:d
	cursor: default;
snippet cur:ha
	cursor: hand;
snippet cur:he
	cursor: help;
snippet cur:m
	cursor: move;
snippet cur:p
	cursor: pointer;
snippet cur:t
	cursor: text;
snippet d
	display: \${1};
snippet d:mib
	display: -moz-inline-box;
snippet d:mis
	display: -moz-inline-stack;
snippet d:b
	display: block;
snippet d:cp
	display: compact;
snippet d:ib
	display: inline-block;
snippet d:itb
	display: inline-table;
snippet d:i
	display: inline;
snippet d:li
	display: list-item;
snippet d:n
	display: none;
snippet d:ri
	display: run-in;
snippet d:tbcp
	display: table-caption;
snippet d:tbc
	display: table-cell;
snippet d:tbclg
	display: table-column-group;
snippet d:tbcl
	display: table-column;
snippet d:tbfg
	display: table-footer-group;
snippet d:tbhg
	display: table-header-group;
snippet d:tbrg
	display: table-row-group;
snippet d:tbr
	display: table-row;
snippet d:tb
	display: table;
snippet ec
	empty-cells: \${1};
snippet ec:h
	empty-cells: hide;
snippet ec:s
	empty-cells: show;
snippet exp
	expression()
snippet fl
	float: \${1};
snippet fl:l
	float: left;
snippet fl:n
	float: none;
snippet fl:r
	float: right;
snippet f+
	font: \${1:1em} \${2:Arial},\${3:sans-serif};
snippet fef
	font-effect: \${1};
snippet fef:eb
	font-effect: emboss;
snippet fef:eg
	font-effect: engrave;
snippet fef:n
	font-effect: none;
snippet fef:o
	font-effect: outline;
snippet femp
	font-emphasize-position: \${1};
snippet femp:a
	font-emphasize-position: after;
snippet femp:b
	font-emphasize-position: before;
snippet fems
	font-emphasize-style: \${1};
snippet fems:ac
	font-emphasize-style: accent;
snippet fems:c
	font-emphasize-style: circle;
snippet fems:ds
	font-emphasize-style: disc;
snippet fems:dt
	font-emphasize-style: dot;
snippet fems:n
	font-emphasize-style: none;
snippet fem
	font-emphasize: \${1};
snippet ff
	font-family: \${1};
snippet ff:c
	font-family: \${1:'Monotype Corsiva','Comic Sans MS'},cursive;
snippet ff:f
	font-family: \${1:Capitals,Impact},fantasy;
snippet ff:m
	font-family: \${1:Monaco,'Courier New'},monospace;
snippet ff:ss
	font-family: \${1:Helvetica,Arial},sans-serif;
snippet ff:s
	font-family: \${1:Georgia,'Times New Roman'},serif;
snippet fza
	font-size-adjust: \${1};
snippet fza:n
	font-size-adjust: none;
snippet fz
	font-size: \${1};
snippet fsm
	font-smooth: \${1};
snippet fsm:aw
	font-smooth: always;
snippet fsm:a
	font-smooth: auto;
snippet fsm:n
	font-smooth: never;
snippet fst
	font-stretch: \${1};
snippet fst:c
	font-stretch: condensed;
snippet fst:e
	font-stretch: expanded;
snippet fst:ec
	font-stretch: extra-condensed;
snippet fst:ee
	font-stretch: extra-expanded;
snippet fst:n
	font-stretch: normal;
snippet fst:sc
	font-stretch: semi-condensed;
snippet fst:se
	font-stretch: semi-expanded;
snippet fst:uc
	font-stretch: ultra-condensed;
snippet fst:ue
	font-stretch: ultra-expanded;
snippet fs
	font-style: \${1};
snippet fs:i
	font-style: italic;
snippet fs:n
	font-style: normal;
snippet fs:o
	font-style: oblique;
snippet fv
	font-variant: \${1};
snippet fv:n
	font-variant: normal;
snippet fv:sc
	font-variant: small-caps;
snippet fw
	font-weight: \${1};
snippet fw:b
	font-weight: bold;
snippet fw:br
	font-weight: bolder;
snippet fw:lr
	font-weight: lighter;
snippet fw:n
	font-weight: normal;
snippet f
	font: \${1};
snippet h
	height: \${1};
snippet h:a
	height: auto;
snippet l
	left: \${1};
snippet l:a
	left: auto;
snippet lts
	letter-spacing: \${1};
snippet lh
	line-height: \${1};
snippet lisi
	list-style-image: url(\${1});
snippet lisi:n
	list-style-image: none;
snippet lisp
	list-style-position: \${1};
snippet lisp:i
	list-style-position: inside;
snippet lisp:o
	list-style-position: outside;
snippet list
	list-style-type: \${1};
snippet list:c
	list-style-type: circle;
snippet list:dclz
	list-style-type: decimal-leading-zero;
snippet list:dc
	list-style-type: decimal;
snippet list:d
	list-style-type: disc;
snippet list:lr
	list-style-type: lower-roman;
snippet list:n
	list-style-type: none;
snippet list:s
	list-style-type: square;
snippet list:ur
	list-style-type: upper-roman;
snippet lis
	list-style: \${1};
snippet lis:n
	list-style: none;
snippet mb
	margin-bottom: \${1};
snippet mb:a
	margin-bottom: auto;
snippet ml
	margin-left: \${1};
snippet ml:a
	margin-left: auto;
snippet mr
	margin-right: \${1};
snippet mr:a
	margin-right: auto;
snippet mt
	margin-top: \${1};
snippet mt:a
	margin-top: auto;
snippet m
	margin: \${1};
snippet m:4
	margin: \${1:0} \${2:0} \${3:0} \${4:0};
snippet m:3
	margin: \${1:0} \${2:0} \${3:0};
snippet m:2
	margin: \${1:0} \${2:0};
snippet m:0
	margin: 0;
snippet m:a
	margin: auto;
snippet mah
	max-height: \${1};
snippet mah:n
	max-height: none;
snippet maw
	max-width: \${1};
snippet maw:n
	max-width: none;
snippet mih
	min-height: \${1};
snippet miw
	min-width: \${1};
snippet op
	opacity: \${1};
snippet op:ie
	filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=\${1:100});
snippet op:ms
	-ms-filter: 'progid:DXImageTransform.Microsoft.Alpha(Opacity=\${1:100})';
snippet orp
	orphans: \${1};
snippet o+
	outline: \${1:1px} \${2:solid} #\${3:000};
snippet oc
	outline-color: \${1:#000};
snippet oc:i
	outline-color: invert;
snippet oo
	outline-offset: \${1};
snippet os
	outline-style: \${1};
snippet ow
	outline-width: \${1};
snippet o
	outline: \${1};
snippet o:n
	outline: none;
snippet ovs
	overflow-style: \${1};
snippet ovs:a
	overflow-style: auto;
snippet ovs:mq
	overflow-style: marquee;
snippet ovs:mv
	overflow-style: move;
snippet ovs:p
	overflow-style: panner;
snippet ovs:s
	overflow-style: scrollbar;
snippet ovx
	overflow-x: \${1};
snippet ovx:a
	overflow-x: auto;
snippet ovx:h
	overflow-x: hidden;
snippet ovx:s
	overflow-x: scroll;
snippet ovx:v
	overflow-x: visible;
snippet ovy
	overflow-y: \${1};
snippet ovy:a
	overflow-y: auto;
snippet ovy:h
	overflow-y: hidden;
snippet ovy:s
	overflow-y: scroll;
snippet ovy:v
	overflow-y: visible;
snippet ov
	overflow: \${1};
snippet ov:a
	overflow: auto;
snippet ov:h
	overflow: hidden;
snippet ov:s
	overflow: scroll;
snippet ov:v
	overflow: visible;
snippet pb
	padding-bottom: \${1};
snippet pl
	padding-left: \${1};
snippet pr
	padding-right: \${1};
snippet pt
	padding-top: \${1};
snippet p
	padding: \${1};
snippet p:4
	padding: \${1:0} \${2:0} \${3:0} \${4:0};
snippet p:3
	padding: \${1:0} \${2:0} \${3:0};
snippet p:2
	padding: \${1:0} \${2:0};
snippet p:0
	padding: 0;
snippet pgba
	page-break-after: \${1};
snippet pgba:aw
	page-break-after: always;
snippet pgba:a
	page-break-after: auto;
snippet pgba:l
	page-break-after: left;
snippet pgba:r
	page-break-after: right;
snippet pgbb
	page-break-before: \${1};
snippet pgbb:aw
	page-break-before: always;
snippet pgbb:a
	page-break-before: auto;
snippet pgbb:l
	page-break-before: left;
snippet pgbb:r
	page-break-before: right;
snippet pgbi
	page-break-inside: \${1};
snippet pgbi:a
	page-break-inside: auto;
snippet pgbi:av
	page-break-inside: avoid;
snippet pos
	position: \${1};
snippet pos:a
	position: absolute;
snippet pos:f
	position: fixed;
snippet pos:r
	position: relative;
snippet pos:s
	position: static;
snippet q
	quotes: \${1};
snippet q:en
	quotes: '\\201C' '\\201D' '\\2018' '\\2019';
snippet q:n
	quotes: none;
snippet q:ru
	quotes: '\\00AB' '\\00BB' '\\201E' '\\201C';
snippet rz
	resize: \${1};
snippet rz:b
	resize: both;
snippet rz:h
	resize: horizontal;
snippet rz:n
	resize: none;
snippet rz:v
	resize: vertical;
snippet r
	right: \${1};
snippet r:a
	right: auto;
snippet tbl
	table-layout: \${1};
snippet tbl:a
	table-layout: auto;
snippet tbl:f
	table-layout: fixed;
snippet tal
	text-align-last: \${1};
snippet tal:a
	text-align-last: auto;
snippet tal:c
	text-align-last: center;
snippet tal:l
	text-align-last: left;
snippet tal:r
	text-align-last: right;
snippet ta
	text-align: \${1};
snippet ta:c
	text-align: center;
snippet ta:l
	text-align: left;
snippet ta:r
	text-align: right;
snippet td
	text-decoration: \${1};
snippet td:l
	text-decoration: line-through;
snippet td:n
	text-decoration: none;
snippet td:o
	text-decoration: overline;
snippet td:u
	text-decoration: underline;
snippet te
	text-emphasis: \${1};
snippet te:ac
	text-emphasis: accent;
snippet te:a
	text-emphasis: after;
snippet te:b
	text-emphasis: before;
snippet te:c
	text-emphasis: circle;
snippet te:ds
	text-emphasis: disc;
snippet te:dt
	text-emphasis: dot;
snippet te:n
	text-emphasis: none;
snippet th
	text-height: \${1};
snippet th:a
	text-height: auto;
snippet th:f
	text-height: font-size;
snippet th:m
	text-height: max-size;
snippet th:t
	text-height: text-size;
snippet ti
	text-indent: \${1};
snippet ti:-
	text-indent: -9999px;
snippet tj
	text-justify: \${1};
snippet tj:a
	text-justify: auto;
snippet tj:d
	text-justify: distribute;
snippet tj:ic
	text-justify: inter-cluster;
snippet tj:ii
	text-justify: inter-ideograph;
snippet tj:iw
	text-justify: inter-word;
snippet tj:k
	text-justify: kashida;
snippet tj:t
	text-justify: tibetan;
snippet to+
	text-outline: \${1:0} \${2:0} #\${3:000};
snippet to
	text-outline: \${1};
snippet to:n
	text-outline: none;
snippet tr
	text-replace: \${1};
snippet tr:n
	text-replace: none;
snippet tsh+
	text-shadow: \${1:0} \${2:0} \${3:0} #\${4:000};
snippet tsh
	text-shadow: \${1};
snippet tsh:n
	text-shadow: none;
snippet tt
	text-transform: \${1};
snippet tt:c
	text-transform: capitalize;
snippet tt:l
	text-transform: lowercase;
snippet tt:n
	text-transform: none;
snippet tt:u
	text-transform: uppercase;
snippet tw
	text-wrap: \${1};
snippet tw:no
	text-wrap: none;
snippet tw:n
	text-wrap: normal;
snippet tw:s
	text-wrap: suppress;
snippet tw:u
	text-wrap: unrestricted;
snippet t
	top: \${1};
snippet t:a
	top: auto;
snippet va
	vertical-align: \${1};
snippet va:bl
	vertical-align: baseline;
snippet va:b
	vertical-align: bottom;
snippet va:m
	vertical-align: middle;
snippet va:sub
	vertical-align: sub;
snippet va:sup
	vertical-align: super;
snippet va:tb
	vertical-align: text-bottom;
snippet va:tt
	vertical-align: text-top;
snippet va:t
	vertical-align: top;
snippet v
	visibility: \${1};
snippet v:c
	visibility: collapse;
snippet v:h
	visibility: hidden;
snippet v:v
	visibility: visible;
snippet whsc
	white-space-collapse: \${1};
snippet whsc:ba
	white-space-collapse: break-all;
snippet whsc:bs
	white-space-collapse: break-strict;
snippet whsc:k
	white-space-collapse: keep-all;
snippet whsc:l
	white-space-collapse: loose;
snippet whsc:n
	white-space-collapse: normal;
snippet whs
	white-space: \${1};
snippet whs:n
	white-space: normal;
snippet whs:nw
	white-space: nowrap;
snippet whs:pl
	white-space: pre-line;
snippet whs:pw
	white-space: pre-wrap;
snippet whs:p
	white-space: pre;
snippet wid
	widows: \${1};
snippet w
	width: \${1};
snippet w:a
	width: auto;
snippet wob
	word-break: \${1};
snippet wob:ba
	word-break: break-all;
snippet wob:bs
	word-break: break-strict;
snippet wob:k
	word-break: keep-all;
snippet wob:l
	word-break: loose;
snippet wob:n
	word-break: normal;
snippet wos
	word-spacing: \${1};
snippet wow
	word-wrap: \${1};
snippet wow:no
	word-wrap: none;
snippet wow:n
	word-wrap: normal;
snippet wow:s
	word-wrap: suppress;
snippet wow:u
	word-wrap: unrestricted;
snippet z
	z-index: \${1};
snippet z:a
	z-index: auto;
snippet zoo
	zoom: 1;
`}),ace.define("ace/snippets/css",["require","exports","module","ace/snippets/css.snippets"],function(n,p,A){p.snippetText=n("./css.snippets"),p.scope="css"}),function(){ace.require(["ace/snippets/css"],function(n){M&&(M.exports=n)})}()})(br);var vr={exports:{}};(function(M,b){ace.define("ace/snippets/javascript.snippets",["require","exports","module"],function(n,p,A){A.exports=`# Prototype
snippet proto
	\${1:class_name}.prototype.\${2:method_name} = function(\${3:first_argument}) {
		\${4:// body...}
	};
# Function
snippet fun
	function \${1?:function_name}(\${2:argument}) {
		\${3:// body...}
	}
# Anonymous Function
regex /((=)\\s*|(:)\\s*|(\\()|\\b)/f/(\\))?/
snippet f
	function\${M1?: \${1:functionName}}($2) {
		\${0:$TM_SELECTED_TEXT}
	}\${M2?;}\${M3?,}\${M4?)}
# Immediate function
trigger \\(?f\\(
endTrigger \\)?
snippet f(
	(function(\${1}) {
		\${0:\${TM_SELECTED_TEXT:/* code */}}
	}(\${1}));
# if
snippet if
	if (\${1:true}) {
		\${0}
	}
# if ... else
snippet ife
	if (\${1:true}) {
		\${2}
	} else {
		\${0}
	}
# tertiary conditional
snippet ter
	\${1:/* condition */} ? \${2:a} : \${3:b}
# switch
snippet switch
	switch (\${1:expression}) {
		case '\${3:case}':
			\${4:// code}
			break;
		\${5}
		default:
			\${2:// code}
	}
# case
snippet case
	case '\${1:case}':
		\${2:// code}
		break;
	\${3}

# while (...) {...}
snippet wh
	while (\${1:/* condition */}) {
		\${0:/* code */}
	}
# try
snippet try
	try {
		\${0:/* code */}
	} catch (e) {}
# do...while
snippet do
	do {
		\${2:/* code */}
	} while (\${1:/* condition */});
# Object Method
snippet :f
regex /([,{[])|^\\s*/:f/
	\${1:method_name}: function(\${2:attribute}) {
		\${0}
	}\${3:,}
# setTimeout function
snippet setTimeout
regex /\\b/st|timeout|setTimeo?u?t?/
	setTimeout(function() {\${3:$TM_SELECTED_TEXT}}, \${1:10});
# Get Elements
snippet gett
	getElementsBy\${1:TagName}('\${2}')\${3}
# Get Element
snippet get
	getElementBy\${1:Id}('\${2}')\${3}
# console.log (Firebug)
snippet cl
	console.log(\${1});
# return
snippet ret
	return \${1:result}
# for (property in object ) { ... }
snippet fori
	for (var \${1:prop} in \${2:Things}) {
		\${0:$2[$1]}
	}
# hasOwnProperty
snippet has
	hasOwnProperty(\${1})
# docstring
snippet /**
	/**
	 * \${1:description}
	 *
	 */
snippet @par
regex /^\\s*\\*\\s*/@(para?m?)?/
	@param {\${1:type}} \${2:name} \${3:description}
snippet @ret
	@return {\${1:type}} \${2:description}
# JSON.parse
snippet jsonp
	JSON.parse(\${1:jstr});
# JSON.stringify
snippet jsons
	JSON.stringify(\${1:object});
# self-defining function
snippet sdf
	var \${1:function_name} = function(\${2:argument}) {
		\${3:// initial code ...}

		$1 = function($2) {
			\${4:// main code}
		};
	}
# singleton
snippet sing
	function \${1:Singleton} (\${2:argument}) {
		// the cached instance
		var instance;

		// rewrite the constructor
		$1 = function $1($2) {
			return instance;
		};
		
		// carry over the prototype properties
		$1.prototype = this;

		// the instance
		instance = new $1();

		// reset the constructor pointer
		instance.constructor = $1;

		\${3:// code ...}

		return instance;
	}
# class
snippet class
regex /^\\s*/clas{0,2}/
	var \${1:class} = function(\${20}) {
		$40$0
	};
	
	(function() {
		\${60:this.prop = ""}
	}).call(\${1:class}.prototype);
	
	exports.\${1:class} = \${1:class};
# 
snippet for-
	for (var \${1:i} = \${2:Things}.length; \${1:i}--; ) {
		\${0:\${2:Things}[\${1:i}];}
	}
# for (...) {...}
snippet for
	for (var \${1:i} = 0; $1 < \${2:Things}.length; $1++) {
		\${3:$2[$1]}$0
	}
# for (...) {...} (Improved Native For-Loop)
snippet forr
	for (var \${1:i} = \${2:Things}.length - 1; $1 >= 0; $1--) {
		\${3:$2[$1]}$0
	}


#modules
snippet def
	define(function(require, exports, module) {
	"use strict";
	var \${1/.*\\///} = require("\${1}");
	
	$TM_SELECTED_TEXT
	});
snippet req
guard ^\\s*
	var \${1/.*\\///} = require("\${1}");
	$0
snippet requ
guard ^\\s*
	var \${1/.*\\/(.)/\\u$1/} = require("\${1}").\${1/.*\\/(.)/\\u$1/};
	$0
`}),ace.define("ace/snippets/javascript",["require","exports","module","ace/snippets/javascript.snippets"],function(n,p,A){p.snippetText=n("./javascript.snippets"),p.scope="javascript"}),function(){ace.require(["ace/snippets/javascript"],function(n){M&&(M.exports=n)})}()})(vr);var yr={exports:{}};(function(M,b){ace.define("ace/theme/tomorrow-css",["require","exports","module"],function(n,p,A){A.exports=`.ace-tomorrow .ace_gutter {
  background: #f6f6f6;
  color: #4D4D4C
}

.ace-tomorrow .ace_print-margin {
  width: 1px;
  background: #f6f6f6
}

.ace-tomorrow {
  background-color: #FFFFFF;
  color: #4D4D4C
}

.ace-tomorrow .ace_cursor {
  color: #AEAFAD
}

.ace-tomorrow .ace_marker-layer .ace_selection {
  background: #D6D6D6
}

.ace-tomorrow.ace_multiselect .ace_selection.ace_start {
  box-shadow: 0 0 3px 0px #FFFFFF;
}

.ace-tomorrow .ace_marker-layer .ace_step {
  background: rgb(255, 255, 0)
}

.ace-tomorrow .ace_marker-layer .ace_bracket {
  margin: -1px 0 0 -1px;
  border: 1px solid #D1D1D1
}

.ace-tomorrow .ace_marker-layer .ace_active-line {
  background: #EFEFEF
}

.ace-tomorrow .ace_gutter-active-line {
  background-color : #dcdcdc
}

.ace-tomorrow .ace_marker-layer .ace_selected-word {
  border: 1px solid #D6D6D6
}

.ace-tomorrow .ace_invisible {
  color: #D1D1D1
}

.ace-tomorrow .ace_keyword,
.ace-tomorrow .ace_meta,
.ace-tomorrow .ace_storage,
.ace-tomorrow .ace_storage.ace_type,
.ace-tomorrow .ace_support.ace_type {
  color: #8959A8
}

.ace-tomorrow .ace_keyword.ace_operator {
  color: #3E999F
}

.ace-tomorrow .ace_constant.ace_character,
.ace-tomorrow .ace_constant.ace_language,
.ace-tomorrow .ace_constant.ace_numeric,
.ace-tomorrow .ace_keyword.ace_other.ace_unit,
.ace-tomorrow .ace_support.ace_constant,
.ace-tomorrow .ace_variable.ace_parameter {
  color: #F5871F
}

.ace-tomorrow .ace_constant.ace_other {
  color: #666969
}

.ace-tomorrow .ace_invalid {
  color: #FFFFFF;
  background-color: #C82829
}

.ace-tomorrow .ace_invalid.ace_deprecated {
  color: #FFFFFF;
  background-color: #8959A8
}

.ace-tomorrow .ace_fold {
  background-color: #4271AE;
  border-color: #4D4D4C
}

.ace-tomorrow .ace_entity.ace_name.ace_function,
.ace-tomorrow .ace_support.ace_function,
.ace-tomorrow .ace_variable {
  color: #4271AE
}

.ace-tomorrow .ace_support.ace_class,
.ace-tomorrow .ace_support.ace_type {
  color: #C99E00
}

.ace-tomorrow .ace_heading,
.ace-tomorrow .ace_markup.ace_heading,
.ace-tomorrow .ace_string {
  color: #718C00
}

.ace-tomorrow .ace_entity.ace_name.ace_tag,
.ace-tomorrow .ace_entity.ace_other.ace_attribute-name,
.ace-tomorrow .ace_meta.ace_tag,
.ace-tomorrow .ace_string.ace_regexp,
.ace-tomorrow .ace_variable {
  color: #C82829
}

.ace-tomorrow .ace_comment {
  color: #8E908C
}

.ace-tomorrow .ace_indent-guide {
  background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAACCAYAAACZgbYnAAAAE0lEQVQImWP4////f4bdu3f/BwAlfgctduB85QAAAABJRU5ErkJggg==) right repeat-y
}

.ace-tomorrow .ace_indent-guide-active {
  background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAACCAYAAACZgbYnAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAAAZSURBVHjaYvj///9/hivKyv8BAAAA//8DACLqBhbvk+/eAAAAAElFTkSuQmCC") right repeat-y;
} 
`}),ace.define("ace/theme/tomorrow",["require","exports","module","ace/theme/tomorrow-css","ace/lib/dom"],function(n,p,A){p.isDark=!1,p.cssClass="ace-tomorrow",p.cssText=n("./tomorrow-css");var e=n("../lib/dom");e.importCssString(p.cssText,p.cssClass,!1)}),function(){ace.require(["ace/theme/tomorrow"],function(n){M&&(M.exports=n)})}()})(yr);var xr={exports:{}};(function(M,b){ace.define("ace/theme/twilight-css",["require","exports","module"],function(n,p,A){A.exports=`.ace-twilight .ace_gutter {
  background: #232323;
  color: #E2E2E2
}

.ace-twilight .ace_print-margin {
  width: 1px;
  background: #232323
}

.ace-twilight {
  background-color: #141414;
  color: #F8F8F8
}

.ace-twilight .ace_cursor {
  color: #A7A7A7
}

.ace-twilight .ace_marker-layer .ace_selection {
  background: rgba(221, 240, 255, 0.20)
}

.ace-twilight.ace_multiselect .ace_selection.ace_start {
  box-shadow: 0 0 3px 0px #141414;
}

.ace-twilight .ace_marker-layer .ace_step {
  background: rgb(102, 82, 0)
}

.ace-twilight .ace_marker-layer .ace_bracket {
  margin: -1px 0 0 -1px;
  border: 1px solid rgba(255, 255, 255, 0.25)
}

.ace-twilight .ace_marker-layer .ace_active-line {
  background: rgba(255, 255, 255, 0.031)
}

.ace-twilight .ace_gutter-active-line {
  background-color: rgba(255, 255, 255, 0.031)
}

.ace-twilight .ace_marker-layer .ace_selected-word {
  border: 1px solid rgba(221, 240, 255, 0.20)
}

.ace-twilight .ace_invisible {
  color: rgba(255, 255, 255, 0.25)
}

.ace-twilight .ace_keyword,
.ace-twilight .ace_meta {
  color: #CDA869
}

.ace-twilight .ace_constant,
.ace-twilight .ace_constant.ace_character,
.ace-twilight .ace_constant.ace_character.ace_escape,
.ace-twilight .ace_constant.ace_other,
.ace-twilight .ace_heading,
.ace-twilight .ace_markup.ace_heading,
.ace-twilight .ace_support.ace_constant {
  color: #CF6A4C
}

.ace-twilight .ace_invalid.ace_illegal {
  color: #F8F8F8;
  background-color: rgba(86, 45, 86, 0.75)
}

.ace-twilight .ace_invalid.ace_deprecated {
  text-decoration: underline;
  font-style: italic;
  color: #D2A8A1
}

.ace-twilight .ace_support {
  color: #9B859D
}

.ace-twilight .ace_fold {
  background-color: #AC885B;
  border-color: #F8F8F8
}

.ace-twilight .ace_support.ace_function {
  color: #DAD085
}

.ace-twilight .ace_list,
.ace-twilight .ace_markup.ace_list,
.ace-twilight .ace_storage {
  color: #F9EE98
}

.ace-twilight .ace_entity.ace_name.ace_function,
.ace-twilight .ace_meta.ace_tag {
  color: #AC885B
}

.ace-twilight .ace_string {
  color: #8F9D6A
}

.ace-twilight .ace_string.ace_regexp {
  color: #E9C062
}

.ace-twilight .ace_comment {
  font-style: italic;
  color: #5F5A60
}

.ace-twilight .ace_variable {
  color: #7587A6
}

.ace-twilight .ace_xml-pe {
  color: #494949
}

.ace-twilight .ace_indent-guide {
  background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAACCAYAAACZgbYnAAAAEklEQVQImWMQERFpYLC1tf0PAAgOAnPnhxyiAAAAAElFTkSuQmCC) right repeat-y
}

.ace-twilight .ace_indent-guide-active {
  background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAACCAYAAACZgbYnAAAAEklEQVQIW2PQ1dX9zzBz5sz/ABCcBFFentLlAAAAAElFTkSuQmCC) right repeat-y;
}
`}),ace.define("ace/theme/twilight",["require","exports","module","ace/theme/twilight-css","ace/lib/dom"],function(n,p,A){p.isDark=!0,p.cssClass="ace-twilight",p.cssText=n("./twilight-css");var e=n("../lib/dom");e.importCssString(p.cssText,p.cssClass,!1)}),function(){ace.require(["ace/theme/twilight"],function(n){M&&(M.exports=n)})}()})(xr);var wr={exports:{}};(function(M,b){ace.define("ace/snippets",["require","exports","module","ace/lib/dom","ace/lib/oop","ace/lib/event_emitter","ace/lib/lang","ace/range","ace/range_list","ace/keyboard/hash_handler","ace/tokenizer","ace/clipboard","ace/editor"],function(n,p,A){function e(t){var r=new Date().toLocaleString("en-us",t);return r.length==1?"0"+r:r}var a=n("./lib/dom"),h=n("./lib/oop"),g=n("./lib/event_emitter").EventEmitter,l=n("./lib/lang"),s=n("./range").Range,i=n("./range_list").RangeList,d=n("./keyboard/hash_handler").HashHandler,f=n("./tokenizer").Tokenizer,$=n("./clipboard"),S={CURRENT_WORD:function(t){return t.session.getTextRange(t.session.getWordRange())},SELECTION:function(t,r,o){var c=t.session.getTextRange();return o?c.replace(/\n\r?([ \t]*\S)/g,`
`+o+"$1"):c},CURRENT_LINE:function(t){return t.session.getLine(t.getCursorPosition().row)},PREV_LINE:function(t){return t.session.getLine(t.getCursorPosition().row-1)},LINE_INDEX:function(t){return t.getCursorPosition().row},LINE_NUMBER:function(t){return t.getCursorPosition().row+1},SOFT_TABS:function(t){return t.session.getUseSoftTabs()?"YES":"NO"},TAB_SIZE:function(t){return t.session.getTabSize()},CLIPBOARD:function(t){return $.getText&&$.getText()},FILENAME:function(t){return/[^/\\]*$/.exec(this.FILEPATH(t))[0]},FILENAME_BASE:function(t){return/[^/\\]*$/.exec(this.FILEPATH(t))[0].replace(/\.[^.]*$/,"")},DIRECTORY:function(t){return this.FILEPATH(t).replace(/[^/\\]*$/,"")},FILEPATH:function(t){return"/not implemented.txt"},WORKSPACE_NAME:function(){return"Unknown"},FULLNAME:function(){return"Unknown"},BLOCK_COMMENT_START:function(t){var r=t.session.$mode||{};return r.blockComment&&r.blockComment.start||""},BLOCK_COMMENT_END:function(t){var r=t.session.$mode||{};return r.blockComment&&r.blockComment.end||""},LINE_COMMENT:function(t){var r=t.session.$mode||{};return r.lineCommentStart||""},CURRENT_YEAR:e.bind(null,{year:"numeric"}),CURRENT_YEAR_SHORT:e.bind(null,{year:"2-digit"}),CURRENT_MONTH:e.bind(null,{month:"numeric"}),CURRENT_MONTH_NAME:e.bind(null,{month:"long"}),CURRENT_MONTH_NAME_SHORT:e.bind(null,{month:"short"}),CURRENT_DATE:e.bind(null,{day:"2-digit"}),CURRENT_DAY_NAME:e.bind(null,{weekday:"long"}),CURRENT_DAY_NAME_SHORT:e.bind(null,{weekday:"short"}),CURRENT_HOUR:e.bind(null,{hour:"2-digit",hour12:!1}),CURRENT_MINUTE:e.bind(null,{minute:"2-digit"}),CURRENT_SECOND:e.bind(null,{second:"2-digit"})};S.SELECTED_TEXT=S.SELECTION;var m=function(){function t(){this.snippetMap={},this.snippetNameMap={},this.variables=S}return t.prototype.getTokenizer=function(){return t.$tokenizer||this.createTokenizer()},t.prototype.createTokenizer=function(){function r(u){return u=u.substr(1),/^\d+$/.test(u)?[{tabstopId:parseInt(u,10)}]:[{text:u}]}function o(u){return"(?:[^\\\\"+u+"]|\\\\.)"}var c={regex:"/("+o("/")+"+)/",onMatch:function(u,y,w){var C=w[0];return C.fmtString=!0,C.guard=u.slice(1,-1),C.flag="",""},next:"formatString"};return t.$tokenizer=new f({start:[{regex:/\\./,onMatch:function(u,y,w){var C=u[1];return(C=="}"&&w.length||"`$\\".indexOf(C)!=-1)&&(u=C),[u]}},{regex:/}/,onMatch:function(u,y,w){return[w.length?w.shift():u]}},{regex:/\$(?:\d+|\w+)/,onMatch:r},{regex:/\$\{[\dA-Z_a-z]+/,onMatch:function(u,y,w){var C=r(u.substr(1));return w.unshift(C[0]),C},next:"snippetVar"},{regex:/\n/,token:"newline",merge:!1}],snippetVar:[{regex:"\\|"+o("\\|")+"*\\|",onMatch:function(u,y,w){var C=u.slice(1,-1).replace(/\\[,|\\]|,/g,function(_){return _.length==2?_[1]:"\0"}).split("\0").map(function(_){return{value:_}});return w[0].choices=C,[C[0]]},next:"start"},c,{regex:"([^:}\\\\]|\\\\.)*:?",token:"",next:"start"}],formatString:[{regex:/:/,onMatch:function(u,y,w){return w.length&&w[0].expectElse?(w[0].expectElse=!1,w[0].ifEnd={elseEnd:w[0]},[w[0].ifEnd]):":"}},{regex:/\\./,onMatch:function(u,y,w){var C=u[1];return C=="}"&&w.length||"`$\\".indexOf(C)!=-1?u=C:C=="n"?u=`
`:C=="t"?u="	":"ulULE".indexOf(C)!=-1&&(u={changeCase:C,local:C>"a"}),[u]}},{regex:"/\\w*}",onMatch:function(u,y,w){var C=w.shift();return C&&(C.flag=u.slice(1,-1)),this.next=C&&C.tabstopId?"start":"",[C||u]},next:"start"},{regex:/\$(?:\d+|\w+)/,onMatch:function(u,y,w){return[{text:u.slice(1)}]}},{regex:/\${\w+/,onMatch:function(u,y,w){var C={text:u.slice(2)};return w.unshift(C),[C]},next:"formatStringVar"},{regex:/\n/,token:"newline",merge:!1},{regex:/}/,onMatch:function(u,y,w){var C=w.shift();return this.next=C&&C.tabstopId?"start":"",[C||u]},next:"start"}],formatStringVar:[{regex:/:\/\w+}/,onMatch:function(u,y,w){var C=w[0];return C.formatFunction=u.slice(2,-1),[w.shift()]},next:"formatString"},c,{regex:/:[\?\-+]?/,onMatch:function(u,y,w){u[1]=="+"&&(w[0].ifEnd=w[0]),u[1]=="?"&&(w[0].expectElse=!0)},next:"formatString"},{regex:"([^:}\\\\]|\\\\.)*:?",token:"",next:"formatString"}]}),t.$tokenizer},t.prototype.tokenizeTmSnippet=function(r,o){return this.getTokenizer().getLineTokens(r,o).tokens.map(function(c){return c.value||c})},t.prototype.getVariableValue=function(r,o,c){if(/^\d+$/.test(o))return(this.variables.__||{})[o]||"";if(/^[A-Z]\d+$/.test(o))return(this.variables[o[0]+"__"]||{})[o.substr(1)]||"";if(o=o.replace(/^TM_/,""),!this.variables.hasOwnProperty(o))return"";var u=this.variables[o];return typeof u=="function"&&(u=this.variables[o](r,o,c)),u==null?"":u},t.prototype.tmStrFormat=function(r,o,c){if(!o.fmt)return r;var u=o.flag||"",y=o.guard;y=new RegExp(y,u.replace(/[^gim]/g,""));var w=typeof o.fmt=="string"?this.tokenizeTmSnippet(o.fmt,"formatString"):o.fmt,C=this,_=r.replace(y,function(){var T=C.variables.__;C.variables.__=[].slice.call(arguments);for(var L=C.resolveVariables(w,c),F="E",I=0;I<L.length;I++){var z=L[I];if(typeof z=="object")if(L[I]="",z.changeCase&&z.local){var j=L[I+1];j&&typeof j=="string"&&(z.changeCase=="u"?L[I]=j[0].toUpperCase():L[I]=j[0].toLowerCase(),L[I+1]=j.substr(1))}else z.changeCase&&(F=z.changeCase);else F=="U"?L[I]=z.toUpperCase():F=="L"&&(L[I]=z.toLowerCase())}return C.variables.__=T,L.join("")});return _},t.prototype.tmFormatFunction=function(r,o,c){return o.formatFunction=="upcase"?r.toUpperCase():o.formatFunction=="downcase"?r.toLowerCase():r},t.prototype.resolveVariables=function(r,o){function c(F){var I=r.indexOf(F,C+1);I!=-1&&(C=I)}for(var u=[],y="",w=!0,C=0;C<r.length;C++){var _=r[C];if(typeof _=="string"){u.push(_),_==`
`?(w=!0,y=""):w&&(y=/^\t*/.exec(_)[0],w=/\S/.test(_));continue}if(_){if(w=!1,_.fmtString){var T=r.indexOf(_,C+1);T==-1&&(T=r.length),_.fmt=r.slice(C+1,T),C=T}if(_.text){var L=this.getVariableValue(o,_.text,y)+"";_.fmtString&&(L=this.tmStrFormat(L,_,o)),_.formatFunction&&(L=this.tmFormatFunction(L,_,o)),L&&!_.ifEnd?(u.push(L),c(_)):!L&&_.ifEnd&&c(_.ifEnd)}else _.elseEnd?c(_.elseEnd):(_.tabstopId!=null||_.changeCase!=null)&&u.push(_)}}return u},t.prototype.getDisplayTextForSnippet=function(r,o){var c=v.call(this,r,o);return c.text},t.prototype.insertSnippetForSelection=function(r,o,c){c===void 0&&(c={});var u=v.call(this,r,o,c),y=r.getSelectionRange(),w=r.session.replace(y,u.text),C=new E(r),_=r.inVirtualSelectionMode&&r.selection.index;C.addTabstops(u.tabstops,y.start,w,_)},t.prototype.insertSnippet=function(r,o,c){c===void 0&&(c={});var u=this;if(r.inVirtualSelectionMode)return u.insertSnippetForSelection(r,o,c);r.forEachSelection(function(){u.insertSnippetForSelection(r,o,c)},null,{keepOrder:!0}),r.tabstopManager&&r.tabstopManager.tabNext()},t.prototype.$getScope=function(r){var o=r.session.$mode.$id||"";if(o=o.split("/").pop(),o==="html"||o==="php"){o==="php"&&!r.session.$mode.inlinePhp&&(o="html");var c=r.getCursorPosition(),u=r.session.getState(c.row);typeof u=="object"&&(u=u[0]),u.substring&&(u.substring(0,3)=="js-"?o="javascript":u.substring(0,4)=="css-"?o="css":u.substring(0,4)=="php-"&&(o="php"))}return o},t.prototype.getActiveScopes=function(r){var o=this.$getScope(r),c=[o],u=this.snippetMap;return u[o]&&u[o].includeScopes&&c.push.apply(c,u[o].includeScopes),c.push("_"),c},t.prototype.expandWithTab=function(r,o){var c=this,u=r.forEachSelection(function(){return c.expandSnippetForSelection(r,o)},null,{keepOrder:!0});return u&&r.tabstopManager&&r.tabstopManager.tabNext(),u},t.prototype.expandSnippetForSelection=function(r,o){var c=r.getCursorPosition(),u=r.session.getLine(c.row),y=u.substring(0,c.column),w=u.substr(c.column),C=this.snippetMap,_;return this.getActiveScopes(r).some(function(T){var L=C[T];return L&&(_=this.findMatchingSnippet(L,y,w)),!!_},this),_?(o&&o.dryRun||(r.session.doc.removeInLine(c.row,c.column-_.replaceBefore.length,c.column+_.replaceAfter.length),this.variables.M__=_.matchBefore,this.variables.T__=_.matchAfter,this.insertSnippetForSelection(r,_.content),this.variables.M__=this.variables.T__=null),!0):!1},t.prototype.findMatchingSnippet=function(r,o,c){for(var u=r.length;u--;){var y=r[u];if(!(y.startRe&&!y.startRe.test(o))&&!(y.endRe&&!y.endRe.test(c))&&!(!y.startRe&&!y.endRe))return y.matchBefore=y.startRe?y.startRe.exec(o):[""],y.matchAfter=y.endRe?y.endRe.exec(c):[""],y.replaceBefore=y.triggerRe?y.triggerRe.exec(o)[0]:"",y.replaceAfter=y.endTriggerRe?y.endTriggerRe.exec(c)[0]:"",y}},t.prototype.register=function(r,o){function c(T){return T&&!/^\^?\(.*\)\$?$|^\\b$/.test(T)&&(T="(?:"+T+")"),T||""}function u(T,L,F){return T=c(T),L=c(L),F?(T=L+T,T&&T[T.length-1]!="$"&&(T+="$")):(T+=L,T&&T[0]!="^"&&(T="^"+T)),new RegExp(T)}function y(T){T.scope||(T.scope=o||"_"),o=T.scope,w[o]||(w[o]=[],C[o]={});var L=C[o];if(T.name){var F=L[T.name];F&&_.unregister(F),L[T.name]=T}w[o].push(T),T.prefix&&(T.tabTrigger=T.prefix),!T.content&&T.body&&(T.content=Array.isArray(T.body)?T.body.join(`
`):T.body),T.tabTrigger&&!T.trigger&&(!T.guard&&/^\w/.test(T.tabTrigger)&&(T.guard="\\b"),T.trigger=l.escapeRegExp(T.tabTrigger)),!(!T.trigger&&!T.guard&&!T.endTrigger&&!T.endGuard)&&(T.startRe=u(T.trigger,T.guard,!0),T.triggerRe=new RegExp(T.trigger),T.endRe=u(T.endTrigger,T.endGuard,!0),T.endTriggerRe=new RegExp(T.endTrigger))}var w=this.snippetMap,C=this.snippetNameMap,_=this;r||(r=[]),Array.isArray(r)?r.forEach(y):Object.keys(r).forEach(function(T){y(r[T])}),this._signal("registerSnippets",{scope:o})},t.prototype.unregister=function(r,o){function c(w){var C=y[w.scope||o];if(C&&C[w.name]){delete C[w.name];var _=u[w.scope||o],T=_&&_.indexOf(w);T>=0&&_.splice(T,1)}}var u=this.snippetMap,y=this.snippetNameMap;r.content?c(r):Array.isArray(r)&&r.forEach(c)},t.prototype.parseSnippetFile=function(r){r=r.replace(/\r/g,"");for(var o=[],c={},u=/^#.*|^({[\s\S]*})\s*$|^(\S+) (.*)$|^((?:\n*\t.*)+)/gm,y;y=u.exec(r);){if(y[1])try{c=JSON.parse(y[1]),o.push(c)}catch(T){}if(y[4])c.content=y[4].replace(/^\t/gm,""),o.push(c),c={};else{var w=y[2],C=y[3];if(w=="regex"){var _=/\/((?:[^\/\\]|\\.)*)|$/g;c.guard=_.exec(C)[1],c.trigger=_.exec(C)[1],c.endTrigger=_.exec(C)[1],c.endGuard=_.exec(C)[1]}else w=="snippet"?(c.tabTrigger=C.match(/^\S*/)[0],c.name||(c.name=C)):w&&(c[w]=C)}}return o},t.prototype.getSnippetByName=function(r,o){var c=this.snippetNameMap,u;return this.getActiveScopes(o).some(function(y){var w=c[y];return w&&(u=w[r]),!!u},this),u},t}();h.implement(m.prototype,g);var v=function(t,r,o){function c(D){for(var X=[],K=0;K<D.length;K++){var W=D[K];if(typeof W=="object"){if(L[W.tabstopId])continue;var ce=D.lastIndexOf(W,K-1);W=X[ce]||{tabstopId:W.tabstopId}}X[K]=W}return X}o===void 0&&(o={});var u=t.getCursorPosition(),y=t.session.getLine(u.row),w=t.session.getTabString(),C=y.match(/^\s*/)[0];u.column<C.length&&(C=C.slice(0,u.column)),r=r.replace(/\r/g,"");var _=this.tokenizeTmSnippet(r);_=this.resolveVariables(_,t),_=_.map(function(D){return D==`
`&&!o.excludeExtraIndent?D+C:typeof D=="string"?D.replace(/\t/g,w):D});var T=[];_.forEach(function(D,X){if(typeof D=="object"){var K=D.tabstopId,W=T[K];if(W||(W=T[K]=[],W.index=K,W.value="",W.parents={}),W.indexOf(D)===-1){D.choices&&!W.choices&&(W.choices=D.choices),W.push(D);var ce=_.indexOf(D,X+1);if(ce!==-1){var le=_.slice(X+1,ce),we=le.some(function(Me){return typeof Me=="object"});we&&!W.value?W.value=le:le.length&&(!W.value||typeof W.value!="string")&&(W.value=le.join(""))}}}}),T.forEach(function(D){D.length=0});for(var L={},F=0;F<_.length;F++){var I=_[F];if(typeof I=="object"){var z=I.tabstopId,j=T[z],q=_.indexOf(I,F+1);if(L[z]){L[z]===I&&(delete L[z],Object.keys(L).forEach(function(D){j.parents[D]=!0}));continue}L[z]=I;var J=j.value;typeof J!="string"?J=c(J):I.fmt&&(J=this.tmStrFormat(J,I,t)),_.splice.apply(_,[F+1,Math.max(0,q-F)].concat(J,I)),j.indexOf(I)===-1&&j.push(I)}}var ne=0,Z=0,G="";return _.forEach(function(D){if(typeof D=="string"){var X=D.split(`
`);X.length>1?(Z=X[X.length-1].length,ne+=X.length-1):Z+=D.length,G+=D}else D&&(D.start?D.end={row:ne,column:Z}:D.start={row:ne,column:Z})}),{text:G,tabstops:T,tokens:_}},E=function(){function t(r){if(this.index=0,this.ranges=[],this.tabstops=[],r.tabstopManager)return r.tabstopManager;r.tabstopManager=this,this.$onChange=this.onChange.bind(this),this.$onChangeSelection=l.delayedCall(this.onChangeSelection.bind(this)).schedule,this.$onChangeSession=this.onChangeSession.bind(this),this.$onAfterExec=this.onAfterExec.bind(this),this.attach(r)}return t.prototype.attach=function(r){this.$openTabstops=null,this.selectedTabstop=null,this.editor=r,this.session=r.session,this.editor.on("change",this.$onChange),this.editor.on("changeSelection",this.$onChangeSelection),this.editor.on("changeSession",this.$onChangeSession),this.editor.commands.on("afterExec",this.$onAfterExec),this.editor.keyBinding.addKeyboardHandler(this.keyboardHandler)},t.prototype.detach=function(){this.tabstops.forEach(this.removeTabstopMarkers,this),this.ranges.length=0,this.tabstops.length=0,this.selectedTabstop=null,this.editor.off("change",this.$onChange),this.editor.off("changeSelection",this.$onChangeSelection),this.editor.off("changeSession",this.$onChangeSession),this.editor.commands.off("afterExec",this.$onAfterExec),this.editor.keyBinding.removeKeyboardHandler(this.keyboardHandler),this.editor.tabstopManager=null,this.session=null,this.editor=null},t.prototype.onChange=function(r){for(var o=r.action[0]=="r",c=this.selectedTabstop||{},u=c.parents||{},y=this.tabstops.slice(),w=0;w<y.length;w++){var C=y[w],_=C==c||u[C.index];if(C.rangeList.$bias=_?0:1,r.action=="remove"&&C!==c){var T=C.parents&&C.parents[c.index],L=C.rangeList.pointIndex(r.start,T);L=L<0?-L-1:L+1;var F=C.rangeList.pointIndex(r.end,T);F=F<0?-F-1:F-1;for(var I=C.rangeList.ranges.slice(L,F),z=0;z<I.length;z++)this.removeRange(I[z])}C.rangeList.$onChange(r)}var j=this.session;!this.$inChange&&o&&j.getLength()==1&&!j.getValue()&&this.detach()},t.prototype.updateLinkedFields=function(){var r=this.selectedTabstop;if(!(!r||!r.hasLinkedRanges||!r.firstNonLinked)){this.$inChange=!0;for(var o=this.session,c=o.getTextRange(r.firstNonLinked),u=0;u<r.length;u++){var y=r[u];if(y.linked){var w=y.original,C=p.snippetManager.tmStrFormat(c,w,this.editor);o.replace(y,C)}}this.$inChange=!1}},t.prototype.onAfterExec=function(r){r.command&&!r.command.readOnly&&this.updateLinkedFields()},t.prototype.onChangeSelection=function(){if(this.editor){for(var r=this.editor.selection.lead,o=this.editor.selection.anchor,c=this.editor.selection.isEmpty(),u=0;u<this.ranges.length;u++)if(!this.ranges[u].linked){var y=this.ranges[u].contains(r.row,r.column),w=c||this.ranges[u].contains(o.row,o.column);if(y&&w)return}this.detach()}},t.prototype.onChangeSession=function(){this.detach()},t.prototype.tabNext=function(r){var o=this.tabstops.length,c=this.index+(r||1);c=Math.min(Math.max(c,1),o),c==o&&(c=0),this.selectTabstop(c),this.updateTabstopMarkers(),c===0&&this.detach()},t.prototype.selectTabstop=function(r){this.$openTabstops=null;var o=this.tabstops[this.index];if(o&&this.addTabstopMarkers(o),this.index=r,o=this.tabstops[this.index],!(!o||!o.length)){this.selectedTabstop=o;var c=o.firstNonLinked||o;if(o.choices&&(c.cursor=c.start),this.editor.inVirtualSelectionMode)this.editor.selection.fromOrientedRange(c);else{var u=this.editor.multiSelect;u.toSingleRange(c);for(var y=0;y<o.length;y++)o.hasLinkedRanges&&o[y].linked||u.addRange(o[y].clone(),!0)}this.editor.keyBinding.addKeyboardHandler(this.keyboardHandler),this.selectedTabstop&&this.selectedTabstop.choices&&this.editor.execCommand("startAutocomplete",{matches:this.selectedTabstop.choices})}},t.prototype.addTabstops=function(r,o,c){var u=this.useLink||!this.editor.getOption("enableMultiselect");if(this.$openTabstops||(this.$openTabstops=[]),!r[0]){var y=s.fromPoints(c,c);R(y.start,o),R(y.end,o),r[0]=[y],r[0].index=0}var w=this.index,C=[w+1,0],_=this.ranges,T=this.snippetId=(this.snippetId||0)+1;r.forEach(function(L,F){var I=this.$openTabstops[F]||L;I.snippetId=T;for(var z=0;z<L.length;z++){var j=L[z],q=s.fromPoints(j.start,j.end||j.start);P(q.start,o),P(q.end,o),q.original=j,q.tabstop=I,_.push(q),I!=L?I.unshift(q):I[z]=q,j.fmtString||I.firstNonLinked&&u?(q.linked=!0,I.hasLinkedRanges=!0):I.firstNonLinked||(I.firstNonLinked=q)}I.firstNonLinked||(I.hasLinkedRanges=!1),I===L&&(C.push(I),this.$openTabstops[F]=I),this.addTabstopMarkers(I),I.rangeList=I.rangeList||new i,I.rangeList.$bias=0,I.rangeList.addList(I)},this),C.length>2&&(this.tabstops.length&&C.push(C.splice(2,1)[0]),this.tabstops.splice.apply(this.tabstops,C))},t.prototype.addTabstopMarkers=function(r){var o=this.session;r.forEach(function(c){c.markerId||(c.markerId=o.addMarker(c,"ace_snippet-marker","text"))})},t.prototype.removeTabstopMarkers=function(r){var o=this.session;r.forEach(function(c){o.removeMarker(c.markerId),c.markerId=null})},t.prototype.updateTabstopMarkers=function(){if(this.selectedTabstop){var r=this.selectedTabstop.snippetId;this.selectedTabstop.index===0&&r--,this.tabstops.forEach(function(o){o.snippetId===r?this.addTabstopMarkers(o):this.removeTabstopMarkers(o)},this)}},t.prototype.removeRange=function(r){var o=r.tabstop.indexOf(r);o!=-1&&r.tabstop.splice(o,1),o=this.ranges.indexOf(r),o!=-1&&this.ranges.splice(o,1),o=r.tabstop.rangeList.ranges.indexOf(r),o!=-1&&r.tabstop.splice(o,1),this.session.removeMarker(r.markerId),r.tabstop.length||(o=this.tabstops.indexOf(r.tabstop),o!=-1&&this.tabstops.splice(o,1),this.tabstops.length||this.detach())},t}();E.prototype.keyboardHandler=new d,E.prototype.keyboardHandler.bindKeys({Tab:function(t){p.snippetManager&&p.snippetManager.expandWithTab(t)||(t.tabstopManager.tabNext(1),t.renderer.scrollCursorIntoView())},"Shift-Tab":function(t){t.tabstopManager.tabNext(-1),t.renderer.scrollCursorIntoView()},Esc:function(t){t.tabstopManager.detach()}});var P=function(t,r){t.row==0&&(t.column+=r.column),t.row+=r.row},R=function(t,r){t.row==r.row&&(t.column-=r.column),t.row-=r.row};a.importCssString(`
.ace_snippet-marker {
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    background: rgba(194, 193, 208, 0.09);
    border: 1px dotted rgba(211, 208, 235, 0.62);
    position: absolute;
}`,"snippets.css",!1),p.snippetManager=new m;var k=n("./editor").Editor;(function(){this.insertSnippet=function(t,r){return p.snippetManager.insertSnippet(this,t,r)},this.expandSnippet=function(t){return p.snippetManager.expandWithTab(this,t)}}).call(k.prototype)}),ace.define("ace/ext/emmet",["require","exports","module","ace/keyboard/hash_handler","ace/editor","ace/snippets","ace/range","ace/config","resources","resources","tabStops","resources","utils","actions"],function(n,p,A){var e=n("../keyboard/hash_handler").HashHandler,a=n("../editor").Editor,h=n("../snippets").snippetManager,g=n("../range").Range,l=n("../config"),s,i,d=function(){function v(){}return v.prototype.setupContext=function(E){this.ace=E,this.indentation=E.session.getTabString(),s||(s=window.emmet);var P=s.resources||s.require("resources");P.setVariable("indentation",this.indentation),this.$syntax=null,this.$syntax=this.getSyntax()},v.prototype.getSelectionRange=function(){var E=this.ace.getSelectionRange(),P=this.ace.session.doc;return{start:P.positionToIndex(E.start),end:P.positionToIndex(E.end)}},v.prototype.createSelection=function(E,P){var R=this.ace.session.doc;this.ace.selection.setRange({start:R.indexToPosition(E),end:R.indexToPosition(P)})},v.prototype.getCurrentLineRange=function(){var E=this.ace,P=E.getCursorPosition().row,R=E.session.getLine(P).length,k=E.session.doc.positionToIndex({row:P,column:0});return{start:k,end:k+R}},v.prototype.getCaretPos=function(){var E=this.ace.getCursorPosition();return this.ace.session.doc.positionToIndex(E)},v.prototype.setCaretPos=function(E){var P=this.ace.session.doc.indexToPosition(E);this.ace.selection.moveToPosition(P)},v.prototype.getCurrentLine=function(){var E=this.ace.getCursorPosition().row;return this.ace.session.getLine(E)},v.prototype.replaceContent=function(E,P,R,k){R==null&&(R=P==null?this.getContent().length:P),P==null&&(P=0);var t=this.ace,r=t.session.doc,o=g.fromPoints(r.indexToPosition(P),r.indexToPosition(R));t.session.remove(o),o.end=o.start,E=this.$updateTabstops(E),h.insertSnippet(t,E)},v.prototype.getContent=function(){return this.ace.getValue()},v.prototype.getSyntax=function(){if(this.$syntax)return this.$syntax;var E=this.ace.session.$modeId.split("/").pop();if(E=="html"||E=="php"){var P=this.ace.getCursorPosition(),R=this.ace.session.getState(P.row);typeof R!="string"&&(R=R[0]),R&&(R=R.split("-"),R.length>1?E=R[0]:E=="php"&&(E="html"))}return E},v.prototype.getProfileName=function(){var E=s.resources||s.require("resources");switch(this.getSyntax()){case"css":return"css";case"xml":case"xsl":return"xml";case"html":var P=E.getVariable("profile");return P||(P=this.ace.session.getLines(0,2).join("").search(/<!DOCTYPE[^>]+XHTML/i)!=-1?"xhtml":"html"),P;default:var R=this.ace.session.$mode;return R.emmetConfig&&R.emmetConfig.profile||"xhtml"}},v.prototype.prompt=function(E){return prompt(E)},v.prototype.getSelection=function(){return this.ace.session.getTextRange()},v.prototype.getFilePath=function(){return""},v.prototype.$updateTabstops=function(E){var P=1e3,R=0,k=null,t=s.tabStops||s.require("tabStops"),r=s.resources||s.require("resources"),o=r.getVocabulary("user"),c={tabstop:function(y){var w=parseInt(y.group,10),C=w===0;C?w=++R:w+=P;var _=y.placeholder;_&&(_=t.processText(_,c));var T="${"+w+(_?":"+_:"")+"}";return C&&(k=[y.start,T]),T},escape:function(y){return y=="$"?"\\$":y=="\\"?"\\\\":y}};if(E=t.processText(E,c),o.variables.insert_final_tabstop&&!/\$\{0\}$/.test(E))E+="${0}";else if(k){var u=s.utils?s.utils.common:s.require("utils");E=u.replaceSubstring(E,"${0}",k[0],k[1])}return E},v}(),f={expand_abbreviation:{mac:"ctrl+alt+e",win:"alt+e"},match_pair_outward:{mac:"ctrl+d",win:"ctrl+,"},match_pair_inward:{mac:"ctrl+j",win:"ctrl+shift+0"},matching_pair:{mac:"ctrl+alt+j",win:"alt+j"},next_edit_point:"alt+right",prev_edit_point:"alt+left",toggle_comment:{mac:"command+/",win:"ctrl+/"},split_join_tag:{mac:"shift+command+'",win:"shift+ctrl+`"},remove_tag:{mac:"command+'",win:"shift+ctrl+;"},evaluate_math_expression:{mac:"shift+command+y",win:"shift+ctrl+y"},increment_number_by_1:"ctrl+up",decrement_number_by_1:"ctrl+down",increment_number_by_01:"alt+up",decrement_number_by_01:"alt+down",increment_number_by_10:{mac:"alt+command+up",win:"shift+alt+up"},decrement_number_by_10:{mac:"alt+command+down",win:"shift+alt+down"},select_next_item:{mac:"shift+command+.",win:"shift+ctrl+."},select_previous_item:{mac:"shift+command+,",win:"shift+ctrl+,"},reflect_css_value:{mac:"shift+command+r",win:"shift+ctrl+r"},encode_decode_data_url:{mac:"shift+ctrl+d",win:"ctrl+'"},expand_abbreviation_with_tab:"Tab",wrap_with_abbreviation:{mac:"shift+ctrl+a",win:"shift+ctrl+a"}},$=new d;p.commands=new e,p.runEmmetCommand=function v(E){if(this.action=="expand_abbreviation_with_tab"){if(!E.selection.isEmpty())return!1;var P=E.selection.lead,R=E.session.getTokenAt(P.row,P.column);if(R&&/\btag\b/.test(R.type))return!1}try{$.setupContext(E);var k=s.actions||s.require("actions");if(this.action=="wrap_with_abbreviation")return setTimeout(function(){k.run("wrap_with_abbreviation",$)},0);var t=k.run(this.action,$)}catch(o){if(!s){var r=p.load(v.bind(this,E));return this.action=="expand_abbreviation_with_tab"?!1:r}E._signal("changeStatus",typeof o=="string"?o:o.message),l.warn(o),t=!1}return t};for(var S in f)p.commands.addCommand({name:"emmet:"+S,action:S,bindKey:f[S],exec:p.runEmmetCommand,multiSelectAction:"forEach"});p.updateCommands=function(v,E){E?v.keyBinding.addKeyboardHandler(p.commands):v.keyBinding.removeKeyboardHandler(p.commands)},p.isSupportedMode=function(v){if(!v)return!1;if(v.emmetConfig)return!0;var E=v.$id||v;return/css|less|scss|sass|stylus|html|php|twig|ejs|handlebars/.test(E)},p.isAvailable=function(v,E){if(/(evaluate_math_expression|expand_abbreviation)$/.test(E))return!0;var P=v.session.$mode,R=p.isSupportedMode(P);if(R&&P.$modes)try{$.setupContext(v),/js|php/.test($.getSyntax())&&(R=!1)}catch(k){}return R};var m=function(v,E){var P=E;if(P){var R=p.isSupportedMode(P.session.$mode);v.enableEmmet===!1&&(R=!1),R&&p.load(),p.updateCommands(P,R)}};p.load=function(v){return typeof i!="string"?(l.warn("script for emmet-core is not loaded"),!1):(l.loadModule(i,function(){i=null,v&&v()}),!0)},p.AceEmmetEditor=d,l.defineOptions(a.prototype,"editor",{enableEmmet:{set:function(v){this[v?"on":"removeListener"]("changeMode",m),m({enableEmmet:!!v},this)},value:!0}}),p.setCore=function(v){typeof v=="string"?i=v:s=v}}),function(){ace.require(["ace/ext/emmet"],function(n){M&&(M.exports=n)})}()})(wr);var _r={exports:{}};(function(M,b){ace.define("ace/snippets",["require","exports","module","ace/lib/dom","ace/lib/oop","ace/lib/event_emitter","ace/lib/lang","ace/range","ace/range_list","ace/keyboard/hash_handler","ace/tokenizer","ace/clipboard","ace/editor"],function(n,p,A){function e(t){var r=new Date().toLocaleString("en-us",t);return r.length==1?"0"+r:r}var a=n("./lib/dom"),h=n("./lib/oop"),g=n("./lib/event_emitter").EventEmitter,l=n("./lib/lang"),s=n("./range").Range,i=n("./range_list").RangeList,d=n("./keyboard/hash_handler").HashHandler,f=n("./tokenizer").Tokenizer,$=n("./clipboard"),S={CURRENT_WORD:function(t){return t.session.getTextRange(t.session.getWordRange())},SELECTION:function(t,r,o){var c=t.session.getTextRange();return o?c.replace(/\n\r?([ \t]*\S)/g,`
`+o+"$1"):c},CURRENT_LINE:function(t){return t.session.getLine(t.getCursorPosition().row)},PREV_LINE:function(t){return t.session.getLine(t.getCursorPosition().row-1)},LINE_INDEX:function(t){return t.getCursorPosition().row},LINE_NUMBER:function(t){return t.getCursorPosition().row+1},SOFT_TABS:function(t){return t.session.getUseSoftTabs()?"YES":"NO"},TAB_SIZE:function(t){return t.session.getTabSize()},CLIPBOARD:function(t){return $.getText&&$.getText()},FILENAME:function(t){return/[^/\\]*$/.exec(this.FILEPATH(t))[0]},FILENAME_BASE:function(t){return/[^/\\]*$/.exec(this.FILEPATH(t))[0].replace(/\.[^.]*$/,"")},DIRECTORY:function(t){return this.FILEPATH(t).replace(/[^/\\]*$/,"")},FILEPATH:function(t){return"/not implemented.txt"},WORKSPACE_NAME:function(){return"Unknown"},FULLNAME:function(){return"Unknown"},BLOCK_COMMENT_START:function(t){var r=t.session.$mode||{};return r.blockComment&&r.blockComment.start||""},BLOCK_COMMENT_END:function(t){var r=t.session.$mode||{};return r.blockComment&&r.blockComment.end||""},LINE_COMMENT:function(t){var r=t.session.$mode||{};return r.lineCommentStart||""},CURRENT_YEAR:e.bind(null,{year:"numeric"}),CURRENT_YEAR_SHORT:e.bind(null,{year:"2-digit"}),CURRENT_MONTH:e.bind(null,{month:"numeric"}),CURRENT_MONTH_NAME:e.bind(null,{month:"long"}),CURRENT_MONTH_NAME_SHORT:e.bind(null,{month:"short"}),CURRENT_DATE:e.bind(null,{day:"2-digit"}),CURRENT_DAY_NAME:e.bind(null,{weekday:"long"}),CURRENT_DAY_NAME_SHORT:e.bind(null,{weekday:"short"}),CURRENT_HOUR:e.bind(null,{hour:"2-digit",hour12:!1}),CURRENT_MINUTE:e.bind(null,{minute:"2-digit"}),CURRENT_SECOND:e.bind(null,{second:"2-digit"})};S.SELECTED_TEXT=S.SELECTION;var m=function(){function t(){this.snippetMap={},this.snippetNameMap={},this.variables=S}return t.prototype.getTokenizer=function(){return t.$tokenizer||this.createTokenizer()},t.prototype.createTokenizer=function(){function r(u){return u=u.substr(1),/^\d+$/.test(u)?[{tabstopId:parseInt(u,10)}]:[{text:u}]}function o(u){return"(?:[^\\\\"+u+"]|\\\\.)"}var c={regex:"/("+o("/")+"+)/",onMatch:function(u,y,w){var C=w[0];return C.fmtString=!0,C.guard=u.slice(1,-1),C.flag="",""},next:"formatString"};return t.$tokenizer=new f({start:[{regex:/\\./,onMatch:function(u,y,w){var C=u[1];return(C=="}"&&w.length||"`$\\".indexOf(C)!=-1)&&(u=C),[u]}},{regex:/}/,onMatch:function(u,y,w){return[w.length?w.shift():u]}},{regex:/\$(?:\d+|\w+)/,onMatch:r},{regex:/\$\{[\dA-Z_a-z]+/,onMatch:function(u,y,w){var C=r(u.substr(1));return w.unshift(C[0]),C},next:"snippetVar"},{regex:/\n/,token:"newline",merge:!1}],snippetVar:[{regex:"\\|"+o("\\|")+"*\\|",onMatch:function(u,y,w){var C=u.slice(1,-1).replace(/\\[,|\\]|,/g,function(_){return _.length==2?_[1]:"\0"}).split("\0").map(function(_){return{value:_}});return w[0].choices=C,[C[0]]},next:"start"},c,{regex:"([^:}\\\\]|\\\\.)*:?",token:"",next:"start"}],formatString:[{regex:/:/,onMatch:function(u,y,w){return w.length&&w[0].expectElse?(w[0].expectElse=!1,w[0].ifEnd={elseEnd:w[0]},[w[0].ifEnd]):":"}},{regex:/\\./,onMatch:function(u,y,w){var C=u[1];return C=="}"&&w.length||"`$\\".indexOf(C)!=-1?u=C:C=="n"?u=`
`:C=="t"?u="	":"ulULE".indexOf(C)!=-1&&(u={changeCase:C,local:C>"a"}),[u]}},{regex:"/\\w*}",onMatch:function(u,y,w){var C=w.shift();return C&&(C.flag=u.slice(1,-1)),this.next=C&&C.tabstopId?"start":"",[C||u]},next:"start"},{regex:/\$(?:\d+|\w+)/,onMatch:function(u,y,w){return[{text:u.slice(1)}]}},{regex:/\${\w+/,onMatch:function(u,y,w){var C={text:u.slice(2)};return w.unshift(C),[C]},next:"formatStringVar"},{regex:/\n/,token:"newline",merge:!1},{regex:/}/,onMatch:function(u,y,w){var C=w.shift();return this.next=C&&C.tabstopId?"start":"",[C||u]},next:"start"}],formatStringVar:[{regex:/:\/\w+}/,onMatch:function(u,y,w){var C=w[0];return C.formatFunction=u.slice(2,-1),[w.shift()]},next:"formatString"},c,{regex:/:[\?\-+]?/,onMatch:function(u,y,w){u[1]=="+"&&(w[0].ifEnd=w[0]),u[1]=="?"&&(w[0].expectElse=!0)},next:"formatString"},{regex:"([^:}\\\\]|\\\\.)*:?",token:"",next:"formatString"}]}),t.$tokenizer},t.prototype.tokenizeTmSnippet=function(r,o){return this.getTokenizer().getLineTokens(r,o).tokens.map(function(c){return c.value||c})},t.prototype.getVariableValue=function(r,o,c){if(/^\d+$/.test(o))return(this.variables.__||{})[o]||"";if(/^[A-Z]\d+$/.test(o))return(this.variables[o[0]+"__"]||{})[o.substr(1)]||"";if(o=o.replace(/^TM_/,""),!this.variables.hasOwnProperty(o))return"";var u=this.variables[o];return typeof u=="function"&&(u=this.variables[o](r,o,c)),u==null?"":u},t.prototype.tmStrFormat=function(r,o,c){if(!o.fmt)return r;var u=o.flag||"",y=o.guard;y=new RegExp(y,u.replace(/[^gim]/g,""));var w=typeof o.fmt=="string"?this.tokenizeTmSnippet(o.fmt,"formatString"):o.fmt,C=this,_=r.replace(y,function(){var T=C.variables.__;C.variables.__=[].slice.call(arguments);for(var L=C.resolveVariables(w,c),F="E",I=0;I<L.length;I++){var z=L[I];if(typeof z=="object")if(L[I]="",z.changeCase&&z.local){var j=L[I+1];j&&typeof j=="string"&&(z.changeCase=="u"?L[I]=j[0].toUpperCase():L[I]=j[0].toLowerCase(),L[I+1]=j.substr(1))}else z.changeCase&&(F=z.changeCase);else F=="U"?L[I]=z.toUpperCase():F=="L"&&(L[I]=z.toLowerCase())}return C.variables.__=T,L.join("")});return _},t.prototype.tmFormatFunction=function(r,o,c){return o.formatFunction=="upcase"?r.toUpperCase():o.formatFunction=="downcase"?r.toLowerCase():r},t.prototype.resolveVariables=function(r,o){function c(F){var I=r.indexOf(F,C+1);I!=-1&&(C=I)}for(var u=[],y="",w=!0,C=0;C<r.length;C++){var _=r[C];if(typeof _=="string"){u.push(_),_==`
`?(w=!0,y=""):w&&(y=/^\t*/.exec(_)[0],w=/\S/.test(_));continue}if(_){if(w=!1,_.fmtString){var T=r.indexOf(_,C+1);T==-1&&(T=r.length),_.fmt=r.slice(C+1,T),C=T}if(_.text){var L=this.getVariableValue(o,_.text,y)+"";_.fmtString&&(L=this.tmStrFormat(L,_,o)),_.formatFunction&&(L=this.tmFormatFunction(L,_,o)),L&&!_.ifEnd?(u.push(L),c(_)):!L&&_.ifEnd&&c(_.ifEnd)}else _.elseEnd?c(_.elseEnd):(_.tabstopId!=null||_.changeCase!=null)&&u.push(_)}}return u},t.prototype.getDisplayTextForSnippet=function(r,o){var c=v.call(this,r,o);return c.text},t.prototype.insertSnippetForSelection=function(r,o,c){c===void 0&&(c={});var u=v.call(this,r,o,c),y=r.getSelectionRange(),w=r.session.replace(y,u.text),C=new E(r),_=r.inVirtualSelectionMode&&r.selection.index;C.addTabstops(u.tabstops,y.start,w,_)},t.prototype.insertSnippet=function(r,o,c){c===void 0&&(c={});var u=this;if(r.inVirtualSelectionMode)return u.insertSnippetForSelection(r,o,c);r.forEachSelection(function(){u.insertSnippetForSelection(r,o,c)},null,{keepOrder:!0}),r.tabstopManager&&r.tabstopManager.tabNext()},t.prototype.$getScope=function(r){var o=r.session.$mode.$id||"";if(o=o.split("/").pop(),o==="html"||o==="php"){o==="php"&&!r.session.$mode.inlinePhp&&(o="html");var c=r.getCursorPosition(),u=r.session.getState(c.row);typeof u=="object"&&(u=u[0]),u.substring&&(u.substring(0,3)=="js-"?o="javascript":u.substring(0,4)=="css-"?o="css":u.substring(0,4)=="php-"&&(o="php"))}return o},t.prototype.getActiveScopes=function(r){var o=this.$getScope(r),c=[o],u=this.snippetMap;return u[o]&&u[o].includeScopes&&c.push.apply(c,u[o].includeScopes),c.push("_"),c},t.prototype.expandWithTab=function(r,o){var c=this,u=r.forEachSelection(function(){return c.expandSnippetForSelection(r,o)},null,{keepOrder:!0});return u&&r.tabstopManager&&r.tabstopManager.tabNext(),u},t.prototype.expandSnippetForSelection=function(r,o){var c=r.getCursorPosition(),u=r.session.getLine(c.row),y=u.substring(0,c.column),w=u.substr(c.column),C=this.snippetMap,_;return this.getActiveScopes(r).some(function(T){var L=C[T];return L&&(_=this.findMatchingSnippet(L,y,w)),!!_},this),_?(o&&o.dryRun||(r.session.doc.removeInLine(c.row,c.column-_.replaceBefore.length,c.column+_.replaceAfter.length),this.variables.M__=_.matchBefore,this.variables.T__=_.matchAfter,this.insertSnippetForSelection(r,_.content),this.variables.M__=this.variables.T__=null),!0):!1},t.prototype.findMatchingSnippet=function(r,o,c){for(var u=r.length;u--;){var y=r[u];if(!(y.startRe&&!y.startRe.test(o))&&!(y.endRe&&!y.endRe.test(c))&&!(!y.startRe&&!y.endRe))return y.matchBefore=y.startRe?y.startRe.exec(o):[""],y.matchAfter=y.endRe?y.endRe.exec(c):[""],y.replaceBefore=y.triggerRe?y.triggerRe.exec(o)[0]:"",y.replaceAfter=y.endTriggerRe?y.endTriggerRe.exec(c)[0]:"",y}},t.prototype.register=function(r,o){function c(T){return T&&!/^\^?\(.*\)\$?$|^\\b$/.test(T)&&(T="(?:"+T+")"),T||""}function u(T,L,F){return T=c(T),L=c(L),F?(T=L+T,T&&T[T.length-1]!="$"&&(T+="$")):(T+=L,T&&T[0]!="^"&&(T="^"+T)),new RegExp(T)}function y(T){T.scope||(T.scope=o||"_"),o=T.scope,w[o]||(w[o]=[],C[o]={});var L=C[o];if(T.name){var F=L[T.name];F&&_.unregister(F),L[T.name]=T}w[o].push(T),T.prefix&&(T.tabTrigger=T.prefix),!T.content&&T.body&&(T.content=Array.isArray(T.body)?T.body.join(`
`):T.body),T.tabTrigger&&!T.trigger&&(!T.guard&&/^\w/.test(T.tabTrigger)&&(T.guard="\\b"),T.trigger=l.escapeRegExp(T.tabTrigger)),!(!T.trigger&&!T.guard&&!T.endTrigger&&!T.endGuard)&&(T.startRe=u(T.trigger,T.guard,!0),T.triggerRe=new RegExp(T.trigger),T.endRe=u(T.endTrigger,T.endGuard,!0),T.endTriggerRe=new RegExp(T.endTrigger))}var w=this.snippetMap,C=this.snippetNameMap,_=this;r||(r=[]),Array.isArray(r)?r.forEach(y):Object.keys(r).forEach(function(T){y(r[T])}),this._signal("registerSnippets",{scope:o})},t.prototype.unregister=function(r,o){function c(w){var C=y[w.scope||o];if(C&&C[w.name]){delete C[w.name];var _=u[w.scope||o],T=_&&_.indexOf(w);T>=0&&_.splice(T,1)}}var u=this.snippetMap,y=this.snippetNameMap;r.content?c(r):Array.isArray(r)&&r.forEach(c)},t.prototype.parseSnippetFile=function(r){r=r.replace(/\r/g,"");for(var o=[],c={},u=/^#.*|^({[\s\S]*})\s*$|^(\S+) (.*)$|^((?:\n*\t.*)+)/gm,y;y=u.exec(r);){if(y[1])try{c=JSON.parse(y[1]),o.push(c)}catch(T){}if(y[4])c.content=y[4].replace(/^\t/gm,""),o.push(c),c={};else{var w=y[2],C=y[3];if(w=="regex"){var _=/\/((?:[^\/\\]|\\.)*)|$/g;c.guard=_.exec(C)[1],c.trigger=_.exec(C)[1],c.endTrigger=_.exec(C)[1],c.endGuard=_.exec(C)[1]}else w=="snippet"?(c.tabTrigger=C.match(/^\S*/)[0],c.name||(c.name=C)):w&&(c[w]=C)}}return o},t.prototype.getSnippetByName=function(r,o){var c=this.snippetNameMap,u;return this.getActiveScopes(o).some(function(y){var w=c[y];return w&&(u=w[r]),!!u},this),u},t}();h.implement(m.prototype,g);var v=function(t,r,o){function c(D){for(var X=[],K=0;K<D.length;K++){var W=D[K];if(typeof W=="object"){if(L[W.tabstopId])continue;var ce=D.lastIndexOf(W,K-1);W=X[ce]||{tabstopId:W.tabstopId}}X[K]=W}return X}o===void 0&&(o={});var u=t.getCursorPosition(),y=t.session.getLine(u.row),w=t.session.getTabString(),C=y.match(/^\s*/)[0];u.column<C.length&&(C=C.slice(0,u.column)),r=r.replace(/\r/g,"");var _=this.tokenizeTmSnippet(r);_=this.resolveVariables(_,t),_=_.map(function(D){return D==`
`&&!o.excludeExtraIndent?D+C:typeof D=="string"?D.replace(/\t/g,w):D});var T=[];_.forEach(function(D,X){if(typeof D=="object"){var K=D.tabstopId,W=T[K];if(W||(W=T[K]=[],W.index=K,W.value="",W.parents={}),W.indexOf(D)===-1){D.choices&&!W.choices&&(W.choices=D.choices),W.push(D);var ce=_.indexOf(D,X+1);if(ce!==-1){var le=_.slice(X+1,ce),we=le.some(function(Me){return typeof Me=="object"});we&&!W.value?W.value=le:le.length&&(!W.value||typeof W.value!="string")&&(W.value=le.join(""))}}}}),T.forEach(function(D){D.length=0});for(var L={},F=0;F<_.length;F++){var I=_[F];if(typeof I=="object"){var z=I.tabstopId,j=T[z],q=_.indexOf(I,F+1);if(L[z]){L[z]===I&&(delete L[z],Object.keys(L).forEach(function(D){j.parents[D]=!0}));continue}L[z]=I;var J=j.value;typeof J!="string"?J=c(J):I.fmt&&(J=this.tmStrFormat(J,I,t)),_.splice.apply(_,[F+1,Math.max(0,q-F)].concat(J,I)),j.indexOf(I)===-1&&j.push(I)}}var ne=0,Z=0,G="";return _.forEach(function(D){if(typeof D=="string"){var X=D.split(`
`);X.length>1?(Z=X[X.length-1].length,ne+=X.length-1):Z+=D.length,G+=D}else D&&(D.start?D.end={row:ne,column:Z}:D.start={row:ne,column:Z})}),{text:G,tabstops:T,tokens:_}},E=function(){function t(r){if(this.index=0,this.ranges=[],this.tabstops=[],r.tabstopManager)return r.tabstopManager;r.tabstopManager=this,this.$onChange=this.onChange.bind(this),this.$onChangeSelection=l.delayedCall(this.onChangeSelection.bind(this)).schedule,this.$onChangeSession=this.onChangeSession.bind(this),this.$onAfterExec=this.onAfterExec.bind(this),this.attach(r)}return t.prototype.attach=function(r){this.$openTabstops=null,this.selectedTabstop=null,this.editor=r,this.session=r.session,this.editor.on("change",this.$onChange),this.editor.on("changeSelection",this.$onChangeSelection),this.editor.on("changeSession",this.$onChangeSession),this.editor.commands.on("afterExec",this.$onAfterExec),this.editor.keyBinding.addKeyboardHandler(this.keyboardHandler)},t.prototype.detach=function(){this.tabstops.forEach(this.removeTabstopMarkers,this),this.ranges.length=0,this.tabstops.length=0,this.selectedTabstop=null,this.editor.off("change",this.$onChange),this.editor.off("changeSelection",this.$onChangeSelection),this.editor.off("changeSession",this.$onChangeSession),this.editor.commands.off("afterExec",this.$onAfterExec),this.editor.keyBinding.removeKeyboardHandler(this.keyboardHandler),this.editor.tabstopManager=null,this.session=null,this.editor=null},t.prototype.onChange=function(r){for(var o=r.action[0]=="r",c=this.selectedTabstop||{},u=c.parents||{},y=this.tabstops.slice(),w=0;w<y.length;w++){var C=y[w],_=C==c||u[C.index];if(C.rangeList.$bias=_?0:1,r.action=="remove"&&C!==c){var T=C.parents&&C.parents[c.index],L=C.rangeList.pointIndex(r.start,T);L=L<0?-L-1:L+1;var F=C.rangeList.pointIndex(r.end,T);F=F<0?-F-1:F-1;for(var I=C.rangeList.ranges.slice(L,F),z=0;z<I.length;z++)this.removeRange(I[z])}C.rangeList.$onChange(r)}var j=this.session;!this.$inChange&&o&&j.getLength()==1&&!j.getValue()&&this.detach()},t.prototype.updateLinkedFields=function(){var r=this.selectedTabstop;if(!(!r||!r.hasLinkedRanges||!r.firstNonLinked)){this.$inChange=!0;for(var o=this.session,c=o.getTextRange(r.firstNonLinked),u=0;u<r.length;u++){var y=r[u];if(y.linked){var w=y.original,C=p.snippetManager.tmStrFormat(c,w,this.editor);o.replace(y,C)}}this.$inChange=!1}},t.prototype.onAfterExec=function(r){r.command&&!r.command.readOnly&&this.updateLinkedFields()},t.prototype.onChangeSelection=function(){if(this.editor){for(var r=this.editor.selection.lead,o=this.editor.selection.anchor,c=this.editor.selection.isEmpty(),u=0;u<this.ranges.length;u++)if(!this.ranges[u].linked){var y=this.ranges[u].contains(r.row,r.column),w=c||this.ranges[u].contains(o.row,o.column);if(y&&w)return}this.detach()}},t.prototype.onChangeSession=function(){this.detach()},t.prototype.tabNext=function(r){var o=this.tabstops.length,c=this.index+(r||1);c=Math.min(Math.max(c,1),o),c==o&&(c=0),this.selectTabstop(c),this.updateTabstopMarkers(),c===0&&this.detach()},t.prototype.selectTabstop=function(r){this.$openTabstops=null;var o=this.tabstops[this.index];if(o&&this.addTabstopMarkers(o),this.index=r,o=this.tabstops[this.index],!(!o||!o.length)){this.selectedTabstop=o;var c=o.firstNonLinked||o;if(o.choices&&(c.cursor=c.start),this.editor.inVirtualSelectionMode)this.editor.selection.fromOrientedRange(c);else{var u=this.editor.multiSelect;u.toSingleRange(c);for(var y=0;y<o.length;y++)o.hasLinkedRanges&&o[y].linked||u.addRange(o[y].clone(),!0)}this.editor.keyBinding.addKeyboardHandler(this.keyboardHandler),this.selectedTabstop&&this.selectedTabstop.choices&&this.editor.execCommand("startAutocomplete",{matches:this.selectedTabstop.choices})}},t.prototype.addTabstops=function(r,o,c){var u=this.useLink||!this.editor.getOption("enableMultiselect");if(this.$openTabstops||(this.$openTabstops=[]),!r[0]){var y=s.fromPoints(c,c);R(y.start,o),R(y.end,o),r[0]=[y],r[0].index=0}var w=this.index,C=[w+1,0],_=this.ranges,T=this.snippetId=(this.snippetId||0)+1;r.forEach(function(L,F){var I=this.$openTabstops[F]||L;I.snippetId=T;for(var z=0;z<L.length;z++){var j=L[z],q=s.fromPoints(j.start,j.end||j.start);P(q.start,o),P(q.end,o),q.original=j,q.tabstop=I,_.push(q),I!=L?I.unshift(q):I[z]=q,j.fmtString||I.firstNonLinked&&u?(q.linked=!0,I.hasLinkedRanges=!0):I.firstNonLinked||(I.firstNonLinked=q)}I.firstNonLinked||(I.hasLinkedRanges=!1),I===L&&(C.push(I),this.$openTabstops[F]=I),this.addTabstopMarkers(I),I.rangeList=I.rangeList||new i,I.rangeList.$bias=0,I.rangeList.addList(I)},this),C.length>2&&(this.tabstops.length&&C.push(C.splice(2,1)[0]),this.tabstops.splice.apply(this.tabstops,C))},t.prototype.addTabstopMarkers=function(r){var o=this.session;r.forEach(function(c){c.markerId||(c.markerId=o.addMarker(c,"ace_snippet-marker","text"))})},t.prototype.removeTabstopMarkers=function(r){var o=this.session;r.forEach(function(c){o.removeMarker(c.markerId),c.markerId=null})},t.prototype.updateTabstopMarkers=function(){if(this.selectedTabstop){var r=this.selectedTabstop.snippetId;this.selectedTabstop.index===0&&r--,this.tabstops.forEach(function(o){o.snippetId===r?this.addTabstopMarkers(o):this.removeTabstopMarkers(o)},this)}},t.prototype.removeRange=function(r){var o=r.tabstop.indexOf(r);o!=-1&&r.tabstop.splice(o,1),o=this.ranges.indexOf(r),o!=-1&&this.ranges.splice(o,1),o=r.tabstop.rangeList.ranges.indexOf(r),o!=-1&&r.tabstop.splice(o,1),this.session.removeMarker(r.markerId),r.tabstop.length||(o=this.tabstops.indexOf(r.tabstop),o!=-1&&this.tabstops.splice(o,1),this.tabstops.length||this.detach())},t}();E.prototype.keyboardHandler=new d,E.prototype.keyboardHandler.bindKeys({Tab:function(t){p.snippetManager&&p.snippetManager.expandWithTab(t)||(t.tabstopManager.tabNext(1),t.renderer.scrollCursorIntoView())},"Shift-Tab":function(t){t.tabstopManager.tabNext(-1),t.renderer.scrollCursorIntoView()},Esc:function(t){t.tabstopManager.detach()}});var P=function(t,r){t.row==0&&(t.column+=r.column),t.row+=r.row},R=function(t,r){t.row==r.row&&(t.column-=r.column),t.row-=r.row};a.importCssString(`
.ace_snippet-marker {
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    background: rgba(194, 193, 208, 0.09);
    border: 1px dotted rgba(211, 208, 235, 0.62);
    position: absolute;
}`,"snippets.css",!1),p.snippetManager=new m;var k=n("./editor").Editor;(function(){this.insertSnippet=function(t,r){return p.snippetManager.insertSnippet(this,t,r)},this.expandSnippet=function(t){return p.snippetManager.expandWithTab(this,t)}}).call(k.prototype)}),ace.define("ace/autocomplete/popup",["require","exports","module","ace/virtual_renderer","ace/editor","ace/range","ace/lib/event","ace/lib/lang","ace/lib/dom","ace/config","ace/lib/useragent"],function(n,p,A){var e=n("../virtual_renderer").VirtualRenderer,a=n("../editor").Editor,h=n("../range").Range,g=n("../lib/event"),l=n("../lib/lang"),s=n("../lib/dom"),i=n("../config").nls,d=n("./../lib/useragent"),f=function(P){return"suggest-aria-id:".concat(P)},$=d.isSafari?"menu":"listbox",S=d.isSafari?"menuitem":"option",m=d.isSafari?"aria-current":"aria-selected",v=function(P){var R=new e(P);R.$maxLines=4;var k=new a(R);return k.setHighlightActiveLine(!1),k.setShowPrintMargin(!1),k.renderer.setShowGutter(!1),k.renderer.setHighlightGutterLine(!1),k.$mouseHandler.$focusTimeout=0,k.$highlightTagPending=!0,k},E=function(){function P(R){var k=s.createElement("div"),t=v(k);R&&R.appendChild(k),k.style.display="none",t.renderer.content.style.cursor="default",t.renderer.setStyle("ace_autocomplete"),t.renderer.$textLayer.element.setAttribute("role",$),t.renderer.$textLayer.element.setAttribute("aria-roledescription",i("autocomplete.popup.aria-roledescription","Autocomplete suggestions")),t.renderer.$textLayer.element.setAttribute("aria-label",i("autocomplete.popup.aria-label","Autocomplete suggestions")),t.renderer.textarea.setAttribute("aria-hidden","true"),t.setOption("displayIndentGuides",!1),t.setOption("dragDelay",150);var r=function(){};t.focus=r,t.$isFocused=!0,t.renderer.$cursorLayer.restartTimer=r,t.renderer.$cursorLayer.element.style.opacity="0",t.renderer.$maxLines=8,t.renderer.$keepTextAreaAtCursor=!1,t.setHighlightActiveLine(!1),t.session.highlight(""),t.session.$searchHighlight.clazz="ace_highlight-marker",t.on("mousedown",function(_){var T=_.getDocumentPosition();t.selection.moveToPosition(T),u.start.row=u.end.row=T.row,_.stop()});var o,c=new h(-1,0,-1,1/0),u=new h(-1,0,-1,1/0);u.id=t.session.addMarker(u,"ace_active-line","fullLine"),t.setSelectOnHover=function(_){_?c.id&&(t.session.removeMarker(c.id),c.id=null):c.id=t.session.addMarker(c,"ace_line-hover","fullLine")},t.setSelectOnHover(!1),t.on("mousemove",function(_){if(!o){o=_;return}if(!(o.x==_.x&&o.y==_.y)){o=_,o.scrollTop=t.renderer.scrollTop,t.isMouseOver=!0;var T=o.getDocumentPosition().row;c.start.row!=T&&(c.id||t.setRow(T),w(T))}}),t.renderer.on("beforeRender",function(){if(o&&c.start.row!=-1){o.$pos=null;var _=o.getDocumentPosition().row;c.id||t.setRow(_),w(_,!0)}}),t.renderer.on("afterRender",function(){for(var _=t.renderer.$textLayer,T=_.config.firstRow,L=_.config.lastRow;T<=L;T++){var F=_.element.childNodes[T-_.config.firstRow];F.setAttribute("role",S),F.setAttribute("aria-roledescription",i("autocomplete.popup.item.aria-roledescription","item")),F.setAttribute("aria-setsize",t.data.length),F.setAttribute("aria-describedby","doc-tooltip"),F.setAttribute("aria-posinset",T+1);var I=t.getData(T);if(I){var z="".concat(I.caption||I.value).concat(I.meta?", ".concat(I.meta):"");F.setAttribute("aria-label",z)}var j=F.querySelectorAll(".ace_completion-highlight");j.forEach(function(q){q.setAttribute("role","mark")})}}),t.renderer.on("afterRender",function(){var _=t.getRow(),T=t.renderer.$textLayer,L=T.element.childNodes[_-T.config.firstRow],F=document.activeElement;if(L!==t.selectedNode&&t.selectedNode&&(s.removeCssClass(t.selectedNode,"ace_selected"),t.selectedNode.removeAttribute(m),t.selectedNode.removeAttribute("id")),F.removeAttribute("aria-activedescendant"),t.selectedNode=L,L){var I=f(_);s.addCssClass(L,"ace_selected"),L.id=I,T.element.setAttribute("aria-activedescendant",I),F.setAttribute("aria-activedescendant",I),L.setAttribute(m,"true")}});var y=function(){w(-1)},w=function(_,T){_!==c.start.row&&(c.start.row=c.end.row=_,T||t.session._emit("changeBackMarker"),t._emit("changeHoverMarker"))};t.getHoveredRow=function(){return c.start.row},g.addListener(t.container,"mouseout",function(){t.isMouseOver=!1,y()}),t.on("hide",y),t.on("changeSelection",y),t.session.doc.getLength=function(){return t.data.length},t.session.doc.getLine=function(_){var T=t.data[_];return typeof T=="string"?T:T&&T.value||""};var C=t.session.bgTokenizer;return C.$tokenizeRow=function(_){function T(D,X){D&&F.push({type:(L.className||"")+(X||""),value:D})}var L=t.data[_],F=[];if(!L)return F;typeof L=="string"&&(L={value:L});for(var I=L.caption||L.value||L.name,z=I.toLowerCase(),j=(t.filterText||"").toLowerCase(),q=0,J=0,ne=0;ne<=j.length;ne++)if(ne!=J&&(L.matchMask&1<<ne||ne==j.length)){var Z=j.slice(J,ne);J=ne;var G=z.indexOf(Z,q);if(G==-1)continue;T(I.slice(q,G),""),q=G+Z.length,T(I.slice(G,q),"completion-highlight")}return T(I.slice(q,I.length),""),F.push({type:"completion-spacer",value:" "}),L.meta&&F.push({type:"completion-meta",value:L.meta}),L.message&&F.push({type:"completion-message",value:L.message}),F},C.$updateOnChange=r,C.start=r,t.session.$computeWidth=function(){return this.screenWidth=0},t.isOpen=!1,t.isTopdown=!1,t.autoSelect=!0,t.filterText="",t.isMouseOver=!1,t.data=[],t.setData=function(_,T){t.filterText=T||"",t.setValue(l.stringRepeat(`
`,_.length),-1),t.data=_||[],t.setRow(0)},t.getData=function(_){return t.data[_]},t.getRow=function(){return u.start.row},t.setRow=function(_){_=Math.max(this.autoSelect?0:-1,Math.min(this.data.length-1,_)),u.start.row!=_&&(t.selection.clearSelection(),u.start.row=u.end.row=_||0,t.session._emit("changeBackMarker"),t.moveCursorTo(_||0,0),t.isOpen&&t._signal("select"))},t.on("changeSelection",function(){t.isOpen&&t.setRow(t.selection.lead.row),t.renderer.scrollCursorIntoView()}),t.hide=function(){this.container.style.display="none",t.anchorPos=null,t.anchor=null,t.isOpen&&(t.isOpen=!1,this._signal("hide"))},t.tryShow=function(_,T,L,F){if(!F&&t.isOpen&&t.anchorPos&&t.anchor&&t.anchorPos.top===_.top&&t.anchorPos.left===_.left&&t.anchor===L)return!0;var I=this.container,z=this.renderer.scrollBar.width||10,j=window.innerHeight-z,q=window.innerWidth-z,J=this.renderer,ne=J.$maxLines*T*1.4,Z={top:0,bottom:0,left:0},G=j-_.top-3*this.$borderSize-T,D=_.top-3*this.$borderSize;L||(D<=G||G>=ne?L="bottom":L="top"),L==="top"?(Z.bottom=_.top-this.$borderSize,Z.top=Z.bottom-ne):L==="bottom"&&(Z.top=_.top+T+this.$borderSize,Z.bottom=Z.top+ne);var X=Z.top>=0&&Z.bottom<=j;if(!F&&!X)return!1;X?J.$maxPixelHeight=null:L==="top"?J.$maxPixelHeight=D:J.$maxPixelHeight=G,L==="top"?(I.style.top="",I.style.bottom=j+z-Z.bottom+"px",t.isTopdown=!1):(I.style.top=Z.top+"px",I.style.bottom="",t.isTopdown=!0),I.style.display="";var K=_.left;return K+I.offsetWidth>q&&(K=q-I.offsetWidth),I.style.left=K+"px",I.style.right="",s.$fixPositionBug(I),t.isOpen||(t.isOpen=!0,this._signal("show"),o=null),t.anchorPos=_,t.anchor=L,!0},t.show=function(_,T,L){this.tryShow(_,T,L?"bottom":void 0,!0)},t.goTo=function(_){var T=this.getRow(),L=this.session.getLength()-1;switch(_){case"up":T=T<=0?L:T-1;break;case"down":T=T>=L?-1:T+1;break;case"start":T=0;break;case"end":T=L}this.setRow(T)},t.getTextLeftOffset=function(){return this.$borderSize+this.renderer.$padding+this.$imageSize},t.$imageSize=0,t.$borderSize=1,t}return P}();s.importCssString(`
.ace_editor.ace_autocomplete .ace_marker-layer .ace_active-line {
    background-color: #CAD6FA;
    z-index: 1;
}
.ace_dark.ace_editor.ace_autocomplete .ace_marker-layer .ace_active-line {
    background-color: #3a674e;
}
.ace_editor.ace_autocomplete .ace_line-hover {
    border: 1px solid #abbffe;
    margin-top: -1px;
    background: rgba(233,233,253,0.4);
    position: absolute;
    z-index: 2;
}
.ace_dark.ace_editor.ace_autocomplete .ace_line-hover {
    border: 1px solid rgba(109, 150, 13, 0.8);
    background: rgba(58, 103, 78, 0.62);
}
.ace_completion-meta {
    opacity: 0.5;
    margin-left: 0.9em;
}
.ace_completion-message {
    margin-left: 0.9em;
    color: blue;
}
.ace_editor.ace_autocomplete .ace_completion-highlight{
    color: #2d69c7;
}
.ace_dark.ace_editor.ace_autocomplete .ace_completion-highlight{
    color: #93ca12;
}
.ace_editor.ace_autocomplete {
    width: 300px;
    z-index: 200000;
    border: 1px lightgray solid;
    position: fixed;
    box-shadow: 2px 3px 5px rgba(0,0,0,.2);
    line-height: 1.4;
    background: #fefefe;
    color: #111;
}
.ace_dark.ace_editor.ace_autocomplete {
    border: 1px #484747 solid;
    box-shadow: 2px 3px 5px rgba(0, 0, 0, 0.51);
    line-height: 1.4;
    background: #25282c;
    color: #c1c1c1;
}
.ace_autocomplete .ace_text-layer  {
    width: calc(100% - 8px);
}
.ace_autocomplete .ace_line {
    display: flex;
    align-items: center;
}
.ace_autocomplete .ace_line > * {
    min-width: 0;
    flex: 0 0 auto;
}
.ace_autocomplete .ace_line .ace_ {
    flex: 0 1 auto;
    overflow: hidden;
    text-overflow: ellipsis;
}
.ace_autocomplete .ace_completion-spacer {
    flex: 1;
}
.ace_autocomplete.ace_loading:after  {
    content: "";
    position: absolute;
    top: 0px;
    height: 2px;
    width: 8%;
    background: blue;
    z-index: 100;
    animation: ace_progress 3s infinite linear;
    animation-delay: 300ms;
    transform: translateX(-100%) scaleX(1);
}
@keyframes ace_progress {
    0% { transform: translateX(-100%) scaleX(1) }
    50% { transform: translateX(625%) scaleX(2) } 
    100% { transform: translateX(1500%) scaleX(3) } 
}
@media (prefers-reduced-motion) {
    .ace_autocomplete.ace_loading:after {
        transform: translateX(625%) scaleX(2);
        animation: none;
     }
}
`,"autocompletion.css",!1),p.AcePopup=E,p.$singleLineEditor=v,p.getAriaId=f}),ace.define("ace/autocomplete/inline_screenreader",["require","exports","module"],function(n,p,A){var e=function(){function a(h){this.editor=h,this.screenReaderDiv=document.createElement("div"),this.screenReaderDiv.classList.add("ace_screenreader-only"),this.editor.container.appendChild(this.screenReaderDiv)}return a.prototype.setScreenReaderContent=function(h){for(!this.popup&&this.editor.completer&&this.editor.completer.popup&&(this.popup=this.editor.completer.popup,this.popup.renderer.on("afterRender",function(){var l=this.popup.getRow(),s=this.popup.renderer.$textLayer,i=s.element.childNodes[l-s.config.firstRow];if(i){for(var d="doc-tooltip ",f=0;f<this._lines.length;f++)d+="ace-inline-screenreader-line-".concat(f," ");i.setAttribute("aria-describedby",d)}}.bind(this)));this.screenReaderDiv.firstChild;)this.screenReaderDiv.removeChild(this.screenReaderDiv.firstChild);this._lines=h.split(/\r\n|\r|\n/);var g=this.createCodeBlock();this.screenReaderDiv.appendChild(g)},a.prototype.destroy=function(){this.screenReaderDiv.remove()},a.prototype.createCodeBlock=function(){var h=document.createElement("pre");h.setAttribute("id","ace-inline-screenreader");for(var g=0;g<this._lines.length;g++){var l=document.createElement("code");l.setAttribute("id","ace-inline-screenreader-line-".concat(g));var s=document.createTextNode(this._lines[g]);l.appendChild(s),h.appendChild(l)}return h},a}();p.AceInlineScreenReader=e}),ace.define("ace/autocomplete/inline",["require","exports","module","ace/snippets","ace/autocomplete/inline_screenreader"],function(n,p,A){var e=n("../snippets").snippetManager,a=n("./inline_screenreader").AceInlineScreenReader,h=function(){function g(){this.editor=null}return g.prototype.show=function(l,s,i){if(i=i||"",l&&this.editor&&this.editor!==l&&(this.hide(),this.editor=null,this.inlineScreenReader=null),!l||!s)return!1;this.inlineScreenReader||(this.inlineScreenReader=new a(l));var d=s.snippet?e.getDisplayTextForSnippet(l,s.snippet):s.value;return s.hideInlinePreview||!d||!d.startsWith(i)?!1:(this.editor=l,this.inlineScreenReader.setScreenReaderContent(d),d=d.slice(i.length),d===""?l.removeGhostText():l.setGhostText(d),!0)},g.prototype.isOpen=function(){return this.editor?!!this.editor.renderer.$ghostText:!1},g.prototype.hide=function(){return this.editor?(this.editor.removeGhostText(),!0):!1},g.prototype.destroy=function(){this.hide(),this.editor=null,this.inlineScreenReader&&(this.inlineScreenReader.destroy(),this.inlineScreenReader=null)},g}();p.AceInline=h}),ace.define("ace/autocomplete/util",["require","exports","module"],function(n,p,A){p.parForEach=function(a,h,g){var l=0,s=a.length;s===0&&g();for(var i=0;i<s;i++)h(a[i],function(d,f){l++,l===s&&g(d,f)})};var e=/[a-zA-Z_0-9\$\-\u00A2-\u2000\u2070-\uFFFF]/;p.retrievePrecedingIdentifier=function(a,h,g){g=g||e;for(var l=[],s=h-1;s>=0&&g.test(a[s]);s--)l.push(a[s]);return l.reverse().join("")},p.retrieveFollowingIdentifier=function(a,h,g){g=g||e;for(var l=[],s=h;s<a.length&&g.test(a[s]);s++)l.push(a[s]);return l},p.getCompletionPrefix=function(a){var h=a.getCursorPosition(),g=a.session.getLine(h.row),l;return a.completers.forEach(function(s){s.identifierRegexps&&s.identifierRegexps.forEach(function(i){!l&&i&&(l=this.retrievePrecedingIdentifier(g,h.column,i))}.bind(this))}.bind(this)),l||this.retrievePrecedingIdentifier(g,h.column)},p.triggerAutocomplete=function(a,g){var g=g==null?a.session.getPrecedingCharacter():g;return a.completers.some(function(l){if(l.triggerCharacters&&Array.isArray(l.triggerCharacters))return l.triggerCharacters.includes(g)})}}),ace.define("ace/autocomplete",["require","exports","module","ace/keyboard/hash_handler","ace/autocomplete/popup","ace/autocomplete/inline","ace/autocomplete/popup","ace/autocomplete/util","ace/lib/lang","ace/lib/dom","ace/snippets","ace/config","ace/lib/event","ace/lib/scroll"],function(n,p,A){var e=n("./keyboard/hash_handler").HashHandler,a=n("./autocomplete/popup").AcePopup,h=n("./autocomplete/inline").AceInline,g=n("./autocomplete/popup").getAriaId,l=n("./autocomplete/util"),s=n("./lib/lang"),i=n("./lib/dom"),d=n("./snippets").snippetManager,f=n("./config"),$=n("./lib/event"),S=n("./lib/scroll").preventParentScroll,m=function(R,k){k.completer&&k.completer.destroy()},v=function(){function R(){this.autoInsert=!1,this.autoSelect=!0,this.autoShown=!1,this.exactMatch=!1,this.inlineEnabled=!1,this.keyboardHandler=new e,this.keyboardHandler.bindKeys(this.commands),this.parentNode=null,this.setSelectOnHover=!1,this.hasSeen=new Set,this.showLoadingState=!1,this.stickySelectionDelay=500,this.blurListener=this.blurListener.bind(this),this.changeListener=this.changeListener.bind(this),this.mousedownListener=this.mousedownListener.bind(this),this.mousewheelListener=this.mousewheelListener.bind(this),this.onLayoutChange=this.onLayoutChange.bind(this),this.changeTimer=s.delayedCall(function(){this.updateCompletions(!0)}.bind(this)),this.tooltipTimer=s.delayedCall(this.updateDocTooltip.bind(this),50),this.popupTimer=s.delayedCall(this.$updatePopupPosition.bind(this),50),this.stickySelectionTimer=s.delayedCall(function(){this.stickySelection=!0}.bind(this),this.stickySelectionDelay),this.$firstOpenTimer=s.delayedCall(function(){var k=this.completionProvider&&this.completionProvider.initialPosition;this.autoShown||this.popup&&this.popup.isOpen||!k||this.editor.completers.length===0||(this.completions=new P(R.completionsForLoading),this.openPopup(this.editor,k.prefix,!1),this.popup.renderer.setStyle("ace_loading",!0))}.bind(this),this.stickySelectionDelay)}return Object.defineProperty(R,"completionsForLoading",{get:function(){return[{caption:f.nls("autocomplete.loading","Loading..."),value:""}]},enumerable:!1,configurable:!0}),R.prototype.$init=function(){return this.popup=new a(this.parentNode||document.body||document.documentElement),this.popup.on("click",function(k){this.insertMatch(),k.stop()}.bind(this)),this.popup.focus=this.editor.focus.bind(this.editor),this.popup.on("show",this.$onPopupShow.bind(this)),this.popup.on("hide",this.$onHidePopup.bind(this)),this.popup.on("select",this.$onPopupChange.bind(this)),$.addListener(this.popup.container,"mouseout",this.mouseOutListener.bind(this)),this.popup.on("changeHoverMarker",this.tooltipTimer.bind(null,null)),this.popup.renderer.on("afterRender",this.$onPopupRender.bind(this)),this.popup},R.prototype.$initInline=function(){if(!(!this.inlineEnabled||this.inlineRenderer))return this.inlineRenderer=new h,this.inlineRenderer},R.prototype.getPopup=function(){return this.popup||this.$init()},R.prototype.$onHidePopup=function(){this.inlineRenderer&&this.inlineRenderer.hide(),this.hideDocTooltip(),this.stickySelectionTimer.cancel(),this.popupTimer.cancel(),this.stickySelection=!1},R.prototype.$seen=function(k){!this.hasSeen.has(k)&&k&&k.completer&&k.completer.onSeen&&typeof k.completer.onSeen=="function"&&(k.completer.onSeen(this.editor,k),this.hasSeen.add(k))},R.prototype.$onPopupChange=function(k){if(this.inlineRenderer&&this.inlineEnabled){var t=k?null:this.popup.getData(this.popup.getRow());if(this.$updateGhostText(t),this.popup.isMouseOver&&this.setSelectOnHover){this.tooltipTimer.call(null,null);return}this.popupTimer.schedule(),this.tooltipTimer.schedule()}else this.popupTimer.call(null,null),this.tooltipTimer.call(null,null)},R.prototype.$updateGhostText=function(k){var t=this.base.row,r=this.base.column,o=this.editor.getCursorPosition().column,c=this.editor.session.getLine(t).slice(r,o);this.inlineRenderer.show(this.editor,k,c)?this.$seen(k):this.inlineRenderer.hide()},R.prototype.$onPopupRender=function(){var k=this.inlineRenderer&&this.inlineEnabled;if(this.completions&&this.completions.filtered&&this.completions.filtered.length>0)for(var t=this.popup.getFirstVisibleRow();t<=this.popup.getLastVisibleRow();t++){var r=this.popup.getData(t);r&&(!k||r.hideInlinePreview)&&this.$seen(r)}},R.prototype.$onPopupShow=function(k){this.$onPopupChange(k),this.stickySelection=!1,this.stickySelectionDelay>=0&&this.stickySelectionTimer.schedule(this.stickySelectionDelay)},R.prototype.observeLayoutChanges=function(){if(!(this.$elements||!this.editor)){window.addEventListener("resize",this.onLayoutChange,{passive:!0}),window.addEventListener("wheel",this.mousewheelListener);for(var k=this.editor.container.parentNode,t=[];k;)t.push(k),k.addEventListener("scroll",this.onLayoutChange,{passive:!0}),k=k.parentNode;this.$elements=t}},R.prototype.unObserveLayoutChanges=function(){var k=this;window.removeEventListener("resize",this.onLayoutChange,{passive:!0}),window.removeEventListener("wheel",this.mousewheelListener),this.$elements&&this.$elements.forEach(function(t){t.removeEventListener("scroll",k.onLayoutChange,{passive:!0})}),this.$elements=null},R.prototype.onLayoutChange=function(){if(!this.popup.isOpen)return this.unObserveLayoutChanges();this.$updatePopupPosition(),this.updateDocTooltip()},R.prototype.$updatePopupPosition=function(){var k=this.editor,t=k.renderer,r=t.layerConfig.lineHeight,o=t.$cursorLayer.getPixelPosition(this.base,!0);o.left-=this.popup.getTextLeftOffset();var c=k.container.getBoundingClientRect();o.top+=c.top-t.layerConfig.offset,o.left+=c.left-k.renderer.scrollLeft,o.left+=t.gutterWidth;var u={top:o.top,left:o.left};t.$ghostText&&t.$ghostTextWidget&&this.base.row===t.$ghostText.position.row&&(u.top+=t.$ghostTextWidget.el.offsetHeight);var y=k.container.getBoundingClientRect().bottom-r,w=y<u.top?{top:y,left:u.left}:u;this.popup.tryShow(w,r,"bottom")||this.popup.tryShow(o,r,"top")||this.popup.show(o,r)},R.prototype.openPopup=function(k,t,r){this.$firstOpenTimer.cancel(),this.popup||this.$init(),this.inlineEnabled&&!this.inlineRenderer&&this.$initInline(),this.popup.autoSelect=this.autoSelect,this.popup.setSelectOnHover(this.setSelectOnHover);var o=this.popup.getRow(),c=this.popup.data[o];this.popup.setData(this.completions.filtered,this.completions.filterText),this.editor.textInput.setAriaOptions&&this.editor.textInput.setAriaOptions({activeDescendant:g(this.popup.getRow()),inline:this.inlineEnabled}),k.keyBinding.addKeyboardHandler(this.keyboardHandler);var u;this.stickySelection&&(u=this.popup.data.indexOf(c)),(!u||u===-1)&&(u=0),this.popup.setRow(this.autoSelect?u:-1),u===o&&c!==this.completions.filtered[u]&&this.$onPopupChange();var y=this.inlineRenderer&&this.inlineEnabled;if(u===o&&y){var w=this.popup.getData(this.popup.getRow());this.$updateGhostText(w)}r||(this.popup.setTheme(k.getTheme()),this.popup.setFontSize(k.getFontSize()),this.$updatePopupPosition(),this.tooltipNode&&this.updateDocTooltip()),this.changeTimer.cancel(),this.observeLayoutChanges()},R.prototype.detach=function(){this.editor&&(this.editor.keyBinding.removeKeyboardHandler(this.keyboardHandler),this.editor.off("changeSelection",this.changeListener),this.editor.off("blur",this.blurListener),this.editor.off("mousedown",this.mousedownListener),this.editor.off("mousewheel",this.mousewheelListener)),this.$firstOpenTimer.cancel(),this.changeTimer.cancel(),this.hideDocTooltip(),this.completionProvider&&this.completionProvider.detach(),this.popup&&this.popup.isOpen&&this.popup.hide(),this.popup&&this.popup.renderer&&this.popup.renderer.off("afterRender",this.$onPopupRender),this.base&&this.base.detach(),this.activated=!1,this.completionProvider=this.completions=this.base=null,this.unObserveLayoutChanges()},R.prototype.changeListener=function(k){var t=this.editor.selection.lead;(t.row!=this.base.row||t.column<this.base.column)&&this.detach(),this.activated?this.changeTimer.schedule():this.detach()},R.prototype.blurListener=function(k){var t=document.activeElement,r=this.editor.textInput.getElement(),o=k.relatedTarget&&this.tooltipNode&&this.tooltipNode.contains(k.relatedTarget),c=this.popup&&this.popup.container;t!=r&&t.parentNode!=c&&!o&&t!=this.tooltipNode&&k.relatedTarget!=r&&this.detach()},R.prototype.mousedownListener=function(k){this.detach()},R.prototype.mousewheelListener=function(k){this.popup&&!this.popup.isMouseOver&&this.detach()},R.prototype.mouseOutListener=function(k){this.popup.isOpen&&this.$updatePopupPosition()},R.prototype.goTo=function(k){this.popup.goTo(k)},R.prototype.insertMatch=function(k,t){if(k||(k=this.popup.getData(this.popup.getRow())),!k)return!1;if(k.value==="")return this.detach();var r=this.completions,o=this.getCompletionProvider().insertMatch(this.editor,k,r.filterText,t);return this.completions==r&&this.detach(),o},R.prototype.showPopup=function(k,t){this.editor&&this.detach(),this.activated=!0,this.editor=k,k.completer!=this&&(k.completer&&k.completer.detach(),k.completer=this),k.on("changeSelection",this.changeListener),k.on("blur",this.blurListener),k.on("mousedown",this.mousedownListener),k.on("mousewheel",this.mousewheelListener),this.updateCompletions(!1,t)},R.prototype.getCompletionProvider=function(k){return this.completionProvider||(this.completionProvider=new E(k)),this.completionProvider},R.prototype.gatherCompletions=function(k,t){return this.getCompletionProvider().gatherCompletions(k,t)},R.prototype.updateCompletions=function(k,t){if(k&&this.base&&this.completions){var o=this.editor.getCursorPosition(),c=this.editor.session.getTextRange({start:this.base,end:o});if(c==this.completions.filterText)return;if(this.completions.setFilter(c),!this.completions.filtered.length)return this.detach();if(this.completions.filtered.length==1&&this.completions.filtered[0].value==c&&!this.completions.filtered[0].snippet)return this.detach();this.openPopup(this.editor,c,k);return}if(t&&t.matches){var o=this.editor.getSelectionRange().start;return this.base=this.editor.session.doc.createAnchor(o.row,o.column),this.base.$insertRight=!0,this.completions=new P(t.matches),this.getCompletionProvider().completions=this.completions,this.openPopup(this.editor,"",k)}var r=this.editor.getSession(),o=this.editor.getCursorPosition(),c=l.getCompletionPrefix(this.editor);this.base=r.doc.createAnchor(o.row,o.column-c.length),this.base.$insertRight=!0;var u={exactMatch:this.exactMatch,ignoreCaption:this.ignoreCaption};this.getCompletionProvider({prefix:c,pos:o}).provideCompletions(this.editor,u,function(y,w,C){var _=w.filtered,T=l.getCompletionPrefix(this.editor);if(this.$firstOpenTimer.cancel(),C){if(!_.length){var L=!this.autoShown&&this.emptyMessage;if(typeof L=="function"&&(L=this.emptyMessage(T)),L){var F=[{caption:L,value:""}];this.completions=new P(F),this.openPopup(this.editor,T,k),this.popup.renderer.setStyle("ace_loading",!1),this.popup.renderer.setStyle("ace_empty-message",!0);return}return this.detach()}if(_.length==1&&_[0].value==T&&!_[0].snippet)return this.detach();if(this.autoInsert&&!this.autoShown&&_.length==1)return this.insertMatch(_[0])}this.completions=!C&&this.showLoadingState?new P(R.completionsForLoading.concat(_),w.filterText):w,this.openPopup(this.editor,T,k),this.popup.renderer.setStyle("ace_empty-message",!1),this.popup.renderer.setStyle("ace_loading",!C)}.bind(this)),this.showLoadingState&&!this.autoShown&&(!this.popup||!this.popup.isOpen)&&this.$firstOpenTimer.delay(this.stickySelectionDelay/2)},R.prototype.cancelContextMenu=function(){this.editor.$mouseHandler.cancelContextMenu()},R.prototype.updateDocTooltip=function(){var k=this.popup,t=this.completions&&this.completions.filtered,r=t&&(t[k.getHoveredRow()]||t[k.getRow()]),o=null;if(!r||!this.editor||!this.popup.isOpen)return this.hideDocTooltip();for(var c=this.editor.completers.length,u=0;u<c;u++){var y=this.editor.completers[u];if(y.getDocTooltip&&r.completerId===y.id){o=y.getDocTooltip(r);break}}if(!o&&typeof r!="string"&&(o=r),typeof o=="string"&&(o={docText:o}),!o||!o.docHTML&&!o.docText)return this.hideDocTooltip();this.showDocTooltip(o)},R.prototype.showDocTooltip=function(k){this.tooltipNode||(this.tooltipNode=i.createElement("div"),this.tooltipNode.style.margin="0",this.tooltipNode.style.pointerEvents="auto",this.tooltipNode.style.overscrollBehavior="contain",this.tooltipNode.tabIndex=-1,this.tooltipNode.onblur=this.blurListener.bind(this),this.tooltipNode.onclick=this.onTooltipClick.bind(this),this.tooltipNode.id="doc-tooltip",this.tooltipNode.setAttribute("role","tooltip"),this.tooltipNode.addEventListener("wheel",S));var t=this.editor.renderer.theme;this.tooltipNode.className="ace_tooltip ace_doc-tooltip "+(t.isDark?"ace_dark ":"")+(t.cssClass||"");var r=this.tooltipNode;k.docHTML?r.innerHTML=k.docHTML:k.docText&&(r.textContent=k.docText),r.parentNode||this.popup.container.appendChild(this.tooltipNode);var o=this.popup,c=o.container.getBoundingClientRect(),u=400,y=300,w=o.renderer.scrollBar.width||10,C=c.left,_=window.innerWidth-c.right-w,T=o.isTopdown?window.innerHeight-w-c.bottom:c.top,L=[Math.min(_/u,1),Math.min(C/u,1),Math.min(T/y,1)*.9],F=Math.max.apply(Math,L),I=r.style;I.display="block",F==L[0]||L[0]>=1?(I.left=c.right+1+"px",I.right="",I.maxWidth=u*F+"px",I.top=c.top+"px",I.bottom="",I.maxHeight=Math.min(window.innerHeight-w-c.top,y)+"px"):F==L[1]||L[1]>=1?(I.right=window.innerWidth-c.left+"px",I.left="",I.maxWidth=u*F+"px",I.top=c.top+"px",I.bottom="",I.maxHeight=Math.min(window.innerHeight-w-c.top,y)+"px"):F==L[2]&&(I.left=c.left+"px",I.right="",I.maxWidth=Math.min(u,window.innerWidth-c.left)+"px",o.isTopdown?(I.top=c.bottom+"px",I.bottom="",I.maxHeight=Math.min(window.innerHeight-w-c.bottom,y)+"px"):(I.top="",I.bottom=window.innerHeight-c.top+"px",I.maxHeight=Math.min(c.top,y)+"px")),i.$fixPositionBug(r)},R.prototype.hideDocTooltip=function(){if(this.tooltipTimer.cancel(),!!this.tooltipNode){var k=this.tooltipNode;!this.editor.isFocused()&&document.activeElement==k&&this.editor.focus(),this.tooltipNode=null,k.parentNode&&k.parentNode.removeChild(k)}},R.prototype.onTooltipClick=function(k){for(var t=k.target;t&&t!=this.tooltipNode;){if(t.nodeName=="A"&&t.href){t.rel="noreferrer",t.target="_blank";break}t=t.parentNode}},R.prototype.destroy=function(){if(this.detach(),this.popup){this.popup.destroy();var k=this.popup.container;k&&k.parentNode&&k.parentNode.removeChild(k)}this.editor&&this.editor.completer==this&&(this.editor.off("destroy",m),this.editor.completer=null),this.inlineRenderer=this.popup=this.editor=null},R.for=function(k){return k.completer instanceof R||(k.completer&&(k.completer.destroy(),k.completer=null),f.get("sharedPopups")?(R.$sharedInstance||(R.$sharedInstance=new R),k.completer=R.$sharedInstance):(k.completer=new R,k.once("destroy",m))),k.completer},R}();v.prototype.commands={Up:function(R){R.completer.goTo("up")},Down:function(R){R.completer.goTo("down")},"Ctrl-Up|Ctrl-Home":function(R){R.completer.goTo("start")},"Ctrl-Down|Ctrl-End":function(R){R.completer.goTo("end")},Esc:function(R){R.completer.detach()},Return:function(R){return R.completer.insertMatch()},"Shift-Return":function(R){R.completer.insertMatch(null,{deleteSuffix:!0})},Tab:function(R){var k=R.completer.insertMatch();if(k||R.tabstopManager)return k;R.completer.goTo("down")},Backspace:function(R){R.execCommand("backspace");var k=l.getCompletionPrefix(R);!k&&R.completer&&R.completer.detach()},PageUp:function(R){R.completer.popup.gotoPageUp()},PageDown:function(R){R.completer.popup.gotoPageDown()}},v.startCommand={name:"startAutocomplete",exec:function(R,k){var t=v.for(R);t.autoInsert=!1,t.autoSelect=!0,t.autoShown=!1,t.showPopup(R,k),t.cancelContextMenu()},bindKey:"Ctrl-Space|Ctrl-Shift-Space|Alt-Space"};var E=function(){function R(k){this.initialPosition=k,this.active=!0}return R.prototype.insertByIndex=function(k,t,r){return!this.completions||!this.completions.filtered?!1:this.insertMatch(k,this.completions.filtered[t],r)},R.prototype.insertMatch=function(k,t,r){if(!t)return!1;if(k.startOperation({command:{name:"insertMatch"}}),t.completer&&t.completer.insertMatch)t.completer.insertMatch(k,t);else{if(!this.completions)return!1;var o=this.completions.filterText.length,c=0;if(t.range&&t.range.start.row===t.range.end.row&&(o-=this.initialPosition.prefix.length,o+=this.initialPosition.pos.column-t.range.start.column,c+=t.range.end.column-this.initialPosition.pos.column),o||c){var u;k.selection.getAllRanges?u=k.selection.getAllRanges():u=[k.getSelectionRange()];for(var y=0,w;w=u[y];y++)w.start.column-=o,w.end.column+=c,k.session.remove(w)}t.snippet?d.insertSnippet(k,t.snippet):this.$insertString(k,t),t.completer&&t.completer.onInsert&&typeof t.completer.onInsert=="function"&&t.completer.onInsert(k,t),t.command&&t.command==="startAutocomplete"&&k.execCommand(t.command)}return k.endOperation(),!0},R.prototype.$insertString=function(k,t){var r=t.value||t;k.execCommand("insertstring",r)},R.prototype.gatherCompletions=function(k,t){var r=k.getSession(),o=k.getCursorPosition(),c=l.getCompletionPrefix(k),u=[];this.completers=k.completers;var y=k.completers.length;return k.completers.forEach(function(w,C){w.getCompletions(k,r,o,c,function(_,T){w.hideInlinePreview&&(T=T.map(function(L){return Object.assign(L,{hideInlinePreview:w.hideInlinePreview})})),!_&&T&&(u=u.concat(T)),t(null,{prefix:l.getCompletionPrefix(k),matches:u,finished:--y===0})})}),!0},R.prototype.provideCompletions=function(k,t,r){var o=function(w){var C=w.prefix,_=w.matches;this.completions=new P(_),t.exactMatch&&(this.completions.exactMatch=!0),t.ignoreCaption&&(this.completions.ignoreCaption=!0),this.completions.setFilter(C),(w.finished||this.completions.filtered.length)&&r(null,this.completions,w.finished)}.bind(this),c=!0,u=null;if(this.gatherCompletions(k,function(w,C){if(this.active){w&&(r(w,[],!0),this.detach());var _=C.prefix;if(_.indexOf(C.prefix)===0){if(c){u=C;return}o(C)}}}.bind(this)),c=!1,u){var y=u;u=null,o(y)}},R.prototype.detach=function(){this.active=!1,this.completers&&this.completers.forEach(function(k){typeof k.cancel=="function"&&k.cancel()})},R}(),P=function(){function R(k,t){this.all=k,this.filtered=k,this.filterText=t||"",this.exactMatch=!1,this.ignoreCaption=!1}return R.prototype.setFilter=function(k){if(k.length>this.filterText&&k.lastIndexOf(this.filterText,0)===0)var t=this.filtered;else var t=this.all;this.filterText=k,t=this.filterCompletions(t,this.filterText),t=t.sort(function(o,c){return c.exactMatch-o.exactMatch||c.$score-o.$score||(o.caption||o.value).localeCompare(c.caption||c.value)});var r=null;t=t.filter(function(o){var c=o.snippet||o.caption||o.value;return c===r?!1:(r=c,!0)}),this.filtered=t},R.prototype.filterCompletions=function(k,t){var r=[],o=t.toUpperCase(),c=t.toLowerCase();e:for(var u=0,y;y=k[u];u++){if(y.skipFilter){y.$score=y.score,r.push(y);continue}var w=!this.ignoreCaption&&y.caption||y.value||y.snippet;if(w){var C=-1,_=0,T=0,L,F;if(this.exactMatch){if(t!==w.substr(0,t.length))continue e}else{var I=w.toLowerCase().indexOf(c);if(I>-1)T=I;else for(var z=0;z<t.length;z++){var j=w.indexOf(c[z],C+1),q=w.indexOf(o[z],C+1);if(L=j>=0&&(q<0||j<q)?j:q,L<0)continue e;F=L-C-1,F>0&&(C===-1&&(T+=10),T+=F,_|=1<<z),C=L}}y.matchMask=_,y.exactMatch=T?0:1,y.$score=(y.score||0)-T,r.push(y)}}return r},R}();p.Autocomplete=v,p.CompletionProvider=E,p.FilteredList=P}),ace.define("ace/marker_group",["require","exports","module"],function(n,p,A){var e=function(){function a(h,g){g&&(this.markerType=g.markerType),this.markers=[],this.session=h,h.addDynamicMarker(this)}return a.prototype.getMarkerAtPosition=function(h){return this.markers.find(function(g){return g.range.contains(h.row,h.column)})},a.prototype.markersComparator=function(h,g){return h.range.start.row-g.range.start.row},a.prototype.setMarkers=function(h){this.markers=h.sort(this.markersComparator).slice(0,this.MAX_MARKERS),this.session._signal("changeBackMarker")},a.prototype.update=function(h,g,l,s){if(!(!this.markers||!this.markers.length))for(var i=s.firstRow,d=s.lastRow,f,$=0,S=0,m=0;m<this.markers.length;m++){var v=this.markers[m];if(!(v.range.end.row<i)&&!(v.range.start.row>d)&&(v.range.start.row===S?$++:(S=v.range.start.row,$=0),!($>200))){var E=v.range.clipRows(i,d);if(!(E.start.row===E.end.row&&E.start.column===E.end.column)){var P=E.toScreenRange(l);if(P.isEmpty()){f=l.getNextFoldLine(E.end.row,f),f&&f.end.row>E.end.row&&(i=f.end.row);continue}this.markerType==="fullLine"?g.drawFullLineMarker(h,P,v.className,s):P.isMultiLine()?this.markerType==="line"?g.drawMultiLineMarker(h,P,v.className,s):g.drawTextMarker(h,P,v.className,s):g.drawSingleLineMarker(h,P,v.className+" ace_br15",s)}}}},a}();e.prototype.MAX_MARKERS=1e4,p.MarkerGroup=e}),ace.define("ace/autocomplete/text_completer",["require","exports","module","ace/range"],function(n,p,A){function e(l,s){var i=l.getTextRange(h.fromPoints({row:0,column:0},s));return i.split(g).length-1}function a(l,s){var i=e(l,s),d=l.getValue().split(g),f=Object.create(null),$=d[i];return d.forEach(function(S,m){if(!(!S||S===$)){var v=Math.abs(i-m),E=d.length-v;f[S]?f[S]=Math.max(E,f[S]):f[S]=E}}),f}var h=n("../range").Range,g=/[^a-zA-Z_0-9\$\-\u00C0-\u1FFF\u2C00-\uD7FF\w]+/;p.id="textCompleter",p.getCompletions=function(l,s,i,d,f){var $=a(s,i),S=Object.keys($);f(null,S.map(function(m){return{caption:m,value:m,score:$[m],meta:"local"}}))}}),ace.define("ace/ext/language_tools",["require","exports","module","ace/snippets","ace/autocomplete","ace/config","ace/lib/lang","ace/autocomplete/util","ace/marker_group","ace/autocomplete/text_completer","ace/editor","ace/config"],function(n,p,A){var e=n("../snippets").snippetManager,a=n("../autocomplete").Autocomplete,h=n("../config"),g=n("../lib/lang"),l=n("../autocomplete/util"),s=n("../marker_group").MarkerGroup,i=n("../autocomplete/text_completer"),d={getCompletions:function(c,u,y,w,C){if(u.$mode.completer)return u.$mode.completer.getCompletions(c,u,y,w,C);var _=c.session.getState(y.row),T=u.$mode.getCompletions(_,u,y,w);T=T.map(function(L){return L.completerId=d.id,L}),C(null,T)},id:"keywordCompleter"},f=function(c){var u={};return c.replace(/\${(\d+)(:(.*?))?}/g,function(y,w,C,_){return u[w]=_||""}).replace(/\$(\d+?)/g,function(y,w){return u[w]})},$={getCompletions:function(c,u,y,w,C){var _=[],T=u.getTokenAt(y.row,y.column);T&&T.type.match(/(tag-name|tag-open|tag-whitespace|attribute-name|attribute-value)\.xml$/)?_.push("html-tag"):_=e.getActiveScopes(c);var L=e.snippetMap,F=[];_.forEach(function(I){for(var z=L[I]||[],j=z.length;j--;){var q=z[j],J=q.name||q.tabTrigger;J&&F.push({caption:J,snippet:q.content,meta:q.tabTrigger&&!q.name?q.tabTrigger+"⇥ ":"snippet",completerId:$.id})}},this),C(null,F)},getDocTooltip:function(c){c.snippet&&!c.docHTML&&(c.docHTML=["<b>",g.escapeHTML(c.caption),"</b>","<hr></hr>",g.escapeHTML(f(c.snippet))].join(""))},id:"snippetCompleter"},S=[$,i,d];p.setCompleters=function(c){S.length=0,c&&S.push.apply(S,c)},p.addCompleter=function(c){S.push(c)},p.textCompleter=i,p.keyWordCompleter=d,p.snippetCompleter=$;var m={name:"expandSnippet",exec:function(c){return e.expandWithTab(c)},bindKey:"Tab"},v=function(c,u){E(u.session.$mode)},E=function(c){typeof c=="string"&&(c=h.$modes[c]),c&&(e.files||(e.files={}),P(c.$id,c.snippetFileId),c.modes&&c.modes.forEach(E))},P=function(c,u){!u||!c||e.files[c]||(e.files[c]={},h.loadModule(u,function(y){y&&(e.files[c]=y,!y.snippets&&y.snippetText&&(y.snippets=e.parseSnippetFile(y.snippetText)),e.register(y.snippets||[],y.scope),y.includeScopes&&(e.snippetMap[y.scope].includeScopes=y.includeScopes,y.includeScopes.forEach(function(w){E("ace/mode/"+w)})))}))},R=function(c){var u=c.editor,y=u.completer&&u.completer.activated;if(c.command.name==="backspace")y&&!l.getCompletionPrefix(u)&&u.completer.detach();else if(c.command.name==="insertstring"&&!y){k=c;var w=c.editor.$liveAutocompletionDelay;w?t.delay(w):r(c)}},k,t=g.delayedCall(function(){r(k)},0),r=function(c){var u=c.editor,y=l.getCompletionPrefix(u),w=c.args,C=l.triggerAutocomplete(u,w);if(y&&y.length>=u.$liveAutocompletionThreshold||C){var _=a.for(u);_.autoShown=!0,_.showPopup(u)}},o=n("../editor").Editor;n("../config").defineOptions(o.prototype,"editor",{enableBasicAutocompletion:{set:function(c){c?(a.for(this),this.completers||(this.completers=Array.isArray(c)?c:S),this.commands.addCommand(a.startCommand)):this.commands.removeCommand(a.startCommand)},value:!1},enableLiveAutocompletion:{set:function(c){c?(this.completers||(this.completers=Array.isArray(c)?c:S),this.commands.on("afterExec",R)):this.commands.off("afterExec",R)},value:!1},liveAutocompletionDelay:{initialValue:0},liveAutocompletionThreshold:{initialValue:0},enableSnippets:{set:function(c){c?(this.commands.addCommand(m),this.on("changeMode",v),v(null,this)):(this.commands.removeCommand(m),this.off("changeMode",v))},value:!1}}),p.MarkerGroup=s}),function(){ace.require(["ace/ext/language_tools"],function(n){M&&(M.exports=n)})}()})(_r);var kr={exports:{}};(function(M,b){ace.define("ace/ext/searchbox-css",["require","exports","module"],function(n,p,A){A.exports=`

/* ------------------------------------------------------------------------------------------
 * Editor Search Form
 * --------------------------------------------------------------------------------------- */
.ace_search {
    background-color: #ddd;
    color: #666;
    border: 1px solid #cbcbcb;
    border-top: 0 none;
    overflow: hidden;
    margin: 0;
    padding: 4px 6px 0 4px;
    position: absolute;
    top: 0;
    z-index: 99;
    white-space: normal;
}
.ace_search.left {
    border-left: 0 none;
    border-radius: 0px 0px 5px 0px;
    left: 0;
}
.ace_search.right {
    border-radius: 0px 0px 0px 5px;
    border-right: 0 none;
    right: 0;
}

.ace_search_form, .ace_replace_form {
    margin: 0 20px 4px 0;
    overflow: hidden;
    line-height: 1.9;
}
.ace_replace_form {
    margin-right: 0;
}
.ace_search_form.ace_nomatch {
    outline: 1px solid red;
}

.ace_search_field {
    border-radius: 3px 0 0 3px;
    background-color: white;
    color: black;
    border: 1px solid #cbcbcb;
    border-right: 0 none;
    outline: 0;
    padding: 0;
    font-size: inherit;
    margin: 0;
    line-height: inherit;
    padding: 0 6px;
    min-width: 17em;
    vertical-align: top;
    min-height: 1.8em;
    box-sizing: content-box;
}
.ace_searchbtn {
    border: 1px solid #cbcbcb;
    line-height: inherit;
    display: inline-block;
    padding: 0 6px;
    background: #fff;
    border-right: 0 none;
    border-left: 1px solid #dcdcdc;
    cursor: pointer;
    margin: 0;
    position: relative;
    color: #666;
}
.ace_searchbtn:last-child {
    border-radius: 0 3px 3px 0;
    border-right: 1px solid #cbcbcb;
}
.ace_searchbtn:disabled {
    background: none;
    cursor: default;
}
.ace_searchbtn:hover {
    background-color: #eef1f6;
}
.ace_searchbtn.prev, .ace_searchbtn.next {
     padding: 0px 0.7em
}
.ace_searchbtn.prev:after, .ace_searchbtn.next:after {
     content: "";
     border: solid 2px #888;
     width: 0.5em;
     height: 0.5em;
     border-width:  2px 0 0 2px;
     display:inline-block;
     transform: rotate(-45deg);
}
.ace_searchbtn.next:after {
     border-width: 0 2px 2px 0 ;
}
.ace_searchbtn_close {
    background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAcCAYAAABRVo5BAAAAZ0lEQVR42u2SUQrAMAhDvazn8OjZBilCkYVVxiis8H4CT0VrAJb4WHT3C5xU2a2IQZXJjiQIRMdkEoJ5Q2yMqpfDIo+XY4k6h+YXOyKqTIj5REaxloNAd0xiKmAtsTHqW8sR2W5f7gCu5nWFUpVjZwAAAABJRU5ErkJggg==) no-repeat 50% 0;
    border-radius: 50%;
    border: 0 none;
    color: #656565;
    cursor: pointer;
    font: 16px/16px Arial;
    padding: 0;
    height: 14px;
    width: 14px;
    top: 9px;
    right: 7px;
    position: absolute;
}
.ace_searchbtn_close:hover {
    background-color: #656565;
    background-position: 50% 100%;
    color: white;
}

.ace_button {
    margin-left: 2px;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -o-user-select: none;
    -ms-user-select: none;
    user-select: none;
    overflow: hidden;
    opacity: 0.7;
    border: 1px solid rgba(100,100,100,0.23);
    padding: 1px;
    box-sizing:    border-box!important;
    color: black;
}

.ace_button:hover {
    background-color: #eee;
    opacity:1;
}
.ace_button:active {
    background-color: #ddd;
}

.ace_button.checked {
    border-color: #3399ff;
    opacity:1;
}

.ace_search_options{
    margin-bottom: 3px;
    text-align: right;
    -webkit-user-select: none;
    -moz-user-select: none;
    -o-user-select: none;
    -ms-user-select: none;
    user-select: none;
    clear: both;
}

.ace_search_counter {
    float: left;
    font-family: arial;
    padding: 0 8px;
}`}),ace.define("ace/ext/searchbox",["require","exports","module","ace/ext/searchbox","ace/ext/searchbox","ace/lib/dom","ace/lib/lang","ace/lib/event","ace/ext/searchbox-css","ace/keyboard/hash_handler","ace/lib/keys","ace/config"],function(n,p,A){var e=n("../lib/dom"),a=n("../lib/lang"),h=n("../lib/event"),g=n("./searchbox-css"),l=n("../keyboard/hash_handler").HashHandler,s=n("../lib/keys"),i=n("../config").nls,d=999;e.importCssString(g,"ace_searchbox",!1);var f=function(){function m(v,E,P){this.activeInput,this.element=e.buildDom(["div",{class:"ace_search right"},["span",{action:"hide",class:"ace_searchbtn_close"}],["div",{class:"ace_search_form"},["input",{class:"ace_search_field",placeholder:i("search-box.find.placeholder","Search for"),spellcheck:"false"}],["span",{action:"findPrev",class:"ace_searchbtn prev"},"​"],["span",{action:"findNext",class:"ace_searchbtn next"},"​"],["span",{action:"findAll",class:"ace_searchbtn",title:"Alt-Enter"},i("search-box.find-all.text","All")]],["div",{class:"ace_replace_form"},["input",{class:"ace_search_field",placeholder:i("search-box.replace.placeholder","Replace with"),spellcheck:"false"}],["span",{action:"replaceAndFindNext",class:"ace_searchbtn"},i("search-box.replace-next.text","Replace")],["span",{action:"replaceAll",class:"ace_searchbtn"},i("search-box.replace-all.text","All")]],["div",{class:"ace_search_options"},["span",{action:"toggleReplace",class:"ace_button",title:i("search-box.toggle-replace.title","Toggle Replace mode"),style:"float:left;margin-top:-2px;padding:0 5px;"},"+"],["span",{class:"ace_search_counter"}],["span",{action:"toggleRegexpMode",class:"ace_button",title:i("search-box.toggle-regexp.title","RegExp Search")},".*"],["span",{action:"toggleCaseSensitive",class:"ace_button",title:i("search-box.toggle-case.title","CaseSensitive Search")},"Aa"],["span",{action:"toggleWholeWords",class:"ace_button",title:i("search-box.toggle-whole-word.title","Whole Word Search")},"\\b"],["span",{action:"searchInSelection",class:"ace_button",title:i("search-box.toggle-in-selection.title","Search In Selection")},"S"]]]),this.setSession=this.setSession.bind(this),this.$onEditorInput=this.onEditorInput.bind(this),this.$init(),this.setEditor(v),e.importCssString(g,"ace_searchbox",v.container),h.addListener(this.element,"touchstart",function(R){R.stopPropagation()},v)}return m.prototype.setEditor=function(v){v.searchBox=this,v.renderer.scroller.appendChild(this.element),this.editor=v},m.prototype.setSession=function(v){this.searchRange=null,this.$syncOptions(!0)},m.prototype.onEditorInput=function(){this.find(!1,!1,!0)},m.prototype.$initElements=function(v){this.searchBox=v.querySelector(".ace_search_form"),this.replaceBox=v.querySelector(".ace_replace_form"),this.searchOption=v.querySelector("[action=searchInSelection]"),this.replaceOption=v.querySelector("[action=toggleReplace]"),this.regExpOption=v.querySelector("[action=toggleRegexpMode]"),this.caseSensitiveOption=v.querySelector("[action=toggleCaseSensitive]"),this.wholeWordOption=v.querySelector("[action=toggleWholeWords]"),this.searchInput=this.searchBox.querySelector(".ace_search_field"),this.replaceInput=this.replaceBox.querySelector(".ace_search_field"),this.searchCounter=v.querySelector(".ace_search_counter")},m.prototype.$init=function(){var v=this.element;this.$initElements(v);var E=this;h.addListener(v,"mousedown",function(P){setTimeout(function(){E.activeInput.focus()},0),h.stopPropagation(P)}),h.addListener(v,"click",function(P){var R=P.target||P.srcElement,k=R.getAttribute("action");k&&E[k]?E[k]():E.$searchBarKb.commands[k]&&E.$searchBarKb.commands[k].exec(E),h.stopPropagation(P)}),h.addCommandKeyListener(v,function(P,R,k){var t=s.keyCodeToString(k),r=E.$searchBarKb.findKeyCommand(R,t);r&&r.exec&&(r.exec(E),h.stopEvent(P))}),this.$onChange=a.delayedCall(function(){E.find(!1,!1)}),h.addListener(this.searchInput,"input",function(){E.$onChange.schedule(20)}),h.addListener(this.searchInput,"focus",function(){E.activeInput=E.searchInput,E.searchInput.value&&E.highlight()}),h.addListener(this.replaceInput,"focus",function(){E.activeInput=E.replaceInput,E.searchInput.value&&E.highlight()})},m.prototype.setSearchRange=function(v){this.searchRange=v,v?this.searchRangeMarker=this.editor.session.addMarker(v,"ace_active-line"):this.searchRangeMarker&&(this.editor.session.removeMarker(this.searchRangeMarker),this.searchRangeMarker=null)},m.prototype.$syncOptions=function(v){e.setCssClass(this.replaceOption,"checked",this.searchRange),e.setCssClass(this.searchOption,"checked",this.searchOption.checked),this.replaceOption.textContent=this.replaceOption.checked?"-":"+",e.setCssClass(this.regExpOption,"checked",this.regExpOption.checked),e.setCssClass(this.wholeWordOption,"checked",this.wholeWordOption.checked),e.setCssClass(this.caseSensitiveOption,"checked",this.caseSensitiveOption.checked);var E=this.editor.getReadOnly();this.replaceOption.style.display=E?"none":"",this.replaceBox.style.display=this.replaceOption.checked&&!E?"":"none",this.find(!1,!1,v)},m.prototype.highlight=function(v){this.editor.session.highlight(v||this.editor.$search.$options.re),this.editor.renderer.updateBackMarkers()},m.prototype.find=function(v,E,P){if(this.editor.session){var R=this.editor.find(this.searchInput.value,{skipCurrent:v,backwards:E,wrap:!0,regExp:this.regExpOption.checked,caseSensitive:this.caseSensitiveOption.checked,wholeWord:this.wholeWordOption.checked,preventScroll:P,range:this.searchRange}),k=!R&&this.searchInput.value;e.setCssClass(this.searchBox,"ace_nomatch",k),this.editor._emit("findSearchBox",{match:!k}),this.highlight(),this.updateCounter()}},m.prototype.updateCounter=function(){var v=this.editor,E=v.$search.$options.re,P=E.unicode,R=0,k=0;if(E){var t=this.searchRange?v.session.getTextRange(this.searchRange):v.getValue();v.$search.$isMultilineSearch(v.getLastSearchOptions())&&(t=t.replace(/\r\n|\r|\n/g,`
`),v.session.doc.$autoNewLine=`
`);var r=v.session.doc.positionToIndex(v.selection.anchor);this.searchRange&&(r-=v.session.doc.positionToIndex(this.searchRange.start));for(var o=E.lastIndex=0,c;(c=E.exec(t))&&(R++,o=c.index,o<=r&&k++,!(R>d||!c[0]&&(E.lastIndex=o+=a.skipEmptyMatch(t,o,P),o>=t.length))););}this.searchCounter.textContent=i("search-box.search-counter","$0 of $1",[k,R>d?d+"+":R])},m.prototype.findNext=function(){this.find(!0,!1)},m.prototype.findPrev=function(){this.find(!0,!0)},m.prototype.findAll=function(){var v=this.editor.findAll(this.searchInput.value,{regExp:this.regExpOption.checked,caseSensitive:this.caseSensitiveOption.checked,wholeWord:this.wholeWordOption.checked}),E=!v&&this.searchInput.value;e.setCssClass(this.searchBox,"ace_nomatch",E),this.editor._emit("findSearchBox",{match:!E}),this.highlight(),this.hide()},m.prototype.replace=function(){this.editor.getReadOnly()||this.editor.replace(this.replaceInput.value)},m.prototype.replaceAndFindNext=function(){this.editor.getReadOnly()||(this.editor.replace(this.replaceInput.value),this.findNext())},m.prototype.replaceAll=function(){this.editor.getReadOnly()||this.editor.replaceAll(this.replaceInput.value)},m.prototype.hide=function(){this.active=!1,this.setSearchRange(null),this.editor.off("changeSession",this.setSession),this.editor.off("input",this.$onEditorInput),this.element.style.display="none",this.editor.keyBinding.removeKeyboardHandler(this.$closeSearchBarKb),this.editor.focus()},m.prototype.show=function(v,E){this.active=!0,this.editor.on("changeSession",this.setSession),this.editor.on("input",this.$onEditorInput),this.element.style.display="",this.replaceOption.checked=E,this.editor.$search.$options.regExp&&(v=a.escapeRegExp(v)),v!=null&&(this.searchInput.value=v),this.searchInput.focus(),this.searchInput.select(),this.editor.keyBinding.addKeyboardHandler(this.$closeSearchBarKb),this.$syncOptions(!0)},m.prototype.isFocused=function(){var v=document.activeElement;return v==this.searchInput||v==this.replaceInput},m}(),$=new l;$.bindKeys({"Ctrl-f|Command-f":function(m){var v=m.isReplace=!m.isReplace;m.replaceBox.style.display=v?"":"none",m.replaceOption.checked=!1,m.$syncOptions(),m.searchInput.focus()},"Ctrl-H|Command-Option-F":function(m){m.editor.getReadOnly()||(m.replaceOption.checked=!0,m.$syncOptions(),m.replaceInput.focus())},"Ctrl-G|Command-G":function(m){m.findNext()},"Ctrl-Shift-G|Command-Shift-G":function(m){m.findPrev()},esc:function(m){setTimeout(function(){m.hide()})},Return:function(m){m.activeInput==m.replaceInput&&m.replace(),m.findNext()},"Shift-Return":function(m){m.activeInput==m.replaceInput&&m.replace(),m.findPrev()},"Alt-Return":function(m){m.activeInput==m.replaceInput&&m.replaceAll(),m.findAll()},Tab:function(m){(m.activeInput==m.replaceInput?m.searchInput:m.replaceInput).focus()}}),$.addCommands([{name:"toggleRegexpMode",bindKey:{win:"Alt-R|Alt-/",mac:"Ctrl-Alt-R|Ctrl-Alt-/"},exec:function(m){m.regExpOption.checked=!m.regExpOption.checked,m.$syncOptions()}},{name:"toggleCaseSensitive",bindKey:{win:"Alt-C|Alt-I",mac:"Ctrl-Alt-R|Ctrl-Alt-I"},exec:function(m){m.caseSensitiveOption.checked=!m.caseSensitiveOption.checked,m.$syncOptions()}},{name:"toggleWholeWords",bindKey:{win:"Alt-B|Alt-W",mac:"Ctrl-Alt-B|Ctrl-Alt-W"},exec:function(m){m.wholeWordOption.checked=!m.wholeWordOption.checked,m.$syncOptions()}},{name:"toggleReplace",exec:function(m){m.replaceOption.checked=!m.replaceOption.checked,m.$syncOptions()}},{name:"searchInSelection",exec:function(m){m.searchOption.checked=!m.searchRange,m.setSearchRange(m.searchOption.checked&&m.editor.getSelectionRange()),m.$syncOptions()}}]);var S=new l([{bindKey:"Esc",name:"closeSearchBar",exec:function(m){m.searchBox.hide()}}]);f.prototype.$searchBarKb=$,f.prototype.$closeSearchBarKb=S,p.SearchBox=f,p.Search=function(m,v){var E=m.searchBox||new f(m),P=m.session.selection.getRange(),R=P.isMultiLine()?"":m.session.getTextRange(P);E.show(R,v)}}),function(){ace.require(["ace/ext/searchbox"],function(n){M&&(M.exports=n)})}()})(kr);var Le={},gt={},Ye={exports:{}};Ye.exports;(function(M,b){var n=200,p="__lodash_hash_undefined__",A=1,e=2,a=9007199254740991,h="[object Arguments]",g="[object Array]",l="[object AsyncFunction]",s="[object Boolean]",i="[object Date]",d="[object Error]",f="[object Function]",$="[object GeneratorFunction]",S="[object Map]",m="[object Number]",v="[object Null]",E="[object Object]",P="[object Promise]",R="[object Proxy]",k="[object RegExp]",t="[object Set]",r="[object String]",o="[object Symbol]",c="[object Undefined]",u="[object WeakMap]",y="[object ArrayBuffer]",w="[object DataView]",C="[object Float32Array]",_="[object Float64Array]",T="[object Int8Array]",L="[object Int16Array]",F="[object Int32Array]",I="[object Uint8Array]",z="[object Uint8ClampedArray]",j="[object Uint16Array]",q="[object Uint32Array]",J=/[\\^$.*+?()[\]{}|]/g,ne=/^\[object .+?Constructor\]$/,Z=/^(?:0|[1-9]\d*)$/,G={};G[C]=G[_]=G[T]=G[L]=G[F]=G[I]=G[z]=G[j]=G[q]=!0,G[h]=G[g]=G[y]=G[s]=G[w]=G[i]=G[d]=G[f]=G[S]=G[m]=G[E]=G[k]=G[t]=G[r]=G[u]=!1;var D=typeof re=="object"&&re&&re.Object===Object&&re,X=typeof self=="object"&&self&&self.Object===Object&&self,K=D||X||Function("return this")(),W=b&&!b.nodeType&&b,ce=W&&!0&&M&&!M.nodeType&&M,le=ce&&ce.exports===W,we=le&&D.process,Me=function(){try{return we&&we.binding&&we.binding("util")}catch(x){}}(),xt=Me&&Me.isTypedArray;function on(x,O){for(var N=-1,B=x==null?0:x.length,Q=0,V=[];++N<B;){var te=x[N];O(te,N,x)&&(V[Q++]=te)}return V}function sn(x,O){for(var N=-1,B=O.length,Q=x.length;++N<B;)x[Q+N]=O[N];return x}function an(x,O){for(var N=-1,B=x==null?0:x.length;++N<B;)if(O(x[N],N,x))return!0;return!1}function cn(x,O){for(var N=-1,B=Array(x);++N<x;)B[N]=O(N);return B}function ln(x){return function(O){return x(O)}}function pn(x,O){return x.has(O)}function hn(x,O){return x==null?void 0:x[O]}function un(x){var O=-1,N=Array(x.size);return x.forEach(function(B,Q){N[++O]=[Q,B]}),N}function dn(x,O){return function(N){return x(O(N))}}function gn(x){var O=-1,N=Array(x.size);return x.forEach(function(B){N[++O]=B}),N}var fn=Array.prototype,mn=Function.prototype,Be=Object.prototype,et=K["__core-js_shared__"],wt=mn.toString,ge=Be.hasOwnProperty,_t=function(){var x=/[^.]+$/.exec(et&&et.keys&&et.keys.IE_PROTO||"");return x?"Symbol(src)_1."+x:""}(),kt=Be.toString,bn=RegExp("^"+wt.call(ge).replace(J,"\\$&").replace(/hasOwnProperty|(function).*?(?=\\\()| for .+?(?=\\\])/g,"$1.*?")+"$"),$t=le?K.Buffer:void 0,je=K.Symbol,St=K.Uint8Array,Ct=Be.propertyIsEnumerable,vn=fn.splice,_e=je?je.toStringTag:void 0,Tt=Object.getOwnPropertySymbols,yn=$t?$t.isBuffer:void 0,xn=dn(Object.keys,Object),tt=Re(K,"DataView"),Pe=Re(K,"Map"),nt=Re(K,"Promise"),rt=Re(K,"Set"),it=Re(K,"WeakMap"),Ne=Re(Object,"create"),wn=Se(tt),_n=Se(Pe),kn=Se(nt),$n=Se(rt),Sn=Se(it),Et=je?je.prototype:void 0,ot=Et?Et.valueOf:void 0;function ke(x){var O=-1,N=x==null?0:x.length;for(this.clear();++O<N;){var B=x[O];this.set(B[0],B[1])}}function Cn(){this.__data__=Ne?Ne(null):{},this.size=0}function Tn(x){var O=this.has(x)&&delete this.__data__[x];return this.size-=O?1:0,O}function En(x){var O=this.__data__;if(Ne){var N=O[x];return N===p?void 0:N}return ge.call(O,x)?O[x]:void 0}function An(x){var O=this.__data__;return Ne?O[x]!==void 0:ge.call(O,x)}function Mn(x,O){var N=this.__data__;return this.size+=this.has(x)?0:1,N[x]=Ne&&O===void 0?p:O,this}ke.prototype.clear=Cn,ke.prototype.delete=Tn,ke.prototype.get=En,ke.prototype.has=An,ke.prototype.set=Mn;function fe(x){var O=-1,N=x==null?0:x.length;for(this.clear();++O<N;){var B=x[O];this.set(B[0],B[1])}}function Rn(){this.__data__=[],this.size=0}function On(x){var O=this.__data__,N=Ue(O,x);if(N<0)return!1;var B=O.length-1;return N==B?O.pop():vn.call(O,N,1),--this.size,!0}function Ln(x){var O=this.__data__,N=Ue(O,x);return N<0?void 0:O[N][1]}function In(x){return Ue(this.__data__,x)>-1}function Pn(x,O){var N=this.__data__,B=Ue(N,x);return B<0?(++this.size,N.push([x,O])):N[B][1]=O,this}fe.prototype.clear=Rn,fe.prototype.delete=On,fe.prototype.get=Ln,fe.prototype.has=In,fe.prototype.set=Pn;function $e(x){var O=-1,N=x==null?0:x.length;for(this.clear();++O<N;){var B=x[O];this.set(B[0],B[1])}}function Nn(){this.size=0,this.__data__={hash:new ke,map:new(Pe||fe),string:new ke}}function Fn(x){var O=qe(this,x).delete(x);return this.size-=O?1:0,O}function zn(x){return qe(this,x).get(x)}function Dn(x){return qe(this,x).has(x)}function Bn(x,O){var N=qe(this,x),B=N.size;return N.set(x,O),this.size+=N.size==B?0:1,this}$e.prototype.clear=Nn,$e.prototype.delete=Fn,$e.prototype.get=zn,$e.prototype.has=Dn,$e.prototype.set=Bn;function He(x){var O=-1,N=x==null?0:x.length;for(this.__data__=new $e;++O<N;)this.add(x[O])}function jn(x){return this.__data__.set(x,p),this}function Hn(x){return this.__data__.has(x)}He.prototype.add=He.prototype.push=jn,He.prototype.has=Hn;function ve(x){var O=this.__data__=new fe(x);this.size=O.size}function Un(){this.__data__=new fe,this.size=0}function qn(x){var O=this.__data__,N=O.delete(x);return this.size=O.size,N}function Wn(x){return this.__data__.get(x)}function Vn(x){return this.__data__.has(x)}function Gn(x,O){var N=this.__data__;if(N instanceof fe){var B=N.__data__;if(!Pe||B.length<n-1)return B.push([x,O]),this.size=++N.size,this;N=this.__data__=new $e(B)}return N.set(x,O),this.size=N.size,this}ve.prototype.clear=Un,ve.prototype.delete=qn,ve.prototype.get=Wn,ve.prototype.has=Vn,ve.prototype.set=Gn;function Kn(x,O){var N=We(x),B=!N&&lr(x),Q=!N&&!B&&st(x),V=!N&&!B&&!Q&&Ft(x),te=N||B||Q||V,ie=te?cn(x.length,String):[],oe=ie.length;for(var ee in x)(O||ge.call(x,ee))&&!(te&&(ee=="length"||Q&&(ee=="offset"||ee=="parent")||V&&(ee=="buffer"||ee=="byteLength"||ee=="byteOffset")||ir(ee,oe)))&&ie.push(ee);return ie}function Ue(x,O){for(var N=x.length;N--;)if(Lt(x[N][0],O))return N;return-1}function Xn(x,O,N){var B=O(x);return We(x)?B:sn(B,N(x))}function Fe(x){return x==null?x===void 0?c:v:_e&&_e in Object(x)?nr(x):cr(x)}function At(x){return ze(x)&&Fe(x)==h}function Mt(x,O,N,B,Q){return x===O?!0:x==null||O==null||!ze(x)&&!ze(O)?x!==x&&O!==O:Yn(x,O,N,B,Mt,Q)}function Yn(x,O,N,B,Q,V){var te=We(x),ie=We(O),oe=te?g:ye(x),ee=ie?g:ye(O);oe=oe==h?E:oe,ee=ee==h?E:ee;var ae=oe==E,ue=ee==E,se=oe==ee;if(se&&st(x)){if(!st(O))return!1;te=!0,ae=!1}if(se&&!ae)return V||(V=new ve),te||Ft(x)?Rt(x,O,N,B,Q,V):er(x,O,oe,N,B,Q,V);if(!(N&A)){var pe=ae&&ge.call(x,"__wrapped__"),he=ue&&ge.call(O,"__wrapped__");if(pe||he){var xe=pe?x.value():x,me=he?O.value():O;return V||(V=new ve),Q(xe,me,N,B,V)}}return se?(V||(V=new ve),tr(x,O,N,B,Q,V)):!1}function Jn(x){if(!Nt(x)||sr(x))return!1;var O=It(x)?bn:ne;return O.test(Se(x))}function Zn(x){return ze(x)&&Pt(x.length)&&!!G[Fe(x)]}function Qn(x){if(!ar(x))return xn(x);var O=[];for(var N in Object(x))ge.call(x,N)&&N!="constructor"&&O.push(N);return O}function Rt(x,O,N,B,Q,V){var te=N&A,ie=x.length,oe=O.length;if(ie!=oe&&!(te&&oe>ie))return!1;var ee=V.get(x);if(ee&&V.get(O))return ee==O;var ae=-1,ue=!0,se=N&e?new He:void 0;for(V.set(x,O),V.set(O,x);++ae<ie;){var pe=x[ae],he=O[ae];if(B)var xe=te?B(he,pe,ae,O,x,V):B(pe,he,ae,x,O,V);if(xe!==void 0){if(xe)continue;ue=!1;break}if(se){if(!an(O,function(me,Ce){if(!pn(se,Ce)&&(pe===me||Q(pe,me,N,B,V)))return se.push(Ce)})){ue=!1;break}}else if(!(pe===he||Q(pe,he,N,B,V))){ue=!1;break}}return V.delete(x),V.delete(O),ue}function er(x,O,N,B,Q,V,te){switch(N){case w:if(x.byteLength!=O.byteLength||x.byteOffset!=O.byteOffset)return!1;x=x.buffer,O=O.buffer;case y:return!(x.byteLength!=O.byteLength||!V(new St(x),new St(O)));case s:case i:case m:return Lt(+x,+O);case d:return x.name==O.name&&x.message==O.message;case k:case r:return x==O+"";case S:var ie=un;case t:var oe=B&A;if(ie||(ie=gn),x.size!=O.size&&!oe)return!1;var ee=te.get(x);if(ee)return ee==O;B|=e,te.set(x,O);var ae=Rt(ie(x),ie(O),B,Q,V,te);return te.delete(x),ae;case o:if(ot)return ot.call(x)==ot.call(O)}return!1}function tr(x,O,N,B,Q,V){var te=N&A,ie=Ot(x),oe=ie.length,ee=Ot(O),ae=ee.length;if(oe!=ae&&!te)return!1;for(var ue=oe;ue--;){var se=ie[ue];if(!(te?se in O:ge.call(O,se)))return!1}var pe=V.get(x);if(pe&&V.get(O))return pe==O;var he=!0;V.set(x,O),V.set(O,x);for(var xe=te;++ue<oe;){se=ie[ue];var me=x[se],Ce=O[se];if(B)var zt=te?B(Ce,me,se,O,x,V):B(me,Ce,se,x,O,V);if(!(zt===void 0?me===Ce||Q(me,Ce,N,B,V):zt)){he=!1;break}xe||(xe=se=="constructor")}if(he&&!xe){var Ve=x.constructor,Ge=O.constructor;Ve!=Ge&&"constructor"in x&&"constructor"in O&&!(typeof Ve=="function"&&Ve instanceof Ve&&typeof Ge=="function"&&Ge instanceof Ge)&&(he=!1)}return V.delete(x),V.delete(O),he}function Ot(x){return Xn(x,ur,rr)}function qe(x,O){var N=x.__data__;return or(O)?N[typeof O=="string"?"string":"hash"]:N.map}function Re(x,O){var N=hn(x,O);return Jn(N)?N:void 0}function nr(x){var O=ge.call(x,_e),N=x[_e];try{x[_e]=void 0;var B=!0}catch(V){}var Q=kt.call(x);return B&&(O?x[_e]=N:delete x[_e]),Q}var rr=Tt?function(x){return x==null?[]:(x=Object(x),on(Tt(x),function(O){return Ct.call(x,O)}))}:dr,ye=Fe;(tt&&ye(new tt(new ArrayBuffer(1)))!=w||Pe&&ye(new Pe)!=S||nt&&ye(nt.resolve())!=P||rt&&ye(new rt)!=t||it&&ye(new it)!=u)&&(ye=function(x){var O=Fe(x),N=O==E?x.constructor:void 0,B=N?Se(N):"";if(B)switch(B){case wn:return w;case _n:return S;case kn:return P;case $n:return t;case Sn:return u}return O});function ir(x,O){return O=O==null?a:O,!!O&&(typeof x=="number"||Z.test(x))&&x>-1&&x%1==0&&x<O}function or(x){var O=typeof x;return O=="string"||O=="number"||O=="symbol"||O=="boolean"?x!=="__proto__":x===null}function sr(x){return!!_t&&_t in x}function ar(x){var O=x&&x.constructor,N=typeof O=="function"&&O.prototype||Be;return x===N}function cr(x){return kt.call(x)}function Se(x){if(x!=null){try{return wt.call(x)}catch(O){}try{return x+""}catch(O){}}return""}function Lt(x,O){return x===O||x!==x&&O!==O}var lr=At(function(){return arguments}())?At:function(x){return ze(x)&&ge.call(x,"callee")&&!Ct.call(x,"callee")},We=Array.isArray;function pr(x){return x!=null&&Pt(x.length)&&!It(x)}var st=yn||gr;function hr(x,O){return Mt(x,O)}function It(x){if(!Nt(x))return!1;var O=Fe(x);return O==f||O==$||O==l||O==R}function Pt(x){return typeof x=="number"&&x>-1&&x%1==0&&x<=a}function Nt(x){var O=typeof x;return x!=null&&(O=="object"||O=="function")}function ze(x){return x!=null&&typeof x=="object"}var Ft=xt?ln(xt):Zn;function ur(x){return pr(x)?Kn(x):Qn(x)}function dr(){return[]}function gr(){return!1}M.exports=hr})(Ye,Ye.exports);var Gt=Ye.exports,de={};Object.defineProperty(de,"__esModule",{value:!0});de.getAceInstance=de.debounce=de.editorEvents=de.editorOptions=void 0;var $r=["minLines","maxLines","readOnly","highlightActiveLine","tabSize","enableBasicAutocompletion","enableLiveAutocompletion","enableSnippets"];de.editorOptions=$r;var Sr=["onChange","onFocus","onInput","onBlur","onCopy","onPaste","onSelectionChange","onCursorChange","onScroll","handleOptions","updateRef"];de.editorEvents=Sr;var Cr=function(){var M;return typeof window=="undefined"?(re.window={},M=Xe,delete re.window):window.ace?(M=window.ace,M.acequire=window.ace.require||window.ace.acequire):M=Xe,M};de.getAceInstance=Cr;var Tr=function(M,b){var n=null;return function(){var p=this,A=arguments;clearTimeout(n),n=setTimeout(function(){M.apply(p,A)},b)}};de.debounce=Tr;var Er=re&&re.__extends||function(){var M=function(b,n){return M=Object.setPrototypeOf||{__proto__:[]}instanceof Array&&function(p,A){p.__proto__=A}||function(p,A){for(var e in A)Object.prototype.hasOwnProperty.call(A,e)&&(p[e]=A[e])},M(b,n)};return function(b,n){if(typeof n!="function"&&n!==null)throw new TypeError("Class extends value "+String(n)+" is not a constructor or null");M(b,n);function p(){this.constructor=b}b.prototype=n===null?Object.create(n):(p.prototype=n.prototype,new p)}}(),pt=re&&re.__assign||function(){return pt=Object.assign||function(M){for(var b,n=1,p=arguments.length;n<p;n++){b=arguments[n];for(var A in b)Object.prototype.hasOwnProperty.call(b,A)&&(M[A]=b[A])}return M},pt.apply(this,arguments)};Object.defineProperty(gt,"__esModule",{value:!0});var Ar=Xe,H=ut,Dt=dt,Ke=Gt,Oe=de,Bt=(0,Oe.getAceInstance)(),Mr=function(M){Er(b,M);function b(n){var p=M.call(this,n)||this;return Oe.editorEvents.forEach(function(A){p[A]=p[A].bind(p)}),p.debounce=Oe.debounce,p}return b.prototype.isInShadow=function(n){for(var p=n&&n.parentNode;p;){if(p.toString()==="[object ShadowRoot]")return!0;p=p.parentNode}return!1},b.prototype.componentDidMount=function(){var n=this,p=this.props,A=p.className,e=p.onBeforeLoad,a=p.onValidate,h=p.mode,g=p.focus,l=p.theme,s=p.fontSize,i=p.value,d=p.defaultValue,f=p.showGutter,$=p.wrapEnabled,S=p.showPrintMargin,m=p.scrollMargin,v=m===void 0?[0,0,0,0]:m,E=p.keyboardHandler,P=p.onLoad,R=p.commands,k=p.annotations,t=p.markers,r=p.placeholder;this.editor=Bt.edit(this.refEditor),e&&e(Bt);for(var o=Object.keys(this.props.editorProps),c=0;c<o.length;c++)this.editor[o[c]]=this.props.editorProps[o[c]];this.props.debounceChangePeriod&&(this.onChange=this.debounce(this.onChange,this.props.debounceChangePeriod)),this.editor.renderer.setScrollMargin(v[0],v[1],v[2],v[3]),this.isInShadow(this.refEditor)&&this.editor.renderer.attachToShadowRoot(),this.editor.getSession().setMode(typeof h=="string"?"ace/mode/".concat(h):h),l&&l!==""&&this.editor.setTheme("ace/theme/".concat(l)),this.editor.setFontSize(typeof s=="number"?"".concat(s,"px"):s),this.editor.getSession().setValue(d||i||""),this.props.navigateToFileEnd&&this.editor.navigateFileEnd(),this.editor.renderer.setShowGutter(f),this.editor.getSession().setUseWrapMode($),this.editor.setShowPrintMargin(S),this.editor.on("focus",this.onFocus),this.editor.on("blur",this.onBlur),this.editor.on("copy",this.onCopy),this.editor.on("paste",this.onPaste),this.editor.on("change",this.onChange),this.editor.on("input",this.onInput),r&&this.updatePlaceholder(),this.editor.getSession().selection.on("changeSelection",this.onSelectionChange),this.editor.getSession().selection.on("changeCursor",this.onCursorChange),a&&this.editor.getSession().on("changeAnnotation",function(){var y=n.editor.getSession().getAnnotations();n.props.onValidate(y)}),this.editor.session.on("changeScrollTop",this.onScroll),this.editor.getSession().setAnnotations(k||[]),t&&t.length>0&&this.handleMarkers(t);var u=this.editor.$options;Oe.editorOptions.forEach(function(y){u.hasOwnProperty(y)?n.editor.setOption(y,n.props[y]):n.props[y]}),this.handleOptions(this.props),Array.isArray(R)&&R.forEach(function(y){typeof y.exec=="string"?n.editor.commands.bindKey(y.bindKey,y.exec):n.editor.commands.addCommand(y)}),E&&this.editor.setKeyboardHandler("ace/keyboard/"+E),A&&(this.refEditor.className+=" "+A),P&&P(this.editor),this.editor.resize(),g&&this.editor.focus()},b.prototype.componentDidUpdate=function(n){for(var p=n,A=this.props,e=0;e<Oe.editorOptions.length;e++){var a=Oe.editorOptions[e];A[a]!==p[a]&&this.editor.setOption(a,A[a])}if(A.className!==p.className){var h=this.refEditor.className,g=h.trim().split(" "),l=p.className.trim().split(" ");l.forEach(function(d){var f=g.indexOf(d);g.splice(f,1)}),this.refEditor.className=" "+A.className+" "+g.join(" ")}var s=this.editor&&A.value!=null&&this.editor.getValue()!==A.value;if(s){this.silent=!0;var i=this.editor.session.selection.toJSON();this.editor.setValue(A.value,A.cursorStart),this.editor.session.selection.fromJSON(i),this.silent=!1}A.placeholder!==p.placeholder&&this.updatePlaceholder(),A.mode!==p.mode&&this.editor.getSession().setMode(typeof A.mode=="string"?"ace/mode/".concat(A.mode):A.mode),A.theme!==p.theme&&this.editor.setTheme("ace/theme/"+A.theme),A.keyboardHandler!==p.keyboardHandler&&(A.keyboardHandler?this.editor.setKeyboardHandler("ace/keyboard/"+A.keyboardHandler):this.editor.setKeyboardHandler(null)),A.fontSize!==p.fontSize&&this.editor.setFontSize(typeof A.fontSize=="number"?"".concat(A.fontSize,"px"):A.fontSize),A.wrapEnabled!==p.wrapEnabled&&this.editor.getSession().setUseWrapMode(A.wrapEnabled),A.showPrintMargin!==p.showPrintMargin&&this.editor.setShowPrintMargin(A.showPrintMargin),A.showGutter!==p.showGutter&&this.editor.renderer.setShowGutter(A.showGutter),Ke(A.setOptions,p.setOptions)||this.handleOptions(A),(s||!Ke(A.annotations,p.annotations))&&this.editor.getSession().setAnnotations(A.annotations||[]),!Ke(A.markers,p.markers)&&Array.isArray(A.markers)&&this.handleMarkers(A.markers),Ke(A.scrollMargin,p.scrollMargin)||this.handleScrollMargins(A.scrollMargin),(n.height!==this.props.height||n.width!==this.props.width)&&this.editor.resize(),this.props.focus&&!n.focus&&this.editor.focus()},b.prototype.handleScrollMargins=function(n){n===void 0&&(n=[0,0,0,0]),this.editor.renderer.setScrollMargin(n[0],n[1],n[2],n[3])},b.prototype.componentWillUnmount=function(){this.editor&&(this.editor.destroy(),this.editor=null)},b.prototype.onChange=function(n){if(this.props.onChange&&!this.silent){var p=this.editor.getValue();this.props.onChange(p,n)}},b.prototype.onSelectionChange=function(n){if(this.props.onSelectionChange){var p=this.editor.getSelection();this.props.onSelectionChange(p,n)}},b.prototype.onCursorChange=function(n){if(this.props.onCursorChange){var p=this.editor.getSelection();this.props.onCursorChange(p,n)}},b.prototype.onInput=function(n){this.props.onInput&&this.props.onInput(n),this.props.placeholder&&this.updatePlaceholder()},b.prototype.onFocus=function(n){this.props.onFocus&&this.props.onFocus(n,this.editor)},b.prototype.onBlur=function(n){this.props.onBlur&&this.props.onBlur(n,this.editor)},b.prototype.onCopy=function(n){var p=n.text;this.props.onCopy&&this.props.onCopy(p)},b.prototype.onPaste=function(n){var p=n.text;this.props.onPaste&&this.props.onPaste(p)},b.prototype.onScroll=function(){this.props.onScroll&&this.props.onScroll(this.editor)},b.prototype.handleOptions=function(n){for(var p=Object.keys(n.setOptions),A=0;A<p.length;A++)this.editor.setOption(p[A],n.setOptions[p[A]])},b.prototype.handleMarkers=function(n){var p=this,A=this.editor.getSession().getMarkers(!0);for(var e in A)A.hasOwnProperty(e)&&this.editor.getSession().removeMarker(A[e].id);A=this.editor.getSession().getMarkers(!1);for(var e in A)A.hasOwnProperty(e)&&A[e].clazz!=="ace_active-line"&&A[e].clazz!=="ace_selected-word"&&this.editor.getSession().removeMarker(A[e].id);n.forEach(function(a){var h=a.startRow,g=a.startCol,l=a.endRow,s=a.endCol,i=a.className,d=a.type,f=a.inFront,$=f===void 0?!1:f,S=new Ar.Range(h,g,l,s);p.editor.getSession().addMarker(S,i,d,$)})},b.prototype.updatePlaceholder=function(){var n=this.editor,p=this.props.placeholder,A=!n.session.getValue().length,e=n.renderer.placeholderNode;!A&&e?(n.renderer.scroller.removeChild(n.renderer.placeholderNode),n.renderer.placeholderNode=null):A&&!e?(e=n.renderer.placeholderNode=document.createElement("div"),e.textContent=p||"",e.className="ace_comment ace_placeholder",e.style.padding="0 9px",e.style.position="absolute",e.style.zIndex="3",n.renderer.scroller.appendChild(e)):A&&e&&(e.textContent=p)},b.prototype.updateRef=function(n){this.refEditor=n},b.prototype.render=function(){var n=this.props,p=n.name,A=n.width,e=n.height,a=n.style,h=pt({width:A,height:e},a);return Dt.createElement("div",{ref:this.updateRef,id:p,style:h})},b.propTypes={mode:H.oneOfType([H.string,H.object]),focus:H.bool,theme:H.string,name:H.string,className:H.string,height:H.string,width:H.string,fontSize:H.oneOfType([H.number,H.string]),showGutter:H.bool,onChange:H.func,onCopy:H.func,onPaste:H.func,onFocus:H.func,onInput:H.func,onBlur:H.func,onScroll:H.func,value:H.string,defaultValue:H.string,onLoad:H.func,onSelectionChange:H.func,onCursorChange:H.func,onBeforeLoad:H.func,onValidate:H.func,minLines:H.number,maxLines:H.number,readOnly:H.bool,highlightActiveLine:H.bool,tabSize:H.number,showPrintMargin:H.bool,cursorStart:H.number,debounceChangePeriod:H.number,editorProps:H.object,setOptions:H.object,style:H.object,scrollMargin:H.array,annotations:H.array,markers:H.array,keyboardHandler:H.string,wrapEnabled:H.bool,enableSnippets:H.bool,enableBasicAutocompletion:H.oneOfType([H.bool,H.array]),enableLiveAutocompletion:H.oneOfType([H.bool,H.array]),navigateToFileEnd:H.bool,commands:H.array,placeholder:H.string},b.defaultProps={name:"ace-editor",focus:!1,mode:"",theme:"",height:"500px",width:"500px",fontSize:12,enableSnippets:!1,showGutter:!0,onChange:null,onPaste:null,onLoad:null,onScroll:null,minLines:null,maxLines:null,readOnly:!1,highlightActiveLine:!0,showPrintMargin:!0,tabSize:4,cursorStart:1,editorProps:{},style:{},scrollMargin:[0,0,0,0],setOptions:{},wrapEnabled:!1,enableBasicAutocompletion:!1,enableLiveAutocompletion:!1,placeholder:null,navigateToFileEnd:!0},b}(Dt.Component);gt.default=Mr;var ft={},Je={},Kt={exports:{}};(function(M,b){ace.define("ace/split",["require","exports","module","ace/lib/oop","ace/lib/lang","ace/lib/event_emitter","ace/editor","ace/virtual_renderer","ace/edit_session"],function(n,p,A){var e=n("./lib/oop");n("./lib/lang");var a=n("./lib/event_emitter").EventEmitter,h=n("./editor").Editor,g=n("./virtual_renderer").VirtualRenderer,l=n("./edit_session").EditSession,s;s=function(i,d,f){this.BELOW=1,this.BESIDE=0,this.$container=i,this.$theme=d,this.$splits=0,this.$editorCSS="",this.$editors=[],this.$orientation=this.BESIDE,this.setSplits(f||1),this.$cEditor=this.$editors[0],this.on("focus",function($){this.$cEditor=$}.bind(this))},function(){e.implement(this,a),this.$createEditor=function(){var i=document.createElement("div");i.className=this.$editorCSS,i.style.cssText="position: absolute; top:0px; bottom:0px",this.$container.appendChild(i);var d=new h(new g(i,this.$theme));return d.on("focus",function(){this._emit("focus",d)}.bind(this)),this.$editors.push(d),d.setFontSize(this.$fontSize),d},this.setSplits=function(i){var d;if(i<1)throw"The number of splits have to be > 0!";if(i!=this.$splits){if(i>this.$splits){for(;this.$splits<this.$editors.length&&this.$splits<i;)d=this.$editors[this.$splits],this.$container.appendChild(d.container),d.setFontSize(this.$fontSize),this.$splits++;for(;this.$splits<i;)this.$createEditor(),this.$splits++}else for(;this.$splits>i;)d=this.$editors[this.$splits-1],this.$container.removeChild(d.container),this.$splits--;this.resize()}},this.getSplits=function(){return this.$splits},this.getEditor=function(i){return this.$editors[i]},this.getCurrentEditor=function(){return this.$cEditor},this.focus=function(){this.$cEditor.focus()},this.blur=function(){this.$cEditor.blur()},this.setTheme=function(i){this.$editors.forEach(function(d){d.setTheme(i)})},this.setKeyboardHandler=function(i){this.$editors.forEach(function(d){d.setKeyboardHandler(i)})},this.forEach=function(i,d){this.$editors.forEach(i,d)},this.$fontSize="",this.setFontSize=function(i){this.$fontSize=i,this.forEach(function(d){d.setFontSize(i)})},this.$cloneSession=function(i){var d=new l(i.getDocument(),i.getMode()),f=i.getUndoManager();return d.setUndoManager(f),d.setTabSize(i.getTabSize()),d.setUseSoftTabs(i.getUseSoftTabs()),d.setOverwrite(i.getOverwrite()),d.setBreakpoints(i.getBreakpoints()),d.setUseWrapMode(i.getUseWrapMode()),d.setUseWorker(i.getUseWorker()),d.setWrapLimitRange(i.$wrapLimitRange.min,i.$wrapLimitRange.max),d.$foldData=i.$cloneFoldData(),d},this.setSession=function(i,d){var f;d==null?f=this.$cEditor:f=this.$editors[d];var $=this.$editors.some(function(S){return S.session===i});return $&&(i=this.$cloneSession(i)),f.setSession(i),i},this.getOrientation=function(){return this.$orientation},this.setOrientation=function(i){this.$orientation!=i&&(this.$orientation=i,this.resize())},this.resize=function(){var i=this.$container.clientWidth,d=this.$container.clientHeight,f;if(this.$orientation==this.BESIDE)for(var $=i/this.$splits,S=0;S<this.$splits;S++)f=this.$editors[S],f.container.style.width=$+"px",f.container.style.top="0px",f.container.style.left=S*$+"px",f.container.style.height=d+"px",f.resize();else for(var m=d/this.$splits,S=0;S<this.$splits;S++)f=this.$editors[S],f.container.style.width=i+"px",f.container.style.top=S*m+"px",f.container.style.left="0px",f.container.style.height=m+"px",f.resize()}}.call(s.prototype),p.Split=s}),ace.define("ace/ext/split",["require","exports","module","ace/ext/split","ace/split"],function(n,p,A){A.exports=n("../split")}),function(){ace.require(["ace/ext/split"],function(n){M&&(M.exports=n)})}()})(Kt);var Rr=Kt.exports,Or="Expected a function",Xt="__lodash_hash_undefined__",Yt=1/0,Lr="[object Function]",Ir="[object GeneratorFunction]",Pr="[object Symbol]",Nr=/\.|\[(?:[^[\]]*|(["'])(?:(?!\1)[^\\]|\\.)*?\1)\]/,Fr=/^\w*$/,zr=/^\./,Dr=/[^.[\]]+|\[(?:(-?\d+(?:\.\d+)?)|(["'])((?:(?!\2)[^\\]|\\.)*?)\2)\]|(?=(?:\.|\[\])(?:\.|\[\]|$))/g,Br=/[\\^$.*+?()[\]{}|]/g,jr=/\\(\\)?/g,Hr=/^\[object .+?Constructor\]$/,Ur=typeof re=="object"&&re&&re.Object===Object&&re,qr=typeof self=="object"&&self&&self.Object===Object&&self,mt=Ur||qr||Function("return this")();function Wr(M,b){return M==null?void 0:M[b]}function Vr(M){var b=!1;if(M!=null&&typeof M.toString!="function")try{b=!!(M+"")}catch(n){}return b}var Gr=Array.prototype,Kr=Function.prototype,Jt=Object.prototype,at=mt["__core-js_shared__"],jt=function(){var M=/[^.]+$/.exec(at&&at.keys&&at.keys.IE_PROTO||"");return M?"Symbol(src)_1."+M:""}(),Zt=Kr.toString,bt=Jt.hasOwnProperty,Qt=Jt.toString,Xr=RegExp("^"+Zt.call(bt).replace(Br,"\\$&").replace(/hasOwnProperty|(function).*?(?=\\\()| for .+?(?=\\\])/g,"$1.*?")+"$"),Ht=mt.Symbol,Yr=Gr.splice,Jr=en(mt,"Map"),De=en(Object,"create"),Ut=Ht?Ht.prototype:void 0,qt=Ut?Ut.toString:void 0;function Ee(M){var b=-1,n=M?M.length:0;for(this.clear();++b<n;){var p=M[b];this.set(p[0],p[1])}}function Zr(){this.__data__=De?De(null):{}}function Qr(M){return this.has(M)&&delete this.__data__[M]}function ei(M){var b=this.__data__;if(De){var n=b[M];return n===Xt?void 0:n}return bt.call(b,M)?b[M]:void 0}function ti(M){var b=this.__data__;return De?b[M]!==void 0:bt.call(b,M)}function ni(M,b){var n=this.__data__;return n[M]=De&&b===void 0?Xt:b,this}Ee.prototype.clear=Zr;Ee.prototype.delete=Qr;Ee.prototype.get=ei;Ee.prototype.has=ti;Ee.prototype.set=ni;function Ie(M){var b=-1,n=M?M.length:0;for(this.clear();++b<n;){var p=M[b];this.set(p[0],p[1])}}function ri(){this.__data__=[]}function ii(M){var b=this.__data__,n=Ze(b,M);if(n<0)return!1;var p=b.length-1;return n==p?b.pop():Yr.call(b,n,1),!0}function oi(M){var b=this.__data__,n=Ze(b,M);return n<0?void 0:b[n][1]}function si(M){return Ze(this.__data__,M)>-1}function ai(M,b){var n=this.__data__,p=Ze(n,M);return p<0?n.push([M,b]):n[p][1]=b,this}Ie.prototype.clear=ri;Ie.prototype.delete=ii;Ie.prototype.get=oi;Ie.prototype.has=si;Ie.prototype.set=ai;function Ae(M){var b=-1,n=M?M.length:0;for(this.clear();++b<n;){var p=M[b];this.set(p[0],p[1])}}function ci(){this.__data__={hash:new Ee,map:new(Jr||Ie),string:new Ee}}function li(M){return Qe(this,M).delete(M)}function pi(M){return Qe(this,M).get(M)}function hi(M){return Qe(this,M).has(M)}function ui(M,b){return Qe(this,M).set(M,b),this}Ae.prototype.clear=ci;Ae.prototype.delete=li;Ae.prototype.get=pi;Ae.prototype.has=hi;Ae.prototype.set=ui;function Ze(M,b){for(var n=M.length;n--;)if(ki(M[n][0],b))return n;return-1}function di(M,b){b=bi(b,M)?[b]:mi(b);for(var n=0,p=b.length;M!=null&&n<p;)M=M[wi(b[n++])];return n&&n==p?M:void 0}function gi(M){if(!nn(M)||yi(M))return!1;var b=$i(M)||Vr(M)?Xr:Hr;return b.test(_i(M))}function fi(M){if(typeof M=="string")return M;if(yt(M))return qt?qt.call(M):"";var b=M+"";return b=="0"&&1/M==-Yt?"-0":b}function mi(M){return tn(M)?M:xi(M)}function Qe(M,b){var n=M.__data__;return vi(b)?n[typeof b=="string"?"string":"hash"]:n.map}function en(M,b){var n=Wr(M,b);return gi(n)?n:void 0}function bi(M,b){if(tn(M))return!1;var n=typeof M;return n=="number"||n=="symbol"||n=="boolean"||M==null||yt(M)?!0:Fr.test(M)||!Nr.test(M)||b!=null&&M in Object(b)}function vi(M){var b=typeof M;return b=="string"||b=="number"||b=="symbol"||b=="boolean"?M!=="__proto__":M===null}function yi(M){return!!jt&&jt in M}var xi=vt(function(M){M=Ci(M);var b=[];return zr.test(M)&&b.push(""),M.replace(Dr,function(n,p,A,e){b.push(A?e.replace(jr,"$1"):p||n)}),b});function wi(M){if(typeof M=="string"||yt(M))return M;var b=M+"";return b=="0"&&1/M==-Yt?"-0":b}function _i(M){if(M!=null){try{return Zt.call(M)}catch(b){}try{return M+""}catch(b){}}return""}function vt(M,b){if(typeof M!="function"||b&&typeof b!="function")throw new TypeError(Or);var n=function(){var p=arguments,A=b?b.apply(this,p):p[0],e=n.cache;if(e.has(A))return e.get(A);var a=M.apply(this,p);return n.cache=e.set(A,a),a};return n.cache=new(vt.Cache||Ae),n}vt.Cache=Ae;function ki(M,b){return M===b||M!==M&&b!==b}var tn=Array.isArray;function $i(M){var b=nn(M)?Qt.call(M):"";return b==Lr||b==Ir}function nn(M){var b=typeof M;return!!M&&(b=="object"||b=="function")}function Si(M){return!!M&&typeof M=="object"}function yt(M){return typeof M=="symbol"||Si(M)&&Qt.call(M)==Pr}function Ci(M){return M==null?"":fi(M)}function Ti(M,b,n){var p=M==null?void 0:di(M,b);return p===void 0?n:p}var Ei=Ti,Ai=re&&re.__extends||function(){var M=function(b,n){return M=Object.setPrototypeOf||{__proto__:[]}instanceof Array&&function(p,A){p.__proto__=A}||function(p,A){for(var e in A)Object.prototype.hasOwnProperty.call(A,e)&&(p[e]=A[e])},M(b,n)};return function(b,n){if(typeof n!="function"&&n!==null)throw new TypeError("Class extends value "+String(n)+" is not a constructor or null");M(b,n);function p(){this.constructor=b}b.prototype=n===null?Object.create(n):(p.prototype=n.prototype,new p)}}(),ht=re&&re.__assign||function(){return ht=Object.assign||function(M){for(var b,n=1,p=arguments.length;n<p;n++){b=arguments[n];for(var A in b)Object.prototype.hasOwnProperty.call(b,A)&&(M[A]=b[A])}return M},ht.apply(this,arguments)};Object.defineProperty(Je,"__esModule",{value:!0});var Te=de,ct=(0,Te.getAceInstance)(),Mi=Xe,Ri=Rr,U=ut,Wt=dt,lt=Gt,be=Ei,Oi=function(M){Ai(b,M);function b(n){var p=M.call(this,n)||this;return Te.editorEvents.forEach(function(A){p[A]=p[A].bind(p)}),p.debounce=Te.debounce,p}return b.prototype.isInShadow=function(n){for(var p=n&&n.parentNode;p;){if(p.toString()==="[object ShadowRoot]")return!0;p=p.parentNode}return!1},b.prototype.componentDidMount=function(){var n=this,p=this.props,A=p.className,e=p.onBeforeLoad,a=p.mode,h=p.focus,g=p.theme,l=p.fontSize,s=p.value,i=p.defaultValue,d=p.cursorStart,f=p.showGutter,$=p.wrapEnabled,S=p.showPrintMargin,m=p.scrollMargin,v=m===void 0?[0,0,0,0]:m,E=p.keyboardHandler,P=p.onLoad,R=p.commands,k=p.annotations,t=p.markers,r=p.splits;this.editor=ct.edit(this.refEditor),this.isInShadow(this.refEditor)&&this.editor.renderer.attachToShadowRoot(),this.editor.setTheme("ace/theme/".concat(g)),e&&e(ct);var o=Object.keys(this.props.editorProps),c=new Ri.Split(this.editor.container,"ace/theme/".concat(g),r);this.editor.env.split=c,this.splitEditor=c.getEditor(0),this.split=c,this.editor.setShowPrintMargin(!1),this.editor.renderer.setShowGutter(!1);var u=this.splitEditor.$options;this.props.debounceChangePeriod&&(this.onChange=this.debounce(this.onChange,this.props.debounceChangePeriod)),c.forEach(function(w,C){for(var _=0;_<o.length;_++)w[o[_]]=n.props.editorProps[o[_]];var T=be(i,C),L=be(s,C,"");w.session.setUndoManager(new ct.UndoManager),w.setTheme("ace/theme/".concat(g)),w.renderer.setScrollMargin(v[0],v[1],v[2],v[3]),w.getSession().setMode("ace/mode/".concat(a)),w.setFontSize(l),w.renderer.setShowGutter(f),w.getSession().setUseWrapMode($),w.setShowPrintMargin(S),w.on("focus",n.onFocus),w.on("blur",n.onBlur),w.on("input",n.onInput),w.on("copy",n.onCopy),w.on("paste",n.onPaste),w.on("change",n.onChange),w.getSession().selection.on("changeSelection",n.onSelectionChange),w.getSession().selection.on("changeCursor",n.onCursorChange),w.session.on("changeScrollTop",n.onScroll),w.setValue(T===void 0?L:T,d);var F=be(k,C,[]),I=be(t,C,[]);w.getSession().setAnnotations(F),I&&I.length>0&&n.handleMarkers(I,w);for(var _=0;_<Te.editorOptions.length;_++){var z=Te.editorOptions[_];u.hasOwnProperty(z)?w.setOption(z,n.props[z]):n.props[z]}n.handleOptions(n.props,w),Array.isArray(R)&&R.forEach(function(j){typeof j.exec=="string"?w.commands.bindKey(j.bindKey,j.exec):w.commands.addCommand(j)}),E&&w.setKeyboardHandler("ace/keyboard/"+E)}),A&&(this.refEditor.className+=" "+A),h&&this.splitEditor.focus();var y=this.editor.env.split;y.setOrientation(this.props.orientation==="below"?y.BELOW:y.BESIDE),y.resize(!0),P&&P(y)},b.prototype.componentDidUpdate=function(n){var p=this,A=n,e=this.props,a=this.editor.env.split;if(e.splits!==A.splits&&a.setSplits(e.splits),e.orientation!==A.orientation&&a.setOrientation(e.orientation==="below"?a.BELOW:a.BESIDE),a.forEach(function(s,i){e.mode!==A.mode&&s.getSession().setMode("ace/mode/"+e.mode),e.keyboardHandler!==A.keyboardHandler&&(e.keyboardHandler?s.setKeyboardHandler("ace/keyboard/"+e.keyboardHandler):s.setKeyboardHandler(null)),e.fontSize!==A.fontSize&&s.setFontSize(e.fontSize),e.wrapEnabled!==A.wrapEnabled&&s.getSession().setUseWrapMode(e.wrapEnabled),e.showPrintMargin!==A.showPrintMargin&&s.setShowPrintMargin(e.showPrintMargin),e.showGutter!==A.showGutter&&s.renderer.setShowGutter(e.showGutter);for(var d=0;d<Te.editorOptions.length;d++){var f=Te.editorOptions[d];e[f]!==A[f]&&s.setOption(f,e[f])}lt(e.setOptions,A.setOptions)||p.handleOptions(e,s);var $=be(e.value,i,"");if(s.getValue()!==$){p.silent=!0;var S=s.session.selection.toJSON();s.setValue($,e.cursorStart),s.session.selection.fromJSON(S),p.silent=!1}var m=be(e.annotations,i,[]),v=be(A.annotations,i,[]);lt(m,v)||s.getSession().setAnnotations(m);var E=be(e.markers,i,[]),P=be(A.markers,i,[]);!lt(E,P)&&Array.isArray(E)&&p.handleMarkers(E,s)}),e.className!==A.className){var h=this.refEditor.className,g=h.trim().split(" "),l=A.className.trim().split(" ");l.forEach(function(s){var i=g.indexOf(s);g.splice(i,1)}),this.refEditor.className=" "+e.className+" "+g.join(" ")}e.theme!==A.theme&&a.setTheme("ace/theme/"+e.theme),e.focus&&!A.focus&&this.splitEditor.focus(),(e.height!==this.props.height||e.width!==this.props.width)&&this.editor.resize()},b.prototype.componentWillUnmount=function(){this.editor.destroy(),this.editor=null},b.prototype.onChange=function(n){if(this.props.onChange&&!this.silent){var p=[];this.editor.env.split.forEach(function(A){p.push(A.getValue())}),this.props.onChange(p,n)}},b.prototype.onSelectionChange=function(n){if(this.props.onSelectionChange){var p=[];this.editor.env.split.forEach(function(A){p.push(A.getSelection())}),this.props.onSelectionChange(p,n)}},b.prototype.onCursorChange=function(n){if(this.props.onCursorChange){var p=[];this.editor.env.split.forEach(function(A){p.push(A.getSelection())}),this.props.onCursorChange(p,n)}},b.prototype.onFocus=function(n){this.props.onFocus&&this.props.onFocus(n)},b.prototype.onInput=function(n){this.props.onInput&&this.props.onInput(n)},b.prototype.onBlur=function(n){this.props.onBlur&&this.props.onBlur(n)},b.prototype.onCopy=function(n){this.props.onCopy&&this.props.onCopy(n)},b.prototype.onPaste=function(n){this.props.onPaste&&this.props.onPaste(n)},b.prototype.onScroll=function(){this.props.onScroll&&this.props.onScroll(this.editor)},b.prototype.handleOptions=function(n,p){for(var A=Object.keys(n.setOptions),e=0;e<A.length;e++)p.setOption(A[e],n.setOptions[A[e]])},b.prototype.handleMarkers=function(n,p){var A=p.getSession().getMarkers(!0);for(var e in A)A.hasOwnProperty(e)&&p.getSession().removeMarker(A[e].id);A=p.getSession().getMarkers(!1);for(var e in A)A.hasOwnProperty(e)&&p.getSession().removeMarker(A[e].id);n.forEach(function(a){var h=a.startRow,g=a.startCol,l=a.endRow,s=a.endCol,i=a.className,d=a.type,f=a.inFront,$=f===void 0?!1:f,S=new Mi.Range(h,g,l,s);p.getSession().addMarker(S,i,d,$)})},b.prototype.updateRef=function(n){this.refEditor=n},b.prototype.render=function(){var n=this.props,p=n.name,A=n.width,e=n.height,a=n.style,h=ht({width:A,height:e},a);return Wt.createElement("div",{ref:this.updateRef,id:p,style:h})},b.propTypes={className:U.string,debounceChangePeriod:U.number,defaultValue:U.arrayOf(U.string),focus:U.bool,fontSize:U.oneOfType([U.number,U.string]),height:U.string,mode:U.string,name:U.string,onBlur:U.func,onChange:U.func,onCopy:U.func,onFocus:U.func,onInput:U.func,onLoad:U.func,onPaste:U.func,onScroll:U.func,orientation:U.string,showGutter:U.bool,splits:U.number,theme:U.string,value:U.arrayOf(U.string),width:U.string,onSelectionChange:U.func,onCursorChange:U.func,onBeforeLoad:U.func,minLines:U.number,maxLines:U.number,readOnly:U.bool,highlightActiveLine:U.bool,tabSize:U.number,showPrintMargin:U.bool,cursorStart:U.number,editorProps:U.object,setOptions:U.object,style:U.object,scrollMargin:U.array,annotations:U.array,markers:U.array,keyboardHandler:U.string,wrapEnabled:U.bool,enableBasicAutocompletion:U.oneOfType([U.bool,U.array]),enableLiveAutocompletion:U.oneOfType([U.bool,U.array]),commands:U.array},b.defaultProps={name:"ace-editor",focus:!1,orientation:"beside",splits:2,mode:"",theme:"",height:"500px",width:"500px",value:[],fontSize:12,showGutter:!0,onChange:null,onPaste:null,onLoad:null,onScroll:null,minLines:null,maxLines:null,readOnly:!1,highlightActiveLine:!0,showPrintMargin:!0,tabSize:4,cursorStart:1,editorProps:{},style:{},scrollMargin:[0,0,0,0],setOptions:{},wrapEnabled:!1,enableBasicAutocompletion:!1,enableLiveAutocompletion:!1},b}(Wt.Component);Je.default=Oi;var rn={exports:{}};(function(M){var b=function(){this.Diff_Timeout=1,this.Diff_EditCost=4,this.Match_Threshold=.5,this.Match_Distance=1e3,this.Patch_DeleteThreshold=.5,this.Patch_Margin=4,this.Match_MaxBits=32},n=-1,p=1,A=0;b.Diff=function(e,a){return[e,a]},b.prototype.diff_main=function(e,a,h,g){typeof g=="undefined"&&(this.Diff_Timeout<=0?g=Number.MAX_VALUE:g=new Date().getTime()+this.Diff_Timeout*1e3);var l=g;if(e==null||a==null)throw new Error("Null input. (diff_main)");if(e==a)return e?[new b.Diff(A,e)]:[];typeof h=="undefined"&&(h=!0);var s=h,i=this.diff_commonPrefix(e,a),d=e.substring(0,i);e=e.substring(i),a=a.substring(i),i=this.diff_commonSuffix(e,a);var f=e.substring(e.length-i);e=e.substring(0,e.length-i),a=a.substring(0,a.length-i);var $=this.diff_compute_(e,a,s,l);return d&&$.unshift(new b.Diff(A,d)),f&&$.push(new b.Diff(A,f)),this.diff_cleanupMerge($),$},b.prototype.diff_compute_=function(e,a,h,g){var l;if(!e)return[new b.Diff(p,a)];if(!a)return[new b.Diff(n,e)];var s=e.length>a.length?e:a,i=e.length>a.length?a:e,d=s.indexOf(i);if(d!=-1)return l=[new b.Diff(p,s.substring(0,d)),new b.Diff(A,i),new b.Diff(p,s.substring(d+i.length))],e.length>a.length&&(l[0][0]=l[2][0]=n),l;if(i.length==1)return[new b.Diff(n,e),new b.Diff(p,a)];var f=this.diff_halfMatch_(e,a);if(f){var $=f[0],S=f[1],m=f[2],v=f[3],E=f[4],P=this.diff_main($,m,h,g),R=this.diff_main(S,v,h,g);return P.concat([new b.Diff(A,E)],R)}return h&&e.length>100&&a.length>100?this.diff_lineMode_(e,a,g):this.diff_bisect_(e,a,g)},b.prototype.diff_lineMode_=function(e,a,h){var g=this.diff_linesToChars_(e,a);e=g.chars1,a=g.chars2;var l=g.lineArray,s=this.diff_main(e,a,!1,h);this.diff_charsToLines_(s,l),this.diff_cleanupSemantic(s),s.push(new b.Diff(A,""));for(var i=0,d=0,f=0,$="",S="";i<s.length;){switch(s[i][0]){case p:f++,S+=s[i][1];break;case n:d++,$+=s[i][1];break;case A:if(d>=1&&f>=1){s.splice(i-d-f,d+f),i=i-d-f;for(var m=this.diff_main($,S,!1,h),v=m.length-1;v>=0;v--)s.splice(i,0,m[v]);i=i+m.length}f=0,d=0,$="",S="";break}i++}return s.pop(),s},b.prototype.diff_bisect_=function(e,a,h){for(var g=e.length,l=a.length,s=Math.ceil((g+l)/2),i=s,d=2*s,f=new Array(d),$=new Array(d),S=0;S<d;S++)f[S]=-1,$[S]=-1;f[i+1]=0,$[i+1]=0;for(var m=g-l,v=m%2!=0,E=0,P=0,R=0,k=0,t=0;t<s&&!(new Date().getTime()>h);t++){for(var r=-t+E;r<=t-P;r+=2){var o=i+r,c;r==-t||r!=t&&f[o-1]<f[o+1]?c=f[o+1]:c=f[o-1]+1;for(var u=c-r;c<g&&u<l&&e.charAt(c)==a.charAt(u);)c++,u++;if(f[o]=c,c>g)P+=2;else if(u>l)E+=2;else if(v){var y=i+m-r;if(y>=0&&y<d&&$[y]!=-1){var w=g-$[y];if(c>=w)return this.diff_bisectSplit_(e,a,c,u,h)}}}for(var C=-t+R;C<=t-k;C+=2){var y=i+C,w;C==-t||C!=t&&$[y-1]<$[y+1]?w=$[y+1]:w=$[y-1]+1;for(var _=w-C;w<g&&_<l&&e.charAt(g-w-1)==a.charAt(l-_-1);)w++,_++;if($[y]=w,w>g)k+=2;else if(_>l)R+=2;else if(!v){var o=i+m-C;if(o>=0&&o<d&&f[o]!=-1){var c=f[o],u=i+c-o;if(w=g-w,c>=w)return this.diff_bisectSplit_(e,a,c,u,h)}}}}return[new b.Diff(n,e),new b.Diff(p,a)]},b.prototype.diff_bisectSplit_=function(e,a,h,g,l){var s=e.substring(0,h),i=a.substring(0,g),d=e.substring(h),f=a.substring(g),$=this.diff_main(s,i,!1,l),S=this.diff_main(d,f,!1,l);return $.concat(S)},b.prototype.diff_linesToChars_=function(e,a){var h=[],g={};h[0]="";function l(f){for(var $="",S=0,m=-1,v=h.length;m<f.length-1;){m=f.indexOf(`
`,S),m==-1&&(m=f.length-1);var E=f.substring(S,m+1);(g.hasOwnProperty?g.hasOwnProperty(E):g[E]!==void 0)?$+=String.fromCharCode(g[E]):(v==s&&(E=f.substring(S),m=f.length),$+=String.fromCharCode(v),g[E]=v,h[v++]=E),S=m+1}return $}var s=4e4,i=l(e);s=65535;var d=l(a);return{chars1:i,chars2:d,lineArray:h}},b.prototype.diff_charsToLines_=function(e,a){for(var h=0;h<e.length;h++){for(var g=e[h][1],l=[],s=0;s<g.length;s++)l[s]=a[g.charCodeAt(s)];e[h][1]=l.join("")}},b.prototype.diff_commonPrefix=function(e,a){if(!e||!a||e.charAt(0)!=a.charAt(0))return 0;for(var h=0,g=Math.min(e.length,a.length),l=g,s=0;h<l;)e.substring(s,l)==a.substring(s,l)?(h=l,s=h):g=l,l=Math.floor((g-h)/2+h);return l},b.prototype.diff_commonSuffix=function(e,a){if(!e||!a||e.charAt(e.length-1)!=a.charAt(a.length-1))return 0;for(var h=0,g=Math.min(e.length,a.length),l=g,s=0;h<l;)e.substring(e.length-l,e.length-s)==a.substring(a.length-l,a.length-s)?(h=l,s=h):g=l,l=Math.floor((g-h)/2+h);return l},b.prototype.diff_commonOverlap_=function(e,a){var h=e.length,g=a.length;if(h==0||g==0)return 0;h>g?e=e.substring(h-g):h<g&&(a=a.substring(0,h));var l=Math.min(h,g);if(e==a)return l;for(var s=0,i=1;;){var d=e.substring(l-i),f=a.indexOf(d);if(f==-1)return s;i+=f,(f==0||e.substring(l-i)==a.substring(0,i))&&(s=i,i++)}},b.prototype.diff_halfMatch_=function(e,a){if(this.Diff_Timeout<=0)return null;var h=e.length>a.length?e:a,g=e.length>a.length?a:e;if(h.length<4||g.length*2<h.length)return null;var l=this;function s(P,R,k){for(var t=P.substring(k,k+Math.floor(P.length/4)),r=-1,o="",c,u,y,w;(r=R.indexOf(t,r+1))!=-1;){var C=l.diff_commonPrefix(P.substring(k),R.substring(r)),_=l.diff_commonSuffix(P.substring(0,k),R.substring(0,r));o.length<_+C&&(o=R.substring(r-_,r)+R.substring(r,r+C),c=P.substring(0,k-_),u=P.substring(k+C),y=R.substring(0,r-_),w=R.substring(r+C))}return o.length*2>=P.length?[c,u,y,w,o]:null}var i=s(h,g,Math.ceil(h.length/4)),d=s(h,g,Math.ceil(h.length/2)),f;if(!i&&!d)return null;d?i?f=i[4].length>d[4].length?i:d:f=d:f=i;var $,S,m,v;e.length>a.length?($=f[0],S=f[1],m=f[2],v=f[3]):(m=f[0],v=f[1],$=f[2],S=f[3]);var E=f[4];return[$,S,m,v,E]},b.prototype.diff_cleanupSemantic=function(e){for(var a=!1,h=[],g=0,l=null,s=0,i=0,d=0,f=0,$=0;s<e.length;)e[s][0]==A?(h[g++]=s,i=f,d=$,f=0,$=0,l=e[s][1]):(e[s][0]==p?f+=e[s][1].length:$+=e[s][1].length,l&&l.length<=Math.max(i,d)&&l.length<=Math.max(f,$)&&(e.splice(h[g-1],0,new b.Diff(n,l)),e[h[g-1]+1][0]=p,g--,g--,s=g>0?h[g-1]:-1,i=0,d=0,f=0,$=0,l=null,a=!0)),s++;for(a&&this.diff_cleanupMerge(e),this.diff_cleanupSemanticLossless(e),s=1;s<e.length;){if(e[s-1][0]==n&&e[s][0]==p){var S=e[s-1][1],m=e[s][1],v=this.diff_commonOverlap_(S,m),E=this.diff_commonOverlap_(m,S);v>=E?(v>=S.length/2||v>=m.length/2)&&(e.splice(s,0,new b.Diff(A,m.substring(0,v))),e[s-1][1]=S.substring(0,S.length-v),e[s+1][1]=m.substring(v),s++):(E>=S.length/2||E>=m.length/2)&&(e.splice(s,0,new b.Diff(A,S.substring(0,E))),e[s-1][0]=p,e[s-1][1]=m.substring(0,m.length-E),e[s+1][0]=n,e[s+1][1]=S.substring(E),s++),s++}s++}},b.prototype.diff_cleanupSemanticLossless=function(e){function a(E,P){if(!E||!P)return 6;var R=E.charAt(E.length-1),k=P.charAt(0),t=R.match(b.nonAlphaNumericRegex_),r=k.match(b.nonAlphaNumericRegex_),o=t&&R.match(b.whitespaceRegex_),c=r&&k.match(b.whitespaceRegex_),u=o&&R.match(b.linebreakRegex_),y=c&&k.match(b.linebreakRegex_),w=u&&E.match(b.blanklineEndRegex_),C=y&&P.match(b.blanklineStartRegex_);return w||C?5:u||y?4:t&&!o&&c?3:o||c?2:t||r?1:0}for(var h=1;h<e.length-1;){if(e[h-1][0]==A&&e[h+1][0]==A){var g=e[h-1][1],l=e[h][1],s=e[h+1][1],i=this.diff_commonSuffix(g,l);if(i){var d=l.substring(l.length-i);g=g.substring(0,g.length-i),l=d+l.substring(0,l.length-i),s=d+s}for(var f=g,$=l,S=s,m=a(g,l)+a(l,s);l.charAt(0)===s.charAt(0);){g+=l.charAt(0),l=l.substring(1)+s.charAt(0),s=s.substring(1);var v=a(g,l)+a(l,s);v>=m&&(m=v,f=g,$=l,S=s)}e[h-1][1]!=f&&(f?e[h-1][1]=f:(e.splice(h-1,1),h--),e[h][1]=$,S?e[h+1][1]=S:(e.splice(h+1,1),h--))}h++}},b.nonAlphaNumericRegex_=/[^a-zA-Z0-9]/,b.whitespaceRegex_=/\s/,b.linebreakRegex_=/[\r\n]/,b.blanklineEndRegex_=/\n\r?\n$/,b.blanklineStartRegex_=/^\r?\n\r?\n/,b.prototype.diff_cleanupEfficiency=function(e){for(var a=!1,h=[],g=0,l=null,s=0,i=!1,d=!1,f=!1,$=!1;s<e.length;)e[s][0]==A?(e[s][1].length<this.Diff_EditCost&&(f||$)?(h[g++]=s,i=f,d=$,l=e[s][1]):(g=0,l=null),f=$=!1):(e[s][0]==n?$=!0:f=!0,l&&(i&&d&&f&&$||l.length<this.Diff_EditCost/2&&i+d+f+$==3)&&(e.splice(h[g-1],0,new b.Diff(n,l)),e[h[g-1]+1][0]=p,g--,l=null,i&&d?(f=$=!0,g=0):(g--,s=g>0?h[g-1]:-1,f=$=!1),a=!0)),s++;a&&this.diff_cleanupMerge(e)},b.prototype.diff_cleanupMerge=function(e){e.push(new b.Diff(A,""));for(var a=0,h=0,g=0,l="",s="",i;a<e.length;)switch(e[a][0]){case p:g++,s+=e[a][1],a++;break;case n:h++,l+=e[a][1],a++;break;case A:h+g>1?(h!==0&&g!==0&&(i=this.diff_commonPrefix(s,l),i!==0&&(a-h-g>0&&e[a-h-g-1][0]==A?e[a-h-g-1][1]+=s.substring(0,i):(e.splice(0,0,new b.Diff(A,s.substring(0,i))),a++),s=s.substring(i),l=l.substring(i)),i=this.diff_commonSuffix(s,l),i!==0&&(e[a][1]=s.substring(s.length-i)+e[a][1],s=s.substring(0,s.length-i),l=l.substring(0,l.length-i))),a-=h+g,e.splice(a,h+g),l.length&&(e.splice(a,0,new b.Diff(n,l)),a++),s.length&&(e.splice(a,0,new b.Diff(p,s)),a++),a++):a!==0&&e[a-1][0]==A?(e[a-1][1]+=e[a][1],e.splice(a,1)):a++,g=0,h=0,l="",s="";break}e[e.length-1][1]===""&&e.pop();var d=!1;for(a=1;a<e.length-1;)e[a-1][0]==A&&e[a+1][0]==A&&(e[a][1].substring(e[a][1].length-e[a-1][1].length)==e[a-1][1]?(e[a][1]=e[a-1][1]+e[a][1].substring(0,e[a][1].length-e[a-1][1].length),e[a+1][1]=e[a-1][1]+e[a+1][1],e.splice(a-1,1),d=!0):e[a][1].substring(0,e[a+1][1].length)==e[a+1][1]&&(e[a-1][1]+=e[a+1][1],e[a][1]=e[a][1].substring(e[a+1][1].length)+e[a+1][1],e.splice(a+1,1),d=!0)),a++;d&&this.diff_cleanupMerge(e)},b.prototype.diff_xIndex=function(e,a){var h=0,g=0,l=0,s=0,i;for(i=0;i<e.length&&(e[i][0]!==p&&(h+=e[i][1].length),e[i][0]!==n&&(g+=e[i][1].length),!(h>a));i++)l=h,s=g;return e.length!=i&&e[i][0]===n?s:s+(a-l)},b.prototype.diff_prettyHtml=function(e){for(var a=[],h=/&/g,g=/</g,l=/>/g,s=/\n/g,i=0;i<e.length;i++){var d=e[i][0],f=e[i][1],$=f.replace(h,"&amp;").replace(g,"&lt;").replace(l,"&gt;").replace(s,"&para;<br>");switch(d){case p:a[i]='<ins style="background:#e6ffe6;">'+$+"</ins>";break;case n:a[i]='<del style="background:#ffe6e6;">'+$+"</del>";break;case A:a[i]="<span>"+$+"</span>";break}}return a.join("")},b.prototype.diff_text1=function(e){for(var a=[],h=0;h<e.length;h++)e[h][0]!==p&&(a[h]=e[h][1]);return a.join("")},b.prototype.diff_text2=function(e){for(var a=[],h=0;h<e.length;h++)e[h][0]!==n&&(a[h]=e[h][1]);return a.join("")},b.prototype.diff_levenshtein=function(e){for(var a=0,h=0,g=0,l=0;l<e.length;l++){var s=e[l][0],i=e[l][1];switch(s){case p:h+=i.length;break;case n:g+=i.length;break;case A:a+=Math.max(h,g),h=0,g=0;break}}return a+=Math.max(h,g),a},b.prototype.diff_toDelta=function(e){for(var a=[],h=0;h<e.length;h++)switch(e[h][0]){case p:a[h]="+"+encodeURI(e[h][1]);break;case n:a[h]="-"+e[h][1].length;break;case A:a[h]="="+e[h][1].length;break}return a.join("	").replace(/%20/g," ")},b.prototype.diff_fromDelta=function(e,a){for(var h=[],g=0,l=0,s=a.split(/\t/g),i=0;i<s.length;i++){var d=s[i].substring(1);switch(s[i].charAt(0)){case"+":try{h[g++]=new b.Diff(p,decodeURI(d))}catch(S){throw new Error("Illegal escape in diff_fromDelta: "+d)}break;case"-":case"=":var f=parseInt(d,10);if(isNaN(f)||f<0)throw new Error("Invalid number in diff_fromDelta: "+d);var $=e.substring(l,l+=f);s[i].charAt(0)=="="?h[g++]=new b.Diff(A,$):h[g++]=new b.Diff(n,$);break;default:if(s[i])throw new Error("Invalid diff operation in diff_fromDelta: "+s[i])}}if(l!=e.length)throw new Error("Delta length ("+l+") does not equal source text length ("+e.length+").");return h},b.prototype.match_main=function(e,a,h){if(e==null||a==null||h==null)throw new Error("Null input. (match_main)");return h=Math.max(0,Math.min(h,e.length)),e==a?0:e.length?e.substring(h,h+a.length)==a?h:this.match_bitap_(e,a,h):-1},b.prototype.match_bitap_=function(e,a,h){if(a.length>this.Match_MaxBits)throw new Error("Pattern too long for this browser.");var g=this.match_alphabet_(a),l=this;function s(c,u){var y=c/a.length,w=Math.abs(h-u);return l.Match_Distance?y+w/l.Match_Distance:w?1:y}var i=this.Match_Threshold,d=e.indexOf(a,h);d!=-1&&(i=Math.min(s(0,d),i),d=e.lastIndexOf(a,h+a.length),d!=-1&&(i=Math.min(s(0,d),i)));var f=1<<a.length-1;d=-1;for(var $,S,m=a.length+e.length,v,E=0;E<a.length;E++){for($=0,S=m;$<S;)s(E,h+S)<=i?$=S:m=S,S=Math.floor((m-$)/2+$);m=S;var P=Math.max(1,h-S+1),R=Math.min(h+S,e.length)+a.length,k=Array(R+2);k[R+1]=(1<<E)-1;for(var t=R;t>=P;t--){var r=g[e.charAt(t-1)];if(E===0?k[t]=(k[t+1]<<1|1)&r:k[t]=(k[t+1]<<1|1)&r|((v[t+1]|v[t])<<1|1)|v[t+1],k[t]&f){var o=s(E,t-1);if(o<=i)if(i=o,d=t-1,d>h)P=Math.max(1,2*h-d);else break}}if(s(E+1,h)>i)break;v=k}return d},b.prototype.match_alphabet_=function(e){for(var a={},h=0;h<e.length;h++)a[e.charAt(h)]=0;for(var h=0;h<e.length;h++)a[e.charAt(h)]|=1<<e.length-h-1;return a},b.prototype.patch_addContext_=function(e,a){if(a.length!=0){if(e.start2===null)throw Error("patch not initialized");for(var h=a.substring(e.start2,e.start2+e.length1),g=0;a.indexOf(h)!=a.lastIndexOf(h)&&h.length<this.Match_MaxBits-this.Patch_Margin-this.Patch_Margin;)g+=this.Patch_Margin,h=a.substring(e.start2-g,e.start2+e.length1+g);g+=this.Patch_Margin;var l=a.substring(e.start2-g,e.start2);l&&e.diffs.unshift(new b.Diff(A,l));var s=a.substring(e.start2+e.length1,e.start2+e.length1+g);s&&e.diffs.push(new b.Diff(A,s)),e.start1-=l.length,e.start2-=l.length,e.length1+=l.length+s.length,e.length2+=l.length+s.length}},b.prototype.patch_make=function(e,a,h){var g,l;if(typeof e=="string"&&typeof a=="string"&&typeof h=="undefined")g=e,l=this.diff_main(g,a,!0),l.length>2&&(this.diff_cleanupSemantic(l),this.diff_cleanupEfficiency(l));else if(e&&typeof e=="object"&&typeof a=="undefined"&&typeof h=="undefined")l=e,g=this.diff_text1(l);else if(typeof e=="string"&&a&&typeof a=="object"&&typeof h=="undefined")g=e,l=a;else if(typeof e=="string"&&typeof a=="string"&&h&&typeof h=="object")g=e,l=h;else throw new Error("Unknown call format to patch_make.");if(l.length===0)return[];for(var s=[],i=new b.patch_obj,d=0,f=0,$=0,S=g,m=g,v=0;v<l.length;v++){var E=l[v][0],P=l[v][1];switch(!d&&E!==A&&(i.start1=f,i.start2=$),E){case p:i.diffs[d++]=l[v],i.length2+=P.length,m=m.substring(0,$)+P+m.substring($);break;case n:i.length1+=P.length,i.diffs[d++]=l[v],m=m.substring(0,$)+m.substring($+P.length);break;case A:P.length<=2*this.Patch_Margin&&d&&l.length!=v+1?(i.diffs[d++]=l[v],i.length1+=P.length,i.length2+=P.length):P.length>=2*this.Patch_Margin&&d&&(this.patch_addContext_(i,S),s.push(i),i=new b.patch_obj,d=0,S=m,f=$);break}E!==p&&(f+=P.length),E!==n&&($+=P.length)}return d&&(this.patch_addContext_(i,S),s.push(i)),s},b.prototype.patch_deepCopy=function(e){for(var a=[],h=0;h<e.length;h++){var g=e[h],l=new b.patch_obj;l.diffs=[];for(var s=0;s<g.diffs.length;s++)l.diffs[s]=new b.Diff(g.diffs[s][0],g.diffs[s][1]);l.start1=g.start1,l.start2=g.start2,l.length1=g.length1,l.length2=g.length2,a[h]=l}return a},b.prototype.patch_apply=function(e,a){if(e.length==0)return[a,[]];e=this.patch_deepCopy(e);var h=this.patch_addPadding(e);a=h+a+h,this.patch_splitMax(e);for(var g=0,l=[],s=0;s<e.length;s++){var i=e[s].start2+g,d=this.diff_text1(e[s].diffs),f,$=-1;if(d.length>this.Match_MaxBits?(f=this.match_main(a,d.substring(0,this.Match_MaxBits),i),f!=-1&&($=this.match_main(a,d.substring(d.length-this.Match_MaxBits),i+d.length-this.Match_MaxBits),($==-1||f>=$)&&(f=-1))):f=this.match_main(a,d,i),f==-1)l[s]=!1,g-=e[s].length2-e[s].length1;else{l[s]=!0,g=f-i;var S;if($==-1?S=a.substring(f,f+d.length):S=a.substring(f,$+this.Match_MaxBits),d==S)a=a.substring(0,f)+this.diff_text2(e[s].diffs)+a.substring(f+d.length);else{var m=this.diff_main(d,S,!1);if(d.length>this.Match_MaxBits&&this.diff_levenshtein(m)/d.length>this.Patch_DeleteThreshold)l[s]=!1;else{this.diff_cleanupSemanticLossless(m);for(var v=0,E,P=0;P<e[s].diffs.length;P++){var R=e[s].diffs[P];R[0]!==A&&(E=this.diff_xIndex(m,v)),R[0]===p?a=a.substring(0,f+E)+R[1]+a.substring(f+E):R[0]===n&&(a=a.substring(0,f+E)+a.substring(f+this.diff_xIndex(m,v+R[1].length))),R[0]!==n&&(v+=R[1].length)}}}}}return a=a.substring(h.length,a.length-h.length),[a,l]},b.prototype.patch_addPadding=function(e){for(var a=this.Patch_Margin,h="",g=1;g<=a;g++)h+=String.fromCharCode(g);for(var g=0;g<e.length;g++)e[g].start1+=a,e[g].start2+=a;var l=e[0],s=l.diffs;if(s.length==0||s[0][0]!=A)s.unshift(new b.Diff(A,h)),l.start1-=a,l.start2-=a,l.length1+=a,l.length2+=a;else if(a>s[0][1].length){var i=a-s[0][1].length;s[0][1]=h.substring(s[0][1].length)+s[0][1],l.start1-=i,l.start2-=i,l.length1+=i,l.length2+=i}if(l=e[e.length-1],s=l.diffs,s.length==0||s[s.length-1][0]!=A)s.push(new b.Diff(A,h)),l.length1+=a,l.length2+=a;else if(a>s[s.length-1][1].length){var i=a-s[s.length-1][1].length;s[s.length-1][1]+=h.substring(0,i),l.length1+=i,l.length2+=i}return h},b.prototype.patch_splitMax=function(e){for(var a=this.Match_MaxBits,h=0;h<e.length;h++)if(!(e[h].length1<=a)){var g=e[h];e.splice(h--,1);for(var l=g.start1,s=g.start2,i="";g.diffs.length!==0;){var d=new b.patch_obj,f=!0;for(d.start1=l-i.length,d.start2=s-i.length,i!==""&&(d.length1=d.length2=i.length,d.diffs.push(new b.Diff(A,i)));g.diffs.length!==0&&d.length1<a-this.Patch_Margin;){var $=g.diffs[0][0],S=g.diffs[0][1];$===p?(d.length2+=S.length,s+=S.length,d.diffs.push(g.diffs.shift()),f=!1):$===n&&d.diffs.length==1&&d.diffs[0][0]==A&&S.length>2*a?(d.length1+=S.length,l+=S.length,f=!1,d.diffs.push(new b.Diff($,S)),g.diffs.shift()):(S=S.substring(0,a-d.length1-this.Patch_Margin),d.length1+=S.length,l+=S.length,$===A?(d.length2+=S.length,s+=S.length):f=!1,d.diffs.push(new b.Diff($,S)),S==g.diffs[0][1]?g.diffs.shift():g.diffs[0][1]=g.diffs[0][1].substring(S.length))}i=this.diff_text2(d.diffs),i=i.substring(i.length-this.Patch_Margin);var m=this.diff_text1(g.diffs).substring(0,this.Patch_Margin);m!==""&&(d.length1+=m.length,d.length2+=m.length,d.diffs.length!==0&&d.diffs[d.diffs.length-1][0]===A?d.diffs[d.diffs.length-1][1]+=m:d.diffs.push(new b.Diff(A,m))),f||e.splice(++h,0,d)}}},b.prototype.patch_toText=function(e){for(var a=[],h=0;h<e.length;h++)a[h]=e[h];return a.join("")},b.prototype.patch_fromText=function(e){var a=[];if(!e)return a;for(var h=e.split(`
`),g=0,l=/^@@ -(\d+),?(\d*) \+(\d+),?(\d*) @@$/;g<h.length;){var s=h[g].match(l);if(!s)throw new Error("Invalid patch string: "+h[g]);var i=new b.patch_obj;for(a.push(i),i.start1=parseInt(s[1],10),s[2]===""?(i.start1--,i.length1=1):s[2]=="0"?i.length1=0:(i.start1--,i.length1=parseInt(s[2],10)),i.start2=parseInt(s[3],10),s[4]===""?(i.start2--,i.length2=1):s[4]=="0"?i.length2=0:(i.start2--,i.length2=parseInt(s[4],10)),g++;g<h.length;){var d=h[g].charAt(0);try{var f=decodeURI(h[g].substring(1))}catch($){throw new Error("Illegal escape in patch_fromText: "+f)}if(d=="-")i.diffs.push(new b.Diff(n,f));else if(d=="+")i.diffs.push(new b.Diff(p,f));else if(d==" ")i.diffs.push(new b.Diff(A,f));else{if(d=="@")break;if(d!=="")throw new Error('Invalid patch mode "'+d+'" in: '+f)}g++}}return a},b.patch_obj=function(){this.diffs=[],this.start1=null,this.start2=null,this.length1=0,this.length2=0},b.patch_obj.prototype.toString=function(){var e,a;this.length1===0?e=this.start1+",0":this.length1==1?e=this.start1+1:e=this.start1+1+","+this.length1,this.length2===0?a=this.start2+",0":this.length2==1?a=this.start2+1:a=this.start2+1+","+this.length2;for(var h=["@@ -"+e+" +"+a+` @@
`],g,l=0;l<this.diffs.length;l++){switch(this.diffs[l][0]){case p:g="+";break;case n:g="-";break;case A:g=" ";break}h[l+1]=g+encodeURI(this.diffs[l][1])+`
`}return h.join("").replace(/%20/g," ")},M.exports=b,M.exports.diff_match_patch=b,M.exports.DIFF_DELETE=n,M.exports.DIFF_INSERT=p,M.exports.DIFF_EQUAL=A})(rn);var Li=rn.exports,Ii=re&&re.__extends||function(){var M=function(b,n){return M=Object.setPrototypeOf||{__proto__:[]}instanceof Array&&function(p,A){p.__proto__=A}||function(p,A){for(var e in A)Object.prototype.hasOwnProperty.call(A,e)&&(p[e]=A[e])},M(b,n)};return function(b,n){if(typeof n!="function"&&n!==null)throw new TypeError("Class extends value "+String(n)+" is not a constructor or null");M(b,n);function p(){this.constructor=b}b.prototype=n===null?Object.create(n):(p.prototype=n.prototype,new p)}}();Object.defineProperty(ft,"__esModule",{value:!0});var Y=ut,Vt=dt,Pi=Je,Ni=Li,Fi=function(M){Ii(b,M);function b(n){var p=M.call(this,n)||this;return p.state={value:p.props.value},p.onChange=p.onChange.bind(p),p.diff=p.diff.bind(p),p}return b.prototype.componentDidUpdate=function(){var n=this.props.value;n!==this.state.value&&this.setState({value:n})},b.prototype.onChange=function(n){this.setState({value:n}),this.props.onChange&&this.props.onChange(n)},b.prototype.diff=function(){var n=new Ni,p=this.state.value[0],A=this.state.value[1];if(p.length===0&&A.length===0)return[];var e=n.diff_main(p,A);n.diff_cleanupSemantic(e);var a=this.generateDiffedLines(e),h=this.setCodeMarkers(a);return h},b.prototype.generateDiffedLines=function(n){var p={DIFF_EQUAL:0,DIFF_DELETE:-1,DIFF_INSERT:1},A={left:[],right:[]},e={left:1,right:1};return n.forEach(function(a){var h=a[0],g=a[1],l=g.split(`
`).length-1;if(g.length!==0){var s=g[0],i=g[g.length-1],d=0;switch(h){case p.DIFF_EQUAL:e.left+=l,e.right+=l;break;case p.DIFF_DELETE:s===`
`&&(e.left++,l--),d=l,d===0&&A.right.push({startLine:e.right,endLine:e.right}),i===`
`&&(d-=1),A.left.push({startLine:e.left,endLine:e.left+d}),e.left+=l;break;case p.DIFF_INSERT:s===`
`&&(e.right++,l--),d=l,d===0&&A.left.push({startLine:e.left,endLine:e.left}),i===`
`&&(d-=1),A.right.push({startLine:e.right,endLine:e.right+d}),e.right+=l;break;default:throw new Error("Diff type was not defined.")}}}),A},b.prototype.setCodeMarkers=function(n){n===void 0&&(n={left:[],right:[]});for(var p=[],A={left:[],right:[]},e=0;e<n.left.length;e++){var a={startRow:n.left[e].startLine-1,endRow:n.left[e].endLine,type:"text",className:"codeMarker"};A.left.push(a)}for(var e=0;e<n.right.length;e++){var a={startRow:n.right[e].startLine-1,endRow:n.right[e].endLine,type:"text",className:"codeMarker"};A.right.push(a)}return p[0]=A.left,p[1]=A.right,p},b.prototype.render=function(){var n=this.diff();return Vt.createElement(Pi.default,{name:this.props.name,className:this.props.className,focus:this.props.focus,orientation:this.props.orientation,splits:this.props.splits,mode:this.props.mode,theme:this.props.theme,height:this.props.height,width:this.props.width,fontSize:this.props.fontSize,showGutter:this.props.showGutter,onChange:this.onChange,onPaste:this.props.onPaste,onLoad:this.props.onLoad,onScroll:this.props.onScroll,minLines:this.props.minLines,maxLines:this.props.maxLines,readOnly:this.props.readOnly,highlightActiveLine:this.props.highlightActiveLine,showPrintMargin:this.props.showPrintMargin,tabSize:this.props.tabSize,cursorStart:this.props.cursorStart,editorProps:this.props.editorProps,style:this.props.style,scrollMargin:this.props.scrollMargin,setOptions:this.props.setOptions,wrapEnabled:this.props.wrapEnabled,enableBasicAutocompletion:this.props.enableBasicAutocompletion,enableLiveAutocompletion:this.props.enableLiveAutocompletion,value:this.state.value,markers:n})},b.propTypes={cursorStart:Y.number,editorProps:Y.object,enableBasicAutocompletion:Y.bool,enableLiveAutocompletion:Y.bool,focus:Y.bool,fontSize:Y.number,height:Y.string,highlightActiveLine:Y.bool,maxLines:Y.number,minLines:Y.number,mode:Y.string,name:Y.string,className:Y.string,onLoad:Y.func,onPaste:Y.func,onScroll:Y.func,onChange:Y.func,orientation:Y.string,readOnly:Y.bool,scrollMargin:Y.array,setOptions:Y.object,showGutter:Y.bool,showPrintMargin:Y.bool,splits:Y.number,style:Y.object,tabSize:Y.number,theme:Y.string,value:Y.array,width:Y.string,wrapEnabled:Y.bool},b.defaultProps={cursorStart:1,editorProps:{},enableBasicAutocompletion:!1,enableLiveAutocompletion:!1,focus:!1,fontSize:12,height:"500px",highlightActiveLine:!0,maxLines:null,minLines:null,mode:"",name:"ace-editor",onLoad:null,onScroll:null,onPaste:null,onChange:null,orientation:"beside",readOnly:!1,scrollMargin:[0,0,0,0],setOptions:{},showGutter:!0,showPrintMargin:!0,splits:2,style:{},tabSize:4,theme:"github",value:["",""],width:"500px",wrapEnabled:!0},b}(Vt.Component);ft.default=Fi;Object.defineProperty(Le,"__esModule",{value:!0});Le.diff=Le.split=void 0;var zi=gt,Di=ft;Le.diff=Di.default;var Bi=Je;Le.split=Bi.default;var Ui=Le.default=zi.default;export{Ui as _};
