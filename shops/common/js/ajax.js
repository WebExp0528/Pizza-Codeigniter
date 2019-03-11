function ajaxObject (callAfter) {
	var xmlstate;
	var  XMLLoader;
	var _datamode;
	var parsCount;
	var that;
	this.onloadCall = callAfter;

	ajaxObject.prototype._parseXML=function(a,b,c,d,xml){
		var p=new xmlPointer(xml.getXMLTopNode("ajax"));
		a.onloadCall(p);

	};

   this.callEvent=function(name,a){
         if (this["ev_"+name]) return this["ev_"+name].apply(this,a);
		 return true;
   };

   ajaxObject.prototype.setCtrl=function(c){
 	   this.ctrl = c;
  };

   ajaxObject.prototype.getCtrl=function(){
 	   return this.ctrl;
  };

   ajaxObject.prototype.loadXML=function(file,afterCall){ 
	  if (this._datamode && this._datamode!="xml") return this["load"+this._datamode.toUpperCase()](file,afterCall);
      that=this;
      this.xmlstate=1;

	  this.XMLLoader=new dtmlXMLLoaderObject(this._parseXML,this,true,this.no_cashe);

      //if (afterCall) this.XMLLoader.waitCall=afterCall;
      this.XMLLoader.loadXML(file,true,file,false);
   }
}
function xmlPointer(data){this.d=data};xmlPointer.prototype={text:function(){if (!_isFF)return this.d.xml;var x = new XMLSerializer();return x.serializeToString(this.d)},
 get:function(name){return this.d.getAttribute(name)},
 exists:function(){return !!this.d },
 content:function(){return this.d.firstChild?this.d.firstChild.data:""}, 
 each:function(name,f,t,i){var a=this.d.childNodes;var c=new xmlPointer();if (a.length)for (i=i||0;i<a.length;i++)if (a[i].tagName==name){c.d=a[i];if(f.apply(t,[c,i])==-1) return}},
 get_all:function(){var a={};var b=this.d.attributes;for (var i=0;i<b.length;i++)a[b[i].name]=b[i].value;return a},
 sub:function(name){var a=this.d.childNodes;var c=new xmlPointer();if (a.length)for (var i=0;i<a.length;i++)if (a[i].tagName==name){c.d=a[i];return c}},
 up:function(name){return new xmlPointer(this.d.parentNode)},
 set:function(name,val){this.d.setAttribute(name,val)},
 clone:function(name){return new xmlPointer(this.d)},
 sub_exists:function(name){var a=this.d.childNodes;if (a.length)for (var i=0;i<a.length;i++)if (a[i].tagName==name)return true;return false},
 through:function(name,rule,v,f,t){var a=this.d.childNodes;if (a.length)for (var i=0;i<a.length;i++){if (a[i].tagName==name && a[i].getAttribute(rule)!=null && a[i].getAttribute(rule)!="" && (!v || a[i].getAttribute(rule)==v )) {var c=new xmlPointer(a[i]);f.apply(t,[c,i])};var w=this.d;this.d=a[i];this.through(name,rule,v,f,t);this.d=w}}}
