(function(){window.EasyWay||(window.EasyWay={}),EasyWay.Account=function(){function e(){}return e.defaultIndex=1,e.dictionary={},e.$accountWrapper=$(".account__wrapper"),e.$accountAddress=$("#delivery_addresses").find(".section__article").first(),e.$accountPaymentMethod=$("#payment_methods").find(".section__article").first(),e.$slides=$(".section:not(#account)"),e.setup=function(){return EasyWay.Header.collapse(),this.storeDictionary(),this.createSlider(),this.bindEvents()},e.createSlider=function(){return this.accountSlider=this.$accountWrapper.bxSlider({pageSelector:".section",pager:!1,controls:!1,infiniteLoop:!1,touchEnabled:!1,adaptiveHeight:!0,slideMargin:20,onSlideBefore:function(e,t,n){var i;return i=$("#"+EasyWay.Account.dictionary[t]+", #"+EasyWay.Account.dictionary[n]),EasyWay.Account.$slides.not(i).hide(),i.show()}})},e.storeDictionary=function(){var e,t,n,i,r,o;for(i=$(".section"),r=[],t=e=0,n=i.length;n>e;t=++e)o=i[t],r.push(this.storeSection(o,t));return r},e.storeSection=function(e,t){return this.dictionary[t]=e.id,this.dictionary[e.id]=t},e.goToStep=function(e){var t;return t=this.dictionary[e]||0,this.accountSlider.goToSlide(t)},e.bindEvents=function(){return this.$accountWrapper.on("click",".js-goto",function(e){return function(t){var n;return n=t.currentTarget.dataset.step,e.goToStep(n),!1}}(this)),this.$accountWrapper.on("click",".js-save-address",function(e){return function(){return e.$accountAddress.before(e.$accountAddress.clone()),e.goToStep("delivery_addresses")}}(this)),this.$accountWrapper.on("click",".js-save-payment-method",function(e){return function(){return e.$accountPaymentMethod.before(e.$accountPaymentMethod.clone()),e.goToStep("payment_methods")}}(this)),this.$accountWrapper.on("click",".js-update-password",function(e){return function(){return window.location.hash="#password-change",EasyWay.Notification.open()}}(this))},e}()}).call(this),function(){window.EasyWay||(window.EasyWay={}),EasyWay.Cart=function(){function e(){}return e.$cartTag=$("aside.cart"),e.$cartItemTag=e.$cartTag.find(".cart-item:first"),e.$cartTotalTag=e.$cartTag.find(".cart__total"),e.$cartItemListTag=e.$cartTag.find(".cart-item__list"),e.$cartEmpty=e.$cartTag.find(".cart-empty"),e.setup=function(){return this.emptyCart(),this.bindEvents()},e.open=function(){return this.$cartTag.addClass("open")},e.close=function(){return this.$cartTag.removeClass("open")},e.bindEvents=function(){return this.$cartTag.on("click",".cart__title",function(e){return function(){return e.close()}}(this)),this.$cartTag.on("click",".cart__place-order",function(e){return function(){return e.goToCheckout()}}(this))},e.addItem=function(){return this.fillCart(),this.$cartItemListTag.append(this.$cartItemTag.clone())},e.emptyCart=function(){return this.$cartItemListTag.html(""),this.toggleCartContent(!0)},e.fillCart=function(){return this.toggleCartContent(!1)},e.toggleCartContent=function(e){return this.$cartTotalTag.toggle(!e),this.$cartItemListTag.toggle(!e),this.$cartEmpty.toggle(e)},e.goToCheckout=function(){return window.location.hash="#login",EasyWay.Notification.open()},e}()}.call(this),function(){window.EasyWay||(window.EasyWay={}),EasyWay.Checkout=function(){function e(){}return e.$footerTag=$(".footer"),e.activeForm=$("#checkout-form"),e.$deliveryOptions=$('input[name="delivery"]'),e.$methodOptions=$('input[name="method"]'),e.setup=function(){return EasyWay.Header.collapse(),this.overrideSliderOptions(),this.createStepper(),this.activeForm.toggleClass("open"),this.bindEvents()},e.overrideSliderOptions=function(){return EasyWay.Stepper.sliderOptions.slideSelector=".checkout__step",EasyWay.Stepper.sliderOptions.adaptiveHeight=!0},e.createStepper=function(){return EasyWay.Stepper.create(this.activeForm)},e.bindEvents=function(){return this.$footerTag.on("click",".footer__stepper-next",function(e){return function(t){var n;return n=$(t.currentTarget).hasClass("js-submit"),n&&e.submitForm()||EasyWay.Stepper.nextStep()}}(this)),this.$deliveryOptions.on("change",function(){return $(".bx-viewport").height("100%"),$("#pickup, #delivery").toggleClass("checkout__hidden")}),this.$methodOptions.on("change",function(){return $("#cash, #credit_card").toggleClass("checkout__hidden")})},e.submitForm=function(){return window.location="/thanks.html"},e}()}.call(this),function(){window.EasyWay||(window.EasyWay={}),EasyWay.Favorites=function(){function e(){}return e.dictionary={},e.$favoritesWrapper=$(".favorites__wrapper"),e.$footerAction=$(".footer__stepper-next"),e.setup=function(){return EasyWay.Header.collapse(),this.storeDictionary(),this.createSlider(),this.setFooterText(),this.bindEvents()},e.createSlider=function(){return this.accountSlider=this.$favoritesWrapper.bxSlider({pageSelector:".section",pager:!1,controls:!1,infiniteLoop:!1,touchEnabled:!1,adaptiveHeight:!0,slideMargin:20,onSlideBefore:function(e,t,n){return EasyWay.Favorites.enableFooter(n)}})},e.storeDictionary=function(){var e,t,n,i,r,o;for(i=$(".section"),r=[],t=e=0,n=i.length;n>e;t=++e)o=i[t],r.push(this.storeSection(o,t));return r},e.storeSection=function(e,t){return this.dictionary[e.id]=t},e.goToStep=function(e){var t;return t=this.dictionary[e]||0,this.accountSlider.goToSlide(t)},e.bindEvents=function(){return this.$favoritesWrapper.on("click",".js-goto",function(e){return function(t){var n;return n=t.currentTarget.dataset.step,e.goToStep(n),!1}}(this)),this.$footerAction.on("click",function(){return window.location.href="/checkout.html"})},e.setFooterText=function(){return this.$footerAction.text("Order now")},e.enableFooter=function(e){return $("footer.footer").toggle(e)},e.toggleEmpty=function(){return $("#favorites .section__article").toggle()},e}()}.call(this),function(){window.EasyWay||(window.EasyWay={}),EasyWay.Footer=function(){function e(){}return e.$footerTag=$(".footer"),e.$footerActionTag=$(".footer__stepper-next"),e.isLastStep=function(){return this.$footerActionTag.hasClass("js-submit")},e.submit=function(e){return this.isLastStep()&&e()},e.updateActionText=function(e){return this.$footerActionTag.text(e)},e.show=function(){return this.$footerTag.show()},e.hide=function(){return this.$footerTag.hide()},e}()}.call(this),function(){window.EasyWay||(window.EasyWay={}),EasyWay.GroupOrders=function(){function e(){}return e.activeForm=$("#group-orders-form"),e.$newOrder=$(".js-new-group-order"),e.$respondOrder=$(".js-respond-group-order"),e.setup=function(){return this.overrideSliderOptions(),this.bindEvents()},e.overrideSliderOptions=function(){return EasyWay.Stepper.sliderOptions.slideSelector=".js-step",EasyWay.Stepper.sliderOptions.adaptiveHeight=!0},e.createStepper=function(){return EasyWay.Stepper.create(this.activeForm)},e.bindEvents=function(){return this.$newOrder.on("click",function(e){return function(){return EasyWay.Footer.show(),e.toggleNewOrder(),e.createStepper()}}(this)),this.$respondOrder.on("click",function(e){return function(){return window.location.href="/#make-order"}}(this)),EasyWay.Footer.$footerActionTag.on("click",function(e){return function(t){return EasyWay.Footer.submit(e.submitForm)||EasyWay.Stepper.nextStep()}}(this))},e.submitForm=function(){return window.location="/#order-sent"},e.toggleNewOrder=function(){return $(".section").toggle()},e}()}.call(this),function(){window.EasyWay||(window.EasyWay={}),EasyWay.Header=function(){function e(){}return e.$headerTag=$("header.header"),e.collapse=function(){return this.$headerTag.addClass("collapsed")},e.expand=function(){return this.$headerTag.removeClass("collapsed")},e.bindEvents=function(){return this.$headerTag.on("click",".header__cart",function(e){return function(){return e.openCart(),!1}}(this)),this.$headerTag.on("click",".header__menu-icon",function(e){return function(){return e.openMainMenu(),!1}}(this))},e.openCart=function(){return EasyWay.Cart.open(),EasyWay.MainMenu.close()},e.openMainMenu=function(){return EasyWay.MainMenu.open(),EasyWay.Cart.close()},e}()}.call(this),function(){window.EasyWay||(window.EasyWay={}),EasyWay.MainMenu=function(){function e(){}return e.$mainMenuTag=$(".main-menu"),e.setup=function(){return this.bindEvents()},e.open=function(){return this.$mainMenuTag.addClass("open")},e.close=function(){return this.$mainMenuTag.removeClass("open")},e.bindEvents=function(){return this.$mainMenuTag.on("click",".main-menu__title",function(e){return function(){return e.close()}}(this))},e}()}.call(this),function(){window.EasyWay||(window.EasyWay={}),EasyWay.Menu=function(){function e(){}return e.$menuTag=$(".main__container > .menu"),e.$menuItemTag=$(".menu-item"),e.$quantity=$(".menu-item__quantity"),e.$footerTag=$(".footer"),e.bindEvents=function(){return this.$menuTag.on("click",".menu__header",function(e){return function(t){return e.activeMenu=$(t.currentTarget).next(".menu__content"),e.collapseHeader(),e.closeAllForms(),e.closeAllMenues(),e.activeMenu.toggleClass("open")}}(this)),this.$menuItemTag.on("click",".menu-item__header",function(e){return function(t){return e.activeForm=$(t.currentTarget).next(".menu-item__form"),e.closeAllForms(),e.activeForm.toggleClass("open"),e.createStepper()}}(this)),this.$quantity.on("click",".menu-item__quantity-update",function(e){return function(t){var n;return n=$(t.currentTarget).hasClass("js-minus"),e.activeQuantity=$(t.delegateTarget).find(".menu-item__quantity-input"),n&&e.decreaseQuantity()||e.increaseQuantity()}}(this)),this.$footerTag.on("click",".footer__stepper-next",function(e){return function(t){var n;return n=$(t.currentTarget).hasClass("js-submit"),n&&e.submitForm()||e.nextStep()}}(this))},e.closeAllMenues=function(){return this.$menuTag.find(".menu__content").not(this.activeMenu).removeClass("open")},e.closeAllForms=function(){return this.destroyStepper(),this.$menuItemTag.find(".menu-item__form").not(this.activeForm).removeClass("open")},e.collapseHeader=function(){return EasyWay.Header.collapse()},e.decreaseQuantity=function(){return EasyWay.Quantity.decrease(this.activeQuantity)},e.increaseQuantity=function(){return EasyWay.Quantity.increase(this.activeQuantity)},e.createStepper=function(){return EasyWay.Stepper.create(this.activeForm)},e.destroyStepper=function(){return EasyWay.Stepper.destroy()},e.nextStep=function(){return EasyWay.Stepper.nextStep()},e.submitForm=function(){return EasyWay.Stepper.submitForm()},e}()}.call(this),function(){window.EasyWay||(window.EasyWay={}),EasyWay.Notification=function(){function e(){}return e.$overlay=$("<div>",{"class":"notification__overlay"}),e.open=function(){var e,t;return e=window.location.hash,t=$(e),t.length?(this.close(),this.bindEvents(),this.addBackground(),$(t).show(),"#out-of-delivery"===e?this.addMap():void 0):void 0},e.bindEvents=function(){return $(".notification__box-action").one("click",function(e){return function(){return e.close()}}(this)),this.$overlay.on("click",function(e){return function(){return e.close()}}(this))},e.close=function(){return this.removeBackground(),$(".notification").hide(),!1},e.addBackground=function(){return $("body").prepend(this.$overlay),this.$overlay.show()},e.removeBackground=function(){return this.$overlay.hide()},e.addMap=function(){var e,t;return e=document.getElementById("map"),t={center:new google.maps.LatLng(44.5403,-78.5463),zoom:8,mapTypeId:google.maps.MapTypeId.ROADMAP},new google.maps.Map(e,t)},e}()}.call(this),function(){window.EasyWay||(window.EasyWay={}),EasyWay.Quantity=function(){function e(){}return e.decrease=function(e){return e.val(function(e,t){return t-1&&--t||t})},e.increase=function(e){return e.val(function(e,t){return++t})},e.reset=function(e){return e.val(1)},e}()}.call(this),function(){window.EasyWay||(window.EasyWay={}),EasyWay.Sign=function(){function e(){}return e.setup=function(){return $(".js-sign").on("click",function(e){return function(e){return window.location.hash=e.currentTarget.hash,EasyWay.Notification.open()}}(this)),$(".js-continue-checkout").on("click",function(){return window.location.href="/checkout.html"})},e}()}.call(this),function(){window.EasyWay||(window.EasyWay={}),EasyWay.Stepper=function(){function e(){}return e.sliderOptions={slideSelector:".js-slide",infiniteLoop:!1,controls:!1,pagerSelector:".footer__stepper",oneToOneTouch:!1,slideMargin:20,buildPager:function(){return""},onSliderLoad:function(){var e,t;return e=EasyWay.Stepper.form.find(this.slideSelector).first(),t=e.next().attr("data-name"),EasyWay.Stepper.updateNextAction(t)},onSlideBefore:function(e,t,n){var i,r;return i=e.next(),r=i.length&&i.attr("data-name")||"Add to bag",EasyWay.Stepper.updateNextAction(r,"js-submit",!i.length)},onSlideNext:function(e,t){return EasyWay.Stepper.updateNavigationItem(t,!0)},onSlidePrev:function(e,t){return EasyWay.Stepper.updateNavigationItem(t,!1)}},e.create=function(e){return this.form=e,this.updateNextAction("","js-submit",!1),this.slider=this.form.bxSlider(this.sliderOptions)},e.destroy=function(){return this.sliderExist()?($(".bx-pager").remove(),this.slider.destroySlider()):void 0},e.sliderExist=function(){return this.slider&&this.slider.length},e.nextStep=function(){return this.sliderExist()?this.slider.goToNextSlide():void 0},e.updateNextAction=function(e,t,n){return null==t&&(t=""),null==n&&(n=!1),$(".footer__stepper-next").text(e).toggleClass(t,n)},e.updateNavigationItem=function(e,t){return $(".bx-pager-item a[data-slide-index='"+e+"']").toggleClass("done",t)},e.submitForm=function(){var e;return $(".open, .active").removeClass("open active"),this.destroy(),e=$("<span>",{text:"$49.50"}),$(".footer__stepper").html(e),this.updateNextAction("Place order","js-submit",!1),EasyWay.Cart.addItem(),EasyWay.Cart.open()},e}()}.call(this),function(){window.EasyWay||(window.EasyWay={}),EasyWay.ThemeSelector=function(){function e(){}return e.$styleTag=$("head > #theme"),e.appendSelector=function(e){var t,n,i,r;for(this.themes=e,this.$select=$("<select>",{id:"theme-select",style:"position: absolute; top: 5px; left: 5px;"}),i=Object.keys(this.themes),t=0,n=i.length;n>t;t++)r=i[t],this.appendOption(r);return $("body").append(this.$select),this.bindEvents()},e.appendOption=function(e){var t;return t=$("<option>",{value:this.themes[e],text:e}),this.$select.append(t)},e.bindEvents=function(){return this.$select.on("change",function(){return EasyWay.ThemeSelector.$styleTag.attr("href","/stylesheets/"+this.value)})},e}()}.call(this),function(){}.call(this);