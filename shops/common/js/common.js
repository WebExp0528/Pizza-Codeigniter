function interfaceObject (formID) {

	var elements;
	if (formID == undefined)
	{
		formObject = document.getElementById("contentForm");
	}
	else
	{
		formObject = document.getElementById(formID);
	}
	this.elements = formObject.elements;
}

interfaceObject.prototype = {

	getElements : function (formID) {
		if (formID == undefined)
		{
			formObject = document.getElementById("contentForm");
		}
		else
		{
			formObject = document.getElementById(formID);
		}
		this.elements = formObject.elements;
	},
	getElement : function (name) {

		for (i=0;i<this.elements.length;i++)
		{
			if (this.elements[i].id == name)
			{
				return this.elements[i];
			}
		}
		return null;
	},
	getObject : function (id) {
		if (document.getElementById(id))
		{
			return document.getElementById(id);
		}
		return null;
	},

	getExtraUrl : function (insert) {

		externUrl = "";
		if (!this.elements)
		{
			this.getElements();
		}
		for (i=0;i<this.elements.length;i++)
		{
			if (this.elements[i].name != "")
			{
				switch (this.elements[i].type)
				{
					case "radio":
						if (this.elements[i].checked)
						{
							externUrl	+=	"&" + this.elements[i].name + "="+this.elements[i].value;
						}
					break;
					case "checkbox":
						if (this.elements[i].checked)
						{
							externUrl	+=	"&" + this.elements[i].name + "=1";
						}
						else
							externUrl	+=	"&" + this.elements[i].name + "=0";
					break;
					case "button":
					break;
					case "select-one":
						obj = this.elements[i];
						if (obj.options.length > 0)
						{
							value = obj.options[obj.selectedIndex].value;
							externUrl	+=	"&" + obj.id + "=" + value;
						}
					break;
					case "select-multiple":
						obj = this.elements[i];
						f = 1;
						for (i=0;i<obj.options.length ;i++ )
						{
							if (obj.options[i].selected == true)
							{
								if (f)
								{
									value = obj.options[i].value;
									f = 0;
								}
								else
									value += ","+obj.options[i].value;
							}
						}
						externUrl	+=	"&" + obj.name + "=" + value;
					break;
					default:
						if (insert != undefined && this.elements[i].name == "id")
						{
							externUrl	+=	"&parent=" + this.elements[i].value;
						}
						else
						{
							externUrl	+=	"&" + this.elements[i].name + "=" + this.elements[i].value;
						}
					break;
				}
			}
		}
		return externUrl;
	},

	getTextValue: function (name) {
		return document.getElementById(name).value;
	},
	setTextValue: function (name,value) {
		try
		{
			document.getElementById(name).value=value;
		}
		catch (e)
		{
			return;
		}
	},
	setSpanContent: function (name,value) {
		try
		{
			document.getElementById(name).innerHTML=value;
		}
		catch (e)
		{
			return;
		}
	},
	
	InitElements: function () {
		for (i=0;i<this.elements.length;i++)
		{
				if (this.elements[i].type  == "hidden" || this.elements[i].type  == "text" || this.elements[i].type  == "password"||this.elements[i].type  == "textarea")
				{
					this.elements[i].value = "";
				}
				if (this.elements[i].type  == "select-multiple" )
				{
						len = this.elements[i].options.length;
						for (j=0;j<len;j++)
						{
							this.elements[i].options[j].selected = false
						}
				}
				if (this.elements[i].type  == "checkbox")
				{
					if (this.elements[i].checked)
					{
						this.elements[i].checked = false;
					}
				}
		}

	},
	EnableElements: function (p) {
		for (i=0;i<this.elements.length;i++)
		{
				if (this.elements[i].type  == "hidden" || this.elements[i].type  == "text" || this.elements[i].type  == "password" || this.elements[i].type == "textarea" || this.elements[i].type  == "select-multiple" || this.elements[i].type  == "button" )
				{
					if (p == "disable") this.elements[i].readOnly = true;
					else this.elements[i].readOnly = false;
				}
		}

	}
}
// add 2011-09-04
	var OSX = {
		container: null,
		open: function (d) {
			var self = this;
			self.container = d.container[0];
			d.overlay.fadeIn('fast', function () {
				$("#osx-modal-content", self.container).show();
				var title = $("#osx-modal-title", self.container);
				title.show();
				$("div.close", self.container).show();
				$("#osx-modal-data", self.container).show();
				d.container.fadeIn('slow', function () {
				});
			})
		},
		close: function (d) {
			var self = this; // this = SimpleModal object
			d.data.fadeOut('slow', function () {
				d.container.fadeOut('fast', function () {
					d.overlay.fadeOut('fast', function () {
						self.close(); // must call this!
					});
				});
			});
		}
	};
// add 2011-09-25
function progressControl (state) {
	var p1 = document.createElement("DIV");
	p1.className = "PolyProgress";
	var p2 = document.createElement("DIV");
	p2.className = "PolyProgressBGIMG";
	document.getElementById("progressbar").appendChild(p1);
	document.getElementById("progressbar").appendChild(p2);
	document.getElementById("progressbar").style.display = (state==true?"":"none");
}

$(document).ready(function() {
	$(".offer_link").click(function(){
		var url = $(this).attr("alt");
		$('#osx-modal-data').load(url);
		$("#osx-modal-title").html("Special Offer");
		$('#osx-modal-content').modal({
			overlayId: 'osx-overlay',
			containerId: 'osx-container',
			closeHTML: null,
			minHeight: 80,
			opacity: 65, 
			containerCss: {width:800,height: 600,overflow:'auto'}, 
			escClose: true,
			onOpen: OSX.open,
			onClose: OSX.close
		});
	});
	$(".category_link").click(function(){
		var url = $(this).attr("alt");
		window.location.href = url;
	});
});

