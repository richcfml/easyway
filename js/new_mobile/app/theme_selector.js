(function(){window.EasyWay||(window.EasyWay={}),EasyWay.ThemeSelector=function(){function e(){}return e.$styleTag=$("head > #theme"),e.appendSelector=function(e){var t,n,i,r;for(this.themes=e,this.$select=$("<select>",{id:"theme-select",style:"position: absolute; top: 5px; left: 5px;"}),i=Object.keys(this.themes),t=0,n=i.length;n>t;t++)r=i[t],this.appendOption(r);return $("body").append(this.$select),this.bindEvents()},e.appendOption=function(e){var t;return t=$("<option>",{value:this.themes[e],text:e}),this.$select.append(t)},e.bindEvents=function(){return this.$select.on("change",function(){return EasyWay.ThemeSelector.$styleTag.attr("href","/stylesheets/"+this.value)})},e}()}).call(this);