/**
 * BxSlider v4.1.2 - Fully loaded, responsive content slider
 * http://bxslider.com
 *
 * Copyright 2014, Steven Wanderski - http://stevenwanderski.com - http://bxcreative.com
 * Written while drinking Belgian ales and listening to jazz
 *
 * Released under the MIT license - http://opensource.org/licenses/MIT
 */
!function(e){var t={},n={mode:"horizontal",slideSelector:"",infiniteLoop:!0,hideControlOnEnd:!1,speed:500,easing:null,slideMargin:0,startSlide:0,randomStart:!1,captions:!1,ticker:!1,tickerHover:!1,adaptiveHeight:!1,adaptiveHeightSpeed:500,video:!1,useCSS:!0,preloadImages:"visible",responsive:!0,slideZIndex:50,wrapperClass:"bx-wrapper",touchEnabled:!0,swipeThreshold:50,oneToOneTouch:!0,preventDefaultSwipeX:!0,preventDefaultSwipeY:!1,pager:!0,pagerType:"full",pagerShortSeparator:" / ",pagerSelector:null,buildPager:null,pagerCustom:null,controls:!0,nextText:"Next",prevText:"Prev",nextSelector:null,prevSelector:null,autoControls:!1,startText:"Start",stopText:"Stop",autoControlsCombine:!1,autoControlsSelector:null,auto:!1,pause:4e3,autoStart:!0,autoDirection:"next",autoHover:!1,autoDelay:0,autoSlideForOnePage:!1,minSlides:1,maxSlides:1,moveSlides:0,slideWidth:0,onSliderLoad:function(){},onSlideBefore:function(){},onSlideAfter:function(){},onSlideNext:function(){},onSlidePrev:function(){},onSliderResize:function(){}};e.fn.bxSlider=function(r){if(0==this.length)return this;if(this.length>1)return this.each(function(){e(this).bxSlider(r)}),this;var o={},s=this;t.el=this;var a=e(window).width(),l=e(window).height(),c=function(){o.settings=e.extend({},n,r),o.settings.slideWidth=parseInt(o.settings.slideWidth),o.children=s.children(o.settings.slideSelector),o.children.length<o.settings.minSlides&&(o.settings.minSlides=o.children.length),o.children.length<o.settings.maxSlides&&(o.settings.maxSlides=o.children.length),o.settings.randomStart&&(o.settings.startSlide=Math.floor(Math.random()*o.children.length)),o.active={index:o.settings.startSlide},o.carousel=o.settings.minSlides>1||o.settings.maxSlides>1,o.carousel&&(o.settings.preloadImages="all"),o.minThreshold=o.settings.minSlides*o.settings.slideWidth+(o.settings.minSlides-1)*o.settings.slideMargin,o.maxThreshold=o.settings.maxSlides*o.settings.slideWidth+(o.settings.maxSlides-1)*o.settings.slideMargin,o.working=!1,o.controls={},o.interval=null,o.animProp="vertical"==o.settings.mode?"top":"left",o.usingCSS=o.settings.useCSS&&"fade"!=o.settings.mode&&function(){var e=document.createElement("div"),t=["WebkitPerspective","MozPerspective","OPerspective","msPerspective"];for(var n in t)if(void 0!==e.style[t[n]])return o.cssPrefix=t[n].replace("Perspective","").toLowerCase(),o.animProp="-"+o.cssPrefix+"-transform",!0;return!1}(),"vertical"==o.settings.mode&&(o.settings.maxSlides=o.settings.minSlides),s.data("origStyle",s.attr("style")),s.children(o.settings.slideSelector).each(function(){e(this).data("origStyle",e(this).attr("style"))}),u()},u=function(){s.wrap('<div class="'+o.settings.wrapperClass+'"><div class="bx-viewport"></div></div>'),o.viewport=s.parent(),o.loader=e('<div class="bx-loading" />'),o.viewport.prepend(o.loader),s.css({width:"horizontal"==o.settings.mode?100*o.children.length+215+"%":"auto",position:"relative"}),o.usingCSS&&o.settings.easing?s.css("-"+o.cssPrefix+"-transition-timing-function",o.settings.easing):o.settings.easing||(o.settings.easing="swing");v();o.viewport.css({width:"100%",overflow:"hidden",position:"relative"}),o.viewport.parent().css({maxWidth:h()}),o.settings.pager||o.viewport.parent().css({margin:"0 auto 0px"}),o.children.css({"float":"horizontal"==o.settings.mode?"left":"none",listStyle:"none",position:"relative"}),o.children.css("width",g()),"horizontal"==o.settings.mode&&o.settings.slideMargin>0&&o.children.css("marginRight",o.settings.slideMargin),"vertical"==o.settings.mode&&o.settings.slideMargin>0&&o.children.css("marginBottom",o.settings.slideMargin),"fade"==o.settings.mode&&(o.children.css({position:"absolute",zIndex:0,display:"none"}),o.children.eq(o.settings.startSlide).css({zIndex:o.settings.slideZIndex,display:"block"})),o.controls.el=e('<div class="bx-controls" />'),o.settings.captions&&C(),o.active.last=o.settings.startSlide==m()-1,o.settings.video&&s.fitVids();var t=o.children.eq(o.settings.startSlide);"all"==o.settings.preloadImages&&(t=o.children),o.settings.ticker?o.settings.pager=!1:(o.settings.pager&&S(),o.settings.controls&&E(),o.settings.auto&&o.settings.autoControls&&T(),(o.settings.controls||o.settings.autoControls||o.settings.pager)&&o.viewport.after(o.controls.el)),d(t,p)},d=function(t,n){var i=t.find("img, iframe").length;if(0==i)return void n();var r=0;t.find("img, iframe").each(function(){e(this).one("load",function(){++r==i&&n()}).each(function(){this.complete&&e(this).load()})})},p=function(){if(o.settings.infiniteLoop&&"fade"!=o.settings.mode&&!o.settings.ticker){var t="vertical"==o.settings.mode?o.settings.minSlides:o.settings.maxSlides,n=o.children.slice(0,t).clone().addClass("bx-clone"),i=o.children.slice(-t).clone().addClass("bx-clone");s.append(n).prepend(i)}o.loader.remove(),x(),"vertical"==o.settings.mode&&(o.settings.adaptiveHeight=!0),o.viewport.height(f()),s.redrawSlider(),o.settings.onSliderLoad(o.active.index),o.initialized=!0,o.settings.responsive&&e(window).bind("resize",z),o.settings.auto&&o.settings.autoStart&&(m()>1||o.settings.autoSlideForOnePage)&&L(),o.settings.ticker&&H(),o.settings.pager&&A(o.settings.startSlide),o.settings.controls&&j(),o.settings.touchEnabled&&!o.settings.ticker&&F()},f=function(){var t=0,n=e();if("vertical"==o.settings.mode||o.settings.adaptiveHeight)if(o.carousel){var r=1==o.settings.moveSlides?o.active.index:o.active.index*y();for(n=o.children.eq(r),i=1;i<=o.settings.maxSlides-1;i++)n=r+i>=o.children.length?n.add(o.children.eq(i-1)):n.add(o.children.eq(r+i))}else n=o.children.eq(o.active.index);else n=o.children;return"vertical"==o.settings.mode?(n.each(function(n){t+=e(this).outerHeight()}),o.settings.slideMargin>0&&(t+=o.settings.slideMargin*(o.settings.minSlides-1))):t=Math.max.apply(Math,n.map(function(){return e(this).outerHeight(!1)}).get()),"border-box"==o.viewport.css("box-sizing")?t+=parseFloat(o.viewport.css("padding-top"))+parseFloat(o.viewport.css("padding-bottom"))+parseFloat(o.viewport.css("border-top-width"))+parseFloat(o.viewport.css("border-bottom-width")):"padding-box"==o.viewport.css("box-sizing")&&(t+=parseFloat(o.viewport.css("padding-top"))+parseFloat(o.viewport.css("padding-bottom"))),t},h=function(){var e="100%";return o.settings.slideWidth>0&&(e="horizontal"==o.settings.mode?o.settings.maxSlides*o.settings.slideWidth+(o.settings.maxSlides-1)*o.settings.slideMargin:o.settings.slideWidth),e},g=function(){var e=o.settings.slideWidth,t=o.viewport.width();return 0==o.settings.slideWidth||o.settings.slideWidth>t&&!o.carousel||"vertical"==o.settings.mode?e=t:o.settings.maxSlides>1&&"horizontal"==o.settings.mode&&(t>o.maxThreshold||t<o.minThreshold&&(e=(t-o.settings.slideMargin*(o.settings.minSlides-1))/o.settings.minSlides)),e},v=function(){var e=1;if("horizontal"==o.settings.mode&&o.settings.slideWidth>0)if(o.viewport.width()<o.minThreshold)e=o.settings.minSlides;else if(o.viewport.width()>o.maxThreshold)e=o.settings.maxSlides;else{var t=o.children.first().width()+o.settings.slideMargin;e=Math.floor((o.viewport.width()+o.settings.slideMargin)/t)}else"vertical"==o.settings.mode&&(e=o.settings.minSlides);return e},m=function(){var e=0;if(o.settings.moveSlides>0)if(o.settings.infiniteLoop)e=Math.ceil(o.children.length/y());else for(var t=0,n=0;t<o.children.length;)++e,t=n+v(),n+=o.settings.moveSlides<=v()?o.settings.moveSlides:v();else e=Math.ceil(o.children.length/v());return e},y=function(){return o.settings.moveSlides>0&&o.settings.moveSlides<=v()?o.settings.moveSlides:v()},x=function(){if(o.children.length>o.settings.maxSlides&&o.active.last&&!o.settings.infiniteLoop){if("horizontal"==o.settings.mode){var e=o.children.last(),t=e.position();b(-(t.left-(o.viewport.width()-e.outerWidth())),"reset",0)}else if("vertical"==o.settings.mode){var n=o.children.length-o.settings.minSlides,t=o.children.eq(n).position();b(-t.top,"reset",0)}}else{var t=o.children.eq(o.active.index*y()).position();o.active.index==m()-1&&(o.active.last=!0),void 0!=t&&("horizontal"==o.settings.mode?b(-t.left,"reset",0):"vertical"==o.settings.mode&&b(-t.top,"reset",0))}},b=function(e,t,n,i){if(o.usingCSS){var r="vertical"==o.settings.mode?"translate3d(0, "+e+"px, 0)":"translate3d("+e+"px, 0, 0)";s.css("-"+o.cssPrefix+"-transition-duration",n/1e3+"s"),"slide"==t?(s.css(o.animProp,r),s.bind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd",function(){s.unbind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd"),M()})):"reset"==t?s.css(o.animProp,r):"ticker"==t&&(s.css("-"+o.cssPrefix+"-transition-timing-function","linear"),s.css(o.animProp,r),s.bind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd",function(){s.unbind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd"),b(i.resetValue,"reset",0),P()}))}else{var a={};a[o.animProp]=e,"slide"==t?s.animate(a,n,o.settings.easing,function(){M()}):"reset"==t?s.css(o.animProp,e):"ticker"==t&&s.animate(a,speed,"linear",function(){b(i.resetValue,"reset",0),P()})}},w=function(){for(var t="",n=m(),i=0;n>i;i++){var r="";o.settings.buildPager&&e.isFunction(o.settings.buildPager)?(r=o.settings.buildPager(i),o.pagerEl.addClass("bx-custom-pager")):(r=i+1,o.pagerEl.addClass("bx-default-pager")),t+='<div class="bx-pager-item"><a href="" data-slide-index="'+i+'" class="bx-pager-link">'+r+"</a></div>"}o.pagerEl.html(t)},S=function(){o.settings.pagerCustom?o.pagerEl=e(o.settings.pagerCustom):(o.pagerEl=e('<div class="bx-pager" />'),o.settings.pagerSelector?e(o.settings.pagerSelector).html(o.pagerEl):o.controls.el.addClass("bx-has-pager").append(o.pagerEl),w()),o.pagerEl.on("click","a",_)},E=function(){o.controls.next=e('<a class="bx-next" href="">'+o.settings.nextText+"</a>"),o.controls.prev=e('<a class="bx-prev" href="">'+o.settings.prevText+"</a>"),o.controls.next.bind("click",$),o.controls.prev.bind("click",k),o.settings.nextSelector&&e(o.settings.nextSelector).append(o.controls.next),o.settings.prevSelector&&e(o.settings.prevSelector).append(o.controls.prev),o.settings.nextSelector||o.settings.prevSelector||(o.controls.directionEl=e('<div class="bx-controls-direction" />'),o.controls.directionEl.append(o.controls.prev).append(o.controls.next),o.controls.el.addClass("bx-has-controls-direction").append(o.controls.directionEl))},T=function(){o.controls.start=e('<div class="bx-controls-auto-item"><a class="bx-start" href="">'+o.settings.startText+"</a></div>"),o.controls.stop=e('<div class="bx-controls-auto-item"><a class="bx-stop" href="">'+o.settings.stopText+"</a></div>"),o.controls.autoEl=e('<div class="bx-controls-auto" />'),o.controls.autoEl.on("click",".bx-start",W),o.controls.autoEl.on("click",".bx-stop",N),o.settings.autoControlsCombine?o.controls.autoEl.append(o.controls.start):o.controls.autoEl.append(o.controls.start).append(o.controls.stop),o.settings.autoControlsSelector?e(o.settings.autoControlsSelector).html(o.controls.autoEl):o.controls.el.addClass("bx-has-controls-auto").append(o.controls.autoEl),D(o.settings.autoStart?"stop":"start")},C=function(){o.children.each(function(t){var n=e(this).find("img:first").attr("title");void 0!=n&&(""+n).length&&e(this).append('<div class="bx-caption"><span>'+n+"</span></div>")})},$=function(e){o.settings.auto&&s.stopAuto(),s.goToNextSlide(),e.preventDefault()},k=function(e){o.settings.auto&&s.stopAuto(),s.goToPrevSlide(),e.preventDefault()},W=function(e){s.startAuto(),e.preventDefault()},N=function(e){s.stopAuto(),e.preventDefault()},_=function(t){o.settings.auto&&s.stopAuto();var n=e(t.currentTarget);if(void 0!==n.attr("data-slide-index")){var i=parseInt(n.attr("data-slide-index"));i!=o.active.index&&s.goToSlide(i),t.preventDefault()}},A=function(t){var n=o.children.length;return"short"==o.settings.pagerType?(o.settings.maxSlides>1&&(n=Math.ceil(o.children.length/o.settings.maxSlides)),void o.pagerEl.html(t+1+o.settings.pagerShortSeparator+n)):(o.pagerEl.find("a").removeClass("active"),void o.pagerEl.each(function(n,i){e(i).find("a").eq(t).addClass("active")}))},M=function(){if(o.settings.infiniteLoop){var e="";0==o.active.index?e=o.children.eq(0).position():o.active.index==m()-1&&o.carousel?e=o.children.eq((m()-1)*y()).position():o.active.index==o.children.length-1&&(e=o.children.eq(o.children.length-1).position()),e&&("horizontal"==o.settings.mode?b(-e.left,"reset",0):"vertical"==o.settings.mode&&b(-e.top,"reset",0))}o.working=!1,o.settings.onSlideAfter(o.children.eq(o.active.index),o.oldIndex,o.active.index)},D=function(e){o.settings.autoControlsCombine?o.controls.autoEl.html(o.controls[e]):(o.controls.autoEl.find("a").removeClass("active"),o.controls.autoEl.find("a:not(.bx-"+e+")").addClass("active"))},j=function(){1==m()?(o.controls.prev.addClass("disabled"),o.controls.next.addClass("disabled")):!o.settings.infiniteLoop&&o.settings.hideControlOnEnd&&(0==o.active.index?(o.controls.prev.addClass("disabled"),o.controls.next.removeClass("disabled")):o.active.index==m()-1?(o.controls.next.addClass("disabled"),o.controls.prev.removeClass("disabled")):(o.controls.prev.removeClass("disabled"),o.controls.next.removeClass("disabled")))},L=function(){if(o.settings.autoDelay>0){setTimeout(s.startAuto,o.settings.autoDelay)}else s.startAuto();o.settings.autoHover&&s.hover(function(){o.interval&&(s.stopAuto(!0),o.autoPaused=!0)},function(){o.autoPaused&&(s.startAuto(!0),o.autoPaused=null)})},H=function(){var t=0;if("next"==o.settings.autoDirection)s.append(o.children.clone().addClass("bx-clone"));else{s.prepend(o.children.clone().addClass("bx-clone"));var n=o.children.first().position();t="horizontal"==o.settings.mode?-n.left:-n.top}b(t,"reset",0),o.settings.pager=!1,o.settings.controls=!1,o.settings.autoControls=!1,o.settings.tickerHover&&!o.usingCSS&&o.viewport.hover(function(){s.stop()},function(){var t=0;o.children.each(function(n){t+="horizontal"==o.settings.mode?e(this).outerWidth(!0):e(this).outerHeight(!0)});var n=o.settings.speed/t,i="horizontal"==o.settings.mode?"left":"top",r=n*(t-Math.abs(parseInt(s.css(i))));P(r)}),P()},P=function(e){speed=e?e:o.settings.speed;var t={left:0,top:0},n={left:0,top:0};"next"==o.settings.autoDirection?t=s.find(".bx-clone").first().position():n=o.children.first().position();var i="horizontal"==o.settings.mode?-t.left:-t.top,r="horizontal"==o.settings.mode?-n.left:-n.top,a={resetValue:r};b(i,"ticker",speed,a)},F=function(){o.touch={start:{x:0,y:0},end:{x:0,y:0}},o.viewport.bind("touchstart",O)},O=function(e){if(o.working)e.preventDefault();else{o.touch.originalPos=s.position();var t=e.originalEvent;o.touch.start.x=t.changedTouches[0].pageX,o.touch.start.y=t.changedTouches[0].pageY,o.viewport.bind("touchmove",q),o.viewport.bind("touchend",I)}},q=function(e){var t=e.originalEvent,n=Math.abs(t.changedTouches[0].pageX-o.touch.start.x),i=Math.abs(t.changedTouches[0].pageY-o.touch.start.y);if(3*n>i&&o.settings.preventDefaultSwipeX?e.preventDefault():3*i>n&&o.settings.preventDefaultSwipeY&&e.preventDefault(),"fade"!=o.settings.mode&&o.settings.oneToOneTouch){var r=0;if("horizontal"==o.settings.mode){var s=t.changedTouches[0].pageX-o.touch.start.x;r=o.touch.originalPos.left+s}else{var s=t.changedTouches[0].pageY-o.touch.start.y;r=o.touch.originalPos.top+s}b(r,"reset",0)}},I=function(e){o.viewport.unbind("touchmove",q);var t=e.originalEvent,n=0;if(o.touch.end.x=t.changedTouches[0].pageX,o.touch.end.y=t.changedTouches[0].pageY,"fade"==o.settings.mode){var i=Math.abs(o.touch.start.x-o.touch.end.x);i>=o.settings.swipeThreshold&&(o.touch.start.x>o.touch.end.x?s.goToNextSlide():s.goToPrevSlide(),s.stopAuto())}else{var i=0;"horizontal"==o.settings.mode?(i=o.touch.end.x-o.touch.start.x,n=o.touch.originalPos.left):(i=o.touch.end.y-o.touch.start.y,n=o.touch.originalPos.top),!o.settings.infiniteLoop&&(0==o.active.index&&i>0||o.active.last&&0>i)?b(n,"reset",200):Math.abs(i)>=o.settings.swipeThreshold?(0>i?s.goToNextSlide():s.goToPrevSlide(),s.stopAuto()):b(n,"reset",200)}o.viewport.unbind("touchend",I)},z=function(t){if(o.initialized){var n=e(window).width(),i=e(window).height();(a!=n||l!=i)&&(a=n,l=i,s.redrawSlider(),o.settings.onSliderResize.call(s,o.active.index))}};return s.goToSlide=function(t,n){if(!o.working&&o.active.index!=t)if(o.working=!0,o.oldIndex=o.active.index,0>t?o.active.index=m()-1:t>=m()?o.active.index=0:o.active.index=t,o.settings.onSlideBefore(o.children.eq(o.active.index),o.oldIndex,o.active.index),"next"==n?o.settings.onSlideNext(o.children.eq(o.active.index),o.oldIndex,o.active.index):"prev"==n&&o.settings.onSlidePrev(o.children.eq(o.active.index),o.oldIndex,o.active.index),o.active.last=o.active.index>=m()-1,o.settings.pager&&A(o.active.index),o.settings.controls&&j(),"fade"==o.settings.mode)o.settings.adaptiveHeight&&o.viewport.height()!=f()&&o.viewport.animate({height:f()},o.settings.adaptiveHeightSpeed),o.children.filter(":visible").fadeOut(o.settings.speed).css({zIndex:0}),o.children.eq(o.active.index).css("zIndex",o.settings.slideZIndex+1).fadeIn(o.settings.speed,function(){e(this).css("zIndex",o.settings.slideZIndex),M()});else{o.settings.adaptiveHeight&&o.viewport.height()!=f()&&o.viewport.animate({height:f()},o.settings.adaptiveHeightSpeed);var i=0,r={left:0,top:0};if(!o.settings.infiniteLoop&&o.carousel&&o.active.last)if("horizontal"==o.settings.mode){var a=o.children.eq(o.children.length-1);r=a.position(),i=o.viewport.width()-a.outerWidth()}else{var l=o.children.length-o.settings.minSlides;r=o.children.eq(l).position()}else if(o.carousel&&o.active.last&&"prev"==n){var c=1==o.settings.moveSlides?o.settings.maxSlides-y():(m()-1)*y()-(o.children.length-o.settings.maxSlides),a=s.children(".bx-clone").eq(c);r=a.position()}else if("next"==n&&0==o.active.index)r=s.find("> .bx-clone").eq(o.settings.maxSlides).position(),o.active.last=!1;else if(t>=0){var u=t*y();r=o.children.eq(u).position()}if("undefined"!=typeof r){var d="horizontal"==o.settings.mode?-(r.left-i):-r.top;b(d,"slide",o.settings.speed)}}},s.goToNextSlide=function(){if(o.settings.infiniteLoop||!o.active.last){var e=parseInt(o.active.index)+1;s.goToSlide(e,"next")}},s.goToPrevSlide=function(){if(o.settings.infiniteLoop||0!=o.active.index){var e=parseInt(o.active.index)-1;s.goToSlide(e,"prev")}},s.startAuto=function(e){o.interval||(o.interval=setInterval(function(){"next"==o.settings.autoDirection?s.goToNextSlide():s.goToPrevSlide()},o.settings.pause),o.settings.autoControls&&1!=e&&D("stop"))},s.stopAuto=function(e){o.interval&&(clearInterval(o.interval),o.interval=null,o.settings.autoControls&&1!=e&&D("start"))},s.getCurrentSlide=function(){return o.active.index},s.getCurrentSlideElement=function(){return o.children.eq(o.active.index)},s.getSlideCount=function(){return o.children.length},s.redrawSlider=function(){o.children.add(s.find(".bx-clone")).width(g()),o.viewport.css("height",f()),o.settings.ticker||x(),o.active.last&&(o.active.index=m()-1),o.active.index>=m()&&(o.active.last=!0),o.settings.pager&&!o.settings.pagerCustom&&(w(),A(o.active.index))},s.destroySlider=function(){o.initialized&&(o.initialized=!1,e(".bx-clone",this).remove(),o.children.each(function(){void 0!=e(this).data("origStyle")?e(this).attr("style",e(this).data("origStyle")):e(this).removeAttr("style")}),void 0!=e(this).data("origStyle")?this.attr("style",e(this).data("origStyle")):e(this).removeAttr("style"),e(this).unwrap().unwrap(),o.controls.el&&o.controls.el.remove(),o.controls.next&&o.controls.next.remove(),o.controls.prev&&o.controls.prev.remove(),o.pagerEl&&o.settings.controls&&o.pagerEl.remove(),e(".bx-caption",this).remove(),o.controls.autoEl&&o.controls.autoEl.remove(),clearInterval(o.interval),o.settings.responsive&&e(window).unbind("resize",z))},s.reloadSlider=function(e){void 0!=e&&(r=e),s.destroySlider(),c()},c(),this}}(jQuery);