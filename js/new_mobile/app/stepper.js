(function(){window.EasyWay||(window.EasyWay={}),EasyWay.Stepper=function(){function e(){}return e.sliderOptions={slideSelector:".js-slide",infiniteLoop:!1,controls:!1,pagerSelector:".footer__stepper",oneToOneTouch:!1,slideMargin:20,buildPager:function(){return""},onSliderLoad:function(){var e,t;return e=EasyWay.Stepper.form.find(this.slideSelector).first(),t=e.next().attr("data-name"),EasyWay.Stepper.updateNextAction(t)},onSlideBefore:function(e,t,n){var i,r;return i=e.next(),r=i.length&&i.attr("data-name")||"Add to bag",EasyWay.Stepper.updateNextAction(r,"js-submit",!i.length)},onSlideNext:function(e,t){return EasyWay.Stepper.updateNavigationItem(t,!0)},onSlidePrev:function(e,t){return EasyWay.Stepper.updateNavigationItem(t,!1)}},e.create=function(e){return this.form=e,this.updateNextAction("","js-submit",!1),this.slider=this.form.bxSlider(this.sliderOptions)},e.destroy=function(){return this.sliderExist()?($(".bx-pager").remove(),this.slider.destroySlider()):void 0},e.sliderExist=function(){return this.slider&&this.slider.length},e.nextStep=function(){return this.sliderExist()?this.slider.goToNextSlide():void 0},e.updateNextAction=function(e,t,n){return null==t&&(t=""),null==n&&(n=!1),$(".footer__stepper-next").text(e).toggleClass(t,n)},e.updateNavigationItem=function(e,t){return $(".bx-pager-item a[data-slide-index='"+e+"']").toggleClass("done",t)},e.submitForm=function(){var e;return $(".open, .active").removeClass("open active"),this.destroy(),e=$("<span>",{text:"$49.50"}),$(".footer__stepper").html(e),this.updateNextAction("Place order","js-submit",!1),EasyWay.Cart.addItem(),EasyWay.Cart.open()},e}()}).call(this);